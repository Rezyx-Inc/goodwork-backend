const pm2 = require('pm2');
var path = require("path");
var cron = require('node-cron');
require("dotenv").config();

var { report } = require('./src/set.js')

const server = path.join(path.dirname(__filename), "server.js")
const crons = path.join(path.dirname(__filename), "/src/crons/index.js")
const webhooks = path.join(path.dirname(__filename), "/src/webhooks/stripe.js")

const { argv } = require('node:process');

pm2.connect(function(err) {
  if (err) {
    console.error(err)
    report("Unable to connect PM2")
    process.exit(2)
  }

  if(argv[2] === "start"){

    // Watch is disabled by default for now because it is unstable - and i don't have time to fix it
    var watch = false;
    if(argv[3]){
      argv[3] == "true" ? watch = true : watch = false
    }

    console.log("Starting the File Management service.")

    // Files management
    pm2.start({
      script    : server,
      name      : 'files',
      watch     : watch
    }, function(err, apps) {
      if (err) {
        console.error(err)
        report("Unable to start File management")
        return pm2.disconnect()

      }
      return pm2.disconnect()
    });

    console.log("Starting the Webhooks service")
    // Webhook management
    pm2.start({
      script    : webhooks,
      name      : 'webhooks',
      watch     : watch
    }, function(err, apps) {
      if (err) {
        console.error(err)
        report("Unable to start Webhook service")
        return pm2.disconnect()

      }
      return pm2.disconnect()
    });

    // Crons management
    if(process.env.ENABLE_CRONS){
      console.log("Starting the Cron service.")
      pm2.start({
        script    : crons,
        name      : 'crons',
        watch     : watch
      }, function(err, apps) {
        if (err) {
          console.error(err)
          report("Unable to start Cron service")
          return pm2.disconnect()
        }
        return pm2.disconnect()
      });
    }
  
  }else if(argv[2] === "stop"){

    console.log("Stopping the file management and integration services")

    // List the processes managed by PM2 then delete
    pm2.list((err, list) => {
      if(list.length == 0){
        console.log("No process to stop.")
        pm2.disconnect()
      }    
      if(!err){

        for(proc of list){
          pm2.delete(proc.name, (err, proc) => {
            // Disconnects from PM2
            pm2.disconnect()
          });  
        }

      }else{
        console.log("Unable to list processes", err)
        report("Unable to list PM2 processes")
        pm2.disconnect()
      }
    })

  }else{
    console.error("Invalid argument")
    report("Invalid app argument")
  }
  
})


/*
  some notes
  This script here is supposed to manage storage types, either disk (using path in docs.files model) or mongodb, or any other type of storage.
  Each storage is yet to be defined, for now it is only mongodb, although a disk storage is already set by default by creating a directory for it since it will most likely be used very soon.

  This script also acts as an entry point for integrations cron jobs

*/