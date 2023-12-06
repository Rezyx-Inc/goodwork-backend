<?php

namespace App\Http\Controllers\Api\Details;

//MODELS :

use App\Models\Keyword;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
class DetailsController extends Controller
{
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

    public function getSpecialitiesByProfession(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
            'profession_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $id = $request->profession_id;
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
    public function jobTypes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
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
    public function subjectTypes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
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

    public function getSettingType(Request $request)
    {
        $validator = \Validator::make($request->all(), [
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

    public function getContractTerminologyPolicy(Request $request)
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
