fetch('/api/recensioni/casuali')
    .then(res => res.text())
    .then(txt => {
        console.log("Risposta ricevuta:", txt);

        try {
            const data = JSON.parse(txt); // singolo oggetto recensione

            const container = document.getElementById('recensioni-container');
            container.innerHTML = ''; // Pulisce il contenuto precedente

            const maxStars = 5;
            // Assicura che la valutazione sia tra 0 e 5
            const valutazioneCorretta = Math.min(Math.max(data.valutazione, 0), maxStars);

            const stellePiene = '★'.repeat(valutazioneCorretta);
            const stelleVuote = '☆'.repeat(maxStars - valutazioneCorretta);

            const div = document.createElement('div');
            div.classList.add('mb-4');
            div.innerHTML = `
                <div class="card shadow-sm rounded-3 p-3 bg-white">
                    <h5 class="text-primary">${data.utente}</h5>
                    <p class="mb-2">${data.commento}</p>
                    <p class="text-warning">${stellePiene}${stelleVuote}</p>
                </div>
            `;
            container.appendChild(div);

        } catch (e) {
            document.getElementById('recensioni-container').innerHTML = `
                <div class="alert alert-danger">
                    Errore nel caricamento delle recensioni. <br>
                    Controlla la console per maggiori dettagli.
                </div>
            `;
            console.error("Errore di parsing JSON:", e);
            console.log("Contenuto ricevuto (non JSON):", txt);
        }
    })
    .catch(err => {
        document.getElementById('recensioni-container').innerHTML = `
            <div class="alert alert-danger">
                Errore di rete durante il caricamento delle recensioni.
            </div>
        `;
        console.error('Errore di rete:', err);
    });
