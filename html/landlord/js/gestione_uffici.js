

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-dettagli').forEach(button => {
        button.addEventListener('click', () => {
            // Leggo tutti i data- attributi dal bottone

            const titolo = button.getAttribute('data-titolo') || '';
            const descrizione = button.getAttribute('data-descrizione') || '';
            const prezzo = button.getAttribute('data-prezzo') || '';
            const superficie = button.getAttribute('data-superficie') || '';
            const fascia = button.getAttribute('data-fascia') || '';
            const indirizzo = button.getAttribute('data-indirizzo') || '';
            const postazioni = button.getAttribute('data-postazioni') || '';
            const servizi = button.getAttribute('data-servizi') || '';
            const rifiuto = button.getAttribute('data-rifiuto');

            // Inserisco nei campi del modale
            document.getElementById('modalTitoloHeader').textContent = `🏢 ${titolo}`;
            document.getElementById('modalNomeUfficio').textContent = titolo;
            document.getElementById('modalDescrizione').textContent = descrizione;
            document.getElementById('modalPrezzo').textContent = prezzo;
            document.getElementById('modalSuperficie').textContent = superficie;
            document.getElementById('modalFascia').textContent = fascia;
            document.getElementById('modalIndirizzo').textContent = indirizzo;
            document.getElementById('modalPostazioni').textContent = postazioni;
            document.getElementById('modalServizi').textContent = servizi;
            document.getElementById('modalRifiuto').textContent = rifiuto
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    let cardToDelete = null;

    // Quando clicchi il pulsante "Cancella", salva la card da eliminare
    document.querySelectorAll('.btn-cancella').forEach(btn => {
        btn.addEventListener('click', () => {
            cardToDelete = btn.closest('.col-md-6'); // o .col-lg-4 se preferisci
        });
    });

    // Quando clicchi "Conferma" nel modale
    const confermaBtn = document.getElementById('conferma-cancella-btn');
    if (confermaBtn) {
        confermaBtn.addEventListener('click', () => {
            if (cardToDelete) {
                cardToDelete.remove();
                cardToDelete = null;

                const modalEl = document.getElementById('modal-conferma-cancella');
                let instance = bootstrap.Modal.getInstance(modalEl);
                if (!instance) {
                    instance = new bootstrap.Modal(modalEl);
                }
                instance.hide();
            }
        });
    }
});







