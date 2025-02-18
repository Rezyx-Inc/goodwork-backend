@section('js')

<script type="text/javascript">
    
    function addJobCards(jobs){

        const locationIcon = @json(asset('frontend/img/location.png'));
        const calendarIcon = @json(asset('frontend/img/calendar.png'));
        const dollarIcon = @json(asset('frontend/img/dollarcircle.png'));
        
        for(job of jobs.jobs){
            
            var newCard =
           '<div class="ss-job-prfle-sec job-item" data-id="'+job.id+'" >'+
                '<div class="row d-flex align-items-center">'+
                    '<p class="col-12 text-end d-lg-none" style="padding-right:20px;">'+
                        '<span>+'+ job.offer_count +' Applied</span>'+
                    '</p>'+
                    '<div class="col-12 d-flex justify-content-between justify-content-lg-start col-lg-10">'+
                        '<div class="infos_like_ul">'+
                            (job.profession ? 
                                '<li><a href="#"><svg style="vertical-align: sub;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16"> <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" /> </svg> '+ job.profession +'</a></li>' 
                            : '') +
                            '</div>'+
                        
                            '<div class="infos_like_ul">'+
                            (job.preferred_specialty ? 
                                '<li><a href="#"> '+ job.preferred_specialty +'</a></li>' 
                            : '') +
                        '</div>'+
                    '</div>'+
                    '<p class="d-none d-lg-block col-lg-2 text-center" style="padding-right:20px;">'+
                        '<span>+'+ job.offer_count +' Applied</span>'+
                    '</p>'+
                '</div>'+

                '<div class="row mt-2 mt-md-0 d-flex align-items-center">'+
                    '<div class="col-7">'+
                        '<ul>';
                        if(job.job_city && job.job_state){
                            newCard += `<li><a href="#"><img class="icon_cards" src=${locationIcon}>`+ job.job_city +', '+ job.job_state +'</a></li>';
                        }
                        newCard += '</ul>'+
                    '</div>'+
                    '<div class="col-5 d-flex justify-content-end">'+
                        '<ul>';
                        if(job.preferred_assignment_duration && job.terms === 'Contract'){
                            newCard += `<li><a href="#"><img class="icon_cards" src=${calendarIcon}>`+ job.preferred_assignment_duration +' wks / assignment</a></li>';
                        }
                        if(job.hours_per_week){
                            newCard += `<li><a href="#"><img class="icon_cards" src=${calendarIcon}>`+ job.hours_per_week +' hrs/wk</a></li>';
                        }
                        newCard += '</ul>'+
                    '</div>'+
                '</div>'+

                '<div class="row d-flex align-items-center">';
                if(job.preferred_shift_duration){
                    newCard += '<div class="col-12 col-md-6 col-lg-6 d-flex justify-content-between justify-content-md-start ">'+
                        '<div class="infos_like_ul">';
                        if(job.preferred_shift_duration === '5x8 Days' || job.preferred_shift_duration === '4x10 Days'){
                            newCard += '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-brightness-alt-high-fill" viewBox="0 0 16 16">'+
                                '<path d="M8 3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 3m8 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5m-13.5.5a.5.5 0 0 0 0-1h-2a.5.5 0 0 0 0 1z"/>'+
                            '</svg>';
                        } else if(job.preferred_shift_duration === '3x12 Nights or Days'){
                            newCard += '<svg style="vertical-align: text-bottom;" xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-moon-stars" viewBox="0 0 16 16">'+
                                '<path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.73 1.73 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.73 1.73 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.73 1.73 0 0 0 1.097-1.097z"/>'+
                            '</svg>';
                        }
                        newCard += job.preferred_shift_duration +
                        '</div>';
                        if(job.actual_hourly_rate){
                            newCard += '<div class="infos_like_ul">'+
                                '$'+ job.actual_hourly_rate +'/hr'+
                            '</div>';
                        }
                    newCard += '</div>'+
                    '<div class="col-12 mt-3 mt-md-0 col-md-6 col-lg-6 d-flex justify-content-between justify-content-md-end">'+
                        (job.weekly_pay ? 
                            '<div class="infos_like_ul">'+
                                '$'+ job.weekly_pay +'/wk'+
                            '</div>' : '') +
                        (job.weekly_pay ? 
                            '<div class="infos_like_ul" style="font-weight: 600;">'+
                                '$'+ (job.weekly_pay * 4 * 12) +'/yr'+
                            '</div>' : '') +
                    '</div>';
                } else {
                    newCard += '<div class="col-12 d-flex justify-content-end">'+
                        '<ul>';
                        if(job.actual_hourly_rate){
                            newCard += '<li>$ '+ job.actual_hourly_rate +'/hr</li>';
                        }
                        if(job.weekly_pay){
                            newCard += '<li>$ '+ job.weekly_pay +'/wk</li>';
                            newCard += '<li style="font-weight: 600;">$ '+ (job.weekly_pay * 4 * 12) +'/yr</li>';
                        }
                        newCard += '</ul>'+
                    '</div>';
                }
                newCard += '</div>'+

                '<div class="row w-100">'+
                    '<div class="col-6 d-flex justify-content-start">';
                    if(job.as_soon_as === true){
                        newCard += '<p class="col-12" style="padding-bottom: 0px; padding-top: 8px;">As soon as possible</p>';
                    }
                newCard += '</div>'+
                '<div class="col-6 d-flex justify-content-end">';
                    if(job.urgency === 'Auto Offer' || job.as_soon_as === true){
                        newCard += '<p class="col-2 text-center" style="padding-bottom: 0px; padding-top: 8px;">Urgent</p>';
                    }
                newCard += '</div>'+
                '</div>'+
            '</div>';

            var container = $('#job-item-container');

            if (container.length === 0) {
                console.error("Job list container not found!");
                return;
            }

            container.append(newCard);

        }
    }
</script>