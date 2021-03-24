// Wait until the page is loaded.
$(window).ready(function () {

    // Link the event with the username input.
    $("#registerForm").submit(function (event) {
        event.preventDefault();
        // Check the password.
/*
        if ($('#password').val() !== $('repassword').val()) {
            alert('')
        }
*/
        // Send a post request.
        $.ajax({
            method: 'POST',
            url: '/v1/user',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify({
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                birthday: $('#year').val() + '-' + $('#month').val() + '-' + $('#day').val(),
                policy: $('#policy').val() ? 'Y' : 'N'
            }),
            success: function (data) {
                $('#registerForm').replaceWith('<p id="welcome"><a href="/index">' + data.result + '</a></p>');
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    });

});