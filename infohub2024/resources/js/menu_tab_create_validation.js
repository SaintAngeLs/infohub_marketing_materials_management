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
            slug: {
                required: true
            },
            parent_id: {
                required: function(element) {
                    return $("#type").val() === "sub";
                }
            },
            'owners[]': {},
            visibility_start: {
                date: true
            },
            visibility_end: {
                date: true,
                greaterThan: "#start"
            },
            banner: {
                required: true
            },
            status: {
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
            slug: {
                required: "Proszę wprowadzić tagi zakładki"
            },
            parent_id: {
                required: "Proszę wybrać element nadrzędny dla podrzędnej zakładki"
            },
            banner: {
                required: "Proszę wybrać przypisanie banera"
            },
            visibility_start: {
                date: "Proszę wprowadzić prawidłową datę"
            },
            visibility_end: {
                date: "Proszę wprowadzić prawidłową datę",
                greaterThan: "Data końca musi być późniejsza niż data początku"
            },
            status: {
                required: "Proszę wybrać status"
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
            var errorPlaceholder = element.closest('.form-group').find('.error-placeholder');
            if (errorPlaceholder.length) {
                errorPlaceholder.html(error);
            } else {
                error.insertAfter(element.closest('.form-group').find('label'));
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

    $.validator.addMethod("greaterThan", function(value, element, param) {
        var startDateValue = $(param).val();
        var endDateValue = value;
        function isDateInvalidOrEmpty(dateValue) {
            var regex = /^(dd\/mm\/yyyy|)$/i;
            return regex.test(dateValue);
        }

        if (isDateInvalidOrEmpty(startDateValue) || isDateInvalidOrEmpty(endDateValue)) {
            return true;
        }

        return Date.parse(endDateValue) > Date.parse(startDateValue);
    }, "Data końca musi być późniejsza niż data początku.");

    $('#submit-form-button').click(function(event) {
        event.preventDefault();
        if($('#create-menu-item-form').valid()) {
            $('#create-menu-item-form').submit();
        }
    });

});
