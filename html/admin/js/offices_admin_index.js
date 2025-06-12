// Gestione cambio tab semplice
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.getAttribute('data-target');
        // Aggiorna tabs
        document.querySelectorAll('.tab').forEach(t => {
            t.classList.toggle('active', t === tab);
            t.setAttribute('aria-selected', t === tab);
            t.setAttribute('tabindex', t === tab ? '0' : '-1');
        });
        // Aggiorna sezioni
        document.querySelectorAll('.section-tab').forEach(sec => {
            if (sec.id === target) {
                sec.classList.add('active');
                sec.removeAttribute('hidden');
            } else {
                sec.classList.remove('active');
                sec.setAttribute('hidden', '');
            }
        });
    });
});