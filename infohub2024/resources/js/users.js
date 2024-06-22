
function togglePasswordFields(select) {
    const display = select.value === 'yes' ? 'block' : 'none';
    document.getElementById('password_fields').style.display = display;
}

window.togglePasswordFields = togglePasswordFields ;
