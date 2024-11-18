const { google } = require('googleapis');

// Function to delete all spreadsheets
async function deleteAllSpreadsheets(auth) {
    const drive = google.drive({ version: 'v3', headers: { Authorization: `Bearer ${auth}` } });
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
    const drive = google.drive({ version: 'v3', headers: { Authorization: `Bearer ${auth}` } });
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
        const sheets = google.sheets({ version: 'v4', headers: { Authorization: `Bearer ${auth}` } });

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


module.exports = { deleteAllSpreadsheets, deleteSpreadsheetById, addDataToSpreadsheet };
