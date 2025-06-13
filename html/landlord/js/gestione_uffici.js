

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-dettagli').forEach(button => {
        button.addEventListener('click', () => {
            // Leggo tutti i data- attributi dal bottone

            const titolo = button.getAttribute('data-titolo') || '';
            const descrizione = button.getAttribute('data-descrizione') || '';
            const prezzo = button.getAttribute('data-prezzo') || '';
            const superficie = button.getAttribute('data-superficie') || '';

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

            document.getElementById('modalIndirizzo').textContent = indirizzo;
            document.getElementById('modalPostazioni').textContent = postazioni;
            document.getElementById('modalServizi').textContent = servizi;
            const rifiutoContainer = document.getElementById('modalRifiutoContainer');

            if (rifiuto && rifiuto.trim() !== '') {
                rifiutoContainer.style.display = 'block'; // mostra
                document.getElementById('modalRifiuto').textContent = rifiuto;
            } else {
                rifiutoContainer.style.display = 'none';  // nascondi
                document.getElementById('modalRifiuto').textContent = '';
            }
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

document.addEventListener('DOMContentLoaded', () => {
    const disponibilitaButtons = document.querySelectorAll('.btn-disponibilita');
    disponibilitaButtons.forEach(button => {
        button.addEventListener('click', () => {
            const officeId = button.getAttribute('data-office-id');
            document.getElementById('disponibilita-office-id').value = officeId;
        });
    });

});

document.getElementById('modal-disponibilita').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const officeId = button.getAttribute('data-office-id');
    const disponibilitaJson = button.getAttribute('data-disponibilita');
    const disponibilita = disponibilitaJson ? JSON.parse(disponibilitaJson) : [];
    console.log("Raw disponibilitaJson:", disponibilitaJson);
    console.log("Parsed disponibilita:", disponibilita);

    document.getElementById('disponibilita-office-id').value = officeId;

    const tbody = document.getElementById('disponibilita-esistenti-body');
    tbody.innerHTML = '';

    if (disponibilita.length > 0) {
        disponibilita.forEach(d => {
            const dataInizio = d.dataInizio ? d.dataInizio.split('T')[0] : '-';
            const dataFine = d.dataFine ? d.dataFine.split('T')[0] : '-';
            const fascia = d.fascia || '-';

            const tr = document.createElement('tr');
            tr.innerHTML = `
        <td>${dataInizio}</td>
        <td>${dataFine}</td>
        <td>${fascia}</td>
      `;
            tbody.appendChild(tr);
        });
    } else {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td colspan="3" class="text-muted text-center">Nessuna disponibilità</td>`;
        tbody.appendChild(tr);
    }
});








