<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('recruiter')->group(function () {
    // Route::get('/', 'RecruiterController@index');

    Route::middleware(['recruiter_not_logged_in'])->group(function () {
        // Route::get('/', 'RecruiterController@index');
        Route::get('/login', ['uses' => 'RecruiterAuthController@get_login', 'as' => 'recruiter.login']);
        Route::post('recruiter-login', ['uses' => 'RecruiterAuthController@post_login', 'as' => 'recruiter-login']);
        Route::get('/recruiter-verify', ['uses' => 'RecruiterAuthController@verify', 'as' => 'recruiter.verify']);
        Route::post('recruiter-otp', ['uses' => 'RecruiterAuthController@submit_otp', 'as' => 'recruiter.otp']);
        Route::get('/signup', ['uses' => 'RecruiterAuthController@get_signup', 'as' => 'recruiter-signup']);
        Route::post('signup', ['uses' => 'RecruiterAuthController@post_signup', 'as' => 'recruiter.signup']);
        Route::get('resend-otp', ['uses' => 'RecruiterAuthController@resend_otp', 'as' => 'recruiter.resend-otp']);

    });
    Route::middleware(['recruiter_logged_in'])->group(function () {
        /** Dashboard routes */
        Route::get('recruiter-dashboard', ['uses' => 'RecruiterDashboardController@index', 'as' => 'recruiter-dashboard']);
        //Route::post('recruiter-messages', ['uses' => 'RecruiterDashboardController@communication', 'as' => 'recruiter-messages']);
        Route::get('recruiter-profile/{type}', ['uses' => 'RecruiterDashboardController@profile', 'as' => 'recruiter-profile']);
        Route::post('help-and-support', ['uses' => 'RecruiterDashboardController@helpAndSupport', 'as' => 'help-and-support']);
        Route::post('recruiter-update-profile', ['uses' => 'RecruiterDashboardController@updateProfile', 'as' => 'recruiter-update-profile']);
        Route::post('recruiter-remove-qualities', ['uses' => 'RecruiterDashboardController@recruiterRemoveQualities', 'as' => 'recruiter-remove-qualities']);


        Route::get('recruiter-application',  ['uses' => 'ApplicationController@application', 'as' => 'recruiter-application']);
        /**
        * Route: GET /recruiter-application
        *
        * Function: ApplicationController@application
        *
        * @response: Returns a view 'recruiter::recruiter/applicationjourney' with a compacted array 'statusCounts' which contains the count of applications for each status.
        *
        * Functionality: This function prepares the data for a view that represents the journey of an application in a recruitment process.
        * It first defines a list of statuses that an application can have. It then initializes an array which will hold the count of applications for each status.
        * A database query is performed to select all offers where the status is in the status list, groups them by status, and counts the number of offers for each status.
        * The results of the query are then used to update the count of offers for the corresponding status in the statusCounts array.
        * Finally, the function returns a view named 'recruiter::recruiter/applicationjourney', passing the statusCounts array to the view.
        */

        Route::post('get-application-listing', ['uses' => 'ApplicationController@getApplicationListing', 'as' => 'get-application-listing']);

        // getApplicationListing in ApplicationController.php need to be (optimized / rebuild) it return large views and data

        /**
         * Route: POST /get-application-listing
         *
         * Function: ApplicationController@getApplicationListing
         *
         * @bodyparam1: type (required) - The status of the applications to be fetched.
         * @bodyparam2: id (optional) - The ID of a specific application to be fetched.
         * @bodyparam3: formtype (optional) - The type of form to be displayed.
         * @bodyparam4: jobid (optional) - The ID of a specific job to be fetched.
         *
         * @response: Returns a JSON object containing application listing, application details, all specialties, all certificates, and all vaccinations.
         * - Success: {"applicationlisting": "...", "applicationdetails": "...", "allspecialty": {...}, "allcertificate": {...}, "allvaccinations": {...}}
         * - Failure: {"applicationlisting": "<div class="text-center"><span>No Application</span></div>", "applicationdetails": "<div class="text-center"><span>Data Not found</span></div>"}
         *
         * Functionality: This function fetches a list of applications based on their status. If no applications are found, it returns a response with a message indicating that no applications were found.
         * If applications are found, it prepares a string of HTML to be displayed for each application.
         * It also fetches the details of a specific application if an ID is provided, or the details of the first application if no ID is provided.
         * It fetches the details of a specific job if a job ID is provided, or the details of the job associated with the application if no job ID is provided.
         * It fetches the details of the nurse associated with the application, and the details of all jobs applied to by the nurse.
         * It prepares a string of HTML to be displayed for the application details, and returns a response containing the application listing, application details, all specialties, all certificates, and all vaccinations.
         */


        // Route::get('recruiter-single-job/{id}', ['uses' => 'RecruiterDashboardController@getSinglejob', 'as' => 'recruiter-single-job']);
        Route::post('update-application-status', ['uses' => 'ApplicationController@updateApplicationStatus', 'as' => 'update-application-status']);
        /**
        * Route: POST /update-application-status
        *
        * Function: ApplicationController@updateApplicationStatus
        *
        * @bodyparam1: type (optional) - Type of the application.
        * @bodyparam2: id (required) - ID of the application.
        * @bodyparam3: formtype (required) - New status to be updated.
        * @bodyparam4: jobid (required) - ID of the job associated with the application.
        *
        * @response: Returns {"message": "..."}
        * - Success: {"message": "Update Successfully"}
        * - Failure: {"message": "Something went wrong! Please check"}
        *
        * Functionality: Validates input data and updates the status of a job offer in the Offer model.
        * If jobid is set in the request, it updates the status of the job offer where the job_id matches jobid and id matches id.
        * Returns success message on successful update, otherwise returns appropriate error messages.
        */
        // to send an offer to a worker
        Route::post('recruiter-send-job-offer', ['uses' => 'ApplicationController@sendJobOffer', 'as' => 'recruiter-send-job-offer']);

        // to save an offer as a draft

        //Route::post('recruiter-send-job-offer', ['uses' => 'ApplicationController@saveAsDraft', 'as' => 'recruiter-save-job-offer']);
        /**
        * Route: POST /recruiter-send-job-offer
        *
        * Function: ApplicationController@sendJobOffer
        *
        * @request: Expects 'worker_user_id' and 'job_id' in the request. Optionally, it can also receive other job details.
        *
        * @response: Returns a JSON response with 'status' and 'message'. The 'status' can be 'error' if validation fails, or 'success' if a job offer is created or updated successfully. The 'message' provides additional information about the operation.
        *
        *Functionality: This function is used to send a job offer to a worker. It first validates the request data. If the validation fails, it returns a JSON response with an error status and message.
        *If the validation passes, it fetches the offer, user, and job data from the database using the provided IDs.
        *It then prepares an array of data to update or create a job offer. If certain fields are not provided in the request, it uses the existing data from the fetched job.
        *It checks if a job offer already exists for the given job, worker, and recruiter. If it does, it updates the existing offer with the new data. If it doesn't, it creates a new job offer.
        *If the job offer is successfully created or updated, it sends a notification to the worker about the job offer.
        *The function also logs the operation, recording the recruiter who sent the offer, the worker who received it, and the job details.
        *Finally, it returns a JSON response indicating the result of the operation. The response includes the status of the operation ('success' or 'error'), a message providing additional information, and if successful, the details of the job offer.

        */

        Route::get('recruiter-opportunities-manager', ['uses' => 'OpportunitiesController@index', 'as' => 'recruiter-opportunities-manager']);
        Route::get('recruiter-create-opportunity', ['uses' => 'OpportunitiesController@create', 'as' => 'recruiter-create-opportunity']);
        Route::post('recruiter-create-opportunity/{check_type}', ['uses' => 'OpportunitiesController@hide_job', 'as' => 'recruiter-create-opportunity-store']);
        Route::post('get-job-listing', ['uses' => 'OpportunitiesController@getJobListing', 'as' => 'recruiter-get-job-listing']);

        Route::get('recruiter-logout', ['uses' => 'RecruiterAuthController@logout', 'as' => 'recruiter-logout']);

        Route::post('remove/{id}', ['uses' => 'OpportunitiesController@recruiterRemoveInfo', 'as' => 'recruiter-remove-info']);

        Route::post('ask-recruiter-notification', ['uses' => 'RecruiterDashboardController@askRecruiterNotification', 'as' => 'ask-recruiter-notification']);

        Route::get('get-single-nurse-details/{id}', ['uses' => 'RecruiterDashboardController@getSingleNurseDetails', 'as' => 'get-single-nurse-details']);

        Route::post('send-job-offer-recruiter', ['uses' => 'ApplicationController@sendJobOfferRecruiter', 'as' => 'send-job-offer-recruiter']);
        /**
        * Route: POST /send-job-offer-recruiter
        *
        * Function: ApplicationController@sendJobOfferRecruiter
        *
        * @request: Expects 'id' and 'jobid' in the request. Optionally, it can also receive 'type' with value "rejectcounter".
        *
        * @response: Returns a JSON response with 'status' and 'message'. The 'status' can be 'error' if validation fails, or 'success' if a job offer is sent successfully or already sent. The 'message' provides additional information about the operation.
        *
        * Functionality: This function is used to send a job offer from a recruiter. It first validates the request data. If the validation fails, it returns a JSON response with an error status and message.
        * If the validation passes, it checks if the request type is "rejectcounter". If it is, it sets the 'is_counter' field in the update array to '0'.
        * It then sets the 'is_draft' field in the update array to '0', indicating that the job offer is no longer a draft.
        * It fetches the job offer from the database using the provided ID. If the job offer is already sent (i.e., 'is_draft' is '0'), it returns a success response indicating that the job offer is already sent.
        * If the job offer is still a draft, it updates the job offer with the data in the update array. If the update is successful, it returns a success response. If the update fails, it returns an error response.
        */

        Route::get('/getMessages', ['uses'=>'RecruiterController@get_private_messages', 'as'=>'getPrivateMessages']);
        Route::get('recruiter-messages', ['uses' => 'RecruiterController@get_messages', 'as' => 'recruiter-messages']);

        Route::post('/send-message', ['uses' => 'RecruiterController@sendMessages', 'as' => 'SendMessage']);

        Route::get('add-job', ['uses' => 'RecruiterController@addJob', 'as' => 'add-job']);
        Route::post('add-job', ['uses' => 'RecruiterController@addJobStore', 'as' => 'addJob.store']);


         // disactivate account
         Route::post('disactivate-account',['uses' => 'RecruiterDashboardController@disactivate_account', 'as' => 'disactivate_account']);

        // new post route for profile updating
        Route::post('update-recruiter-profile', ['uses' => 'RecruiterDashboardController@update_recruiter_profile', 'as' => 'update-recruiter-profile']);

        // new post route for account setting updating
        Route::post('update-recruiter-account-setting',['uses' => 'RecruiterDashboardController@update_recruiter_account_setting', 'as' => 'update-recruiter-account-setting']);

        // sending support tickets
        Route::post('send-support-ticket',['uses' => 'RecruiterDashboardController@send_support_ticket', 'as' => 'send_support_ticket']);

        // Send amount

        Route::post('send-amount-transfer',['uses' => 'RecruiterDashboardController@send_amount', 'as' => 'send_amount']);

        //edit job
        Route::post('get-job-to-edit', ['uses' => 'RecruiterController@get_job_to_edit', 'as' => 'get_job_to_edit']);

        Route::post('edit-job', ['uses' => 'RecruiterController@edit_job', 'as' => 'edit_job']);

        // reading message notification
        Route::post('read-recruiter-message-notification', ['uses' => 'RecruiterController@read_recruiter_message_notification', 'as' => 'read-recruiter-message-notification']);

        // reading job notification
        Route::post('read-recruiter-job-notification', ['uses' => 'RecruiterController@read_recruiter_job_notification', 'as' => 'read-recruiter-job-notification']);

        // reading offer notification
        Route::post('read-recruiter-offer-notification', ['uses' => 'RecruiterController@read_recruiter_offer_notification', 'as' => 'read-recruiter-offer-notification']);

        // update-recruiter-profile-image

        Route::post('update-recruiter-profile-image', ['uses' => 'RecruiterDashboardController@update_recruiter_profile_image', 'as' => 'update-recruiter-profile-image']);

    });
});
