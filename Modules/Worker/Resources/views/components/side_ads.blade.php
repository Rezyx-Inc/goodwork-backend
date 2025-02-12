
<style>
    /* Ads Container */
    .ads-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: sticky;
        top: 10px;
        overflow-y: auto;
        z-index: 10;
    }

    .ad {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .ad:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Ad Image */
    .ad-image {
        width: 90%;
        height: auto;
        /* Adjust height automatically */
        max-height: 80vh;
        /* Limit maximum height */
        object-fit: contain;
        /* Ensure the image fits without cropping */
        display: block;
        margin: 0 auto;
        /* Center the image horizontally */
    }

    /* Ad Content */
    .content-ad-content {
        padding: 20px;
        text-align: center;
    }

    .content-ad-content * {
        font-size: 14px;
        color: #666666;
        line-height: 1.5;
    }

    .content-ad-content h2 {
        font-weight: 600;
        margin-bottom: 10px;
        color: #333333;
    }

    .content-ad-content p {
        color: #666666;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .content-ad-content ul {
        list-style-type: disc;
        padding-left: 20px;
        margin-bottom: 15px;
        text-align: left;
    }

    /* Call-to-Action Button */
    .cta-button {
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

    .cta-button:hover {
        background-color: #0056b3;
        color: #ffffff
    }
</style>




<div class="ads-container">

    @php
        $nbr = $nbr ?? 3;
        $ads = \App\Enums\AdsEnum::random(3);
    @endphp

    @foreach($ads as $randomAd)

        <a href="{{ $randomAd['link'] }}" target="_blank">
    
            <div class="ad">

                <img src="{{ asset($randomAd['image']) }}" alt="Ad Image" class="ad-image">

                @if($randomAd['content'])
                    <div class="content-ad-content">
                        {!! $randomAd['content'] !!}
                    </div>
                @endif

            </div>

        </a>

    @endforeach

</div>