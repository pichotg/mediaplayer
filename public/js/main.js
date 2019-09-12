$(document).ready( function () {
    $('table').DataTable({
        'searching': false,
    });

    $('table').on('click','tr',function() {
        var data = table.fnGetData( this );
        alert(data);
    });

} );