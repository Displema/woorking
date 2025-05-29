// Carico il primo modale
fetch('modal_dettagli_ufficio.html')
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

//carico il secondo modale
fetch('modal_conferma_cancella.html')
.then(response => response.text())
.then(html => {
document.getElementById('modal-container').innerHTML += html;

let cardToDelete = null;

document.querySelectorAll('.btn-cancella').forEach(btn => {
    btn.addEventListener('click', () => {
    cardToDelete = btn.closest('.col-md-6');
    });
});

// Aspetta che il DOM sia aggiornato e il modale inizializzato
setTimeout(() => {
    const confermaBtn = document.getElementById('conferma-cancella-btn');
    confermaBtn.addEventListener('click', () => {
    if (cardToDelete) {
        cardToDelete.remove();
        cardToDelete = null;
        const modalEl = document.getElementById('modal-conferma-cancella');       
        let instance = bootstrap.Modal.getInstance(modalEl);
        // Se l'istanza non esiste, la creiamo noi
        if (!instance) {
            instance = new bootstrap.Modal(modalEl);
            }
        // Chiudi il modale
        instance.hide();
        }
    });
}, 100); // leggero ritardo per garantire che il modale sia pronto
});




