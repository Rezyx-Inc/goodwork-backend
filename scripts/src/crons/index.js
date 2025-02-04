//To configure environment variables from a .env file
require("dotenv").config();

//import all required libraries and/or modules
var cron = require("node-cron"); // Used to schedule cron jobs
var { vitalink } = require("../integrations/laboredge");
var { report } = require("../set.js");

//var gsheet = require("../gSheet/index.js").main;
//var gSheetAuth = require("../gSheet/services/authService.js").authorize;

var ardorHealth = require("../integrations/ardorHealth.js");

// Uncomment to seed accounts
//vitalink.seed(999);

//report("Hello from cron")
//vitalink.update();
// (async () => {
//     console.log("Exec imm");
//     await gSheetAuth(true);
// })();

//Checking if cron jobs are enabled
if (process.env.ENABLE_CRONS) {
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
        ardorHealth.init();
    //     // vitalink.update();
    //     gsheet();
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
