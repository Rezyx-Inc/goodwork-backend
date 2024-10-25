const { pool } = require('./mysql.js');

async function validateFields(jobData) {
  try {
    const formatValidations = {
      'job_type': value => typeof value === 'string' || value === null,
      'job_id': value => typeof value === 'string' || value === null,
      'job_name': value => typeof value === 'string' || value === null,
      'job_city': value => typeof value === 'string' || value === null,
      'job_state': value => typeof value === 'string' || value === null,
      'weekly_pay': value => (typeof value === 'number' && value >= 0) || value === null,
      'preferred_specialty': value => typeof value === 'string' || value === null,
      'preferred_work_location': value => typeof value === 'string' || value === null,
      'description': value => typeof value === 'string' || value === null,
      'terms': value => typeof value === 'string' || value === null,
      'start_date': value => value === null || !isNaN(Date.parse(value)),
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
      'min_title_of_reference': value => typeof value === 'string' || value === null,
      'eligible_work_in_us': value => typeof value === 'boolean' || value === null,
      'recency_of_reference': value => Number.isInteger(value) || value === null,
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
      'as_soon_as': value => Number.isInteger(value) || value === null,
      'professional_state_licensure': value => typeof value === 'string' || value === null,
    };

    for (let field in formatValidations) {
      if (!formatValidations[field](jobData[field] || null)) {
        console.log(`Invalid format for field: ${field}`);
        return false;
      }
    }

    return true;
  } catch (err) {
    console.error('Error validating fields:', err);
    throw err;
  }
};

module.exports.insertJob = async function (orgaId, jobData) {
  try {
    const isValid = await validateFields(jobData);
    if (!isValid) {
      console.log("Invalid field");
      return;
    }
    
    const lastJobId = await getNewJobId();

    const [result] = await pool.query(
      `INSERT INTO jobs 
            (id, organization_id, job_id, job_name, description, type, terms, preferred_assignment_duration, actual_hourly_rate, profession, specialty, job_city, job_state, weekly_pay, preferred_specialty, start_date, hours_shift, hours_per_week, preferred_experience, facility_shift_cancelation_policy, traveler_distance_from_facility, clinical_setting, patient_ratio, unit, scrub_color, rto, guaranteed_hours, weeks_shift, referral_bonus, sign_on_bonus, completion_bonus, extension_bonus, other_bonus, overtime, holiday, orientation_rate, on_call, on_call_rate, call_back_rate, weekly_non_taxable_amount, emr, job_location, vaccinations, number_of_references, min_title_of_reference, eligible_work_in_us, recency_of_reference, certificate, skills, urgency, facilitys_parent_system, facility_name, nurse_classification, pay_frequency, benefits, feels_like_per_hour, preferred_shift_duration, as_soon_as, professional_state_licensure) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        lastJobId,
        orgaId,
        jobData.job_id,
        jobData.job_name,
        jobData.description,
        jobData.job_type,
        jobData.terms,
        jobData.preferred_assignment_duration,
        jobData.actual_hourly_rate,
        jobData.profession,
        jobData.specialty,
        jobData.job_city,
        jobData.job_state,
        jobData.weekly_pay,
        jobData.preferred_specialty,
        jobData.start_date,
        jobData.hours_shift,
        jobData.hours_per_week,
        jobData.preferred_experience,
        jobData.facility_shift_cancelation_policy,
        jobData.traveler_distance_from_facility,
        jobData.clinical_setting,
        jobData.Patient_ratio,
        jobData.Unit,
        jobData.scrub_color,
        jobData.rto,
        jobData.guaranteed_hours,
        jobData.weeks_shift,
        jobData.referral_bonus,
        jobData.sign_on_bonus,
        jobData.completion_bonus,
        jobData.extension_bonus,
        jobData.other_bonus,
        jobData.overtime,
        jobData.holiday,
        jobData.orientation_rate,
        jobData.on_call,
        jobData.on_call_rate,
        jobData.call_back_rate,
        jobData.weekly_non_taxable_amount,
        jobData.Emr,
        jobData.job_location,
        jobData.vaccinations,
        jobData.number_of_references,
        jobData.min_title_of_reference,
        jobData.eligible_work_in_us,
        jobData.recency_of_reference,
        jobData.certificate,
        jobData.skills,
        jobData.urgency,
        jobData.facilitys_parent_system,
        jobData.facility_name,
        jobData.nurse_classification,
        jobData.pay_frequency,
        jobData.benefits,
        jobData.feels_like_per_hour,
        jobData.preferred_shift_duration,
        jobData.as_soon_as,
        jobData.professional_state_licensure
      ]
    );

    console.log("job inserted", jobData.job_id);
    return result;
  } catch (err) {
    console.error('Error inserting job:', err);
    throw err;
  }
};


async function getNewJobId() {
  try {
    const [rows] = await pool.query(
      `SELECT id FROM jobs ORDER BY id DESC LIMIT 1`
    );

    if (rows.length > 0) {
      const lastId = rows[0].id;

      const prefix = lastId.slice(0, 3);
      const numberPart = lastId.slice(3);

      const newNumberPart = String(parseInt(numberPart) + 1).padStart(numberPart.length, '0');

      const newJobId = `${prefix}${newNumberPart}`;

      return newJobId;
    } else {
      console.log("create one");
      return "GWJ000001";
    }
  } catch (err) {
    console.error('Error getting new job ID:', err);
    throw err;
  }
}

module.exports.updateJob = async function (orgaId, jobData) {
  try {
    // Check if the job exists
    const [existJob] = await pool.query(
      `SELECT * FROM jobs WHERE job_id = ? AND organization_id = ?`,
      [jobData.job_id, orgaId]
    );

    if (existJob.length === 0) {
      console.log(`No job with ID ${jobData.job_id} exists`);
      return;
    }

    // Validate input fields
    const isValid = await validateFields(jobData);
    if (!isValid) {
      console.log("Invalid field(s) in job data");
      return;
    }

    // Update the job in the database
    const [result] = await pool.query(
      `UPDATE jobs SET 
            job_name = ?, 
            description = ?, 
            type = ?, 
            terms = ?, 
            preferred_assignment_duration = ?, 
            actual_hourly_rate = ?, 
            profession = ?, 
            specialty = ?, 
            job_city = ?, 
            job_state = ?, 
            weekly_pay = ?, 
            preferred_specialty = ?, 
            start_date = ?, 
            hours_shift = ?, 
            hours_per_week = ?, 
            preferred_experience = ?, 
            facility_shift_cancelation_policy = ?, 
            traveler_distance_from_facility = ?, 
            clinical_setting = ?, 
            patient_ratio = ?, 
            unit = ?, 
            scrub_color = ?, 
            rto = ?, 
            guaranteed_hours = ?, 
            weeks_shift = ?, 
            referral_bonus = ?, 
            sign_on_bonus = ?, 
            completion_bonus = ?, 
            extension_bonus = ?, 
            other_bonus = ?, 
            overtime = ?, 
            holiday = ?, 
            orientation_rate = ?, 
            on_call = ?, 
            on_call_rate = ?, 
            call_back_rate = ?, 
            weekly_non_taxable_amount = ?, 
            emr = ?, 
            job_location = ?, 
            vaccinations = ?, 
            number_of_references = ?, 
            min_title_of_reference = ?, 
            eligible_work_in_us = ?, 
            recency_of_reference = ?, 
            certificate = ?, 
            skills = ?, 
            urgency = ?, 
            facilitys_parent_system = ?, 
            facility_name = ?, 
            nurse_classification = ?, 
            pay_frequency = ?, 
            benefits = ?, 
            feels_like_per_hour = ?, 
            preferred_shift_duration = ?, 
            as_soon_as = ?, 
            professional_state_licensure = ? 
        WHERE job_id = ? AND organization_id = ?`,
      [
        jobData.job_name,
        jobData.description,
        jobData.job_type,
        jobData.terms,
        jobData.preferred_assignment_duration,
        jobData.actual_hourly_rate,
        jobData.profession,
        jobData.specialty,
        jobData.job_city,
        jobData.job_state,
        jobData.weekly_pay,
        jobData.preferred_specialty,
        jobData.start_date,
        jobData.hours_shift,
        jobData.hours_per_week,
        jobData.preferred_experience,
        jobData.facility_shift_cancelation_policy,
        jobData.traveler_distance_from_facility,
        jobData.clinical_setting,
        jobData.patient_ratio,
        jobData.unit,
        jobData.scrub_color,
        jobData.rto,
        jobData.guaranteed_hours,
        jobData.weeks_shift,
        jobData.referral_bonus,
        jobData.sign_on_bonus,
        jobData.completion_bonus,
        jobData.extension_bonus,
        jobData.other_bonus,
        jobData.overtime,
        jobData.holiday,
        jobData.orientation_rate,
        jobData.on_call,
        jobData.on_call_rate,
        jobData.call_back_rate,
        jobData.weekly_non_taxable_amount,
        jobData.emr,
        jobData.job_location,
        jobData.vaccinations,
        jobData.number_of_references,
        jobData.min_title_of_reference,
        jobData.eligible_work_in_us,
        jobData.recency_of_reference,
        jobData.certificate,
        jobData.skills,
        jobData.urgency,
        jobData.facilitys_parent_system,
        jobData.facility_name,
        jobData.nurse_classification,
        jobData.pay_frequency,
        jobData.benefits,
        jobData.feels_like_per_hour,
        jobData.preferred_shift_duration,
        jobData.as_soon_as,
        jobData.professional_state_licensure,
        jobData.job_id,
        orgaId
      ]
    );

    console.log('Job updated successfully:', jobData.job_id);
    return result;
  } catch (err) {
    console.error('Error updating job:', err.message);
    throw err;
  }
};

module.exports.deleteJob = async function (orgaId, jobId) {
  try {
    // if the job exists
    const [existJob] = await pool.query(
      `SELECT * FROM jobs WHERE job_id = ? AND organization_id = ?`,
      [jobId, orgaId]
    );

    if (existJob.length === 0) {
      console.log(`No job with ID ${jobId} exists for organization ${orgaId}`);
      return;
    }

    const [result] = await pool.query(
      `DELETE FROM jobs WHERE job_id = ? AND organization_id = ?`,
      [jobId, orgaId]
    );

    console.log('Job deleted successfully:', jobId);
    return result;
  } catch (err) {
    console.error('Error deleting job:', err.message);
    throw err;
  }
};



