$(document).ready(function() {
    $("#accessRequestForm").validate({
        rules: {
            company_name: "required",
            first_name: "required",
            last_name: "required",
            email: {
                required: true,
                email: true
            },
            phone: "required"
        },
        messages: {
            company_name: "Proszę wpisać nazwę firmy",
            first_name: "Proszę wpisać imię",
            last_name: "Proszę wpisać nazwisko",
            email: {
                required: "Proszę wpisać adres email",
                email: "Proszę wpisać poprawny adres email"
            },
            phone: "Proszę wpisać numer telefonu"
        },
        errorElement: 'div',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            if (element.prop('type') === 'checkbox') {
                error.insertAfter(element.next('label'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    alert('Access request submitted successfully.');
                    window.location.href = '/access-request-thank-you';
                    $(form)[0].reset();
                },
                error: function(xhr, status, error) {
                    var errorMsg = xhr.responseJSON.message || 'Zaistniało miejsce dla nieprzewidywalnego błędu.';
                    alert(errorMsg);
                }
            });
            return false;
        }
    });
});
