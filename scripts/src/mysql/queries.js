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
    let queryParams = [status];

    if (!_.isNull(is_payment_required)) {
        query += ", is_payment_required=?";
        queryParams.push(is_payment_required);
    }
    if (!_.isNull(is_payment_done)) {
        query += ", is_payment_done=?";
        queryParams.push(is_payment_done);
    }

    query += " WHERE id=?";
    queryParams.push(offerId);

    const [result, fields] = await pool.query(query, queryParams);
    
    return result;
}

