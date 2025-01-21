const { pool } = require("./mysql.js");

module.exports.getStripeId = async function (userId) {

    const [result, fields] = await pool.query(
        "SELECT stripeAccountId FROM users WHERE id=?;",
        [userId]
    );

    return result[0];
};

module.exports.insertStripeId = async function (stripeId, userId) {

    const [result, fields] = await pool.query(
        "UPDATE users SET stripeAccountId=? WHERE id=?;",
        [stripeId, userId]
    );

    return result;
};

module.exports.checkStripeId = async function (stripeId) {

    const [result, fields] = await pool.query(
        "SELECT stripeAccountId FROM users WHERE stripeAccountId=?;",
        [stripeId]
    );

    return result;
};

// Customers
module.exports.insertCustomerStripeId = async function (stripeId, email) {

    const [result, fields] = await pool.query(
        "UPDATE users SET stripeAccountId=? WHERE email=?;",
        [stripeId, email]
    );

    return result;
};

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

module.exports.getOfferDetails = async function (offerId) {

    const [result, fields] = await pool.query(
        "SELECT worker_user_id, total_organization_amount, worker_payment_status FROM offers WHERE id=?;",
        [offerId]
    );

    return result;
};

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

module.exports.closeImportedJobs = async function (imported_id) {

    const [result, fields] = await pool.query(
        "UPDATE jobs SET is_open=0, is_closed=1 WHERE import_id=?;",
        [imported_id]
    );

    return result;
};

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

    let description =
        importData.postingId +
        " " +
        importData.description +
        " " +
        importData.shift;

    let hoursPerShift = importData.scheduledHrs1 / importData.shiftsPerWeek1;

    const [result, fields] = await pool.query(
        "INSERT INTO jobs (import_id, job_name, description, sign_on_bonus, job_type, start_date, end_date, preferred_shift_duration, is_open, active, is_closed, float_requirement, weeks_shift, hours_per_week, hours_shift, profession, specialty, actual_hourly_rate ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);",
        [
            importData.id,
            importData.jobTitle,
            description,
            importData.signOnBonus,
            importData.jobType,
            importData.startDate,
            importData.endDate,
            importData.duration,
            0,
            0,
            0,
            floatReq,
            importData.shiftsPerWeek1,
            importData.scheduledHrs1,
            hoursPerShift,
            importData.profession,
            importData.specialty,
            importData.hourlyPay,
        ]
    );

    return result;
};


module.exports.fetchSpecialities = async function () {
    const [rows] = await pool.execute(
        'SELECT title FROM keywords WHERE filter = "Speciality";'
    );

    return rows.map(row => row.title);
};
