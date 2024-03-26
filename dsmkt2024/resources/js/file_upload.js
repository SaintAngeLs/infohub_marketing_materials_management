$(document).ready(function() {
    // Initialize Dropzone
    var dropzoneOptions = {
        url: "/upload", // Specify your upload URL
        maxFilesize: 2, // Max filesize in MB
        dictDefaultMessage: "Drop files here to upload", // Custom message
        // Add more options based on your requirements
    };

    var myDropzone = new Dropzone("#dropzoneFileUpload", dropzoneOptions);
});
