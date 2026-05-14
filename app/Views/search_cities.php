<div class="col-12 col-md-8 order-2 order-md-1 mb-4">
    <div class="card shadow border border-light-subtle bg-light p-3 p-md-4 rounded-4">
        <form id="form-city-search" method="POST" action="">
            <div class="input-group mb-4 shadow-sm">
                <input type="text" id="city-search-input" class="form-control border-0 py-2" placeholder="Search city name..." />
                <button type="submit" class="btn btn-primary px-4 fw-bold" id="searchBtn">Search</button>
            </div>
        </form>
        <div id="searchResultsList">

            <div id="search-placeholder" class="text-center py-5 text-muted">
                <p class="mb-0 fw-semibold">Search cities to get their weather.</p>
            </div>

            <div id="search-error" class="text-center py-5 text-danger" style="display:none">
                <p class="mb-0 fw-semibold">We couldn't find the city you entered.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->section("scripts") ?>
<script>
    var ajaxCity = null;
    $("#form-city-search").submit(function(event) {
        event.preventDefault();
        if (ajaxCity !== null) {
            ajaxCity.abort();
        }
        let search = $("#city-search-input").val().trim();
        if (!search) {
            return;
        }
        $("#searchResultsList .search-result-card").remove();
        $("#search-placeholder").hide();
        $("#search-error").hide();
        ajaxCity = $.ajax({
            url: "<?= url_to("weather.city") ?>",
            type: 'GET',
            data: {
                "city_name": search
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    processCities(response.data)
                }
            },
            error: function(xhr, status, error) {
                let response = xhr.responseJSON;
                showErrorAlert(response.message);
            }
        });
    })

    function processCities(cities) {
        if (!cities.length) {
            $("#search-error").show();
        }
        listContent = "";
        cities.forEach(city => {
            listContent += renderSearchResult(city);
        });
        $('#searchResultsList').prepend(listContent);
    }

    function renderSearchResult(weatherData) {
        if (!(weatherData.weather && weatherData.weather.length)) {
            return "";
        }
        let icon = weatherData.weather[0].icon;
        let temp = Math.round(weatherData.main.temp);
        let cityName = weatherData.city_name;
        let country = weatherData.sys.country;
        let description = weatherData.weather[0].description;
        let humidity = weatherData.main.humidity;
        let feels_like = weatherData.main.feels_like;
        let wind = weatherData.wind.speed;

        let localTimeMs = (weatherData.dt + weatherData.timezone) * 1000;
        let dateObj = new Date(localTimeMs);

        let formattedDate = dateObj.toLocaleDateString('en-US', {
            timeZone: 'UTC',
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        });
        let formattedTime = dateObj.toLocaleTimeString('en-US', {
            timeZone: 'UTC',
            hour: '2-digit',
            minute: '2-digit'
        });
        let displayDateTime = formattedDate + ' at ' + formattedTime;

        return `
            <div class="card mb-3 shadow-sm border-0 overflow-hidden search-result-card">
                <div class="row g-0 align-items-stretch">
                    <div class="col-3 d-flex flex-column align-items-center justify-content-center p-2">
                        <img src="https://openweathermap.org/img/wn/${icon}@2x.png" alt="Weather icon" class="img-fluid mb-1 usr-loc-icon" style="width: 40px;">
                        <h4 class="fw-bold mb-0">${temp}°</h4>
                    </div>
                    
                    <div class="col-9">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="card-title fw-bold mb-0 text-dark">${cityName}</h5>
                                <span class="badge bg-secondary text-uppercase">${country}</span>
                            </div>
                            <p class="mb-1 small text-primary fw-bold">${displayDateTime}</p>
                            <p class="card-text text-muted text-capitalize mb-2 small fw-semibold">
                                ${description}
                            </p>
                            
                            <div class="d-flex justify-content-start gap-3 small text-muted">
                                <span><strong>Humidity:</strong> ${humidity}%</span>
                                <span><strong>Wind:</strong> ${wind} m/s</span>
                                <span><strong>Feels Like:</strong> ${feels_like} m/s</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
</script>
<?= $this->endSection() ?>