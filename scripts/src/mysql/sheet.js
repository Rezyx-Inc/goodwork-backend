const { pool } = require('./mysql.js');
const moment = require('moment');

async function validateFields(jobData) {
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

    // const isValid = await validateFields(jobData);
    // if (!isValid) {
    //   console.log("Invalid field");
    //   return;
    // }
    const emptyValue = " ";
    const zeroInt = 0;
    const lastJobId = await getNewJobId();

    let results = await pool.query(
      `INSERT INTO jobs 
            (id, created_at, organization_id, created_by, recruiter_id, job_id, job_type, terms, profession, preferred_specialty, actual_hourly_rate, weekly_pay, hours_per_week, job_state, job_city, preferred_shift_duration, guaranteed_hours, hours_shift, weeks_shift, Preferred_assignment_duration, start_date, end_date, rto, overtime, on_call_rate, call_back_rate, orientation_rate, weekly_taxable_amount, weekly_non_taxable_amount, feels_like_per_hour, referral_bonus, sign_on_bonus, extension_bonus, total_organization_amount, pay_frequency, benefits, clinical_setting, preferred_work_location, facility_name, facilitys_parent_system, facility_shift_cancelation_policy, contract_termination_policy, traveler_distance_from_facility, job_location, certificate, description, urgency, preferred_experience, number_of_references, skills, on_call, block_scheduling, float_requirement, Patient_ratio, Emr, Unit, nurse_classification, vaccinations, tax_status, facility_city, facility_state, professional_licensure, is_resume)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        lastJobId,
        moment().format("YYYY-MM-DD HH:mm:ss"),
        orgaId,
        orgaId,
        orgaId,
        jobData["Org Job Id"],
        jobData["Type"],
        jobData["Employment"],
        jobData["Profession"],
        jobData["Specialty"],
        jobData["$/hr"],
        jobData["$wk"],
        jobData["Hrs/Wk"],
        jobData["State"],
        jobData["City"],
        jobData["Shift Time"],
        jobData["Guaranteed Hrs/wk"],
        jobData["Hrs/Shift"],
        jobData["Shift/Wk"],
        Number(jobData["Wks/Contract"]) || 0 ,
        moment(jobData["Start Date"], ["MM/DD/YYYY", "MM-DD-YYYY"]).format("YYYY-MM-DD"),
        moment(jobData["End Date"].replace(/[/-]/g, "-")).format("YYYY-MM-DD"),
        jobData["RTO"],
        jobData["OT $/Hr"],
        jobData["On Call $/Hr"],
        jobData["Call Back $/Hr"],
        jobData["Orientation $/Hr"],
        jobData["Taxable/Wk"],
        jobData["Non-taxable/Wk"],
        jobData["Feels Like $/hr"],
        //jobData["Gw$/Wk"],
        jobData["Referral Bonus"],
        jobData["Sign-On Bonus"],
        jobData["Extension Bonus"],
        jobData["$/Org"],
        // jobData["$/Gw"],
        // jobData["Total $"],
        jobData["Pay Frequency"],
        jobData["Benefits"],
        jobData["Clinical Setting"],
        jobData["Adress"],
        jobData["Facility"],
        jobData["Facility's Parent System"],
        jobData["Facility Shift Cancellation Policy"],
        jobData["Contract Termination Policy"],
        jobData["Min Miles Must Live From Facility"],
        jobData["Professional Licensure"],
        jobData["Certifications"],
        jobData["Description"],
        jobData["Auto Offer"],
        jobData["Experience"],
        jobData["References"],
        jobData["Skills checklist"],
        zeroInt,//jobData["On Call?"],
        jobData["Block scheduling"],
        zeroInt,//jobData["Floating Required"],
        jobData["Patient Ratio Max"],
        jobData["EMR"],
        jobData["Unit"],
        jobData["Classification"],
        jobData["Vaccinations & Immunizations"],
        emptyValue,
        emptyValue,
        emptyValue,
        emptyValue,
        jobData["Resume"]== "TRUE" ? "1" : "0",

      ]
    );



    console.log("job inserted", lastJobId);
    return lastJobId;
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
      [jobData["Org Job Id"], orgaId]
    );

    if (existJob.length === 0) {
      console.log(`No job with ID ${jobData["Org Job Id"]} exists`);
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
            job_type = ?,
            terms = ?,
            profession = ?,
            preferred_specialty = ?,
            actual_hourly_rate = ?,
            weekly_pay = ?,
            hours_per_week = ?,
            job_state = ?,
            job_city = ?,
            preferred_shift_duration = ?,
            guaranteed_hours = ?,
            hours_shift = ?,
            weeks_shift = ?,
            Preferred_assignment_duration = ?,
            start_date = ?,
            end_date = ?,
            rto = ?,
            overtime = ?,
            on_call_rate = ?,
            call_back_rate = ?,
            orientation_rate = ?,
            weekly_taxable_amount = ?,
            weekly_non_taxable_amount = ?,
            feels_like_per_hour = ?,
            referral_bonus = ?,
            sign_on_bonus = ?,
            extension_bonus = ?,
            total_organization_amount = ?,
            pay_frequency = ?,
            benefits = ?,
            clinical_setting = ?,
            preferred_work_location = ?,
            facility_name = ?,
            facilitys_parent_system = ?,
            facility_shift_cancelation_policy = ?,
            contract_termination_policy = ?,
            traveler_distance_from_facility = ?,
            job_location = ?,
            certificate = ?,
            description = ?,
            urgency = ?,
            preferred_experience = ?,
            number_of_references = ?,
            skills = ?,
            on_call = ?,
            block_scheduling = ?,
            float_requirement = ?,
            Patient_ratio = ?,
            Emr = ?,
            Unit = ?,
            nurse_classification = ?,
            vaccinations = ?
        WHERE job_id = ? AND organization_id = ?`,
      [
        jobData["Type"],
        jobData["Terms *"],
        jobData["Profession *"],
        jobData["Specialty *"],
        jobData["$/hr *"],
        jobData["$/Wk *"],
        jobData["Hrs/Wk *"],
        jobData["State *"],
        jobData["City *"],
        jobData["Shift Time"],
        jobData["Guaranteed Hrs/wk"],
        jobData["Hrs/Shift"],
        jobData["Shifts/Wk"],
        jobData["Wks/Contract"],
        jobData["Start Date"],
        jobData["End Date"],
        jobData["RTO"],
        jobData["OT $/Hr"],
        jobData["On Call $/Hr"],
        jobData["Call Back $/Hr"],
        jobData["Orientation $/Hr"],
        jobData["Taxable/Wk"],
        jobData["Non-taxable/Wk"],
        jobData["Feels Like $/hr"],
        // jobData["Gw$/Wk"],
        jobData["Referral Bonus"],
        jobData["Sign-On Bonus"],
        jobData["Extension Bonus"],
        jobData["$/Org"],
        // jobData["$/Gw"],
        // jobData["Total $"],
        jobData["Pay Frequency"],
        jobData["Benefits"],
        jobData["Clinical Setting"],
        jobData["Adress"],
        jobData["Facility"],
        jobData["Facility's Parent System"],
        jobData["Facility Shift Cancellation Policy"],
        jobData["Contract Termination Policy"],
        jobData["Min Miles Must Live From Facility"],
        jobData["Professional Licensure"],
        jobData["Certifications"],
        jobData["Description"],
        jobData["Auto Offer"],
        jobData["Experience"],
        jobData["References"],
        jobData["Skills checklist"],
        jobData["On Call?"],
        jobData["Block scheduling"],
        jobData["Floating Required"],
        jobData["Patient Ratio Max"],
        jobData["EMR"],
        jobData["Unit"],
        jobData["Classification"],
        jobData["Vaccinations & Immunizations"],
        jobData["Org Job Id"],
        orgaId
      ]
    );

    console.log('Job updated successfully:', jobData["Org Job Id"]);
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


module.exports.updateJobRecruiterID = async function (jobdbId, recruiter_id) {
  try {
    
    const [result] = await pool.query(
      `UPDATE jobs SET recruiter_id = ? WHERE job_id = ?`,
      [recruiter_id, jobdbId]
    );

    console.log(`Job ${jobdbId} assigned to recruiter ${recruiter_id}`);
    return result;

  } catch (err) {
    console.error('Error updating job:', err.message);
    throw err;
  }
  
}


