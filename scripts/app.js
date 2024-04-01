const pm2 = require('pm2');
var path = require("path");

const script = path.join(path.dirname(__filename), "server.js")

const { argv } = require('node:process');

pm2.connect(function(err) {
  if (err) {
    console.error(err)
    process.exit(2)
  }

  if(argv[2] === "start"){

    // Watch is disabled by default for now because it is unstable - and i don't have time to fix it
    var watch = false;
    if(argv[3]){
      argv[3] == "true" ? watch = true : watch = false
    }
    
    console.log("Starting the file management service.", watch)

    pm2.start({
      script    : script,
      name      : 'files',
      watch     : watch
    }, function(err, apps) {
      if (err) {
        console.error(err)
        return pm2.disconnect()
      }
      return pm2.disconnect()
    })
  
  }else if(argv[2] === "stop"){

    console.log("Stopping the file management service")

    // List the processes managed by PM2 then delete
    pm2.list((err, list) => {
      if(list.length == 0){
        console.log("No process to stop.")
        pm2.disconnect()
      }    
      if(!err){

        pm2.delete('files', (err, proc) => {
          // Disconnects from PM2
          pm2.disconnect()
        })

      }else{
        console.log("Unable to list processes", err)
        pm2.disconnect()
      }
    })

  }else{
    console.error("Invalid argument")
  }
  
})


/*
  some notes
  This script here is supposed to manage storage types, either disk (using path in docs.files model) or mongodb, or any other type of storage.
  Each storage is yet to be defined, for now it is only mongodb, although a disk storage is already set by default by creating a directory for it since it will most likely be used very soon.

*/