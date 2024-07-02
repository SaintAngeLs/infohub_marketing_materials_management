$(document).ready(function() {
    $("#forgot-password-form").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "Pole adresu e-mail jest wymagane.",
                email: "Proszę wpisać prawidłowy adres e-mail."
            }
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
                type: $(form).attr('method'),
                data: $(form).serialize(),
                success: function(response) {
                    if(response.status === 'success') {
                        window.location.href = "{{ route('password.thankyou') }}";
                    } else {
                        alert('Failed to send reset link. Please try again.');
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    }
                    alert(errorMessage);
                }
            });
            return false;
        }
    });
});
