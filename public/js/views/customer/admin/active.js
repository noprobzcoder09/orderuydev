$(document).ready(function() {
    table = $('#table-active-subs').DataTable( {
        "ajax": url.activeSub,
        "autoWidth": false,
        "columns": [
            { "data": "SubID" },
            { "data": "Product" },
            { "data": "Status" },
            // { "data": "Manage" },
            {
                "className":      'selections-control',
                "orderable":      false,
                "data":           "Manage",
                "defaultContent": ''
            },
        ],
        "order": [[1, 'asc']],
        responsive:true
    });

    // Add event listener for opening and closing details
    $('#table-active-subs tbody').on('click', '.selection-control', function () {
        var _this = $(this);
        
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            _this.removeClass('shown');
        }
        else {
            System.blockUI(tr);
            System.setAjaxRequest(
                url.weeks,
                {subcycleid: _this.data('subcycleid'), subid: _this.data('subid'), user_id: userId},
                'GET',
                function(response) {
                    setTimeout( function() {
                        // Open this row
                        row.child(response).show();
                        tr.addClass('shown x');
                        _this.addClass('shown');
                        tr.next().find('td').attr('style','padding: 0px !important;');
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
    } );

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
                        tr.after('<tr class="meals-table"><td style="width: 100%;" colspan="3">'+response+'</td></tr>');
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

