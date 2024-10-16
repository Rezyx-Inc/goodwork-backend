const { pool } = require('./mysql.js');

module.exports.insertJob = async function (orgaId, jobData) {
  try {

    const isValid = await validateFields(jobData);
    if (!isValid) {
      console.log("invalid field");
      return;
    }

    const lastJobId = await getNewJobId();

    const [result] = await pool.query(
      `INSERT INTO jobs 
            (id,organization_id,job_id,job_name,description,type,terms,preferred_assignment_duration,actual_hourly_rate,profession,specialty,job_city,job_state,start_date, auto_offers, end_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?)`,
      [
        lastJobId,
        orgaId,
        jobData.jobId,
        jobData.JobName,
        jobData.Description,
        jobData.Type,
        jobData.Term,
        jobData.Length,
        jobData["Taxable hourly rate"],
        jobData.profession,
        jobData.specialty,
        jobData.city,
        jobData.state,
        jobData["start date"],
        jobData["auto offer"],
        jobData["end date"]
      ]
    );
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
      console.log("Nooo");
      return { id: null };
    }
  } catch (err) {
    console.error('Error getting new job ID:', err);
    throw err;
  }
}

module.exports.updateJob = async function (orgaId, jobData) {
  try {

    const [existJob] = await pool.query(
      `SELECT * FROM jobs WHERE job_id = ? AND organization_id = ?`,
      [jobData.jobId, orgaId]
    );


    if (existJob.length === 0) {
      console.log(`no job with ID ${jobData.jobId} exist`);
      return;
    }

    const isValid = await validateFields(jobData);
    if (!isValid) {
      console.log("iinvalid field");
      return;
    }

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
            start_date = ?, 
            auto_offers = ?, 
            end_date = ? 
        WHERE job_id = ? and organization_id=?`,
      [
        jobData.JobName,
        jobData.Description,
        jobData.Type,
        jobData.Term,
        jobData.Length,
        jobData["Taxable hourly rate"],
        jobData.profession,
        jobData.specialty,
        jobData.city,
        jobData.state,
        jobData["start date"],
        jobData["auto offer"],
        jobData["end date"],
        jobData.jobId,
        orgaId


      ]
    );
    console.log('Job updated successfully');

    return result;
  } catch (err) {
    console.error('Error updating job:', err);
    throw err;
  }
};

async function validateFields(jobData) {
  try {
    const formatValidations = {
      'JobName': value => typeof value === 'string',
      'Description': value => typeof value === 'string',
      'Type': value => typeof value === 'string',
      'Term': value => typeof value === 'string',
      'Length': Number.isInteger,
      'Taxable hourly rate': value => typeof value === 'number' && value >= 0,
      'profession': value => typeof value === 'string',
      'specialty': value => typeof value === 'string',
      'city': value => typeof value === 'string',
      'state': value => typeof value === 'string',
      'start date': value => !isNaN(Date.parse(value)),
      'end date': value => !isNaN(Date.parse(value)),
      'auto offer': Number.isInteger
    };

    for (let field in formatValidations) {
      if (!formatValidations[field](jobData[field] || '')) {
        //report(`Invalid format for field: ${field}`);
        return false;
      }
    }


    return true;
  } catch (err) {
    console.error('Error validating fields:', err);
    throw err;
  }
};