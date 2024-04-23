var cron = require('node-cron');
var laboredge = require('./laboredge.js');

// Uncomment to seed accounts
//laboredge.seed(999);

if(process.env.ENABLE_CRON){
  console.log("Starting integrations cron jobs.")

  // Check newly added integrations every 10 minutes
  cron.schedule('*/10 * * * *', () => {
    console.log('Checking new integrations');
    laboredge.init();
  });

  // Check updates every hour
  cron.schedule('0 * * * *', () => {
    console.log('Checking new integrations');
    laboredge.update();
  });

  // Check other updates every day at 1 am
  cron.schedule('0 1 * * *', () => {
    console.log('Checking new integrations');
    laboredge.updateOthers();
  });

}else{
  console.log("CRON jobs disabled.")
}