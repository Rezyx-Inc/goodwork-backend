const { pool } = require("./mysql.js")

module.exports.getStripeId = async function (stripeId){

	const [result, fields] = await pool.query("SELECT stripeId FROM users WHERE id=?;", [userId])
	return result[0]
}

module.exports.insertStripeId = async function (stripeId, userId){

	const [result, fields] = await pool.query("UPDATE users SET stripeAccountId=? WHERE id=?;", [stripeId, userId])
	return result
}

module.exports.checkStripeId = async function (stripeId){

	const [result, fields] = await pool.query("SELECT stripeId FROM users WHERE stripeId=?;", [stripeId])
	return result
}

// Customers
module.exports.insertCustomerStripeId = async function (stripeId, email){

	const [result, fields] = await pool.query("UPDATE users SET stripeAccountId=? WHERE email=?;", [stripeId, email])
	return result
}

module.exports.setOfferStatus = async function (offerId, status){

	const [result, fields] = await pool.query("UPDATE offers SET status=? WHERE oid=?;", [offerId, status])
	return result
}