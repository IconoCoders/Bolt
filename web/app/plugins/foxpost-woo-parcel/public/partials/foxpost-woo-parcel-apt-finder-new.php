<?php
/**
 * @var $apiKey string
 * @var $cssUri string
 * @var $cssVersion string
 * @var $jsUri string
 * @var $jsVersion string
 */
if (!defined('ABSPATH')) {
    die;
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>


    <link rel="stylesheet"
          href="<?php echo $cssUri ?>foxpost-woo-parcel-apt-finder.css?<?php echo $cssVersion ?>"
          type="text/css"/>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"
          type="text/css"/>

    <script
            src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script defer type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_html($apiKey) ?>"></script>


    <script src="<?php echo $jsUri ?>apt-finder.js?<?php echo $jsVersion ?>"></script>

</head>

<body>
<div id="search" class="foxpost_woo_parcel_apt_finder">
    <div class="container-fluid">
        <div class="row search">
            <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 searchmodal">
                <div class="row">
                    <form id="automatakereso_form">
                        <div class="col-xs-12  col-sm-12"><h1>Automatakereső</h1></div>
                        <div class="col-sm-12"><p><strong>Csak írd be a címed, mi pedig mutatjuk, hol van a legközelebbi FOXPOST automata</strong></p></div>
                        <div class="col-sm-12"><input class="form-control" type="text" placeholder="Város, vagy irányítószám"></div>
                        <div class="col-sm-12"><input class="form-control" type="text" placeholder="Utca, házszám"></div>
                        <div class="col-sm-12"><input class="btn btn-primary btn-sm btn-block" type="submit" value="keresés"></div>
                        <div class="col-sm-12" id="automatakereso_result"></div>
                    </form>
                </div>
            </div>

            <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8 map">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div id="mapControl">
                                Nézet:
                                <button id="mapbtn" type="button" class="btn btn-default active">Térkép</button>
                                <button id="listbtn" type="button" class="btn btn-default">Lista</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="googleMap"></div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-7 map">
                <div class="row">
                    <div class="maplist_container">
                        <div class="maplist"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="googleMapModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle">&nbsp;</h2>
                    <button type="button" class="close closesubmodal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="modalContent"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function ($) {
        'use strict';
        $(document).ready(function () {
            $('.closesubmodal').on('click',function(){
                $("#googleMapModal").modal("hide");
            });

            $('#terkep-modal').on('hidden.bs.modal', function () {
                $("#googleMapModal").modal("hide");
            });
        });
    })(jQuery);

</script>

</body>

</html>