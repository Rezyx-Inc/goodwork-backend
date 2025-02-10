<style>
    /* Popup */
    /* Popup container */
    #adPopup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      min-width: 300px;
      background-color: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      padding: 20px;
      border-radius: 8px;
      z-index: 1000;
      display: none; /* Initially hidden */
    }

    /* Overlay background */
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

    /* Close button */
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
    /* end Popup */




    /* ads style */
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
        border: 1px solid #ddd; /* Optional: Add a border to separate ads */
    }

    #adPopup .ad-image {
        max-width: 200px;
        height: auto;
        border-radius: 8px;
        margin-right: 20px; /* Space between image and text */
    }

    #adPopup .ad-content {
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

    /* end ads style */
</style>



    <!-- Overlay -->
    <div id="popupOverlay"></div>

    <!-- Popup -->
    <div id="adPopup">
        <button class="close-btn" id="closePopup">&times;</button>
        
        <div class="ads-container">
            
            {{-- <a href="https://www.fithortrip.com/BHKX349Z/B47749C/" class="ad" target="_blank">
                <img src="{{ asset('images/ads/fithortrip.jpeg') }}" alt="Ad Image" class="ad-image">
                
            </a> --}}



            
            <a href="https://www.bhmediatrack.com/25S2ZK4/2LWX2H7/?sub1=1&sub2=2&sub3=3" target="_blank">
            
                <div class="ad">
                    <img src="{{ asset('images/ads/myPerfectResume.png') }}" alt="Ad Image" class="ad-image">
                    <div class="ad-content">
                        <h2>Get the help you need to land your next gig with a <b>custom resume!</b></h2>
                        <p>
                            Our Industry-leading resume tools take your job search from basic to next level.
                        </p>
                    </div>
                </div>

            </a>

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