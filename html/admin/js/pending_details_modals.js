// Modal Tabler (usa Tabler JS per gestire i modal)
const modalApproveEl = document.getElementById('modalApprove');
const modalRejectEl = document.getElementById('modalReject');

const modalApprove = new tabler.Modal(modalApproveEl);
const modalReject = new tabler.Modal(modalRejectEl);

document.getElementById('btnApprove').addEventListener('click', () => {
    modalApprove.show();
});

document.getElementById('btnReject').addEventListener('click', () => {
    modalReject.show();
});

document.getElementById('confirmApprove').addEventListener('click', () => {
    modalApprove.hide();
    const approveForm = document.createElement("form")
    approveForm.setAttribute("action", "/admin/offices/pending/{{ office.id }}/approve")
    approveForm.setAttribute("method", "POST")
    document.body.appendChild(approveForm);
    approveForm.submit()
});

document.getElementById('rejectForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const reason = document.getElementById('rejectReason').value.trim();
    if (!reason) {
        alert('Inserisci una motivazione valida.');
        return;
    }
    modalReject.hide();
    const rejectForm = document.createElement("form")
    const reasonInput = document.createElement("input")
    reasonInput.setAttribute("type", "text")
    reasonInput.setAttribute("name", "reason")
    reasonInput.setAttribute("value", reason)
    rejectForm.append(reasonInput)
    rejectForm.setAttribute("action", "/admin/offices/pending/{{ office.id }}/reject")
    rejectForm.setAttribute("method", "POST")
    document.body.appendChild(rejectForm);
    rejectForm.submit()
});