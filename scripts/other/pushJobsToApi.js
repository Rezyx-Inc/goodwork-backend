var axios = require("axios");
const csv=require('csvtojson');
var path = require("node:path");
const _ = require('lodash');
var moment = require("moment");

(async function (){

  const currentHeaders = ['Org Job Id','Type','Employment','Profession','Specialty','$/hr','$wk','State','City','Resume','Shift Time','Hrs/Wk','Guaranteed Hrs/wk','Hrs/Shift','Shift/Wk','Wks/Contract','Start Date','End Date'];
  const dbHeaders = ['job_id','job_type','terms','profession','preferred_specialty','actual_hourly_rate','weekly_pay','job_state','job_city','is_resume','preferred_shift_duration','hours_per_week','guaranteed_hours','hours_shift','weeks_shift','preferred_assignment_duration','start_date','end_date'];

  var json = await csv().fromFile(path.join(__dirname,"Test Jobs.csv"));

  var updatedObj = _.map(json, function(currObj) {

      var newObj = {};
      //console.log(currObj)
      Object.keys(currObj).forEach( function(key, index, value) {

        //console.log(_.includes(currentHeaders,key), key);
        if(_.includes(currentHeaders,key)) {

          newObj[dbHeaders[index]] = currObj[key];
        }

      });

      newObj.api_key = 'OWUzOGRjODA5Yjg3YmZhNzk0ZjFjNDFiYzczNTY1MDYzODViOWU1ZTFjNjQ5ZmE1';
      newObj.organization_id = "GWU000006";

      newObj.start_date = newObj.start_date.replace(/[/-]/g, "-");
      newObj.end_date = newObj.end_date.replace(/[/-]/g, "-");

      newObj.start_date = moment(newObj.start_date, "MM-DD-YYYY");
      newObj.end_date = moment(newObj.end_date, "MM-DD-YYYY");

      newObj.is_resume == "TRUE" ? newObj.is_resume = "1" : newObj.is_resume = "0";

      return newObj;
  });

  for(let i=0; i<updatedObj.length;i++){

    axios.post('http://localhost:8000/api/organization-add-job', updatedObj[i])
    .then(function (response) {
      console.log(response.data.success);
    })
    .catch(function (error) {
      console.log(error);
    });
    const delay = ms => new Promise(resolve => setTimeout(resolve, ms))
    await delay(1000);

  }

})();
