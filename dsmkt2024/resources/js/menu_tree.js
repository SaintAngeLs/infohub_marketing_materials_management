// import 'jquery';
// import $ from 'jquery';

$(document).ready(function() {
    $('#menu-tree').jstree({
        'core': {
            'data': {
                'url': '/menu/get-menu-items',
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
    }).on('ready.jstree', function (e, data) {
        updateNodePadding(data.instance);
    }).on('open_node.jstree', function (e, data) {
        updateNodePadding(data.instance, data.node.id);
    });

    function updateNodePadding(instance, nodeId) {
        var selector = nodeId ? '#' + nodeId + ' > .jstree-children' : '#menu-tree .jstree-node';
        $(selector).each(function() {
            var depth = instance.get_node($(this).closest('.jstree-node')).parents.length;
             // Base padding + 5px for each level
            var paddingLeft = 10 + depth * 5;
            $(this).css('padding-left', paddingLeft + 'px');
        });
    }
});
$(document).on('click', '.node-name', function(e) {
    e.stopPropagation();
    var nodeId = $(this).closest('.jstree-node').attr('id');
    window.location.href = '/menu/edit/' + nodeId;
});

// Toggle status on status details click
$(document).on('click', '.node-details-status', function(e) {
    e.stopPropagation();
    var nodeId = $(this).closest('.jstree-node').attr('id');

    $.ajax({
        url: '/menu/toggle-status/' + nodeId,
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            alert('Status updated successfully');
            location.reload();
        },
        error: function(xhr) {
            alert('Error updating status');
        }
    });
});
