/*
  some notes
  This script here is supposed to manage storage types, either disk (using path in docs.files model) or mongodb, or any other type of storage.
  Each storage is yet to be defined, for now it is only mongodb, although a disk storage is already set by default by creating a directory for it since it will most likely be used very soon.

  This script also acts as an entry point for integrations cron jobs

*/

//Import required libraries and/or modules
const pm2 = require('pm2'); // To connect to PM2
var path = require("path"); // To work with file paths and directories
var cron = require('node-cron'); //To shedule tasks

//To read environment variables from the .env file
require("dotenv").config();

var { report } = require('./src/set.js')


const server = path.join(path.dirname(__filename), "server.js")
const crons = path.join(path.dirname(__filename), "/src/crons/index.js")
const webhooks = path.join(path.dirname(__filename), "/src/webhooks/stripe.js")

const { argv } = require('node:process');

//Connecting to pm2
pm2.connect( async function(err) {

  //Log and report if failed to connect to pm2
  if (err) {
    console.error(err)
    await report('error','app.js',"Unable to connect PM2")
    process.exit(2)
  }

  //Check if we can start the watch
  if(argv[2] === "start"){

    // Watch is disabled by default for now because it is unstable - and i don't have time to fix it
    var watch = false;
    if(argv[3]){
      argv[3] == "true" ? watch = true : watch = false
    }

    console.log("Starting the File Management service.")

    // Files management
    await pm2.start({
      script    : server,
      name      : 'files',
      watch     : watch
    }, async function(err, apps) {

      //Log and return, if failed to start the server
      if (err) {
        console.error(err)
        await report('error','app.js',"Unable to start File management")
        return pm2.disconnect()

      }
      await report("notification", 'app.js', 'Started Files management service');
      return pm2.disconnect() //Disconnect from pm2
    });

    console.log("Starting the Webhooks service")
    // Webhook management
    await pm2.start({
      script    : webhooks,
      name      : 'webhooks',
      watch     : watch
    }, async function(err, apps) {

      //Log and return, if failed to start the webhook server
      if (err) {
        console.error(err)
        await report('error','app.js',"Unable to start Webhook service")
        return pm2.disconnect()

      }

      await report("notification", 'app.js', 'Started Webhooks service');
      return pm2.disconnect()
    });

    // Crons management
    console.log(process.env.ENABLE_CRONS)

    //Check if crons are enabled
    if(process.env.ENABLE_CRONS){
      console.log("Starting the Cron service.")
      await pm2.start({
        script    : crons,
        name      : 'crons',
        watch     : watch
      }, async function(err, apps) {
        //Log and return, if failed to start the cron server
        if (err) {
          console.error(err)
          await report('error','app.js',"Unable to start Cron service")
          return pm2.disconnect()
        }
        await report("notification", 'app.js', 'Started Crons service');
        return pm2.disconnect()
      });
    }

  }else if(argv[2] === "stop"){ //Stop the cron service

    console.log("Stopping the file management and integration services")

    // List the processes managed by PM2 then delete
    await pm2.list(async (err, list) => {
      if(list.length == 0){
        console.log("No process to stop.")
        pm2.disconnect()
      }
      if(!err){

        for(proc of list){
          pm2.delete(proc.name, async (err, proc) => {

            await report("notification", 'app.js', 'Stopped a service');
            // Disconnects from PM2
            pm2.disconnect()
          });
        }

      }else{

        //Log and return in case of error
        console.log("Unable to list processes", err)
        await report('error','app.js',"Unable to list PM2 processes")
        pm2.disconnect()
      }
    })

  }else{
    console.error("Invalid argument")
    await report('error','app.js',"Invalid app argument")
  }

})
