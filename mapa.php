<?php
/**
 * mapa.php — Página del mapa con geolocalización
 * 
 * Utiliza la API de Geolocalización de JavaScript para obtener
 * las coordenadas del usuario. Muestra un mapa interactivo con
 * Leaflet.js y los eventos disponibles como marcadores.
 */

$titulo_pagina = 'Mapa';
require_once 'includes/conexion.php';
require_once 'includes/header.php';

// ── Obtener eventos con coordenadas ───────────────────────
$stmt = $pdo->query("
    SELECT id, titulo, descripcion, ubicacion, latitud, longitud, categoria, fecha_evento
    FROM eventos 
    WHERE latitud IS NOT NULL AND longitud IS NOT NULL
    ORDER BY fecha_evento ASC
");
$eventos_mapa = $stmt->fetchAll();
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<section class="mapa-section">
    <div class="grid-container">
        
        <div class="section-header text-center">
            <h1 class="section-title"><i class="fas fa-map-marked-alt"></i> Mapa de Eventos</h1>
            <p class="section-subtitle">Descubrí eventos cerca tuyo</p>
        </div>

        <!-- ══════════════════════════════════════════════════
             INFORMACIÓN DE GEOLOCALIZACIÓN
             ══════════════════════════════════════════════════ -->
        <div class="grid-x grid-margin-x">
            <div class="cell medium-4">
                <div class="card geo-card">
                    <div class="card-section">
                        <h3><i class="fas fa-crosshairs neon-icon"></i> Tu Ubicación</h3>
                        <div id="geo-status" class="geo-status">
                            <div class="geo-loading">
                                <i class="fas fa-spinner fa-spin"></i> Obteniendo ubicación...
                            </div>
                        </div>
                        <div id="geo-coords" class="geo-coords" style="display:none;">
                            <div class="coord-item">
                                <span class="coord-label">Latitud:</span>
                                <span class="coord-value" id="lat-value">—</span>
                            </div>
                            <div class="coord-item">
                                <span class="coord-label">Longitud:</span>
                                <span class="coord-value" id="lng-value">—</span>
                            </div>
                            <div class="coord-item">
                                <span class="coord-label">Precisión:</span>
                                <span class="coord-value" id="acc-value">—</span>
                            </div>
                        </div>
                        <button id="btn-geolocate" class="button btn-neon expanded" onclick="obtenerUbicacion()">
                            <i class="fas fa-location-arrow"></i> Actualizar Ubicación
                        </button>
                    </div>
                </div>

                <!-- Lista de eventos en el mapa -->
                <div class="card geo-card">
                    <div class="card-section">
                        <h3><i class="fas fa-list neon-icon"></i> Eventos en Mapa</h3>
                        <?php if (empty($eventos_mapa)): ?>
                            <p><em>No hay eventos con ubicación disponible.</em></p>
                        <?php else: ?>
                            <ul class="eventos-mapa-lista">
                                <?php foreach ($eventos_mapa as $ev): ?>
                                    <li class="evento-mapa-item" 
                                        data-lat="<?php echo $ev['latitud']; ?>" 
                                        data-lng="<?php echo $ev['longitud']; ?>"
                                        onclick="centrarMapa(<?php echo $ev['latitud']; ?>, <?php echo $ev['longitud']; ?>)">
                                        <i class="fas fa-map-pin"></i>
                                        <div>
                                            <strong><?php echo htmlspecialchars($ev['titulo']); ?></strong>
                                            <small><?php echo htmlspecialchars($ev['ubicacion']); ?></small>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════
                 MAPA INTERACTIVO
                 ══════════════════════════════════════════════ -->
            <div class="cell medium-8">
                <div class="mapa-container">
                    <div id="mapa" class="mapa-leaflet"></div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Datos de eventos para JS (evita mezclar PHP y JS directamente) -->
<script>
    const eventosData = <?php echo json_encode($eventos_mapa); ?>;
</script>

<!-- Script del mapa -->
<script src="assets/js/mapa.js"></script>

<?php require_once 'includes/footer.php'; ?>
