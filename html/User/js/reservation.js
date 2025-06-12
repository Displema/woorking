

    <!-- script that allow to switch from active to past -->
    const btnAttive = document.getElementById("btn-attive");
    const btnPassate = document.getElementById("btn-passate");
    const prenotazioni = document.querySelectorAll(".prenotazione");

    btnAttive.addEventListener("click", () => {
    btnAttive.classList.add("active");
    btnPassate.classList.remove("active");
    prenotazioni.forEach(card => {
    card.classList.toggle("d-none", card.classList.contains("passata"));
});
});

    btnPassate.addEventListener("click", () => {
    btnPassate.classList.add("active");
    btnAttive.classList.remove("active");
    prenotazioni.forEach(card => {
    card.classList.toggle("d-none", card.classList.contains("attiva"));
});
});


