//To read environment variables from the .env file
require("dotenv").config();
var fs = require("fs"); // To read and/or write files
var path = require("path"); // To work with file paths and directories
//const db = require(path.join(__dirname,"../database.js"));

const folderName = path.join(process.cwd(),process.env.FILES_STORAGE_DIR_NAME);

console.log("building storage at "+ folderName);

try {
  //Check and return if the folder already exists
  if (!fs.existsSync(folderName)) {
    fs.mkdirSync(folderName);
    console.log("Storage ready.")
  }else{
  	console.log("Storage exist")
  }
} catch (err) {
  console.error("Unable to build storage", err); // Log the error message
}