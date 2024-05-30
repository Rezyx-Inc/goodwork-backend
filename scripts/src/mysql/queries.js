const { pool } = require("./mysql.js")

module.exports.getStripeId = async function (stripeId){

	const [result, fields] = await pool.query("SELECT stripeAccountId FROM users WHERE id=?;", [userId])
	return result[0]
}

module.exports.insertStripeId = async function (stripeId, userId){

	const [result, fields] = await pool.query("UPDATE users SET stripeAccountId=? WHERE id=?;", [stripeId, userId])
	return result
}

module.exports.checkStripeId = async function (stripeId){

	const [result, fields] = await pool.query("SELECT stripeAccountId FROM users WHERE stripeAccountId=?;", [stripeId])
	return result
}

// Customers
module.exports.insertCustomerStripeId = async function (stripeId, email){

	const [result, fields] = await pool.query("UPDATE users SET stripeAccountId=? WHERE email=?;", [stripeId, email])
	return result
}

module.exports.setOfferStatus = async function (offerId, status, is_payment_done, is_payment_required){
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
}


//  queeries for worker payment 


module.exports.getWorkerDetails = async function (workerId){

	const [result, fields] = await pool.query("SELECT stripeAccountId, account_tier FROM users WHERE id=?;", [workerId])
	return result
}

module.exports.getOfferDetails = async function (offerId){

	const [result, fields] = await pool.query("SELECT worker_user_id, total_employer_amount, worker_payment_status FROM offers WHERE id=?;", [offerId])
	return result
}

module.exports.setWorkerPaymentStatus = async function (offerId) {
    const [result, fields] = await pool.query("UPDATE offers SET worker_payment_status = 'Done' WHERE id = ?;", [offerId]);
    return result;
}


