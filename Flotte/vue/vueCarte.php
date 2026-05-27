<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="./css/tableaubord.css">

<style>
    .map-container {
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: 4px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        padding: 10px;
        margin-top: 20px;
    }
    #map {
        height: 650px;
        width: 100%;
        border-radius: 4px;
        z-index: 1; /* Reste sous le menu déroulant */
    }
    .leaflet-popup-content strong {
        color: var(--erp-primary);
        font-size: 1.1rem;
    }
</style>

<div class="erp-dashboard">
    <div class="erp-header">
        <div>
            <h1 class="erp-title">Suivi Logistique Mondial</h1>
            <span class="erp-subtitle">Localisation des infrastructures et de la flotte</span>
        </div>
    </div>

    <div class="map-container">
        <div id="map"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialisation de la carte (Centrée sur la France par défaut)
    var map = L.map('map').setView([46.603354, 1.888334], 5);

    // Ajout du fond de carte OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Récupération des données encodées en PHP
    var depots = <?= $depotsJson ?>;

    // Ajout des marqueurs sur la carte
    depots.forEach(function(depot) {
        var marker = L.marker([depot.lat, depot.lng]).addTo(map);
        
        // Contenu de la bulle d'info au clic
        marker.bindPopup(
            "<strong>🏢 " + depot.nom + "</strong><br>" +
            "📍 " + depot.ville + "<br>" +
            "📞 " + (depot.tel ? depot.tel : 'Non renseigné')
        );
    });
</script>