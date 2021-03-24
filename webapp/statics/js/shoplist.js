// Wait until the page is loaded.
$(window).ready(function () {

    // Hide the template node.
    document.getElementById('giftTemplate').setAttribute('style', 'display:none');

    // Get the gifts for the current user.
    $.ajax({
        method: 'GET',
        contentType: 'application/json; charset=UTF-8',
        url: '/v1/gift',
        data: {
            owner: 'me'
        },
        success: function (data) {
            if (data.num > 0) {
                document.getElementById('info').setAttribute('style', 'display:none');
                iterator('giftTemplate', data.gifts);
            } else {
                document.getElementById('info').innerHTML = data.gifts;
                document.getElementById('info').removeAttribute('style');
            }
        },
        error: function (data) {
            alert(data.responseJSON.error.msg);
        }
    });

});