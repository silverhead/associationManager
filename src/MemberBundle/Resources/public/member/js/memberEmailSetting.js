$(function () {
    //TinyMCE
    tinymce.init({
        selector: ".textEdit",
        theme: "modern",
        height: 300,
        width: 550
    });
    // tinymce.init({
    //     selector: "#new_subscription_email",
    //     theme: "modern",
    //     height: 300,
    //     width: 550
    // });
    // tinymce.init({
    //     selector: "#new_cotisation_coming_soon_email",
    //     theme: "modern",
    //     height: 300,
    //     width: 550
    // });
    // tinymce.init({
    //     selector: "#late_member_notification_email",
    //     theme: "modern",
    //     height: 300,
    //     width: 550
    // });
    tinymce.suffix = ".min";
    tinyMCE.baseURL = '/plugins/tinymce';
});
