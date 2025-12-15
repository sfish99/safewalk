// volunteer_live_map.js
let map;
const markers = {};
const POLL_INTERVAL_MS = 5000;

function initVolunteerMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: { lat: 32.0853, lng: 34.7818 }
  });

  fetchAndUpdate();
  setInterval(fetchAndUpdate, POLL_INTERVAL_MS);
}

function fetchAndUpdate() {
  fetch('get_active_walkers.php')
    .then(res => res.json())
    .then(data => {
      if (!data || !Array.isArray(data)) return;

      const walkerIds = data.map(w => w.walker_id);

      // עדכון או יצירת markers
      data.forEach(w => {
        const lat = parseFloat(w.latitude);
        const lng = parseFloat(w.longitude);
        if (markers[w.walker_id]) {
          markers[w.walker_id].setPosition({ lat, lng });
        } else {
          markers[w.walker_id] = new google.maps.Marker({
            map: map,
            position: { lat, lng },
            title: w.first_name
          });
        }
      });

      // הסרת markers שלא קיימים יותר
      Object.keys(markers).forEach(id => {
        if (!walkerIds.includes(parseInt(id))) {
          markers[id].setMap(null);
          delete markers[id];
        }
      });
    })
    .catch(err => console.error('Error fetching walkers', err));
}

// אתחול המפה לאחר טעינה
window.addEventListener('load', () => {
  if (typeof google !== 'undefined' && google.maps) {
    setTimeout(initVolunteerMap, 500);
  } else {
    setTimeout(initVolunteerMap, 1000);
  }
});
