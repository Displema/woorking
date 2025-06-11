const tabAttiveBtn = document.getElementById('tabAttiveBtn');
const tabChiuseBtn = document.getElementById('tabChiuseBtn');
const tabAttive = document.getElementById('tabAttive');
const tabChiuse = document.getElementById('tabChiuse');

tabAttiveBtn.addEventListener('click', (e) => {
    e.preventDefault();
    tabAttive.classList.remove('d-none');
    tabAttive.classList.add('active');
    tabChiuse.classList.add('d-none');
    tabChiuse.classList.remove('active');
    tabAttiveBtn.classList.add('active');
    tabChiuseBtn.classList.remove('active');
})

tabChiuseBtn.addEventListener('click', (e) => {
    e.preventDefault();
    tabChiuse.classList.remove('d-none');
    tabChiuse.classList.add('active');
    tabAttive.classList.add('d-none');
    tabAttive.classList.remove('active');
    tabChiuseBtn.classList.add('active');
    tabAttiveBtn.classList.remove('active');
})