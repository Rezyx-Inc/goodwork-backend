<?php

namespace App\Http\Controllers\Api\StaticContent;


//Models 
//FACILITY
use App\Models\States;
use App\Models\Cities;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use DB;

class StaticContentController extends Controller
{
    public function getCountries()
    {

        $controller = new controller();
        $countries = $controller->getCountries()->pluck('name', 'id');
        $data = [];
        foreach ($countries as $key => $value) {
            $data[] = ['country_id' => strval($key), "name" => $value];
        }
        // moved usa and canada to top of the row
        $this->moveElement($data, 235, 0);
        $this->moveElement($data, 40, 1);
        // moved usa and canada to top of the row
        $this->check = "1";
        $this->message = "Countries listed successfully";
        $this->return_data = $data;

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function getStates(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'country_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $get_states = States::where(['country_id' => $request->country_id])->get();
            if ($get_states->count() > 0) {
                $states = $get_states;
                $data = [];
                foreach ($states as $key => $value) {
                    $data[] = ['state_id' => strval($value->id), "name" => $value->name, 'iso_name' => $value->iso2];
                }
                $this->check = "1";
                $this->message = "States listed successfully";
                $this->return_data = $data;
            } else {
                $this->return_data = [];
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function getCities(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'state_id' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $get_cities = Cities::where(['state_id' => $request->state_id])->get();
            if ($get_cities->count() > 0) {
                $cities = $get_cities;
                $data = [];
                foreach ($cities as $key => $value) {
                    $data[] = ['city_id' => strval($value->id), "name" => $value->name];
                }
                $this->check = "1";
                $this->message = "Cities listed successfully";
                $this->return_data = $data;
            } else {
                $this->return_data = [];
            }
        }

        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }
    public function termsAndConditions()
    {
        $this->message = "Terms and Conditions";
        $this->check = "1";
        $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
        return response()->json(["api_status" => $this->check, "message" => $this->message, "data" => $this->return_data], 200);
    }

    public function privacyPolicy(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $aboutapp =  \DB::table('setting')->where('slug', '=', 'privacy')->get();
            $this->return_data = $aboutapp[0]->content;
            $this->message = "Privacy Policy";
            $this->check = "1";
            $this->url = url('/privacy-policy');
            
            // $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
            return response()->json(["api_status" => $this->check, "About-web" => $this->url, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }

    public function aboutAPP(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
        } else {
            $aboutapp =  \DB::table('setting')->where('slug', '=', 'about_app')->get();
            $this->return_data = $aboutapp[0]->content;
            $this->message = "About App";
            $this->check = "1";
            $this->url = url('/aboutus');
            // $this->return_data = '<p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site.</p><p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p> <p>Provides an online resource for health care professionals. These terms may be changed from time to time and without further notice. Your continued use of the Site after any such changes constitutes your acceptance of the new terms. If you do not agree to abide by these or any future terms, please do not use the Site or download materials from it. GE Healthcare, a division of General Electric Company ("GE"), may terminate, change, suspend or discontinue any aspect of the Site, including the availability of any features, at any time. GE may remove, modify or otherwise change any content, including that of third parties, on or from this Site. </p>';
            return response()->json(["api_status" => $this->check, "about_web" => $this->url, "message" => $this->message, "data" => $this->return_data], 200);
        }
    }
}
