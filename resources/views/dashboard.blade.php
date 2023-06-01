@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
@push('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>

<!-- Add a script tag to initialize the AJAX call -->
<script>
     $('#city-input').keydown(function(event) {
        // Check if the Enter key is pressed
        if (event.keyCode === 13) {
            event.preventDefault(); // Prevent form submission

            // Simulate a click on the "Find" button
            $('#get-weather-btn').click();
        }
    });
     function displayErrorMessage(message) {
        $('#error-message').text('Not Found');
    }


    // Update HTML content with the API response
    function updateWeatherData(response) {
        // Update location
        $('.location').text(response.location.name + ', ' + response.location.region + ', ' + response.location.country);

        // Update current weather
        $('.today .degree .num').text(response.current.temp_c + "°C");
        $('.today .forecast-icon img').attr('src', response.current.condition.icon);
        $('.today .date').text(response.location.localtime.split(' ')[0]); // Extract date from localtime
        $('.today .day').text(getDayName(response.location.localtime.split(' ')[0])); // Extract day from localtime and get the corresponding day name

        // Update other elements with the relevant data from the response
        $('.today .precip').text(response.current.precip_mm + 'mm');
        $('.today .wind').text(response.current.wind_kph + 'km/h');
        $('.today .wind-dir').text(response.current.wind_dir);
        $('.today .humidity').text(response.current.humidity + '%');

        // Update description
        $('.card-body p').text(response.current.condition.text);
    }

    // Function to get the day name from the date string
    function getDayName(dateString) {
        var date = new Date(dateString);
        var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return dayNames[date.getDay()];
    }

    $(document).ready(function() {
        $('#get-weather-btn').click(function() {
            var cityName = $('#city-input').val();

            // AJAX call to the "weather" route with the city name parameter
            $.ajax({
                url: "{{ route('weather') }}",
                method: "POST",
                data: { 
                    city: cityName,
                    _token: "{{ csrf_token() }}" // Include the CSRF token
                },
                success: function(response) {
                    $('#loader').hide();
                        if (response.hasOwnProperty('message')) {
                        displayErrorMessage(response.message);
                    } else {
                        updateWeatherData(response);
                         $('#forecastShow').show();
                    }
                    // Call the function to update HTML with the API response
                },
                error: function(xhr, status, error) {
                    $('#loader').hide();
                    // Handle error case
                    displayErrorMessage("Error: " + 'NoT Found');
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible show fade">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h4>Hi 👋, {{ auth()->user()->name }}</h4>
                        <p>{{ __('You are logged in!') }}</p>
                    </div>
                </div>
                <div id="error-message" style="color: red;">
                    
                </div>
                <div class="card">
                    <div class="card-title">
                        <h3 class="m-3">Weather</h3>
                    </div>
                </div>
                <div class=""></div>
                <div class="container">
                    <form action="" method="" class="find-location">
                        <div class="row">
                            <div class="col-11">
                                 <input type="text" name="city" id="city-input" value="" placeholder="Search any city / country..." required>
                            </div>
                            <div class="col-1 mt-2">
                                 <button class="btn btn-primary" type="button" id="get-weather-btn" >Find</button>
                            </div>
                       </div>
                    </form>

                </div>
                     <div class="forecast-table" id="forecastShow" style="    display: none;">
                    <div class="container">
                        <div class="forecast-container" style="margin-top: 0px;">
                            <div class="today forecast">
                                <div class="forecast-header">
                                    <div class="day">Monday</div>
                                    <div class="date">6 Oct</div>
                                </div> <!-- .forecast-header -->
                                <div class="forecast-content">
                                    <div class="location">New York</div>
                                    <div class="degree">
                                        <div class="num">23<sup>o</sup>C</div>
                                        <div class="forecast-icon">
                                            <img src="images/icons/icon-1.svg" alt="" width="90">
                                        </div>  
                                    </div>
                                    <span class="precip"><img src="images/icon-umberella.png" alt="">20%</span>
                                    <span class="wind"><img src="images/icon-wind.png" alt="">18km/h</span>
                                    <span class="wind-dir"><img src="images/icon-compass.png" alt="">East</span>
                                    <span class="humidity"><img src="images/icon-humidity.png" alt="">64%</span>
                                </div>
                            </div>
                        </div>
                        <div class="card card-body" style="background: #323544;">
                            <h4 style="color:white;">Description</h4>
                            <p style="color:white;">description</p>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
