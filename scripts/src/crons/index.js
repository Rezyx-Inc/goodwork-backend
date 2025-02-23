//To configure environment variables from a .env file
require("dotenv").config();

//import all required libraries and/or modules
var cron = require("node-cron"); // Used to schedule cron jobs
var { vitalink } = require("../integrations/laboredge");
var { expedientRN } = require("../integrations/ceipal");
var { report } = require("../set.js");

//var gsheet = require("../gSheet/index.js").main;
//var gSheetAuth = require("../gSheet/services/authService.js").authorize;

var ardorHealth = require("../integrations/ardorHealth.js");

if (process.env.ENABLE_CRONS === "true") {
    console.log("Starting integrations cron jobs.");

    // Check newly added integrations every 10 minutes
    //cron.schedule("*/10 * * * *", () => {
        //console.log("Checking new integrations");
        // vitalink.init();
    //});

    // Check newly added integrations every 30 minutes
    // cron.schedule("*/30 * * * *", async () => {
    //   console.log("Refresh Gsheet token");
    //   await gSheetAuth(true);
    // });

    // Check updates every hour
    cron.schedule("0 * * * *", () => {

        console.log("Hourly Checking job updates");
        // ardorHealth.init();
        vitalink.update();

    });

    // Check other updates every day at 1 am
    cron.schedule("0 1 * * *", () => {
        console.log("Checking other updates");
        //vitalink.updateOthers();
    });

    //Handling unexpected termination on SIGTERM signal
    process.on("SIGTERM", function () {
        report("Unexpected Crons exit");
    });
} else {
    console.log("CRON jobs disabled.");
}
