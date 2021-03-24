    // Load the gifts.
var loadGifts = function () {
    $.ajax({
        method: 'GET',
        contentType: 'application/json; charset=UTF-8',
        url: '/v1/gift',
        data: {
            username: $('#username').val()
        },
        success: function (data) {
            var splitData;
            if (data.num > 0) {
                document.getElementById('info').setAttribute('style', 'display:none');
                splitData = splitArray(data.gifts, 3);
                iterator('giftTemplate1', splitData[0]);
                iterator('giftTemplate2', splitData[1]);
                iterator('giftTemplate3', splitData[2]);
            } else {
                document.getElementById('info').innerHTML = data.gifts;
                document.getElementById('info').removeAttribute('style');
                removeSiblings(document.getElementById('giftTemplate1'));
                removeSiblings(document.getElementById('giftTemplate2'));
                removeSiblings(document.getElementById('giftTemplate3'));
            }
        },
        error: function (data) {
            alert(data.responseJSON.error.msg);
        }
    });
};

// Wait until the page is ready.
$(window).ready(function () {
    // Hide the template node.
    document.getElementById('giftTemplate1').setAttribute('style', 'display:none');
    document.getElementById('giftTemplate2').setAttribute('style', 'display:none');
    document.getElementById('giftTemplate3').setAttribute('style', 'display:none');
    // Link the event with the username input.
    $("#username").keyup(loadGifts);
    // Load the gifts.
    loadGifts();
});
