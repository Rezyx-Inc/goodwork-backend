//Import all required libraries and/or modules
const { pool } = require("./mysql.js");
var _ = require('lodash');
var moment = require('moment');
const { validateFields, getNewJobId } = require('../helpers/sheetHelper.js');
const { getNextUpRecruiter } = require('../helpers/integrationHelper.js');

//Function to get the Stripe Account Id using the user id
module.exports.getStripeId = async function (userId) {

    const [result, fields] = await pool.query(
        "SELECT stripeAccountId FROM users WHERE id=?;",
        [userId]
    );

    return result[0];
};

//Function to update the Stripe Account Id using the stripe id and user id
module.exports.insertStripeId = async function (stripeId, userId) {

    const [result, fields] = await pool.query(
        "UPDATE users SET stripeAccountId=? WHERE id=?;",
        [stripeId, userId]
    );

    return result;
};

//Function to check if a particular Stripe Account Id exists in the users
module.exports.checkStripeId = async function (stripeId) {

    const [result, fields] = await pool.query(
        "SELECT stripeAccountId FROM users WHERE stripeAccountId=?;",
        [stripeId]
    );

    return result;
};

// Customers
//Function to update the Stripe Account Id using the stripe id and email
module.exports.insertCustomerStripeId = async function (stripeId, email) {

    const [result, fields] = await pool.query(
        "UPDATE users SET stripeAccountId=? WHERE email=?;",
        [stripeId, email]
    );

    return result;
};

//Function to update the offer status based on offerId
module.exports.setOfferStatus = async function ( offerId, status, is_payment_done, is_payment_required) {

    let query = "UPDATE offers SET status=?";
    if (is_payment_required != null) query += ", is_payment_required=?";
    if (is_payment_done != null) query += ", is_payment_done=?";
    query += " WHERE id=?";

    let queryParams = [status];
    if (is_payment_required != null) queryParams.push(is_payment_required);
    if (is_payment_done != null) queryParams.push(is_payment_done);
    queryParams.push(offerId);

    const [result, fields] = await pool.query(query, queryParams);

    return result;
};

//  queries for worker payment
module.exports.getWorkerDetails = async function (workerId) {

    const [result, fields] = await pool.query(
        "SELECT stripeAccountId, account_tier FROM users WHERE id=?;",
        [workerId]
    );

    return result;
};

//Function to get offer details of a worker
module.exports.getOfferDetails = async function (offerId) {

    const [result, fields] = await pool.query(
        "SELECT worker_user_id, total_organization_amount, worker_payment_status FROM offers WHERE id=?;",
        [offerId]
    );

    return result;
};

//Function to update the Payment status as done (paid)
module.exports.setWorkerPaymentStatus = async function (offerId) {

    const [result, fields] = await pool.query(
        "UPDATE offers SET worker_payment_status = 'Done' WHERE id = ?;",
        [offerId]
    );

    return result;
};

// Queries for Laboredge integration
module.exports.getLaboredgeLogin = async function (userId) {

    const [result, fields] = await pool.query(
        "SELECT * from laboredge WHERE user_id=?;",
        [userId]
    );

    return result;
};

//(Get Info)
module.exports.closeImportedJobs = async function (imported_id) {

    const [result, fields] = await pool.query(
        "UPDATE jobs SET is_open=0, is_closed=1 WHERE import_id=?;",
        [imported_id]
    );

    return result;
};

module.exports.updateImportedJob = async function (importData) {
    
    // console.log("Inside update imported job method");
    var floatReq = "";

    if (
        importData.floatingReqUnits == "" ||
        importData.floatingReqUnits == null
    ) {
        floatReq = 0;
    } else {
        floatReq = 1;
    }

    //Job description -- don't update
    let description = importData.description;

    let hoursPerShift = importData.scheduledHrs1 / importData.shiftsPerWeek1;

    const query = `UPDATE jobs SET import_id = ?,job_name = ?,description = ?,sign_on_bonus = ?,job_type = ?,start_date = ?,end_date = ?,preferred_shift_duration = ?,is_open = ?,active = ?,is_closed = ?,float_requirement = ?,weeks_shift = ?,hours_per_week = ?,hours_shift = ?,profession = ?,specialty = ?,actual_hourly_rate = ?,as_soon_as = ?,auto_offers = ?,dental = ?,eligible_work_in_us = ?,facility_city = ?,facility_state = ?,four_zero_one_k = ?,health_insaurance = ?,is_hidden = ?,is_resume = ?,on_call = ?,professional_licensure = ?,tax_status = ?,vision = ? WHERE id = ?`;

    const values = [
        importData.imported_id ?? null, // import_id
        importData.jobTitle ?? null, // job_name
        description ?? null, // description
        importData.signOnBonus ?? null, // sign_on_bonus
        importData.jobType ?? null, // job_type
        importData.startDate ?? null, // start_date
        importData.endDate ?? null, // end_date
        importData.duration ?? null, // preferred_shift_duration
        1, // is_open
        1, // active
        0, // is_closed
        floatReq ?? null, // float_requirement
        importData.shiftsPerWeek1 ?? null, // weeks_shift
        importData.scheduledHrs1 ?? null, // hours_per_week
        hoursPerShift ?? null, // hours_shift
        importData.profession ?? null, // profession
        importData.specialty ?? null, // specialty
        importData.hourlyPay ?? null, // actual_hourly_rate
        0, // as_soon_as
        0, // auto_offers
        0, // dental
        0, // eligible_work_in_us
        (importData.facility_city != null) ? importData.facility_city : "", // facility_city
        (importData.facility_state != null) ? importData.facility_state : "", // facility_state
        0, // four_zero_one_k (401k)
        0, // health_insurance
        0, // is_hidden
        0, // is_resume
        0, // on_call
        (importData.professional_licensure != null) 
        ? importData.professional_licensure : "", // professional_licensure
        "", // tax_status
        0, // vision
        importData.id ?? null // id for WHERE clause (row to update)
    ];

    // Execute the query using your database library 
    const results = pool.execute(query, values)
    .then(([res]) => {
        // console.log('Update successful:', res);
        return res;
    })
    .catch((error) => {
        console.error('Error updating record:', error);
    });

    return results;
};
//Function to insert imported jobs into our db
module.exports.addImportedJob = async function (importData) {

    if (importData.durationType != "WEEKS") {
        return false;
    }

    var floatReq = "";

    if (
        importData.floatingReqUnits == "" ||
        importData.floatingReqUnits == null
    ) {
        floatReq = 0;
    } else {
        floatReq = 1;
    }

    //Job description
    let description =
        importData.postingId +
        " " +
        importData.description +
        " " +
        importData.shift;

    let hoursPerShift = importData.scheduledHrs1 / importData.shiftsPerWeek1;

    var is_open=0,is_closed=0,active=0,is_hidden=0;
    
    if(importData.jobStatus === "Open"){
        is_open = 1;
        active = 1;
        is_closed = 0;
        is_hidden=0;
    }else if(importData.jobStatus === "Closed"){
        is_open = 0;
        active = 0;
        is_closed = 1;
        is_hidden = 0;
    }

    const [result, fields] = await pool.query(
        "INSERT INTO jobs (id,import_id, job_name, description, sign_on_bonus, job_type, start_date, end_date, preferred_shift_duration, is_open, active, is_closed, float_requirement, weeks_shift, hours_per_week, hours_shift, profession, specialty, actual_hourly_rate, as_soon_as, auto_offers, dental, eligible_work_in_us, facility_city, facility_state, four_zero_one_k, health_insaurance, is_hidden, is_resume, on_call, professional_licensure, tax_status, vision) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);",
        [
            importData.id,
            importData.imported_id,
            importData.jobTitle,
            description,
            importData.signOnBonus,
            importData.jobType,
            importData.startDate,
            importData.endDate,
            importData.duration,
            is_open, //is_open
            active, //active
            is_closed, //is_closed
            floatReq,
            importData.shiftsPerWeek1,
            importData.scheduledHrs1,
            hoursPerShift,
            importData.profession,
            importData.specialty,
            importData.hourlyPay,
            0, //as_soon_as
            0, //auto_offers
            0, //dental
            0, //eligible_work_in_us
            (importData.facility_city != null) ? importData.facility_city : "",
            (importData.facility_state != null) ? importData.facility_state : "",
            0, //401k
            0, //health_insurance
            is_hidden, //is_hidden
            0, //is_resume
            0, //on_call
            (importData.professional_licensure != null) ? importData.professional_licensure : "",
            "",//tax_status
            0, //vision
        ]
    );

    return result;
};

// Query to get specialties
module.exports.getSpecialties = async function () {

    const [result, fields] = await pool.query(
        "SELECT short_name, full_name,id from specialities;"
    );

    return result;
};

// Query to get non-clinical specialties
module.exports.getNcSpecialties = async function () {

    const [result, fields] = await pool.query(
        "SELECT * from keywords where filter='Non-ClinicalProfession';"
    );

    return result;
};

//(Get info)
module.exports.importArdorHealthJobs = async function (ardorOrgId, importData, draft, update) {

    // We keep update == false case for the sake of future integrations
    if(update == false){

        var active, is_open;
        if(draft == true){
            active = 1;
            is_open = 0;
        }else{
            active = 1;
            is_open = 1;
        }

        if (importData.type != "Non-Clinicial"){
            importData.type = "Clinical";
        }

        var hours_per_week = null, hourlyPay = null;

        if(importData.hours.length == 0 && importData.shift.shiftAmount && importData.shift.hrsShift){

            hours_per_week = importData.shift.shiftAmount * importData.shift.hrsShift;

        }else if(Number(importData.hours[0]) == 0 && importData.shift.shiftAmount && importData.shift.hrsShift){

            hours_per_week = importData.shift.shiftAmount * importData.shift.hrsShift;

        }else{

            hours_per_week = Number(importData.hours[0]);
        }

        if(!importData.shift.shiftAmount){
            importData.shift.shiftAmount = null;
        }

        if(hours_per_week == 0){
            hourlyPay = null;
        }else{
            hourlyPay = (Number(importData.weeklyrate[0]) / hours_per_week).toFixed(2);
        }


        if(_.isArray(importData.Specialty)){
            importData.Specialty = importData.Specialty[0];
        }

        importData.startdate = moment(importData.startdate[0]).format("YYYY-MM-DD");
        importData.enddate = moment(importData.enddate[0]).format("YYYY-MM-DD");

        let id = await getNewJobId(pool);

        if(!importData.shift.hrsShift){
            importData.shift.hrsShift = 0;
        }

        const query = await pool.query(
            "INSERT INTO jobs (professional_licensure, facility_state, facility_city, terms, tax_status, id, organization_id, created_by, job_id, job_name, job_city, job_state, weeks_shift, hours_shift, preferred_shift_duration, start_date, end_date, hours_per_week, weekly_pay, description, job_type, active, is_open, is_closed, profession, preferred_specialty, actual_hourly_rate ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);",
            [
                importData.state[0],
                importData.state[0],
                importData.city[0],
                "Contract (Travel or Local)",
                "",
                id,
                ardorOrgId,
                ardorOrgId,
                importData.jobid[0],
                importData.jobtitle[0],
                importData.city[0],
                importData.state[0],
                Number(importData.shift.shiftAmount).toFixed(2) || null,
                Number(importData.shift.hrsShift).toFixed(1) || null,
                importData.shift.shiftTimeOfDay || null,
                importData.startdate,
                importData.enddate,
                hours_per_week.toFixed(0),
                importData.weeklyrate[0],
                importData.description,
                importData.jobType,
                active,
                is_open,
                0,
                importData.license[0],
                importData.Specialty,
                hourlyPay,
            ]
        );

        return query

    }else{

        // Check for existing jobs in the database
        var existingJob = await pool.query(
        "SELECT * from jobs where job_id='"+ importData.jobid[0] +"' AND organization_id='"+ardorOrgId+"';"
        ,).then (results => {return results}).catch(e => {console.log(e); return false});

        // If the job doesn't exist, add it
        if(existingJob[0].length == 0){

            var active, is_open;
            if(draft == true){
                active = 1;
                is_open = 0;
            }else{
                active = 1;
                is_open = 1;
            }

            if (importData.type != "Non-Clinicial"){
                importData.type = "Clinical";
            }

            var hours_per_week = null, hourlyPay = null;

            if(importData.hours.length == 0 && importData.shift.shiftAmount && importData.shift.hrsShift){

                hours_per_week = importData.shift.shiftAmount * importData.shift.hrsShift;

            }else if(Number(importData.hours[0]) == 0 && importData.shift.shiftAmount && importData.shift.hrsShift){

                hours_per_week = importData.shift.shiftAmount * importData.shift.hrsShift;

            }else{

                hours_per_week = Number(importData.hours[0]);
            }

            if(!importData.shift.shiftAmount){
                importData.shift.shiftAmount = null;
            }

            if(hours_per_week == 0){
                hourlyPay = null;
            }else{
                hourlyPay = (Number(importData.weeklyrate[0]) / hours_per_week).toFixed(2);
            }


            if(_.isArray(importData.Specialty)){
                importData.Specialty = importData.Specialty[0];
            }

            importData.startdate = moment(importData.startdate[0]).format("YYYY-MM-DD");
            importData.enddate = moment(importData.enddate[0]).format("YYYY-MM-DD");
            var created_at = moment().format("YYYY-MM-DD");
            var updated_at = moment().format("YYYY-MM-DD");

            let id = await getNewJobId(pool);

            if(!importData.shift.hrsShift){
                importData.shift.hrsShift = 0;
            }

            let recruiterId = await getNextUpRecruiter(ardorOrgId);

            const query = await pool.query(
                "INSERT INTO jobs (created_at, updated_at, professional_licensure, facility_state, facility_city, tax_status, id, organization_id, created_by, job_id, job_name, job_city, job_state, weeks_shift, hours_shift, preferred_shift_duration, start_date, end_date, hours_per_week, weekly_pay, description, job_type, active, is_open, is_closed, profession, preferred_specialty, actual_hourly_rate, recruiter_id ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);",
                [
                    created_at,
                    updated_at,
                    importData.state[0],
                    importData.state[0],
                    importData.city[0],
                    "",
                    id,
                    ardorOrgId,
                    ardorOrgId,
                    importData.jobid[0],
                    importData.jobtitle[0],
                    importData.city[0],
                    importData.state[0],
                    Number(importData.shift.shiftAmount).toFixed(2) || null,
                    Number(importData.shift.hrsShift).toFixed(1) || null,
                    importData.shift.shiftTimeOfDay || null,
                    importData.startdate,
                    importData.enddate,
                    hours_per_week.toFixed(0),
                    importData.weeklyrate[0],
                    importData.description,
                    importData.jobType,
                    active,
                    is_open,
                    0,
                    importData.license[0],
                    importData.Specialty,
                    hourlyPay,
                    recruiterId
                ]
            );

            return query

        // If the job exists, update it
        }else{

            // Pre format data
            var active, is_open;

            if(draft == true){
                active = 1;
                is_open = 0;
            }else{
                active = 1;
                is_open = 1;
            }

            if (importData.type != "Non-Clinicial"){
                importData.type = "Clinical";
            }

            var hours_per_week = null, hourlyPay = null;

            if(importData.hours.length == 0 && importData.shift.shiftAmount && importData.shift.hrsShift){

                hours_per_week = importData.shift.shiftAmount * importData.shift.hrsShift;

            }else if(Number(importData.hours[0]) == 0 && importData.shift.shiftAmount && importData.shift.hrsShift){

                hours_per_week = importData.shift.shiftAmount * importData.shift.hrsShift;

            }else{

                hours_per_week = Number(importData.hours[0]);
            }

            if(!importData.shift.shiftAmount){
                importData.shift.shiftAmount = null;
            }

            if(hours_per_week == 0){
                hourlyPay = null;
            }else{
                hourlyPay = (Number(importData.weeklyrate[0]) / hours_per_week).toFixed(2);
            }


            if(_.isArray(importData.Specialty)){
                importData.Specialty = importData.Specialty[0];
            }

            importData.startdate = moment(importData.startdate[0]).format("YYYY-MM-DD");
            importData.enddate = moment(importData.enddate[0]).format("YYYY-MM-DD");

            if(importData.shift.shiftAmount == null){
                importData.shift.shiftAmount = 0;
            }

            if(!importData.shift.hrsShift){
                importData.shift.hrsShift = 0;
            }

            // Compare both and extract changes
            existingJob = existingJob[0][0];

            var changes = {};

            active.toString() == existingJob.active.toString() ? null : changes.active = active;
            is_open.toString() == existingJob.is_open.toString() ? null : changes.is_open = is_open;
            importData.state[0] == existingJob.professional_licensure ? null : changes.professional_licensure = importData.state[0];
            importData.state[0] == existingJob.facility_state ? null : changes.facility_state = importData.state[0];
            importData.city[0] == existingJob.facility_city ? null : changes.facility_city = importData.city[0];
            importData.jobtitle[0] == existingJob.job_name ? null : changes.job_name = importData.jobtitle[0];
            importData.city[0] == existingJob.job_city ? null : changes.job_city = importData.city[0];
            importData.state[0] == existingJob.job_state ? null : changes.job_state = importData.state[0];
            importData.shift.shiftAmount == Number(existingJob.weeks_shift) ? null : changes.weeks_shift = importData.shift.shiftAmount;
            Math.ceil(Number(importData.shift.hrsShift)) == existingJob.hours_shift ? null : changes.hours_shift = Math.ceil(Number(importData.shift.hrsShift));
            importData.shift.shiftTimeOfDay == existingJob.preferred_shift_duration ? null : changes.preferred_shift_duration = importData.shift.shiftTimeOfDay;
            importData.startdate == moment(existingJob.start_date).format("YYYY-MM-DD") ? null : changes.start_date = moment(importData.startdate).format("YYYY-MM-DD");
            importData.enddate == moment(existingJob.end_date).format("YYYY-MM-DD") ? null : changes.end_date = moment(importData.enddate).format("YYYY-MM-DD");
            hours_per_week.toFixed(0) == existingJob.hours_per_week ? null : changes.hours_per_week = hours_per_week.toFixed(0);
            Number(importData.weeklyrate[0]).toFixed(2) == existingJob.weekly_pay.toFixed(2) ? null : changes.weekly_pay = Number(importData.weeklyrate[0]).toFixed(2);
            importData.description == existingJob.description ? null : changes.description = importData.description;
            importData.jobType == existingJob.job_type ? null : changes.jobType = importData.jobType;
            importData.license[0] == existingJob.profession ? null : changes.profession = importData.license[0];
            importData.Specialty == existingJob.preferred_specialty ? null : changes.preferred_specialty = importData.Specialty;
            hourlyPay == existingJob.actual_hourly_rate ? null : changes.actual_hourly_rate = hourlyPay;

            // If no changes, we are done
            if(_.isEmpty(changes)){

                return true

            // Record the changes if any
            }else{

                // Store the changes object's property names into an SQL query builder and the values in an array
                var theArray = [];
                var queryBuild = "UPDATE jobs SET ";

                const keys = Object.keys(changes);
                for (var i = 0; i < keys.length; i++) {

                    // If it is the last property, do not add a coma at the end
                    if(i == keys.length -1){

                        queryBuild += keys[i] + "= ? ";
                        theArray.push(changes[keys[i]]);

                    }else{
                        queryBuild += keys[i] + "= ?, ";
                        theArray.push(changes[keys[i]]);
                    }
                }

                // Add the end of the query
                queryBuild+= " ,updated_at=? WHERE id = ? AND organization_id = ?;";

                // Add the values for the WHERE clause
                theArray.push(moment().format("YYYY-MM-DD"));
                theArray.push(existingJob.id);
                theArray.push(existingJob.organization_id);

                // Do the update
                const updateQuery = await pool.query( queryBuild, theArray );

                return updateQuery;
            }
        }

    }
};

module.exports.cleanArdorHealthJobs = async function (ardorOrgId, importData) {

    var existingJobs = await pool.query(
        "SELECT * from jobs where job_id <> '' AND organization_id='"+ardorOrgId+"';"
    ,)
    .then (results => {return results})
    .catch(e => {console.log(e); return false});

    if(existingJobs[0].length == 0){
        return true
    }

    existingJobs = existingJobs[0];
    var jobReworked = []

    for (let [index, value] of importData.entries()) {

        importData[index].job_id = importData[index].jobid[0];

        // Alias for jobs
        jobReworked.push(importData[index]);
    }

    var tbd = _.differenceBy(existingJobs, jobReworked, 'job_id');

    for(let i of tbd){

        const updateQuery = await pool.query( `UPDATE jobs SET active=0, is_open=0, is_hidden=1, is_closed=0, updated_at='${ moment().format("YYYY-MM-DD") }' where id='${i.id}'`);
    }

    return true
}

module.exports.updateRecruiterId = async function (orgId){

    var existingJobs = await pool.query(
        `SELECT * from jobs where job_id <> '' AND organization_id='${orgId}';`)
    .then (results => {return results})
    .catch(e => {console.log(e); return false});

    if(existingJobs[0].length == 0){
        return 0
    }

    existingJobs = existingJobs[0];

    for (let i of existingJobs) {
        console.log("Parsing job", i.id);
        let recruiterId = await getNextUpRecruiter(orgId);
        const updateQuery = await pool.query( `UPDATE jobs SET recruiter_id='${recruiterId}', updated_at='${ moment().format("YYYY-MM-DD") }' where id='${i.id}'`);
    }

    return existingJobs.length;
}
