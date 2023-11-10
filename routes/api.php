<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


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
// Get nurse information
Route::post('set-banking-details', 'ApiController@setBankingDetails');
Route::post('get-banking-details', 'ApiController@getBankingDetails');
Route::post('worker-profile-HomeScreen', 'ApiController@workerProfileHomeScreen');
Route::post('worker-home-screen', 'ApiController@workerHomeScreen');
Route::post('home-screen-graph', 'ApiController@graphHomeScreen');
Route::post('get-worker-info', 'ApiController@workerInfo');
Route::post('get-worker-basicinfo', 'ApiController@workerBasicInfo');
Route::post('get-worker-skills', 'ApiController@workerSkills');
Route::post('get-worker-vaccination', 'ApiController@workerVaccination');
Route::post('get-worker-referrence', 'ApiController@workerReferrence');
Route::post('get-worker-certificate', 'ApiController@workerCertificates');
Route::post('get-worker-urgency', 'ApiController@workerUrgency');

Route::post('get-worker-facilityinfo', 'ApiController@workerFacilityInfo');
Route::post('get-patient-ratio', 'ApiController@patientRatio');
Route::post('get-worker-dates', 'ApiController@interviewDate');
Route::post('get-worker-bonus', 'ApiController@workerBonus');
Route::post('get-worker-feelshour', 'ApiController@workerFeelsLikeHour');

Route::post('get-nurse-profile-by-mobile', 'ApiController@NurseProfileInfoBymobile');
Route::post('get-emedical-records', 'ApiController@getEMedicalRecordsOptions');
Route::post('update-profile-picture', 'ApiController@profilePictureUpload');
Route::post('update-role-interest', 'ApiController@updateRoleInterest');
Route::post('nurse-resume', 'ApiController@resume');
Route::post('terms-conditions', 'ApiController@termsAndConditions');
Route::post('privacy-policy', 'ApiController@privacyPolicy');
Route::post('about-app', 'ApiController@aboutAPP');
Route::post('change-password', 'ApiController@changePassword');
Route::post('forgot-password', 'ApiController@sendResetLinkEmail');
Route::post('view-job-detail', 'ApiController@viewJobOffered');
Route::post('facility-rating', 'ApiController@facilityRatings');
Route::post('new-phone-number', 'ApiController@newPhoneNumber');
Route::post('confirm-otp', 'ApiController@confirmOTP');
Route::post('get-countries', 'ApiController@getCountries');
Route::post('get-states', 'ApiController@getStates');
Route::post('get-cities', 'ApiController@getCities');
Route::post('worker-information', 'ApiController@workerInformation');
Route::post('skip-worker-information', 'ApiController@workerInformationSkip');
Route::post('update-worker-information', 'ApiController@updateWorkerInformation');
Route::post('jobs-information', 'ApiController@jobInformation');


// user recruiter's api
Route::post('user-recruiter', 'ApiController@userRecruiter');
Route::post('edit-user-recruiter', 'ApiController@editUserRecruiter');
Route::post('user-profile-picture', 'ApiController@recruiterProfilePictureUpload');

/* facility */
Route::post('facility-dropdown-{type}', 'ApiController@facilityDropdown');
Route::post('facility-profile', 'ApiController@facilityDetail');
Route::post('change-facility-logo', 'ApiController@changeFacilityLogo');
Route::post('browse-nurses', 'ApiController@browseNurses');
Route::post('get-seniority-level', 'ApiController@getSeniorityLevelOptions');
Route::post('job-offered-{type}', 'ApiController@offeredNurses');
Route::post('job-{type}', 'ApiController@createJob');
Route::post('get-job-function', 'ApiController@getJobFunctionOptions');
Route::post('apply', 'ApiController@apiJobApply');
Route::post('send-offer', 'ApiController@apiJobInvite');
Route::post('my-jobs-{type}', 'ApiController@facilityPostedJobs');
Route::post('offer-job-to-nurse-dropdown', 'ApiController@apiJobsList');
Route::post('job-info-short', 'ApiController@apiJobFacility');
Route::post('nurses-applied-jobs', 'ApiController@appliedNurses');
Route::post('nurse-rating', 'ApiController@nurseRating');
Route::post('remove-job-asset', 'ApiController@removeJobDocument');
Route::post('facility-settings', 'ApiController@settingsFacility');
Route::post('facility-notifications', 'ApiController@notificationFacility');

/* facility */
Route::post('get-user-images', 'ApiController@userImages');

Route::post('country/{name}/states', 'ProfileController@stateJson')->name('state-json');
Route::post('testing', 'ApiController@test');

Route::post('get-search-status', 'ApiController@getSearchStatusOptions')->name('search-status');
Route::post('get-license-types', 'ApiController@getLicenseTypeOptions')->name('license-types');
Route::post('get-license-status', 'ApiController@getLicenseStatusOptions')->name('license-status');
Route::post('nurse-license-detail', 'ApiController@nurseLicenseDetail');
Route::post('addUserActivity', 'ApiController@addUserActivity');

Route::post('get-worker-detail-new', 'ApiController@workerDetailsNew');
Route::post('explore', 'ApiController@explore');
Route::post('save-job', 'ApiController@saveJob');
Route::post('remove-saved-job', 'ApiController@removesavedJob');
Route::post('my-saved-jobs', 'ApiController@jobSaved');
Route::post('nurse-saved-jobs', 'ApiController@nurseJobSaved');

Route::post('my-applied-jobs', 'ApiController@myjobApplied');
Route::post('my-offered-jobs', 'ApiController@myjobOffered');
Route::post('my-hired-jobs', 'ApiController@myjobHired');
Route::post('my-past-jobs', 'ApiController@myjobPast');

Route::post('nurse-personal-detail', 'ApiController@nursepersonalDetail');
Route::delete('delete-nurse', 'ApiController@deleteNurse');
// Route::post('nurse-certification-detail', 'ApiController@nursecertificationDetail');
Route::post('nurse-education-detail', 'ApiController@nurseEducationDetail');
Route::post('add-experience-detail', 'ApiController@addnurseExperienceDetail');
Route::post('edit-experience-detail', 'ApiController@editnurseExperienceDetail');
Route::post('experience-type-list', 'ApiController@experienceTpesOptions');

/* Recruiter */
Route::post('recruiter-register', 'ApiController@registerRecruiter');
Route::post('home-screen', 'ApiController@homeScreen');
Route::post('account-info', 'ApiController@accountInfo');
Route::post('update_account-info', 'ApiController@updateAccInfo');
Route::post('get-recruiter-by-mobile', 'ApiController@accountInfoByMobile');
Route::post('get-employers', 'ApiController@employers');
Route::post('get-applications', 'ApiController@applications');
Route::post('get-new-applications', 'ApiController@newApplications');
Route::post('get-screening-applications', 'ApiController@screeningApplications');
Route::post('get-submitted-applications', 'ApiController@submittedApplications');
Route::post('get-offered-applications', 'ApiController@offeredApplications');
Route::post('get-draft-applications', 'ApiController@draftedApplications');
Route::post('get-published-applications', 'ApiController@publishedApplications');
Route::post('get-hidden-applications', 'ApiController@hiddenApplications');
Route::post('get-closed-applications', 'ApiController@closedApplications');
Route::post('draft-job', 'ApiController@draftJob');
Route::post('get-onboarding-applications', 'ApiController@onboardingApplications');
Route::post('get-working-applications', 'ApiController@workingApplications');
Route::post('get-done-applications', 'ApiController@doneApplications');
Route::post('get-rejected-applications', 'ApiController@rejectedApplications');
Route::post('get-blocked-applications', 'ApiController@blockedApplications');
Route::post('get-application-status', 'ApiController@applicationStatus');
Route::post('get-worker-detail', 'ApiController@workerDetails');
Route::post('update-status', 'ApiController@updateStatus');
Route::post('recruiter-information', 'ApiController@recruiterInformation');
Route::post('get-employer-list', 'ApiController@getfacilities');
Route::post('explore-screen', 'ApiController@exploreScreen');
Route::post('update-recruiter-information', 'ApiController@updateRecruiterInformation');
Route::post('get-facility-list', 'ApiController@getFacilityList');
Route::post('get-shift', 'ApiController@getShift');
Route::post('get-shift_time_of_day', 'ApiController@getShiftTimeOfDay');
Route::post('get-recruiter-info', 'ApiController@recruiterInfo');
Route::post('get-recruiter-applied-jobs', 'ApiController@recruiterAppliedJobs');
Route::post('recruiter-information', 'ApiController@recruiterInformation');
Route::post('update-recruiter-information', 'ApiController@updateRecruiterInformation');
Route::post('get-application-info', 'ApiController@applicationInfo');
Route::post('get-jobs-applied-worker', 'ApiController@jobAppliedWorkers');
Route::post('get-unblock-worker', 'ApiController@unblockWorker');
Route::post('get-hide-application', 'ApiController@hideStatusApplication');
Route::post('get-close-application', 'ApiController@closeStatusApplication');
Route::post('send-notification', 'ApiController@sendRecordNotification');
Route::post('push-notification', 'ApiController@pushNotification');
Route::post('get-job-keys', 'ApiController@getJobKeys');
Route::post('remove-draft-job', 'ApiController@removeDraftJob');
Route::post('send-offer-job', 'ApiController@sendOfferJob');
Route::post('get-offer-job', 'ApiController@getOfferJob');
Route::post('get-offer-joblist', 'ApiController@getOfferJoblist');
Route::post('get-drafted-offer-job', 'ApiController@getDraftOfferJob');
Route::post('get-worker-offer-job', 'ApiController@workerGetOfferJob');
Route::post('rejected-counter-offer', 'ApiController@rejectedCounterOffer');
Route::post('counter-offer-job', 'ApiController@counterOfferJob');
Route::post('counter-offer-joblist', 'ApiController@getCounterOfferJoblist');
Route::post('get-counter-offer', 'ApiController@getCounterOfferJob');
Route::post('get-draft-counter-offer', 'ApiController@getDraftCounterOfferJob');
Route::post('get-drafted-offered-joblist', 'ApiController@getDraftOfferedJoblist');
Route::post('get-drafted-counteroffered-list', 'ApiController@getDraftCounterOfferedJoblist');

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
// Route::post('/session', 'ApiController@session')->name('session');
// Route::post('/make-payment', 'ApiController@make_payment')->name('make_payment');




