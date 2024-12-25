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

  if (!Object.keys(req.body).length) {

    return res.status(200).send({success: false, message: "Empty request"});
  }

  const { organizationId, organizationName } = req.body;

  try {

    const auth = await authorize();

    // Check if a spreadsheet with the organizationId already exists
    const existingSpreadsheet = await checkIfSpreadsheetExists(auth.credentials.access_token, organizationId);
    
    if (existingSpreadsheet) {

      return res.status(200).json({ success: false, message: 'Spreadsheet already exists.', data : { spreadsheetId: existingSpreadsheet.id } });
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

    return res.status(200).json({ success: true, message: 'Spreadsheet created and initialized successfully' , data : { link: `https://docs.google.com/spreadsheets/d/${spreadsheetId}/edit?gid=0#gid=0`, spreadsheetId } });

  } catch (error) {

    console.error('Error creating spreadsheet:', error);
    report('error', 'sheetRoute.js',`Unable to create a spreadsheet for ${organizationName}-${organizationId} : ${error.message}`);

    return res.status(200).json({ success: false, message: 'Internal server error' });

  }
});


module.exports = router;

