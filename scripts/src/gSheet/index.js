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

    //console.log(rows.slice(1).length);


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
        if (header === 'Job ID') {
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
          let id = oldRow["Org Job Id"];
          let newRow = jsonData.find((newRow) => newRow["Org Job Id"] === id);

          if (!newRow) {
            console.log('Row deleted:', oldRow["Org Job Id"]);

            let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
            try {
              await queries.deleteJob(OrgaId, oldRow["Org Job Id"],);

            } catch (err) {
              console.error(`Error in job with ID ${oldRow["Org Job Id"]}:`, err);
            }

          }
        }

        // Update & add rows
        for (let i in jsonData) {
          let newRow = jsonData[i];
          let id = newRow["Org Job Id"];
          let oldRow = old_File_Parsed.find((j) => j["Org Job Id"] === id);


          if (oldRow) {
            if (_.isEqual(newRow, oldRow)) {
              continue;
            } else {
              console.log('Row updated:', newRow["Org Job Id"]);
              // update data in the database
              // get the organization ID
              let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];

              await queries.updateJob(OrgaId, newRow);

            }
          } else {
            console.log('Row added:', newRow["Org Job Id"]);

            // insert data in the database
            // get the organization ID
            let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
            try {
              await queries.insertJob(OrgaId, newRow);

            } catch (err) {
              console.error(`Error add job with ID ${OrgaId}:`, err);
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


        } catch (err) {
          console.error(`Error in job with ID ${job["Org Job Id"]}:`, err);
        }
      }
    }



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
        || spreadsheet.id === "1DxvWVsTSEdBaMfNLQ8aZ9uy0OCEosRnh7b6vd3iAPtA"
      ) {
        console.log("skip this");
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
        '',
        'Clinical',
        'Contract',
        'RN',
        'Peds CVICU',
        50,
        2.899,
        36,
        'TX',
        'Austin',
        0,
        0,
        12,
        3,
        13,
        'ASAP',
        'ASAP',
        'Not Allowed',
        'Time and a half',
        "25n",
        "25n",
        50,
        1.800,
        1.099,
        81,
        116,
        1,
        0,
        1.2,
        37.687,
        1.507,
        39.194,
        'Weekly',
        'options',
        'options',
        'Morocco',
        "St. David's North Austin",
        'HCA',
        'Up to 3 shifts per 13 week assignment can be canceled with no guaranteed pay for any of those 3 canceled shifts',
        '2 weeks of guaranteed pay unless canceled for cause',
        '75 miles',
        'TX',
        'Options',
        'description',
        'no auto offer',
        '3 years',
        "N_references",
        'Peds CVICU RN Skills checklist',
        'yes',
        'not allowed',
        'yes',
        3,
        'Options',
        "m",                    //Unit
        'w-2',
        'Options',
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

    await processAllSpreadsheets(auth);

    const id_for_add = "1IWv1voLSTzIRWZkBj4wB0PyYAQdU0-nOQ4Y28wY9xC4"
    //await addDataToSpreadsheet(auth, id_for_add);

    //await deleteAllSpreadsheets(auth);

    const idd_for_delete = "1IWv1voLSTzIRWZkBj4wB0PyYAQdU0-nOQ4Y28wY9xC4"
    //await deleteSpreadsheetById(auth, idd_for_delete);

    const liste_id_to_delete = [
      "1IWv1voLSTzIRWZkBj4wB0PyYAQdU0-nOQ4Y28wY9xC4",
      "1IWv1voLSTzIRWZkBj4wB0PyYAQdU0-nOQ4Y28wY9xC4",
    ]
    //liste_id_to_delete.forEach(id => { deleteSpreadsheetById(auth, id);});
  } catch (err) {
    console.error('Error in main execution:', err.message);
  }
}

main().catch(console.error);

module.exports = { authorize, main };

