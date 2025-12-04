let map, userMarker, destinationMarker, routeLine;
const routeTolerance = 50; // מטרים מרוחק מהמטרה / מסלול

function initMap() {
    // יצירת מפה
    map = L.map('map').setView([32.0853, 34.7818], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // מיקום המשתמש
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(updatePosition, showError, {
            enableHighAccuracy: true,
            maximumAge: 1000
        });
    } else {
        alert("הדפדפן שלך לא תומך במיקום.");
    }

    // הגדרת אירוע לכפתור קביעת יעד
    document.getElementById("setDestinationBtn").addEventListener("click", setDestination);

    // Autocomplete
    const input = document.getElementById("destination");
    const suggestionsList = document.getElementById("suggestions");

    input.addEventListener("input", function() {
        const query = input.value.trim();
        if (query.length < 3) { suggestionsList.innerHTML = ""; return; }

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

    // Geocoder במפה
    L.Control.geocoder({
        defaultMarkGeocode: false
    }).on('markgeocode', function(e) {
        const center = e.geocode.center;
        input.value = e.geocode.name || ""; // ממלא את השדה
        setDestinationCoordinates(center.lat, center.lng); // יוצר marker וקו
    }).addTo(map);
}

// עדכון מיקום המשתמש
function updatePosition(position) {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;
    const accuracy = position.coords.accuracy;


    if (!userMarker) {
        userMarker = L.marker([lat, lng]).addTo(map).bindPopup("את כאן").openPopup();
        map.setView([lat, lng], 15);
    } else {
        userMarker.setLatLng([lat, lng]);
    }

    // עדכון הקו אם יש יעד
    if (destinationMarker) drawRouteLine();
}

// קביעת יעד דרך שדה כתובת
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

// פונקציה שמקבלת קואורדינטות ומציירת Marker וקו
function setDestinationCoordinates(lat, lon) {
    if (destinationMarker) map.removeLayer(destinationMarker);
    if (routeLine) map.removeLayer(routeLine);

    destinationMarker = L.marker([lat, lon]).addTo(map).bindPopup("יעד").openPopup();
    drawRouteLine();
}

// יצירת קו מסלול מהמשתמש ליעד
function drawRouteLine() {
    if (!userMarker || !destinationMarker) return;
    if (routeLine) map.removeLayer(routeLine);

    routeLine = L.polyline([userMarker.getLatLng(), destinationMarker.getLatLng()], {color: 'blue'}).addTo(map);
    map.fitBounds(routeLine.getBounds());
}

// בדיקה אם המשתמש חרג מהמסלול
function checkDeviation(lat, lng) {
    if (!routeLine) return;
    const lineLatLngs = routeLine.getLatLngs();
    const distance = L.GeometryUtil.distanceSegment(map, L.latLng(lat, lng), lineLatLngs[0], lineLatLngs[1]);
    routeLine.setStyle({color: distance > routeTolerance ? 'red' : 'blue'});
}

function showError(error) {
    alert(`שגיאת מיקום: ${error.message}`);
}

window.onload = initMap;
