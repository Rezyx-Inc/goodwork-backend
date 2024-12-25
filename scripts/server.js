/****** some notes: *****

 - max 25 files per user
 - file size max 5mb
 - max upload at once 130mb
 - integrations server is useful only in dev

*/

require("dotenv").config();

const mongoose = require("mongoose");
const express = require("express");
var bodyParser = require("body-parser");
const cors = require("cors");
const app = express();

var { report } = require("./src/set.js");

app.use(
  cors({
    origin: ["http://127.0.0.1:8000", "http://localhost:8000", "http://127.0.0.1", "http://localhost"],
  })
);

const docsRoute = require('./src/routes/docs');
const integrationsRoute = require('./src/routes/integrations');
const paymentsRoute = require('./src/routes/Payments');
const orgsRoute = require('./src/routes/orgs')
const sheetRoute = require('./src/routes/sheetRoute');

const createGlobalRuleFields = require('./src/functions/createGlobalRuleFields.js');

app.use(bodyParser.json({ limit: "130mb" }));
app.use(process.env.FILE_API_BASE_PATH, docsRoute);
app.use(process.env.INTEGRATIONS_API_BASE_PATH, integrationsRoute);
app.use(process.env.PAYMENTS_API_BASE_PATH, paymentsRoute);
app.use(process.env.ORGANIZATIONS_API_BASE_PATH, orgsRoute);
app.use(process.env.SHEET_API_BASE_PATH, sheetRoute); 


// Root Route
app.get("/", (req, res) => {
  res.redirect("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});

//Connect to DB
mongoose
  .connect(process.env.MONGODB_FILES_URI)
  .then(async () => {

    console.log("SERVER START : Connected to MongoDB");
    createGlobalRuleFields();

  })
  .catch((error) => {

    console.error("Error connecting to MongoDB:", error);
    await report('error', 'server.js', 'MongoDB connection : ' + error);

  });

app.listen(process.env.FILE_API_PORT);

// catches uncaught exceptions
process.on("uncaughtException", async function (ercc) {
  console.log(ercc);
  await report('error', 'server.js', "Unexpected Server exit | uncaughtException");
});
