$(document).ready(function() {
    invoiceTable = $('#table-invoice').DataTable( {
        "ajax": url.invoices+'?userId='+userId,
        "autoWidth": false,
        "columns": [
            { "data": "subId", 'visible': false },
            { "data": "date" },
            { "data": "cycle" },
            { "data": "status" },
            { "data": "orderId" },
            { "data": "items" },
            { "data": "download" },
        ],
        "order": [[4, 'desc']],
        responsive:true
    });

    // Add event listener for opening and closing details
    $('#table-invoice tbody').on('click', '.invoice-selection-control', function () {
        var _this = $(this);
        
        var tr = $(this).closest('tr');
        var row = invoiceTable.row( tr );
        
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            _this.removeClass('shown');
        }
        else {
            System.blockUI(tr);
            System.setAjaxRequest(
                url.invoicesMenu,
                {subcycleid: _this.data('subcycleid'), user_id: userId},
                'GET',
                function(response) {
                    setTimeout( function() {
                        // Open this row
                        row.child(response).show();
                        tr.addClass('shown x');
                        _this.addClass('shown');
                        tr.next().find('td').attr('style','width: 100% !important; padding: 0px !important;');
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
});

function searchBySubId(value) {
    invoiceTable.column( 0 ).search( value ).draw();
}