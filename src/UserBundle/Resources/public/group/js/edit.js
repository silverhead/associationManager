$(function(){
    $('#checkAllCredential').click(function(){
        $('.credential_check').not(this).prop('checked', $('#checkAllCredential').prop('checked'));
    });

    if($('input.credential_check:checked').length == $('input.credential_check').length){
        $('#checkAllCredential').prop('checked', 'checked');
    }
});
