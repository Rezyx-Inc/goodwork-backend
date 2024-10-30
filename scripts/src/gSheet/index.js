const { google } = require('googleapis');
const { authenticate } = require('@google-cloud/local-auth');
const path = require('path');
const fs = require('fs');
const sha256 = require('sha256');
const { lineUpdated, lineAdded, lineDeleted } = require('./funcSheet');
var _ = require('lodash');
const { json } = require('body-parser');
const { exit } = require('process');

const queries = require("../mysql/sheet.js");

const SCOPES = [
  'https://www.googleapis.com/auth/spreadsheets',
  'https://www.googleapis.com/auth/drive.file',
  'https://www.googleapis.com/auth/drive'
];


const TOKEN_PATH = path.join(__dirname, 'token.json');
const CREDENTIALS_PATH = path.join(__dirname, 'credentials.json');

async function loadSavedCredentialsIfExist() {

  try {

    const content = await fs.promises.readFile(TOKEN_PATH);
    const credentials = JSON.parse(content);
    return google.auth.fromJSON(credentials);

  } catch (err) {

    console.error('Error loading saved credentials:', err.message);
    return null;

  }
}

async function saveCredentials(client) {

  try {

    const content = await fs.promises.readFile(CREDENTIALS_PATH);
    const keys = JSON.parse(content);
    const key = keys.installed || keys.web;

    const payload = JSON.stringify({

      type: 'authorized_user',
      client_id: key.client_id,
      client_secret: key.client_secret,
      refresh_token: client.credentials.refresh_token,

    });

    await fs.promises.writeFile(TOKEN_PATH, payload);

  } catch (err) {

    console.error('Error saving credentials:', err.message);

  }
}

async function authorize() {

  try {

    let client = await loadSavedCredentialsIfExist();
    if (client) {
      return client;
    }
    client = await authenticate({
      scopes: SCOPES,
      keyfilePath: CREDENTIALS_PATH,
    });
    if (client.credentials) {
      await saveCredentials(client);
    }
    return client;
  } catch (err) {
    console.error('Error during authorization:', err.message);
    throw err;
  }
}


async function getDataAndSaveAsJson(auth, spreadsheetId, spreadsheetName) {
  try {


    const sheets = google.sheets({ version: 'v4', auth });
    const res = await sheets.spreadsheets.values.get({
      spreadsheetId: spreadsheetId,
      range: `Sheet1!A:BH`,
      auth: auth,
    });


    // Access the rows from the values property
    var rows = res.data.values;

    if (!rows || rows.length === 0) {
      console.log('No data found.');
      return;
    }
    const headers = rows.shift();

    // Transform the data into an array of objects
    const jsonData = rows.map(row => {
      const rowObject = {};

      headers.forEach((header, index) => {
        let value = row[index] || '';

        // Check if the value is fully numeric
        const isNumeric = /^\d+$/.test(value); // Regular expression to check if the value is all digits

        // Check if the value is boolean (case insensitive)
        const isBoolean = value.toLowerCase() === 'true' || value.toLowerCase() === 'false';

        // Assign based on type
        // Skip conversion for 'job_id' and assign it directly as a string
        if (header === 'job_id') {
          rowObject[header] = value; // Keep 'job_id' as a string
        } else if (isNumeric) {
          rowObject[header] = parseFloat(value); // Convert to number if fully numeric
        } else if (isBoolean) {
          rowObject[header] = value.toLowerCase() === 'true'; // Convert to boolean (true or false)
        } else {
          rowObject[header] = value; // Keep as string for all other cases
        }
      });

      return rowObject;
    });





    // Prepare filename
    const fileName = `${spreadsheetName.replace(/[<>:"/\\|?*]/g, '_')}.json`;
    const folderPath = path.join(__dirname, 'jsons');
    const filePath = path.join(folderPath, fileName);

    if (!fs.existsSync(folderPath)) {
      fs.mkdirSync(folderPath);
    }

    // Check if the JSON file already exists
    const existFile = fs.existsSync(filePath);

    if (existFile) {
      // Read the existing file
      const oldFile = await fs.promises.readFile(filePath, 'utf8');
      const old_File_Parsed = JSON.parse(oldFile);

      if (_.isEqual(old_File_Parsed, jsonData)) {
        console.log('No changes');

      } else {
        // Delete rows
        for (let i in old_File_Parsed) {
          let oldRow = old_File_Parsed[i];
          let id = oldRow.job_id;
          let newRow = jsonData.find((newRow) => newRow.job_id === id);

          if (!newRow) {
            console.log('Row deleted:', oldRow.job_id);

            let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
            try {
              await queries.deleteJob(OrgaId, oldRow.job_id);

            } catch (err) {
              console.error(`Error in job with ID ${job.job_id}:`, err);
            }

          }
        }

        // Update & add rows
        for (let i in jsonData) {
          let newRow = jsonData[i];
          let id = newRow.job_id;
          let oldRow = old_File_Parsed.find((j) => j.job_id === id);


          if (oldRow) {
            if (_.isEqual(newRow, oldRow)) {
              continue;
            } else {
              console.log('Row updated:', newRow.job_id);
              // update data in the database
              // get the organization ID
              let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];

              for (const job of jsonData) {
                try {
                  await queries.updateJob(OrgaId, job);

                } catch (err) {
                  console.error(`Error in job with ID ${job.job_id}:`, err);
                }
              }
            }
          } else {
            console.log('Row added:', newRow.job_id);

            // insert data in the database
            // get the organization ID
            let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
            try {
              await queries.insertJob(OrgaId, newRow);

            } catch (err) {
              console.error(`Error in job with ID ${job.job_id}:`, err);
            }

          }
        }



        // Save the updated data
        await fs.promises.writeFile(filePath, JSON.stringify(jsonData, null, 2));
        console.log(`Updated data saved to ${filePath}`);
      }
    } else {

      // Save the JSON data to a new file in the jsons folder
      await fs.promises.writeFile(filePath, JSON.stringify(jsonData, null, 2));
      console.log(`Data saved to ${filePath}`);


      // insert data in the database
      // get the organization ID
      let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];

      for (const job of jsonData) {
        try {
          await queries.insertJob(OrgaId, job);

          // Extract the numeric part from OrgaId (e.g., "000002")
          let numericPart = OrgaId.substring(3); // Get "000002"

          // Increment the numeric part
          let newNumber = (parseInt(numericPart) + 1).toString();

          // Add leading zeros back based on the length of the new number
          OrgaId = `GWU${newNumber.padStart(6, '0')}`;

        } catch (err) {
          console.error(`Error in job with ID ${job.job_id}:`, err);
        }
      }
    }


    // let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
    // for (const job of jsonData) {
    //   try {
    //     await queries.insertJob(OrgaId, job);
    //     OrgaId += 1;
    //   } catch (err) {
    //     console.error(`Error in job with ID ${job.job_id}:`, err);
    //   }
    // }

    // update data in the database
    // let getOgrId = 1;
    // for (const job of jsonData) {
    //   try {
    //     await queries.updateJob(getOgrId, job);
    //     getOgrId += 1;
    //   } catch (err) {
    //     console.error(`Error in job with ID ${job.job_id}:`, err);
    //   }
    // }

  } catch (err) {
    console.error('Error fetching or saving data:', err);
  }
}






// write a function to get all spread sheet ids
async function listSpreadsheetIds(auth) {
  const drive = google.drive({ version: 'v3', auth });
  try {
    const res = await drive.files.list({
      q: "mimeType='application/vnd.google-apps.spreadsheet'",
      fields: 'files(id, name)',
    });

    const files = res.data.files;
    if (files.length) {
      return files;
    } else {
      console.log('No spreadsheets found.');
      return [];
    }
  } catch (err) {
    console.error('Error listing spreadsheets:', err.message);
    throw err;
  }
}

async function processAllSpreadsheets(auth) {
  try {
    // Step 1: Get all spreadsheet IDs
    const spreadsheets = await listSpreadsheetIds(auth);

    if (spreadsheets.length === 0) {
      console.log('No spreadsheets to process.');
      return;
    }

    // Step 2: Loop through each spreadsheet and get the data
    for (const spreadsheet of spreadsheets) {
      // skip a specific spreadsheet
      if (spreadsheet.id === "1Q5e9vVb1dApdBkoeg8rqwwWlNJEk51SV7W36qBeuP_c"
        || spreadsheet.id === "1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms"
        || spreadsheet.id === "1DoTKZgapb-OHHDpDOI1FSDuGfs1D0SlmMKp55KJZk24"
      ) {
        //  console.log("skip this");
        exit
      } else {
        console.log(`Processing spreadsheet: ${spreadsheet.name} (${spreadsheet.id})`);

        // Call your function to get data and save it as JSON
        await getDataAndSaveAsJson(auth, spreadsheet.id, spreadsheet.name);
      }

    }
  } catch (err) {
    console.error('Error processing spreadsheets:', err.message);
  }
}

// Function to delete all spreadsheets
async function deleteAllSpreadsheets(auth) {
  const drive = google.drive({ version: 'v3', auth });
  try {
    // Step 1: List all spreadsheets
    const res = await drive.files.list({
      q: "mimeType='application/vnd.google-apps.spreadsheet'",
      fields: 'files(id, name)',
    });

    const files = res.data.files;

    if (files.length === 0) {
      console.log('No spreadsheets found to delete.');
      return;
    }

    // Step 2: Loop through each spreadsheet and delete it
    for (const file of files) {
      await drive.files.delete({
        fileId: file.id,
      });
      console.log(`Deleted spreadsheet: ${file.name} (${file.id})`);
    }
  } catch (err) {
    console.error('Error deleting spreadsheets:', err.message);
  }
}

// Function to delete a spreadsheet by ID
async function deleteSpreadsheetById(auth, spreadsheetId) {
  const drive = google.drive({ version: 'v3', auth });
  try {
    await drive.files.delete({
      fileId: spreadsheetId,
    });
    console.log(`Deleted spreadsheet with ID: ${spreadsheetId}`);
  } catch (err) {
    console.error(`Error deleting spreadsheet with ID ${spreadsheetId}:`, err.message);
  }
}


// Function to add data to the Google Sheet
async function addDataToSpreadsheet(auth, idForAdd) {
  try {
    const sheets = google.sheets({ version: 'v4', auth });

    // Example data to insert
    const values = [
      [
        'N12345',                            // job_id
        'ICU Nurse',                         // job_name
        'New York',                          // job_city
        'Travel Nurse',                      // job_type
        'Contract',                          // type
        'NY',                                // job_state
        1500,                                // weekly_pay
        "n555",                                // preferred_specialty
        true,                                // active (assuming true; change if needed)
        'Responsible',                       // description
        '2024-11-01',                        // start_date
        12,                                  // hours_shift
        36,                                  // hours_per_week
        2,                                   // preferred_experience
        '24-hour notice for cancellations',  // facility_shift_cancelation_policy
        '30 miles',                          // traveler_distance_from_facility
        'Critical Care',                     // clinical_setting
        '1:4',                               // Patient_ratio
        'N/A',                               // Unit
        'Blue',                              // scrub_color

        'None',                              // rto
        'Guaranteed',                        // guaranteed_hours
        '4 weeks',                           // weeks_shift
        '500n',                               // referral_bonus
        '2000n',                              // sign_on_bonus
        '1500n',                              // completion_bonus
        '1000n',                              // extension_bonus
        '750n',                               // other_bonus
        "40nn",                                  // actual_hourly_rate
        'Time and a half',                   // overtime
        'No holiday work required',          // holiday
        'Orientation pay at 50/hr',         // orientation_rate
        'Yes',                               // on_call
        "25n",                                  // on_call_rate
        "25n",                                  // call_back_rate
        "25n",                                 // weekly_non_taxable_amount
        'Registered Nurse',                   // profession
        'Contract',                          // terms
        '1 month',                           // preferred_assignment_duration

        'Flexible',                          // block_scheduling
        '2 weeks notice',                    // contract_termination_policy
        'EMR example',                       // emr
        'City General Hospital',             // job_location
        'Required',                          // vaccinations
        3,                                   // number_of_references
        'Manager',                           // min_title_of_reference
        true,                                // eligible_work_in_us
        1,                                   // recency_of_reference
        'BLS Certification',                 // certificate
        '1 week',                            // preferred_shift_duration
        'IV Certification, ACLS',            // skills
        'Immediate',                         // urgency
        'Healthcare System A',               // facilitys_parent_system
        'City General',                      // facility_name
        'RN',                                // nurse_classification
        'Biweekly',                          // pay_frequency
        'Health Insurance, 401k',           // benefits
        '30n',                                // feels_like_per_hour
        2,                                   // as_soon_as

        'NY RN License',                     // professional_state_licensure
      ],
    ];



    const resource = {
      values,
    };

    const res = await sheets.spreadsheets.values.append({
      spreadsheetId: idForAdd,
      range: `Sheet1!A:BH`, // Adjust this range based on the number of fields
      valueInputOption: 'RAW',
      insertDataOption: 'INSERT_ROWS',
      resource,
    });

    console.log(`${res.data.updates.updatedCells} cells appended.`);
  } catch (err) {
    console.error('Error adding data:', err.message);
  }
}


async function main() {
  try {
    const auth = await authorize();

    //await processAllSpreadsheets(auth);

    const id_for_add = "1P4PxtT-S6c42-jGHz-Mo2jpV1dYmCfRi2-a4aO3JNHk"
    //await addDataToSpreadsheet(auth , id_for_add);

    //await deleteAllSpreadsheets(auth);

    const idd_for_delete = "1YkzbHYkDc8EVTL2ED8aFQ5r1-p37JjeXgD4KWZM-hK0"
    await deleteSpreadsheetById(auth , idd_for_delete);


  } catch (err) {
    console.error('Error in main execution:', err.message);
  }
}

main().catch(console.error);

module.exports = { authorize, main };

