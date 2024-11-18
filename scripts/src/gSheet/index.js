const { google } = require('googleapis');
const path = require('path');
const fs = require('fs');
const sha256 = require('sha256');
var _ = require('lodash');
const { json } = require('body-parser');
const { exit } = require('process');
const { deleteAllSpreadsheets, deleteSpreadsheetById, addDataToSpreadsheet } = require('./crud/crud.js');

const queries = require("../mysql/sheet.js");

const { authorize } = require('./services/authService');

async function getDataAndSaveAsJson(auth, spreadsheetId, spreadsheetName) {
  try {


    const sheets = google.sheets({ version: 'v4', headers: { Authorization: `Bearer ${auth}` } });
    const res = await sheets.spreadsheets.values.get({
      spreadsheetId: spreadsheetId,
      range: `Sheet1!A:BH`,
      // auth: auth,
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
        const isNumeric = /^\d+$/.test(value);

        // Check if the value is boolean (case insensitive)
        const isBoolean = value.toLowerCase() === 'true' || value.toLowerCase() === 'false';

        // Check if the value is in "M/D/YYYY", "MM/DD/YYYY", "M-D-YYYY", or "MM-DD-YYYY" format
        const isDateFormat = /^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/.test(value);

        // Assign based on type
        if (header === 'Job ID') {
          rowObject[header] = value; // Keep 'job_id' as a string
        } else if ((header === 'On Call?' && value === '')
          || (header === 'Floating Required' && value === '')) {
          rowObject[header] = 0;
        } else if (isNumeric) {
          rowObject[header] = parseFloat(value); // Convert to number if fully numeric
        } else if (isBoolean) {
          rowObject[header] = value.toLowerCase() === 'true'; // Convert to boolean
        } else if (isDateFormat) {
          // Convert "M/D/YYYY", "MM/DD/YYYY", "M-D-YYYY", or "MM-DD-YYYY" to "YYYY-MM-DD"
          const [month, day, year] = value.split(/[\/-]/);
          const formattedMonth = month.padStart(2, '0');
          const formattedDay = day.padStart(2, '0');
          rowObject[header] = `${year}-${formattedMonth}-${formattedDay}`;
        } else if (value === '') {
          rowObject[header] = null; // Set empty values to null
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
  const drive = google.drive({ version: 'v3', headers: { Authorization: `Bearer ${auth}` } });
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

    await processAllSpreadsheets(auth.credentials.access_token);

    const id_for_add = "1IWv1voLSTzIRWZkBj4wB0PyYAQdU0-nOQ4Y28wY9xC4"
    //await addDataToSpreadsheet(auth.credentials.access_token, id_for_add);

    //await deleteAllSpreadsheets(auth.credentials.access_token);

    const idd_for_delete = "1jWUW6v0lOmDCCJ8x9YPqpY0lyIi_pmPaUGfM19BA318"
    //await deleteSpreadsheetById(auth.credentials.access_token, idd_for_delete);

    const liste_id_to_delete = [
      "1a4JejQb0FQtvZ-ACE1pxuH57oozXD_T51UfevmSy8Zg",
      "1QUAKun2LFmKqtiqpC18DS1GWNO31iipuRuVkhUv0m08",
    ]
    //liste_id_to_delete.forEach(id => { deleteSpreadsheetById(auth.credentials.access_token, id);});
  } catch (err) {
    console.error('Error in main execution:', err.message);
  }
}

main().catch(console.error);

module.exports = { main };

