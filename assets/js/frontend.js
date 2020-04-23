(function($) {
    "use strict";

    $(document).ready(function() {
        novel_covid19_frontend.ready();
    });

    $(window).load(function() {
        novel_covid19_frontend.load();
    });

    var novel_covid19_frontend = (window.$novel_covid19_frontend = {
        /**
         * Call functions when document ready
         */
        ready: function() {
            this.map();
            this.chart();
        },

        /**
         * Call functions when window load.
         */
        load: function() {},

        map: function() {
            var data = novel_covid19.countries;
            for (var i = 0; i < data.length; i++) {
                var $country = $('.covid-map [title="' + data[i].country + '"]');
                var flag = data[i].countryInfo.flag;
                var level = 0;
                if ($country.length) {
                    var cases = data[i].cases;
                    var deaths = data[i].deaths;
                    var todaydeaths = data[i].todayDeaths;
                    var todayCases = data[i].todayCases;
                    var active = data[i].active;
                    var critical = data[i].critical;
                    if (cases <= 10) {
                        level = 1;
                    }
                    if (cases > 10 && cases <= 100) {
                        level = 2;
                    }
                    if (cases > 100 && cases <= 999) {
                        level = 3;
                    }
                    if (cases > 1000 && cases <= 9999) {
                        level = 4;
                    }
                    if (cases > 10000) {
                        level = 5;
                    }
                    $country.attr("data-level", level);
                    $country.attr("data-cases", cases);
                    $country.attr("data-todayCases", todayCases);
                    $country.attr("data-deaths", deaths);
                    $country.attr("data-todaydeaths", todaydeaths);
                    $country.attr("data-active", active);
                    $country.attr("data-critical", critical);
                    $country.attr("data-flag", flag);
                }
                console.log(data[i]);
            }

            var $description = $(".covid-map .covid-tooltip");
            var confirmed_label = $description.attr("data-confirmed");
            var todayCases_label = $description.attr("data-todayCases");
            var deaths_label = $description.attr("data-deaths");
            var todaydeaths_label = $description.attr("data-todaydeaths");
            var active_label = $description.attr("data-active");
            var critical_label = $description.attr("data-critical");
            var flag = $description.attr("data-flag");

            $(".covid-map path")
                .mouseenter(function() {
                    var cases = $(this).attr("data-cases") ?
                        $(this).attr("data-cases") :
                        0;
                    var todayCases = $(this).attr("data-todayCases") ?
                        $(this).attr("data-todayCases") :
                        0;
                    var deaths = $(this).attr("data-deaths") ?
                        $(this).attr("data-deaths") :
                        0;
                    var todaydeaths = $(this).attr("data-todaydeaths") ?
                        $(this).attr("data-todaydeaths") :
                        0;
                    var active = $(this).attr("data-active") ?
                        $(this).attr("data-active") :
                        0;
                    var critical = $(this).attr("data-critical") ?
                        $(this).attr("data-critical") :
                        0;
                    var flag = $(this).attr("data-flag") ?
                        '<img width="20" src="' + $(this).attr("data-flag") + '"/>' :
                        "";
                    $description.addClass("active");
                    $description.html(
                        '<div class="covid-flag">' +
                        flag +
                        "</div>" +
                        '<p class="tt-title">' +
                        $(this).attr("title") +
                        "</p>" +
                        '<table class="table-tooltip"><tr><td>' +
                        confirmed_label +
                        " :</td><td>" +
                        cases +
                        ' <span class="text-danger">(+' +
                        todayCases +
                        ")</span></td></tr>" +
                        "<tr><td>" +
                        deaths_label +
                        " :</td><td>" +
                        deaths +
                        ' <span class="text-danger">(+' +
                        todaydeaths +
                        ")</span></td></tr>" +
                        "<tr><td>" +
                        active_label +
                        " :</td><td>" +
                        active +
                        "</td></tr>" +
                        "<tr><td>" +
                        critical_label +
                        " :</td><td>" +
                        critical +
                        "</td></tr></table>"
                    );
                })
                .mouseleave(function() {
                    $description.removeClass("active");
                });

            $(".covid-map").on("mousemove", function(e) {
                $description.css({
                    left: e.offsetX + 0,
                    top: e.offsetY - 115,
                });
            });
        },

        chart: function() {
            var ctx = $(".covid-chart canvas");
            var data = novel_covid19.history;
            var labels = Object.keys(data.cases);
            var cases = Object.values(data.cases);
            var deaths = Object.values(data.deaths);
            var recovered = Object.values(data.recovered);
            $(".covid-chart canvas").each(function(index, value) {
                var label_confirmed = $(this).data("confirmed");
                var label_deaths = $(this).data("deaths");
                var label_recovered = $(this).data("recovered");
                var chartType = $(this).data("type");
                var country = $(this).data("country");
                if (country) {
                    var thisCountry = $(this).data("json");
                    cases = Object.values(thisCountry.timeline.cases);
                    deaths = Object.values(thisCountry.timeline.deaths);
                    recovered = Object.values(thisCountry.timeline.recovered);
                }

                new Chart($(this), {
                    type: chartType,
                    data: {
                        labels: labels,
                        datasets: [{
                                label: label_confirmed,
                                borderColor: "#EF5350",
                                backgroundColor: "#EF5350",
                                data: cases,
                                fill: false,
                                pointRadius: 1,
                                pointHoverRadius: 5,
                            },
                            {
                                label: label_deaths,
                                borderColor: "#515A5A",
                                backgroundColor: "#515A5A",
                                data: deaths,
                                fill: false,
                                pointRadius: 1,
                                pointHoverRadius: 5,
                            },
                            {
                                label: label_recovered,
                                borderColor: "#2ECC71",
                                backgroundColor: "#2ECC71",
                                data: recovered,
                                fill: false,
                                pointRadius: 1,
                                pointHoverRadius: 5,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            position: "nearest",
                            mode: "index",
                            intersect: true,
                        },
                    },
                });
            });
        },
    });

    $(document).ready(function() {
        $("#worldwide").DataTable({
            order: [
                [1, "desc"]
            ],
            scrollX: true,
        });
    });
})(jQuery);