// Import necessary Bootstrap components
import { Modal } from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

$(document).ready(function() {
    const deleteButton = document.getElementById('delete-menu-item');
    const deleteConfirmationModalElement = document.querySelector('#deleteConfirmationModal');
    const deleteConfirmationModal = new Modal(deleteConfirmationModalElement);
    const subTabWarningElement = document.getElementById('subTabWarning');
    const confirmDeleteButton = document.getElementById('confirmDelete');
    const menuItemId = deleteButton.getAttribute('data-menu-item-id');

    deleteButton.addEventListener('click', function() {
        fetch(`/menu/menu-items/${menuItemId}/has-sub-items`)
            .then(response => response.json())
            .then(data => {
                if(data.hasSubItems) {
                    subTabWarningElement.style.display = 'block';
                } else {
                    subTabWarningElement.style.display = 'none';
                }
                deleteConfirmationModal.show();
            })
            .catch(error => console.error('Error:', error));
    });

    confirmDeleteButton.addEventListener('click', function() {
        fetch(`/menu/menu-items/${menuItemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '/menu/structure';
            } else {
                alert('Failed to delete the menu item.');
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            deleteConfirmationModal.hide();
        });
    });
});
