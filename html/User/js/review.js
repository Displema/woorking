
    // clickable star
    const stars = document.querySelectorAll('.fa-star');
    const votoInput = document.getElementById('voto');

    stars.forEach(star => {
    star.addEventListener('click', () => {
        const value = star.getAttribute('data-val');
        votoInput.value = value;

        // Aggiungi classe "checked" fino alla stella selezionata
        stars.forEach(s => {
            if (s.getAttribute('data-val') <= value) {
                s.classList.add('checked');
            } else {
                s.classList.remove('checked');
            }
        });
    });
});
