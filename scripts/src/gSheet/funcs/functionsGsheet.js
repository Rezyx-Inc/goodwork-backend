const { google } = require('googleapis');
const csv = require('csvtojson');
const path = require('path');
const fs = require('fs');
const xlsx = require('xlsx');
const axios = require('axios');
const queries = require("../../mysql/sheet.js");



// insert data from local data
async function addJobsWithLocalData() {

    const filePath = path.join(__dirname, "Test Jobs.csv");

    // Get the file extension and base filename
    const fileExtension = path.extname(filePath).toLowerCase();
    const baseFileName = path.basename(filePath, fileExtension);
    const outputFileName = `${baseFileName}[${fileExtension.substring(1)}].json`; // Format: filename[extension].json
    const outputFilePath = path.join(__dirname, outputFileName);

    let json;

    if (fileExtension === '.csv') {

        // Convert CSV to JSON
        json = await csv().fromFile(filePath);
        console.log("CSV converted to JSON:", json);

    } else if (fileExtension === '.json') {

        // Read and parse the JSON file
        const fileContent = fs.readFileSync(filePath, 'utf8');
        json = JSON.parse(fileContent);
        console.log("File is already JSON:", json);

    } else if (fileExtension === '.xlsx') {

        // Convert XLSX to JSON
        const workbook = xlsx.readFile(filePath);
        const sheetName = workbook.SheetNames[0]; // Use the first sheet
        const worksheet = workbook.Sheets[sheetName];
        json = xlsx.utils.sheet_to_json(worksheet);
        console.log("XLSX converted to JSON:", json);

    } else {

        console.error("Unsupported file format. Please provide a CSV, JSON, or XLSX file.");
        return;

    }

    // Save the converted JSON to a file
    fs.writeFileSync(outputFilePath, JSON.stringify(json, null, 2), 'utf8');
    console.log(`Converted JSON saved to: ${outputFilePath}`);


    const OrgaId = "GWU000007";
    let recruiterID = null;

    try {
        recruiterID = await axios.post("http://localhost:4545/organizations/assignUpNextRecruiter", { id: OrgaId });
    } catch (error) {
        console.error("Error fetching recruiter ID:", /*error*/);
    }

    if (recruiterID !== "Up next recruiter not found.") {
    for (const job of json) {
        try {
            await queries.insertJob(OrgaId, job);

            await queries.updateJobRecruiterID(job["Org Job Id"], recruiterID.data.data);
            
        } catch (err) {
            console.error(`Error in job with ID ${job["Org Job Id"]}:`, err);
        }
    }
    } else {
        console.log("No recruiter found for this organization");
    }

}


async function addJobsFromPublicSheet(url) {
    try {
        // Fetch the spreadsheet content
        const response = await axios.get(url, { responseType: 'arraybuffer' });
        const contentType = response.headers['content-type'];
        let json;

        if (contentType.includes('text/csv')) {
            // If the content is CSV
            const csvData = response.data.toString('utf8');
            json = await csv().fromString(csvData);
        } else if (contentType.includes('application/json')) {
            // If the content is JSON
            const jsonData = response.data.toString('utf8');
            json = JSON.parse(jsonData);
        } else if (contentType.includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
            // If the content is an Excel sheet
            const workbook = xlsx.read(response.data, { type: 'buffer' });
            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            json = xlsx.utils.sheet_to_json(worksheet);
        } else {
            throw new Error('Unsupported file format.');
        }

        console.log("Data fetched and converted to JSON:", json);

        // Process the jobs
        await processJobs(json);

    } catch (error) {
        console.error("Error fetching or processing data from public sheet:", error);
    }
}

async function processJobs(json) {
    const OrgaId = "GWU000007";
    let recruiterID = null;

    try {
        recruiterID = await axios.post("http://localhost:4545/organizations/assignUpNextRecruiter", { id: OrgaId });
    } catch (error) {
        console.error("Error fetching recruiter ID:", /*error*/);
    }

    for (const job of json) {
        try {
            await queries.insertJob(OrgaId, job);
        } catch (err) {
            console.error(`Error in job with ID ${job["Org Job Id"]}:`, err);
        }
    }
}


// Function to add data to the Google Sheet
// async function addDataToSpreadsheet(auth, idForAdd) {
//     try {
//         const sheets = google.sheets({ version: 'v4', headers: { Authorization: `Bearer ${auth}` } });



//         const res = await sheets.spreadsheets.values.append({
//             spreadsheetId: idForAdd,
//             range: `Sheet1!A:BH`, // Adjust this range based on the number of fields
//             valueInputOption: 'RAW',
//             insertDataOption: 'INSERT_ROWS',
//             resource,
//         });

//         console.log(`${res.data.updates.updatedCells} cells appended.`);
//     } catch (err) {
//         console.error('Error adding data:', err.message);
//     }
// }


module.exports = { addJobsWithLocalData, addJobsFromPublicSheet };
