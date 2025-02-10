<?php

namespace App\Enums;

class AdsEnum
{
    // boardwalk ads
    // https://app.boardwalk.marketing/offers

    const AD_1 = [
        'link' => 'https://www.bhmediatrack.com/25S2ZK4/2LWX2H7/?sub1=1&sub2=2&sub3=3',
        'image' => 'images/ads/myPerfectResume.png',
        'content' => '<h2>Get the help you need to land your next gig with a <b>custom resume!</b></h2>
                            <p>
                                Our Industry-leading resume tools take your job search from basic to next level.
                            </p>',
    ];

    const AD_2 = [
        'link' => 'https://www.bhmediatrack.com/25S2ZK4/6Z7MWN9/?sub1=1&sub2=2&sub3=3',
        'image' => 'images/ads/chime.png',
        'content' => '<h2>
                            The best way to get up to $500 before payday*
                        </h2>
                        <ul>
                            <li>No interest*</li>
                            <li>No credit check</li>
                            <li>No mandatory fees</li>
                        </ul>',
    ];

    const AD_3 = [
        'link' => 'https://www.bhgtrack.com/25S2ZK4/3DFLFWP/',
        'image' => 'images/ads/self.png',
        'content' => '<h3>Raise your credit score like your landlord raises rent.</h3>
                        <b>Get credit for paying rent — plus your cell phone and utilities! — 100% FREE.</b> 
                        <p>No credit card or credit check required.</p>',
    ];

    const AD_4 = [
        'link' => 'https://www.bhmediatrack.com/25S2ZK4/6QTNH6P/',
        'image' => 'images/ads/next_day.jpg',
        'content' => '<h3>Request a loan in under 2 minutes!</h3>
                        <p>A fast and easy way to find a loan. <br>From $100 to $40,000</p>',
    ];

    const AD_5 = [
        'link' => 'https://www.bhgtrack.com/25S2ZK4/6LP29CP/',
        'image' => 'images/ads/unemployment_benefits_guide.png',
        'content' => '<h3>The Ultimate Unemployment Benefits Guide</h3>
                        <p>✓ Find out how to leverage unemployment<br> ✓ Jobs, Money Assistance and more </br></p>',
    ];

    // end :: boardwalk ads


    // from skype
    const AD_6 = [
        'link' => 'https://www.fithortrip.com/BHKX349Z/B47749C/',
        'image' => 'images/ads/fithortrip.jpeg',
        'content' => null,
    ];

    
    // end :: from skype


    /**
     * Get all ads as an array.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::AD_1,
            self::AD_2,
            self::AD_3,
            self::AD_4,
            self::AD_5,
            self::AD_6,
            
        ];
    }

    /**
     * Get a random ad(s), defaulting to 1 if no number is provided.
     *
     * @param int $numAds
     * @return array
     */
    public static function random($numAds = 1)
    {
        $ads = self::all();
        $numAds = min($numAds, count($ads));  // Ensure we don't ask for more ads than available
    
        if ($numAds === 1) {
            return [$ads[array_rand($ads)]];
        }
    
        shuffle($ads);
        return array_slice($ads, 0, $numAds);
    }
}
