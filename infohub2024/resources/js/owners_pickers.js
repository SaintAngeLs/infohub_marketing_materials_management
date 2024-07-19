$(document).ready(function() {
    const allUsersList = document.getElementById('all-users');
    const selectedOwnersList = document.getElementById('selected-owners');
    const ownersInput = document.getElementById('owners-input');

    function updateOwnersInput() {
        if (selectedOwnersList) {
            const ownerIds = Array.from(selectedOwnersList.querySelectorAll('.picklist-item'))
                .map(item => item.getAttribute('data-user-id'));
            if (ownersInput) {
                ownersInput.value = JSON.stringify(ownerIds);
            }
        }
    }

    const addButton = document.getElementById('add-button');
    if (addButton) {
        addButton.addEventListener('click', function () {
            if (allUsersList && selectedOwnersList) {
                Array.from(allUsersList.querySelectorAll('.picklist-item.selected')).forEach(item => {
                    selectedOwnersList.appendChild(item);
                    item.classList.remove('selected');
                    updateOwnersInput();
                });
            }
        });
    }

    const removeButton = document.getElementById('remove-button');
    if (removeButton) {
        removeButton.addEventListener('click', function () {
            if (allUsersList && selectedOwnersList) {
                Array.from(selectedOwnersList.querySelectorAll('.picklist-item.selected')).forEach(item => {
                    allUsersList.appendChild(item);
                    item.classList.remove('selected');
                    updateOwnersInput();
                });
            }
        });
    }

    if (allUsersList) {
        allUsersList.addEventListener('click', function (event) {
            if (event.target.classList.contains('picklist-item')) {
                event.target.classList.toggle('selected');
            }
        });
    }

    if (selectedOwnersList) {
        selectedOwnersList.addEventListener('click', function (event) {
            if (event.target.classList.contains('picklist-item')) {
                event.target.classList.toggle('selected');
            }
        });
    }

    updateOwnersInput();
});
