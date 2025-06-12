fetch('/api/grafici/entrate-mensili')
    .then(res => res.json())
    .then(data => {
        const mesi = data.map(e => e.mese);
        const entrate = data.map(e => e.entrate);

        new Chart(document.getElementById('graficoEntrate'), {
            type: 'bar',
            data: {
                labels: mesi,
                datasets: [{
                    label: 'Entrate (€)',
                    data: entrate,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // colore barre
                    borderColor: 'rgba(54, 162, 235, 1)',       // bordo barre
                    borderWidth: 1,
                    borderRadius: 5                            // arrotonda gli angoli delle barre
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => '€ ' + value.toLocaleString()  // formato valuta sull'asse Y
                        }
                    }
                }
            }
        });
    });