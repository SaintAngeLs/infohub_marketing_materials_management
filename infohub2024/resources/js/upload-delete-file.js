$(document).ready(function() {
    // Attach event listeners to buttons
    $(document).on('click', '.download-file-btn', function() {
        var fileId = $(this).data('file-id');
        downloadFile(fileId);
    });

    $(document).on('click', '.delete-file-btn', function() {
        var fileId = $(this).data('file-id');
        deleteFile(fileId);
    });
});

function downloadFile(fileId) {
    // Logic to handle file download
    window.location.href = `/menu/files/download/${fileId}`;
}

function deleteFile(fileId) {
    if (confirm('Czy na pewno chcesz usunąć ten plik?')) {
        $.ajax({
            url: `/files/delete/${fileId}`,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function() {
                alert('Plik został usunięty.');
                window.location.reload(); // Or any other logic to update the UI
            },
            error: function() {
                alert('Wystąpił błąd podczas usuwania pliku.');
            }
        });
    }
}

window.downloadFile = downloadFile;
window.deleteFile = deleteFile;
