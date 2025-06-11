const anchorTag = document.getElementById("submitAnchor")
anchorTag.addEventListener('click', (e) => {
    e.preventDefault()
    const form = document.getElementById("deleteForm")
    const checkbox = document.getElementById("shouldRefundCheckbox")
    const hiddenInput = document.getElementById("shouldRefundHidden")
    if (checkbox) {
        hiddenInput.value = checkbox.checked ? 1 : 0
    }

    form.submit()
})