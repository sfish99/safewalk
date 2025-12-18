let map, marker;

function initMap() {
    const initialPos = { lat: INITIAL_LAT, lng: INITIAL_LNG };
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 16,
        center: initialPos
    });
    marker = new google.maps.Marker({
        position: initialPos,
        map: map,
        title: WALKER_NAME
    });

    // בדיקה כל 5 שניות
    setInterval(updatePosition, 5000);
}

function updatePosition() {
    fetch(`get_walker_location.php?walker_id=${WALKER_ID}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.latitude && data.longitude) {
                const newPos = { lat: parseFloat(data.latitude), lng: parseFloat(data.longitude) };
                marker.setPosition(newPos);
                map.setCenter(newPos);
            }
        })
        .catch(err => console.error("Error fetching location:", err));
}

window.addEventListener('load', initMap);
