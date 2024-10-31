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
    //const existingSpreadsheet = await checkIfSpreadsheetExists(auth, organizationId);

    // if (existingSpreadsheet) {
    //   return res.status(400).json({
    //     success: false,
    //     message: `Spreadsheet already exists for organization ID: ${organizationId}`,
    //     spreadsheetId: existingSpreadsheet.id
    //   });
    // }

    const sheets = google.sheets({ version: 'v4', auth });

    // Create a new spreadsheet
    const response = await sheets.spreadsheets.create({
      resource: {
        properties: {
          title: `${organizationName}[${organizationId}]`,
        },
      },
      fields: 'spreadsheetId',
    });

    const spreadsheetId = response.data.spreadsheetId;

    // Define the fields to be initialized in the sheet
    const fields = [
      'job_id', 'job_name', 'job_city', 'job_type', 'type', 'job_state', 'weekly_pay', 'preferred_specialty',
      'active', 'description', 'start_date', 'hours_shift', 'hours_per_week', 'preferred_experience',
      'facility_shift_cancelation_policy', 'traveler_distance_from_facility', 'clinical_setting', 'Patient_ratio',
      'Unit', 'scrub_color', 'rto', 'guaranteed_hours', 'weeks_shift', 'referral_bonus', 'sign_on_bonus',
      'completion_bonus', 'extension_bonus', 'other_bonus', 'actual_hourly_rate', 'overtime', 'holiday',
      'orientation_rate', 'on_call', 'on_call_rate', 'call_back_rate', 'weekly_non_taxable_amount', 'profession',
      'terms', 'preferred_assignment_duration', 'block_scheduling', 'contract_termination_policy',
      'Emr', 'job_location', 'vaccinations', 'number_of_references', 'min_title_of_reference', 'eligible_work_in_us',
      'recency_of_reference', 'certificate', 'preferred_shift_duration', 'skills', 'urgency', 'facilitys_parent_system',
      'facility_name', 'nurse_classification', 'pay_frequency', 'benefits', 'feels_like_per_hour', 'as_soon_as',
      'professional_state_licensure'
    ];



    // Write the fields as headers in the first row of the sheet
    await sheets.spreadsheets.values.update({
      spreadsheetId,
      range: 'Sheet1!A1', // Assuming the first sheet is named 'Sheet1' and you want to start at A1
      valueInputOption: 'RAW',
      resource: {
        values: [fields], // The fields are written in the first row
      },
    });

    return res.status(200).json({
      success: true,
      link: `https://docs.google.com/spreadsheets/d/${spreadsheetId}/edit?gid=0#gid=0`,
      spreadsheetId,
      message: 'Spreadsheet created and initialized successfully'
    });

  } catch (error) {
    console.error('Error creating spreadsheet:', error);
    //report(`Error creating spreadsheet for ${organizationName}-${organizationId} : ${error.message}`);
    return res.status(500).json({
      success: false,
      message: 'Internal server error'
    });
  }
});


module.exports = router;
