window.updateGroupPermission = function updateGroupPermission(checkboxElement) {
    var menuId = $(checkboxElement).val();
    var groupId = $('#group-id').val();
    var isChecked = $(checkboxElement).is(':checked');
    var action = isChecked ? 'assign' : 'remove';

    $.ajax({
        url: '/menu/permissions/update-group-permission',
        method: 'POST',
        data: {
            menu_id: menuId,
            group_id: groupId,
            action: action,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alert('Uprawnienia zostały zmienione pomyślnie.');
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error updating permissions:', error);
        }
    });
}
