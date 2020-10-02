$(document).on('click','check-all', function(e){
    e.preventDefault();
    alert('test');
    if(this.checked){
        $('.credential_check').checked = true;
    }
    else{
        $('.credential_check').checked = false;
    }

});
