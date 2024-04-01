$(document).ready(function() {
    // Custom method for file size validation
    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, 'File size must be less than {0}');

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
        submitHandler: function(form) {
            form.submit();
        }
    });

    // Show/hide input fields based on file_source selection
    $('#file_source').change(function() {
        $('.file-source').hide();
        $('#input_' + $(this).val()).show();
    }).trigger('change');
});
