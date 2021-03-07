// Wait until the page is loaded.
$(window).load(function () {

    $("#exit").click(function () {
        $.ajax({
            method: "DELETE",
            dataType: "json",
            url: "/v1/auth",
            success: function (data) {
                document.location.href = "/";
            },
            error: function (data) {
                document.location.href = "/";
            }
        });
    });
});
