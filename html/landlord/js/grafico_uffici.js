fetch('/api/grafici/utilizzo-uffici')
    .then(res => res.json())
    .then(data => {
        const nomiUffici = data.map(e => e.nome);
        const utilizzi = data.map(e => e.numeroPrenotazioni);

        new Chart(document.getElementById('graficoUtilizzo'), {
            type: 'pie',
            data: {
                labels: nomiUffici,
                datasets: [{
                    label: 'Prenotazioni',
                    data: utilizzi,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: 'white',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                return `${label}: ${value} prenotazioni`;
                            }
                        }
                    }
                }
            }
        });
    })
    .catch(err => console.error("Errore nel fetch del grafico:", err));