$(function(){

    $('#app_bundle_setting_email_form_type_emailType').change(function(){

        $.ajax({
            url: Routing.generate('app_email_setting_show_json'),
            type:'POST',
            data: 'id='+ $(this).val(),
            dataType: 'json',
            success: function(jsonData)
            {
                if (jsonData.code == 'success'){
                    var data = jsonData.data;

                    var regex = /save\/[0-9]*$/g;

                    var $form = $('form[name="app_bundle_setting_email_form_type"]');
                    var action = $form.attr('action');
                    var action2 = action.replace(regex, 'save');
                    action2 += '/' + data.id;
                    $form.attr('action', action2);

                    $('#app_bundle_setting_email_form_type_subject').val(data.subject);
                    $('#app_bundle_setting_email_form_type_body').val(data.body);
                }
                else{
                    swal(
                        Translator.trans('app.common.errorTitle'),
                        jsonData.message,
                        "error"
                    );
                }
            },
            'error': function()
            {
                swal(
                    Translator.trans('app.common.errorTitle'),
                    Translator.trans('app.common.errorUnknow'),
                    "error"
                );
            }
        });
    });

});