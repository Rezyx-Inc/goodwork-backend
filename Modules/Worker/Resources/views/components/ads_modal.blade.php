<style>
    /* Popup */
    #adPopup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 90%;
        min-width: 300px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-radius: 8px;
        z-index: 1000;
        display: none; /* Initially hidden */
    }

    #popupOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none; /* Initially hidden */
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #adPopup .ads-container {
        max-width: 800px;
        margin: 20px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #adPopup .ad {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    #adPopup .ad-image {
        max-width: 200px;
        height: auto;
        border-radius: 8px;
        margin-right: 20px;
        max-height: 70vh;
    }

    #adPopup .content-ad-content {
        flex: 1;
    }

    #adPopup .cta-button {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }

    #adPopup .cta-button:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        #adPopup {
            width: 90%;
            height: auto;
        }

        #adPopup .ad {
            flex-direction: column; /* Stack image and content vertically */
            align-items: flex-start;
        }

        #adPopup .ad-image {
            margin-right: 0;
            margin-bottom: 10px;
            max-width: 100%;
        }

        #adPopup .content-ad-content {
            text-align: center;
        }
    }
</style>

<div id="popupOverlay"></div>

<div id="adPopup">
    <button class="close-btn" id="closePopup">&times;</button>
    <div class="ads-container">
        @php
            $ads = \App\Enums\AdsEnum::random();
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
</div>

<script>
    // Show the popup after 3 seconds
    setTimeout(() => {
        document.getElementById('popupOverlay').style.display = 'block';
        document.getElementById('adPopup').style.display = 'block';
    }, 3000);

    // Close popup
    document.getElementById('closePopup').addEventListener('click', () => {
        document.getElementById('popupOverlay').style.display = 'none';
        document.getElementById('adPopup').style.display = 'none';
    });
</script>
