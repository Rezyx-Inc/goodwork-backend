// Import all required modules (and/or libraries)
// var fs = require('fs'); // To read files (json, in our case)
// var xml2js = require('xml2js'); // To parse XML into JS objects
import fs from 'fs'; // To read files (json, in our case)
import xml2js from 'xml2js'; // To parse XML into JS objects
// const axios = require('axios').default;
import axios from 'axios'; // To fetch data from a url
import fetchSpecialities from '../mysql/queries.js';

// Load specialties from the database
var specialties = await fetchSpecialities.fetchSpecialities();

var xmlData = [];


  // Async function for setting property values to parse accordingly
  (async () => {
    let options = {
      explicitArray: true, // Keep arrays for elements even if they have a single child
      explicitRoot: false, // Remove the root wrapper
      attrkey: "$", // Use "$" for attributes
      charkey: "_", // Use "_" for character data
    };

    //using the Parser function in the xml2js module for conversion, using the above options
    var parser = new xml2js.Parser(options);

    // Getting the xml data from the URL using axios
    let { data } = await axios.get('https://jobs.ardorhealth.com/feeds/RN.xml');

    //Converting the fetched data into Buffer for further processing
    var parsedXML = Buffer.from(data);

    //var parsedXML = fs.readFileSync(__dirname + '/RN.xml');

    // Final step to parse the XML into JS object
    await parser.parseString(parsedXML, async function (err, result) {

      await Object.keys(result).forEach((key) => {

        // The jobs stored in xmlData object for further mapping
        return xmlData = result[key];
      })
    });

    // Array to map jobs by Specialties
    var found = [];

    // Array with Well formatted shifts information (not used)
    var goodShift = [];

    // Array with Badly formatted shift information (not used)
    var badShift = [];

    //Iterate through all jobs and map them according to the pre-defined rules of good or bad formatting
    for (let [index, value] of xmlData.entries()) {

      // Alias for jobs
      var job = xmlData[index];

      if (job.shift.length) {
        job.shift[0].toLowerCase().match(/([0-9]x[0-9])/) ? xmlData[index].shift = formatGoodShifts(job) : xmlData[index].shift = formatBadShifts(job);
      }

      const specialtyMap = {
        "(stepdown)": "Stepdown",
        "(Progressive Care Unit)": "PCU - Progressive Care Unit",
        "(Ortho)": "Orthopedics",
        "(Emergency Room)": "ED - Emergency Department",
        "(Oncology)": "Hematology & Oncology",
        "(OB\\/GYN)": "Obstetrics / Gynecology",
        "(Labor, Delivery, Recovery, Postpartum)": "Labor and Delivery",
        "(Medical Surgical Neurology)": "Neurology",
        "(Medical Surgical Pediatrics)": "Pediatrics",
        "(Registered Nurse First)": "First Assist",
        "(Medical Surgical Float)": "Float",
        "(Ear Nose Throat)": "Otolaryngology",
        "(Infusions)": "Infusion",
        "(Observation)": "DOU - Direct Observation Unit",
        "(Pediatric Cardiovascular Operating Room)": "Pediatric CVOR",
        "(Pre-Op)": "Preoperative",
        "(Cardiac Progessive Care)": "Cardiac Progressive Care Unit",
        "(Medical Surgical Cardiac)": "Med Surg / Telemetry",
        "(Cardiac Telemetry)": "Telemetry",
        "(Medical Surgical Acute)": "Med Surg"
      };


      //Re-writing the values according to the above Map
      for (let specialty of specialties) {
        for (let [pattern, mappedSpecialty] of Object.entries(specialtyMap)) {
          if (job.Specialty[0].match(new RegExp(pattern, 'gi'))) {
            xmlData[index].Specialty = mappedSpecialty;
            break;
          }
        }

        // Normal processing
        if(specialty != undefined && job.Specialty[0] != undefined)
            specialty.full_name.toLowerCase() == job.Specialty[0].toLowerCase() ? found.push(job) : null;
      }
    }

    // Missed mapping for specialties
    var missed = xmlData.filter(({ jobid: id1 }) => !found.some(({ jobid: id2 }) => id1[0] == id2[0]));

    // unique missing by specialty
    var uniqueMissingSpecialties = missed.filter((obj, index, self) => index === self.findIndex((t) => t.Specialty[0] === obj.Specialty[0]));

    //Printing the uniquie missing specialities in the console
    for (let i of uniqueMissingSpecialties) {
      if (i.Specialty[0].length > 1) {
        console.log(i.Specialty[0])
      }
    }

  })();

//Function to process well-formatted shifts (e.g. 4x8 days or 5x9 nights)
function formatGoodShifts(shift) {

  let shiftAmount = shift.shift[0].toLowerCase().split('x')[0];
  let hrsShift = shift.shift[0].toLowerCase().split('x')[1].split(' ')[0];
  let shiftTimeOfDay = handleshiftTimeOfDay(shift.shift[0].toLowerCase().split('x')[1].split(' ')[1]);
  if (shiftTimeOfDay == "err") {
    //console.log("Good Shift ERR",shift.shift[0])
  }
  return (
    {
      shiftAmount: shiftAmount,
      hrsShift: hrsShift,
      shiftTimeOfDay: shiftTimeOfDay
    }
  )
}

//Function to process badly formatted shifts
function formatBadShifts(shift) {

  //Process the shift time of day normally first
  var shiftTimeOfDay = handleshiftTimeOfDay(shift.shift[0].toLowerCase());

  // Err means it doesn't fit in the usual patterns
  if (shiftTimeOfDay == "err") {

    let unhandled = shift.shift[0].split(' ');

    // Means it is shift time of day
    if (unhandled.length == 1) {

      shiftTimeOfDay = handleshiftTimeOfDay(unhandled[0].toLowerCase());

    } else {

      // Means it is hours per shift only
      if (unhandled[1] == "Hrs") {

        return (
          {
            hrsShift: unhandled[0],
          }
        )
      } else {

        // A mix falls here
        // containing Hours with Hrs and shift time of day
        if (unhandled[0].match(/([0-9][0-9]?.?[0-9]?Hrs)/g)) {

          let STOD = handleshiftTimeOfDay(unhandled[1].toLowerCase());
          let hrsShift = unhandled[0].split('Hrs')[0];

          //console.log(STOD, hrsShift)
          return (
            {
              shiftTimeOfDay: STOD,
              hrsShift: hrsShift
            }
          );

          // containing hours and shifts with s format and shift time of day
        } else {

          let STOD = handleshiftTimeOfDay(unhandled[1].toLowerCase());
          let hrsShift = unhandled[0].split('-')[0];
          let shiftAmount = unhandled[0].split('-')[1].split('s')[0];

          return (
            {
              shiftAmount: shiftAmount,
              hrsShift: hrsShift,
              shiftTimeOfDay: shiftTimeOfDay
            }
          )

        }
      }
    }
  }

  return ({
    shiftTimeOfDay: shiftTimeOfDay
  })
}

// Function to convert shifts into desired (and equivalent) format
function handleshiftTimeOfDay(shiftTimeOfDay) {
  const shiftMap = {
    days: "Days",
    nights: "Nights",
    rotating: "Nights & Days",
    evenings: "Nights",
    mixed: "Nights & Days",
    fle: "Nights or Days",
    flex: "Nights or Days",
    any: "Nights & Days",
    unknown: null,
  };

  // Return the mapped value or default to "err"
  // This indicates an error in the shift formatting (maybe doesn't fall under the defined rules)
  return shiftMap[shiftTimeOfDay] || "err";
}