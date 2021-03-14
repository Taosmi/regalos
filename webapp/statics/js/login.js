    // Cookie stuff.
var profun = {
        cookie: {
            //Borra la cookie.
            erase: function (name) {
                profun.cookie.write(name, "", -1);
            },

            //Lee el valor de la cookie.
            read: function (name) {
                var cookie = document.cookie.match(name + '=([^;]*)');
                return (cookie) ? cookie[1] : "";
            },

            //Guarda en la cookie el valor y propiedades especificados.
            write: function (name, value, days, secure, domain, path) {
                secure = secure || false;
                domain = domain || document.domain;
                path = path || "/";
                var cookie = name + "=" + value + ";";
                if (days) cookie += " expires=" + $time(days).toGMTString() + ";";
                cookie += " path=" + path + ";";
                cookie += " domain=" + domain + ";";
                if (secure) cookie += " secure";
                document.cookie = cookie;
            }
        }
    };

// Wait until the page is loaded.
$(window).ready(function () {

    // Select the language from the cookie or from the browser.
    var lang = profun.cookie.read('language') || navigator.language.replace('-','_');
    $('#language').val(lang);

    // Login form submit event.
    $('#loginForm').submit(function (event) {
        event.preventDefault();
        // Authorization request.
        $.ajax({
            method: 'POST',
            url: '/v1/auth',
            contentType: 'application/json; charset=UTF-8',
            data: {
                console: 'on',
                email: $('#email').val(),
                pwd: $('#pwd').val()
            },
            success: function () {
                document.location = '/news';
            },
            error: function (data) {
                alert(data.responseJSON.error.msg);
            }
        });
    });

    // Change language event.
    $('#language').change(function () {
        // Set the chosen language in the cookie and reload the page.
        profun.cookie.write('language', $('#language').val(), null, true);
        document.location.reload(true);
    })
});
