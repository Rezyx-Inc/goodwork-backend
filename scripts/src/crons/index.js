require("dotenv").config();

var cron = require("node-cron");
var laboredge = require("./laboredge.js");
var { report } = require("../set.js");
//var gsheet = require("../gSheet/index.js").main;
//var gSheetAuth = require("../gSheet/services/authService.js").authorize;

var ardorHealth = require("./ardorHealth.js");

// Uncomment to seed accounts
//laboredge.seed(999);

//report("Hello from cron")
//laboredge.update();
// (async () => {
//     console.log("Exec imm");
//     await gSheetAuth(true);
// })();

if (process.env.ENABLE_CRONS === "true") {
    console.log("Starting integrations cron jobs.");

    // Check newly added integrations every 10 minutes
    //cron.schedule("*/10 * * * *", () => {
        //console.log("Checking new integrations");
        // laboredge.init();
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
    //     // laboredge.update();
    //     gsheet();
    });

    // Check other updates every day at 1 am
    cron.schedule("0 1 * * *", () => {
        console.log("Checking other updates");
        //laboredge.updateOthers();
    });

    process.on("SIGTERM", function () {
        report("Unexpected Crons exit");
    });
} else {
    console.log("CRON jobs disabled.");
}
