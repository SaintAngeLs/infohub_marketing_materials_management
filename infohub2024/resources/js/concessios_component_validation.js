$(document).ready(function() {
    $('#concessions-component form').validate({
        rules: {
            name: "required",
            address: "required",
            code: {
                required: true,
                pattern: /^\d{2}-?\d{3}$/,
                minlength: 5,
                maxlength: 6
            },
            city: "required",
            phone: {
                required: true,
                pattern: /^\+?\d{9,14}$/,
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            name: "Proszę wpisać nazwę",
            address: "Proszę wpisać adres",
            code: {
                required: "Proszę wpisać kod pocztowy",
                minlength: "Kod pocztowy musi składać się z 5 znaków",
                maxlength: "Kod pocztowy musi składać się z 5 znaków"
            },
            city: "Proszę wpisać miasto",
            phone: {
                required: "Proszę wpisać numer telefonu",
                digits: "Proszę wpisywać tylko cyfry",
                minlength: "Numer telefonu musi składać się przynajmniej z 10 cyfr",
                maxlength: "Numer telefonu nie może przekraczać 15 cyfr"
            },
            email: "Proszę wpisać prawidłowy adres email"
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
        var fieldsWithError = ['name', 'address', 'code', 'city', 'phone', 'email'];

        fieldsWithError.forEach(function(field) {
            var errorSelector = `#concessions-component .${field}-error`;
            var inputSelector = `#concessions-component [name=${field}]`;
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
