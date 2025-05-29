
// Funzione per gestire le tabs
const tabs = document.querySelectorAll('.tab');
const contents = document.querySelectorAll('.tab-content');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
    // Rimuovi active da tutti i tab
    tabs.forEach(t => t.classList.remove('active'));
    // Nascondi tutti i contenuti
    contents.forEach(c => c.style.display = 'none');

    // Aggiungi active al tab cliccato
    tab.classList.add('active');
    // Mostra contenuto relativo
    document.getElementById(tab.dataset.tab).style.display = 'block';
    });
});  


// Carica il modal
fetch('modal_dettagli.html')
.then(response => response.text())
.then(html => {
    document.getElementById('modal-container').innerHTML = html;

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content-section').forEach(s => s.style.display = 'none');
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).style.display = 'block';
    });
    });
});


  