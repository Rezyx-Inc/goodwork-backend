//import all required libraries and/or modules
const { google } = require('googleapis'); //Required to connect to google APIs
const csv = require('csvtojson'); // Convert csv files to json
const path = require('path'); //To work with file paths and directories
const fs = require('fs'); // To read and write files
const xlsx = require('xlsx'); //To work with excel files
const axios = require('axios'); // Used for API calls
const queries = require("../../mysql/sheet.js");

// Process jobs
async function processJobs(json) {

    const OrgaId = "GWU000007";
    let recruiterID = null;

    try {
        //POST API call to assignUpNextRecruiter API
        recruiterID = await axios.post("http://localhost:4545/organizations/assignUpNextRecruiter", { id: OrgaId });
    } catch (error) {
        //Log the error, in case of failure
        console.error("Error fetching recruiter ID:", /*error*/);
    }

    //Check if recruiter is not found
    if (recruiterID !== "Up next recruiter not found.") {
        //Iterate through jobs present in the json
        for (const job of json) {
            try {
                await queries.insertJob(OrgaId, job); //Insert each job into the db

                await queries.updateJobRecruiterID(job["Org Job Id"], recruiterID.data.data); //Update recruiter details

            } catch (err) {

                //Log the error, in case of failure
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

    //Connect to google auth
    const auth = new google.auth.GoogleAuth({
        keyFile: path.resolve(__dirname, 'service-account.json'),
        scopes: ['https://www.googleapis.com/auth/drive.readonly', 'https://www.googleapis.com/auth/spreadsheets.readonly']
    });
    return await auth.getClient();
}

//Function to get list of spread sheets
async function listSpreadsheets(auth) {

    //Connect to google drive
    const drive = google.drive({ version: 'v3', auth });
    try {
        const res = await drive.files.list({
            q: "mimeType='application/vnd.google-apps.spreadsheet'", // Filter for Google Sheets
            fields: 'files(id, name)',
        });
        return res.data.files;
    } catch (error) {

        //Log and return in case of error
        console.error("Error listing spreadsheets:", error.message);
        return [];
    }
}

//Function to retrieve data in the spread sheet
async function fetchSpreadsheetData(auth, spreadsheetId) {
    const sheets = google.sheets({ version: 'v4', auth }); // Connect to google sheets API
    try {
        const response = await sheets.spreadsheets.values.get({
            spreadsheetId,
            range: 'Sheet1', // Adjust if the data is on a different sheet or range
        });
        const rows = response.data.values;

        //Check and return if there is no data in the spreadsheet
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

//Function to add jobs, with authorization 
async function addJobsFromLinkWithAuth() {
    try {
        const auth = await authorize();

        // List all spreadsheets linked to the service account
        const spreadsheets = await listSpreadsheets(auth);
        console.log("========", spreadsheets);

        //Check and return if no spreasheets are found
        if (spreadsheets.length === 0) {
            console.log("No spreadsheets found.");
            return;
        }

        //Iterate through each spreadsheet
        for (const { id, name } of spreadsheets) {
            console.log(`Processing spreadsheet: ${name} (ID: ${id})`);

            // Fetch data from each spreadsheet
            const jobData = await fetchSpreadsheetData(auth, id);

            //Check if there are jobs mentioned in the preadsheet and process them
            if (jobData.length > 0) {
                console.log(`Inserting jobs from spreadsheet: ${name}`);
                await processJobs(jobData);
            } else {
                console.log(`No valid job data in spreadsheet: ${name}`);
            }
        }
    } catch (error) {

        //Log in case of any error
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

    //CHeck file types and process accordingly
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

        //Log and return the error response, if the file format is not supported
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

        //Check and process according to the type of the content
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
        //Log the error message
        console.error("Error fetching or processing data from public sheet:", error.message);
    }
}

//----------------------------------------------------------------------------------
// delete all jobs from the database
async function deleteAllJobs() {
    try {
            await queries.deleteAllJobs()
    } catch (error) {
        //Log the error message
        console.error('Error deleting all jobs:', error.message);
    }
}


module.exports = { addJobsWithLocalData, addJobsFromPublicSheet, addJobsFromLinkWithAuth , deleteAllJobs};
