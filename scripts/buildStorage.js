require("dotenv").config();
var fs = require("fs");
var path = require("path");
//const db = require(path.join(__dirname,"../database.js"));

const folderName = path.join(process.cwd(),process.env.FILES_STORAGE_DIR_NAME);

console.log("building storage at "+ folderName);

try {
  if (!fs.existsSync(folderName)) {
    fs.mkdirSync(folderName);
    console.log("Storage ready.")
  }else{
  	console.log("Storage exist")
  }
} catch (err) {
  console.error("Unable to build storage", err);
}