const { google } = require('googleapis');
const csv = require('csvtojson');
const path = require('path');
const fs = require('fs');
const xlsx = require('xlsx');
const axios = require('axios');
const queries = require("../../mysql/sheet.js");

// precess jobs
async function processJobs(json) {

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

//----------------------------------------------------------------------------------

// addJobsFromLinkWithAuth function 
async function authorize() {
    const auth = new google.auth.GoogleAuth({
        keyFile: path.resolve(__dirname, 'service-account.json'),
        scopes: ['https://www.googleapis.com/auth/drive.readonly', 'https://www.googleapis.com/auth/spreadsheets.readonly']
    });
    return await auth.getClient();
}

async function listSpreadsheets(auth) {
    const drive = google.drive({ version: 'v3', auth });
    try {
        const res = await drive.files.list({
            q: "mimeType='application/vnd.google-apps.spreadsheet'", // Filter for Google Sheets
            fields: 'files(id, name)',
        });
        return res.data.files;
    } catch (error) {
        console.error("Error listing spreadsheets:", error.message);
        return [];
    }
}

async function fetchSpreadsheetData(auth, spreadsheetId) {
    const sheets = google.sheets({ version: 'v4', auth });
    try {
        const response = await sheets.spreadsheets.values.get({
            spreadsheetId,
            range: 'Sheet1', // Adjust if the data is on a different sheet or range
        });
        const rows = response.data.values;

        if (!rows || rows.length === 0) {
            console.log(`No data found in spreadsheet ${spreadsheetId}`);
            return [];
        }

        // Convert rows to JSON (assuming the first row contains headers)
        const headers = rows[0];
        const json = rows.slice(1).map(row => {
            return headers.reduce((acc, header, idx) => {
                acc[header] = row[idx] || null;
                return acc;
            }, {});
        });

        return json;
    } catch (error) {
        console.error(`Error fetching data from spreadsheet ${spreadsheetId}:`, error.message);
        return [];
    }
}

async function addJobsFromLinkWithAuth() {
    try {
        const auth = await authorize();

        // List all spreadsheets linked to the service account
        const spreadsheets = await listSpreadsheets(auth);
        console.log("========", spreadsheets);
        if (spreadsheets.length === 0) {
            console.log("No spreadsheets found.");
            return;
        }

        for (const { id, name } of spreadsheets) {
            console.log(`Processing spreadsheet: ${name} (ID: ${id})`);

            // Fetch data from each spreadsheet
            const jobData = await fetchSpreadsheetData(auth, id);
            if (jobData.length > 0) {
                console.log(`Inserting jobs from spreadsheet: ${name}`);
                await processJobs(jobData);
            } else {
                console.log(`No valid job data in spreadsheet: ${name}`);
            }
        }
    } catch (error) {
        console.error("Error in addJobsFromLinkWithAuth:", error.message);
    }
}

//----------------------------------------------------------------------------------


// insert jobs from local data
async function addJobsWithLocalData() {

    // to test 
    const xlsx_file = "yassine.xlsx"
    const csv_file = "Test Jobs.csv"

    const filePath = path.join(__dirname, xlsx_file);

    // Get the file extension and base filename
    const fileExtension = path.extname(filePath).toLowerCase();
    const baseFileName = path.basename(filePath, fileExtension);
    const outputFileName = `${baseFileName}[${fileExtension.substring(1)}].json`; // Format: filename[extension].json
    const outputFilePath = path.join(__dirname, outputFileName);

    let json;

    if (fileExtension === '.csv') {

        // Convert CSV to JSON
        json = await csv().fromFile(filePath);
        // console.log("CSV converted to JSON:", json);

    } else if (fileExtension === '.json') {

        // Read and parse the JSON file
        const fileContent = fs.readFileSync(filePath, 'utf8');
        json = JSON.parse(fileContent);
        // console.log("File is already JSON:", json);

    } else if (fileExtension === '.xlsx') {

        // Convert XLSX to JSON
        const workbook = xlsx.readFile(filePath);
        const sheetName = workbook.SheetNames[0]; // Use the first sheet
        const worksheet = workbook.Sheets[sheetName];
        json = xlsx.utils.sheet_to_json(worksheet);
        // console.log("XLSX converted to JSON:", json);

    } else {

        console.error("Unsupported file format. Please provide a CSV, JSON, or XLSX file.");
        return;

    }

    // Save the converted JSON to a file
    fs.writeFileSync(outputFilePath, JSON.stringify(json, null, 2), 'utf8');
    console.log(`Converted JSON saved to: ${outputFilePath}`);


    // Process the jobs
    await processJobs(json);

}

//----------------------------------------------------------------------------------

// insert jobs from from a public Google Sheet
async function addJobsFromPublicSheet(url) {
    try {
        // Modify the URL for raw data export
        if (url.includes('/edit')) {
            url = url.replace(/\/edit.*$/, '/export?format=csv');
        }

        // Fetch the spreadsheet content
        const response = await axios.get(url, { responseType: 'arraybuffer' });
        const contentType = response.headers['content-type'];
        let json;

        if (contentType.includes('text/csv')) {
            // If the content is CSV
            const csvData = response.data.toString('utf8');
            json = await csv().fromString(csvData);
        } else if (contentType.includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
            // If the content is an Excel sheet
            const workbook = xlsx.read(response.data, { type: 'buffer' });
            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            json = xlsx.utils.sheet_to_json(worksheet);
        } else {
            throw new Error('Unsupported file format.');
        }

        // console.log("Data fetched and converted to JSON:", json);

        // Process the jobs
        await processJobs(json);

    } catch (error) {
        console.error("Error fetching or processing data from public sheet:", error.message);
    }
}

//----------------------------------------------------------------------------------
// delete all jobs from the database
async function deleteAllJobs() {
    try {
            await queries.deleteAllJobs()
    } catch (error) {
        console.error('Error deleting all jobs:', error.message);
    }
}




module.exports = { addJobsWithLocalData, addJobsFromPublicSheet, addJobsFromLinkWithAuth , deleteAllJobs};
