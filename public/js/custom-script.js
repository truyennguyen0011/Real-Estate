$(function () {
    // When open modal then focus input
    $('#searchModal').on('shown.bs.modal', function () {
        $('#input-search').focus();
    });
})