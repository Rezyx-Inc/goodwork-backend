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
            $profilePlaceholder = Storage::get('assets/workers/' . $this->defaultId());
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
            if (Auth::user() && $user->hasRole('Worker') && $user->worker) {
                if ($user->worker->specialty != '') {
                    $specialty = $user->worker->specialty;
                }
                if ($user->image) {
                    $t = Storage::exists('assets/workers/profile/' . $user->image);
                    if ($t) {
                        $profilePlaceholder = Storage::get('assets/workers/profile/' . $user->image);
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

            $email_logo_image = Storage::get('assets/email/GoodworkEmailLogo');
            $email_header_image = Storage::get('assets/email/GoodworkEmailHeader1-9900000000079e3c');
            $email_middle_image = Storage::get('assets/email/middle-990000079e028a3c');
            $email_bottom_image = Storage::get('assets/email/bottom-9900000000079e3c');

            $view->with(
                compact(['email_logo_image', 'email_header_image', 'email_middle_image', 'email_bottom_image'])
            );
        });
        view()->composer(['workers.filter', 'jobs.filter', 'facilities.filter'], function ($view) {
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





    }

    private function defaultId()
    {
        return '8810d9fb-c8f4-458c-85ef-d3674e2c540a';
    }
}
