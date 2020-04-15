var automatak = [];
var helyszinek = [];
var searchmarker;

(function ($) {
    $(document).ready(function () {

        var map = null;

        var rad = function (x) {
            return x * Math.PI / 180;
        };
        var log = function (data) {
            if (typeof console !== "undefined") {
                console.log(data);
            }
        }

        var getGeoDistance = function (p1, p2) {
            if (!p1 || !p2) {
                log('Missing data');
                return 0;
            }
            var R = 6378137; // Earth’s mean radius in meter
            var dLat = rad(p2.lat() - p1.lat());
            var dLong = rad(p2.lng() - p1.lng());
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) *
                Math.sin(dLong / 2) * Math.sin(dLong / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;
            return d; // returns the distance in meter
        };

        function clearResult() {
            $("#automatakereso_result").html('<br/><br/><h1 class="searchresult">Legközelebbi automaták</h1>');

        }

        function addToResult(egyautomata, keresespos) {
            var item = $('<div></div>');
            item.append($("<a class='btn btn-primary btn-sm btn-block'></a>").text(egyautomata.data.name).click(function () {
                new google.maps.event.trigger(egyautomata.marker, 'click');
            }));

            item.append($('<h4></h4>').text('Távolság: ' + Math.ceil(getGeoDistance(keresespos, egyautomata.pos) / 1000) + ' km'));
            item.append($('<h4></h4>').text(egyautomata.data.group));
            item.append($('<h4></h4>').text(egyautomata.data.address));
            item.append("<br/>");
            $("#automatakereso_result").append(item);

            if (searchmarker) {
                searchmarker.setPosition(keresespos);
            } else {
                searchmarker = new google.maps.Marker({
                    map: map,
                    icon: './images/markerblue.png',
                    animation: google.maps.Animation.BOUNCE
                });
            }
        }

        function addressToCoordinate(address, cb) {
            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({'address': address}, function (result, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var loc = result[0].geometry.location;
                    cb(loc);
                } else {
                    cb(null);
                }
            });
        }

        function mapResultGenerator(loc) {
            //how many markers should I show;
            var how_many_to_show = 3;

            //sort by distance
            automatak = automatak.sort(function (a, b) {
                return getGeoDistance(loc, a.pos) - getGeoDistance(loc, b.pos);
            });

            //center map
            var bounds = new google.maps.LatLngBounds();

            //show result + center map
            clearResult();
            for (var i = 0; i < how_many_to_show; i++) {
                addToResult(automatak[i], loc);
                bounds.extend(automatak[i].pos);
            }

            map.fitBounds(bounds);
        }

        /*Map or list button*/
        $('#mapbtn').on("click", function () {
            $(this).addClass('active');
            $('#listbtn').removeClass('active');
            $('#googleMap').fadeIn(400);
            $('.maplist').fadeOut(400);

            var center = map.getCenter();
            map.setCenter(center);
            //resizeMap();

            google.maps.event.trigger(map, "resize");


        });

        $('#listbtn').on("click", function () {
            $(this).addClass('active');
            $('#mapbtn').removeClass('active');

            $('.maplist').fadeIn(400);
            $('#googleMap').fadeOut(400);
        });

        $("#automatakereso_form").submit(function (e) {
            e.preventDefault();

            var addr = "Magyarország, ";

            $(this).find('input[type=text]').each(function (i, item) {
                if ($(item).val() === '') {
                    return;
                }
                if (addr !== '') {
                    addr += ' ';
                }
                addr += $(item).val();
            });
            addressToCoordinate(addr, mapResultGenerator);
            return false;
        });

        /*Google Maps*/
        var isDraggable = true;

        if ($('#mobil').css('display') === 'block') {
            isDraggable = false;
        }

        function initialize() {

            // $("#terkep-modal").on("shown.bs.modal", function() {
            //     google.maps.event.trigger(map, 'resize');
            // }).on("hide.bs.modal", function() {
            //
            // });

            var mapProp = {
                center: new google.maps.LatLng(47.4856034, 19.0562112),
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROAD,
                disableDefaultUI: false,
                scrollwheel: isDraggable
            };

            map = new google.maps.Map(document.getElementById("googleMap"), mapProp);


            $.getJSON("https://cdn.foxpost.hu/foxpost_terminals_extended_v3.json", function (data) {
                $.each(data, function (key, value) {

                    var place_id = value.operator_id;
                    var latLng = new google.maps.LatLng(value.geolat, value.geolng);
                    var marker = new google.maps.Marker({
                        position: latLng,
                        map: map,
                        icon: '/wp-content/plugins/foxpost-woo-parcel/public/img/marker.png',

                        title: value.name
                    });

                    $('.maplist').append('<h1>' + value.name + '</h1>');

                    var listcontent = "Hely: " + value.group + "<br/>";

                    var open_monday = value.open.hetfo || '';
                    var open_tuesday = value.open.kedd || '';
                    var open_wednesday = value.open.szerda || '';
                    var open_thursday = value.open.csutortok || '';
                    var open_friday = value.open.pentek || '';
                    var open_saturday = value.open.szombat || '';
                    var open_sunday = value.open.vasarnap || '';

                    listcontent += "Cím: " + value.address + "<br/>";
                    if (value.open_monday !== undefined) {
                        var bontas =
                            "<br>Hétfő: " + open_monday +
                            "<br>Kedd: " + open_tuesday +
                            "<br>Szerda: " + open_wednesday +
                            "<br>Csütörtök: " + open_thursday +
                            "<br>Péntek: " + open_friday +
                            "<br>Szombat: " + open_saturday +
                            "<br>Vasárnap: " + open_sunday +
                            "<br>";
                        listcontent += "Nyitvatartás: " + bontas + "<br/>";
                    }
                    if (value.findme !== undefined) {
                        listcontent += value.findme + '<br>';
                    }
                    if (value.arrival !== undefined) {
                        listcontent += '</b><br>';
                    }
                    helyszinek.push({
                        "Név": value.name,
                        "Hely": value.group,
                        "Cím": value.address,
                        "Hétfő": open_monday,
                        "Kedd": open_tuesday,
                        "Szerda": open_wednesday,
                        "Csütörtök": open_thursday,
                        "Péntek": open_friday,
                        "Szombat": open_saturday,
                        "Vasárnap": open_sunday,
                        "Megjegyzés": value.findme
                    });

                    $('.maplist').append('<p>' + listcontent + '</p>');
                    automatak.push({
                        pos: latLng,
                        marker: marker,
                        data: value
                    });

                    google.maps.event.addListener(marker, 'click', function (e) {
                        $('#googleMapModal').modal('show');
                        $('#modalTitle').html(value.name);
                        var content = "Hely: " + value.group + "<br/>";
                        content += "Cím: " + value.address + "<br/>";
                        if (value.open !== undefined) {

                            var bontas =
                                "<br>Hétfő: " + open_monday +
                                "<br>Kedd: " + open_tuesday +
                                "<br>Szerda: " + open_wednesday +
                                "<br>Csütörtök: " + open_thursday +
                                "<br>Péntek: " + open_friday +
                                "<br>Szombat: " + open_saturday +
                                "<br>Vasárnap: " + open_sunday +
                                "<br>";

                            content += "Nyitvatartás: " + bontas + "<br/>";
                        }
                        if (value.findme !== undefined) {
                            content += value.findme + '<br>';

                        }
                        if (value.arrival !== undefined) {
                            content += '</b><br>';
                        }
                        content += '<button data-val="' + place_id + '" class="button btn btn-default selectaddress">Kiválasztom</button>';

                        $('#modalContent').html(content);

                        $(".selectaddress").click(function () {

                            var selectedAptId = $(this).data('val');
                            $("#googleMapModal").modal("hide");
                            $("#terkep-modal").modal("hide");
                            parent.foxpost_woo_parcel_close_dialog(selectedAptId);
                            // if ($("#reg-modal").data('bs.modal') && $("#reg-modal").data('bs.modal').isShown) {
                            //     $('.mapaddresshome').val(selectedAptId);
                            // } else {
                            //     $('.mapaddress').val(selectedAptId);
                            // }
                        });

                    });

                });


            });
            // changeMapStyles(map);
        }

        //Map Style
        function changeMapStyles(map) {

            var hueColor = "#62bba5";

            var featureOpts = [{
                stylers: [
                    {hue: hueColor},
                    {saturation: 0},
                    {lightness: -10}
                ]
                // },{
                //     elementType: "labels",
                //     stylers: [{
                //         visibility: "off"
                //     }]
                // },{
                //     featureType: "road",
                //     stylers: [{
                //         visibility: "on"
                //     }, {
                //         color: roadColor
                //     }]
                // },{
                //     featureType: "water",
                //     stylers: [{
                //         visibility: "on"
                //     }, {
                //         color: waterColor
                //     }]
                // },
                // {
                //     featureType: "poi"
                //     ,stylers: [{
                //         visibility: "on"
                //     }, {
                //         color: parkColor
                //     }]
                // },
                // {
                //     featureType: "landscape",
                //     stylers: [{
                //         visibility: "on"
                //     }, {
                //         color: landscapeColor
                //     }]
            }];

            map.setOptions({
                styles: featureOpts
            });
        }

        if ($('#googleMap').length) {
            google.maps.event.addDomListener(window, "load", initialize);

            google.maps.event.addDomListener(window, "resize", function () {
                if (map && 'getCenter' in map) {

                    var center = map.getCenter();

                    //resizeMap();

                    google.maps.event.trigger(map, "resize");
                    map.setCenter(center);
                }

            });
        }

        function resizeMap() {
            var h = window.innerHeight;
            var w = window.innerWidth;
            $("#googleMap").height(h);
        }

    });
})(jQuery);
