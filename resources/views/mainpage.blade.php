@extends('layouts.main')
@section('pageTitle', 'WIFI Finance')
@section('content')
<div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
    <div class="col-md-5 p-lg-5 mx-auto my-5">
    <h1 class="display-4 fw-normal">WIFI Finance</h1>
    <p class="lead fw-normal">Buy and Sell Stocks, view your Portfolio, get the latest trading data here.</p>
    <a class="btn btn-outline-secondary" href="{{ route('register')}}">Sign Up</a>
    </div>
    <div class="product-device shadow-sm d-none d-md-block"></div>
    <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
</div>

<div class="container">

    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading">Check stock information <span class="text-muted">- for free!!</span></h2>
            <p class="lead">Anyone can check their favourite stock for more information for free.</p>
        </div>
        <div class="col-md-5">
            <img id="stockinfo_img" src="./media/Stockinfo.PNG" alt="stockinfo_img">
            {{-- <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="300" height="300" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#eee"/><text x="50%" y="50%" fill="#aaa" dy=".3em">Screenshot1</text></svg> --}}
        </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7 order-md-2">
            <h2 class="featurette-heading">Cool features <span class="text-muted">when you sign up for an account.</span></h2>
            <p class="lead">Add stocks to your watchlist, to always have an eye on them. Buy/Sell stocks, track your portfolio and much more.</p>
        </div>
        <div class="col-md-5 order-md-1">
                <img id="portfolio_img" src="./media/portfolio.PNG" alt="portfolio_img">
                <img id="btns_img" src="./media/btns.PNG" alt="btns_img">
            {{-- <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="300" height="300" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#eee"/><text x="50%" y="50%" fill="#aaa" dy=".3em">Screenshot2</text></svg> --}}
        </div>
    </div>

    <hr class="featurette-divider">
    
</div>
@endsection