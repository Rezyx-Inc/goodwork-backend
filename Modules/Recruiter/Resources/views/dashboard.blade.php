@extends('recruiter::layouts.main')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<main style="padding-top: 170px" class="ss-main-body-sec">
    <div class="container">

        <!--------Ghraph area------->
        <!-- <div class="ss-home-graph-main-sec">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ss-home-graph-div1">
                        <img src="{{URL::asset('frontend/img/home-graph-1.png')}}" />
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="ss-home-graph-div2">
                        <img src="{{URL::asset('frontend/img/home-graph-2.png')}}" />
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="ss-home-graph-div3">
                        <img src="{{URL::asset('frontend/img/home-graph-3.png')}}" />
                    </div>
                </div>

            </div>
        </div> -->
        <!-- <canvas id="myChart" style="width:100%;max-width:600px"></canvas> -->
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="w-75 ss-job-prfle-sec m-auto p-5">
                    <h3 class="ss-color-pink font-weight-bold">Application stages</h3>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <div class="ss-rec-start-rec-div-sec">
                    <h6>Start Posting Your Job Request</h6>
                    <a href="{{ route('recruiter-application') }}"><img src="{{URL::asset('recruiter/assets/images/plus-icon.png')}}" /></a>
                </div>
            </div>
        </div>

    </div>

</main>
<script>
    let values = <?php echo json_encode($statusCounts); ?>;
    console.log(values);
    let yValues = values.split(",");
    const ctx = document.getElementById('myChart');
    // const yValues = [55, 49, 44, 24, 15];
    const xValues = ['New', 'Offered', 'Onboard', 'Working', 'Done'];

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
        // options: {
        //     scales: {
        //         y: {
        //             beginAtZero: true
        //         }
        //     }
        // }
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: ""
            }
        }
    });
</script>
@endsection