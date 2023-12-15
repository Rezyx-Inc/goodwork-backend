@extends('employer::layouts.main')

@section('content')
<main style="padding-top: 170px" class="ss-main-body-sec">
    <div class="container">
        <div class="row emp-container applicants-header text-center">
            <!-- <div class="col-4"> -->
            <div class="Proffesion_appears" style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec emp" onclick="applicationType('Apply')" id="Apply">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="37.8049" height="37.6" rx="13" fill="#006BC9" />
                        <path
                            d="M26.4634 18.5664H11.3415C11.1241 18.5664 10.9256 18.4724 10.7933 18.3032C10.7266 18.2213 10.6793 18.1256 10.6547 18.0231C10.6302 17.9206 10.6291 17.8139 10.6515 17.711L11.7195 12.635C12.0692 10.99 12.7781 9.47656 15.5851 9.47656H22.2293C25.0363 9.47656 25.7451 10.9994 26.0948 12.635L27.1628 17.7204C27.2101 17.9272 27.1534 18.1434 27.0211 18.3126C26.8793 18.4724 26.6808 18.5664 26.4634 18.5664ZM12.211 17.1564H25.5845L24.6961 12.9264C24.4314 11.695 24.1195 10.8866 22.2198 10.8866H15.5851C13.6854 10.8866 13.3735 11.695 13.1089 12.9264L12.211 17.1564Z"
                            fill="#FFEEEF" />
                        <path
                            d="M26.4266 28.9106H24.6498C23.1187 28.9106 22.8257 28.0364 22.6367 27.463L22.4477 26.899C22.2019 26.1846 22.1736 26.0906 21.323 26.0906H16.484C15.6333 26.0906 15.5766 26.2504 15.3593 26.899L15.1702 27.463C14.9718 28.0458 14.6882 28.9106 13.1571 28.9106H11.3803C10.6337 28.9106 9.91536 28.6004 9.41445 28.0552C8.92298 27.5194 8.6867 26.805 8.75286 26.0906L9.28213 20.366C9.4239 18.815 9.83975 17.1606 12.8736 17.1606H24.9333C27.9672 17.1606 28.3736 18.815 28.5248 20.366L29.0541 26.0906C29.1202 26.805 28.884 27.5194 28.3925 28.0552C27.8916 28.6004 27.1733 28.9106 26.4266 28.9106ZM16.484 24.6806H21.323C23.0431 24.6806 23.4495 25.442 23.7898 26.4384L23.9882 27.0212C24.1489 27.5006 24.1489 27.51 24.6593 27.51H26.4361C26.7858 27.51 27.1166 27.369 27.3529 27.1152C27.5797 26.8708 27.6837 26.5606 27.6553 26.2316L27.126 20.507C27.0032 19.238 26.8519 18.58 24.9523 18.58H12.8736C10.9644 18.58 10.8132 19.238 10.6998 20.507L10.1705 26.2316C10.1422 26.5606 10.2462 26.8708 10.473 27.1152C10.6998 27.369 11.0401 27.51 11.3898 27.51H13.1666C13.6769 27.51 13.6769 27.5006 13.8376 27.0306L14.0266 26.4572C14.2629 25.7052 14.6126 24.6806 16.484 24.6806ZM11.3406 15.7506H10.3955C10.008 15.7506 9.68664 15.431 9.68664 15.0456C9.68664 14.6602 10.008 14.3406 10.3955 14.3406H11.3406C11.7281 14.3406 12.0494 14.6602 12.0494 15.0456C12.0494 15.431 11.7281 15.7506 11.3406 15.7506ZM27.4077 15.7506H26.4626C26.0751 15.7506 25.7537 15.431 25.7537 15.0456C25.7537 14.6602 26.0751 14.3406 26.4626 14.3406H27.4077C27.7952 14.3406 28.1165 14.6602 28.1165 15.0456C28.1165 15.431 27.7952 15.7506 27.4077 15.7506ZM18.9016 12.9306C18.5141 12.9306 18.1927 12.611 18.1927 12.2256V10.3456C18.1927 9.96023 18.5141 9.64062 18.9016 9.64062C19.2891 9.64062 19.6104 9.96023 19.6104 10.3456V12.2256C19.6104 12.611 19.2891 12.9306 18.9016 12.9306Z"
                            fill="#FFEEEF" />
                        <path
                            d="M20.3192 12.9256H17.4839C17.0964 12.9256 16.775 12.606 16.775 12.2206C16.775 11.8352 17.0964 11.5156 17.4839 11.5156H20.3192C20.7067 11.5156 21.0281 11.8352 21.0281 12.2206C21.0281 12.606 20.7067 12.9256 20.3192 12.9256ZM16.0662 22.3256H13.2308C12.8433 22.3256 12.522 22.006 12.522 21.6206C12.522 21.2352 12.8433 20.9156 13.2308 20.9156H16.0662C16.4537 20.9156 16.775 21.2352 16.775 21.6206C16.775 22.006 16.4537 22.3256 16.0662 22.3256ZM24.5723 22.3256H21.7369C21.3494 22.3256 21.0281 22.006 21.0281 21.6206C21.0281 21.2352 21.3494 20.9156 21.7369 20.9156H24.5723C24.9598 20.9156 25.2811 21.2352 25.2811 21.6206C25.2811 22.006 24.9598 22.3256 24.5723 22.3256Z"
                            fill="#FFEEEF" />
                    </svg>

                    <p>Permanent</p>
                    <span>{{ $statusCounts['Apply'] }} Nurses Available</span>
                </div>
            </div>
            <!-- </div>
            <div class="col-4"> -->
            <div class="Proffesion_appears" style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec emp" onclick="applicationType('Screening')" id="Screening">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="37.8049" height="37.6" rx="13" fill="#006BC9" />
                        <path
                            d="M26.4634 18.5664H11.3415C11.1241 18.5664 10.9256 18.4724 10.7933 18.3032C10.7266 18.2213 10.6793 18.1256 10.6547 18.0231C10.6302 17.9206 10.6291 17.8139 10.6515 17.711L11.7195 12.635C12.0692 10.99 12.7781 9.47656 15.5851 9.47656H22.2293C25.0363 9.47656 25.7451 10.9994 26.0948 12.635L27.1628 17.7204C27.2101 17.9272 27.1534 18.1434 27.0211 18.3126C26.8793 18.4724 26.6808 18.5664 26.4634 18.5664ZM12.211 17.1564H25.5845L24.6961 12.9264C24.4314 11.695 24.1195 10.8866 22.2198 10.8866H15.5851C13.6854 10.8866 13.3735 11.695 13.1089 12.9264L12.211 17.1564Z"
                            fill="#FFEEEF" />
                        <path
                            d="M26.4266 28.9106H24.6498C23.1187 28.9106 22.8257 28.0364 22.6367 27.463L22.4477 26.899C22.2019 26.1846 22.1736 26.0906 21.323 26.0906H16.484C15.6333 26.0906 15.5766 26.2504 15.3593 26.899L15.1702 27.463C14.9718 28.0458 14.6882 28.9106 13.1571 28.9106H11.3803C10.6337 28.9106 9.91536 28.6004 9.41445 28.0552C8.92298 27.5194 8.6867 26.805 8.75286 26.0906L9.28213 20.366C9.4239 18.815 9.83975 17.1606 12.8736 17.1606H24.9333C27.9672 17.1606 28.3736 18.815 28.5248 20.366L29.0541 26.0906C29.1202 26.805 28.884 27.5194 28.3925 28.0552C27.8916 28.6004 27.1733 28.9106 26.4266 28.9106ZM16.484 24.6806H21.323C23.0431 24.6806 23.4495 25.442 23.7898 26.4384L23.9882 27.0212C24.1489 27.5006 24.1489 27.51 24.6593 27.51H26.4361C26.7858 27.51 27.1166 27.369 27.3529 27.1152C27.5797 26.8708 27.6837 26.5606 27.6553 26.2316L27.126 20.507C27.0032 19.238 26.8519 18.58 24.9523 18.58H12.8736C10.9644 18.58 10.8132 19.238 10.6998 20.507L10.1705 26.2316C10.1422 26.5606 10.2462 26.8708 10.473 27.1152C10.6998 27.369 11.0401 27.51 11.3898 27.51H13.1666C13.6769 27.51 13.6769 27.5006 13.8376 27.0306L14.0266 26.4572C14.2629 25.7052 14.6126 24.6806 16.484 24.6806ZM11.3406 15.7506H10.3955C10.008 15.7506 9.68664 15.431 9.68664 15.0456C9.68664 14.6602 10.008 14.3406 10.3955 14.3406H11.3406C11.7281 14.3406 12.0494 14.6602 12.0494 15.0456C12.0494 15.431 11.7281 15.7506 11.3406 15.7506ZM27.4077 15.7506H26.4626C26.0751 15.7506 25.7537 15.431 25.7537 15.0456C25.7537 14.6602 26.0751 14.3406 26.4626 14.3406H27.4077C27.7952 14.3406 28.1165 14.6602 28.1165 15.0456C28.1165 15.431 27.7952 15.7506 27.4077 15.7506ZM18.9016 12.9306C18.5141 12.9306 18.1927 12.611 18.1927 12.2256V10.3456C18.1927 9.96023 18.5141 9.64062 18.9016 9.64062C19.2891 9.64062 19.6104 9.96023 19.6104 10.3456V12.2256C19.6104 12.611 19.2891 12.9306 18.9016 12.9306Z"
                            fill="#FFEEEF" />
                        <path
                            d="M20.3192 12.9256H17.4839C17.0964 12.9256 16.775 12.606 16.775 12.2206C16.775 11.8352 17.0964 11.5156 17.4839 11.5156H20.3192C20.7067 11.5156 21.0281 11.8352 21.0281 12.2206C21.0281 12.606 20.7067 12.9256 20.3192 12.9256ZM16.0662 22.3256H13.2308C12.8433 22.3256 12.522 22.006 12.522 21.6206C12.522 21.2352 12.8433 20.9156 13.2308 20.9156H16.0662C16.4537 20.9156 16.775 21.2352 16.775 21.6206C16.775 22.006 16.4537 22.3256 16.0662 22.3256ZM24.5723 22.3256H21.7369C21.3494 22.3256 21.0281 22.006 21.0281 21.6206C21.0281 21.2352 21.3494 20.9156 21.7369 20.9156H24.5723C24.9598 20.9156 25.2811 21.2352 25.2811 21.6206C25.2811 22.006 24.9598 22.3256 24.5723 22.3256Z"
                            fill="#FFEEEF" />
                    </svg>

                    <p>Travel</p>
                    <span>{{ $statusCounts['Screening'] }} Nurses Available</span>
                </div>
            </div>
            <!-- </div>
            <div class="col-4"> -->
            <div class="Proffesion_appears" style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec emp" onclick="applicationType('Submitted')" id="Submitted">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="37.8049" height="37.6" rx="13" fill="#006BC9" />
                        <path
                            d="M26.4634 18.5664H11.3415C11.1241 18.5664 10.9256 18.4724 10.7933 18.3032C10.7266 18.2213 10.6793 18.1256 10.6547 18.0231C10.6302 17.9206 10.6291 17.8139 10.6515 17.711L11.7195 12.635C12.0692 10.99 12.7781 9.47656 15.5851 9.47656H22.2293C25.0363 9.47656 25.7451 10.9994 26.0948 12.635L27.1628 17.7204C27.2101 17.9272 27.1534 18.1434 27.0211 18.3126C26.8793 18.4724 26.6808 18.5664 26.4634 18.5664ZM12.211 17.1564H25.5845L24.6961 12.9264C24.4314 11.695 24.1195 10.8866 22.2198 10.8866H15.5851C13.6854 10.8866 13.3735 11.695 13.1089 12.9264L12.211 17.1564Z"
                            fill="#FFEEEF" />
                        <path
                            d="M26.4266 28.9106H24.6498C23.1187 28.9106 22.8257 28.0364 22.6367 27.463L22.4477 26.899C22.2019 26.1846 22.1736 26.0906 21.323 26.0906H16.484C15.6333 26.0906 15.5766 26.2504 15.3593 26.899L15.1702 27.463C14.9718 28.0458 14.6882 28.9106 13.1571 28.9106H11.3803C10.6337 28.9106 9.91536 28.6004 9.41445 28.0552C8.92298 27.5194 8.6867 26.805 8.75286 26.0906L9.28213 20.366C9.4239 18.815 9.83975 17.1606 12.8736 17.1606H24.9333C27.9672 17.1606 28.3736 18.815 28.5248 20.366L29.0541 26.0906C29.1202 26.805 28.884 27.5194 28.3925 28.0552C27.8916 28.6004 27.1733 28.9106 26.4266 28.9106ZM16.484 24.6806H21.323C23.0431 24.6806 23.4495 25.442 23.7898 26.4384L23.9882 27.0212C24.1489 27.5006 24.1489 27.51 24.6593 27.51H26.4361C26.7858 27.51 27.1166 27.369 27.3529 27.1152C27.5797 26.8708 27.6837 26.5606 27.6553 26.2316L27.126 20.507C27.0032 19.238 26.8519 18.58 24.9523 18.58H12.8736C10.9644 18.58 10.8132 19.238 10.6998 20.507L10.1705 26.2316C10.1422 26.5606 10.2462 26.8708 10.473 27.1152C10.6998 27.369 11.0401 27.51 11.3898 27.51H13.1666C13.6769 27.51 13.6769 27.5006 13.8376 27.0306L14.0266 26.4572C14.2629 25.7052 14.6126 24.6806 16.484 24.6806ZM11.3406 15.7506H10.3955C10.008 15.7506 9.68664 15.431 9.68664 15.0456C9.68664 14.6602 10.008 14.3406 10.3955 14.3406H11.3406C11.7281 14.3406 12.0494 14.6602 12.0494 15.0456C12.0494 15.431 11.7281 15.7506 11.3406 15.7506ZM27.4077 15.7506H26.4626C26.0751 15.7506 25.7537 15.431 25.7537 15.0456C25.7537 14.6602 26.0751 14.3406 26.4626 14.3406H27.4077C27.7952 14.3406 28.1165 14.6602 28.1165 15.0456C28.1165 15.431 27.7952 15.7506 27.4077 15.7506ZM18.9016 12.9306C18.5141 12.9306 18.1927 12.611 18.1927 12.2256V10.3456C18.1927 9.96023 18.5141 9.64062 18.9016 9.64062C19.2891 9.64062 19.6104 9.96023 19.6104 10.3456V12.2256C19.6104 12.611 19.2891 12.9306 18.9016 12.9306Z"
                            fill="#FFEEEF" />
                        <path
                            d="M20.3192 12.9256H17.4839C17.0964 12.9256 16.775 12.606 16.775 12.2206C16.775 11.8352 17.0964 11.5156 17.4839 11.5156H20.3192C20.7067 11.5156 21.0281 11.8352 21.0281 12.2206C21.0281 12.606 20.7067 12.9256 20.3192 12.9256ZM16.0662 22.3256H13.2308C12.8433 22.3256 12.522 22.006 12.522 21.6206C12.522 21.2352 12.8433 20.9156 13.2308 20.9156H16.0662C16.4537 20.9156 16.775 21.2352 16.775 21.6206C16.775 22.006 16.4537 22.3256 16.0662 22.3256ZM24.5723 22.3256H21.7369C21.3494 22.3256 21.0281 22.006 21.0281 21.6206C21.0281 21.2352 21.3494 20.9156 21.7369 20.9156H24.5723C24.9598 20.9156 25.2811 21.2352 25.2811 21.6206C25.2811 22.006 24.9598 22.3256 24.5723 22.3256Z"
                            fill="#FFEEEF" />
                    </svg>
                    <div class="proffInfo">
                        <p>Per Diem</p>
                        <span>{{ $statusCounts['Submitted'] }} Nurses Available</span>
                    </div>

                </div>
            </div>
            <!-- </div> -->
            <!-- <div class="col-4"> -->
            <div class="Proffesion_appears" style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec emp" onclick="applicationType('Offered')" id="Offered">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="37.8049" height="37.6" rx="13" fill="#006BC9" />
                        <path
                            d="M26.4634 18.5664H11.3415C11.1241 18.5664 10.9256 18.4724 10.7933 18.3032C10.7266 18.2213 10.6793 18.1256 10.6547 18.0231C10.6302 17.9206 10.6291 17.8139 10.6515 17.711L11.7195 12.635C12.0692 10.99 12.7781 9.47656 15.5851 9.47656H22.2293C25.0363 9.47656 25.7451 10.9994 26.0948 12.635L27.1628 17.7204C27.2101 17.9272 27.1534 18.1434 27.0211 18.3126C26.8793 18.4724 26.6808 18.5664 26.4634 18.5664ZM12.211 17.1564H25.5845L24.6961 12.9264C24.4314 11.695 24.1195 10.8866 22.2198 10.8866H15.5851C13.6854 10.8866 13.3735 11.695 13.1089 12.9264L12.211 17.1564Z"
                            fill="#FFEEEF" />
                        <path
                            d="M26.4266 28.9106H24.6498C23.1187 28.9106 22.8257 28.0364 22.6367 27.463L22.4477 26.899C22.2019 26.1846 22.1736 26.0906 21.323 26.0906H16.484C15.6333 26.0906 15.5766 26.2504 15.3593 26.899L15.1702 27.463C14.9718 28.0458 14.6882 28.9106 13.1571 28.9106H11.3803C10.6337 28.9106 9.91536 28.6004 9.41445 28.0552C8.92298 27.5194 8.6867 26.805 8.75286 26.0906L9.28213 20.366C9.4239 18.815 9.83975 17.1606 12.8736 17.1606H24.9333C27.9672 17.1606 28.3736 18.815 28.5248 20.366L29.0541 26.0906C29.1202 26.805 28.884 27.5194 28.3925 28.0552C27.8916 28.6004 27.1733 28.9106 26.4266 28.9106ZM16.484 24.6806H21.323C23.0431 24.6806 23.4495 25.442 23.7898 26.4384L23.9882 27.0212C24.1489 27.5006 24.1489 27.51 24.6593 27.51H26.4361C26.7858 27.51 27.1166 27.369 27.3529 27.1152C27.5797 26.8708 27.6837 26.5606 27.6553 26.2316L27.126 20.507C27.0032 19.238 26.8519 18.58 24.9523 18.58H12.8736C10.9644 18.58 10.8132 19.238 10.6998 20.507L10.1705 26.2316C10.1422 26.5606 10.2462 26.8708 10.473 27.1152C10.6998 27.369 11.0401 27.51 11.3898 27.51H13.1666C13.6769 27.51 13.6769 27.5006 13.8376 27.0306L14.0266 26.4572C14.2629 25.7052 14.6126 24.6806 16.484 24.6806ZM11.3406 15.7506H10.3955C10.008 15.7506 9.68664 15.431 9.68664 15.0456C9.68664 14.6602 10.008 14.3406 10.3955 14.3406H11.3406C11.7281 14.3406 12.0494 14.6602 12.0494 15.0456C12.0494 15.431 11.7281 15.7506 11.3406 15.7506ZM27.4077 15.7506H26.4626C26.0751 15.7506 25.7537 15.431 25.7537 15.0456C25.7537 14.6602 26.0751 14.3406 26.4626 14.3406H27.4077C27.7952 14.3406 28.1165 14.6602 28.1165 15.0456C28.1165 15.431 27.7952 15.7506 27.4077 15.7506ZM18.9016 12.9306C18.5141 12.9306 18.1927 12.611 18.1927 12.2256V10.3456C18.1927 9.96023 18.5141 9.64062 18.9016 9.64062C19.2891 9.64062 19.6104 9.96023 19.6104 10.3456V12.2256C19.6104 12.611 19.2891 12.9306 18.9016 12.9306Z"
                            fill="#FFEEEF" />
                        <path
                            d="M20.3192 12.9256H17.4839C17.0964 12.9256 16.775 12.606 16.775 12.2206C16.775 11.8352 17.0964 11.5156 17.4839 11.5156H20.3192C20.7067 11.5156 21.0281 11.8352 21.0281 12.2206C21.0281 12.606 20.7067 12.9256 20.3192 12.9256ZM16.0662 22.3256H13.2308C12.8433 22.3256 12.522 22.006 12.522 21.6206C12.522 21.2352 12.8433 20.9156 13.2308 20.9156H16.0662C16.4537 20.9156 16.775 21.2352 16.775 21.6206C16.775 22.006 16.4537 22.3256 16.0662 22.3256ZM24.5723 22.3256H21.7369C21.3494 22.3256 21.0281 22.006 21.0281 21.6206C21.0281 21.2352 21.3494 20.9156 21.7369 20.9156H24.5723C24.9598 20.9156 25.2811 21.2352 25.2811 21.6206C25.2811 22.006 24.9598 22.3256 24.5723 22.3256Z"
                            fill="#FFEEEF" />
                    </svg>

                    <p>Local</p>
                    <span>{{ $statusCounts['Offered'] }} Nurses Available</span>
                </div>
            </div>
            <!-- </div> -->


            <!-- start job request  -->
            <div class="Proffesion_appears" style="flex: 3 3 0px;">

                <div class="ss-rec-start-rec-div-sec emp">

                    <h6>Start Posting<br /> Your Job Request</h6>
                    <a href="{{ route('recruiter-application') }}"><img
                            src="{{ URL::asset('recruiter/assets/images/plus-icon.png') }}" /></a>
                </div>
            </div>


            <div class="ss-appli-done-hed-btn-dv d-none" id="ss-appli-done-hed-btn-dv">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>Applicants</h2>
                    </div>
                    <div class="col-lg-6">
                        <ul>
                            <li><a href="javascript:void(0)" onclick="applicationType('Done')" class="active">Done</a>
                            </li>
                            <li><a href="javascript:void(0)" onclick="applicationType('Rejected')">Rejected</a></li>
                            <li><a href="javascript:void(0)" onclick="applicationType('Blocked')">Blocked</a></li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row" style="margin-top:10px;">
                <div style="flex: 3.1 3.1 0px;">


                    <h5 class="ss-dash-wel-div col-lg-5 col-sm-12 col-md-12 text-start">New Applicants List</h5>

                    <div class="ss-job-prfle-sec p-4">

                        <div class="col-lg-12">

                            <div class="row spc">
                                <!-- first col -->
                                <div class="col-12 col-sm-12">

                                    <div class="row">

                                        <div class="col-lg-4 col-sm-12 col-md-12">

                                            <div class="row">
                                                <div class="col-4 col-sm-4 col-md-4">
                                                    <img class="proffession_application_profil"
                                                        src="{{ URL::asset('frontend/img/message-img4.png') }}" alt="">
                                                </div>
                                                <div class="col-8 col-sm-8">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 ">
                                                            <p class="app_info_home1">
                                                                Profession</p>
                                                        </div>
                                                        <div class="col-12 col-sm-12 ">
                                                            <p class="app_info_home2">
                                                                David Lee</p>
                                                        </div>
                                                        <div class="col-12 col-sm-12">
                                                            <p class="app_info_home3">
                                                                Facility</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-12 text-start">
                                                    <a href="#" style="color:#3D2C39;"> Los Angeles, CA</a>
                                                    <a href="#" style="color:#3D2C39;"> Permanent</a>
                                                </div>
                                                <div class="col-12 text-start">
                                                    <a href="#" style="color:#3D2C39; "> 8+ Exp </a>
                                                    <a href="#" style="color:#3D2C39;  "> $2500/wk</a>

                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="col-lg-4 col-sm-12 col-md-12 d-flex align-items-center justify-content-center">
                                            <div class="ss-fliter-btn-dv">
                                                <button class="home_button_application">Move
                                                    to submitted</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr class="col-12 col-sm-12 application-separator" style="height: 2px;">
                                <!-- first col -->
                                <div class="col-12 col-sm-12">

                                    <div class="row">

                                        <div class="col-lg-4 col-sm-12 col-md-12">

                                            <div class="row">
                                                <div class="col-4 col-sm-4 col-md-4">
                                                    <img class="proffession_application_profil"
                                                        src="{{ URL::asset('frontend/img/message-img2.png') }}" alt="">
                                                </div>
                                                <div class="col-8 col-sm-8">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12">
                                                            <p class="app_info_home1">
                                                                Profession</p>
                                                        </div>
                                                        <div class="col-12 col-sm-12">
                                                            <p class="app_info_home2">
                                                                Mary Smith</p>
                                                        </div>
                                                        <div class="col-12 col-sm-12">
                                                            <p class="app_info_home3">
                                                                Facility</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-12 text-start">
                                                    <a href="#" style="color:#3D2C39;"> Los Angeles, CA</a>
                                                    <a href="#" style="color:#3D2C39;"> Permanent</a>
                                                </div>
                                                <div class="col-12 text-start">
                                                    <a href="#" style="color:#3D2C39; "> 8+ Exp </a>
                                                    <a href="#" style="color:#3D2C39;  "> $2500/wk</a>

                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="col-lg-4 col-sm-12 col-md-12 d-flex align-items-center justify-content-center">
                                            <div class="ss-fliter-btn-dv">
                                                <button class="home_button_application">Move
                                                    to submitted</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr class="application-separator" style="height: 2px;">
                                <!-- first col -->
                                <div class="col-12 col-sm-12">

                                    <div class="row">

                                        <div class="col-lg-4 col-sm-12 col-md-12">

                                            <div class="row">
                                                <div class="col-4 col-sm-4 col-md-4">
                                                    <img class="proffession_application_profil"
                                                        src="{{ URL::asset('frontend/img/message-img1.png') }}" alt="">
                                                </div>
                                                <div class="col-8 col-sm-8">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12">
                                                            <p class="app_info_home1">
                                                                Profession</p>
                                                        </div>
                                                        <div class="col-12 col-sm-12">
                                                            <p class="app_info_home2">
                                                                Mary Smith</p>
                                                        </div>
                                                        <div class="col-12 col-sm-12">
                                                            <p class="app_info_home3">
                                                                Facility</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-12 text-start">
                                                    <a href="#" style="color:#3D2C39;"> Los Angeles, CA</a>
                                                    <a href="#" style="color:#3D2C39;"> Permanent</a>
                                                </div>
                                                <div class="col-12 text-start">
                                                    <a href="#" style="color:#3D2C39; "> 8+ Exp </a>
                                                    <a href="#" style="color:#3D2C39;  "> $2500/wk</a>

                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="col-lg-4 col-sm-12 col-md-12 d-flex align-items-center justify-content-center">
                                            <div class="ss-fliter-btn-dv d-flex">
                                                <button class="home_button_application">Move
                                                    to submitted</button>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="flex: 2 2 0px;">
                    <h5 class="ss-dash-wel-div col-3">Messages</h5>
                    <div class="ss-job-prfle-sec p-4">

                        <!-- first -->
                        <div class="row" style="gap:12px;">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-1 "><button class="home_button_icon_messages"></button>
                                    </div>
                                    <div class="col-11 d-flex text-start">
                                        <div class="row">
                                            <div class=" col-12 home_notification_message_messages">
                                                You have a message from that needs to be ...
                                            </div>
                                            <div class="col-12" style="color:#1C1C1C66; font-size: 12px;">Just Now
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-1 "><button class="home_button_icon_messages"></button>
                                    </div>
                                    <div class="col-11 d-flex text-start">
                                        <div class="row">
                                            <div class="col-12 home_notification_message_messages">
                                                New nurse registered ...</div>
                                            <div class="col-12" style="color:#1C1C1C66; font-size: 12px;">59 minutes
                                                ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="row">
                                    <div class="col-1 "><button class="home_button_icon_messages"></button>
                                    </div>
                                    <div class="col-11 d-flex text-start">
                                        <div class="row">
                                            <div class="col-12 home_notification_message_messages">
                                                You have a request that needs to be fixed. ...</div>
                                            <div class="col-12" style="color:#1C1C1C66; font-size: 12px;">12 hours ago
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="row">
                                    <div class="col-1 "><button class="home_button_icon_messages"></button>
                                    </div>
                                    <div class="col-11 d-flex text-start">
                                        <div class="row">
                                            <div class="col-12 home_notification_message_messages">
                                                Andi Lane texted to you ...</div>
                                            <div class="col-12" style="color:#1C1C1C66; font-size: 12px;">Today, 11:59
                                                AM</div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
                <!-- Recommended for you start-->

                <div class="row">
                    <div class="col-lg-10 col-sm-6 col-md-6 d-flex align-items-center   ">
                        <h5 class="ss-dash-wel-div text-start">Recommended for you</h5>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-md-6 text-end"><button class="viewall-applications-emp-btn">view all</button></div>
                    <!-- fisrt card -->
                    <div class="col-lg-4 col-sm-12 col-md-12">
                        <div class="ss-mesg-sml-div" style="padding:0px;">
                            <div class="row">
                                <div class="col-lg-3 col-sm-4 col-md-4 " style="width: 20%;">
                                    <img class="proffession_application_profil"
                                        src="{{ URL::asset('recruiter/assets/images/recomand-img-1.png') }}" alt="">
                                </div>
                                <div class="col-9 col-sm-9">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 ">
                                            <p class="app_info_home1">
                                                CRNA</p>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <p class="app_info_home2">
                                                James Bond</p>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <p class="app_info_home3">
                                                Anesthesia</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- information of application -->
                                <div class="name_card_locations">
                                    <div class="col-3"> <a href="#" class="application-a"> Los Angeles, CA</a>
                                    </div>
                                    <div class="col-3"><a href="#" class="application-a"> Permanent</a></div>
                                    <div class="col-3"><a href="#" class="application-a"> Day Shift</a></div>
                                    <div class="col-3"><a href="#" class="application-a"> $2500/wk</a></div>
                                </div>
                                <!-- information of application -->

                            </div>
                        </div>
                    </div>
                    <!-- second card -->
                    <div class="col-lg-4 col-sm-12 col-md-12">

                        <div class="ss-mesg-sml-div" style="padding:0px;">
                            <div class="row">
                                <div class="col-lg-3 col-sm-4 col-md-4 " style="width: 20%;">
                                    <img class="proffession_application_profil"
                                        src="{{ URL::asset('recruiter/assets/images/recomand-img-2.png') }}" alt="">
                                </div>
                                <div class="col-9 col-sm-9">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 ">
                                            <p class="app_info_home1">
                                                Associate Degree in Nursing</p>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <p class="app_info_home2">
                                                Mary Smith</p>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <p class="app_info_home3">
                                                BLS, CCRN</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- information of application -->
                                <div class="name_card_locations">
                                    <div class="col-3"> <a href="#" class="application-a"> Los Angeles, CA</a>
                                    </div>
                                    <div class="col-3"><a href="#" class="application-a"> Permanent</a></div>
                                    <div class="col-3"><a href="#" class="application-a"> Day Shift</a></div>
                                </div>
                                <!-- information of application -->

                            </div>
                        </div>
                    </div>
                    <!-- three card -->
                    <div class="col-lg-4 col-sm-12 col-md-12">

                        <div class="ss-mesg-sml-div" style="padding:0px;">
                            <div class="row">
                                <div class="col-lg-3 col-sm-4 col-md-4 " style="width: 20%;">
                                    <img class="proffession_application_profil"
                                        src="{{ URL::asset('recruiter/assets/images/recomand-img-3.png') }}" alt="">
                                </div>
                                <div class="col-9 col-sm-9">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 ">
                                            <p class="app_info_home1">
                                                Master of Science in Nursing</p>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <p class="app_info_home2">
                                                David Lee
                                            </p>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <p class="app_info_home3">
                                                CEN, TNCC</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- information of application -->
                                <div class="name_card_locations">
                                    <div class="col-3"> <a href="#" class="application-a"> Los Angeles, CA</a>
                                    </div>
                                    <div class="col-3"><a href="#" class="application-a"> Permanent</a></div>
                                    <div class="col-3"><a href="#" class="application-a"> Day Shift</a></div>
                                    <div class="col-3"><a href="#" class="application-a"> $2500/wk</a></div>
                                </div>
                                <!-- information of application -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommended for you end -->
            </div>
        </div>

</main>
<script>
    function applicationType(type, id = "", formtype, jobid = "") {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

        if (formtype == "joballdetails" || formtype == "createdraft") {
            event.preventDefault();
            var $form = $('#send-job-offer');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!csrfToken) {
                console.error('CSRF token not found.');
                return;
            }
            var formData = $form.serialize();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('recruiter-send-job-offer') }}",
                data: formData,
                dataType: 'json',
                success: function (data) {
                    if (type == "createdraft") {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Draft Created Successfully',
                            time: 5
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    }
                },
                error: function (xhr, status, error) {
                    console.log('AJAX request error:', error);
                }
            });
        }

        var applyElement = document.getElementById('Apply');
        var screeningElement = document.getElementById('Screening');
        var submittedElement = document.getElementById('Submitted');
        var offeredElement = document.getElementById('Offered');
        var onboardingElement = document.getElementById('Onboarding');
        var workingElement = document.getElementById('Working');
        var doneElement = document.getElementById('Done');

        if (applyElement.classList.contains("active")) {
            applyElement.classList.remove("active");
        }
        if (screeningElement.classList.contains("active")) {
            screeningElement.classList.remove("active");
        }
        if (submittedElement.classList.contains("active")) {
            submittedElement.classList.remove("active");
        }
        if (offeredElement.classList.contains("active")) {
            offeredElement.classList.remove("active");
        }
        if (onboardingElement.classList.contains("active")) {
            onboardingElement.classList.remove("active");
        }
        if (workingElement.classList.contains("active")) {
            workingElement.classList.remove("active");
        }
        if (doneElement.classList.contains("active")) {
            doneElement.classList.remove("active");
        }

        document.getElementById(type).classList.add("active")

        document.getElementById('listingname').innerHTML = type + ' Application';
        if (type == 'Done' || type == 'Rejected' || type == 'Blocked') {
            document.getElementById("ss-appli-done-hed-btn-dv").classList.remove("d-none");
        } else {
            document.getElementById("ss-appli-done-hed-btn-dv").classList.add("d-none");
        }
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('recruiter/get-application-listing') }}",
                data: {
                    'token': csrfToken,
                    'type': type,
                    'id': id,
                    'formtype': formtype,
                    'jobid': jobid
                },
                type: 'POST',
                dataType: 'json',
                success: function (result) {

                    $("#application-list").html(result.applicationlisting);
                    $("#application-details").html(result.applicationdetails);
                    window.allspecialty = result.allspecialty;
                    window.allvaccinations = result.allvaccinations;
                    window.allcertificate = result.allcertificate;
                    list_specialities();
                    list_vaccinations();
                    list_certifications();
                },
                error: function (error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }

    function showDetails(id) {
        console.log(id);
    }

    function sendOffer(type, userid, jobid) {
        document.getElementById("offer-form").classList.remove("d-none");
        document.getElementById("application-details").classList.add("d-none");
    }
    $(document).ready(function () {
        applicationType('Apply')
    });



    function offerSend(id, jobid, type) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('recruiter/send-job-offer-recruiter') }}",
                data: {
                    'token': csrfToken,
                    'id': id,
                    'jobid': jobid,
                    'type': type,
                },
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + result.message,
                        time: 5
                    });
                },
                error: function (error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }
    setInterval(function () {
        $(document).ready(function () {
            $('.application-job-slider-owl').owlCarousel({
                items: 3,
                loop: true,
                autoplay: true,
                autoplayTimeout: 5000,
                margin: 20,
                nav: false,
                dots: false,
                navText: ['<span class="fa fa-angle-left  fa-2x"></span>',
                    '<span class="fas fa fa-angle-right fa-2x"></span>'
                ],
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    992: {
                        items: 2
                    }
                }
            })
        })
    }, 3000)
</script>
<script>
    var speciality = {};

    // console.log(window.allspecialty)
    // console.log(window.allvaccinations)
    // console.log(window.allcertificate)

    function add_speciality(obj) {
        if (!$('#preferred_specialty').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the speciality please.',
                time: 3
            });
        } else if (!$('#preferred_experience').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Enter total year of experience.',
                time: 3
            });
        } else {
            if (!speciality.hasOwnProperty($('#preferred_specialty').val())) {
                speciality[$('#preferred_specialty').val()] = $('#preferred_experience').val();
                $('#preferred_experience').val('');
                $('#preferred_specialty').val('');
                list_specialities();
            }
        }
    }

    function remove_speciality(obj, key) {
        if (speciality.hasOwnProperty($(obj).data('key'))) {
            var element = document.getElementById("remove-speciality");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'specialty': key,
                    }
                    let removetype = 'specialty';
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        type: 'POST',
                        url: "{{ url('recruiter/remove') }}/" + removetype,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {

                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
                delete speciality[$(obj).data('key')];
                delete window.allspecialty[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_specialities();
        }
    }

    function list_specialities() {
        var str = '';
        if (window.allspecialty) {
            speciality = Object.assign({}, speciality, window.allspecialty);
        }
        for (const key in speciality) {
            let specialityname = "";

            var select = document.getElementById("preferred_specialty");
            var allspcldata = [];
            for (var i = 0; i < select.options.length; i++) {
                var obj = {
                    'id': select.options[i].value,
                    'title': select.options[i].textContent,
                };
                allspcldata.push(obj);
            }

            if (speciality.hasOwnProperty(key)) {
                allspcldata.forEach(function (item) {
                    if (key == item.id) {
                        specialityname = item.title;
                    }
                });
                const value = speciality[key];
                str += '<ul>';
                str += '<li>' + specialityname + '</li>';
                str += '<li>' + value + ' Years</li>';
                str += '<li><button type="button"  id="remove-speciality" data-key="' + key +
                    '" onclick="remove_speciality(this, ' + key +
                    ')"><img src="{{ URL::asset('
                frontend / img / delete - img.png ') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.speciality-content').html(str);
    }
</script>
<script>
    var vaccinations = {};

    function addvacc() {

        // var container = document.getElementById('add-more-certifications');

        // var newSelect = document.createElement('select');
        // newSelect.name = 'certificate';
        // newSelect.className = 'mb-3';

        // var originalSelect = document.getElementById('certificate');
        // var options = originalSelect.querySelectorAll('option');
        // for (var i = 0; i < options.length; i++) {
        //     var option = options[i].cloneNode(true);
        //     newSelect.appendChild(option);
        // }
        // container.querySelector('.col-md-11').appendChild(newSelect);

        if (!$('#vaccinations').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the vaccinations please.',
                time: 3
            });
        } else {
            if (!vaccinations.hasOwnProperty($('#vaccinations').val())) {
                console.log($('#vaccinations').val());

                var select = document.getElementById("vaccinations");
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                vaccinations[$('#vaccinations').val()] = optionText;
                $('#vaccinations').val('');
                list_vaccinations();
            }
        }
    }

    function list_vaccinations() {
        var str = '';
        if (window.allvaccinations) {
            vaccinations = Object.assign({}, vaccinations, window.allvaccinations);
        }
        for (const key in vaccinations) {
            let vaccinationsname = "";
            var select = document.getElementById("vaccinations");
            console.log(select);
            var allspcldata = [];
            for (var i = 0; i < select.options.length; i++) {
                var obj = {
                    'id': select.options[i].value,
                    'title': select.options[i].textContent,
                };
                allspcldata.push(obj);
            }

            if (vaccinations.hasOwnProperty(key)) {

                allspcldata.forEach(function (item) {
                    if (key == item.id) {
                        vaccinationsname = item.title;
                    }
                });
                const value = vaccinations[key];
                str += '<ul>';
                str += '<li class="w-50">' + vaccinationsname + '</li>';
                str += '<li class="w-50 text-end"><button type="button"  id="remove-vaccinations" data-key="' + key +
                    '" onclick="remove_vaccinations(this, ' + key +
                    ')"><img src="{{ URL::asset('
                frontend / img / delete - img.png ') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.vaccinations-content').html(str);
    }

    function remove_vaccinations(obj, key) {
        if (vaccinations.hasOwnProperty($(obj).data('key'))) {

            var element = document.getElementById("remove-vaccinations");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'vaccinations': key,
                    }
                    let removetype = 'vaccinations';
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        type: 'POST',
                        url: "{{ url('recruiter/remove') }}/" + removetype,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            // notie.alert({
                            //     type: 'success',
                            //     text: '<i class="fa fa-check"></i> ' + data.message,
                            //     time: 5
                            // });
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
                delete window.allvaccinations[$(obj).data('key')];
                delete vaccinations[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_vaccinations();
        }
    }
</script>
<script>
    var certificate = {};

    function addcertifications() {
        if (!$('#certificate').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the certificate please.',
                time: 3
            });
        } else {
            if (!certificate.hasOwnProperty($('#certificate').val())) {
                console.log($('#certificate').val());

                var select = document.getElementById("certificate");
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                certificate[$('#certificate').val()] = optionText;
                $('#certificate').val('');
                list_certifications();
            }
        }
    }

    function list_certifications() {
        var str = '';
        if (window.allcertificate) {
            certificate = Object.assign({}, certificate, window.allcertificate);
        }
        for (const key in certificate) {
            let certificatename = "";
            var select = document.getElementById("certificate");
            console.log(select);
            var allspcldata = [];
            for (var i = 0; i < select.options.length; i++) {
                var obj = {
                    'id': select.options[i].value,
                    'title': select.options[i].textContent,
                };
                allspcldata.push(obj);
            }

            if (certificate.hasOwnProperty(key)) {
                allspcldata.forEach(function (item) {
                    if (key == item.id) {
                        certificatename = item.title;
                    }
                });
                const value = certificate[key];
                str += '<ul>';
                str += '<li class="w-50">' + certificatename + '</li>';
                str += '<li class="w-50 text-end"><button type="button"  id="remove-certificate" data-key="' + key +
                    '" onclick="remove_certificate(this, ' + key +
                    ')"><img src="{{ URL::asset('
                frontend / img / delete - img.png ') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.certificate-content').html(str);
    }

    function remove_certificate(obj, key) {
        if (certificate.hasOwnProperty($(obj).data('key'))) {
            var element = document.getElementById("remove-certificate");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'certificate': key,
                    }
                    let removetype = 'certificate';
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        type: 'POST',
                        url: "{{ url('recruiter/remove') }}/" + removetype,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {

                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
                delete window.allcertificate[$(obj).data('key')];
                delete certificate[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_certifications();
        }
    }
</script>
<script>
    function askWorker(e, type, workerid, jobid) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('recruiter/ask-recruiter-notification') }}",
                data: {
                    'token': csrfToken,
                    'worker_id': workerid,
                    'update_key': type,
                    'job_id': jobid,
                },
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + result.message,
                        time: 5
                    });
                },
                error: function (error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }

    function chatNow(id) {
        localStorage.setItem('nurse_id', id);
    }
    const numberOfReferencesField = document.getElementById('number_of_references');
    numberOfReferencesField.addEventListener('input', function () {
        if (numberOfReferencesField.value.length > 9) {
            numberOfReferencesField.value = numberOfReferencesField.value.substring(0, 9);
        }
    });
    $(document).ready(function () {
        let formData = {
            'country_id': '233',
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-states') }}",
            data: formData,
            dataType: 'json',
            success: function (data) {
                var stateSelect = $('#facility-state-code');
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Facility State Code"
                }));
                $.each(data.data, function (index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    function searchCity(e) {
        var selectedValue = e.value;
        console.log("Selected Value: " + selectedValue);
        let formData = {
            'state_id': selectedValue,
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-cities') }}",
            data: formData,
            dataType: 'json',
            success: function (data) {
                var stateSelect = $('#facility-city');
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Facility City"
                }));
                $.each(data.data, function (index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function getSpecialitiesByProfession(e) {
        var selectedValue = e.value;
        let formData = {
            'profession_id': selectedValue,
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-profession-specialities') }}",
            data: formData,
            dataType: 'json',
            success: function (data) {
                var stateSelect = $('#preferred_specialty');
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Specialty"
                }));
                $.each(data.data, function (index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
</script>
@endsection
