<?php
// name: track
// route: tracking

use src\Repository\CommandeRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$alink = 5;

    ob_start();
?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

    <style>
        .menu-top {
            position: fixed;
            top:0;
            left: 0;
            width: 100%;
            z-index: 600;
            height: var(--nav-top-height);
            background-color: transparent;
        }
        div.nav-fixed {
            display:none;
        }
        main {
            padding-top: 0;
            height: 100vh;
            padding-bottom: 0;
            padding-right: 0;
            padding-left: var(--w-nav-left);
        }
        .leaflet-top.leaflet-left, .leaflet-bottom.leaflet-right {
            z-index:650;
        }
        .menu-top .input-group {
            background-color: #f8f9fa;
            border-radius: 1.2rem;
            /* padding: .5rem; */
            padding: .2rem;
            width: 40vw;
            color: #fff;
        }
        .menu-top .input-group input {
            background-color: transparent;
            width:calc(100% - 36px);
            border: none;
            padding: 5px;
            color: gray;
            font-size: 14px;
        }
        .menu-top .input-group input:focus {
            box-shadow: none !important;
        }
        .menu-top .input-group .append-element {
            display: inline-block;
            width:34px;
            /* padding-right: 1rem; */
            border: 0;
            background-color: transparent;
            /* border:1px solid; */
        }

        .data-list {
            position:absolute;width:100%;left:0;top:40px;border-radius:.5rem;color:#000;font-weight:300;
            max-height: 300px;overflow: hidden;overflow-y: auto;
        }
        .data-list a {
            cursor: pointer;
            padding: .2rem;
        }
        .data-list a:hover {
            background-color: #f4f4f4;
        }
    </style>
<?php
    $style = ob_get_clean();

    ob_start();
?>

    <div class="menu-top">
        <div class="w-100 h-100 px-3 d-flex flex-wrap justify-content-end align-items-center">
            <?php /*<div class="mt- mb-0 mr-4">
                <div class="input-group d-flex justify-content-between align-items-center border border-ederalab position-relative" id="contain-datalist">
                    <input type="search" placeholder="Adresse" onkeyup="getSearchResult(this);" class="form-control form-control-sm px-2 plou" />
                    <button class="append-element text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                    <div class="data-list bg-white shadow"></div>
                </div>
            </div>*/ ?>
            <span class="d-flex justify-content-between align-items-center mt- h-100">
                <a href="#" class="d-flex justify-content-center align-items-center text-ederalab h-100 mr3 hlink<?= isset($hlink) && $hlink == 1 ? ' active' : ''; ?>" style="width:50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                    </svg>
                </a>
                <a href="<?= $router->getRoutes()->path('parametres'); ?>" class="d-flex justify-content-center align-items-center text-ederalab h-100 hlink<?= isset($hlink) && $hlink == 2 ? ' active' : ''; ?>" style="width:50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                    </svg>
                </a>
            </span>
        </div>
    </div>

    <div class="main w-100 h-100 d-flex justify-content-between">

        <div id="leafletmap" class="w-100 h-100" data-latitude="46.603354" data-longitude="1.8883335"></div>

    </div>

<?php
    $main = ob_get_clean();

    ob_start();
?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        window.onload = function() {
            function loadStreetMap($lat, $lng) {
                // initialisation de la carte
                map = L.map('leafletmap').setView([parseFloat($lat), parseFloat($lng)], 10);

                // chargement des tuiles
                L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                    attribution: 'Map data &copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://openstreetmap.fr/">OSM France</a>',
                    minZoom: 1,
                    maxZoom: 20,
                }).addTo(map);
                
                // Ajout du marqueur
                // marker = L.marker([parseFloat($lat), parseFloat($lng)]).addTo(map);

                L.Routing.control({
                    router: new L.Routing.osrmv1({
                        language: 'fr',
                        // profile: 'car', // car, bike, foot
                    }),
                    geocoder: L.Control.Geocoder.nominatim()
                }).addTo(map)
            }

            function geo_success(position) {
                loadStreetMap(position.coords.latitude, position.coords.longitude)
            }
            function geo_error() {
                console.log("Sorry, no position available.");
            }
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(geo_success, geo_error)
            }
        }
    </script>

<?php
    $script = ob_get_clean();

require 'base.php';