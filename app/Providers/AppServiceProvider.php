<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Keyword;
use Illuminate\Support\Facades\Blade;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Faker\Factory as FakerFactory;
use App\Models\NotificationMessage;
use App\Models\NotificationJobModel;
use App\Models\NotificationOfferModel;
use Illuminate\Support\Facades\View;


use Faker\Generator as FakerGenerator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('bugsnag.logger', Log::class);
        $this->app->alias('bugsnag.logger', LoggerInterface::class);

        // register fantasyNameProvider
        $this->app->singleton(FakerGenerator::class, function ($app) {
            $faker = FakerFactory::create();
            $faker->addProvider(new \App\Providers\FantasyNameProvider($faker));
            return $faker;
        });
    }



    public static function keywordTitle($id = null)
    {
        $ret = '';
        if ($id) {
            $tmp = Keyword::where('id', $id)->first();
            if ($tmp) {
                $ret = $tmp->title;
            }
        }
        return $ret;
    }

    public static function fieldAttr($valid, $misc = [])
    {
        $klass = '';
        if (!(isset($misc['class']) && preg_match('/form-check-input/', $misc['class']))) {
            $klass = 'form-control ';
        }
        if ($valid) {
            $klass .= 'is-invalid';
        }

        foreach ($misc as $key => $value) {
            if ($key === 'class') {
                unset($misc['class']);
                $klass .= ' ' . $value;
            }
        }

        $ret = ['class' => $klass];
        $ret = array_merge($ret, $misc);
        return $ret;
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('search', function ($attributes, string $searchTerms) {
            $this->where(function (Builder $query) use ($attributes, $searchTerms) {
                $attributes = is_array($attributes) ? $attributes : [$attributes];
                foreach ($attributes as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerms) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerms) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerms}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerms) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerms}%");
                        }
                    );
                }
            });
            return $this;
        });
        Blade::if('checkrole', function ($roles) {
            return Helper::checkRole($roles);
        });
        Blade::if('isdev', function () {
            return Helper::isDev();
        });
        view()->composer(['inc.head', 'inc.admin.head'], function ($view) {
            $favicon = Storage::get('assets/logo/goodworklogo.png');
            $view->with('favicon', $favicon);
        });
        view()->composer(['inc.admin.header', 'inc.header'], function ($view) {
            $logo = Storage::get('assets/logo/goodworklogo.png');
            $profilePlaceholder = Storage::get('assets/nurses/' . $this->defaultId());
            $specialty = '';
            $user = Auth::user();
            if (Auth::user() && $user->hasRole('Facility', 'FacilityAdmin')) {
                if ($user->image) {
                    $t = Storage::exists('assets/facilities/facility_logo/' . $user->image);
                    if ($t) {
                        $profilePlaceholder = Storage::get('assets/facilities/facility_logo/' . $user->image);
                    }
                }
            }
            if (Auth::user() && $user->hasRole('Nurse') && $user->nurse) {
                if ($user->nurse->specialty != '') {
                    $specialty = $user->nurse->specialty;
                }
                if ($user->image) {
                    $t = Storage::exists('assets/nurses/profile/' . $user->image);
                    if ($t) {
                        $profilePlaceholder = Storage::get('assets/nurses/profile/' . $user->image);
                    }
                }
            }
            $view->with(
                compact(['logo', 'profilePlaceholder', 'specialty'])
            );
        });
        view()->composer([
            'layouts.welcome', 'auth.login', 'auth.register', 'auth.passwords.reset',
            'auth.passwords.email', 'auth.passwords.choose'
        ], function ($view) {
            $logolr = Storage::get('assets/logo/goodworklogo.png');
            $back_big = "background-image: url('/splash-image/big-bg-1');";
            $back_sm = "background-image: url('/splash-image/small-bg-2');";
            $controller = new Controller();
            $specialties = $controller->getSpecialities()->pluck('title', 'id');
            $workLocations = $controller->getGeographicPreferences()->pluck('title', 'id');
            $states = $controller->getStateOptions();
            $licenseType = $controller->getLicenseType()->pluck('title', 'id');
            $view->with(
                compact(['logolr', 'back_big', 'back_sm', 'specialties', 'workLocations', 'states', 'licenseType'])
            );
        });

        view()->composer(['emails.header', 'emails.footer', 'emails.registration'], function ($view) {

            $email_logo_image = Storage::get('assets/email/NurseifyEmailLogo');
            $email_header_image = Storage::get('assets/email/NurseifyEmailHeader1-9900000000079e3c');
            $email_middle_image = Storage::get('assets/email/middle-990000079e028a3c');
            $email_bottom_image = Storage::get('assets/email/bottom-9900000000079e3c');

            $view->with(
                compact(['email_logo_image', 'email_header_image', 'email_middle_image', 'email_bottom_image'])
            );
        });
        view()->composer(['nurses.filter', 'jobs.filter', 'facilities.filter'], function ($view) {
            $controller = new Controller();
            $specialities = $controller->getSpecialities()->pluck('title', 'id');
            $certifications = $controller->getCertifications()->pluck('title', 'id');
            $weekDays = $controller->getWeekDayOptions();
            $usaIsoStates = $controller->getUsaStates()->pluck('name', 'id');
            $searchStatus = $controller->getSearchStatus()->pluck('title', 'id');
            $licenseType = $controller->getLicenseType()->pluck('title', 'id');

            $view->with(
                compact(['specialities', 'certifications', 'weekDays', 'usaIsoStates', 'searchStatus', 'licenseType'])
            );
        });
        view()->composer(['facilities.filter'], function ($view) {
            $controller = new Controller();
            $types = $controller->getFacilityType()->pluck('title', 'id');
            $electronic_medical_records = $controller->getEMedicalRecords()->pluck('title', 'id');
            $view->with(
                compact(['types', 'electronic_medical_records'])
            );
        });

        view()->composer(['inc.notifications'], function ($view) {
            $controller = new Controller();
            $notifications = $controller->getNotifications()->take(10)->get();
            $view->with(
                compact(['notifications'])
            );
        });

        Schema::defaultStringLength(255);




        
       
View::composer(['worker::partials.worker_header', 'worker::worker.messages'], function ($view) {
    if (Auth::guard('frontend')->check()) {
        $user_id = Auth::guard('frontend')->user()->id;
        $nurse_id = Auth::guard('frontend')->user()->nurse->id;

        // Adjusted MongoDB aggregation pipeline for messages
        $pipeline = [
            [
                '$match' => [
                    'receiver' => $user_id,
                ],
            ],
            [
                '$unwind' => '$all_messages_notifs',
            ],
            [
                '$unwind' => '$all_messages_notifs.notifs_of_one_sender',
            ],
            [
                '$match' => [
                    'all_messages_notifs.notifs_of_one_sender.seen' => false,
                ],
            ],
            [
                '$group' => [
                    '_id' => '$all_messages_notifs.sender',
                    'full_name' => ['$first' => '$all_messages_notifs.full_name'], // Assuming full_name is the same for all messages from the same sender
                    'numOfMessages' => ['$sum' => 1],
                ],
            ],
            [
                '$addFields' => [
                    'numOfMessagesStr' => ['$toString' => '$numOfMessages'],
                ],
            ],
            [
                '$project' => [
                    'id' => '$_id',
                    'full_name' => 1, // Assuming full_name is the same for all messages from the same sender
                    'numOfMessagesStr' => 1,
                    'sender' => '$_id', // Assuming sender is represented by _id
                ],
            ],
        ];

        $notificationsDetails = NotificationMessage::raw()->aggregate($pipeline)->toArray();
        $unreadNotificationsCount = count($notificationsDetails);

        // Since the structure of notificationsDetails has changed, adjust how you construct notificationMessages
        $notificationMessages = array_map(function ($detail) {
            // Adjust according to the new structure. Example:
            return [
                'id' => $detail['id'],
                'numOfMessagesStr' => $detail['numOfMessagesStr'],
                'sender' => $detail['sender'],
                'full_name' => $detail['full_name']
            ];
        }, $notificationsDetails);

        // Adjusted MongoDB aggregation pipeline for jobs notifications
        $pipeline = [
            [
                '$match' => [
                    'receiver' => $user_id,
                ],
            ],
            [
                '$unwind' => '$all_jobs_notifs',
            ],
            [
                '$match' => [
                    'all_jobs_notifs.seen' => false,
                ],
            ],
            [
                '$group' => [
                    '_id' => '$all_jobs_notifs.sender',
                    'numOfJobs' => ['$sum' => 1],
                ],
            ],
            [
                '$addFields' => [
                    'numOfJobsStr' => ['$toString' => '$numOfJobs'],
                ],
            ],
            [
                '$project' => [
                    'id' => '$_id',
                    'numOfJobsStr' => 1,
                    'sender' => '$_id',
                ],
            ],
        ];  

        $jobsNotificationsDetails = NotificationJobModel::raw()->aggregate($pipeline)->toArray();
        $unreadJobsNotificationsCount = count($jobsNotificationsDetails);

        // Adjust how you construct jobsNotificationMessages

        $jobsNotificationMessages = array_map(function ($detail) {
            return [
                'id' => $detail['id'],
                'numOfJobsStr' => $detail['numOfJobsStr'],
                'sender' => $detail['sender'],
            ];
        }, $jobsNotificationsDetails);


        
    // Adjusted MongoDB aggregation pipeline for offers notifications


    $pipelineOffers = [
        [
            '$match' => [
                'receiver' => $nurse_id,
            ],
        ],
        [
            '$unwind' => '$all_offers_notifs',
        ],
        [
            '$unwind' => '$all_offers_notifs.notifs_of_one_sender',
        ],
        [
            '$match' => [
                'all_offers_notifs.notifs_of_one_sender.seen' => false,
            ],
        ],
        [
            '$project' => [
                'sender' => '$all_offers_notifs.sender',
                'offer_id' => '$all_offers_notifs.notifs_of_one_sender.offer_id',
                'jobId' => '$all_offers_notifs.notifs_of_one_sender.jobId',
                'seen' => '$all_offers_notifs.notifs_of_one_sender.seen',
                'job_name' => '$all_offers_notifs.notifs_of_one_sender.job_name',
                'full_name' => '$all_offers_notifs.notifs_of_one_sender.full_name',
                'createdAt' => '$all_offers_notifs.notifs_of_one_sender.createdAt',
                'type' => '$all_offers_notifs.notifs_of_one_sender.type',
            ],
        ],
    ];

        // $view->with(compact(['unreadNotificationsCount', 'notificationMessages', 'unreadJobsNotificationsCount', 'jobsNotificationMessages', 'user_id']));

        $offersNotificationMessages = NotificationOfferModel::raw()->aggregate($pipelineOffers)->toArray();
        $unreadOffersNotificationsCount = count($offersNotificationMessages);

        $view->with(compact(['unreadNotificationsCount', 'notificationMessages', 'unreadJobsNotificationsCount', 'jobsNotificationMessages', 'unreadOffersNotificationsCount', 'offersNotificationMessages', 'user_id']));
    }
});


View::composer(['recruiter::partials.header', 'recruiter::recruiter.messages'], function ($view) {
    if (Auth::guard('recruiter')->check()) {
        $user_id = Auth::guard('recruiter')->user()->id;

        // Adjusted MongoDB aggregation pipeline for recruiters
        $pipeline = [
            [
                '$match' => [
                    'receiver' => $user_id,
                ],
            ],
            [
                '$unwind' => '$all_messages_notifs',
            ],
            [
                '$unwind' => '$all_messages_notifs.notifs_of_one_sender',
            ],
            [
                '$match' => [
                    'all_messages_notifs.notifs_of_one_sender.seen' => false,
                ],
            ],
            [
                '$group' => [
                    '_id' => '$all_messages_notifs.sender',
                    'full_name' => ['$first' => '$all_messages_notifs.full_name'],
                    'numOfMessages' => ['$sum' => 1],
                ],
            ],
            [
                '$addFields' => [
                    'numOfMessagesStr' => ['$toString' => '$numOfMessages'],
                ],
            ],
            [
                '$project' => [
                    'id' => '$_id',
                    'full_name' => 1,
                    'numOfMessagesStr' => 1,
                    'sender' => '$_id',
                ],
            ],
        ];

        $notificationsDetails = NotificationMessage::raw()->aggregate($pipeline)->toArray();
        $unreadNotificationsCount = count($notificationsDetails);

        // Adjust how you construct notificationMessages for recruiters
        $notificationMessages = array_map(function ($detail) {
            return [
                'id' => $detail['id'],
                'numOfMessagesStr' => $detail['numOfMessagesStr'],
                'sender' => $detail['sender'],
                'full_name' => $detail['full_name'],
            ];
        }, $notificationsDetails);

        // Adjusted MongoDB aggregation pipeline for jobs notifications

        
        $pipelinejob = [
            [
                '$match' => [
                    'receiver' => $user_id,
                ],
            ],
            [
                '$unwind' => '$all_jobs_notifs',
            ],
            [
                '$unwind' => '$all_jobs_notifs.notifs_of_one_sender',
            ],
            [
                '$match' => [
                    'all_jobs_notifs.notifs_of_one_sender.seen' => false,
                ],
            ],
            [
                '$project' => [
                    'sender' => '$all_jobs_notifs.sender',
                    'jobId' => '$all_jobs_notifs.notifs_of_one_sender.jobId',
                    'seen' => '$all_jobs_notifs.notifs_of_one_sender.seen',
                    'job_name' => '$all_jobs_notifs.notifs_of_one_sender.job_name',
                    'full_name' => '$all_jobs_notifs.notifs_of_one_sender.full_name',
                ],
            ],
        ];

                $jobsNotificationMessages = NotificationJobModel::raw()->aggregate($pipelinejob)->toArray();
                $unreadJobsNotificationsCount = count($jobsNotificationMessages);

                    
    // Adjusted MongoDB aggregation pipeline for offers notifications


    $pipelineOffers = [
        [
            '$match' => [
                'receiver' => $user_id,
            ],
        ],
        [
            '$unwind' => '$all_offers_notifs',
        ],
        [
            '$unwind' => '$all_offers_notifs.notifs_of_one_sender',
        ],
        [
            '$match' => [
                'all_offers_notifs.notifs_of_one_sender.seen' => false,
            ],
        ],
        [
            '$project' => [
                'sender' => '$all_offers_notifs.sender',
                'offer_id' => '$all_offers_notifs.notifs_of_one_sender.offer_id',
                'jobId' => '$all_offers_notifs.notifs_of_one_sender.jobId',
                'seen' => '$all_offers_notifs.notifs_of_one_sender.seen',
                'job_name' => '$all_offers_notifs.notifs_of_one_sender.job_name',
                'full_name' => '$all_offers_notifs.notifs_of_one_sender.full_name',
                'createdAt' => '$all_offers_notifs.notifs_of_one_sender.createdAt',
                'type' => '$all_offers_notifs.notifs_of_one_sender.type',
            ],
        ],
    ];

    $offersNotificationMessages = NotificationOfferModel::raw()->aggregate($pipelineOffers)->toArray();
    $unreadOffersNotificationsCount = count($offersNotificationMessages);

    $view->with(compact(['unreadNotificationsCount', 'notificationMessages', 'unreadJobsNotificationsCount', 'jobsNotificationMessages', 'unreadOffersNotificationsCount', 'offersNotificationMessages', 'user_id']));


       
        // $view->with(compact(['unreadNotificationsCount', 'notificationMessages', 'unreadJobsNotificationsCount', 'jobsNotificationMessages', 'user_id']));

    }
});


    }

    private function defaultId()
    {
        return '8810d9fb-c8f4-458c-85ef-d3674e2c540a';
    }
}


