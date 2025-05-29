const fotoInput = document.getElementById('foto');
const anteprimaContainer = document.getElementById('anteprima-foto');
const altroCheckbox = document.getElementById('altro-servizio');
const campoAltro = document.getElementById('campo-altro');
const selezionaFotoBtn = document.getElementById('seleziona-foto');
const conteggioFoto = document.getElementById('conteggio-foto');
    
//gestione delle immagini
let fileList = [];

  selezionaFotoBtn.addEventListener('click', () => {
    fotoInput.click(); // Apri selettore file
  });

  //gestione del campo "altro"
     document.addEventListener('DOMContentLoaded', () => {
      const altroCheckbox = document.getElementById('altro-servizio');
      const campoAltro = document.getElementById('campo-altro');

      altroCheckbox.addEventListener('change', () => {
        if (altroCheckbox.checked) {
          campoAltro.classList.remove('d-none');
          campoAltro.focus();
        } else {
          campoAltro.classList.add('d-none');
          campoAltro.value = '';
        }
      });
    });

  fotoInput.addEventListener('change', () => {
    const nuoviFiles = Array.from(fotoInput.files);

    if ((fileList.length + nuoviFiles.length) > 3) {
      alert('Puoi caricare al massimo 3 immagini.');
      fotoInput.value = '';
      return;
    }

    fileList = fileList.concat(nuoviFiles);
    renderAnteprime();
    fotoInput.value = ''; // Resetta input
  });

  function renderAnteprime() {
    anteprimaContainer.innerHTML = '';
    fileList.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = function (e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.classList.add('image-preview');

        const removeBtn = document.createElement('button');
        removeBtn.textContent = '✕';
        removeBtn.className = 'remove-btn';
        removeBtn.onclick = () => {
          fileList.splice(index, 1);
          renderAnteprime();
        };

        const wrapper = document.createElement('div');
        wrapper.className = 'image-wrapper';
        wrapper.appendChild(img);
        wrapper.appendChild(removeBtn);

        anteprimaContainer.appendChild(wrapper);
      };
      reader.readAsDataURL(file);
    });

    conteggioFoto.textContent = `${fileList.length} / 3 immagini caricate`;
  }

  // Reset su submit + apparizione modale
  const formUfficio = document.getElementById('form-ufficio');
let pendingSubmit = false;

formUfficio.addEventListener('submit', function (e) {
  e.preventDefault();
  pendingSubmit = true;

  const confermaModale = new bootstrap.Modal(document.getElementById('confermaModale'));
  confermaModale.show();
});

document.getElementById('confermaSubmit').addEventListener('click', function () {
  if (!pendingSubmit) return;

  // Chiudi primo modale
  const confermaModale = bootstrap.Modal.getInstance(document.getElementById('confermaModale'));
  confermaModale.hide();

  // Simulazione invio + reset form
  formUfficio.reset();
  fileList = [];
  anteprimaContainer.innerHTML = '';
  conteggioFoto.textContent = '0 / 3 immagini caricate';

  if (!altroCheckbox.checked) {
    campoAltro.classList.add('d-none');
  }

  // Apri il secondo modale di successo
  const successoModale = new bootstrap.Modal(document.getElementById('successoModale'));
  successoModale.show();

  pendingSubmit = false;
});
