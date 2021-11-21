@extends('layouts.main')
@section('pageTitle', 'Stock Info')
@section('content')
    <h1>Info result</h1>
    <h3>Price now of {{$ticker_name}}: {{ $now_price }}$</h3>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                
                <form method="POST" action="{{ route('info.result') }}">
                    @csrf

                    <div class="form-group row mb-2">
                        <div class="col-md-6">
                            <input id="ticker" type="ticker" class="form-control @error('ticker') is-invalid @enderror" name="ticker" value="{{ old('ticker') }}" required autocomplete="ticker" autofocus>
                            @error('ticker')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                </form>
                
        </div>
    </div>
    <p>{{ $description }}</p>

    <div class="container">
        {{-- <button type="submit" class="btn btn-outline-danger fa fa-heart"></button> --}}
        {{-- <button type="submit" class="btn btn-outline-secondary fa fa-heart"></button> --}}
    </div>
    @auth
        <div class="container">
            <form class="addWatchlist" action="{{ route('info.watchlist')}}" method="POST" data-ticker="{{$ticker}}">
                @csrf
                <input type="hidden" name="ticker" value="{{$ticker}}">
                <button type="submit" id="btnHeart" class="btn {{ $user_has_ticker->isEmpty() ? 'btn-outline-secondary' : 'btn-outline-danger'}} fa fa-heart"></button>
            </form>
        </div>

        @can('user_verified_gate')
            <div class="container">
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="open">Open Modal</button>

                <div id="contact"><button type="button" class="btn btn-info btn" data-toggle="modal" data-target="#contact-modal">Show Contact Form</button></div>
<div id="contact-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Contact Form</h3>
			</div>
			<form id="contactForm" name="contact" role="form">
				<div class="modal-body">				
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" name="name" class="form-control">
					</div>
					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" name="email" class="form-control">
					</div>
					<div class="form-group">
						<label for="message">Message</label>
						<textarea name="message" class="form-control"></textarea>
					</div>					
				</div>
				<div class="modal-footer">					
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<input type="submit" class="btn btn-success" id="submit">
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary buybtn" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
  </button>
  
  


            </div>
        @endcan
        
    @endauth

    <div class="container">
        <canvas id="myChart"></canvas>
    </div>
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif

        
        {{-- const labels = [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                ]; --}}
                
        const labels = <?php echo $dates;?>;
        const data = {
            labels: labels,
            datasets: [{
            label: 'Price Chart',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            {{-- data: [0, 10, 5, 2, 20], --}}
            
            data: {{ $close_prices }}
            }]
        };
        const config = {
            type: 'line',
            data: data,
            options: {}
        };
        var myChart = new Chart(
                    document.getElementById('myChart'),
                    config
                    );
@endsection