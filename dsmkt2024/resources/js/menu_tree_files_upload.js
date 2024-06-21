$(document).ready(function() {
    function updateNodePadding(instance, nodeId) {
        var selector = nodeId ? '#' + nodeId + ' > .jstree-children' : '#menu-tree .jstree-node';
        $(selector).each(function() {
            var depth = instance.get_node($(this).closest('.jstree-node')).parents.length;
            var paddingLeft = 5 + depth * 3;
            $(this).css('padding-left', paddingLeft + 'px');
        });
    }

    function openFileUploadPage(menuItemId) {
        window.location.href = `/menu/files/create?menu_item_id=${menuItemId}`;
    }

    window.openFileUploadPage = openFileUploadPage;

    $(document).on('click', '.node-name', function(e) {
        e.stopPropagation();
        var nodeId = $(this).closest('.jstree-node').attr('id');
        window.location.href = '/menu/edit/' + nodeId;
    });

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

    $(document).on('click', '.file-link', function(e) {
        e.preventDefault();
        var fileId = $(this).data('file-id');
        if (fileId) {
            window.location.href = `/menu/file/edit/${fileId}`;
        }
    });

    var deleteFileButton = document.getElementById('deleteFileButton');
    if (deleteFileButton) {
        deleteFileButton.addEventListener('click', function() {
            if (confirm('Czy na pewno chcesz usunąć ten plik?')) {
                window.location.href = '{{ $deleteAction }}';
            }
        });
    }

    $('#browseServerFilesButton').click(function() {
        $.ajax({
            url: '/menu/files/directory-structure',
            type: 'GET',
            success: function(data) {
                var list = $('#serverFileList');
                list.empty();
                data.forEach(function(file) {
                    list.append('<li>' + file.name + ' (' + file.path + ')</li>');
                });
                modal.style.display = "block";
            },
            error: function(error) {
                console.error("Error fetching directory structure: ", error);
                alert('Could not fetch directory structure. Please try again later.');
            }
        });
    });

    $(document).on('click', '.toggle-file-status', function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Status toggle clicked');

        var fileId = $(this).data('file-id');
        console.log('File ID:', fileId);

        var element = $(this);

        $.ajax({
            url: '/menu/file/toggle-status/' + fileId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    var newStatusText = response.newStatus ? 'Aktywny' : 'Nieaktywny';
                    element.text(newStatusText);
                    console.log('Status updated successfully.');
                } else {
                    alert('Failed to update status. Server responded with error.');
                }
            },
            error: function(xhr) {
                console.error('Error updating status:', xhr.responseText);
                alert('Error updating status. Please check the console for more details.');
            }
        });
    });
});
