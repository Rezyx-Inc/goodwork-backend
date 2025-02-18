//Import required libraries and/or modules
const { pool } = require('./mysql.js'); // Get MySQL connection
const moment = require('moment');

// Needs to be tested
const { validateFields, getNewJobId } = require('../helpers/sheetHelper.js');

//Function to insert a job into the db
module.exports.insertJob = async function (orgaId, jobData) {

  try {

    const emptyValue = " ";
    const zeroInt = 0;
    const lastJobId = await getNewJobId(pool);

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
        Number(jobData["Wks/Contract"]) || 0,
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
        jobData["Resume"] == "TRUE" ? "1" : "0",

      ]
    );

    return lastJobId;

  } catch (err) {

    console.error('Error inserting job:', err);
    throw err;
  }
};

//Function to update the job data
module.exports.updateJob = async function (orgaId, jobData) {

  try {

    // Check if the job exists
    const [existJob] = await pool.query(
      `SELECT * FROM jobs WHERE job_id = ? AND organization_id = ?`,
      [jobData["Org Job Id"], orgaId]
    );

    if (existJob.length === 0) {

      return;
    }

    // Validate input fields
    const isValid = await validateFields(jobData);

    if (!isValid) {

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

    return result;

  } catch (err) {

    console.error('Error updating job:', err.message);
    throw err;
  }
};

//Function to delete a job 
module.exports.deleteJob = async function (orgaId, jobId) {

  try {

    // if the job exists
    const [existJob] = await pool.query(
      `SELECT * FROM jobs WHERE job_id = ? AND organization_id = ?`,
      [jobId, orgaId]
    );

    if (existJob.length === 0) {
      return;
    }

    const [result] = await pool.query(
      `DELETE FROM jobs WHERE job_id = ? AND organization_id = ?`,
      [jobId, orgaId]
    );

    return result;

  } catch (err) {

    console.error('Error deleting job:', err.message);
    throw err;
  }
};

// Function to update the recruiter id of a job
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

};


// delete all jobs from jobs table 
module.exports.deleteAllJobs = async function () {
  try {
    const [result] = await pool.query(`DELETE FROM jobs`);
    console.log('All jobs deleted successfully');
    return result;
  } catch (err) {
    console.error('Error deleting all jobs:', err.message);
    throw err;
  }
};