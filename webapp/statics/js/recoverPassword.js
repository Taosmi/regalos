// Wait until the page is loaded.
$(window).ready(function () {

    // Login form submit.
    $('#recoverForm').submit(function (event) {
        event.preventDefault();
        // Send recover email.
        $.ajax({
            method: 'GET',
            url: '/v1/recover',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify({
                email: $('#email').val(),
            }),
            success: function (data) {
                $('#recoverForm').replaceWith('<h1><a href="/index">' + data.result + '</a></h1>');
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    });

});
