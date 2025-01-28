require("dotenv").config();  //To read environment variables from the .env file

const WEBHOOK_URL = process.env.WEBHOOK_URL; // Fetch url from the env file
const axios = require('axios'); //To make API calls

//Function to report errors, through the API (Weebhook url)
module.exports.report = async (type, indicator, msg) => {

  if(process.env.ENABLE_REPORTER){
    type.toLowerCase() == "error" ? type=":rotating_light: ERROR :rotating_light:": type.toUpperCase();
    type.toLowerCase() == "notification" ? type=":loudspeaker: notification :loudspeaker:": type.toUpperCase();

    try{

      await axios.post(WEBHOOK_URL, {
        content: type + ' :arrow_right: :arrow_right: :arrow_right: ' +indicator.toUpperCase() + ' :arrow_right: :arrow_right: :arrow_right: ' + msg + ' @ ' + process.env.APP_URL,
      });

    }catch(e){

      //Log in case of failure in reporting
      console.log("Unable to log errors", e);
    }

  }else{
    console.log("REPORTER DISABLED =>", type + ' ===> ' +indicator.toUpperCase() + ' ===> ' + msg + ' @ ' + process.env.APP_URL)
  }
}
