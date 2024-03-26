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
                formData.append("file_location", $("input[name='file_location']:checked").val());
            });
        }
    };

    var myDropzone = new Dropzone("#dropzoneFileUpload", dropzoneOptions);
});
