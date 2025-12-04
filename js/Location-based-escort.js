let map;
let userMarker;
let destinationMarker;
let routeLine;
const routeTolerance = 50; // מטרים מרוחק מהמטרה / מסלול


function initMap() {
    map = L.map('map').setView([32.0853, 34.7818], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);


    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(updatePosition, showError, {
            enableHighAccuracy: true,
            maximumAge: 1000
        });
    } else {
        alert("הדפדפן שלך לא תומך במיקום.");
    }

    document.getElementById("setDestinationBtn").addEventListener("click", setDestination);

    // autocomplete
    const input = document.getElementById("destination");
    const suggestionsList = document.getElementById("suggestions");

    input.addEventListener("input", function() {
        const query = input.value.trim();
        if (query.length < 3) {
            suggestionsList.innerHTML = "";
            return;
        }

        fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&lang=he`)
        .then(res => res.json())
        .then(data => {
            suggestionsList.innerHTML = "";
            data.features.forEach(feature => {
                const li = document.createElement("li");
                li.textContent = feature.properties.name + ", " + (feature.properties.city || "");
                li.dataset.lat = feature.geometry.coordinates[1];
                li.dataset.lon = feature.geometry.coordinates[0];
                suggestionsList.appendChild(li);

                li.addEventListener("click", () => {
                    input.value = li.textContent;
                    suggestionsList.innerHTML = "";
                    setDestinationCoordinates(li.dataset.lat, li.dataset.lon);
                });
            });
        });
    });

    
        L.Control.geocoder({
            defaultMarkGeocode: false
        }).on('markgeocode', function(e) {
            const center = e.geocode.center;
            setDestinationCoordinates(center.lat, center.lng); // מחובר למסלול
        }).addTo(map);

// עדכון מיקום המשתמשת
function updatePosition(position) {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;
    const accuracy = position.coords.accuracy;

    document.getElementById("status").innerText = `מיקום: ${lat.toFixed(5)}, ${lng.toFixed(5)} (דיוק ±${accuracy} מטר)`;

    if (!userMarker) {
        userMarker = L.marker([lat, lng]).addTo(map).bindPopup("את כאן").openPopup();
        map.setView([lat, lng], 15);
    } else {
        userMarker.setLatLng([lat, lng]);
    }

    checkDeviation(lat, lng);
}

// קביעת יעד
function setDestination() {
    const address = document.getElementById("destination").value;
    if (!address) return alert("אנא הזיני כתובת");

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
    .then(res => res.json())
    .then(data => {
        if (data.length === 0) return alert("כתובת לא נמצאה");

        setDestinationCoordinates(data[0].lat, data[0].lon);
    });
}

function setDestinationCoordinates(lat, lon) {
    if (destinationMarker) map.removeLayer(destinationMarker);
    if (routeLine) map.removeLayer(routeLine);

    destinationMarker = L.marker([lat, lon]).addTo(map).bindPopup("יעד").openPopup();

    // קו מסלול ישיר מהמשתמש ליעד (נכון לעכשיו קו ישר)
    if (userMarker) {
        routeLine = L.polyline([userMarker.getLatLng(), [lat, lon]], {color: 'blue'}).addTo(map);
        map.fitBounds(routeLine.getBounds());
    }
}

// בדיקה אם המשתמש חרג מהמסלול (טולרנס)
function checkDeviation(lat, lng) {
    if (!routeLine) return;

    const lineLatLngs = routeLine.getLatLngs();
    // חישוב מרחק מנקודת משתמש לקו
    const distance = L.GeometryUtil.distanceSegment(map, L.latLng(lat, lng), lineLatLngs[0], lineLatLngs[1]);

    if (distance > routeTolerance) {
        routeLine.setStyle({color: 'red'});
    } else {
        routeLine.setStyle({color: 'blue'});
    }
}

function showError(error) {
    alert(`שגיאת מיקום: ${error.message}`);
}

// להוסיף Leaflet.GeometryUtil
// <script src="https://unpkg.com/leaflet-geometryutil"></script>


window.onload = initMap;
