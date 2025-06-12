document.addEventListener("DOMContentLoaded", function () {

    let officeIdToDelete = null;



    document.querySelectorAll('.btn-cancella').forEach(button => {
        button.addEventListener('click', function () {
            officeIdToDelete = this.dataset.officeId;
        });
    });


    document.getElementById('conferma-cancella-btn').addEventListener('click', function () {
        console.log('Cliccato il bottone cancella');  // <-- log per debug
        if (!officeIdToDelete) return;

        fetch(`/offices/${officeIdToDelete}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'shouldRefund=1'
        })
            .then(response => response.text())  // qui prendi il testo grezzo della risposta
            .then(text => {
                console.log('Risposta fetch (testo grezzo):', text);
            })
            .catch(error => {
                console.error('Errore fetch:', error);
            });


    });
});