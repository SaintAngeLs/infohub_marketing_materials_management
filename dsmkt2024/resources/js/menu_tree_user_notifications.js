$(document).ready(function() {
    var userId = $('#user-id').val();
    console.log(userId);

    $('#menu-tree-notifications').jstree({
        'core': {
           'data': {
                'url': function(node) {
                    return '/user/get-menu-items-user-notifications?user_id=' + userId;
                },
                'data': function(node) {
                    return { 'id': node.id };
                }
            },
            "check_callback": true,
        },
        "plugins": ["dnd", "contextmenu", "wholerow", "html_data"]
    }).on('ready.jstree', function (e, data) {
        updateNodePadding(data.instance);
        data.instance.open_all();

    // }).on('ready.jstree', function (e, data) {
    //     updateNodePadding(data.instance);
    // }).on('open_node.jstree', function (e, data) {
    //     updateNodePadding(data.instance, data.node.id);
    }).on('after_open.jstree', function(e, data) {
        // Rebind the radio change event handler to ensure it captures dynamically loaded nodes

    });

    // $(document).on('change', '#menu-tree-permissions-user .menu-item-checkbox', function() {
    //     var menuId = $(this).val();
    //     var userId = $('#user-id').val();
    //     console.log(userId);
    //     var isChecked = $(this).is(':checked');


    //     var action = isChecked ? 'assign' : 'remove';

    //     $.ajax({
    //         url: '/menu/permissions/update-user-permission',
    //         method: 'POST',
    //         data: {
    //             menu_id: menuId,
    //             user_id: userId,
    //             action: action,
    //             _token: $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             alert('Permissions updated successfully.');
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error updating permissions:', error);
    //         }
    //     });
    // });

    // $('#menu-tree-notifications').on('change', '.notification-preferences input[type="radio"]', function() {
    //     var menuItemId = $(this).closest('.js-tree-node-content').data('node-id');
    //     var frequency = $(this).val();

    //     $.ajax({
    //         url: '/user/update-menu-item-notification',
    //         method: 'POST',
    //         data: {
    //             menu_item_id: menuItemId,
    //             frequency: frequency,
    //             _token: $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             alert('Notification preference updated successfully.');
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error updating notification preference:', error);
    //         }
    //     });
    // });


    $('#menu-tree-notifications').on('change', '.notification-preferences input[type="radio"]', function() {
        var menuItemId = $(this).closest('.js-tree-node-content').data('node-id');
        var frequency = $(this).val();

        $.ajax({
            url: '/user/update-menu-item-notification',
            method: 'POST',
            data: {
                menu_item_id: menuItemId,
                frequency: frequency,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Notification preference updated successfully.');
            },
            error: function(xhr, status, error) {
                console.error('Error updating notification preference:', error);
            }
        });
    });


    // Immediately invoke to bind event to any existing nodes
    attachRadioChangeEvent();


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
    // e.stopPropagation();
    var nodeId = $(this).closest('.jstree-node').attr('id');
    window.location.href = '/menu/edit/' + nodeId;
});
