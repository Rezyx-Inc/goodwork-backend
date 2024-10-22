<?php

namespace Modules\Recruiter\Http\Controllers;

use DateTime;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\{Request, jsonResponse};
use Illuminate\Contracts\Support\Renderable;
use URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Events\NotificationOffer;
// ************ models ************
/** Models */
use App\Models\{Job, Offer, Nurse, User, OffersLogs, States, Cities, Keyword};

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('recruiter::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('recruiter::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('recruiter::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('recruiter::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function application()
    {
        // auto move to working 
        $recruiter = Auth::guard('recruiter')->user();
        $id = $recruiter->id;
        $offers = Offer::where('status', 'Onboarding')->where('recruiter_id', $id)->get();

        foreach ($offers as $offer) {

            if($offer->as_soon_as == '1'){
                $offer->status = 'Working';
                $offer->save();
                continue;
            }

            $job = Job::where('id', $offer->job_id)->first();
            $start_date = new DateTime($offer->start_date);
            $today = new DateTime();

            if ($start_date <= $today) {
                $offer->status = 'Working';
                $offer->save();
            }
            
        } 

        
        $statusList = ['Apply', 'Screening', 'Submitted', 'Offered', 'Done', 'Onboarding', 'Working', 'Rejected', 'Blocked', 'Hold'];
        $statusCounts = [];
        $offerLists = [];

        foreach ($statusList as $status) {
            $statusCounts[$status] = 0;
        }
        $statusCountsQuery = Offer::where('recruiter_id', $recruiter->id)->whereIn('status', $statusList)->select(\DB::raw('status, count(*) as count'))->groupBy('status')->get();
        foreach ($statusCountsQuery as $statusCount) {
            if ($statusCount) {
                $statusCounts[$statusCount->status] = $statusCount->count;
            } else {
                $statusCounts[$statusCount->status] = 0;
            }
        }
        $status_count_draft = Offer::where('is_draft', true)->count();
        return view('recruiter::recruiter/applicationjourney', compact('statusCounts', 'status_count_draft'));
    }

    public function check_recruiter_payment(Request $request)
    {

        $recruiter_id = Auth::guard('recruiter')->user()->id;

        if (!$this->checkPaymentMethod($recruiter_id)) {
            return response()->json(['status' => false, 'message' => 'Please add a payment method']);
        }

        return response()->json(['status' => true, 'message' => 'Please add a payment method']);

    }

    public function checkPaymentMethod($recruiter_id)
    {
        $url = 'http://localhost:' . config('app.file_api_port') . '/payments/customer/customer-payment-method';
        $stripe_id = User::where('id', $recruiter_id)->first()->stripeAccountId;
        $data = ['customerId' => $stripe_id];
        $response = Http::get($url, $data);

        if ($stripe_id == null || $response->json()['status'] == false) {
            return false;
        }
        return true;
    }





    public function sendJobOffer(Request $request)
    {
        return $request->all();

        $validator = Validator::make($request->all(), [
            'worker_user_id' => 'required',
            'job_id' => 'required',
        ]);
        $responseData = [];
        $message = 'Try Again !';
        if ($validator->fails()) {
            $responseData = [
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ];
        } else {
            try {
                $offerLists = Offer::where('id', $request->id)->first();
                $nurse = Nurse::where('user_id', $request->worker_user_id)->first();
                $user = user::where('id', $request->worker_user_id)->first();
                $job_data = Job::where('id', $request->job_id)->first();
                $update_array['job_name'] = isset($request->job_name) ? $request->job_name : $job_data->job_name;
                $update_array['type'] = isset($request->type) ? $request->type : $job_data->job_type;
                $update_array['terms'] = isset($request->terms) ? $request->terms : $job_data->terms;
                $update_array['profession'] = isset($request->profession) ? $request->profession : $job_data->profession;

                $update_array['block_scheduling'] = isset($request->block_scheduling) ? $request->block_scheduling : $job_data->block_scheduling;
                $update_array['float_requirement'] = isset($request->float_requirement) ? $request->float_requirement : $job_data->float_requirement;
                $update_array['facility_shift_cancelation_policy'] = isset($request->facility_shift_cancelation_policy) ? $request->facility_shift_cancelation_policy : $job_data->facility_shift_cancelation_policy;
                $update_array['contract_termination_policy'] = isset($request->contract_termination_policy) ? $request->contract_termination_policy : $job_data->contract_termination_policy;
                $update_array['traveler_distance_from_facility'] = isset($request->traveler_distance_from_facility) ? $request->traveler_distance_from_facility : $job_data->traveler_distance_from_facility;
                $update_array['job_id'] = isset($request->job_id) ? $request->job_id : $job_data->job_id;
                $update_array['recruiter_id'] = isset($request->recruiter_id) ? $request->recruiter_id : $job_data->recruiter_id;
                $update_array['worker_user_id'] = isset($nurse->id) ? $nurse->id : '';
                $update_array['clinical_setting'] = isset($request->clinical_setting) ? $request->clinical_setting : $job_data->clinical_setting;
                $update_array['Patient_ratio'] = isset($request->Patient_ratio) ? $request->Patient_ratio : $job_data->Patient_ratio;
                $update_array['Emr'] = isset($request->emr) ? $request->emr : $job_data->emr;
                $update_array['Unit'] = isset($request->Unit) ? $request->Unit : $job_data->Unit;
                $update_array['scrub_color'] = isset($request->scrub_color) ? $request->scrub_color : $job_data->scrub_color;
                $update_array['start_date'] = isset($request->start_date) ? $request->start_date : $job_data->start_date;
                $update_array['as_soon_as'] = isset($request->as_soon_as) ? $request->as_soon_as : $job_data->as_soon_as;
                $update_array['rto'] = isset($request->rto) ? $request->rto : $job_data->rto;
                $update_array['hours_per_week'] = isset($request->hours_per_week) ? $request->hours_per_week : $job_data->hours_per_week;
                $update_array['guaranteed_hours'] = isset($request->guaranteed_hours) ? $request->guaranteed_hours : $job_data->guaranteed_hours;
                $update_array['hours_shift'] = isset($request->hours_shift) ? $request->hours_shift : $job_data->hours_shift;
                $update_array['weeks_shift'] = isset($request->weeks_shift) ? $request->weeks_shift : $job_data->weeks_shift;
                $update_array['preferred_assignment_duration'] = isset($request->preferred_assignment_duration) ? $request->preferred_assignment_duration : $job_data->preferred_assignment_duration;
                $update_array['referral_bonus'] = isset($request->referral_bonus) ? $request->referral_bonus : $job_data->referral_bonus;
                $update_array['sign_on_bonus'] = isset($request->sign_on_bonus) ? $request->sign_on_bonus : $job_data->sign_on_bonus;
                $update_array['completion_bonus'] = isset($request->completion_bonus) ? $request->completion_bonus : $job_data->completion_bonus;
                $update_array['extension_bonus'] = isset($request->extension_bonus) ? $request->extension_bonus : $job_data->extension_bonus;
                $update_array['other_bonus'] = isset($request->other_bonus) ? $request->other_bonus : $job_data->other_bonus;
                $update_array['four_zero_one_k'] = isset($request->four_zero_one_k) ? $request->four_zero_one_k : $job_data->four_zero_one_k;
                $update_array['health_insaurance'] = isset($request->health_insaurance) ? $request->health_insaurance : $job_data->health_insaurance;
                $update_array['dental'] = isset($request->dental) ? $request->dental : $job_data->dental;
                $update_array['vision'] = isset($request->vision) ? $request->vision : $job_data->vision;
                $update_array['actual_hourly_rate'] = isset($request->actual_hourly_rate) ? $request->actual_hourly_rate : $job_data->actual_hourly_rate;
                $update_array['overtime'] = isset($request->overtime) ? $request->overtime : $job_data->overtime;
                $update_array['holiday'] = isset($request->holiday) ? $request->holiday : $job_data->holiday;
                $update_array['on_call'] = isset($request->on_call) ? $request->on_call : $job_data->on_call;
                $update_array['orientation_rate'] = isset($request->orientation_rate) ? $request->orientation_rate : $job_data->orientation_rate;
                $update_array['weekly_non_taxable_amount'] = isset($request->weekly_non_taxable_amount) ? $request->weekly_non_taxable_amount : $job_data->weekly_non_taxable_amount;
                $update_array['description'] = isset($request->description) ? $request->description : $job_data->description;
                $update_array['weekly_taxable_amount'] = isset($request->hours_shift) && isset($request->actual_hourly_rate) ? $request->hours_shift * $request->actual_hourly_rate : null;
                $update_array['organization_weekly_amount'] = isset($request->weekly_non_taxable_amount) && isset($update_array['weekly_taxable_amount']) ? $request->weekly_non_taxable_amount + $update_array['weekly_taxable_amount'] : null;
                $update_array['goodwork_weekly_amount'] = isset($update_array['weekly_taxable_amount']) ? $update_array['weekly_taxable_amount'] * 0.05 : 0;
                $update_array['total_organization_amount'] = isset($request->preferred_assignment_duration) && isset($update_array['organization_weekly_amount']) && isset($request->sign_on_bonus) && isset($request->completion_bonus) ? $request->preferred_assignment_duration + $update_array['organization_weekly_amount'] + $request->sign_on_bonus + $request->completion_bonus : null;
                $update_array['total_goodwork_amount'] = isset($request->preferred_assignment_duration) && isset($update_array['goodwork_weekly_amount']) ? $request->preferred_assignment_duration + $update_array['goodwork_weekly_amount'] : null;
                $update_array['total_contract_amount'] = isset($update_array['total_goodwork_amount']) && isset($update_array['total_organization_amount']) ? $update_array['total_goodwork_amount'] + isset($update_array['total_organization_amount']) : null;
                $update_array['weekly_pay'] = isset($job_data->weekly_pay) ? $job_data->weekly_pay : null;
                $update_array['tax_status'] = isset($request->tax_status) ? $request->tax_status : null;
                if ($request->funcionalityType == 'createdraft') {
                    $update_array['status'] = 'Draft';
                    $update_array['is_draft'] = '1';
                    $update_array['is_counter'] = '0';
                    $message = 'Draft Created Successfully !';
                } else {
                    $update_array['status'] = 'Offered';
                    $update_array['is_draft'] = '0';
                    $update_array['is_counter'] = '1';
                    $message = 'Counter Offer Created Successfully !';
                }
                /* create job */
                $update_array['created_by'] = isset($job_data->recruiter_id) && $job_data->recruiter_id != '' ? $job_data->recruiter_id : '';


                $offerexist = DB::table('offers')
                    ->where(['id' => $request->offer_id])
                    ->first();

                if ($offerexist) {
                    $job = DB::table('offers')
                        ->where(['job_id' => $request->job_id, 'worker_user_id' => $nurse->id, 'recruiter_id' => $request->recruiter_id])
                        ->update($update_array);
                } else {
                    $job = Offer::create($update_array);
                }
                /* create job */
                if ($job) {
                    $responseData = [
                        'status' => 'success',
                        'message' => $message,
                    ];
                } else {
                    $responseData = [
                        'status' => 'error',
                        'message' => $message,
                    ];
                }
            } catch (\Exception $e) {
                $responseData = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }
        return response()->json($responseData);
    }



    // get offer information for form counter
    public function get_offer_information(Request $request)
    {
        try {
            $offer_id = $request->offer_id;
            $offerdetails = Offer::where('id', $offer_id)->first();
            // the one who counter an offer or send the first offer
            $user_id = $offerdetails->created_by;
            $userdetails = User::where('id', $user_id)->first();
            $response['content'] = view('recruiter::offers.counter_offer_form', ['offerdetails' => $offerdetails, 'userdetails' => $userdetails])->render();
            //return new JsonResponse($response, 200);
            return response()->json($response);

        } catch (\Exception $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }
    }

    // get worker infromation of each offer type
    public function get_offers_by_type(Request $request)
    {
        try {
            $type = $request->type;
            $recruiter = Auth::guard('recruiter')->user();
            $offerLists = Offer::where('status', $type)->where('recruiter_id', $recruiter->id)->get();

            $nurses = [];
            $offerData = [];
            foreach ($offerLists as $value) {
                if ($value && !in_array($value->worker_user_id, $nurses)) {
                    $nurses[] = $value->worker_user_id;
                    $nurse = Nurse::where('id', $value->worker_user_id)->first();
                    $user = User::where('id', $nurse->user_id)->first();
                    $offerData[] = [
                        'type' => $type,
                        'workerUserId' => $value->worker_user_id,
                        'image' => $user->image,
                        'firstName' => $user->first_name,
                        'lastName' => $user->last_name,
                        'hourlyPayRate' => $nurse->facility_hourly_pay_rate ?? null,
                        'city' => $nurse->city ?? null,
                        'state' => $nurse->state ?? null,
                        'muSpecialty' => $nurse->mu_specialty ?? null,
                        'specialty' => $nurse->specialty ?? null,
                    ];
                }
            }
            if (empty($offerData)) {
                $noApplications = true;
            } else {
                $noApplications = false;
            }
            $response['content'] = view('recruiter::offers.workers_cards_information', ['noApplications' => $noApplications, 'offerData' => $offerData])->render();
            //return new JsonResponse($response, 200);
            return response()->json($response);
        } catch (\Exception $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }
    }

    function get_offers_of_each_worker(Request $request)
    {
        try {
            $recruiter = Auth::guard('recruiter')->user();
            $recruiter_id = $recruiter->id;
            $worker_id = $request->nurse_id;
            $type = $request->type;
            $nurse = Nurse::where('id', $worker_id)->first();
            $user = User::where('id', $nurse->user_id)->first();
            $offers = Offer::where(['status' => $type, 'worker_user_id' => $request->nurse_id, 'recruiter_id' => $recruiter->id])->get();
            $jobappliedcount = Offer::where(['status' => $type, 'worker_user_id' => $worker_id, 'recruiter_id' => $recruiter->id])->count();
            // file availablity check
            $hasFile = false;
            $urlDocs = 'http://localhost:' . config('app.file_api_port') . '/documents/get-docs';
            $fileresponse = Http::post($urlDocs, ['workerId' => $worker_id]);
            $files = [];
            if (!empty($fileresponse->json()['files'])) {
                $hasFile = true;

                foreach ($fileresponse->json()['files'] as $file) {
                    $files[] = [
                        'name' => $file['name'],
                        'content' => $file['content'],
                        'type' => $file['type']
                    ];
                }
            }
            $response['content'] = view('recruiter::offers.workers_complete_information', ['type' => $type, 'hasFile' => $hasFile, 'userdetails' => $user, 'nursedetails' => $nurse, 'jobappliedcount' => $jobappliedcount, 'offerdetails' => $offers])->render();
            $response['files'] = $files;
            //return response()->json(['response'=>$response]);
            //return new JsonResponse($response, 200);
            return response()->json($response);
        } catch (\Exeption $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }
    }

    function get_one_offer_information(Request $request)
    {

        try {
            $offer_id = $request->offer_id;
            $offer = Offer::where('id', $offer_id)->first();
            $worker_id = $offer->worker_user_id;
            $worker_details = Nurse::where('id', $worker_id)->first();
            $user = User::where('id', $worker_details->user_id)->first();
            $offerLogs = OffersLogs::where('original_offer_id', $offer_id)->get();
            $response['content'] = view('recruiter::offers.offer_vs_worker_information', ['userdetails' => $user, 'offerdetails' => $offer, 'offerLogs' => $offerLogs])->render();
            //return new JsonResponse($response, 200);
            return response()->json($response);
        } catch (\Exception $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }
    }

    public function updateApplicationStatus(Request $request)
    {
        $recruiter = Auth::guard('recruiter')->user();
        $recruiter_id = $recruiter->id;
        $full_name = $recruiter->first_name . ' ' . $recruiter->last_name;
        $offer_id = $request->id;
        $offer = Offer::where('id', $offer_id)->first();
        $status = $request->status;
        $jobid = $offer->job_id;

        if (isset($jobid)) {
            $job = Offer::where(['id' => $offer_id])->update(['status' => $status]);
            if ($job) {
                // send notification to the worker
                $time = now()->toDateTimeString();
                $receiver = $offer->worker_user_id;
                $job_name = Job::where('id', $jobid)->first()->job_name;
                event(new NotificationOffer($status, false, $time, $receiver, $recruiter_id, $full_name, $jobid, $job_name, $offer_id));
                $statusList = ['Apply', 'Screening', 'Submitted', 'Offered', 'Done', 'Onboarding', 'Working', 'Rejected', 'Blocked', 'Hold'];
                $statusCounts = [];
                $offerLists = [];
                foreach ($statusList as $status) {
                    $statusCounts[$status] = 0;
                }
                $statusCountsQuery = Offer::whereIn('status', $statusList)->where('recruiter_id', $recruiter_id)->select(\DB::raw('status, count(*) as count'))->groupBy('status')->get();
                foreach ($statusCountsQuery as $statusCount) {
                    if ($statusCount) {
                        $statusCounts[$statusCount->status] = $statusCount->count;
                    } else {
                        $statusCounts[$statusCount->status] = 0;
                    }
                }
                return response()->json(['message' => 'Update Successfully', 'type' => $status, 'statusCounts' => $statusCounts]);
            } else {
                return response()->json(['message' => 'Something went wrong! Please check']);
            }
        } else {
            return response()->json(['message' => 'Something went wrong! Please check']);
        }
    }

    public function recruiter_counter_offer(Request $request)
    {
        try {
            $recruiter = Auth::guard('recruiter')->user();
            $recruiter_id = $recruiter->id;
            $full_name = $recruiter->first_name . ' ' . $recruiter->last_name;
            $offer_id = $request->id;
            $data = $request->data;
            $offer = Offer::where('id', $offer_id)->first();
            // update it
            if ($offer) {
                $offer->update($data);
                $jobid = $offer->job_id;
                $time = now()->toDateTimeString();
                $receiver = $offer->worker_user_id;
                $job_name = Job::where('id', $jobid)->first()->job_name;
                event(new NotificationOffer('Offered', false, $time, $receiver, $recruiter_id, $full_name, $jobid, $job_name, $offer_id));

                return response()->json([
                    'status' => 'success',
                    'message' => 'Offer updated successfully',
                    'offer' => $offer
                ]);

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Offer not found'
                ], 404);
            }

        } catch (\Exception $ex) {
            return response()->json(["message" => $ex->getMessage()]);
        }


    }


    public function AcceptOrRejectJobOffer(Request $request)
    {
        try {
            $user = Auth::guard('recruiter')->user();
            $recruiter_id = $user->id;
            $full_name = $user->first_name . ' ' . $user->last_name;
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'jobid' => 'required',
            ]);
            if ($validator->fails()) {
                $responseData = [
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ];
            } else {
                if ($request->type == 'rejectcounter') {
                    $update_array['is_counter'] = '0';
                    $update_array['is_draft'] = '0';
                    $update_array['status'] = 'Rejected';
                    $job = DB::table('offers')
                        ->where(['id' => $request->id])
                        ->update($update_array);
                    if ($job) {
                        $responseData = [
                            'status' => 'success',
                            'message' => 'Job Rejected successfully',
                        ];
                        $offer = Offer::where('id', $request->id)->first();
                        $id = $request->id;
                        $jobid = $offer->job_id;
                        $time = now()->toDateTimeString();
                        $receiver = $offer->worker_user_id;
                        $job_name = Job::where('id', $jobid)->first()->job_name;


                        event(new NotificationOffer('Rejected', false, $time, $receiver, $recruiter_id, $full_name, $jobid, $job_name, $id));
                    }
                } elseif ($request->type == 'offersend') {
                    $update_array['is_counter'] = '0';
                    $update_array['is_draft'] = '0';
                    $update_array['status'] = 'Onboarding';
                    $job = Offer::find($request->id);
                    if ($job) {
                        $job->update($update_array);
                    }

                    $user = Auth::guard('recruiter')->user();
                    $data = [
                        'offerId' => $request->id,
                        'amount' => '1',
                        'stripeId' => $user->stripeAccountId,
                        'fullName' => $user->first_name . ' ' . $user->last_name,
                    ];

                    ;

                    $responseData = [];
                    if ($job) {
                        $responseData = [
                            'status' => 'success',
                            'message' => $responseInvoice->json()['message'],
                        ];
                        $offer = Offer::where('id', $request->id)->first();
                        $id = $request->id;
                        $jobid = $offer->job_id;
                        $time = now()->toDateTimeString();
                        $receiver = $offer->worker_user_id;
                        $job_name = Job::where('id', $jobid)->first()->job_name;


                        event(new NotificationOffer('Offered', false, $time, $receiver, $recruiter_id, $full_name, $jobid, $job_name, $id));

                    }
                }
                return response()->json($responseData);
            }

        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()]);
        }

    }
}
