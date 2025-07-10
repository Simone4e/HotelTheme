document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([41.9028, 12.4964], 13); // Roma

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(map);

    L.marker([41.9028, 12.4964]).addTo(map)
        .bindPopup('We are here!')
        .openPopup();
});
