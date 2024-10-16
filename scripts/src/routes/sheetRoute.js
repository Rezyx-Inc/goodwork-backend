const express = require('express');
const router = express.Router();
const { google } = require('googleapis');
const { authorize } = require('../gSheet/index');
var { report } = require("../set.js");

router.post('/createSheet', async (req, res) => {
  try {
    const { organizationId, organizationName } = req.body;

    if (!organizationId || !organizationName) {
      return res.status(400).json({
        success: false,
        message: 'organization id and orgaization nname are required'
      });
    }

    const auth = await authorize();
    const sheets = google.sheets({ version: 'v4', auth });

    // Create the Google Sheet
    const response = await sheets.spreadsheets.create({
      resource: {
        properties: {
          title: `${organizationName}-${organizationId}`,
        },
      },
      fields: 'spreadsheetId',
    });

    const spreadsheetId = response.data.spreadsheetId;
    console.log(`spreadsheet created with Id: ${spreadsheetId}`);

    const spreadsheetName = `${organizationName}-${organizationId}`;

    res.status(200).json({
      success: true,
      response,
      spreadsheetId,
      spreadsheetName,
      message: 'Spreadsheet created successfully'
    });

  } catch (error) {
    console.error('Error creating spreadsheet:', error);
    report(`Error creating spreadsheet for ${spreadsheetName} : ${error.message}`)
    res.status(500).json({
      success: false,
      message: error
    });
  }
});

module.exports = router;
