require("dotenv").config();
const mysql = require("mysql2")

module.exports.pool = mysql.createPool(
'mysql://u728345844_goodwork:PjZ:G2w&9hW[@localhost:3306/u728345844_goodwork'
).promise();