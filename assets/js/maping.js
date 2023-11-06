var map = null;
var marker = null;

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
    marker = L.marker([parseFloat($lat), parseFloat($lng)]).addTo(map);
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

const getSearchResult = function (e) {
    adresse = e.value
    if(adresse != '') {
        document.querySelector('.data-list').innerHTML = '<div style="text-align:center;"><img src="../assets/images/site/icons8-dots-loading.gif" width="30" height="30"></div>'
        fetch('https://nominatim.openstreetmap.org/search?q=' + adresse + '&format=json&addressdetails=1&polygon_svg=1&limit=25', {
            method: 'GET'
        })
        .then(response => response.json())
        .then(response => {
            if(response.length != 0) {
                document.querySelector('.data-list').innerHTML = ''
                for(resp in response) {
                    document.querySelector('.data-list').insertAdjacentHTML('beforeend', '<a type="button" class="btn-block" onclick="updateMarker(this)" data-lat="' + response[resp].lat + '" data-lon="' + response[resp].lon + '">' + response[resp].display_name + '</a>')
                }
            }
        })
        .catch(error => console.log(error))
    } else {
        document.querySelector('.data-list').innerHTML = ''
    }
}

const updateMarker = function (e) {
    const addr = e.textContent;
    const lat = e.getAttribute('data-lat');
    const lon = e.getAttribute('data-lon');
    marker = L.marker([lat, lon]).addTo(map);
    marker.bindPopup(addr)
    document.querySelector('.plou').value = addr
    document.querySelector('.data-list').innerHTML = ''

    setTimeout(() => {
        alert('Si vous ne retrouvez pas le marqueur pour lequel pour avez sélectionné une adresse, veuillez dézoomer pour l\'apercevoir.')
    }, 5000)
}
