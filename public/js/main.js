$(document).ready( function () {

    var mediaTable = $('#mediaTable').DataTable({
        'searching': false,
    });

    mediaTable.on('click','tr',function() {
        var data = mediaTable.row( this ).data();

        $('#modalTitle').text(data[1]);
        if(data[7] == 'Audio'){
            $('#modalBody').html('<audio controls><source src="./files/media/' + data[3] + '" type="audio/mp3">Your browser does not support the audio element.</audio>');
        } else if (data[7] == 'Video') {
            $('#modalBody').html('<video width="320" height="240" controls><source src="./files/media/' + data[3] + '" type="audio/ogg">Your browser does not support the video element.</video>');
        }

        $('#playMediaModal').modal().show();
    });

    var genreTable = $('#genreTable').DataTable({
        'searching': false,
    });

} );