$(function(){
    $('.panel-group .panel-heading a').click(function(){
        var $this = $(this);
        var alreadyLoading = $this.data('alreadyloading');

        if("0" == alreadyLoading){
            var id = $this.data('subscription-historical-id');
            var container = $this.data('parent');
            var $listContainer = $('#' + container).find('.panel-body');

            $listContainer.reloadlist({
                masterRoute: 'member_view',
                remoteURL: Routing.generate('member_subscription_fees_list_part', {'subHistId': id, 'anchor': $listContainer.data('anchor')}),
                action: 'list',
                remoteSucessCallBack: function(){
                    $this.data('alreadyloading', 1);
                },
                remoteErrorCallBack: function(){
                    swal(
                        Translator.trans('app.common.errorTitle'),
                        Translator.trans('app.common.errorUnknow'),
                        "error"
                    );
                }
            });
        }

    });
});

