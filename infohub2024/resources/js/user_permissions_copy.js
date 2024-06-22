document.getElementById('copy-permissions-from-user').addEventListener('click', function() {
    window.location.href = "/menu/permissions/copy/from-user";
});

document.getElementById('copy-permissions-from-group').addEventListener('click', function() {
    window.location.href = "{{ route('admin.permissions.copy.from.group') }}";
});
