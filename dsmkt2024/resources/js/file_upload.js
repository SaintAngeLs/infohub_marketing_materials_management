$(document).ready(function() {
    // Initialize Dropzone
    var dropzoneOptions = {
        url: "/menu/files/store",
        method: "post",
        paramName: "file",
        maxFilesize: 700, // MB
        dictDefaultMessage: "Przysuń tu pliki",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            var myDropzone = this;

            if (existingFileUrl && existingFileName) {
                var mockFile = { name: existingFileName, size: 12345, accepted: true };
                myDropzone.files.push(mockFile);
                myDropzone.emit("addedfile", mockFile);
                myDropzone.emit("thumbnail", mockFile, existingFileUrl);
                myDropzone.emit("complete", mockFile);
                myDropzone._updateMaxFilesReachedClass();
            }

            this.on("addedfile", function(file) {
                if (this.files.length > 1 && this.files[0].name === existingFileName) {
                    this.removeFile(this.files[0]);
                }
            });

            this.on("success", function(file, response) {
                console.log(response);
            });
            this.on("error", function(file, response) {
                console.error(response);
            });
            this.on("sending", function(file, xhr, formData) {
                // Append additional form data on file send
                formData.append("menu_id", $("#menu_id").val());
                formData.append("name", $("#file_name").val());
                formData.append("visible_from", $("#visible_from").val());
                formData.append("visible_to", $("#visible_to").val());
                formData.append("tags", $("#tags").val());
                formData.append("auto_id", $("#auto_id").val());
                formData.append("file_source", $("#file_source").val());
                // formData.append("file_location", $("input[name='file_location']:checked").val());

                if ($("#file_source").val() === "file_external") {
                    formData.append("file_url", $("#input_file_external input").val());
                }

                // Only for server file
                if ($("#file_source").val() === "file_server") {
                    formData.append("server_file", $("#input_server_file select").val());
                }
            });
        }
    };

    var myDropzone = new Dropzone("#dropzoneFileUpload", dropzoneOptions);

    function toggleFileSource(source) {
        // Hide all inputs initially
        $('#input_file_pc').hide();
        $('#input_file_external').hide();
        $('#input_server_file').hide();

        // Show the selected input
        if (source === 'file_pc') {
            $('#input_file_pc').show();
        } else if (source === 'file_external') {
            $('#input_file_external').show();
        } else if (source === 'file_server') {
            $('#input_server_file').show();
        }
    }

    $('#file_source').change(function() {
        toggleFileSource($(this).val());
    });

    toggleFileSource($('#file_source').val());



    $('form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]);
        }
        var formAction = $(this).attr('action'); // URL to which the request is sent
        var formMethod = $(this).attr('method'); // GET or POST

        myDropzone.files.forEach(function(file, index) {
            formData.append('file[' + index + ']', file, file.name);
        });


        // Since Dropzone handles file uploads asynchronously,
        // make sure all files are uploaded before submitting the form data
        if (myDropzone.getQueuedFiles().length > 0) {
            myDropzone.on("queuecomplete", function() {
                // After all files are uploaded then send form data
                submitFormData(formData, formAction, formMethod);
            });
            myDropzone.processQueue(); // Start the upload of files in Dropzone's queue
        } else {
            // If there are no files in Dropzone's queue, just submit the form data
            submitFormData(formData, formAction, formMethod);
        }
    });

    function submitFormData(formData, url, method) {
        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false, 
            success: function(response) {

                console.log('Success:', response);

            },
            error: function(jqXHR, textStatus, errorThrown) {

                console.error('Error:', textStatus, errorThrown);

            }
        });
    }
});
