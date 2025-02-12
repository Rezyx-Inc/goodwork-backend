<style>
    /* Ads Container */
    /* Horizontal Ads Container */
    .horizontal-ads-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin: 20px 0;
    }

    .horizontal-ads-container .ad {
        flex: 1 1 calc(33.33% - 20px); // Adjust the width to fit 3 ads per row
        max-width: 300px; // Set a maximum width for the ad
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .horizontal-ads-container .ad:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Ad Image */
    .horizontal-ads-container .ad-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid #e0e0e0;
    }

    /* Ad Content */
    .horizontal-ads-container .ad-content {
        padding: 15px;
        text-align: center;
    }

    .horizontal-ads-container .ad-content * {
        font-size: 14px;
        color: #666666;
        line-height: 1.5;
    }

    .horizontal-ads-container .ad-content h2 {
        font-weight: 600;
        margin-bottom: 10px;
        color: #333333;
    }

    .horizontal-ads-container .ad-content p {
        color: #666666;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .horizontal-ads-container .cta-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #ffffff;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }

    .horizontal-ads-container .cta-button:hover {
        background-color: #0056b3;
    }
</style>



<div class="horizontal-ads-container">

    @php
        $nbr = $nbr ?? 1;
        $ads = \App\Enums\AdsEnum::random($nbr);
    @endphp

    @foreach ($ads as $randomAd)
        <a href="{{ $randomAd['link'] }}" target="_blank">
            <div class="ad">
                <img src="{{ asset($randomAd['image']) }}" alt="Ad Image" class="ad-image">

                @if ($randomAd['content'])
                    <div class="ad-content">
                        {!! $randomAd['content'] !!}
                    </div>
                @endif
            </div>
        </a>
    @endforeach

</div>
