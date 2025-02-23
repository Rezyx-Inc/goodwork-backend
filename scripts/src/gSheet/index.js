//Import all required libraries and/or modules
const { google } = require('googleapis');
const path = require('path');
const fs = require('fs');
const sha256 = require('sha256');
const { authenticate } = require('@google-cloud/local-auth');
var _ = require('lodash');
const { json } = require('body-parser');
const { exit } = require('process');
const { deleteAllSpreadsheets, deleteSpreadsheetById, addDataToSpreadsheet } = require('./crud/crud.js');
const csv = require('csvtojson');
const queries = require("../mysql/sheet.js");
const { addJobsWithLocalData, addJobsFromPublicSheet, addJobsFromLinkWithAuth , deleteAllJobs} = require('./funcs/functionsGsheet.js');

const axios = require('axios');
// const { authorize } = require('./services/authService');


const SCOPES = [
  'https://www.googleapis.com/auth/spreadsheets',
  'https://www.googleapis.com/auth/drive.file',
  'https://www.googleapis.com/auth/drive'
];


const TOKEN_PATH = path.join(__dirname, 'token.json');
const CREDENTIALS_PATH = path.join(__dirname, 'credentials.json');

async function loadSavedCredentialsIfExist() {

  try {

    //Read and use the access toke and credentials
    const content = await fs.promises.readFile(TOKEN_PATH);
    const credentials = JSON.parse(content);
    return google.auth.fromJSON(credentials);

  } catch (err) {

    //Log and return the error message
    console.error('Error loading saved credentials:', err.message);
    return null;

  }
}

//Function to save the client credentials
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

    //Write credentials to a file, as a payload
    await fs.promises.writeFile(TOKEN_PATH, payload);

  } catch (err) {

    console.error('Error saving credentials:', err.message);

  }
}

//Function to authorize the credentials
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

    //Check if credentials are received
    if (client.credentials) {
      await saveCredentials(client);
    }
    return client;
  } catch (err) {

    //Log and return the error message
    console.error('Error during authorization:', err.message);
    throw err;
  }
}

//Function to fetch credentials' data from the spreadsheet and save as json
async function getDataAndSaveAsJson(auth, spreadsheetId, spreadsheetName) {
  try {


    // const sheets = google.sheets({ version: 'v4', headers: { Authorization: `Bearer ${auth}` } });
    // const res = await sheets.spreadsheets.values.get({
    //   spreadsheetId: spreadsheetId,
    //   range: `Sheet1!A:BH`,
    //   // auth: auth,
    // });

    // // Access the rows from the values property
    // var rows = res.data.values;

    // //console.log(rows.slice(1).length);

    // if (!rows || rows.length === 0) {
    //   console.log('No data found.');
    //   return;
    // }
    // const headers = rows.shift();

    // // Transform the data into an array of objects
    // const jsonData = rows.map(row => {
    //   const rowObject = {};

    //   headers.forEach((header, index) => {
    //     let value = row[index] || '';

    //     // Check if the value is fully numeric
    //     const isNumeric = /^\d+$/.test(value);

    //     // Check if the value is boolean (case insensitive)
    //     const isBoolean = value.toLowerCase() === 'true' || value.toLowerCase() === 'false';

    //     // Check if the value is in "M/D/YYYY", "MM/DD/YYYY", "M-D-YYYY", or "MM-DD-YYYY" format
    //     const isDateFormat = /^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/.test(value);

    //     // Assign based on type
    //     if (header === 'Job ID') {
    //       rowObject[header] = value; // Keep 'job_id' as a string
    //     } else if (isNumeric) {
    //       rowObject[header] = parseFloat(value); // Convert to number if fully numeric
    //     } else if (isBoolean) {
    //       rowObject[header] = value.toLowerCase() === 'true'; // Convert to boolean
    //     } else if (isDateFormat) {
    //       // Convert "M/D/YYYY", "MM/DD/YYYY", "M-D-YYYY", or "MM-DD-YYYY" to "YYYY-MM-DD"
    //       const [month, day, year] = value.split(/[\/-]/);
    //       const formattedMonth = month.padStart(2, '0');
    //       const formattedDay = day.padStart(2, '0');
    //       rowObject[header] = `${year}-${formattedMonth}-${formattedDay}`;
    //     } else if (value === '') {
    //       rowObject[header] = null; // Set empty values to null
    //     } else {
    //       rowObject[header] = value; // Keep as string for all other cases
    //     }
    //   });

    //   return rowObject;
    // });

    // Prepare filename
    // const fileName = `${spreadsheetName.replace(/[<>:"/\\|?*]/g, '_')}.json`;
    // const folderPath = path.join(__dirname, 'jsons');
    // const filePath = path.join(folderPath, fileName);
    var json = await csv().fromFile(path.join(__dirname, "Test Jobs.csv"));

    //console.log(json);
    //return ;
    // if (!fs.existsSync(folderPath)) {
    //   fs.mkdirSync(folderPath);
    // }
    // //let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
    // //console.log(OrgaId);

    // // Check if the JSON file already exists
    // const existFile = fs.existsSync(filePath);

    // if (existFile) {
    //   // Read the existing file
    //   const oldFile = await fs.promises.readFile(filePath, 'utf8');
    //   const old_File_Parsed = JSON.parse(oldFile);

    //   if (_.isEqual(old_File_Parsed, jsonData)) {
    //     console.log('No changes');

    //   } else {
    //     // Delete rows
    //     for (let i in old_File_Parsed) {
    //       let oldRow = old_File_Parsed[i];
    //       let id = oldRow["Org Job Id"];
    //       let newRow = jsonData.find((newRow) => newRow["Org Job Id"] === id);

    //       if (!newRow) {
    //         console.log('Row deleted:', oldRow["Org Job Id"]);

    //         let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
    //         try {
    //           await queries.deleteJob(OrgaId, oldRow["Org Job Id"],);

    //         } catch (err) {
    //           console.error(`Error in job with ID ${oldRow["Org Job Id"]}:`, err);
    //         }

    //       }
    //     }

    //     // Update & add rows
    //     for (let i in jsonData) {
    //       let newRow = jsonData[i];
    //       let id = newRow["Org Job Id"];
    //       let oldRow = old_File_Parsed.find((j) => j["Org Job Id"] === id);


    //       if (oldRow) {
    //         if (_.isEqual(newRow, oldRow)) {
    //           continue;
    //         } else {
    //           console.log('Row updated:', newRow["Org Job Id"]);
    //           // update data in the database
    //           // get the organization ID
    //           let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];

    //           await queries.updateJob(OrgaId, newRow);

    //         }
    //       } else {
    //         console.log('Row added:', newRow["Org Job Id"]);

    //         // insert data in the database
    //         // get the organization ID
    //         let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
    //         try {
    //           await queries.insertJob(OrgaId, newRow);

    //         } catch (err) {
    //           console.error(`Error add job with ID ${OrgaId}:`, err);
    //         }

    //       }
    //     }



    //     // Save the updated data
    //     await fs.promises.writeFile(filePath, JSON.stringify(jsonData, null, 2));
    //     console.log(`Updated data saved to ${filePath}`);
    //   }
    // } else {

    // // Save the JSON data to a new file in the jsons folder
    //await fs.promises.writeFile(filePath, JSON.stringify(jsonData, null, 2));
    // console.log(`Data saved to ${filePath}`);


    // insert data in the database

    // get the organization ID
    //let OrgaId = spreadsheetName.match(/\[(.*?)\]/)[1];
    // Daniel's org id
    const OrgaId = "GWU000007";

    //let jobdbId = await queries.insertJob(OrgaId, job);

    for (const job of json) {

      try {

        let recruiterID = null;

        //Fetch recruiter ID from the APi
        recruiterID = await axios.post("http://localhost:4545/organizations/assignUpNextRecruiter", { id: OrgaId });
        recruiterID = recruiterID.data;

        //check if the recruiter id is fetched successfully
        if (recruiterID.success) {

          //Insert the job into the db
          await queries.insertJob(OrgaId, job);

          //Update recruiter details
          await queries.updateJobRecruiterID(job["Org Job Id"], recruiterID.data);

        }

      } catch (err) {
        console.error(`Error in job with ID ${job["Org Job Id"]}:`, err);
      }
    }

  } catch (err) {

    //Log the error message
    console.error('Error fetching or saving data:', err);
  }
}


// write a function to get all spread sheet ids
// async function listSpreadsheetIds(auth) {
//   const drive = google.drive({ version: 'v3', headers: { Authorization: `Bearer ${auth}` } });
//   try {
//     const res = await drive.files.list({
//       q: "mimeType='application/vnd.google-apps.spreadsheet'",
//       fields: 'files(id, name)',
//     });

//     const files = res.data.files;
//     if (files.length) {
//       return files;
//     } else {
//       console.log('No spreadsheets found.');
//       return [];
//     }
//   } catch (err) {
//     console.error('Error listing spreadsheets:', err.message);
//     throw err;
//   }
// }

// async function processAllSpreadsheets(auth) {
//   try {
//     // Step 1: Get all spreadsheet IDs
//     const spreadsheets = await listSpreadsheetIds(auth);

//     if (spreadsheets.length === 0) {
//       console.log('No spreadsheets to process.');
//       return;
//     }

//     // Step 2: Loop through each spreadsheet and get the data
//     for (const spreadsheet of spreadsheets) {

//       if (spreadsheet.id === "1YsIGVl2l19r_j-bFkm7aWSnUNZXPf19HWcoBHO_xqc4") {
//         console.log("skip this");

//       } else {
//         console.log(`Processing spreadsheet: ${spreadsheet.name} (${spreadsheet.id})`);

//         // Call your function to get data and save it as JSON
//         //await getDataAndSaveAsJson(auth, spreadsheet.id, spreadsheet.name);

//         await addJobsFromLinkWithAuth(auth, spreadsheet.id);

//       }

//     }
//   } catch (err) {
//     console.error('Error processing spreadsheets:', err.message);
//   }
// }




async function main() {
  try {

    //from local file
    //await addJobsWithLocalData()

    //from public sheet
    // const url = "https://docs.google.com/spreadsheets/d/19V064m9xqBDoRNH9zRRfP4XIOUHpBIgRHs2XwiJWC5Q/edit?gid=0#gid=0";
    // await addJobsFromPublicSheet(url)

    //from auth sheet
    //await addJobsFromLinkWithAuth();

    // delete all jobs
    //await deleteAllJobs();
    
  } catch (err) {
    console.error('Error in main execution:', err.message);
  }
}
// async function main() {
//   try {
//     const auth = await authorize();

//     //await processAllSpreadsheets(auth.credentials.access_token);

//     await getDataAndSaveAsJson()

//     const id_for_add = "1IWv1voLSTzIRWZkBj4wB0PyYAQdU0-nOQ4Y28wY9xC4"
//     //await addDataToSpreadsheet(auth.credentials.access_token, id_for_add);

//     //await deleteAllSpreadsheets(auth.credentials.access_token);

//     const idd_for_delete = "1YsIGVl2l19r_j-bFkm7aWSnUNZXPf19HWcoBHO_xqc4"
//     //await deleteSpreadsheetById(auth.credentials.access_token, idd_for_delete);

//     const liste_id_to_delete = [
//       "1Z6WN5LHXTtX7S9XCBhwP8etxtNi3PEIqgWiuOEiZgIs",
//       "19l41OgezIeArouJIpJSlmmFtV7JxMFlDa2KDY2_VA60",
//       "1YsIGVl2l19r_j-bFkm7aWSnUNZXPf19HWcoBHO_xqc4",
//       "1kY6Xp8TydZevV39p3BoQZ7r8tw4tDZs-CFzHJKVLKa4",
//     ]
//     //liste_id_to_delete.forEach(id => { deleteSpreadsheetById(auth.credentials.access_token, id);});
//   } catch (err) {
//     console.error('Error in main execution:', err.message);
//   }
// }

main().catch(console.error);

module.exports = { main };

