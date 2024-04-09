document.getElementById('simpleBtn').addEventListener('click', function() {
    document.getElementById('simpleOptions').style.display = 'block';
    document.getElementById('advancedOptions').style.display = 'none';
});

document.getElementById('advancedBtn').addEventListener('click', function() {
    document.getElementById('simpleOptions').style.display = 'none';
    document.getElementById('advancedOptions').style.display = 'block';
});

window.setPredefinedDate = function setPredefinedDate(range) {
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    let fromDate, toDate;

    switch(range) {
        case 'today':
            fromDate = toDate = today;
            break;
        case 'yesterday':
            fromDate = toDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
            break;
        case 'last_7_days':
            fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 6);
            toDate = today;
            break;
        case 'last_30_days':
            fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 29);
            toDate = today;
            break;
    }

    document.getElementById('from').value = fromDate.toISOString().split('T')[0];
    document.getElementById('to').value = toDate.toISOString().split('T')[0];
}
