$(window).on('load', function () {
    $('#myModal').modal('show');
});

$(window).on('click', function () {
    $('#myModal').modal('hide');
});

$('#myModal').on('hide.bs.modal', function () {
    $.ajax({
        method: 'POST',
        url: url
    })
})