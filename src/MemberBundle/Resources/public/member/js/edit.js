$(function(){
    $('.date').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });

    var $logoInput = $("#app_bundle_member_edit_form_type_avatarFile_file");
    var logo = $logoInput.parents('.avatar-holder').data('current-avatar');

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
        removeTitle: Translator.trans('member.member.edit.avatar.cancel'),//'Cancel or reset changes',
        elErrorContainer: '#kv-avatar-errors-2',
        msgErrorClass: 'alert alert-block alert-danger',
        defaultPreviewContent: '<img src="'+logo+'" alt="'+Translator.trans('member.member.edit.form.avatar.alt')+'" style="width:50%" class="img-responsive"><h6 class="text-muted">'+Translator.trans('member.member.edit.form.avatar.clickToChange')+'</h6>',
        layoutTemplates: {main2: '{preview} {remove} {browse}'},
        allowedFileExtensions: ["jpg", "png", "gif"]
    });
    
    $('#add-subscription').on('click', function(e){
    		e.preventDefault();
    		
    		var $formSubscription = $('#app_bundle_member_edit_form_type_subscriptions').data('prototype');
    		
    		$('#subscription-container').html($formSubscription);
    });
});