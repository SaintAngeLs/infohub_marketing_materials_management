$(document).ready(function() {

    Dropzone.autoDiscover = false;

    $('#file-upload-component form').validate({
        rules: {
            menu_id: {
                required: true,
            },
            name: {
                required: true,
                minlength: 3,
            },
            file: {
                required: true,
            },
            file_url: {
                required: function(element) {
                    return $('#file_source').val() === 'file_external';
                },
                url: true
            },
            server_file: {
                required: function(element) {
                    return $('#file_source').val() === 'file_server';
                }
            },
            start: {
                date: true,
            },
            end: {
                date: true,
            },
            key_words: {
                required: false,
            },
            auto_id: {
                required: false,
            }
        },
        messages: {
            menu_id: {
                required: "To pole jest wymagane."
            },
            name: {
                required: "Proszę podać nazwę pliku.",
                minlength: "Nazwa pliku musi zawierać przynajmniej 3 znaki."
            },
            file: {
                required: "Proszę przesłać plik.",
            },
            file_url: {
                required: "Proszę podać URL pliku.",
                url: "Proszę wprowadzić prawidłowy URL."
            },
            server_file: {
                required: "To pole jest wymagane."
            },
            start: {
                date: "Proszę podać prawidłową datę."
            },
            end: {
                date: "Proszę podać prawidłową datę."
            },
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        errorPlacement: function(error, element) {
            if(element.attr("name") == "file") {
                error.insertAfter("#dropzoneFileUpload");
            } else {
                error.insertAfter(element);
            }
        }
    });

    var dropzoneOptions = {
        url: "/menu/files/store",
        method: "post",
        paramName: "file",
        maxFilesize: 700, // MB
        uploadMultiple: false,
        dictDefaultMessage: "Przysuń tu pliki",
        autoProcessQueue: false,
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
                window.location.href = '/menu/files';
            });
            this.on("error", function(file, response) {
                console.error(response);
            });
            this.on("sending", function(file, xhr, formData) {
                formData.append("menu_id", $("#file-upload-component #menu_id").val());
                formData.append("name", $("#file-upload-component #file_name").val());
                formData.append("visible_from", $("#file-upload-component #visible_from").val());
                formData.append("visible_to", $("#file-upload-component #visible_to").val());
                formData.append("key_words", $("#file-upload-component #key_words").val());
                formData.append("auto_id", $("#file-upload-component #auto_id").val());
                formData.append("file_source", $("#file-upload-component #file_source").val());

                if ($("#file_source").val() === "file_external") {
                    formData.append("file_url", $("#file-upload-component #input_file_external input").val());
                }

                if ($("#file_source").val() === "file_server") {
                    formData.append("server_file", $("#file-upload-component #input_server_file input").val());
                }
            });
        }
    };

    var myDropzone = new Dropzone("#dropzoneFileUpload", dropzoneOptions);


    var modal = document.getElementById('serverFilesModal');

    $('#browseServerFilesButton').click(function() {
        $.ajax({
            url: '/menu/files/directory-structure',
            type: 'GET',
            success: function(response) {
                var list = $('#serverFileList');
                list.empty();

                console.log(response); // For debugging

                // Assuming the structure is correct and using 'ftp_upload' as the key
                if (response['ftp_upload'] && response['ftp_upload'].length > 0) {
                    response['ftp_upload'].forEach(function(file) {
                        var fileLink = $('<a href="#">').text(file.name).click(function(e) {
                            e.preventDefault();
                            populateFileField(file.id, file.name);
                        });
                        var listItem = $('<li>').append(fileLink);
                        list.append(listItem);
                    });
                } else {
                    list.append($('<li>').text("No files available"));
                }

                $('#serverFilesModal').show();
            },
            error: function(error) {
                console.error("Error fetching directory structure: ", error);
                alert('Could not fetch directory structure. Please try again later.');
            }
        });
    });


    function populateFileField(fileId, fileName) {
        $('#server_file_input').val(fileId);
        $('#selectedFileName').val(fileName);
        $('#serverFilesModal').hide();
    }


    var closeButton = document.getElementsByClassName("close-button")[0];
    closeButton.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function toggleFileSource(source) {
        $('#input_file_pc').hide();
        $('#input_file_external').hide();
        $('#input_server_file').hide();


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



    $('#file-upload-component form').on('submit', function(e) {
        e.preventDefault();

        // Manually update FormData with form fields
        var formData = new FormData(this);
        myDropzone.files.forEach(function(file) {
            formData.append('file', file);
        });

        // Additional fields can be appended to formData if necessary
        formData.append('additionalField', 'Additional Value');

        // Check if files need to be uploaded
        if (myDropzone.getQueuedFiles().length > 0) {
            myDropzone.on('sending', function(file, xhr, formData) {
                // Append form data to each file upload request
                var data = $('#file-upload-component form').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });
            });

            myDropzone.on('queuecomplete', function() {
                // Handle completion
                console.log('Files uploaded');
                // Optionally, you can trigger a refresh or redirect here
            });

            // Process the file upload queue, which will also submit the form data
            myDropzone.processQueue();
        } else {
            $.ajax({
                url: $('#file-upload-component form').attr('action'),
                type: $('#file-upload-component form').attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Success:', response);
                    window.location.href = '/menu/files';
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                }
            });
        }
    });

});
