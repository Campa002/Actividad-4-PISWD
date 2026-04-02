/**
 * app.js — Lógica principal de EventHub
 * 
 * Funcionalidades:
 * 1. Modo oscuro persistente (localStorage)
 * 2. Menú hamburguesa mobile
 * 3. Auto-cierre de flash messages
 * 4. Animación de cards al scroll
 */

document.addEventListener('DOMContentLoaded', function() {

    // ── 1. MODO OSCURO ────────────────────────────────────
    var body        = document.body;
    var toggleBtn   = document.getElementById('toggle-darkmode');
    var STORAGE_KEY = 'eventhub-dark-mode';

    function actualizarIcono(esDark) {
        if (!toggleBtn) return;
        var icono = toggleBtn.querySelector('i');
        if (!icono) return;
        if (esDark) {
            icono.classList.remove('fa-moon');
            icono.classList.add('fa-sun');
        } else {
            icono.classList.remove('fa-sun');
            icono.classList.add('fa-moon');
        }
    }

    // Aplicar tema guardado
    var modoGuardado = localStorage.getItem(STORAGE_KEY);
    if (modoGuardado === 'true') {
        body.classList.add('dark-mode');
        actualizarIcono(true);
    } else if (modoGuardado === null) {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            body.classList.add('dark-mode');
            actualizarIcono(true);
            localStorage.setItem(STORAGE_KEY, 'true');
        }
    }

    // Click en toggle
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            var esDark = body.classList.toggle('dark-mode');
            localStorage.setItem(STORAGE_KEY, esDark);
            actualizarIcono(esDark);
        });
    }

    // ── 2. MENÚ MOBILE ────────────────────────────────────
    var menuToggle = document.getElementById('menu-toggle-btn');
    var navMenu    = document.getElementById('nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            var icono = this.querySelector('i');
            if (navMenu.classList.contains('active')) {
                icono.classList.remove('fa-bars');
                icono.classList.add('fa-times');
            } else {
                icono.classList.remove('fa-times');
                icono.classList.add('fa-bars');
            }
        });
    }

    // ── 3. AUTO-CIERRE FLASH MESSAGES ─────────────────────
    var flashMsgs = document.querySelectorAll('.flash-message');
    flashMsgs.forEach(function(msg) {
        setTimeout(function() {
            msg.style.opacity = '0';
            msg.style.transform = 'translateY(-10px)';
            setTimeout(function() { msg.remove(); }, 300);
        }, 5000);
    });

    // ── 4. ANIMACIÓN DE CARDS AL SCROLL ───────────────────
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    var cards = document.querySelectorAll('.evento-card, .stat-card, .geo-card');
    cards.forEach(function(card, i) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.5s ease ' + (i * 0.1) + 's, transform 0.5s ease ' + (i * 0.1) + 's';
        observer.observe(card);
    });

    // ── 5. INICIALIZAR FOUNDATION ─────────────────────────
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.foundation !== 'undefined') {
        jQuery(document).foundation();
    }
});
