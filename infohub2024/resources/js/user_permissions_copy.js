$(document).ready(function() {
    var copyPermissionsFromUser = document.getElementById('copy-permissions-from-user');
    if (copyPermissionsFromUser) {
        copyPermissionsFromUser.addEventListener('click', function() {
            window.location.href = "/menu/permissions/copy/from-user";
        });
    }

    var copyPermissionsFromGroup = document.getElementById('copy-permissions-from-group');
    if (copyPermissionsFromGroup) {
        copyPermissionsFromGroup.addEventListener('click', function() {
            window.location.href = "{{ route('admin.permissions.copy.from.group') }}";
        });
    }
});
