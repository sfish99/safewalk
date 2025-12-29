let map, marker;//  Google Map and Marker Instances 


/**
 * Initialize the Google Map and place a marker at the initial walker location.
 * Also sets up a periodic check to update the marker position every 5 seconds.
 */
function initMap() {
    // Initial position using values passed from PHP
    const initialPos = { lat: INITIAL_LAT, lng: INITIAL_LNG };

    // Create the map centered at the initial position
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 16,
        center: initialPos
    });

    // Create a marker at the initial position with the walker's name as title
    marker = new google.maps.Marker({
        position: initialPos,
        map: map,
        title: WALKER_NAME
    });

    // Call updatePosition() every 5 seconds to fetch the latest walker location
    setInterval(updatePosition, 5000);
}

// Fetch the latest walker location from the server and update the map marker
function updatePosition() {
    fetch(`get_walker_location.php?walker_id=${WALKER_ID}`)
        .then(res => res.json())
        .then(data => {
            // If the server returned a valid location
            if (data.success && data.latitude && data.longitude) {
                // Convert string coordinates to float
                const newPos = { 
                    lat: parseFloat(data.latitude),
                    lng: parseFloat(data.longitude) };
                
                marker.setPosition(newPos);// Move the marker to the new position
                map.setCenter(newPos);// Center the map on the new position
            }
        })
        .catch(err => console.error("Error fetching location:", err));// Handle network/server errors
}

window.addEventListener('load', initMap);// Initialize the map once the page has fully loaded
