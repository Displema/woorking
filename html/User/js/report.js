

    <!-- show or not textarea when we choose "altro -->
    const radios = document.querySelectorAll('input[name="reportMotivation"]');
    const altroContainer = document.getElementById("TextContainer");
    const altroTextarea = document.getElementById("Text");
    const form = document.getElementById("segnalazioneForm");

    radios.forEach(radio => {
    radio.addEventListener("change", () => {
        if (document.getElementById("altro").checked) {
            altroContainer.style.display = "block";
            altroTextarea.setAttribute("required", "required");
        } else {
            altroContainer.style.display = "none";
            altroTextarea.removeAttribute("required");
            altroTextarea.value = "";
        }
    });
});



