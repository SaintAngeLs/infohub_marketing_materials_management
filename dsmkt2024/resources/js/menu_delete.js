// Import necessary Bootstrap components
import { Modal } from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

$(document).ready(function() {
    // document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.getElementById('delete-menu-item');
        const deleteConfirmationModalElement = document.querySelector('#deleteConfirmationModal');
        const deleteConfirmationModal = new Modal(deleteConfirmationModalElement);
        const subTabWarningElement = document.getElementById('subTabWarning');
        const menuItemId = deleteButton.getAttribute('data-menu-item-id'); // Ensure your delete button has this data attribute

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
    // });

});
