function updateGroupPermission(checkboxElement) {
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
            alert('Permissions updated successfully.');
        },
        error: function(xhr, status, error) {
            console.error('Error updating permissions:', error);
        }
    });
}


$(document).ready(function() {
    var groupId = $('#group-id').val();
    console.log(groupId);

    $('#menu-tree-permissions').jstree({
        'core': {
           'data': {
                'url': function(node) {
                    return '/menu/users/get-menu-items-group-permissions?group_id=' + groupId;
                },
                'data': function(node) {
                    return { 'id': node.id };
                }
            },
            "check_callback": true,
        },
        "checkbox": {
            "keep_selected_style": false
        },
        "plugins": ["checkbox", "dnd", "wholerow"]
    }).on('ready.jstree', function (e, data) {
        updateNodePadding(data.instance);
        data.instance.open_all();
    }).on('ready.jstree', function (e, data) {
        updateNodePadding(data.instance);
    }).on('open_node.jstree', function (e, data) {
        updateNodePadding(data.instance, data.node.id);
    });




    function updateNodePadding(instance, nodeId) {
        var selector = nodeId ? '#' + nodeId + ' > .jstree-children' : '#menu-tree .jstree-node';
        $(selector).each(function() {
            var depth = instance.get_node($(this).closest('.jstree-node')).parents.length;
            var paddingLeft = 5 + depth * 3;
            $(this).css('padding-left', paddingLeft + 'px');
        });
    }
});

$(document).on('click', '.node-name', function(e) {
    e.stopPropagation();
    var nodeId = $(this).closest('.jstree-node').attr('id');
    window.location.href = '/menu/edit/' + nodeId;
});


$(document).ready(function() {
    $('#save-permissions').click(function() {
        var groupId = $('#group-id').val();
        var permissions = [];
        $('input[name="menu_permissions[]"]:checked').each(function() {
            permissions.push($(this).val());
        });

        $.ajax({
            url: '/menu/permissions/update-group-permission',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                group_id: groupId,
                permissions: permissions
            },
            success: function(response) {
                alert('Uprawnienia zapisane pomyślnie.');
                window.location.href = '/menu/users/usergroups';
            },
            error: function(xhr) {
                alert('Błąd podczas zapisywania uprawnień.');
            }
        });
    });
});
