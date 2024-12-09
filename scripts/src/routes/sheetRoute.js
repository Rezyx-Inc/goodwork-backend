const express = require('express');
const router = express.Router();
const { google } = require('googleapis');
const { authorize } = require('../gSheet/services/authService.js');
var { report } = require("../set.js");

const checkIfSpreadsheetExists = async (auth, organizationId) => {
  
  const drive = google.drive({ version: 'v3', headers: { Authorization: `Bearer ${auth}` } });

  const response = await drive.files.list({
    q: `mimeType='application/vnd.google-apps.spreadsheet' and name contains '${organizationId}'`,
    fields: 'files(id, name)'
  });

  return response.data.files.length > 0 ? response.data.files[0] : null;
};

router.post('/createSheet', async (req, res) => {
  const { organizationId, organizationName } = req.body;

  if (!organizationId || !organizationName) {
    return res.status(400).json({
      success: false,
      message: 'organizationId and organizationName are required'
    });
  }

  try {
    const auth = await authorize();

    // Check if a spreadsheet with the organizationId already exists
    const existingSpreadsheet = await checkIfSpreadsheetExists(auth.credentials.access_token, organizationId);
    
    if (existingSpreadsheet) {
      return res.status(400).json({
        success: false,
        message: `Spreadsheet already exists for organization ID: ${organizationId}`,
        spreadsheetId: existingSpreadsheet.id
      });
    }

    const sheets = google.sheets({ version: 'v4', headers: { Authorization: `Bearer ${auth.credentials.access_token}` } });

    const drive = google.drive({
      version: 'v3',
      headers: {
        Authorization: `Bearer ${auth.credentials.access_token}`,
      },
    });

    const response = await drive.files.create({
      requestBody: {
        name: `${organizationName}[${organizationId}]`,
        mimeType: 'application/vnd.google-apps.spreadsheet',
        parents: ['1xSMyMZyc32joi2NnMTmoIzwmW2VpZfhS']
      }
    });

    const spreadsheetId = response.data.id;

    // Define the fields to be initialized in the sheet
    const fields = [
      'Org Job Id',
      'Type',
      'Terms *',
      'Profession *',
      'Specialty *',
      '$/hr *',
      '$/Wk *',
      'Hrs/Wk *',
      'State *',
      'City *',
      'Shift Time',
      'Guaranteed Hrs/wk',
      'Hrs/Shift',
      'Shifts/Wk',
      'Wks/Contract',
      'Start Date',
      'End Date',
      'RTO',
      'OT $/Hr',
      'On Call $/Hr',
      'Call Back $/Hr',
      'Orientation $/Hr',
      'Taxable/Wk',
      'Non-taxable/Wk',
      'Feels Like $/hr',
      //'Gw$/Wk',
      'Referral Bonus',
      'Sign-On Bonus',
      'Extension Bonus',
      '$/Org',
      // '$/Gw',
      // 'Total $',
      'Pay Frequency',
      'Benefits',
      'Clinical Setting',
      'Adress',
      'Facility',
      "Facility's Parent System",
      'Facility Shift Cancellation Policy',
      'Contract Termination Policy',
      'Min Miles Must Live From Facility',
      'Professional Licensure',
      'Certifications',
      'Description',
      'Auto Offer',
      'Experience',
      'References',
      'Skills checklist',
      'On Call?',
      'Block scheduling',
      'Floating Required',
      'Patient Ratio Max',
      'EMR',
      'Unit',
      'Classification',
      'Vaccinations & Immunizations',
    ];

    // Write the fields as headers in the first row of the sheet
    await sheets.spreadsheets.values.update({
      spreadsheetId,
      range: 'Sheet1!A1',
      valueInputOption: 'RAW',
      resource: {
        values: [fields],
      },
    });

    // await sheets.spreadsheets.batchUpdate({
    //   spreadsheetId,
    //   resource: {
    //     requests: [
    //       {
    //         setDataValidation: {
    //           range: {
    //             sheetId: 0, // Assumes Sheet1 is the first sheet
    //             startRowIndex: 1, // Skip the header row
    //             endRowIndex: 1000, // Adjust the row limit as needed
    //             startColumnIndex: 1, // Column B
    //             endColumnIndex: 2,
    //           },
    //           rule: {
    //             condition: {
    //               type: 'ONE_OF_LIST',
    //               values: [{ userEnteredValue: 'Clinical' }, { userEnteredValue: 'Non-Clinical' }],
    //             },
    //             strict: true,
    //             showCustomUi: true,
    //           },
    //         },
    //       },
    //     ],
    //   },
    // });

    return res.status(200).json({
      success: true,
      link: `https://docs.google.com/spreadsheets/d/${spreadsheetId}/edit?gid=0#gid=0`,
      spreadsheetId,
      message: 'Spreadsheet created and initialized successfully'
    });

  } catch (error) {
    console.error('Error creating spreadsheet:', error);
    report(`Error creating spreadsheet for ${organizationName}-${organizationId} : ${error.message}`);
    return res.status(500).json({
      success: false,
      message: 'Internal server error'
    });
  }
});


module.exports = router;

