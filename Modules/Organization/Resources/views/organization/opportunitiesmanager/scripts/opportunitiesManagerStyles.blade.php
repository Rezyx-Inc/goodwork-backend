
    
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
    
        .ss-job-prfle-sec:after {
            display: none !important;
        }
        .all {
            font-family: 'Neue Kabel';
            margin: 0;
            padding: 0;
            outline: none;
    
        }
    
        .bodyAll {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
            width: 100%;
            margin: 0 auto;
    
    
        }
    
        ::selection {
            color: #fff;
            background: #b5649e;
        }
    
        .container {
    
            margin-top: 7%;
            margin-bottom: 7%;
            /* background: #fff; */
            text-align: center;
            /* border-radius: 5px; */
            padding: 50px 35px 10px 35px;
            /* shadow */
            border: 2px solid #3D2C39 !important;
            box-shadow: 10px 10px 0px 0px #3D2C39;
            border-radius: 12px;
        }
    
        .container header {
            font-size: 35px;
            font-weight: 500;
            margin: 0 0 25px 0;
        }
    
        .container .form-outer {
            width: 100%;
            overflow: hidden;
        }
    
        .container .form-outer form {
            display: flex;
            width: 500%;
        }
    
        .form-outer form .page {
            width: 20%;
            transition: margin-left 0.3s ease-in-out;
        }
    
        .form-outer form .page .title {
            text-align: left;
            font-size: 25px;
            font-weight: 500;
        }
    
        .form-outer form .page .field {
    
            height: 45px;
            margin: 45px 0;
            display: flex;
            position: relative;
        }
    
        form .page .field .label {
            position: absolute;
            top: -30px;
            font-weight: 500;
            display: block;
            color: #000;
            font-size: 16px;
            font-weight: 500;
        }
    
        form .page .field input {
            height: 100%;
            width: 100%;
            /* border: 1px solid lightgrey; */
            /* border-radius: 5px;
            padding-left: 15px;
            font-size: 18px; */
    
    
        }
    
        /* Select field styling */
        .ss-account-form-lft-1 .ss-form-group select,
        .ss-account-form-lft-1 select,
        form .page .field select {
            border: 2px solid #3D2C39 !important;
            width: 90%;
            padding: 10px !important;
            box-shadow: 10px 10px 0px 0px #3D2C39;
            border-radius: 12px;
            background: #fff !important;
            font-size: 17px;
            font-weight: 500;
            margin-bottom: 2px;
        }
    
        .ss-account-form-lft-1 .ss-form-group input,
        .ss-account-form-lft-1 select,
        form .page .field select {
            border: 2px solid #3D2C39 !important;
            width: 90%;
            padding: 10px !important;
            box-shadow: 10px 10px 0px 0px #3D2C39;
            border-radius: 12px;
            background: #fff !important;
            font-size: 17px;
            font-weight: 500;
            margin-bottom: 2px;
        }
    
        .ss-form-group{
            display: inline !important;
        }
    
        .ss-prsnl-frm-specialty{
            width: 95%;
        }
    
        .ss-form-group div{
            margin-top: 4px;
        }
    
        .select2-container .select2-selection--single {
            height: 75% ;
            border: 2px solid #3D2C39 !important;
            border-radius: 12px !important;
            padding: 10px !important;
            box-shadow: 10px 10px 0px 0px #3D2C39 !important;
            background-color: #fff !important;
            width: 90% !important;
        }
    
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000;
            padding-left: 10px;
            font-size: 17px;
            font-weight: 500;
        }
        
    
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50%;
            right: 27px;
            transform: translateY(-50%);
        }
    
        .select2-dropdown {
            border: 2px solid #3D2C39 !important;
            box-shadow: 10px 10px 0px 0px #3D2C39 !important;
        }
    
        .select2-results__option {
            padding: 10px;
            font-size: 17px;
        }
    
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3D2C39 !important;
            color: white !important;
        }
    
        textarea {
            border: 2px solid #3D2C39 !important;
            width: 90%;
            padding: 10px !important;
            box-shadow: 10px 10px 0px 0px #3D2C39;
            border-radius: 12px;
            background: #fff !important;
        }
        form .page .field button {
            width: fit-content;
            height: calc(100% + 5px);
            border: none;
            /* background: #d33f8d; */
            margin-top: -20px;
            /* border-radius: 5px; */
            color: #fff;
            cursor: pointer;
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: 0.5s ease;
    
            background: #3D2C39;
            color: #fff;
            padding: 10px;
            border-radius: 100px;
    
        }
    
        form .page .field button:hover {
            background: #000;
        }
    
        form .page .btns button {
            margin-top: -20px !important;
        }
    
        form .page .btns button.prev {
            margin-right: 3px;
            font-size: 17px;
        }
    
        form .page .btns button.next {
            margin-left: 3px;
        }
    
        .container .progress-bar-item {
            display: flex;
            margin: 40px 0;
            user-select: none;
        }
    
        .container .progress-bar-item .step {
            text-align: center;
            position: relative;
            display: flex;
    
            flex-direction: column;
    
            align-items: center;
            justify-content: space-between;
        }
    
        .container .progress-bar-item .step p {
            font-weight: 500;
            font-size: 18px;
            color: #000;
            margin-bottom: 8px;
        }
    
        .progress-bar-item .step .bullet {
            height: 25px;
            width: 100px;
            border: 2px solid #000;
            display: inline-block;
            border-radius: 10%;
            position: relative;
            transition: 0.2s;
            font-weight: 500;
            font-size: 17px;
            line-height: 25px;
            background-color: #fff;
        }
        /* Progress bar container */
        .progress-bar-item {
            display: flex;
            justify-content: space-between;
            position: relative;
            padding: 0 15px;
            margin-bottom: 40px;
        }
    
        /* Style for the line connecting the steps */
        .progress-bar-item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            background-color: #e0e0e0; /* Line color when not completed */
            z-index: 0;
        }
        .progress-bar-item .step .bullet.active {
            border-color: #b5649e;
            background: #b5649e;
        }
    
        .progress-bar-item .step .bullet span {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
    
        .progress-bar-item .step .bullet.active span {
            /* display: none; */
        }
    
    
        .progress-bar-item .step .bullet.active:after {
            background: #b5649e;
            transform: scaleX(0);
            transform-origin: left;
            animation: animate 0.3s linear forwards;
        }
    
        @keyframes animate {
            100% {
                transform: scaleX(1);
            }
        }
    
        .progress-bar-item .step:last-child .bullet:before,
        .progress-bar-item .step:last-child .bullet:after {
            display: none;
        }
    
        .progress-bar-item .step p.active {
            color: #b5649e !important;
            transition: 0.2s linear;
        }
    
        .progress-bar-item .step .check {
            position: absolute;
            left: 50%;
            top: 70%;
            font-size: 15px;
            transform: translate(-50%, -50%);
            display: none;
        }
    
        .progress-bar-item .step .check.active {
            display: block;
            color: #fff;
        }
    
        .saveDrftBtn {
            border: 1px solid #3D2C39 !important;
            color: #3D2C39 !important;
            border-radius: 100px;
            padding: 10px;
            text-align: center;
            width: 100%;
            margin-top: 25px;
            background: transparent !important;
            margin-right: 6px;
    
        }
    
        label {
            display: block;
            color: #000;
            font-size: 16px;
            font-weight: 500;
        }
    
        .saveDrftBtnEdit {
            margin-right: 3px;
        }
    
        .saveDrftBtnDraft {
            margin-right: 3px;
        }
    
        #assign-container{
            display: flex;
            justify-content: center;
            align-items: baseline;
        }
    
        .ss-form-group {
            display: grid;
        }
    
        .ss-form-group span {
            margin-top: 10px;
        }
    
        
        .btn.first-collapse,
        .btn.first-collapse:hover,
        .btn.first-collapse:focus,
        .btn.first-collapse:active {
            /* background-color: rgb(255, 237, 238); */
            background-color: #fff8fd;
            color: rgb(65, 41, 57);
            font-size: 14px;
            font-family: 'Neue Kabel';
            font-style: normal;
            width: 90%;
        }
        
        
    
    </style>