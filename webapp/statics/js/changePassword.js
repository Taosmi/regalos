// Wait until the page is loaded.
$(window).ready(function () {

    // Login form submit.
    $('#changeForm').submit(function (event) {
        event.preventDefault();
        // Send new password.
        $.ajax({
            method: 'POST',
            url: '/v1/recover',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify({
                password: $('#password').val(),
            }),
            success: function (data) {
                document.location = '/options';
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    });

});
