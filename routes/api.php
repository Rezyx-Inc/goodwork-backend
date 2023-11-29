<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\Worker\WorkerController;
use App\Http\Controllers\Api\Recruiter\RecruiterController;
use App\Http\Controllers\Api\Employer\ApiEmployerController;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// no ProfileController controller created
//Route::post('country/{name}/states', 'ProfileController@stateJson')->name('state-json');

Route::post('send-otp', 'ApiController@sendOtp');
Route::post('mobile-otp', 'ApiController@mobileOtp');
Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');
Route::post('worker-account-info', 'ApiController@workerAccountInfo');
Route::post('get-specialities', 'ApiController@getSpecialities');
Route::post('get-profession-specialities', 'ApiController@getSpecialitiesByProfession');
Route::post('get-job-types', 'ApiController@jobTypes');
Route::post('help-support', 'ApiController@helpSupport');
Route::post('get-help-comment', 'ApiController@getHelpComment');
Route::post('get-help-replycomment', 'ApiController@getHelpReplyComment');
Route::post('get-comment', 'ApiController@getCommentByAdmin');
Route::post('get-subject-types', 'ApiController@subjectTypes');
Route::post('get-work-location', 'ApiController@getGeographicPreferences');
Route::post('get-settings', 'ApiController@getSettingType');
Route::post('get-vms', 'ApiController@getVMSType');
Route::post('get-type', 'ApiController@getType');
Route::post('get-msp', 'ApiController@getMSPType');
Route::post('get-contract-terminology-policy', 'ApiController@getContractTerminologyPolicy');
Route::post('get-state-license', 'ApiController@getStateLicense');
Route::post('get-charting-system', 'ApiController@getChartingSystem');
Route::post('get-profession-list', 'ApiController@getProfessionList');
Route::post('get-skills-list', 'ApiController@getSkillList');
Route::post('get-vaccination-list', 'ApiController@getVaccinationList');
Route::post('get-EMR-list', 'ApiController@getEMRList');
Route::post('get-terms-list', 'ApiController@getTermsList');
Route::post('get-vision-list', 'ApiController@getVisionList');
Route::post('get-healthinsurance-list', 'ApiController@getHealthInsuranceList');
Route::post('get-dental-list', 'ApiController@getDentalList');
Route::post('get-401k-list', 'ApiController@getHowMuchKList');
Route::post('personal-detail', 'ApiController@personalDetail');
Route::post('availability', 'ApiController@availability');
Route::post('get-availability', 'ApiController@getAvailability');
Route::post('shift-duration', 'ApiController@shiftDuration');
Route::post('assignment-duration', 'ApiController@assignmentDurations');
Route::post('preferred-shifts', 'ApiController@preferredShifts');
Route::post('get-weekdays', 'ApiController@getWeekDay');
Route::post('state-list', 'ApiController@stateList');
Route::post('experience', 'ApiController@Experience');
Route::post('get-experience', 'ApiController@workerExperience');
Route::post('facility-types', 'ApiController@facilityTypes');
Route::post('nurse-experience-selections', 'ApiController@nurseExperienceSelectionOptions');
Route::post('forgot-password', 'ApiController@sendResetLinkEmail');
Route::post('new-phone-number', 'ApiController@newPhoneNumber');
Route::post('get-countries', 'ApiController@getCountries');
Route::post('get-states', 'ApiController@getStates');
Route::post('get-cities', 'ApiController@getCities');
Route::post('terms-conditions', 'ApiController@termsAndConditions');
Route::post('privacy-policy', 'ApiController@privacyPolicy');
Route::post('about-app', 'ApiController@aboutAPP');
//worker Details new function dosn't exist in ApiController
// Route::post('get-worker-detail-new', 'ApiController@workerDetailsNew');
// Route::post('nurse-certification-detail', 'ApiController@nursecertificationDetail');

// Route::post('highest-nursing-degrees', 'ApiController@NursingDegrees');
Route::post('certification-type-list', 'ApiController@searchForCredentialsOptions');
Route::post('media-options', 'ApiController@getMediaOptions');
Route::post('get-cerner-medtech-epic-options', 'ApiController@getEHRProficiencyExpOptions');
Route::post('nursing-degrees-options', 'ApiController@getNursingDegreesOptions');

// New Apis
Route::post('add-certification', 'ApiController@addCredentials');
Route::post('edit-certification', 'ApiController@editCredentials');
Route::post('remove-credentials-image', 'ApiController@removeCredentialDoc');
Route::post('get-leadership-roles', 'ApiController@leadershipRoles');
Route::post('get-languages-list', 'ApiController@getLanguages');
Route::post('role-and-interest/page-1', 'ApiController@rolePage1');
Route::post('role-and-interest/page-2', 'ApiController@rolePage2');
Route::post('remove-role-interest-doc', 'ApiController@destroyRoleInterestDocument');
Route::post('browse-jobs', 'ApiController@jobList'); 
Route::post('explore-browse-jobs', 'ApiController@exploreJobList'); 
Route::post('view-job', 'ApiController@viewJob'); 
Route::post('job-applied', 'ApiController@jobApplied');
Route::post('job-like', 'ApiController@jobLikes'); 
Route::post('job-popular', 'ApiController@jobPopular'); 
Route::post('browse-facility', 'ApiController@browse_facilities');
Route::post('facility-follow', 'ApiController@facilityFollows');
Route::post('facility-like', 'ApiController@facilityLikes');
Route::post('job-offers', 'ApiController@jobOffered');
Route::post('job-accept', 'ApiController@jobAcceptPost');
Route::post('job-reject', 'ApiController@jobRejectPost'); 
Route::post('job-completed', 'ApiController@jobCompleted'); 
Route::post('get-notification', 'ApiController@notification');
Route::post('get-offer-notification', 'ApiController@offerNotification');
Route::post('remove-notification', 'ApiController@removeNotification');
Route::post('settings', 'ApiController@settings');
Route::post('get-nurse-profile', 'ApiController@NurseProfileInfo');


// Get nurse information // edited 
Route::post('set-banking-details', 'WorkerController@setBankingDetails');
Route::post('get-banking-details', 'WorkerController@getBankingDetails');
Route::post('worker-profile-HomeScreen', 'WorkerController@workerProfileHomeScreen');
Route::post('worker-home-screen', 'WorkerController@workerHomeScreen');
Route::post('home-screen-graph', 'WorkerController@graphHomeScreen');
Route::post('get-worker-info', 'WorkerController@workerInfo');
Route::post('get-worker-basicinfo', 'WorkerController@workerBasicInfo');
Route::post('get-worker-skills', 'WorkerController@workerSkills');
Route::post('get-worker-vaccination', 'WorkerController@workerVaccination');
Route::post('get-worker-referrence', 'WorkerController@workerReferrence');
Route::post('get-worker-certificate', 'WorkerController@workerCertificates');
Route::post('get-worker-urgency', 'WorkerController@workerUrgency');

Route::post('get-worker-facilityinfo', 'WorkerController@workerFacilityInfo');
Route::post('get-patient-ratio', 'WorkerController@patientRatio');
Route::post('get-worker-dates', 'WorkerController@interviewDate');
Route::post('get-worker-bonus', 'WorkerController@workerBonus');
Route::post('get-worker-feelshour', 'WorkerController@workerFeelsLikeHour');

Route::post('get-nurse-profile-by-mobile', 'WorkerController@NurseProfileInfoBymobile');
Route::post('get-emedical-records', 'WorkerController@getEMedicalRecordsOptions');
Route::post('update-profile-picture', 'WorkerController@profilePictureUpload');
Route::post('update-role-interest', 'WorkerController@updateRoleInterest');
Route::post('nurse-resume', 'WorkerController@resume');



Route::post('change-password', 'WorkerController@changePassword');

Route::post('view-job-detail', 'WorkerController@viewJobOffered');
Route::post('facility-rating', 'WorkerController@facilityRatings');

Route::post('confirm-otp', 'WorkerController@confirmOTP');

Route::post('worker-information', 'WorkerController@workerInformation');
Route::post('skip-worker-information', 'WorkerController@workerInformationSkip');
Route::post('update-worker-information', 'WorkerController@updateWorkerInformation');





/* facility */
Route::post('facility-dropdown-{type}', 'FacilityController@facilityDropdown');
Route::post('facility-profile', 'FacilityController@facilityDetail');
Route::post('change-facility-logo', 'FacilityController@changeFacilityLogo');
Route::post('browse-nurses', 'FacilityController@browseNurses');
Route::post('get-seniority-level', 'FacilityController@getSeniorityLevelOptions');
Route::post('job-offered-{type}', 'FacilityController@offeredNurses');
Route::post('job-{type}', 'FacilityController@createJob');
Route::post('get-job-function', 'FacilityController@getJobFunctionOptions');
Route::post('apply', 'FacilityController@apiJobApply');
Route::post('send-offer', 'FacilityController@apiJobInvite');
Route::post('my-jobs-{type}', 'FacilityController@facilityPostedJobs');
Route::post('offer-job-to-nurse-dropdown', 'FacilityController@apiJobsList');
Route::post('job-info-short', 'FacilityController@apiJobFacility');
Route::post('nurses-applied-jobs', 'FacilityController@appliedNurses');
Route::post('nurse-rating', 'FacilityController@nurseRating');
Route::post('remove-job-asset', 'FacilityController@removeJobDocument');
Route::post('facility-settings', 'FacilityController@settingsFacility');
Route::post('facility-notifications', 'FacilityController@notificationFacility');
Route::post('jobs-information', 'FacilityController@jobInformation');
Route::post('get-user-images', 'FacilityController@userImages');
Route::post('testing', 'FacilityController@test');
Route::post('get-search-status', 'FacilityController@getSearchStatusOptions')->name('search-status');
Route::post('get-license-types', 'FacilityController@getLicenseTypeOptions')->name('license-types');
Route::post('get-license-status', 'FacilityController@getLicenseStatusOptions')->name('license-status');
Route::post('nurse-license-detail', 'FacilityController@nurseLicenseDetail');
Route::post('addUserActivity', 'FacilityController@addUserActivity');
Route::post('explore', 'FacilityController@explore');
Route::post('save-job', 'FacilityController@saveJob');
Route::post('remove-saved-job', 'FacilityController@removesavedJob');
Route::post('my-saved-jobs', 'FacilityController@jobSaved');
Route::post('nurse-saved-jobs', 'FacilityController@nurseJobSaved');
Route::post('my-applied-jobs', 'FacilityController@myjobApplied');
Route::post('my-offered-jobs', 'FacilityController@myjobOffered');
Route::post('my-hired-jobs', 'FacilityController@myjobHired');
Route::post('my-past-jobs', 'FacilityController@myjobPast');
Route::post('nurse-personal-detail', 'FacilityController@nursepersonalDetail');
Route::delete('delete-nurse', 'FacilityController@deleteNurse');
Route::post('nurse-education-detail', 'FacilityController@nurseEducationDetail');
Route::post('add-experience-detail', 'FacilityController@addnurseExperienceDetail');
Route::post('edit-experience-detail', 'FacilityController@editnurseExperienceDetail');
Route::post('experience-type-list', 'FacilityController@experienceTpesOptions');
Route::post('get-employer-list', 'FacilityController@getfacilities');

/* Recruiter */ //Edited 
// user recruiter's api
Route::post('user-recruiter', 'RecruiterController@userRecruiter');
Route::post('edit-user-recruiter', 'RecruiterController@editUserRecruiter');
Route::post('user-profile-picture', 'RecruiterController@recruiterProfilePictureUpload');
Route::post('recruiter-register', 'RecruiterController@registerRecruiter');
Route::post('home-screen', 'RecruiterController@homeScreen');
Route::post('account-info', 'RecruiterController@accountInfo');
Route::post('update_account-info', 'RecruiterController@updateAccInfo');
Route::post('get-recruiter-by-mobile', 'RecruiterController@accountInfoByMobile');

Route::post('get-applications', 'RecruiterController@applications');
Route::post('get-new-applications', 'RecruiterController@newApplications');
Route::post('get-screening-applications', 'RecruiterController@screeningApplications');
Route::post('get-submitted-applications', 'RecruiterController@submittedApplications');
Route::post('get-offered-applications', 'RecruiterController@offeredApplications');
Route::post('get-draft-applications', 'RecruiterController@draftedApplications');
Route::post('get-published-applications', 'RecruiterController@publishedApplications');
Route::post('get-hidden-applications', 'RecruiterController@hiddenApplications');
Route::post('get-closed-applications', 'RecruiterController@closedApplications');
Route::post('draft-job', 'RecruiterController@draftJob');
Route::post('get-onboarding-applications', 'RecruiterController@onboardingApplications');
Route::post('get-working-applications', 'RecruiterController@workingApplications');
Route::post('get-done-applications', 'RecruiterController@doneApplications');
Route::post('get-rejected-applications', 'RecruiterController@rejectedApplications');
Route::post('get-blocked-applications', 'RecruiterController@blockedApplications');
Route::post('get-application-status', 'RecruiterController@applicationStatus');
Route::post('get-worker-detail', 'RecruiterController@workerDetails');
Route::post('update-status', 'RecruiterController@updateStatus');
Route::post('recruiter-information', 'RecruiterController@recruiterInformation');

Route::post('explore-screen', 'RecruiterController@exploreScreen');
Route::post('update-recruiter-information', 'RecruiterController@updateRecruiterInformation');
Route::post('get-facility-list', 'RecruiterController@getFacilityList');
Route::post('get-shift', 'RecruiterController@getShift');
Route::post('get-shift_time_of_day', 'RecruiterController@getShiftTimeOfDay');
Route::post('get-recruiter-info', 'RecruiterController@recruiterInfo');
Route::post('get-recruiter-applied-jobs', 'RecruiterController@recruiterAppliedJobs');
Route::post('recruiter-information', 'RecruiterController@recruiterInformation');
Route::post('update-recruiter-information', 'RecruiterController@updateRecruiterInformation');
Route::post('get-application-info', 'RecruiterController@applicationInfo');
Route::post('get-jobs-applied-worker', 'RecruiterController@jobAppliedWorkers');
Route::post('get-unblock-worker', 'RecruiterController@unblockWorker');
Route::post('get-hide-application', 'RecruiterController@hideStatusApplication');
Route::post('get-close-application', 'RecruiterController@closeStatusApplication');
Route::post('send-notification', 'RecruiterController@sendRecordNotification');
Route::post('push-notification', 'RecruiterController@pushNotification');
Route::post('get-job-keys', 'RecruiterController@getJobKeys');
Route::post('remove-draft-job', 'RecruiterController@removeDraftJob');
Route::post('send-offer-job', 'RecruiterController@sendOfferJob');
Route::post('get-offer-job', 'RecruiterController@getOfferJob');
Route::post('get-offer-joblist', 'RecruiterController@getOfferJoblist');
Route::post('get-drafted-offer-job', 'RecruiterController@getDraftOfferJob');
Route::post('get-worker-offer-job', 'RecruiterController@workerGetOfferJob');
Route::post('rejected-counter-offer', 'RecruiterController@rejectedCounterOffer');
Route::post('counter-offer-job', 'RecruiterController@counterOfferJob');
Route::post('counter-offer-joblist', 'RecruiterController@getCounterOfferJoblist');
Route::post('get-counter-offer', 'RecruiterController@getCounterOfferJob');
// getDraftCounterOfferJob function in the controller doesn't exist 
// Route::post('get-draft-counter-offer', 'RecruiterController@getDraftCounterOfferJob');
Route::post('get-drafted-offered-joblist', 'RecruiterController@getDraftOfferedJoblist');
Route::post('get-drafted-counteroffered-list', 'RecruiterController@getDraftCounterOfferedJoblist');

/* Employer as Facility */
Route::post('employer-send-otp', 'ApiEmployerController@sendOtp');
Route::post('employer-mobile-otp', 'ApiEmployerController@mobileOtp');
Route::post('employer-confirm-otp', 'ApiEmployerController@confirmOtp');
Route::post('employer-login', 'ApiEmployerController@login');
Route::post('employer-applications', 'ApiEmployerController@applications');
Route::post('employer-about', 'ApiEmployerController@aboutEmployer');
Route::post('employer-register', 'ApiEmployerController@registerEmployer');
Route::post('employer-home-screen', 'ApiEmployerController@employerHomeScreen');
Route::post('employer-account-info', 'ApiEmployerController@employerAccountInfo');
Route::post('employer-status-count', 'ApiEmployerController@employerStatusCount');
Route::post('employer-new', 'ApiEmployerController@employerNewList');
Route::post('employer-screening', 'ApiEmployerController@employerScreeningList');
Route::post('employer-submitted', 'ApiEmployerController@employerSubmittedList');
Route::post('employer-offered', 'ApiEmployerController@employerOffredList');
Route::post('employer-done', 'ApiEmployerController@employerDoneList');
Route::post('employer-onbaording', 'ApiEmployerController@employerOnboardingList');
Route::post('employer-working', 'ApiEmployerController@employerWorkingList');
Route::post('employer-rejected', 'ApiEmployerController@employerRejectedList');
Route::post('employer-blocked', 'ApiEmployerController@employerBlockedList');
Route::post('employer-worker-info', 'ApiEmployerController@workerInfo');

// Strip Payment gateway
Route::post('create-account', 'ApiController@createAccount');
Route::post('/send-money', 'ApiController@send_money')->name('send_money');
Route::post('get-employers', 'ApiController@employers');
// Route::post('/session', 'ApiController@session')->name('session');
// Route::post('/make-payment', 'ApiController@make_payment')->name('make_payment');




