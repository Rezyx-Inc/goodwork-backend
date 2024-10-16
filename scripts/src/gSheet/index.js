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
      range: `Sheet1!A:N`,
      auth: auth,
    });

    // Access the rows from the values property
    var rows = res.data.values;
    const headers = rows.shift();

    if (!rows || rows.length === 0) {
      console.log('No data found.');
      return;
    }

    // Transform the data into an array of objects
    const jsonData = rows.map(row => {
      const rowObject = {};

      headers.forEach((header, index) => {
        let value = row[index] || '';

        if (header === "auto offer" || header === "Length" || header === "Taxable hourly rate") {
          value = parseFloat(value) || 0;
        }

        // for date parsing is required 
        /*
        if (header === "start date" || header === "end date") {
          const parsedDate = new Date(value);
          value = isNaN(parsedDate.getTime()) ? null : parsedDate;  
        }
        */

        rowObject[header] = value;
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
        // Update & add rows
        for (let i in jsonData) {
          let newRow = jsonData[i];
          let id = newRow.jobId;
          let oldRow = old_File_Parsed.find((j) => j.jobId === id);

          if (oldRow) {
            if (_.isEqual(newRow, oldRow)) {
              continue; // No changes in this row
            } else {
              console.log('Row updated:', newRow);
            }
          } else {
            console.log('Row added:', newRow);
          }
        }

        // Delete rows
        for (let i in old_File_Parsed) {
          let oldRow = old_File_Parsed[i];
          let id = oldRow.jobId;
          let newRow = jsonData.find((newRow) => newRow.jobId === id);

          if (!newRow) {
            console.log('Row deleted:', oldRow);
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
    }

    // Update data in the database (assuming `queries.updateJob` is a valid function)
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
      // console.log('Spreadsheets found:');
      // files.forEach(file => {
      //   console.log(`${file.name} (${file.id})`);
      // });
      return files;
    } else {
      // console.log('No spreadsheets found.');
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
      console.log(`Processing spreadsheet: ${spreadsheet.name} (${spreadsheet.id})`);

      // Call your function to get data and save it as JSON
      await getDataAndSaveAsJson(auth, spreadsheet.id, spreadsheet.name);
    }
  } catch (err) {
    console.error('Error processing spreadsheets:', err.message);
  }
}
async function main() {
  try {
    const auth = await authorize();

    await processAllSpreadsheets(auth);
    // await addData(auth);


  } catch (err) {
    console.error('Error in main execution:', err.message);
  }
}

main().catch(console.error);

module.exports = { authorize, main };

