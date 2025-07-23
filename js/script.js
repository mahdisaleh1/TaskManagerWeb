
$(document).ready(function () {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

// Mobile nav toggle
document.addEventListener('DOMContentLoaded', function () {
    var nav = document.querySelector('nav');
    var navToggle = document.getElementById('navToggle');
    navToggle.addEventListener('click', function () {
        nav.classList.toggle('show');
    });
    // Hide nav when clicking or scrolling outside (mobile only)
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 768) {
            if (!nav.contains(e.target) && !navToggle.contains(e.target)) {
                nav.classList.remove('show');
            }
        }
    });

    document.addEventListener('scroll', function (e) {
        if (window.innerWidth < 768) {
            if (!nav.contains(e.target) && !navToggle.contains(e.target)) {
                nav.classList.remove('show');
            }
        }
    });
    
});