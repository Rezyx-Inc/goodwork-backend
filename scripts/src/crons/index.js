var cron = require('node-cron');

// will reside here for now, to be moved to crons/index.js
console.log("Starting integrations cron jobs.")
var integrations = cron.schedule('* * * * *', () => {
  console.log('running a task every minute');
});