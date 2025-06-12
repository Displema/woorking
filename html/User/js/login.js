document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const formTitle = document.getElementById("form-title");
    let isLogin = true;

    const handleToggle = (e) => {
        e.preventDefault();

        isLogin = !isLogin;

        if (isLogin) {
            loginForm.style.display = "block";
            registerForm.style.display = "none";
            formTitle.innerHTML = '<i class="fas fa-sign-in-alt"></i> Accedi';
            toggleText.innerHTML = 'Non hai un account? <a href="#" id="toggle-link">Registrati</a>';
        } else {
            loginForm.style.display = "none";
            registerForm.style.display = "block";
            formTitle.innerHTML = '<i class="fas fa-user-plus"></i> Registrati';
            toggleText.innerHTML = 'Hai già un account? <a href="#" id="toggle-link">Accedi</a>';
        }

        // Riattacca il listener al nuovo link appena creato
        document.getElementById("toggle-link").addEventListener("click", handleToggle);
    };

    // Attiva primo listener
    document.getElementById("toggle-link").addEventListener("click", handleToggle);
});

    // Mostra/nascondi campo Partita IVA
    document.addEventListener("DOMContentLoaded", () => {
    const locatoreSwitch = document.getElementById("isLocatoreSwitch");
    const partitaIvaField = document.getElementById("partitaIvaField");

    locatoreSwitch.addEventListener("change", () => {
    const isLocatore = locatoreSwitch.checked;
    partitaIvaField.style.display = locatoreSwitch.checked ? "block" : "none";
    userTypeInput.value = isLocatore ? "LOCATORE" : "UTENTE";
});
});