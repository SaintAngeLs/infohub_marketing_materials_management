$(document).ready(function() {
    const allUsersList = document.getElementById('all-users');
    const selectedOwnersList = document.getElementById('selected-owners');
    const ownersInput = document.getElementById('owners-input');

    function updateOwnersInput() {
        const ownerIds = Array.from(selectedOwnersList.querySelectorAll('.picklist-item'))
            .map(item => item.getAttribute('data-user-id'));
        ownersInput.value = JSON.stringify(ownerIds);
    }

    document.getElementById('add-button').addEventListener('click', function () {
        Array.from(allUsersList.querySelectorAll('.picklist-item.selected')).forEach(item => {
            selectedOwnersList.appendChild(item);
            item.classList.remove('selected');
            updateOwnersInput();
        });
    });

    document.getElementById('remove-button').addEventListener('click', function () {
        Array.from(selectedOwnersList.querySelectorAll('.picklist-item.selected')).forEach(item => {
            allUsersList.appendChild(item);
            item.classList.remove('selected');
            updateOwnersInput();
        });
    });

    allUsersList.addEventListener('click', function (event) {
        if (event.target.classList.contains('picklist-item')) {
            event.target.classList.toggle('selected');
        }
    });

    selectedOwnersList.addEventListener('click', function (event) {
        if (event.target.classList.contains('picklist-item')) {
            event.target.classList.toggle('selected');
        }
    });

    updateOwnersInput();
});
