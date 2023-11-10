<?php

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

Route::prefix('admin')->group(function() {
    Route::get('list', 'TempController@listing');
    Route::get('form', 'TempController@form');
    Route::get('profile', 'TempController@profile');
    Route::middleware(['admin_not_logged_in'])->group(function () {
        Route::get('/', ['uses'=>'AuthController@get_login', 'as'=>'admin.login']);
        Route::get('admin-login', ['uses' => 'AuthController@get_login', 'as' => 'admin-login']);
        Route::post('admin-login', ['uses' => 'AuthController@post_login', 'as' => 'admin-login']);
        Route::post('admin-forgotpassword', ['uses' => 'AuthController@post_forgot_password', 'as' => 'admin-forgotpassword']);
        Route::get('admin-lockscreen', ['uses' => 'AuthController@get_lockscreen', 'as' => 'admin-lockscreen']);
        Route::post('admin-lockscreen', ['uses' => 'AuthController@post_lockscreen', 'as' => 'admin-lockscreen']);
    });

    Route::middleware(['admin_logged_in'])->group(function () {
        Route::get('admin-logout', ['uses' => 'AuthController@logout', 'as' => 'admin-logout']);

        Route::get('admin-dashboard', ['uses' => 'DashboardController@index', 'as' => 'admin-dashboard']);
        Route::get('run-sql', ['uses' => 'DashboardController@run_sql', 'as' => 'run-sql']);

        Route::get('admin-myprofile', ['uses' => 'MyprofileController@get_myprofile', 'as' => 'admin-myprofile']);
        Route::post('admin-myprofile', ['uses' => 'MyprofileController@post_myprofile', 'as' => 'admin-myprofile']);
        Route::get('admin-profile', ['uses' => 'MyprofileController@account_setting', 'as' => 'admin-profile']);
        Route::post('admin-profile', ['uses' => 'MyprofileController@update_profile', 'as' => 'admin-profile']);
        Route::post('admin-change-password', ['uses' => 'MyprofileController@change_password', 'as' => 'admin-change-password']);

        Route::get('admin-cms', ['uses' => 'CmsController@index', 'as' => 'admin-cms']);
        Route::get('admin-viewcms/{id}', ['uses' => 'CmsController@view', 'as' => 'admin-viewcms']);
        Route::get('admin-updatecms/{id}', ['uses' => 'CmsController@get_update', 'as' => 'admin-updatecms']);
        Route::post('admin-updatecms/{id}', ['uses' => 'CmsController@post_update', 'as' => 'admin-updatecms']);

        Route::get('admin-contact', ['uses' => 'ContactController@index', 'as' => 'admin-contact']);
        Route::get('admin-viewcontact/{id}', ['uses' => 'ContactController@view', 'as' => 'admin-viewcontact']);
        Route::post('send-reply/{id}', ['uses' => 'ContactController@send_reply', 'as' => 'send-reply']);

        Route::get('admin-emails', ['uses' => 'EmailController@index', 'as' => 'admin-emails']);
        Route::get('admin-viewemail/{id}', ['uses' => 'EmailController@view', 'as' => 'admin-viewemail']);
        Route::get('admin-updateemail/{id}', ['uses' => 'EmailController@get_update', 'as' => 'admin-updateemail']);
        Route::post('admin-updateemail/{id}', ['uses' => 'EmailController@post_update', 'as' => 'admin-updateemail']);

        Route::get('admin-faqs', ['uses' => 'FaqController@index', 'as' => 'admin-faqs']);
        Route::get('admin-createfaq', ['uses' => 'FaqController@get_create', 'as' => 'admin-createfaq']);
        Route::post('admin-createfaq', ['uses' => 'FaqController@post_create', 'as' => 'admin-createfaq']);
        Route::get('admin-viewfaq/{id}', ['uses' => 'FaqController@view', 'as' => 'admin-viewfaq']);
        Route::get('admin-updatefaq/{id}', ['uses' => 'FaqController@get_update', 'as' => 'admin-updatefaq']);
        Route::post('admin-updatefaq/{id}', ['uses' => 'FaqController@post_update', 'as' => 'admin-updatefaq']);
        Route::get('admin-deletefaq', ['uses' => 'FaqController@delete', 'as' => 'admin-deletefaq']);

        /** Nurses routes */
        Route::resource('workers', 'NurseController')->parameters(['workers' => 'id'])->except(['destroy']);
        Route::get('get-workers-dt',['uses'=>'NurseController@getData','as'=>'get-workers-dt']);
        Route::post('delete-worker',['uses'=>'NurseController@destroy','as'=>'delete-worker']);
        Route::post('invite-worker',['uses'=>'NurseController@invite','as'=>'invite-worker']);
        Route::post('get-states',['uses'=>'NurseController@get_state','as'=>'get-states']);
        Route::post('get-cities',['uses'=>'NurseController@get_city','as'=>'get-cities']);
        Route::post('worker-references/{id}',['uses'=>'NurseController@submit_worker_reference','as'=>'worker-references']);
        Route::post('worker-vaccination/{id}',['uses'=>'NurseController@vaccination_submit','as'=>'worker-vaccination']);
        Route::post('worker-certification/{id}',['uses'=>'NurseController@certification_submit','as'=>'worker-certification']);
        Route::post('worker-skills/{id}',['uses'=>'NurseController@skills_submit','as'=>'worker-skills']);

        /** jobs routes */
        Route::resource('jobs', 'JobController')->parameters(['jobs' => 'id'])->except(['destroy']);
        Route::post('delete-job',['uses'=>'JobController@destroy','as'=>'delete-job']);
        Route::post('get-speciality',['uses'=>'JobController@get_speciality','as'=>'get-speciality']);
        Route::post('delete-job-offer',['uses'=>'JobController@deleteOffers','as'=>'delete-job-offer']);
        Route::post('job-references/{id}',['uses'=>'JobController@submit_job_reference','as'=>'job-references']);


        /** Keywords routes */
        Route::resource('keywords', 'KeyWordController')->parameters(['keywords' => 'id'])->except(['destroy','show']);
        Route::post('delete-keyword',['uses'=>'KeyWordController@destroy','as'=>'delete-keyword']);
            /** professions routes */
            Route::get('professions',['uses'=>'KeyWordController@settings','as'=>'key.profession']);
            Route::get('add-profession',['uses'=>'KeyWordController@create_setting','as'=>'add-profession']);
            /** Specialities routes */
            Route::get('specialities',['uses'=>'KeyWordController@specialities','as'=>'key.speciality']);
            Route::get('add-speciality',['uses'=>'KeyWordController@add_speciality','as'=>'add-speciality']);
            Route::get('speciality/{id}/edit',['uses'=>'KeyWordController@edit_speciality','as'=>'edit-speciality']);
            /** msps routes */
            Route::get('settings.dt',['uses'=>'KeyWordController@settings','as'=>'settings.dt']);
            Route::get('msps',['uses'=>'KeyWordController@settings','as'=>'key.msp']);
            Route::get('add-msp',['uses'=>'KeyWordController@create_setting','as'=>'add-msp']);
            /** vms routes */
            Route::get('vms',['uses'=>'KeyWordController@settings','as'=>'key.vms']);
            Route::get('add-vms',['uses'=>'KeyWordController@create_setting','as'=>'add-vms']);
            /** Shifts routes */
            Route::get('shifts',['uses'=>'KeyWordController@settings','as'=>'key.shift']);
            Route::get('add-shift',['uses'=>'KeyWordController@create_setting','as'=>'add-shift']);
            /** clinical settings routes */
            Route::get('clinical-setting',['uses'=>'KeyWordController@settings','as'=>'key.clinical']);
            Route::get('add-clinical',['uses'=>'KeyWordController@create_setting','as'=>'add-clinical']);

        Route::post('store-setting',['uses'=>'KeyWordController@store_setting','as'=>'store-setting']);
        Route::get('setting/{id}/edit',['uses'=>'KeyWordController@edit_setting','as'=>'edit-setting']);
        Route::post('update-setting/{id}',['uses'=>'KeyWordController@update_setting','as'=>'update-setting']);

        /** States routes */
        Route::resource('states', 'StateController')->parameters(['states' => 'id'])->except(['destroy','show']);
        Route::post('delete-state',['uses'=>'StateController@destroy','as'=>'delete-state']);

        /** Email Template routes */
        Route::resource('email-templates', 'EmailTemplateController')->parameters(['email-templates' => 'id'])->except(['destroy','show']);
        Route::post('delete-email-template',['uses'=>'EmailTemplateController@destroy','as'=>'delete-email-template']);

        /** Recruiters routes */
        Route::resource('recruiters', 'RecruiterController')->parameters(['recruiters' => 'id'])->except(['destroy','show']);
        Route::post('delete-recruiter',['uses'=>'RecruiterController@destroy','as'=>'delete-recruiter']);

        /** roles routes */
        Route::resource('roles', 'RoleController')->parameters(['roles' => 'id'])->except(['destroy','show','edit','update']);
        Route::get('edit-role/{id}',['uses'=>'RoleController@edit','as'=>'roles.edit']);
        Route::post('update-role/{id}',['uses'=>'RoleController@update','as'=>'update-role']);
        Route::post('delete-role',['uses'=>'RoleController@destroy','as'=>'delete-role']);

        /** admins routes */
        Route::resource('admins', 'UserController')->parameters(['admins' => 'id'])->except(['destroy','show']);
        Route::post('delete-admin',['uses'=>'RecruiterController@destroy','as'=>'delete-admin']);

        /** Permissions routes */
        Route::resource('permissions', 'PermissionController')->parameters(['permissions' => 'id'])->except(['destroy','show']);
        Route::get('edit-permission/{id}',['uses'=>'PermissionController@edit','as'=>'permissions.edit']);
        Route::post('update-permission/{id}',['uses'=>'PermissionController@update','as'=>'permissions.update']);
        Route::post('delete-permission',['uses'=>'PermissionController@destroy','as'=>'delete-permission']);

        /** Support ticket routes */
        Route::resource('tickets', 'SupportTicketController')->parameters(['tickets' => 'id'])->except(['destroy','show']);
        Route::post('delete-ticket',['uses'=>'SupportTicketController@destroy','as'=>'delete-ticket']);

        Route::get('admin-creator',['uses'=>'UserController@creator_index','as'=>'admin-creator']);
        Route::get('admin-addcustomer',['uses'=>'UserController@customer_add','as'=>'admin-addcustomer']);
        Route::post('admin-addcustomer',['uses'=>'UserController@customer_post_add','as'=>'admin-addcustomer']);
        Route::get('admin-customer-list-datatable',['uses'=>'UserController@get_customer_data','as'=>'admin-customer-list-datatable']);
        Route::get('admin-updatecreator/{id}',['uses'=>'UserController@creator_update','as'=>'admin-updatecreator']);
        Route::post('admin-updatecreator/{id}',['uses'=>'UserController@creator_post_update','as'=>'admin-updatecreator']);
        Route::get('admin-deletecretor',['uses'=>'UserController@cretor_delete','as'=>'admin-deletecretor']);

        Route::get('admin-member',['uses'=>'UserController@member_index','as'=>'admin-member']);
        Route::get('admin-booster-list-datatable',['uses'=>'UserController@get_booster_data','as'=>'admin-booster-list-datatable']);
        Route::get('admin-addbooster',['uses'=>'UserController@booster_add','as'=>'admin-addbooster']);
        Route::post('admin-addbooster',['uses'=>'UserController@booster_post_add','as'=>'admin-addbooster']);
        Route::get('admin-updatemember/{id}',['uses'=>'UserController@member_update','as'=>'admin-updatemember']);
        Route::post('admin-updatemember/{id}',['uses'=>'UserController@member_post_update','as'=>'admin-updatemember']);
        Route::get('admin-deletemember',['uses'=>'UserController@member_delete','as'=>'admin-deletemember']);

	Route::get('admin-category',['uses'=>'CategoryController@index','as'=>'admin-category']);
        Route::get('admin-addcategory',['uses'=>'CategoryController@add','as'=>'admin-addcategory']);
        Route::post('admin-addcategory',['uses'=>'CategoryController@post_category','as'=>'admin-addcategory']);
        Route::get('admin-category-list-datatable',['uses'=>'CategoryController@category_list','as'=>"admin-category-list-datatable"]);
        Route::get('admin-deletecategory',['uses'=>'CategoryController@delete','as'=>'admin-deletecategory']);
        Route::get('admin-updatecategory/{id}',['uses'=>'CategoryController@edit','as'=>'admin-updatecategory']);
        Route::post('admin-updatecategory/{id}',['uses'=>'CategoryController@post_update','as'=>'admin-updatecategory']);


    });

    Route::middleware(['moderator_logged_in'])->group(function () {
        /*         * ****************ModeratorController**************** */

        Route::get('admin-moderator',['uses'=>'ModeratorController@index','as'=>'admin-moderator']);
        Route::get('admin-moderator-list-datatable',['uses'=>'ModeratorController@moderator_list','as'=>"admin-moderator-list-datatable"]);
        Route::get('admin-addmoderator',['uses'=>'ModeratorController@add','as'=>'admin-addmoderator']);
        Route::post('admin-addmoderator',['uses'=>'ModeratorController@post_add','as'=>'admin-addmoderator']);
        Route::get('admin-updatemoderator/{id}',['uses'=>'ModeratorController@edit','as'=>"admin-updatemoderator"]);
        Route::post('admin-updatemoderator/{id}',['uses'=>'ModeratorController@post_update','as'=>'admin-updatemoderator']);
        Route::get('admin-deletemoderator',['uses'=>'ModeratorController@delete','as'=>"admin-deletemoderator"]);
    });
});
