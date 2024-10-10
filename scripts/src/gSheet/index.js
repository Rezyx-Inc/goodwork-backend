const { google } = require('googleapis');
const { authenticate } = require('@google-cloud/local-auth');
const path = require('path');
const fs = require('fs');
const sha256 = require('sha256');
const { lineUpdated, lineAdded, lineDeleted } = require('./funcSheet');
var _ = require('lodash');
const { updated } = require('../mysql/sheet');
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

const SPREADSHEET_ID = '1z4bz97OHwfM0dyVYge5-BmJurduTSDGIHFOV7m7yKxM';
const SHEET_NAME = 'Sheet1'; // Update this to the correct sheet name if necessary

// Function to get data from the Google Sheet and save it as JSON
async function getDataAndSaveAsJson(auth) {
  try {
    const sheets = google.sheets({ version: 'v4', auth });
    const res = await sheets.spreadsheets.values.get({
      spreadsheetId: SPREADSHEET_ID,
      range: `${SHEET_NAME}!A:N`,
    });
    const rows = res.data.values;
    if (!rows.length) {
      console.log('No data found.');
      return;
    }

    // Extract headers and data
    const headers = rows[0];
    // Remove the first row, which contains headers
    const data = rows.slice(1);

    // Transform the data into an array of objects
    const jsonData = data.map(row => {
      const rowObject = {};


      headers.forEach((header, index) => {
        let value = row[index] || '';

        if (header === "auto offer" || header === "Length" || header === "Taxable hourly rate") {
          value = parseFloat(value) || 0;
        }

        // if (header === "start date" || header === "end date") {
        //   const parsedDate = new Date(value);
        //   value = isNaN(parsedDate.getTime()) ? null : parsedDate;  
        // }


        rowObject[header] = value;
      });
      return rowObject;
    });





    // filename
    const orgName = 'ORGNAME';
    const goodWorkOrgId = 'GOODWORKORGID';
    const fileName = `${orgName}-${goodWorkOrgId}.json`;



    // check if file exist
    const existFile = await fs.existsSync(fileName);


    if (existFile) {

      // get old file
      const oldFile = await fs.readFileSync(fileName, 'utf8');
      const old_File_Parsed = JSON.parse(oldFile);


      if (_.isEqual(old_File_Parsed, jsonData)) {
        console.log('nno changes');


      } else {

        // update & add
        for (let i in jsonData) {
          let newRow = jsonData[i];
          let id = newRow.jobId;
          let oldRow = old_File_Parsed.find((j) => j.jobId === id);

          if (oldRow) {
            if (_.isEqual(newRow, oldRow)) {
              exit
            } else {
              console.log('Row updated:', newRow);
            }
          } else {
            console.log('Row added:', newRow);
          }
        }

        // delete
        for (let i in old_File_Parsed) {
          let oldRow = old_File_Parsed[i];
          let id = oldRow.jobId;
          let newRow = jsonData.find((newRow) => newRow.jobId === id);

          if (!newRow) {
            console.log('Row deleted:', oldRow);
          }
        }


      }



    } else {
      const filePath = path.join(__dirname, fileName);
      await fs.promises.writeFile(filePath, JSON.stringify(jsonData, null, 2));
      console.log(`Data saved to ${filePath}`);


    }


    // Insert data into the database

    // let newOgraId = 1;
    // for (const job of jsonData) {
    //   try {
    //     const result = await queries.insertJob(newOgraId, job);
    //     console.log(`Job with ID ${job.jobId} inserted successfully`, result);
    //     newOgraId += 1;
    //   } catch (err) {
    //     console.error(`Error inserting job with ID ${job.jobId}:`, err);
    //   }
    // }

    // Update data in the database
    let getOgraId = 1;
    for (const job of jsonData) {
      try {

        await queries.updateJob(getOgraId, job);

        getOgraId += 1;
      } catch (err) {
        console.error(`Error updating job with ID ${job.jobId}:`, err);
      }
    }

  } catch (err) {
    console.error('Error fetching or saving data:', err);
  }
}

// Function to add data to the Google Sheet
/*
async function addData(auth) {
  try {
    const sheets = google.sheets({ version: 'v4', auth });
    const values = [
      ['12345', 'Software Engineer', 'Full-stack developer', 'Full-time', 'Permanent', '12 months', '45', 'Engineering', 'Web Development', 'Casablanca', 'Casablanca-Settat', '2024-10-01', 'Yes', '2025-09-30']
    ];

    const resource = {
      values,
    };

    const res = await sheets.spreadsheets.values.append({
      spreadsheetId: SPREADSHEET_ID,
      range: `${SHEET_NAME}!A:N`,
      valueInputOption: 'RAW',
      insertDataOption: 'INSERT_ROWS',
      resource,
    });

    console.log(`${res.data.updates.updatedCells} cells appended.`);
  } catch (err) {
    console.error('Error adding data:', err.message);
  }
}
*/

async function main() {
  try {
    const auth = await authorize();

    await getDataAndSaveAsJson(auth);
    // await addData(auth);



  } catch (err) {
    console.error('Error in main execution:', err.message);
  }
}

main().catch(console.error);

module.exports = { main };

