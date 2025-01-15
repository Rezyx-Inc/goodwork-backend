require("dotenv").config();

const WEBHOOK_URL = process.env.WEBHOOK_URL;
const axios = require('axios');

module.exports.report = async (type, indicator, msg) => {

  if(process.env.ENABLE_REPORTER == true){
    type.toLowerCase() == "error" ? type=":rotating_light: ERROR :rotating_light:": type.toUpperCase();
    type.toLowerCase() == "notification" ? type=":loudspeaker: notification :loudspeaker:": type.toUpperCase();

    try{

      await axios.post(WEBHOOK_URL, {
        content: type + ' :arrow_right: :arrow_right: :arrow_right: ' +indicator.toUpperCase() + ' :arrow_right: :arrow_right: :arrow_right: ' + msg + ' @ ' + process.env.APP_URL,
      });

    }catch(e){
      console.log("Unable to log errors", e);
    }

  }else{
    console.log("REPORTER DISABLED =>", type + ' ===> ' +indicator.toUpperCase() + ' ===> ' + msg + ' @ ' + process.env.APP_URL)
  }
}
