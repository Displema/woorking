 // Caricamento della navbar
fetch('NavBar.html')
.then(response => response.text())
.then(html => {
document.getElementById('navbar-container').innerHTML = html;

// Aggiungi classe 'active' dinamica
const currentPage = window.location.pathname.split('/').pop();
document.querySelectorAll('.nav-link').forEach(link => {
    if (link.getAttribute('href') === currentPage) {
    link.classList.add('active');
    }
});
});