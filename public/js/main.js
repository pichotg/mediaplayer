$(document).ready( function () {

    var mediaTable = $('#mediaTable').DataTable({
        'searching': false,
    });

    mediaTable.on('click','tr',function() {
        var data = mediaTable.row( this ).data();

        $('#modalTitle').text(data[1]);
        if(data[7] == 'Audio'){
            alert('Je suis un fichier audio');
            $('#modalBody').text('<audio controls><source src="../public/files/media/' + data[3] + '" type="audio/ogg">Your browser does not support the audio element.</audio>');
        } else if (data[7] == 'Video') {
            alert('Je suis un fichier vidéo');
            $('#modalBody').text();
        }

        $('#playMediaModal').modal().show();
    });

    var genreTable = $('#genreTable').DataTable({
        'searching': false,
    });

    genreTable.on('click','tr',function() {
        var data = genreTable.row( this ).data();
        alert(data);
    });

} );