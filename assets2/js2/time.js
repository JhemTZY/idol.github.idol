$(document).ready(function() {
    setInterval(timestamp, 1000);
});

function timestamp() {
    $.ajax({
        url: 'http://10.216.128.10/package_view.php',
        success: function(data) {
            $('#timestamp').html(data);
        },
    });
}
