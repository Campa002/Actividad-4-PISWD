/**
 * mapa.js — Geolocalización y mapa interactivo con Leaflet
 * 
 * Funcionalidades:
 * 1. Inicializar mapa Leaflet centrado en Buenos Aires
 * 2. Agregar marcadores de eventos desde eventosData (PHP)
 * 3. Geolocalización del navegador
 * 4. Función para centrar mapa en un evento
 */

// ── Variables globales del mapa ───────────────────────────
var mapa;
var marcadorUsuario;
var marcadores = [];

// ── 1. INICIALIZAR MAPA ──────────────────────────────────
function inicializarMapa() {
    // Centro por defecto: Buenos Aires
    mapa = L.map('mapa').setView([-34.6037, -58.3816], 12);

    // Capa de tiles (mapa oscuro para tema neon)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
        maxZoom: 19
    }).addTo(mapa);

    // Agregar marcadores de eventos
    agregarMarcadoresEventos();

    // Intentar geolocalización automática
    obtenerUbicacion();
}

// ── 2. MARCADORES DE EVENTOS ─────────────────────────────
function agregarMarcadoresEventos() {
    if (typeof eventosData === 'undefined' || !eventosData.length) return;

    // Ícono personalizado para eventos
    var iconoEvento = L.divIcon({
        className: 'marcador-evento',
        html: '<div class="marcador-pin"><i class="fas fa-calendar-check"></i></div>',
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        popupAnchor: [0, -36]
    });

    eventosData.forEach(function(evento) {
        if (evento.latitud && evento.longitud) {
            var lat = parseFloat(evento.latitud);
            var lng = parseFloat(evento.longitud);
            
            var popup = '<div class="popup-evento">' +
                '<h4>' + escapeHtml(evento.titulo) + '</h4>' +
                '<p><i class="fas fa-map-pin"></i> ' + escapeHtml(evento.ubicacion) + '</p>' +
                '<p><i class="fas fa-tag"></i> ' + escapeHtml(evento.categoria) + '</p>' +
                '<p><i class="fas fa-calendar"></i> ' + formatearFecha(evento.fecha_evento) + '</p>' +
                '</div>';

            var marcador = L.marker([lat, lng], { icon: iconoEvento })
                .addTo(mapa)
                .bindPopup(popup);

            marcadores.push(marcador);
        }
    });

    // Ajustar vista para mostrar todos los marcadores
    if (marcadores.length > 0) {
        var grupo = L.featureGroup(marcadores);
        mapa.fitBounds(grupo.getBounds().pad(0.2));
    }
}

// ── 3. GEOLOCALIZACIÓN ───────────────────────────────────
function obtenerUbicacion() {
    var statusEl = document.getElementById('geo-status');
    var coordsEl = document.getElementById('geo-coords');
    var latEl    = document.getElementById('lat-value');
    var lngEl    = document.getElementById('lng-value');
    var accEl    = document.getElementById('acc-value');

    if (!navigator.geolocation) {
        if (statusEl) {
            statusEl.innerHTML = '<p class="geo-error"><i class="fas fa-times-circle"></i> Tu navegador no soporta geolocalización.</p>';
        }
        return;
    }

    if (statusEl) {
        statusEl.innerHTML = '<div class="geo-loading"><i class="fas fa-spinner fa-spin"></i> Obteniendo ubicación...</div>';
    }

    navigator.geolocation.getCurrentPosition(
        // Éxito
        function(posicion) {
            var lat       = posicion.coords.latitude;
            var lng       = posicion.coords.longitude;
            var precision = posicion.coords.accuracy;

            // Mostrar coordenadas
            if (latEl) latEl.textContent = lat.toFixed(6);
            if (lngEl) lngEl.textContent = lng.toFixed(6);
            if (accEl) accEl.textContent = precision.toFixed(0) + ' m';
            if (coordsEl) coordsEl.style.display = 'block';
            if (statusEl) statusEl.innerHTML = '<p style="color: #39FF14;"><i class="fas fa-check-circle"></i> Ubicación obtenida</p>';

            // Ícono del usuario
            var iconoUsuario = L.divIcon({
                className: 'marcador-usuario',
                html: '<div class="marcador-usuario-pin"><i class="fas fa-street-view"></i></div>',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });

            // Agregar o actualizar marcador del usuario
            if (marcadorUsuario) {
                marcadorUsuario.setLatLng([lat, lng]);
            } else {
                marcadorUsuario = L.marker([lat, lng], { icon: iconoUsuario })
                    .addTo(mapa)
                    .bindPopup('<strong><i class="fas fa-user"></i> Tu ubicación</strong>')
                    .openPopup();
            }

            mapa.setView([lat, lng], 14);
        },
        // Error
        function(error) {
            var mensajes = {
                1: 'Permiso de ubicación denegado.',
                2: 'Ubicación no disponible.',
                3: 'Tiempo de espera agotado.'
            };
            var msg = mensajes[error.code] || 'Error desconocido.';
            if (statusEl) {
                statusEl.innerHTML = '<p class="geo-error"><i class="fas fa-exclamation-triangle"></i> ' + msg + '</p>';
            }
        },
        // Opciones
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        }
    );
}

// ── 4. CENTRAR MAPA EN UN EVENTO ─────────────────────────
function centrarMapa(lat, lng) {
    if (mapa) {
        mapa.setView([lat, lng], 16);
        // Abrir popup del marcador más cercano
        marcadores.forEach(function(m) {
            var pos = m.getLatLng();
            if (Math.abs(pos.lat - lat) < 0.001 && Math.abs(pos.lng - lng) < 0.001) {
                m.openPopup();
            }
        });
    }
}

// ── Utilidades ───────────────────────────────────────────
function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatearFecha(fechaStr) {
    var fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-AR') + ' ' + fecha.toLocaleTimeString('es-AR', {hour:'2-digit', minute:'2-digit'});
}

// ── Iniciar cuando el DOM esté listo ─────────────────────
document.addEventListener('DOMContentLoaded', inicializarMapa);
