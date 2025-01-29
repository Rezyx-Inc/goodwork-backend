module.exports.validateFields = async function (jobData) {

  try {

    const formatValidations = {
      'job_type': value => typeof value === 'string' || value === null,
      'job_id': value => typeof value === 'string' || value === null,
      'job_city': value => typeof value === 'string' || value === null,
      'job_state': value => typeof value === 'string' || value === null,
      'weekly_pay': value => (typeof value === 'number' && value >= 0) || value === null,
      'preferred_specialty': value => typeof value === 'string' || value === null,
      'preferred_work_location': value => typeof value === 'string' || value === null,
      'description': value => typeof value === 'string' || value === null,
      'terms': value => typeof value === 'string' || value === null,
      'start_date': value => value === null || !Number.isNaN(Date.parse(value)),
      'hours_shift': value => Number.isInteger(value) || value === null,
      'hours_per_week': value => Number.isInteger(value) || value === null,
      'preferred_experience': value => Number.isInteger(value) || value === null,
      'facility_shift_cancelation_policy': value => typeof value === 'string' || value === null,
      'traveler_distance_from_facility': value => typeof value === 'string' || value === null,
      'clinical_setting': value => typeof value === 'string' || value === null,
      'Patient_ratio': value => typeof value === 'string' || value === null,
      'Unit': value => typeof value === 'string' || value === null,
      'scrub_color': value => typeof value === 'string' || value === null,
      'rto': value => typeof value === 'string' || value === null,
      'guaranteed_hours': value => typeof value === 'string' || value === null,
      'weeks_shift': value => typeof value === 'string' || value === null,
      'referral_bonus': value => typeof value === 'string' || value === null,
      'sign_on_bonus': value => typeof value === 'string' || value === null,
      'completion_bonus': value => typeof value === 'string' || value === null,
      'extension_bonus': value => typeof value === 'string' || value === null,
      'other_bonus': value => typeof value === 'string' || value === null,
      'actual_hourly_rate': value => typeof value === 'string' || value === null,
      'overtime': value => typeof value === 'string' || value === null,
      'holiday': value => typeof value === 'string' || value === null,
      'orientation_rate': value => typeof value === 'string' || value === null,
      'on_call': value => typeof value === 'string' || value === null,
      'on_call_rate': value => typeof value === 'string' || value === null,
      'call_back_rate': value => typeof value === 'string' || value === null,
      'weekly_non_taxable_amount': value => typeof value === 'string' || value === null,
      'profession': value => typeof value === 'string' || value === null,
      'Emr': value => typeof value === 'string' || value === null,
      'preferred_assignment_duration': value => typeof value === 'string' || value === null,
      'block_scheduling': value => typeof value === 'string' || value === null,
      'contract_termination_policy': value => typeof value === 'string' || value === null,
      'job_location': value => typeof value === 'string' || value === null,
      'vaccinations': value => typeof value === 'string' || value === null,
      'number_of_references': value => Number.isInteger(value) || value === null,
      'certificate': value => typeof value === 'string' || value === null,
      'skills': value => typeof value === 'string' || value === null,
      'urgency': value => typeof value === 'string' || value === null,
      'facilitys_parent_system': value => typeof value === 'string' || value === null,
      'facility_name': value => typeof value === 'string' || value === null,
      'nurse_classification': value => typeof value === 'string' || value === null,
      'pay_frequency': value => typeof value === 'string' || value === null,
      'benefits': value => typeof value === 'string' || value === null,
      'feels_like_per_hour': value => typeof value === 'string' || value === null,
      'preferred_shift_duration': value => typeof value === 'string' || value === null,
      'as_soon_as_possible': value => typeof value === 'boolean' || value === null,
      'job_status_code': value => typeof value === 'string' || value === null,
      'position_type': value => typeof value === 'string' || value === null,
      'client_name': value => typeof value === 'string' || value === null,
      'client_primary_division': value => typeof value === 'string' || value === null,
      'client_city': value => typeof value === 'string' || value === null,
      'client_country': value => typeof value === 'string' || value === null,
      'client_state': value => typeof value === 'string' || value === null,
      'required_certifications_for_onboarding': value => Array.isArray(value) && value.every(item => typeof item === 'string') || value === null,
      'required_certifications_for_submittal': value => Array.isArray(value) && value.every(item => typeof item === 'string') || value === null,
      'non_billable_hours': value => (typeof value === 'number' && !Number.isNaN(value)) || value === null,
      'billable_hours': value => (typeof value === 'number' && !Number.isNaN(value)) || value === null,
      'weekly_pay': value => (typeof value === 'number' && !Number.isNaN(value)) || value === null,
      'hourly_pay_range': value => typeof value === 'string' || value === null,
      'weekly_pay_range': value => typeof value === 'string' || value === null
    };

    //Iterate through all fields and verify if the are valid
    for (let field in formatValidations) {
      if (!formatValidations[field](jobData[field] || null)) {
        console.log(`Invalid format for field: ${field}`);
        return false;
      }
    }

    return true;
  } catch (err) {

    //Log and throw error message
    console.error('Error validating fields:', err);
    throw err;
  }
};

module.exports.getNewJobId = async function (pool) {

  try {

    //Get job IDs from the db
    const [rows] = await pool.query(
      `SELECT id FROM jobs ORDER BY id DESC LIMIT 1`
    );

    //Check if data is fetched and process
    if (rows.length > 0) {
      const lastId = rows[0].id;

      const prefix = lastId.slice(0, 3);
      const numberPart = lastId.slice(3);

      const newNumberPart = String(parseInt(numberPart) + 1).padStart(numberPart.length, '0');

      const newJobId = `${prefix}${newNumberPart}`;

      return newJobId;

    } else {

      // this is fishy
      return "GWJ000001";
    }

  } catch (err) {

    //Log and throw error message
    console.error('Error getting new job ID:', err);
    throw err;
  }
}

module.exports.checkIfSpreadsheetExists = async (auth, organizationId, google) => {

  //Connect to google drive API
  const drive = google.drive({ version: 'v3', headers: { Authorization: `Bearer ${auth}` } });

  //Fetch list of files
  const response = await drive.files.list({
    q: `mimeType='application/vnd.google-apps.spreadsheet' and name contains '${organizationId}'`,
    fields: 'files(id, name)'
  });

  return response.data.files.length > 0 ? response.data.files[0] : null;
};
