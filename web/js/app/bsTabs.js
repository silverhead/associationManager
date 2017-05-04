$(function(){
    var url = window.location.href;
    var parseUrl = url.split('#');

    console.log(url);

    if(typeof(parseUrl[1]) != 'undefined'){
        var tabID = parseUrl[1];
        $('.nav-tabs a[href="#' + tabID + '"]').tab('show');
    }
});