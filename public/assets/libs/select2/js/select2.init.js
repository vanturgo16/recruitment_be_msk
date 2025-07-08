if ($(".select2").length) {
    $(document).on("shown.bs.modal", ".modal", function () {
        $(".select2").each(function () {
            $(this).select2({
                dropdownParent: $(this).closest(".modal"),
                width: "100%",
            });
        });
    });
    $(".select2").each(function () {
        $(this).select2({
            width: "100%",
        });
    });

    $(".select2").on("select2:open", function (e) {
        var searchInput = $(e.target).data("select2").dropdown.$search[0];
        if (searchInput) {
            searchInput.focus();
        }
    });
}

$(document).ready(function() {
    // Force Select2 dropdown text alignment to left
    $('.select2').on('select2:open', function() {
        $('.select2-results').css('text-align', 'left');
    });
});