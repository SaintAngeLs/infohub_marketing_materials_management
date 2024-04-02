$(document).ready(function() {
    $('#create-menu-item-form').validate({
        rules: {
            type: {
                required: true
            },
            name: {
                required: true,
                maxlength: 255
            },
            parent_id: {},
            'owners[]': {},
            start: {
                date: true
            },
            end: {
                date: true,
                greaterThan: "#start"
            },
            banner: {
                required: true
            }
        },
        messages: {
            type: {
                required: "Proszę wybrać typ zakładki"
            },
            name: {
                required: "Proszę wprowadzić nazwę zakładki",
                maxlength: "Nazwa musi być krótsza niż 255 znaków"
            },
            banner: {
                required: "Proszę wybrać przypisanie banera"
            },
            start: {
                date: "Proszę wprowadzić prawidłową datę"
            },
            end: {
                date: "Proszę wprowadzić prawidłową datę",
                greaterThan: "Data końca musi być późniejsza niż data początku"
            }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        errorPlacement: function(error, element) {
            if (element.next(".invalid-feedback").length) {
                element.next(".invalid-feedback").replaceWith(error);
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: $(form).serialize(),
                success: function(response) {

                    console.log('Formularz został pomyślnie przesłany.');
                    window.location.href = '/menu/structure';
                },
                error: function(xhr, status, error) {
                    console.error('Wystąpił błąd przy przesyłaniu formularza: ' + error);
                }
            });
        }
    });

    $.validator.addMethod("greaterThan",
    function(value, element, param) {
        var $min = $(param);
        if (this.settings.onfocusout) {
            $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function() {
                $(element).valid();
            });
        }
        return Date.parse(value) > Date.parse($min.val());
    }, "Data końca musi być późniejsza niż data początku.");
});
