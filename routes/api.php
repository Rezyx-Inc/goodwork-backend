<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Facility\FacilityController;
use App\Http\Controllers\Api\Worker\WorkerController;
use App\Http\Controllers\Api\Recruiter\RecruiterController;
use App\Http\Controllers\Api\Employer\ApiEmployerController;
use App\Http\Controllers\Api\AuthApi\AuthApiController;
use App\Http\Controllers\Api\Certification\CertificationController;
use App\Http\Controllers\Api\Details\DetailsController;
use App\Http\Controllers\Api\Job\JobControllerApi;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\StaticContent\StaticContentController;
use App\Http\Controllers\Api\Support\SupportController;
use App\Http\Controllers\Api\UserProfile\UserProfileController;
use App\Http\Controllers\AuthController;
use App\Models\Cities;
use App\Models\Countries;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', 'AuthApiController@getUser');
Route::middleware('auth:api')->get('/user', [AuthApiController::class, 'getUser']);

// no ProfileController controller created
//Route::post('country/{name}/states', 'ProfileController@stateJson')->name('state-json');

// AuthApi all users :
// send otp code for all roles

/**
 * Route: POST /send-otp
 *
 * Function: ApiController@sendOtp
 *
 * @bodyparam1: api_key (required) - API key for authentication.
 * @bodyparam2: id (required) - User's email or mobile number for OTP verification.
 * @bodyparam3: role (required) - Role associated with the user.
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": {...}}
 * - Success: {"api_status": "1", "message": "OTP sent successfully to email/mobile", "data": {"user_id": "...", "otp": "..."}}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Validates input data and sends an OTP to the user's email or mobile number for verification.
 * Generates a random OTP, updates the user's record with the OTP, and sends an email containing the OTP for login.
 * Returns success message with user ID and OTP on successful OTP generation, otherwise returns appropriate error messages.
 */

//Route::post('send-otp', 'AuthApiController@sendOtp');
Route::post('send-otp', [AuthApiController::class, 'sendOtp']);

// send otp code for mobile

/**
 * Route: POST /mobile-otp
 *
 * Function: ApiController@mobileOtp
 *
 * @bodyparam1: api_key (required) - API key for authentication.
 * @bodyparam2: id (required) - User's email or mobile number for OTP verification.
 * @bodyparam3: role (required) - Role associated with the user.
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": {...}}
 * - Success: {"api_status": "1", "message": "OTP sent successfully to email/mobile", "data": {"user_id": "...", "otp": "..."}}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Validates input data and sends an OTP to the user's email or mobile number for verification using Twilio.
 * Generates a random OTP, updates the user's record with the OTP, and sends an SMS containing the OTP for login.
 * Returns success message with user ID and OTP on successful OTP generation, otherwise returns appropriate error messages.
 */

//Route::post('mobile-otp', 'AuthApiController@mobileOtp');
Route::post('mobile-otp', [AuthApiController::class, 'mobileOtp']);

// login functionality for all roles



/**
 * Route: POST /login
 *
 * Function: ApiController@login
 *
 * @bodyparam1: email (required) - User's email for login.
 * @bodyparam2: password (required) - User's password for login.
 * @bodyparam3: fcm_token (required) - User's FCM token for notification.
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": {...}}
 * - Success: {"api_status": "1", "message": "Logged in successfully", "data": {...}}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Authenticates user login credentials.
 * Updates the FCM token for the user and checks for profile completion status based on user role.
 * Returns success message on successful login with user data or appropriate error messages.
 */
// Route::post('login', 'AuthApiController@login');
Route::post('login', [AuthApiController::class, 'login']);
// retreiveDetailsControllers
// retrieve all specialities

/**
 * Route: POST /get-specialities
 *
 * Function: ApiController@getSpecialities
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": [...] }
 * - Success: {"api_status": "1", "message": "Specialities listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of specialities.
 * Utilizes the Controller to fetch specialities and formats the data as an array of objects with 'id' and 'name'.
 * Returns success message with a list of specialities on successful retrieval, otherwise returns appropriate error messages.
 */

//Route::post('get-specialities', 'DetailsController@getSpecialities');
Route::post('get-specialities', [DetailsController::class, 'getSpecialities']);

// retrieve specialities of specefic Profession

/**
 * Route: POST /get-profession-specialities
 *
 * Function: ApiController@getSpecialitiesByProfession
 *
 * @bodyparam1: api_key (required) - API key for authentication.
 * @bodyparam2: profession_id (required) - ID of the profession to retrieve associated specialities.
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": [...] }
 * - Success: {"api_status": "1", "message": "Specialities listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves specialities associated with a specific profession.
 * Fetches keywords filtered by the given profession ID and formats the data as an array of objects with 'id' and 'name'.
 * Returns success message with a list of specialities on successful retrieval, otherwise returns appropriate error messages.
 */
//Route::post('get-profession-specialities', 'DetailsController@getSpecialitiesByProfession');
Route::post('get-profession-specialities', [DetailsController::class, 'getSpecialitiesByProfession']);

// retrieve job types information

/**
 * Route: POST /get-job-types
 *
 * Function: ApiController@jobTypes
 *
 * @bodyparam1: api_key (required) - API key for authentication.
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": [...] }
 * - Success: {"api_status": "1", "message": "Job types listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of job types.
 * Fetches job types and formats the data as an array of objects with 'id' and 'name'.
 * Returns success message with a list of job types on successful retrieval, otherwise returns appropriate error messages.
 */

//Route::post('get-job-types', 'DetailsController@jobTypes');
Route::post('get-job-types', [DetailsController::class, 'jobTypes']);

/**
 * Route: POST /help-support
 *
 * Function: ApiController@helpSupport
 *
 * @bodyparam1: user_id (required) - ID of the user submitting the support request.
 * @bodyparam2: api_key (required) - API key for authentication.
 * @bodyparam3: subject (required) - Subject of the support request.
 * @bodyparam4: issue (required) - Description of the issue or problem.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Comment submitted successfully", "data": "1"}
 * - Failure: {"api_status": "0", "message": "User not found", "data": "0"}
 *
 * Functionality: Handles validation of input data and submits a support request for a user.
 * Creates a new entry in the 'help_support' table with the user's information and support details.
 * Returns success or failure messages based on the operation's result.
 */


//Route::post('help-support', 'SupportController@helpSupport');
Route::post('help-support', [SupportController::class, 'helpSupport']);

/**
 * Route: POST /get-help-comment
 *
 * Function: ApiController@getHelpComment
 *
 * @bodyparam1: user_id (required) - ID of the user to retrieve help comments for.
 * @bodyparam2: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Comment listed successfully", "data": [...]} (Array of comments)
 * - Failure: {"api_status": "0", "message": "User not found", "data": []}
 *
 * Functionality: Retrieves help comments for a specific user.
 * Handles validation of input data and returns comments associated with the user if found,
 * otherwise returns appropriate error messages.
 */



//Route::post('get-help-comment', 'SupportController@getHelpComment');
Route::post('get-help-comment', [SupportController::class, 'getHelpComment']);


/**
 * Route: POST /get-help-replycomment
 *
 * Function: ApiController@getHelpReplyComment
 *
 * @bodyparam1: id (required) - ID of the help comment to reply to.
 * @bodyparam2: api_key (required) - API key for authentication.
 * @bodyparam3: admin_comment (required) - Admin's reply or comment for the help request.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Admin reply submitted successfully", "data": "1"}
 * - Failure: {"api_status": "0", "message": "Comment not found", "data": "0"}
 *
 * Functionality: Handles validation of input data and submission of an admin reply to a help comment.
 */

//Route::post('get-help-replycomment', 'SupportController@getHelpReplyComment');
Route::post('get-help-replycomment', [SupportController::class, 'getHelpReplyComment']);


/**
 * Route: POST /get-comment
 *
 * Function: ApiController@getCommentByAdmin
 *
 * @bodyparam1: user_id (required) - User's ID associated with the comment.
 * @bodyparam2: api_key (required) - API key for authentication.
 * @bodyparam3: id (required) - ID of the comment to retrieve.
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": {...} }
 * - Success: {"api_status": "1", "message": "Admin reply Comment listed successfully", "data": {...}}
 * - Failure: {"api_status": "0", "message": "...", "data": []}
 *
 * Functionality: Retrieves an admin's reply comment by comment ID.
 * Fetches comment details including admin reply, subject, issue, status, and user information.
 * Returns success message with comment details on successful retrieval, otherwise returns appropriate error messages.
 */

//Route::post('get-comment', 'SupportController@getCommentByAdmin');
Route::post('get-comment', [SupportController::class, 'getCommentByAdmin']);


/**
 * Route: POST /get-subject-types
 *
 * Function: ApiController@subjectTypes
 *
 * @bodyparam1: api_key (required) - API key for authentication.
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": [...] }
 * - Success: {"api_status": "1", "message": "Subject types listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of subject types.
 * Fetches subject types and formats the data as an array of objects with 'id' and 'name'.
 * Returns success message with a list of subject types on successful retrieval, otherwise returns appropriate error messages.
 */


//Route::post('get-subject-types', 'DetailsController@subjectTypes');
Route::post('get-subject-types', [DetailsController::class, 'subjectTypes']);

/**
 * Route: POST /get-work-location
 *
 * Function: ApiController@getGeographicPreferences
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": [...] }
 * - Success: {"api_status": "1", "message": "Work locations listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of work locations.
 * Fetches geographic preferences and formats the data as an array of objects with 'id' and 'name'.
 * Returns success message with a list of work locations on successful retrieval, otherwise returns appropriate error messages.
 */


//Route::post('get-work-location', 'DetailsController@getGeographicPreferences');
Route::post('get-work-location', [DetailsController::class, 'getGeographicPreferences']);


/**
 * Route: POST /get-settings
 *
 * Function: ApiController@getSettingType
 *
 * @bodyparam1: api_key (required) - API key for authentication.
 *
 * @response: Returns {"api_status": "1/0", "message": "...", "data": [...] }
 * - Success: {"api_status": "1", "message": "Setting types listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of setting types.
 * Fetches setting types based on the 'SettingType' filter and formats the data as an array of objects with 'id' and 'name'.
 * Returns success message with a list of setting types on successful retrieval, otherwise returns appropriate error messages.
 */


//Route::post('get-settings', 'DetailsController@getSettingType');
Route::post('get-settings', [DetailsController::class, 'getSettingType']);

/**
 * Route: POST /get-vms
 *
 * Function: ApiController@getVMSType
 *
 * @responseExample: Returns {"api_status": "1/0", "message": "...", "data": [...] }
 * - Success: {"api_status": "1", "message": "VMS list retrieved successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of VMS (Virtual Medical Scribe) types.
 * Fetches VMS types based on the 'VMS' filter and formats the data as an array of objects with 'id' and 'name'.
 * Returns success message with a list of VMS types on successful retrieval, otherwise returns appropriate error messages.
 */


//Route::post('get-vms', 'DetailsController@getVMSType');
Route::post('get-vms', [DetailsController::class, 'getVMSType']);


/**
 * Route: POST /get-type
 *
 * Function: ApiController@getType
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Clinical list retrieved successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of clinical types.
 * Queries the database for clinical types categorized under the 'Type' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of clinical types upon successful retrieval, or appropriate error messages in case of failure.
 */


//Route::post('get-type', 'DetailsController@getType');
Route::post('get-type', [DetailsController::class, 'getType']);


/**
 * Route: POST /get-msp
 *
 * Function: ApiController@getMSPType
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "MSP list retrieved successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of MSP types.
 * Queries the database for MSP types categorized under the 'MSP' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of MSP types upon successful retrieval, or appropriate error messages in case of failure.
 */



//Route::post('get-msp', 'DetailsController@getMSPType');
Route::post('get-msp', [DetailsController::class, 'getMSPType']);


/**
 * Route: POST /get-contract-terminology-policy
 *
 * Function: ApiController@getContractTerminologyPolicy
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Contract Terminology Policy listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality: Retrieves a list of Contract Terminology Policies.
 * Queries the database for Contract Terminology Policies categorized under the 'ContractTerminationPolicy' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Contract Terminology Policies upon successful retrieval, or appropriate error messages in case of failure.
 */



//Route::post('get-contract-terminology-policy', 'DetailsController@getContractTerminologyPolicy');
Route::post('get-contract-terminology-policy', [DetailsController::class, 'getContractTerminologyPolicy']);

/**
 * Route: POST /get-state-license
 * Function: ApiController@getStateLicense
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Specialities listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of State Licenses.
 * Queries the database for State Licenses using the 'getSpecialities' method from the Controller.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of State Licenses upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-state-license', 'DetailsController@getStateLicense');
Route::post('get-state-license', [DetailsController::class, 'getStateLicense']);

/**
 * Route: POST /get-charting-system
 * Function: ApiController@getChartingSystem
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Charting System listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of Charting Systems.
 * Queries the database for Charting Systems using the 'Keyword' model with the 'Charting' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Charting Systems upon successful retrieval,
 * or appropriate error messages in case of failure.
 */


//Route::post('get-charting-system', 'DetailsController@getChartingSystem');
Route::post('get-charting-system', [DetailsController::class, 'getChartingSystem']);


/**
 * Route: POST /get-profession-list
 * Function: ApiController@getProfessionList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Profession List listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of Professions.
 * Queries the database for Professions using the 'Keyword' model with the 'Profession' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Professions upon successful retrieval,
 * or appropriate error messages in case of failure.
 */


//Route::post('get-profession-list', 'DetailsController@getProfessionList');
Route::post('get-profession-list', [DetailsController::class, 'getProfessionList']);

/**
 * Route: POST /get-skills-list
 * Function: ApiController@getSkillList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Profession List listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of Skills.
 * Queries the database for Skills using the 'Keyword' model with the 'Skills' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Skills upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-skills-list', 'DetailsController@getSkillList');
Route::post('get-skills-list', [DetailsController::class, 'getSkillList']);

/**
 * Route: POST /get-vaccination-list
 * Function: ApiController@getVaccinationList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Vaccinations listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of Vaccinations.
 * Queries the database for Vaccinations using the 'Keyword' model with the 'Vaccinations' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Vaccinations upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-vaccination-list', 'DetailsController@getVaccinationList');
Route::post('get-vaccination-list', [DetailsController::class, 'getVaccinationList']);

/**
 * Route: POST /get-EMR-list
 * Function: ApiController@getEMRList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "EMR listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of EMRs (Electronic Medical Records).
 * Queries the database for EMRs using the 'Keyword' model with the 'EMR' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of EMRs upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-EMR-list', 'DetailsController@getEMRList');
Route::post('get-EMR-list', [DetailsController::class, 'getEMRList']);



/**
 * Route: POST /get-terms-list
 * Function: ApiController@getTermsList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Terms List listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of Terms.
 * Queries the database for Terms using the 'Keyword' model with the 'Terms' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Terms upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-terms-list', 'DetailsController@getTermsList');
Route::post('get-terms-list', [DetailsController::class, 'getTermsList']);


/**
 * Route: POST /get-vision-list
 * Function: ApiController@getVisionList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Vision List listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of Vision items.
 * Queries the database for Vision items using the 'Keyword' model with the 'Vision' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Vision items upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-vision-list', 'DetailsController@getVisionList');
Route::post('get-vision-list', [DetailsController::class, 'getVisionList']);


/**
 * Route: POST /get-healthinsurance-list
 * Function: ApiController@getHealthInsuranceList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Health Insurance List listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of Health Insurances.
 * Queries the database for Health Insurances using the 'Keyword' model with the 'HealthInsurance' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Health Insurances upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-healthinsurance-list', 'DetailsController@getHealthInsuranceList');
Route::post('get-healthinsurance-list', [DetailsController::class, 'getHealthInsuranceList']);

/**
 * Route: POST /get-dental-list
 * Function: ApiController@getDentalList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Dental List listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of Dental items.
 * Queries the database for Dental items using the 'Keyword' model with the 'Dental' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of Dental items upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-dental-list', 'DetailsController@getDentalList');
Route::post('get-dental-list', [DetailsController::class, 'getDentalList']);

/**
 * Route: POST /get-401k-list
 * Function: ApiController@getHowMuchKList
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "401k List listed successfully", "data": [{"id": "...", "name": "..."}, ...]}
 * - Failure: {"api_status": "0", "message": "...", "data": null}
 *
 * Functionality:
 * Retrieves a list of 401k items.
 * Queries the database for 401k items using the 'Keyword' model with the '401k' filter.
 * Formats the retrieved data into an array of objects containing 'id' and 'name' properties.
 * Returns success message along with the list of 401k items upon successful retrieval,
 * or appropriate error messages in case of failure.
 */

//Route::post('get-401k-list', 'DetailsController@getHowMuchKList');
Route::post('get-401k-list', [DetailsController::class, 'getHowMuchKList']);

/**
 * Route: POST /personal-detail
 * Function: ApiController@personalDetail
 *
 * @parameters:
 * - id (required): User ID
 * - first_name (required): First name of the user
 * - last_name (required): Last name of the user
 * - mobile (required): User's mobile number
 * - email (required): User's email address
 * - nursing_license_state (required): State for nursing license
 * - nursing_license_number (nullable): Nursing license number
 * - specialty (required): User's specialty
 * - address (required): User's address
 * - city (required): User's city
 * - state (required): User's state
 * - postcode (required): User's postcode
 * - country (required): User's country
 * - api_key (required): API key for authentication
 *
 * Functionality:
 * Updates personal details of a user and nurse in the system.
 * Validates the input data and updates the user and nurse details accordingly.
 * Returns success message upon successful update or appropriate error messages in case of failure.
 */

//Route::post('personal-detail', 'UserProfileController@personalDetail');
Route::post('personal-detail', [UserProfileController::class, 'personalDetail']);


/**
 * Route: POST /availability
 * Function: ApiController@availability
 *
 * @parameters:
 * - id (required): User ID
 * - hourly_pay_rate (required): Hourly pay rate
 * - shift_duration (required): Shift duration
 * - assignment_duration (required): Assignment duration
 * - preferred_shift (required): Preferred shift
 * - earliest_start_date (nullable): Earliest start date
 * - work_location (required): Work location
 * - api_key (required): API key for authentication
 *
 * Functionality:
 * Updates nurse availability and hourly pay rate.
 * Validates the input data and updates the nurse's availability and hourly pay rate accordingly.
 * Returns success message upon successful update or appropriate error messages in case of failure.
 */

//Route::post('availability', 'UserProfileController@availability');
Route::post('availability', [UserProfileController::class, 'availability']);

/**
 * Route: POST /get-availability
 * Function: ApiController@getAvailability
 *
 * @parameters:
 * - nurse_id (required): Nurse ID for availability check
 * - month (required): Month for availability check
 * - year (required): Year for availability check
 * - api_key (required): API key for authentication
 *
 * Functionality:
 * Retrieves unavailable dates for a nurse within a specific month and year.
 * Validates input parameters and retrieves unavailable dates based on nurse_id.
 * Filters and returns the unavailable dates within the given month and year.
 * Returns the list of unavailable dates or an appropriate error message if not found.
 */

//Route::post('get-availability', 'UserProfileController@getAvailability');

Route::post('get-availability', [UserProfileController::class, 'getAvailability']);


/**
 * Route: POST /shift-duration
 * Function: ApiController@shiftDuration
 *
 * Functionality:
 * Retrieves a list of shift durations.
 * Fetches shift durations and organizes them into an array with 'id' and 'name' properties.
 * Sorts the shift durations in ascending order and returns them.
 * Returns the list of shift durations.
 */

//Route::post('shift-duration', 'UserProfileController@shiftDuration');
Route::post('shift-duration', [UserProfileController::class, 'shiftDuration']);


/**
 * Route: POST /assignment-duration
 * Function: ApiController@assignmentDurations
 *
 * Functionality:
 * Retrieves a list of assignment durations.
 * Fetches assignment durations and organizes them into an array with 'id' and 'name' properties.
 * Returns the list of assignment durations.
 */

//Route::post('assignment-duration', 'UserProfileController@assignmentDurations');
Route::post('assignment-duration', [UserProfileController::class, 'assignmentDurations']);

/**
 * Route: POST /preferred-shifts
 * Function: ApiController@preferredShifts
 *
 * Functionality:
 * Retrieves a list of preferred shifts.
 * Fetches preferred shifts and organizes them into an array with 'id' and 'name' properties.
 * Returns the list of preferred shifts.
 */

//Route::post('preferred-shifts', 'UserProfileController@preferredShifts');
Route::post('preferred-shifts', [UserProfileController::class, 'preferredShifts']);
/**
 * Route: POST /get-weekdays
 * Function: ApiController@getWeekDay
 *
 * Functionality:
 * Retrieves a list of weekdays.
 * Fetches weekdays and organizes them into an array with 'id' and 'name' properties.
 * Returns the list of weekdays.
 */

//Route::post('get-weekdays', 'UserProfileController@getWeekDay');


Route::post('get-weekdays', [UserProfileController::class, 'getWeekDay']);


/**
 * Route: POST /state-list
 * Function: ApiController@stateList
 *
 * Functionality:
 * Retrieves a list of states.
 * Fetches state options and returns an array of states.
 * Returns the list of states.
 */

//Route::post('state-list', 'UserProfileController@stateList');
Route::post('state-list', [UserProfileController::class, 'stateList']);


/**
 * Route: POST /experience
 *
 * Function: ApiController@Experience
 *
 * @bodyparam1: id (required) - ID of the user
 * @bodyparam2: highest_nursing_degree (required) - Highest nursing degree
 * @bodyparam3: college_uni_name (required) - College / university name
 * ...
 *
 * @response:
 * - Success: {"api_status": "1", "message": "Experience updated successfully", "data": {...}}
 * - Failure: {"api_status": "0", "message": "Failed to update the experience, Please try again later", "data": null}
 *
 * Functionality: Handles the update of nursing experience information based on the provided request parameters. Validates the incoming request parameters and updates the nurse's experience if the user and nurse are found. Responds with a JSON indicating the status of the operation and any relevant messages.
 */

 //Route::post('experience', 'UserProfileController@Experience');
 Route::post('experience', [UserProfileController::class, 'Experience']);

/**
 * Route: POST /get-experience
 *
 * Function: ApiController@workerExperience
 *
 * @bodyparam1: api_key (required) - API key for authentication
 * @bodyparam2: worker_id (required) - ID of the worker
 * ...
 *
 * @response:
 * - Success: {"api_status": "1", "message": "Worker experience details listed successfully", "data": [...] }
 * - Failure: {"api_status": "0", "message": "Worker not found", "data": []}
 *
 * Functionality: Retrieves worker (nurse) experience details based on the provided worker ID. Fetches certifications and details related to a worker's experience. Constructs and returns detailed information about a worker's experience.
 */

 //Route::post('get-experience', 'UserProfileController@workerExperience');
 Route::post('get-experience', [UserProfileController::class, 'workerExperience']);

/**
 * Route: POST /facility-types
 *
 * Function: ApiController@facilityTypes
 *
 * @bodyparam1: None
 *
 * @response:
 * - Success: {"api_status": "1", "message": "Facility types have been listed successfully", "data": [...] }
 *
 * Functionality: Fetches a list of facility types and their associated IDs. Constructs and returns the list of facility types successfully.
 */

 //Route::post('facility-types', 'UserProfileController@facilityTypes');
 Route::post('facility-types', [UserProfileController::class, 'facilityTypes']);
/**
 * Route: POST /nurse-experience-selections
 *
 * Function: ApiController@nurseExperienceSelectionOptions
 *
 * @bodyparam1: id (required) - ID of the user
 * @bodyparam2: api_key (required) - API key for authentication
 * ...
 *
 * @response:
 * - Success: {"api_status": "1", "message": "Facility type's have been listed successfully", "data": [...] }
 * - Failure: {"api_status": "0", "message": "Nurse not found", "data": null}
 *
 * Functionality: Retrieves nurse experience selection options based on the provided nurse ID. Fetches nurse-specific experience selections and returns them successfully.
 */

// Route::post('nurse-experience-selections', 'UserProfileController@nurseExperienceSelectionOptions');

 Route::post('nurse-experience-selections', [UserProfileController::class, 'nurseExperienceSelectionOptions']);



 /**
 * Route: POST /forgot-password
 *
 * Function: ApiController@sendResetLinkEmail
 *
 * @bodyparam1: email (required) - Email address of the user
 * @bodyparam2: api_key (required) - API key for authentication
 *
 * @response:
 * - Success: {"api_status": "1", "message": "Reset password link sent successfully", "data": null}
 * - Failure: {"api_status": "0", "message": "User not found", "data": null}
 *
 * Functionality: Sends a password reset link to the user's email address if found. Generates a unique token and constructs an email template to send the reset link.
 */

 //Route::post('forgot-password', 'UserProfileController@sendResetLinkEmail');

 Route::post('forgot-password', [UserProfileController::class, 'sendResetLinkEmail']);


/**
 * Route: POST /new-phone-number
 *
 * Function: ApiController@newPhoneNumber
 *
 * @bodyparam1: user_id (required) - ID of the user
 * @bodyparam2: phone_number (required) - New phone number
 * @bodyparam3: api_key (required) - API key for authentication
 *
 * @response:
 * - Success: {"api_status": "1", "message": "OTP sent successfully to this number", "data": {"otp": "XXXX"}}
 * - Failure: {"api_status": "0", "message": "User not found", "data": null}
 *
 * Functionality: Updates the user's new phone number. Generates an OTP (One Time Password) and sends it to the provided phone number for verification.
 */

 //Route::post('new-phone-number', 'UserProfileController@newPhoneNumber');
 Route::post('new-phone-number', [UserProfileController::class, 'newPhoneNumber']);

/**
 * Route: POST /get-countries
 *
 * Function: ApiController@getCountries
 *
 * @bodyparam1: None
 *
 * @response:
 * - Success: {"api_status": "1", "message": "Countries listed successfully", "data": [...] }
 *
 * Functionality: Retrieves a list of countries and their IDs. Rearranges the list by moving USA and Canada to the top rows for easier access.
 */

// Route::post('get-countries', 'StaticContentController@getCountries');
 Route::post('get-countries', [StaticContentController::class, 'getCountries']);



/**
 * Route: POST /get-states
 *
 * Function: ApiController@getStates
 *
 * @bodyparam1: country_id (required) - ID of the country
 * @bodyparam2: api_key (required) - API key for authentication
 *
 * @response:
 * - Success: {"api_status": "1", "message": "States listed successfully", "data": [...] }
 *
 * Functionality: Retrieves a list of states based on the provided country ID.
 */
//Route::post('get-states', 'StaticContentController@getStates');
Route::post('get-states', [StaticContentController::class, 'getStates']);
/**
 * Route: POST /get-cities
 *
 * Function: ApiController@getCities
 *
 * @bodyparam1: state_id (required) - ID of the state
 * @bodyparam2: api_key (required) - API key for authentication
 *
 * @response:
 * - Success: {"api_status": "1", "message": "Cities listed successfully", "data": [...] }
 *
 * Functionality: Retrieves a list of cities based on the provided state ID.
 */
//Route::post('get-cities', 'StaticContentController@getCities');
Route::post('get-cities', [StaticContentController::class, 'getCities']);


/**
 * Route: POST /terms-conditions
 *
 * Function: ApiController@termsAndConditions
 *
 * @bodyparam1: None
 *
 * @response:
 * - Success: {"api_status": "1", "message": "Terms and Conditions", "data": "Terms and Conditions content" }
 *
 * Functionality: Returns the Terms and Conditions content.
 */
//Route::post('terms-conditions', 'StaticContentController@termsAndConditions');
Route::post('terms-conditions', [StaticContentController::class, 'termsAndConditions']);


/**
 * Route: POST /privacy-policy
 *
 * Function: ApiController@privacyPolicy
 *
 * @bodyparam1: api_key (required) - API key for authentication
 *
 * @response:
 * - Success: {"api_status": "1", "About-web": "URL", "message": "Privacy Policy", "data": "Privacy Policy content" }
 *
 * Functionality: Returns the Privacy Policy content along with the URL.
 */
//Route::post('privacy-policy', 'StaticContentController@privacyPolicy');
Route::post('privacy-policy', [StaticContentController::class, 'privacyPolicy']);

/**
 * Route: POST /about-app
 *
 * Function: ApiController@aboutAPP
 *
 * @bodyparam1: api_key (required) - API key for authentication
 *
 * @response:
 * - Success: {"api_status": "1", "about_web": "URL", "message": "About App", "data": "About App content" }
 *
 * Functionality: Returns the About App content along with the URL.
 */
//Route::post('about-app', 'StaticContentController@aboutAPP');
Route::post('about-app', [StaticContentController::class, 'aboutAPP']);





//worker Details new function dosn't exist in ApiController
// Route::post('get-worker-detail-new', 'ApiController@workerDetailsNew');
// Route::post('nurse-certification-detail', 'ApiController@nursecertificationDetail');
// Route::post('highest-nursing-degrees', 'ApiController@NursingDegrees');


// Route::post('certification-type-list', 'ApiController@searchForCredentialsOptions');
// Route::post('media-options', 'ApiController@getMediaOptions');
// Route::post('get-cerner-medtech-epic-options', 'ApiController@getEHRProficiencyExpOptions');
// Route::post('nursing-degrees-options', 'ApiController@getNursingDegreesOptions');

Route::post('certification-type-list', [ApiController::class, 'searchForCredentialsOptions']);
Route::post('media-options', [ApiController::class, 'getMediaOptions']);
Route::post('get-cerner-medtech-epic-options', [ApiController::class, 'getEHRProficiencyExpOptions']);
Route::post('nursing-degrees-options', [ApiController::class, 'getNursingDegreesOptions']);

// New Apis

/**
 * Route: POST /add-certification
 *
 * Function: ApiController@addCredentials
 *
 * @bodyparam1: user_id (required) - ID of the user to add certification.
 * @bodyparam2: type (required) - Type of certification (numeric value).
 * @bodyparam3: effective_date (required) - Issue date of the certification (format: YYYY-MM-DD).
 * @bodyparam4: expiration_date (required) - Expiration date of the certification (format: YYYY-MM-DD).
 * @bodyparam5: renewal_date - Renewal date of the certification (optional, format: YYYY-MM-DD).
 * @bodyparam6: certificate_image - Image of the certificate (optional, allowed types: jpeg, png, jpg, pdf).
 * @bodyparam7: resume - Resume file (optional, allowed types: doc, docx, pdf, txt).
 * @bodyparam8: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Certification added successfully", "data": {...}} (Certification details)
 * - Failure: {"api_status": "0", "message": "Problem occurred while updating certification, Please try again later", "data": null}
 *
 * Functionality: Adds a certification for a user after validating and processing the provided information.
 * Handles certificate image upload and returns success or failure messages accordingly.
 */
//Route::post('add-certification', 'CertificationController@addCredentials');
Route::post('add-certification', [CertificationController::class, 'addCredentials']);

/**
 * Route: POST /edit-certification
 *
 * Function: ApiController@editCredentials
 *
 * @bodyparam1: user_id (required) - ID of the user to edit certification.
 * @bodyparam2: certificate_id (required) - ID of the certificate to be edited.
 * @bodyparam3: type (required) - Type of certification (numeric value).
 * @bodyparam4: effective_date (required) - Issue date of the certification (format: YYYY-MM-DD).
 * @bodyparam5: expiration_date (required) - Expiration date of the certification (format: YYYY-MM-DD).
 * @bodyparam6: renewal_date - Renewal date of the certification (optional, format: YYYY-MM-DD).
 * @bodyparam7: certificate_image - Image of the certificate (optional, allowed types: jpeg, png, jpg, pdf).
 * @bodyparam8: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Certificate updated successfully", "data": {...}} (Updated certification details)
 * - Failure: {"api_status": "0", "message": "Failed to update the certificate. Please try again later", "data": null}
 *
 * Functionality: Edits an existing certification for a user after validating and processing the provided information.
 * Handles certificate image upload and returns success or failure messages accordingly.
 */

//Route::post('edit-certification', 'CertificationController@editCredentials');
Route::post('edit-certification', [CertificationController::class, 'editCredentials']);


/**
 * Route: POST /remove-credentials-image
 *
 * Function: ApiController@removeCredentialDoc
 *
 * @bodyparam1: user_id (required) - ID of the user to remove certificate image.
 * @bodyparam2: certificate_id (required) - ID of the certificate to remove its image.
 * @bodyparam3: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Certificate removed successfully", "data": null}
 * - Failure: {"api_status": "0", "message": "Failed to remove or certificate removed already. Please try again later", "data": null}
 *
 * Functionality: Removes the certificate image associated with a specific certificate for a user.
 * Handles deletion of the image file and returns success or failure messages accordingly.
 */
//Route::post('remove-credentials-image', 'CertificationController@removeCredentialDoc');
Route::post('remove-credentials-image', [CertificationController::class, 'removeCredentialDoc']);
/**
 * Route: POST /get-leadership-roles
 *
 * Function: ApiController@leadershipRoles
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Leadership roles has been listed successfully", "data": [...] (Array of leadership roles)}
 * - Failure: {"api_status": "0", "message": "Error occurred while listing leadership roles", "data": null}
 *
 * Functionality: Retrieves a list of leadership roles available and returns it as a response.
 */
//Route::post('get-leadership-roles', 'ApiController@leadershipRoles');
Route::post('get-leadership-roles', [ApiController::class, 'leadershipRoles']);

/**
 * Route: POST /get-languages-list
 *
 * Function: ApiController@getLanguages
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Languages has been listed successfully", "data": [...] (Array of languages)}
 * - Failure: {"api_status": "0", "message": "Error occurred while listing languages", "data": null}
 *
 * Functionality: Retrieves a list of languages available and returns it as a response.
 */
//Route::post('get-languages-list', 'ApiController@getLanguages');
Route::post('get-languages-list', [ApiController::class, 'getLanguages']);


/**
 * Route: POST /role-and-interest/page-1
 *
 * Function: ApiController@rolePage1
 *
 * @bodyparam1: id (required) - User ID for updating roles and interests.
 * @bodyparam2: serving_preceptor - Serving as a preceptor (boolean).
 * @bodyparam3: serving_interim_nurse_leader - Serving as an interim nurse leader (boolean).
 * @bodyparam4: leadership_roles - Selected leadership role (required if serving_interim_nurse_leader is true).
 * @bodyparam5: clinical_educator - Clinical educator (boolean).
 * @bodyparam6: is_daisy_award_winner - Daisy award winner (boolean).
 * @bodyparam7: employee_of_the_mth_qtr_yr - Employee of the month/quarter/year (boolean).
 * @bodyparam8: other_nursing_awards - Other nursing awards (boolean).
 * @bodyparam9: is_professional_practice_council - Part of professional practice council (boolean).
 * @bodyparam10: is_research_publications - Research publications (boolean).
 * @bodyparam11: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Role and Interest Updated Successfully", "data": {...}} (Updated nurse details)
 * - Failure: {"api_status": "0", "message": "Failed to update roles and interests. Please try again later", "data": null}
 *
 * Functionality: Validates and updates nurse roles and interests based on provided parameters.
 */

 //Route::post('role-and-interest/page-1', 'RoleController@rolePage1');
 Route::post('role-and-interest/page-1', [RoleController::class, 'rolePage1']);



/**
 * Route: POST /role-and-interest/page-2
 *
 * Function: ApiController@rolePage2
 *
 * @bodyparam1: id (required) - User ID for updating additional nurse details.
 * @bodyparam2: additional_pictures - Additional photos (array of images, max: 4, max size: 5MB each, allowed types: jpeg, png, jpg).
 * @bodyparam3: additional_files - Additional files (array of documents, max: 4, max size: 1MB each, allowed types: pdf, doc, docx).
 * @bodyparam4: nu_video - Video URL (optional, YouTube or Vimeo valid link).
 * @bodyparam5: summary - Summary of additional information.
 * @bodyparam6: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Role and Interest Updated Successfully", "data": {...}} (Updated nurse details)
 * - Failure: {"api_status": "0", "message": "Failed to update additional nurse details. Please try again later", "data": null}
 *
 * Functionality: Validates and updates additional nurse details for roles and interests.
 */
//Route::post('role-and-interest/page-2', 'RoleController@rolePage2');
Route::post('role-and-interest/page-2', [RoleController::class, 'rolePage2']);

/**
 * Route: POST /remove-role-interest-doc
 *
 * Function: ApiController@destroyRoleInterestDocument
 *
 * @bodyparam1: user_id (required) - ID of the user to remove the document.
 * @bodyparam2: asset_id (required) - ID of the document to be removed.
 * @bodyparam3: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Document removed successfully", "data": null}
 * - Failure: {"api_status": "0", "message": "Failed to remove document. Please try again later", "data": null}
 *
 * Functionality: Removes a specific document related to nurse roles and interests.
 */

 //Route::post('remove-role-interest-doc', 'RoleController@destroyRoleInterestDocument');
 Route::post('remove-role-interest-doc', [RoleController::class, 'destroyRoleInterestDocument']);
/**
 * Route: POST /browse-jobs
 *
 * Function: ApiController@jobList
 *
 * @bodyparam1: user_id (required) - ID of the user to fetch job list.
 * @bodyparam2: profession - Filter jobs by profession.
 * @bodyparam3: type - Filter jobs by type.
 * -@bodyparam4: preferred_specialty - Filter jobs by preferred specialty.
 * -@bodyparam5: preferred_experience - Filter jobs by preferred experience.
 * @bodyparam6: search_location - Search for jobs by location.
 * @bodyparam7: job_type - Filter jobs by job type.
 * @bodyparam8: end_date - Filter jobs by end date.
 * @bodyparam9: preferred_shift - Filter jobs by preferred shift.
 * @bodyparam10: auto_offers - Filter jobs by auto offers.
 * @bodyparam11: weekly_pay_from - Filter jobs by weekly pay (from).
 * @bodyparam12: weekly_pay_to - Filter jobs by weekly pay (to).
 * @bodyparam13: hourly_pay_from - Filter jobs by hourly pay (from).
 * @bodyparam14: hourly_pay_to - Filter jobs by hourly pay (to).
 * @bodyparam15: hours_per_week_from - Filter jobs by hours per week (from).
 * @bodyparam16: hours_per_week_to - Filter jobs by hours per week (to).
 * @bodyparam17: assignment_from - Filter jobs by preferred assignment duration (from).
 * @bodyparam18: assignment_to - Filter jobs by preferred assignment duration (to).
 * @bodyparam19: start_date - Filter jobs by start date.
 * @bodyparam20: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Jobs listed successfully", "data": [...] (Array of job details)}
 * - Failure: {"api_status": "0", "message": "Failed to list jobs. Please try again later", "data": null}
 *
 * Functionality: Retrieves a list of available jobs based on provided criteria for a nurse.
 */
//Route::post('browse-jobs', 'JobControllerApi@jobList');
Route::post('browse-jobs', [JobControllerApi::class, 'jobList']);


/**
 * Route: POST /view-job
 *
 * Function: ApiController@viewJob
 *
 * @bodyparam1: id (required) - ID of the job to view.
 * @bodyparam2: user_id (required) - ID of the user viewing the job.
 * @bodyparam3: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "View job listed successfully", "data": [...] (Array of job details)}
 * - Failure: {"api_status": "0", "message": "Failed to view the job. Please try again later", "data": null}
 *
 * Functionality: Retrieves detailed information about a specific job based on the provided ID.
 */

//Route::post('view-job', 'JobControllerApi@viewJob');
Route::post('view-job', [JobControllerApi::class, 'viewJob']);

/**
 * Route: POST /job-applied
 *
 * Function: ApiController@jobApplied
 *
 * @bodyparam1: user_id (required) - ID of the user who applied for the job.
 * @bodyparam2: job_id (required) - ID of the job applied for.
 * @bodyparam3: type (required) - Type of application (e.g., "1" for applied, "0" for removal).
 * @bodyparam4: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Applied successfully", "data": {...}} (Job application details)
 * - Failure: {"api_status": "0", "message": "Failed to apply/remove job. Please try again later", "data": null}
 *
 * Functionality: Processes job applications or removals for users and returns success or failure messages accordingly.
 */
//Route::post('job-applied', 'JobControllerApi@jobApplied');
Route::post('job-applied', [JobControllerApi::class, 'jobApplied']);

/**
 * Route: POST /job-like
 *
 * Function: ApiController@jobLikes
 *
 * @bodyparam1: user_id (required) - ID of the user who liked the job.
 * @bodyparam2: job_id (required) - ID of the job liked.
 * @bodyparam3: role (optional) - Role information.
 * @bodyparam4: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Job saved successfully", "data": {...}} (Job liking details)
 * - Failure: {"api_status": "0", "message": "Failed to save/remove job. Please try again later", "data": null}
 *
 * Functionality: Handles job liking or removal for users and returns success or failure messages accordingly.
 */
//Route::post('job-like', 'JobControllerApi@jobLikes');
Route::post('job-like', [JobControllerApi::class, 'jobLikes']);

/**
 * Route: POST /job-popular
 *
 * Function: ApiController@jobPopular
 *
 * @bodyparam1: user_id (required) - ID of the user.
 * @bodyparam2: api_key (required) - API key for authentication.
 * @bodyparam3: job_id (required) - ID of the job.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Popular Jobs listed successfully", "data": [...] (Array of popular jobs)}
 * - Failure: {"api_status": "0", "message": "Failed to list popular jobs", "data": null}
 *
 * Functionality: Retrieves a list of popular jobs and returns it as a response based on specified criteria.
 */
//Route::post('job-popular', 'JobControllerApi@jobPopular');
Route::post('job-popular', [JobControllerApi::class, 'jobPopular']);
/**
 * Route: POST /browse-facility
 *
 * Function: ApiController@browse_facilities
 *
 * @bodyparam1: facility_id - ID of the facility to browse (optional).
 * @bodyparam2: facility_type - Type of facility (optional).
 * @bodyparam3: electronic_medical_records - Electronic Medical Records filter (optional).
 * @bodyparam4: search_keyword - Search keyword for facility name (optional).
 * @bodyparam5: open_assignment_type - Type of open assignment (optional).
 * @bodyparam6: state - State filter (optional).
 * @bodyparam7: city - City filter (optional).
 * @bodyparam8: zipcode - Zipcode filter (optional).
 * @bodyparam9: user_id - ID of the user (optional).
 * @bodyparam10: api_key - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Facilities listed below", "data": [...] (Array of facilities)}
 * - Failure: {"api_status": "0", "message": "Failed to list facilities", "data": null}
 *
 * Functionality: Retrieves a list of facilities based on specified filters and returns it as a response.
 */
//Route::post('browse-facility', 'RoleController@browse_facilities');
Route::post('browse-facility', [RoleController::class, 'browse_facilities']);


/**
 * Route: POST /facility-follow
 *
 * Function: ApiController@facilityFollows
 *
 * @bodyparam1: user_id (required) - ID of the user following/unfollowing the facility.
 * @bodyparam2: facility_id (required) - ID of the facility to follow/unfollow.
 * @bodyparam3: type (required) - Type of action (e.g., "1" for follow, "0" for unfollow).
 * @bodyparam4: api_key (required) - API key for authentication.
 *
 * @responseExample:
 * - Success: {"api_status": "1", "message": "Followed/Unfollowed successfully", "data": {...}} (Facility follow details)
 * - Failure: {"api_status": "0", "message": "Failed to follow/unfollow facility. Please try again later", "data": null}
 *
 * Functionality: Handles facility follow/unfollow for users and returns success or failure messages accordingly.
 */

//Route::post('facility-follow', 'RoleController@facilityFollows');
Route::post('facility-follow', [RoleController::class, 'facilityFollows']);


/**
 * Route: facility-like
 * Function: facilityLikes(Request $request)
 * Objective: Manages facility likes and dislikes based on user interaction.
 * Parameters:
 *   - user_id: User identification
 *   - facility_id: Facility identification
 *   - like: Indicates the like status (1 for like, 0 for dislike)
 *   - api_key: Authentication API key
 * Response: JSON response confirming successful liking or disliking of the facility.
 */
//Route::post('facility-like', 'RoleController@facilityLikes');
Route::post('facility-like', [RoleController::class, 'facilityLikes']);
/**
 * Route: job-offers
 * Function: jobOffered(Request $request)
 * Objective: Retrieves job offers tailored for a nurse using their ID.
 * Parameters:
 *   - user_id: Nurse's ID
 *   - api_key: Authorization API key
 * Response: JSON response including detailed job offer information like facility details, job title, duration, shift, and dates.
 */
//Route::post('job-offers', 'JobControllerApi@jobOffered');
Route::post('job-offers', [JobControllerApi::class, 'jobOffered']);




/**
 * Route: job-accept
 * Function: jobAcceptPost(Request $request)
 * Objective: Allows a nurse to accept a specific job offer.
 * Parameters:
 *   - user_id: Nurse's ID
 *   - offer_id: ID of the job offer
 *   - api_key: Authorization API key
 * Response: JSON response confirming the successful acceptance of the job offer.
 */
//Route::post('job-accept', 'JobControllerApi@jobAcceptPost');
Route::post('job-accept', [JobControllerApi::class, 'jobAcceptPost']);

/**
 * Route: job-reject
 * Function: jobRejectPost(Request $request)
 * Objective: Enables a nurse to reject a specific job offer.
 * Parameters:
 *   - user_id: Nurse's ID
 *   - offer_id: ID of the job offer
 *   - api_key: Authorization API key
 * Response: JSON response confirming the successful rejection of the job offer.
 */
//Route::post('job-reject', 'JobControllerApi@jobRejectPost');
Route::post('job-reject', [JobControllerApi::class, 'jobRejectPost']);

/**
 * Route: job-completed
 * Function: jobCompleted(Request $request)
 * Objective: Lists completed jobs for a nurse based on their ID.
 * Parameters:
 *   - user_id: Nurse's ID
 *   - api_key: Authorization API key
 * Response: JSON response with information about completed jobs including facility details, work duration, shift, start and end dates.
 */
//Route::post('job-completed', 'JobControllerApi@jobCompleted');
Route::post('job-completed', [JobControllerApi::class, 'jobCompleted']);


/**
 * Route: get-notification
 * Function: notification(Request $request)
 * Objective: Retrieves and formats notifications for workers, nurses, or recruiters based on role and user ID.
 * Parameters:
 *   - role: Role of the user (worker, nurse, recruiter)
 *   - user_id: User ID to fetch notifications
 * Response: JSON response with formatted notification data or a message indicating no notifications.
 */
//Route::post('get-notification', 'JobControllerApi@notification');
Route::post('get-notification', [JobControllerApi::class, 'notification']);
/**
 * Route: get-offer-notification
 * Function: offerNotification(Request $request)
 * Objective: Fetches offer notifications for workers, nurses, or recruiters based on role and user ID.
 * Parameters:
 *   - worker_user_id: Worker or nurse's ID for offer notifications
 *   - recruiter_id: Recruiter's ID for offer notifications
 * Response: JSON response containing fetched offer notifications or a message if no notifications are available.
 */
//Route::post('get-offer-notification', 'JobControllerApi@offerNotification');
Route::post('get-offer-notification', [JobControllerApi::class, 'offerNotification']);



/**
 * Route: remove-notification
 * Function: removeNotification(Request $request)
 * Objective: Clears a specific notification based on user ID and notification ID.
 * Parameters:
 *   - user_id: User ID of the notification owner
 *   - notification_id: ID of the notification to be removed
 *   - api_key: Authentication API key
 * Response: JSON response confirming successful removal or indicating reasons for failure.
 */
//Route::post('remove-notification', 'JobControllerApi@removeNotification');
Route::post('remove-notification', [JobControllerApi::class, 'removeNotification']);
/**
 * Route: settings
 * Function: settings(Request $request)
 * Objective: Fetches and returns user settings and profile information based on user ID.
 * Parameters:
 *   - user_id: User ID to retrieve settings and profile details
 *   - api_key: Authentication API key
 * Response: JSON response with user settings and profile data or an error message if the user is not found.
 */
//Route::post('settings', 'UserProfileController@settings');
Route::post('settings', [UserProfileController::class, 'settings']);
/**
 * Route: get-nurse-profile
 * Function: NurseProfileInfo(Request $request)
 * Objective: Retrieves nurse profile information based on user ID or nurse ID.
 * Parameters:
 *   - user_id: User ID or nurse ID for profile information
 *   - api_key: Authentication API key
 *   - nurse_id: Nurse ID (if different from user ID)
 * Response: JSON response containing nurse profile data or a message if the nurse or user is not found.
 */
//Route::post('get-nurse-profile', 'UserProfileController@NurseProfileInfo');
Route::post('get-nurse-profile', [UserProfileController::class, 'NurseProfileInfo']);

// Get nurse information // edited
// Route::post('register', 'WorkerController@register');


// Route::post('set-banking-details', 'WorkerController@setBankingDetails');
// Route::post('get-banking-details', 'WorkerController@getBankingDetails');
// Route::post('worker-profile-HomeScreen', 'WorkerController@workerProfileHomeScreen');
// Route::post('worker-home-screen', 'WorkerController@workerHomeScreen');
// Route::post('home-screen-graph', 'WorkerController@graphHomeScreen');
// Route::post('get-worker-info', 'WorkerController@workerInfo');
// Route::post('get-worker-basicinfo', 'WorkerController@workerBasicInfo');
// Route::post('get-worker-skills', 'WorkerController@workerSkills');
// Route::post('get-worker-vaccination', 'WorkerController@workerVaccination');
// Route::post('get-worker-referrence', 'WorkerController@workerReferrence');
// Route::post('get-worker-certificate', 'WorkerController@workerCertificates');
// Route::post('get-worker-urgency', 'WorkerController@workerUrgency');
// Route::post('worker-account-info', 'WorkerController@workerAccountInfo');
// Route::post('get-worker-facilityinfo', 'WorkerController@workerFacilityInfo');
// Route::post('get-patient-ratio', 'WorkerController@patientRatio');
// Route::post('get-worker-dates', 'WorkerController@interviewDate');
// Route::post('get-worker-bonus', 'WorkerController@workerBonus');
// Route::post('get-worker-feelshour', 'WorkerController@workerFeelsLikeHour');

// Route::post('get-nurse-profile-by-mobile', 'WorkerController@NurseProfileInfoBymobile');
// Route::post('get-emedical-records', 'WorkerController@getEMedicalRecordsOptions');
// Route::post('update-profile-picture', 'WorkerController@profilePictureUpload');
// Route::post('update-role-interest', 'WorkerController@updateRoleInterest');
// Route::post('nurse-resume', 'WorkerController@resume');



// Route::post('change-password', 'WorkerController@changePassword');

// Route::post('view-job-detail', 'WorkerController@viewJobOffered');
// Route::post('facility-rating', 'WorkerController@facilityRatings');

// Route::post('confirm-otp', 'WorkerController@confirmOTP');

// Route::post('worker-information', 'WorkerController@workerInformation');
// Route::post('skip-worker-information', 'WorkerController@workerInformationSkip');
// Route::post('update-worker-information', 'WorkerController@updateWorkerInformation');


Route::post('register', [WorkerController::class, 'register']);
Route::post('set-banking-details', [WorkerController::class, 'setBankingDetails']);
Route::post('get-banking-details', [WorkerController::class, 'getBankingDetails']);
Route::post('worker-profile-HomeScreen', [WorkerController::class, 'workerProfileHomeScreen']);
Route::post('worker-home-screen', [WorkerController::class, 'workerHomeScreen']);
Route::post('home-screen-graph', [WorkerController::class, 'graphHomeScreen']);
Route::post('get-worker-info', [WorkerController::class, 'workerInfo']);
Route::post('get-worker-basicinfo', [WorkerController::class, 'workerBasicInfo']);
Route::post('get-worker-skills', [WorkerController::class, 'workerSkills']);
Route::post('get-worker-vaccination', [WorkerController::class, 'workerVaccination']);
Route::post('get-worker-referrence', [WorkerController::class, 'workerReferrence']);
Route::post('get-worker-certificate', [WorkerController::class, 'workerCertificates']);
Route::post('get-worker-urgency', [WorkerController::class, 'workerUrgency']);
Route::post('worker-account-info', [WorkerController::class, 'workerAccountInfo']);
Route::post('get-worker-facilityinfo', [WorkerController::class, 'workerFacilityInfo']);
Route::post('get-patient-ratio', [WorkerController::class, 'patientRatio']);
Route::post('get-worker-dates', [WorkerController::class, 'interviewDate']);
Route::post('get-worker-bonus', [WorkerController::class, 'workerBonus']);
Route::post('get-worker-feelshour', [WorkerController::class, 'workerFeelsLikeHour']);
Route::post('get-nurse-profile-by-mobile', [WorkerController::class, 'NurseProfileInfoBymobile']);
Route::post('get-emedical-records', [WorkerController::class, 'getEMedicalRecordsOptions']);
Route::post('update-profile-picture', [WorkerController::class, 'profilePictureUpload']);
Route::post('update-role-interest', [WorkerController::class, 'updateRoleInterest']);
Route::post('nurse-resume', [WorkerController::class, 'resume']);
Route::post('change-password', [WorkerController::class, 'changePassword']);
Route::post('view-job-detail', [WorkerController::class, 'viewJobOffered']);
Route::post('facility-rating', [WorkerController::class, 'facilityRatings']);
Route::post('confirm-otp', [WorkerController::class, 'confirmOTP']);
Route::post('worker-information', [WorkerController::class, 'workerInformation']);
Route::post('skip-worker-information', [WorkerController::class, 'workerInformationSkip']);
Route::post('update-worker-information', [WorkerController::class, 'updateWorkerInformation']);





/* facility */
// Route::post('facility-dropdown-{type}', 'FacilityController@facilityDropdown');
// Route::post('facility-profile', 'FacilityController@facilityDetail');
// Route::post('change-facility-logo', 'FacilityController@changeFacilityLogo');
// Route::post('browse-nurses', 'FacilityController@browseNurses');
// Route::post('get-seniority-level', 'FacilityController@getSeniorityLevelOptions');
// Route::post('job-offered-{type}', 'FacilityController@offeredNurses');
// Route::post('job-{type}', 'FacilityController@createJob');
// Route::post('get-job-function', 'FacilityController@getJobFunctionOptions');
// Route::post('apply', 'FacilityController@apiJobApply');
// Route::post('send-offer', 'FacilityController@apiJobInvite');
// Route::post('my-jobs-{type}', 'FacilityController@facilityPostedJobs');
// Route::post('offer-job-to-nurse-dropdown', 'FacilityController@apiJobsList');
// Route::post('job-info-short', 'FacilityController@apiJobFacility');
// Route::post('nurses-applied-jobs', 'FacilityController@appliedNurses');
// Route::post('nurse-rating', 'FacilityController@nurseRating');
// Route::post('remove-job-asset', 'FacilityController@removeJobDocument');
// Route::post('facility-settings', 'FacilityController@settingsFacility');
// Route::post('facility-notifications', 'FacilityController@notificationFacility');
// Route::post('jobs-information', 'FacilityController@jobInformation');
// Route::post('get-user-images', 'FacilityController@userImages');
// Route::post('testing', 'FacilityController@test');
// Route::post('get-search-status', 'FacilityController@getSearchStatusOptions')->name('search-status');
// Route::post('get-license-types', 'FacilityController@getLicenseTypeOptions')->name('license-types');
// Route::post('get-license-status', 'FacilityController@getLicenseStatusOptions')->name('license-status');
// Route::post('nurse-license-detail', 'FacilityController@nurseLicenseDetail');
// Route::post('addUserActivity', 'FacilityController@addUserActivity');
// Route::post('explore', 'FacilityController@explore');
// Route::post('save-job', 'FacilityController@saveJob');
// Route::post('remove-saved-job', 'FacilityController@removesavedJob');
// Route::post('my-saved-jobs', 'FacilityController@jobSaved');
// Route::post('nurse-saved-jobs', 'FacilityController@nurseJobSaved');
// Route::post('my-applied-jobs', 'FacilityController@myjobApplied');
// Route::post('my-offered-jobs', 'FacilityController@myjobOffered');
// Route::post('my-hired-jobs', 'FacilityController@myjobHired');
// Route::post('my-past-jobs', 'FacilityController@myjobPast');
// Route::post('nurse-personal-detail', 'FacilityController@nursepersonalDetail');
// Route::delete('delete-nurse', 'FacilityController@deleteNurse');
// Route::post('nurse-education-detail', 'FacilityController@nurseEducationDetail');
// Route::post('add-experience-detail', 'FacilityController@addnurseExperienceDetail');
// Route::post('edit-experience-detail', 'FacilityController@editnurseExperienceDetail');
// Route::post('experience-type-list', 'FacilityController@experienceTpesOptions');
// Route::post('get-employer-list', 'FacilityController@getfacilities');
// Route::post('explore-browse-jobs', 'FacilityController@exploreJobList');

Route::post('facility-dropdown-{type}', [FacilityController::class, 'facilityDropdown']);
Route::post('facility-profile', [FacilityController::class, 'facilityDetail']);
Route::post('change-facility-logo', [FacilityController::class, 'changeFacilityLogo']);
Route::post('browse-nurses', [FacilityController::class, 'browseNurses']);
Route::post('get-seniority-level', [FacilityController::class, 'getSeniorityLevelOptions']);
Route::post('job-offered-{type}', [FacilityController::class, 'offeredNurses']);
Route::post('job-{type}', [FacilityController::class, 'createJob']);
Route::post('get-job-function', [FacilityController::class, 'getJobFunctionOptions']);
Route::post('apply', [FacilityController::class, 'apiJobApply']);
Route::post('send-offer', [FacilityController::class, 'apiJobInvite']);
Route::post('my-jobs-{type}', [FacilityController::class, 'facilityPostedJobs']);
Route::post('offer-job-to-nurse-dropdown', [FacilityController::class, 'apiJobsList']);
Route::post('job-info-short', [FacilityController::class, 'apiJobFacility']);
Route::post('nurses-applied-jobs', [FacilityController::class, 'appliedNurses']);
Route::post('nurse-rating', [FacilityController::class, 'nurseRating']);
Route::post('remove-job-asset', [FacilityController::class, 'removeJobDocument']);
Route::post('facility-settings', [FacilityController::class, 'settingsFacility']);
Route::post('facility-notifications', [FacilityController::class, 'notificationFacility']);
Route::post('jobs-information', [FacilityController::class, 'jobInformation']);
Route::post('get-user-images', [FacilityController::class, 'userImages']);
Route::post('testing', [FacilityController::class, 'test']);
Route::post('get-search-status', [FacilityController::class, 'getSearchStatusOptions'])->name('search-status');
Route::post('get-license-types', [FacilityController::class, 'getLicenseTypeOptions'])->name('license-types');
Route::post('get-license-status', [FacilityController::class, 'getLicenseStatusOptions'])->name('license-status');
Route::post('nurse-license-detail', [FacilityController::class, 'nurseLicenseDetail']);
Route::post('addUserActivity', [FacilityController::class, 'addUserActivity']);
Route::post('explore', [FacilityController::class, 'explore']);
Route::post('save-job', [FacilityController::class, 'saveJob']);
Route::post('remove-saved-job', [FacilityController::class, 'removesavedJob']);
Route::post('my-saved-jobs', [FacilityController::class, 'jobSaved']);
Route::post('nurse-saved-jobs', [FacilityController::class, 'nurseJobSaved']);
Route::post('my-applied-jobs', [FacilityController::class, 'myjobApplied']);
Route::post('my-offered-jobs', [FacilityController::class, 'myjobOffered']);
Route::post('my-hired-jobs', [FacilityController::class, 'myjobHired']);
Route::post('my-past-jobs', [FacilityController::class, 'myjobPast']);
Route::post('nurse-personal-detail', [FacilityController::class, 'nursepersonalDetail']);
Route::delete('delete-nurse', [FacilityController::class, 'deleteNurse']);
Route::post('nurse-education-detail', [FacilityController::class, 'nurseEducationDetail']);
Route::post('add-experience-detail', [FacilityController::class, 'addnurseExperienceDetail']);
Route::post('edit-experience-detail', [FacilityController::class, 'editnurseExperienceDetail']);
Route::post('experience-type-list', [FacilityController::class, 'experienceTpesOptions']);
Route::post('get-employer-list', [FacilityController::class, 'getfacilities']);







/* Recruiter */ //Edited
// user recruiter's api
// Route::post('user-recruiter', 'RecruiterController@userRecruiter');
// Route::post('edit-user-recruiter', 'RecruiterController@editUserRecruiter');
// Route::post('user-profile-picture', 'RecruiterController@recruiterProfilePictureUpload');
// Route::post('recruiter-register', 'RecruiterController@registerRecruiter');
// Route::post('home-screen', 'RecruiterController@homeScreen');
// Route::post('account-info', 'RecruiterController@accountInfo');
// Route::post('update_account-info', 'RecruiterController@updateAccInfo');
// Route::post('get-recruiter-by-mobile', 'RecruiterController@accountInfoByMobile');
// Route::post('get-applications', 'RecruiterController@applications');
// Route::post('get-new-applications', 'RecruiterController@newApplications');
// Route::post('get-screening-applications', 'RecruiterController@screeningApplications');
// Route::post('get-submitted-applications', 'RecruiterController@submittedApplications');
// Route::post('get-offered-applications', 'RecruiterController@offeredApplications');
// Route::post('get-draft-applications', 'RecruiterController@draftedApplications');
// Route::post('get-published-applications', 'RecruiterController@publishedApplications');
// Route::post('get-hidden-applications', 'RecruiterController@hiddenApplications');
// Route::post('get-closed-applications', 'RecruiterController@closedApplications');
// Route::post('draft-job', 'RecruiterController@draftJob');
// Route::post('get-onboarding-applications', 'RecruiterController@onboardingApplications');
// Route::post('get-working-applications', 'RecruiterController@workingApplications');
// Route::post('get-done-applications', 'RecruiterController@doneApplications');
// Route::post('get-rejected-applications', 'RecruiterController@rejectedApplications');
// Route::post('get-blocked-applications', 'RecruiterController@blockedApplications');
// Route::post('get-application-status', 'RecruiterController@applicationStatus');
// Route::post('get-worker-detail', 'RecruiterController@workerDetails');
// Route::post('update-status', 'RecruiterController@updateStatus');
// Route::post('recruiter-information', 'RecruiterController@recruiterInformation');
// Route::post('explore-screen', 'RecruiterController@exploreScreen');
// Route::post('update-recruiter-information', 'RecruiterController@updateRecruiterInformation');
// Route::post('get-facility-list', 'RecruiterController@getFacilityList');
// Route::post('get-shift', 'RecruiterController@getShift');
// Route::post('get-shift_time_of_day', 'RecruiterController@getShiftTimeOfDay');
// Route::post('get-recruiter-info', 'RecruiterController@recruiterInfo');
// Route::post('get-recruiter-applied-jobs', 'RecruiterController@recruiterAppliedJobs');
// Route::post('recruiter-information', 'RecruiterController@recruiterInformation');
// Route::post('update-recruiter-information', 'RecruiterController@updateRecruiterInformation');
// Route::post('get-application-info', 'RecruiterController@applicationInfo');
// Route::post('get-jobs-applied-worker', 'RecruiterController@jobAppliedWorkers');
// Route::post('get-unblock-worker', 'RecruiterController@unblockWorker');
// Route::post('get-hide-application', 'RecruiterController@hideStatusApplication');
// Route::post('get-close-application', 'RecruiterController@closeStatusApplication');
// Route::post('send-notification', 'RecruiterController@sendRecordNotification');
// Route::post('push-notification', 'RecruiterController@pushNotification');
// Route::post('get-job-keys', 'RecruiterController@getJobKeys');
// Route::post('remove-draft-job', 'RecruiterController@removeDraftJob');
// Route::post('send-offer-job', 'RecruiterController@sendOfferJob');
// Route::post('get-offer-job', 'RecruiterController@getOfferJob');
// Route::post('get-offer-joblist', 'RecruiterController@getOfferJoblist');
// Route::post('get-drafted-offer-job', 'RecruiterController@getDraftOfferJob');
// Route::post('get-worker-offer-job', 'RecruiterController@workerGetOfferJob');
// Route::post('rejected-counter-offer', 'RecruiterController@rejectedCounterOffer');
// Route::post('counter-offer-job', 'RecruiterController@counterOfferJob');
// Route::post('counter-offer-joblist', 'RecruiterController@getCounterOfferJoblist');
// Route::post('get-counter-offer', 'RecruiterController@getCounterOfferJob');
// Route::post('get-drafted-offered-joblist', 'RecruiterController@getDraftOfferedJoblist');
// Route::post('get-drafted-counteroffered-list', 'RecruiterController@getDraftCounterOfferedJoblist');


Route::post('user-recruiter', [RecruiterController::class, 'userRecruiter']);
Route::post('edit-user-recruiter', [RecruiterController::class, 'editUserRecruiter']);
Route::post('user-profile-picture', [RecruiterController::class, 'recruiterProfilePictureUpload']);
Route::post('recruiter-register', [RecruiterController::class, 'registerRecruiter']);
Route::post('home-screen', [RecruiterController::class, 'homeScreen']);
Route::post('account-info', [RecruiterController::class, 'accountInfo']);
Route::post('update_account-info', [RecruiterController::class, 'updateAccInfo']);
Route::post('get-recruiter-by-mobile', [RecruiterController::class, 'accountInfoByMobile']);
Route::post('get-applications', [RecruiterController::class, 'applications']);
Route::post('get-new-applications', [RecruiterController::class, 'newApplications']);
Route::post('get-screening-applications', [RecruiterController::class, 'screeningApplications']);
Route::post('get-submitted-applications', [RecruiterController::class, 'submittedApplications']);
Route::post('get-offered-applications', [RecruiterController::class, 'offeredApplications']);
Route::post('get-draft-applications', [RecruiterController::class, 'draftedApplications']);
Route::post('get-published-applications', [RecruiterController::class, 'publishedApplications']);
Route::post('get-hidden-applications', [RecruiterController::class, 'hiddenApplications']);
Route::post('get-closed-applications', [RecruiterController::class, 'closedApplications']);
Route::post('draft-job', [RecruiterController::class, 'draftJob']);
Route::post('get-onboarding-applications', [RecruiterController::class, 'onboardingApplications']);
Route::post('get-working-applications', [RecruiterController::class, 'workingApplications']);
Route::post('get-done-applications', [RecruiterController::class, 'doneApplications']);
Route::post('get-rejected-applications', [RecruiterController::class, 'rejectedApplications']);
Route::post('get-blocked-applications', [RecruiterController::class, 'blockedApplications']);
Route::post('get-application-status', [RecruiterController::class, 'applicationStatus']);
Route::post('get-worker-detail', [RecruiterController::class, 'workerDetails']);
Route::post('update-status', [RecruiterController::class, 'updateStatus']);
Route::post('recruiter-information', [RecruiterController::class, 'recruiterInformation']);
Route::post('explore-screen', [RecruiterController::class, 'exploreScreen']);
Route::post('update-recruiter-information', [RecruiterController::class, 'updateRecruiterInformation']);
Route::post('get-facility-list', [RecruiterController::class, 'getFacilityList']);
Route::post('get-shift', [RecruiterController::class, 'getShift']);
Route::post('get-shift_time_of_day', [RecruiterController::class, 'getShiftTimeOfDay']);
Route::post('get-recruiter-info', [RecruiterController::class, 'recruiterInfo']);
Route::post('get-recruiter-applied-jobs', [RecruiterController::class, 'recruiterAppliedJobs']);
Route::post('recruiter-information', [RecruiterController::class, 'recruiterInformation']);
Route::post('update-recruiter-information', [RecruiterController::class, 'updateRecruiterInformation']);
Route::post('get-application-info', [RecruiterController::class, 'applicationInfo']);
Route::post('get-jobs-applied-worker', [RecruiterController::class, 'jobAppliedWorkers']);
Route::post('get-unblock-worker', [RecruiterController::class, 'unblockWorker']);
Route::post('get-hide-application', [RecruiterController::class, 'hideStatusApplication']);
Route::post('get-close-application', [RecruiterController::class, 'closeStatusApplication']);
Route::post('send-notification', [RecruiterController::class, 'sendRecordNotification']);
Route::post('push-notification', [RecruiterController::class, 'pushNotification']);
Route::post('get-job-keys', [RecruiterController::class, 'getJobKeys']);
Route::post('remove-draft-job', [RecruiterController::class, 'removeDraftJob']);
Route::post('send-offer-job', [RecruiterController::class, 'sendOfferJob']);
Route::post('get-offer-job', [RecruiterController::class, 'getOfferJob']);
Route::post('get-offer-joblist', [RecruiterController::class, 'getOfferJoblist']);
Route::post('get-drafted-offer-job', [RecruiterController::class, 'getDraftOfferJob']);
Route::post('get-worker-offer-job', [RecruiterController::class, 'workerGetOfferJob']);
Route::post('rejected-counter-offer', [RecruiterController::class, 'rejectedCounterOffer']);
Route::post('counter-offer-job', [RecruiterController::class, 'counterOfferJob']);
Route::post('counter-offer-joblist', [RecruiterController::class, 'getCounterOfferJoblist']);
Route::post('get-counter-offer', [RecruiterController::class, 'getCounterOfferJob']);
Route::post('get-drafted-offered-joblist', [RecruiterController::class, 'getDraftOfferedJoblist']);
Route::post('get-drafted-counteroffered-list', [RecruiterController::class, 'getDraftCounterOfferedJoblist']);



/* Employer as Facility */
// Route::post('employer-send-otp', 'ApiEmployerController@sendOtp');
// Route::post('employer-mobile-otp', 'ApiEmployerController@mobileOtp');
// Route::post('employer-confirm-otp', 'ApiEmployerController@confirmOtp');
// Route::post('employer-login', 'ApiEmployerController@login');
// Route::post('employer-applications', 'ApiEmployerController@applications');
// Route::post('employer-about', 'ApiEmployerController@aboutEmployer');
// Route::post('employer-register', 'ApiEmployerController@registerEmployer');
// Route::post('employer-home-screen', 'ApiEmployerController@employerHomeScreen');
// Route::post('employer-account-info', 'ApiEmployerController@employerAccountInfo');
// Route::post('employer-status-count', 'ApiEmployerController@employerStatusCount');
// Route::post('employer-new', 'ApiEmployerController@employerNewList');
// Route::post('employer-screening', 'ApiEmployerController@employerScreeningList');
// Route::post('employer-submitted', 'ApiEmployerController@employerSubmittedList');
// Route::post('employer-offered', 'ApiEmployerController@employerOffredList');
// Route::post('employer-done', 'ApiEmployerController@employerDoneList');
// Route::post('employer-onbaording', 'ApiEmployerController@employerOnboardingList');
// Route::post('employer-working', 'ApiEmployerController@employerWorkingList');
// Route::post('employer-rejected', 'ApiEmployerController@employerRejectedList');
// Route::post('employer-blocked', 'ApiEmployerController@employerBlockedList');
// Route::post('employer-worker-info', 'ApiEmployerController@workerInfo');

Route::post('employer-send-otp', [ApiEmployerController::class, 'sendOtp']);
Route::post('employer-mobile-otp', [ApiEmployerController::class, 'mobileOtp']);
Route::post('employer-confirm-otp', [ApiEmployerController::class, 'confirmOtp']);
Route::post('employer-login', [ApiEmployerController::class, 'login']);
Route::post('employer-applications', [ApiEmployerController::class, 'applications']);
Route::post('employer-about', [ApiEmployerController::class, 'aboutEmployer']);
Route::post('employer-register', [ApiEmployerController::class, 'registerEmployer']);
Route::post('employer-home-screen', [ApiEmployerController::class, 'employerHomeScreen']);
Route::post('employer-account-info', [ApiEmployerController::class, 'employerAccountInfo']);
Route::post('employer-status-count', [ApiEmployerController::class, 'employerStatusCount']);
Route::post('employer-new', [ApiEmployerController::class, 'employerNewList']);
Route::post('employer-screening', [ApiEmployerController::class, 'employerScreeningList']);
Route::post('employer-submitted', [ApiEmployerController::class, 'employerSubmittedList']);
Route::post('employer-offered', [ApiEmployerController::class, 'employerOffredList']);
Route::post('employer-done', [ApiEmployerController::class, 'employerDoneList']);
Route::post('employer-onbaording', [ApiEmployerController::class, 'employerOnboardingList']);
Route::post('employer-working', [ApiEmployerController::class, 'employerWorkingList']);
Route::post('employer-rejected', [ApiEmployerController::class, 'employerRejectedList']);
Route::post('employer-blocked', [ApiEmployerController::class, 'employerBlockedList']);
Route::post('employer-worker-info', [ApiEmployerController::class, 'workerInfo']);


// Strip Payment gateway
// Route::post('create-account', 'ApiController@createAccount');
// Route::post('/send-money', 'ApiController@send_money')->name('send_money');
// Route::post('get-employers', 'ApiController@employers');

Route::post('create-account', [ApiController::class, 'createAccount']);
Route::post('/send-money', [ApiController::class, 'send_money'])->name('send_money');
Route::post('get-employers', [ApiController::class, 'employers']);


// Route::post('auth/register',[AuthController::class,'register'])->name('register-jwt');

// authorize_access to api by email and api_key : return jwt token

Route::post('auth/authorize',[AuthController::class,'authorize_access'])->name('authorize');

// test jwt auth token with scopes ex: employer

Route::middleware(['auth:api','scopes:all_Permession'])->get('/allPermession',[ApiController::class,'all_permession_test'])->name('allPermession');

// test jwt auth token with scopes ex: recruiter

Route::middleware(['auth:api','scopes:some_Permession'])->get('/somePermession',[ApiController::class,'some_permession_test'])->name('somePermession');

// test api key and rate limit 60 hits per minute // controllHeaders to secure response headers

Route::middleware(['auth:api','ThrottleMiddleware:60,1','controllHeaders','auth.apikey'])->get('/getData',[ApiController::class,'get_cities']);

// Dev : test mongodb db connection

// Route::get('/ping', function (Request  $request) {
//     $connection = DB::connection('mongodb'); $msg = 'MongoDB is accessible!'; try { $connection->command(['ping' => 1]); } catch (\Exception  $e) { $msg = 'MongoDB is not accessible. Error: ' . $e->getMessage(); } return ['msg' => $msg]; });


// Route::post('/session', 'ApiController@session')->name('session');
// Route::post('/make-payment', 'ApiController@make_payment')->name('make_payment');




