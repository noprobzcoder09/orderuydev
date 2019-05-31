$(document).ready(function() {
    pastTable = $('#table-past-subs').DataTable( {
        "ajax": url.pastSub,
        "autoWidth": false,
        "columns": [
            { "data": "Date" },
            { "data": "Product" },
            //{ "data": "Status" },
            // { "data": "Manage" },
            {
                "className":      'past-selections-control',
                "orderable":      false,
                "data":           "Manage",
                "defaultContent": ''
            },
        ],
        "order": [[1, 'asc']],
         retrieve: true,
         responsive:true,
         searching: false
    });

    // Add event listener for opening and closing details
    $('#table-past-subs tbody').on('click', '.past-selection-control', function () {
        var _this = $(this);
        var tr = $(this).closest('tr');
        var row = pastTable.row( tr );
    
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            _this.removeClass('shown');
        }
        else {
            System.blockUI(tr);
            System.setAjaxRequest(
                url.pastWeeks,
                {subcycleid: _this.data('subcycleid'), subid: _this.data('subid'), user_id: userId},
                'GET',
                function(response) {
                    // Open this row
                    row.child(response).show();
                    tr.addClass('shown');
                    _this.addClass('shown');
                    tr.next().find('td').attr('style','width: 100% !important; padding: 0px !important;');
                    System.unblockUI();
                },
                function(error) {
                    System.unblockUI();
                },
                'html',
                true
            );
        }
    } );

    $(document).on('click', '#table-past-subs-week tbody .past-view-menus-control', function () {
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
                url.pastMenus,
                {id: _this.attr('data-id')},
                'GET',
                function(response) {
                    System.lazyLoading( function() {
                        $(_this).find('i').removeClass('fa-plus');
                        $(_this).find('i').addClass('fa-minus'); 
                        tr.after('<tr class="meals-table"><td style="width: 100%;" colspan="2">'+response+'</td></tr>');
                        tr.next().find('.meals-table').fadeIn();
                    },1000);
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
});