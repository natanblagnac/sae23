document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            if (!username || !password) {
                event.preventDefault();
                alert('Veuillez remplir tous les champs.');
            }
        });
    }
});

function refreshData() {
    if (window.location.pathname.includes('consultation.php')) {
        fetch('php/consulter.php')
            .then(response => response.json())
            .then(data => {
                const table = document.getElementById('sensor-data');
                if (table) {
                    table.innerHTML = '<tr><th>Capteur</th><th>Température (°C)</th><th>Humidité (%)</th><th>CO2 (ppm)</th><th>Date et Heure</th></tr>';
                    data.forEach(row => {
                        table.innerHTML += `<tr><td>${row.nom_capteur}</td><td>${row.temperature}</td><td>${row.humidity}</td><td>${row.co2}</td><td>${row.date_heure}</td></tr>`;
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
    }
}
setInterval(refreshData, 10000);
