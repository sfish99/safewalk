// walker_share_location.js

let map, marker;// Google Map instance and user location marker

let watchId = null;// ID returned by navigator.geolocation.watchPosition

let sendIntervalId = null;// ID returned by setInterval for sending location updates

let lastPosition = null;// Stores the most recent GPS position received from the browser

const SEND_INTERVAL_MS = 5000; // Send location every 5 seconds


/**
 * Initialize the Google Map with a default center and zoom level
 * Also creates a marker that represents the walker's current location
 */
function initMapInitial() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: { lat: 32.0853, lng: 34.7818 }, // Default location
  });

  // Marker that will move as the user's location updates
  marker = new google.maps.Marker({
    map: map,
    title: "המיקום שלך",
  });
}


/**
 * Start sharing the user's location
 * - Continuously tracks GPS updates using watchPosition
 * - Updates the map marker in real time
 * - Sends location to the server at a fixed interval
 */
function startSharing() {
  if (!navigator.geolocation) {
    alert('דפדפן זה אינו תומך ב-GPS');
    return;
  }

  // Start watching the user's position (continuous updates)
  watchId = navigator.geolocation.watchPosition(
    (pos) => {
      // Save the latest coordinates
      lastPosition = pos.coords;
      
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;

      marker.setPosition({ lat, lng }); // Update marker position on the map
      map.setCenter({ lat, lng });  // Center the map on the user's current location
    },
    (err) => {
      // Handle geolocation errors (permission denied, timeout, etc.)
      console.error('Geolocation error', err);
      alert('לא ניתן לקבל מיקום - ודא שהמכשיר מאפשר שיתוף מיקום');
    },
    // Request high accuracy GPS data when possible, allow cached positions up to 2 seconds old
    { enableHighAccuracy: true, maximumAge: 2000 }
  );

  // Periodically send the last known location to the server
  sendIntervalId = setInterval(() => {
    if (!lastPosition) return;
    sendLocationToServer(lastPosition.latitude, lastPosition.longitude);
  }, SEND_INTERVAL_MS);

  // Update UI state
  document.getElementById('startBtn').disabled = true;
  document.getElementById('stopBtn').disabled = false;
}

/**
 * Stop sharing the user's location
 * - Stops GPS tracking
 * - Stops sending location updates to the server
 */
function stopSharing() {
  // Stop continuous GPS tracking
  if (watchId !== null) {
    navigator.geolocation.clearWatch(watchId);
    watchId = null;
  }
    // Stop sending location updates
  if (sendIntervalId !== null) {
    clearInterval(sendIntervalId);
    sendIntervalId = null;
  }
  // Reset UI state
  document.getElementById('startBtn').disabled = false;
  document.getElementById('stopBtn').disabled = true;
}


/* Send the user's current location to the backend server */
function sendLocationToServer(lat, lng) {
  fetch('walker_send_location.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      latitude: lat,
      longitude: lng
    })
  })
  .then(res => res.json())
  .then(data => {
    // Server responded but did not confirm succes
    if (!data?.success) {
      console.warn('Send location failed', data);
    }
  })
  // Network or server error
  .catch(err => console.error('Fetch send location error', err));
}

/**
 * Initialize map and bind UI events once the page is fully loaded
 */
window.addEventListener('load', () => {
  initMapInitial();

  // Bind start/stop buttons
  document.getElementById('startBtn').addEventListener('click', startSharing);
  document.getElementById('stopBtn').addEventListener('click', stopSharing);
});
