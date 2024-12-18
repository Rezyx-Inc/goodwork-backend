var axios = require("axios");

axios.post('http://localhost:8000/api/organization-add-job', {
    api_key: 'OWUzOGRjODA5Yjg3YmZhNzk0ZjFjNDFiYzczNTY1MDYzODViOWU1ZTFjNjQ5ZmE1',
    organization_id: 'GWU000006',
    job_type: "Clinical",
    job_name: "xxxxx",
    job_city: "Tampa",
    job_state: "FL",
    weekly_pay: "2669.472",
    profession: "Radiology / Cardiology",
    preferred_specialty: "Cath Lab Tech",

  })
  .then(function (response) {
    console.log(response);
  })
  .catch(function (error) {
    console.log(error);
  });

/*

Required fields

'job_type' => 'required|string',
'job_name' => 'required|string',
'job_city' => 'required|string',
'job_state' => 'required|string',
'weekly_pay' => 'required|numeric',
'profession' => 'required|string',
'preferred_specialty' => 'required|string',

*/
