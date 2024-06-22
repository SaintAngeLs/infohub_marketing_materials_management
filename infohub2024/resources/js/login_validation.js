$(document).ready(function() {
    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email: "Proszę wpisać prawidłowy adres email",
            password: {
                required: "Proszę wpisać hasło",
                minlength: "Hasło musi mieć przynajmniej 6 znaków"
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
            form.submit();
        },
    });

    function addServerSideErrors() {
        var fieldsWithError = ['email', 'password'];

        fieldsWithError.forEach(function(field) {
            var errorSelector = `.${field}-error`;
            var inputSelector = `[name=${field}]`;
            var errorMessage = $(errorSelector).text();

            if (errorMessage.length > 0) {
                $(inputSelector).addClass('is-invalid');
                var errorElement = $("<div></div>")
                    .addClass("invalid-feedback")
                    .text(errorMessage);
                $(inputSelector).after(errorElement);
            }
        });
    }

    addServerSideErrors();
});
