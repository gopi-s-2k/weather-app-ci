<div class="col-12 col-md-4 order-1 order-md-2 mb-4 mb-md-0">
    <div id="req-location" class="card p-4 mb-3 shadow-sm border-0 bg-white" style="display: none;">
        <p class="text-center fw-bold mb-3">Get Weather for your current Location</p>
        <button id="getLocationBtn" class="btn btn-success w-100 fw-bold">Use current location</button>
    </div>

    <div id="usr-loc-weather" class="card shadow-sm border-0" style="display: none;">

        <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center border-0 rounded-top">
            <span id="usr-loc-name">City Name</span>
            <span id="refreshCurrentWeather" class="badge bg-light text-primary cursor-pointer">🗘</span>
        </div>

        <div class="card-body text-center bg-light py-4">
            <div class="d-flex justify-content-center align-items-center mb-1">
                <img id="usr-loc-icon" class="usr-loc-icon" src="https://openweathermap.org/img/wn/04d@2x.png" alt="Weather icon" width="80" height="80">
                <h1 class="display-3 fw-bold mb-0 text-dark text-nowrap"><span id="usr-loc-temp"></span> °</h1>
            </div>
            <p id="usr-loc-desc" class="text-capitalize text-muted fw-semibold mb-0 tracking-wide">Weather Description</p>
        </div>

        <ul class="list-group list-group-flush border-top">
            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                <span class="text-muted small fw-bold">Feels Like</span>
                <span class="fw-bold text-nowrap"><span id="usr-loc-feels-like"></span>°</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                <span class="text-muted small fw-bold">Humidity</span>
                <span class="fw-bold text-nowrap"><span id="usr-loc-humidity"></span>%</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                <span class="text-muted small fw-bold">Wind</span>
                <span class="fw-bold text-nowrap"><span id="usr-loc-wind"></span> m/s</span>
            </li>
        </ul>

    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    function startWithCurrentLocaiton(userAction = false) {
        if (!navigator.geolocation) {
            if (userAction) {
                showErrorAlert("Geolocation is not supported by your browser.");
            }
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let lat = position.coords.latitude;
                let lon = position.coords.longitude;
                getWeatherForLatLon(lat, lon);
            },
            function(error) {
                let errMsg = "";
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errMsg = ("Access denied for location. Allow location access in site-settings");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errMsg = ("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        errMsg = ("Unable to locate you! timed out.");
                        break;
                    default:
                        errMsg = ("An unknown error occurred.");
                        break;
                }
                $("#req-location").show();
                if (userAction) {
                    showErrorAlert(errMsg);
                }
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }
    $(document).ready(function() {
        $('#getLocationBtn,#refreshCurrentWeather').on('click', function() {
            startWithCurrentLocaiton(true);
        });
        startWithCurrentLocaiton();
    });

    var ajaxLatLon = null;
    function getWeatherForLatLon(lat, lon) {
        if(ajaxLatLon !== null){
            ajaxLatLon.abort();
        }
        ajaxLatLon = $.ajax({
            url: "<?= url_to("weather.latlon") ?>",
            type: 'GET',
            data: {
                lat,
                lon
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    processLocaitonResponse(response.data);
                }
            },

            error: function(xhr, status, error) {
                // console.error("AJAX request failed:", status, error);
                let response = xhr.responseJSON;
                showErrorAlert(response.message);
            }
        });
    }

    function processLocaitonResponse(locData) {
        $("#usr-loc-name").text(locData.name);
        weather = locData.weather[0];
        $("#usr-loc-icon").attr("src", `https://openweathermap.org/img/wn/${weather.icon}@2x.png`);
        $("#usr-loc-temp").text(locData.main?.temp ?? "--");
        $("#usr-loc-desc").text(weather?.description ?? "---");
        $("#usr-loc-feels-like").text(locData?.main?.feels_like ?? "--");
        $("#usr-loc-humidity").text(locData?.main?.humidity ?? "--");
        $("#usr-loc-wind").text(locData?.wind?.speed ?? "--");
        $("#usr-loc-weather").show();
        $("#req-location").hide();
    }
</script>
<?= $this->endSection() ?>