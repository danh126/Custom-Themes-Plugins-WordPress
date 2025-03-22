jQuery(document).ready(function ($) {
    var welcomeMsg = $('#welcome-message');
    if (welcomeMsg) {
        setTimeout(function () {
            welcomeMsg.css('display','none');
        }, 5000); // Tự ẩn sau 5 giây
    }
});