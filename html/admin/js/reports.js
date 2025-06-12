const modalFooter = document.querySelector('.modal-footer')
const solveButton = modalFooter.querySelector('.btn-success')

solveButton.addEventListener('click', (e) => {
    e.preventDefault()
    const form = document.getElementById('resolveForm')
    form.submit()
})