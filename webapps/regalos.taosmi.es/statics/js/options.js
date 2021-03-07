    // Modify a user.
var modifyUser= function (id) {
        // Send a modify request.
        $.ajax({
            method: 'PUT',
            url: '/v1/user/' + id,
            contentType: 'application/json; charset=UTF-8',
            data: {
                name: $('#name').val(),
                email: $('#email').val(),
                birthday: $('#year').val() + '-' + $('#month').val() + '-' + $('#day').val(),
                policy: $('#policy').prop('checked') ? 'Y' : 'N',
                image: $('#loadAvatar').attr('data-avatar')
            },
            success: function () {
                document.location = '/options';
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    },
    // Get a user information.
    loadUser = function (id) {
        // Send a get request.
        $.ajax({
            method: 'GET',
            url: '/v1/user/' + id,
            contentType: 'application/json; charset=UTF-8',
            success: function (data) {
                if (data.user) {
                    $('#name').val(data.user.name);
                    $('#email').val(data.user.email);
                    birthday = data.user.birthday.split('-');
                    $('#year').val(birthday[0]);
                    $('#month').val(birthday[1]);
                    $('#day').val(birthday[2]);
                    $('#policy').prop('checked', (data.user.policy === 'Y'));
                    $('#loadAvatar').css('background-image','url(/webapps/regalos.taosmi.es/statics/imgs/users/' + id + ')');
                }
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    };

// Wait until the page is loaded.
$(window).ready(function () {

    // Get a file reader for the avatar.
    var reader = new FileReader();

    // Get the user ID and his info.
    var userId = $('#user').attr('data-id');
    loadUser(userId);

    // Create or modify user event.
    $('#user').submit(function (event) {
        event.preventDefault();
        modifyUser(userId);
    });

    // Avatar load event.
    reader.onload = function (file) {
        $('#loadAvatar').css('background-image','url(' + file.target.result + ')');
        $('#loadAvatar').attr('data-avatar', file.target.result);
    };
    // Load avatar popup event.
    $('#loadAvatarButton').click(function () {
        $('#fileInput').click();
    });
    $('#fileInput').change(function (event) {
        reader.readAsDataURL(event.target.files[0]);
    });

});