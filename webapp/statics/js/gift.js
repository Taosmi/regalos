    // Delete a gift by ID.
var deleteGift = function () {
        var id = $('#gift').attr('data-giftid');
        // Send a delete request.
        $.ajax({
            method: 'DELETE',
            url: '/v1/gift/' + id,
            contentType: 'application/json; charset=UTF-8',
            success: function () {
                document.location = '/shopList';
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    },
    // Create a new gift.
    newGift = function () {
        // Send a create request.
        $.ajax({
            method: 'POST',
            url: '/v1/gift',
            contentType: 'application/json; charset=UTF-8',
            data: {
                title: $('#title').val(),
                description: $('#description').val(),
                link: $('#link').val(),
                privacy: $('input[name=privacy]:checked').val(),
                image: $('#loadImg').attr('data-urlimg') || $('#loadImg').attr('data-img')
            },
            success: function () {
                document.location = '/shopList';
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    },
    // Modify a gift.
    modifyGift = function (id) {
        // Send a modify request.
        $.ajax({
            method: 'PUT',
            url: '/v1/gift/' + id,
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify({
                title: $('#title').val(),
                description: $('#description').val(),
                link: $('#link').val(),
                privacy: $('input[name=privacy]:checked').val(),
                image: $('#loadImg').attr('data-urlimg') || $('#loadImg').attr('data-img')
            }),
            success: function (data) {
                document.location = '/shopList';
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    },
    // Get a gift information.
    loadGift = function (id) {
        // Send a get request.
        $.ajax({
            method: 'GET',
            url: '/v1/gift/' + id,
            contentType: 'application/json; charset=UTF-8',
            success: function (data) {
                if (data.gift) {
                    $('#title').val(data.gift.title);
                    $('#description').val(data.gift.description);
                    $('#link').val(data.gift.link);
                    $('input[value="' + data.gift.privacy + '"]').attr('checked', 'checked');
                    $('#loadImg').css('background-image', 'url(' + data.gift.image + ')');
                    $('#loadImg').attr('data-urlimg', data.gift.image);
                }
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    };

// Wait until the page is loaded.
$(window).ready(function () {

    // Get a file reader for the gift image.
    var reader = new FileReader();

    // Get the gift ID.
    var giftId = $('#gift').attr('data-giftid');

    // If no gift ID, hide the delete button (create gift).
    // If gift ID, setup modify gift options.
    if (!giftId) {
        $('#delete').hide();
    } else {
        loadGift(giftId);
        $('#delete').click(deleteGift);
    }

    // Create or modify gift event.
    $('#gift').submit(function (event) {
        event.preventDefault();
        if (giftId) {
            modifyGift(giftId);
        } else {
            newGift();
        }
    });

    // File reader load event.
    reader.onload = function (file) {
        $('#loadImg').css('background-image','url(' + file.target.result + ')');
        $('#loadImg').attr('data-img', file.target.result);
        $('#loadImg').attr('data-urlimg', '');
    };
    // Load image popup event.
    $('#loadFileButton').click(function () {
        $('#fileInput').click();
    });
    $('#fileInput').change(function (event) {
        reader.readAsDataURL(event.target.files[0]);
    });
    // From URL event.
    $('#loadURLButton').click(function () {
        // Get the image URL.
        var url = prompt($('#loadURLButton').attr('data-msg'));
        // Set the image.
        if (url) {
            $('#loadImg').css('background-image','url(' + url + ')');
            $('#loadImg').attr('data-urlimg', url);
            $('#loadImg').attr('data-img', '');
        }
    })
});