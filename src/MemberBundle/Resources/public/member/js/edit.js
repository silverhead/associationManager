$(function(){
    $('.date').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });

    var $logoInput = $("#app_bundle_member_status_form_type_avatar");
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
    
    
//    $("#app_bundle_member_status_form_type_avatar").on('change', function() {
//        //Get count of selected files
//        var countFiles = $(this)[0].files.length;
//        var imgPath = $(this)[0].value;
//        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
//        var image_holder = $(".avatar-holder");
//        image_holder.empty();
//        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
//            if (typeof(FileReader) != "undefined") {
//                //loop for each file selected for uploaded.
//                for (var i = 0; i < countFiles; i++)
//                {
//                    var reader = new FileReader();
//                    reader.onload = function(e) {
//                        $("<img />", {
//                            "src": e.target.result,
//                            "class": "thumb-image",
//                            "style": "width:100%"
//                        }).appendTo(image_holder);
//                    }
//                    image_holder.show();
//                    reader.readAsDataURL($(this)[0].files[i]);
//                }
//            } else {
//                alert("This browser does not support FileReader.");
//            }
//        } else {
//            alert("Pls select only images");
//        }
//    });
    
    $('#add-subscription').on('click', function(e){
    		e.preventDefault();
    		
    		var $formSubscription = $('#app_bundle_member_status_form_type_subscriptions').data('prototype');
    		
    		$('#subscription-container').html($formSubscription);
    });
});