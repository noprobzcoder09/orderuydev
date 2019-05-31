$(document).ready(function() {
    
    $(document).on('click', '#table-active-subs-week tbody .view-menus-control', function () {
        var _this = $(this);
        var tr = $(_this).closest('tr');

        if ( tr.next().find('.meals-menu').length > 0) {
            // This row is already open - close it
            tr.next().fadeOut();
            System.lazyLoading( function() { tr.next().remove();}, 2000);
            $(_this).find('i').addClass('fa-plus');
            $(_this).find('i').removeClass('fa-minus');
        }
        else {
            System.blockUI(tr);
            System.setAjaxRequest(
                url.menus,
                {id: _this.attr('data-id')},
                'GET',
                function(response) {
                    System.lazyLoading( function() {
                        $(_this).find('i').removeClass('fa-plus');
                        $(_this).find('i').addClass('fa-minus'); 
                        tr.after('<tr class="meals-table"><td style="width: 100%;" colspan="2">'+response+'</td></tr>');
                        tr.next().find('.meals-table').fadeIn();
                    },1400);
                    System.unblockUI();
                },
                function(error) {
                    System.unblockUI();
                },
                'html',
                true
            );
        }
    });

     // Add event listener for opening and closing details
    $('#table-active-subs tbody').on('click', '.invoice-control', function () {
        
    } );
});

function viewPreviousSelections(element, subscriptionId) {
       
    const previousSelectionContent = $(element).parent().find('.previous-selections-content');
    

    System.setAjaxRequest(
        url.previousWeeksSubscriptionUrl,
        {subid: subscriptionId},
        'GET',
        function(response) {
            
            if ( previousSelectionContent.is(":visible") ) {
                System.unblockUI();
                previousSelectionContent.hide();
                $(element).find('span.view-hide').html('SHOW');


            } else {
                System.blockUI($(element));
                setTimeout( function() {

                    if ($('#previous-selections-content-wrapper-' + subscriptionId).length > 0) {
                        $(element).parent().find('#previous-selections-content-wrapper-' + subscriptionId).show().html(response);
                    }else {
                        $(element).parent().append('<div id="previous-selections-content-wrapper-' + subscriptionId + '" class="previous-selections-content">'+response+'</div>');
                    }

                },1400);
                System.unblockUI();

                $(element).find('span.view-hide').html('HIDE');
            }   

           

           
        },
        function(error) {
            System.unblockUI();
        },
        'html',
        true
    );
}