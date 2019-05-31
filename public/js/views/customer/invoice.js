$(document).ready(function() {
    console.log(url.listAllInvoiceUrl);
    invoiceTable = $('#table-invoice').DataTable( {
        "ajax": url.listAllInvoiceUrl,
        "autoWidth": false,
        "columns": [
            { "data": "subId", 'visible': false },
            { "data": "orderId" },
            { "data": "items" },  
            { "data": "status" },   
            { "data": "date" },
            { "data": "cycle" },
        ],
        "order": [[4, 'desc']],
        responsive:true
    });
   
});

function searchBySubId(value) {
    invoiceTable.column( 0 ).search( value ).draw();
}