

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid my_fluid">
    <a class="navbar-brand d-inline-flex" href="{{ url('/') }}">
        <img width="190" src="{{ asset('frontend/assets/images/logoOneLine.png') }}" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Explore Jobs</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">For Employers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">For Recruiters</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="aboutus">About Us</a>
            </li>
        </ul>
        <ul class="navbar-nav align-items-center">
            <li class="nav-item">
                <a class="nav-link me-3" href="#">Login</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-dark px-4" href="">Join GoodWork</a>
            </li>
        </ul>
    </div>
  </div>
</nav>