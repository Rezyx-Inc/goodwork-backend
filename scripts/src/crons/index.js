var cron = require('node-cron');
var laboredge = require('./laboredge.js');

// Uncomment to seed accounts
//laboredge.seed(999);
laboredge.init()

// will reside here for now, to be moved to crons/index.js
console.log("Starting integrations cron jobs.")
var integrations = cron.schedule('* * * * *', () => {
  console.log('running a task every minute');
});