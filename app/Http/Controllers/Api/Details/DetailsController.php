<?php

/**
 * This file contains the implementation of the DetailsController class, which is responsible for handling API requests related to details.
 */

namespace App\Http\Controllers\Api\Details;

//MODELS :

use App\Models\Keyword;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

/**
 * The DetailsController class handles the retrieval of details such as specialities, job types, and more.
 */
class DetailsController extends Controller
{

    protected $request;

     /**
     * Constructor method for the DetailsController class.
     * @param Request $request The HTTP request object.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

     /**
     * Retrieves the list of specialities.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the specialities.
     */
    public function getSpecialities()
    {
        $controller = new Controller();
        $specialties = $controller->getSpecialities()->pluck('title', 'id');
        $spl = [];
        if (!empty($specialties)) {
            foreach ($specialties as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Specialities has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

     /**
     * Retrieve specialities based on the given profession ID.
     * return data in JSON format.
     */
    public function getSpecialitiesByProfession()
    {
        $validator = \Validator::make($this->request->all(), [
            'api_key' => 'required',
            'profession_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $id = $this->request->profession_id;
            $keywords = Keyword::where(['active' => true, 'filter' => $id])->get()->pluck('title', 'id');

            $data = [];
            foreach ($keywords as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
            $this->check = "1";
            $this->message = "Specialities has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Retrieves the list of job types.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the job types.
     */
    public function jobTypes()
    {
        $validator = \Validator::make($this->request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $experience = $this->getjobTypes()->pluck('title', 'id');
            $data = [];
            foreach ($experience as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
            $this->check = "1";
            $this->message = "Job types has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
    * Retrieves the list of subject types.
    * @return \Illuminate\Http\JsonResponse The JSON response containing the subject types.
    */
    public function subjectTypes()
    {
        $validator = \Validator::make($this->request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $experience = $this->getsubjectTypes()->pluck('title', 'id');
            $data = [];
            foreach ($experience as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
            $this->check = "1";
            $this->message = "Subject types has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);


    }
   
   /**
   * Retrieves the list of geographic preferences.
   * @return \Illuminate\Http\JsonResponse The JSON response containing the geographic preferences.
   */
    public function getGeographicPreferences()
    {
        $controller = new Controller();
        $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
        $work_location = [];
        if (!empty($workLocations)) {
            foreach ($workLocations as $key => $val) {
                $work_location[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Work location's has been listed successfully";
        $this->return_data = $work_location;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    
    /**
    * Retrieves the list of setting types.
    * @return \Illuminate\Http\JsonResponse The JSON response containing the setting types.
    */
    public function getSettingType()
    {
        $validator = \Validator::make($this->request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $keywords = Keyword::where('filter', 'SettingType')->get()->pluck('title', 'id');
            $data = [];
            foreach ($keywords as $key => $value) {
                $data[] = ['id' => $key, "name" => $value];
            }
            $this->check = "1";
            $this->message = "Setting Types has been listed successfully";
            $this->return_data = $data;
        }
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    
    /**
    * Retrieves the list of VMS types.
    * @return \Illuminate\Http\JsonResponse The JSON response containing the VMS types.
    */
    public function getVMSType()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'VMS')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "VMS List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
    * Retrieves the list of clinical types.
    * @return \Illuminate\Http\JsonResponse The JSON response containing the clinical types.
    */
    public function getType()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Type')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "clinical List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
    * Retrieves the list of MSP types.
    * @return \Illuminate\Http\JsonResponse The JSON response containing the MSP types.
    */
    public function getMSPType()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'MSP')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "MSP List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
    * Retrieves the list of contract termination policies.
    * @return \Illuminate\Http\JsonResponse The JSON response containing the contract termination policies.
    */
    public function getContractTerminologyPolicy()
    {
        $keywords = Keyword::where('filter', 'ContractTerminationPolicy')->get()->pluck('title', 'id');
        $data = [];
        foreach ($keywords as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Contract Terminology Policy has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
    * Retrieves the list of state licenses.
    * @return \Illuminate\Http\JsonResponse The JSON response containing the state licenses.
    */
    public function getStateLicense()
    {
        $controller = new Controller();
        $specialties = $controller->getSpecialities()->pluck('title', 'id');
        $spl = [];
        if (!empty($specialties)) {
            foreach ($specialties as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Specialities has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

   /**
   * Retrieves the list of charting systems.
   * @return \Illuminate\Http\JsonResponse The JSON response containing the charting systems.  
   */
    public function getChartingSystem()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Charting')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Charting System has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Retrieves the list of professions.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the professions.
     */
    public function getProfessionList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Profession')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Profession List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    
    /**
     * Retrieves the list of skills.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the skills.
     */
    public function getSkillList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Skills')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Profession List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Retrieves the list of vaccinations.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the vaccinations.
     */
    public function getVaccinationList()
    {
        $keywords = Keyword::where('filter', 'Vaccinations')->get()->pluck('title', 'id');
        $data = [];
        foreach ($keywords as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "Vaccinations has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

   
    /**
     * Retrieves the list of EMRs.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the EMRs.
     */
    public function getEMRList()
    {
        $keywords = Keyword::where('filter', 'EMR')->get()->pluck('title', 'id');
        $data = [];
        foreach ($keywords as $key => $value) {
            $data[] = ['id' => $key, "name" => $value];
        }
        $this->check = "1";
        $this->message = "EMR has been listed successfully";
        $this->return_data = $data;
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Retrieves the list of terms.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the termss.
     */
    public function getTermsList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Terms')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Terms List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Retrieves the list of visions.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the visions.
     */
    public function getVisionList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Vision')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Vision List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Retrieves the list of health insurances.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the health insurances.
     */
    public function getHealthInsuranceList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'HealthInsurance')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Health Insurance List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    
    /**
     * Retrieves the list of dentals.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the dentals.
     */
    public function getDentalList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', 'Dental')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "Dental List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    /**
     * Retrieves the list of HowMuchK.
     * @return \Illuminate\Http\JsonResponse the JSON response containing the HowMuchK.
     */
    public function getHowMuchKList()
    {
        $controller = new Controller();
        $charting = Keyword::where('filter', '401k')->get()->pluck('title', 'id');
        $spl = [];
        if (!empty($charting)) {
            foreach ($charting as $key => $val) {
                $spl[] = ['id' => $key, 'name' => $val];
            }
        }
        $this->check = "1";
        $this->message = "401k List has been listed successfully";
        $this->return_data = $spl;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

}
