const express = require('express');
const router = express.Router();
const { google } = require('googleapis');
const { authorize } = require('../gSheet/index');
var { report } = require("../set.js");

const checkIfSpreadsheetExists = async (auth, organizationId) => {
  const drive = google.drive({ version: 'v3', auth });

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
    const existingSpreadsheet = await checkIfSpreadsheetExists(auth, organizationId);

    if (existingSpreadsheet) {
      return res.status(400).json({
        success: false,
        message: `Spreadsheet already exists for organization ID: ${organizationId}`,
        spreadsheetId: existingSpreadsheet.id
      });
    }

    const sheets = google.sheets({ version: 'v4', auth });

    // Create a new spreadsheet
    const response = await sheets.spreadsheets.create({
      resource: {
        properties: {
          title: `${organizationName}-${organizationId}`,
        },
      },
      fields: 'spreadsheetId',
    });

    const spreadsheetId = response.data.spreadsheetId;

    return res.status(200).json({
      success: true,
      spreadsheetId,
      message: 'Spreadsheet created successfully'
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
