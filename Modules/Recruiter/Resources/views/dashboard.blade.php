@extends('recruiter::layouts.main')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<main style="padding-top: 170px" class="ss-main-body-sec">
    <div class="container">

        <div class="row mb-5">
            <div class="col-md-12">
                <div class="w-75 ss-job-prfle-sec m-auto p-5">
                    <h3 class="ss-color-pink font-weight-bold">Application stages</h3>
                    <canvas id="recruiterStats"></canvas>
                </div>
            </div>
            <div class="col-md-8">
            </div>
            <div onclick="window.location='{{ route('recruiter-opportunities-manager') }}';" style="cursor: pointer;" class="col-md-4">
                <div class="ss-rec-start-rec-div-sec">
                    <h6>Post a job</h6>
                    <a><img src="{{URL::asset('recruiter/assets/images/plus-icon.png')}}" /></a>
                </div>
            </div>
        </div>

    </div>

</main>
<script>
    let values = <?php echo json_encode($statusCounts); ?>;

    
    let yValues = values;
    
    let max = Math.max(...yValues);
    console.log(yValues);
    const ctx = document.getElementById('recruiterStats');
   
    const xValues = ['New', 'Screening', 'Submitted', 'Offered', 'Onboarding', 'Working'];
    
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: xValues,
            datasets: [{
                data: yValues,
                backgroundColor: [
                    '#A8DFF1',
                    '#AED2F9',
                    '#FF6370',
                    '#73B0CD',
                    '#66B2FF',
                ]

            }]
        },
        
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: ""
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: max,
                        stepSize: 1
                    }
                }]
            }
        }
    });
    
</script>
@endsection