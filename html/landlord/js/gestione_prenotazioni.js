// Tabs nella schermata principale (come già hai)
const tabs = document.querySelectorAll('.tab');
const contents = document.querySelectorAll('.tab-content');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.style.display = 'none');

        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).style.display = 'block';
    });
});


// Gestione tab nel modal
document.addEventListener('DOMContentLoaded', () => {
    const modalTabs = document.querySelectorAll('.tab-btn');
    const modalContents = document.querySelectorAll('.tab-content-section');

    modalTabs.forEach(btn => {
        btn.addEventListener('click', () => {
            modalTabs.forEach(b => b.classList.remove('active'));
            modalContents.forEach(s => s.style.display = 'none');

            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).style.display = 'block';
        });
    });

    // Inizializza modal per aprire sempre con il primo tab attivo
    const modal = document.getElementById('modal-dettagliP');
    modal.addEventListener('show.bs.modal', () => {
        // Reset tabs nel modal (attiva sempre il primo tab)
        modalTabs.forEach(b => b.classList.remove('active'));
        modalTabs[0].classList.add('active');

        modalContents.forEach(s => s.style.display = 'none');
        modalContents[0].style.display = 'block';
    });


    // Popola il modal con i dati dinamici (usando delega evento)
    document.querySelectorAll('.btn-dettagli, .btn-dettagliP').forEach(button => {
        button.addEventListener('click', () => {
            // Prendi i dati dal button
            const nome = button.getAttribute('data-nome');
            const cognome = button.getAttribute('data-cognome');
            const data = button.getAttribute('data-data');
            const telefono = button.getAttribute('data-telefono');


            const nomeUfficio = button.getAttribute('data-nome-ufficio');
            const descrizione = button.getAttribute('data-descrizione');
            const prezzo = button.getAttribute('data-prezzo');
            const superficie = button.getAttribute('data-superficie');

            const indirizzo = button.getAttribute('data-indirizzo');
            const postazioni = button.getAttribute('data-postazioni');
            const servizi = button.getAttribute('data-servizi');


            document.getElementById('modalNome').textContent = nome;
            document.getElementById('modalCognome').textContent = cognome;
            document.getElementById('modalData').textContent = data;
            document.getElementById('modalTelefono').textContent = telefono;


            document.getElementById('modalNomeUfficio').textContent = nomeUfficio;
            document.getElementById('modalDescrizione').textContent = descrizione;
            document.getElementById('modalPrezzo').textContent = prezzo;
            document.getElementById('modalSuperficie').textContent = superficie;

            document.getElementById('modalIndirizzo').textContent = indirizzo;
            document.getElementById('modalPostazioni').textContent = postazioni;
            document.getElementById('modalServizi').textContent = servizi;


        });
    });
});
