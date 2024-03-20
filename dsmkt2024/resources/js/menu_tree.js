// import 'jquery';
// import $ from 'jquery';

$(document).ready(function() {
    $('#menu-tree').jstree({
        'core': {
            'data': {
                'url': '/get-menu-items',
                'data': function(node) {
                    return { 'id': node.id };
                }
            },
            "check_callback": true,
        },
        "plugins": ["dnd", "contextmenu"]
    }).on('select_node.jstree', function(e, data) {
        $('#parent_id').val(data.node.id);
    });
});
