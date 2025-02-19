<?php

namespace App\Console\Commands;

use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class dailyCron extends Command
{
    protected $signature = 'daily:update';

    protected $description = 'Update Jobs daily hitting the start date';

    public function handle()
    {
        $counter = 0;
        // get current date using carbon
        $date = Carbon::parse(Carbon::now())->format('Y-m-d');

        $offers = Offer::where('start_date', $date)->whereIn('status' , ['Onboarding', 'Cleared'])->get();

        try {
            foreach ($offers as $offer) {
                
                $offer_id = $offer->id;
                $stripe_id = $offer->organization->stripeAccountId;
                
                $amount = $offer->total_goodwork_amount;
                $length = $offer->preferred_assignment_duration;

                if (!$stripe_id || !$amount) {
                    
                    // TODO :: send email to admin
                    continue;
                }

                $url = 'http://localhost:' . config('app.file_api_port') . '/payments/customer/subscription';
                
                // post  request to the url
                $response = Http::post($url, [
                    'stripeId' => $stripe_id,
                    'offerId' => $offer_id,
                    'amount' => $amount,
                    'length' => $length
                ]);

                // get response from the request
                $data = json_decode($response->body());
                
                // check if the request was successful
                if ($data->status) {
                    
                    // update the status to working
                    $offer->status = 'Working';
                    $offer->save();
                    
                    $counter++;
                } else {
                    // TODO :: send email to admin
                }   

            }
        } catch (\Exception $e) {
            $this->error('Error updating offers');
        }


        $this->info('Updated ' . $counter . ' offer');
    }
}
