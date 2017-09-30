$(function(){
    $('.money-euro').inputmask('999,99 €', { placeholder: '___,__ €' });
    $('.day-duration').inputmask('999 J', { placeholder: '___ J' });

    autosize($('textarea.auto-growth'));
});

var $logoInput = $("#app_bundle_setting_app_form_type_logo");
var logo = $logoInput.data('current');

$logoInput.fileinput({
    overwriteInitial: true,
    maxFileSize: 1500,
    showZoom: false,
    showRemove: true,
    showClose: false,
    showCaption: false,
    browseOnZoneClick: true,
    browseLabel: '',
    removeLabel: '',
    browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
    removeTitle: Translator.trans('app.setting.form.logo.cancel'),//'Cancel or reset changes',
    elErrorContainer: '#kv-avatar-errors-2',
    msgErrorClass: 'alert alert-block alert-danger',
    defaultPreviewContent: '<img src="'+logo+'" alt="'+Translator.trans('app.setting.form.logo.alt')+'" style="width:50%" class="img-responsive"><h6 class="text-muted">'+Translator.trans('app.setting.form.logo.clickToChange')+'</h6>',
    layoutTemplates: {main2: '{preview} {remove} {browse}'},
    allowedFileExtensions: ["jpg", "png", "gif"]
});