//Import required libraries and/or modules
require("dotenv").config(); //To read environment variables from the .env file
const mysql = require("mysql2")

//Connect to MySQL db
module.exports.pool = mysql.createPool({
	host: process.env.DB_HOST,
	port: process.env.DB_PORT,
	user: process.env.DB_USERNAME,
	password: process.env.DB_PASSWORD,
	database: process.env.DB_DATABASE
}
).promise();
