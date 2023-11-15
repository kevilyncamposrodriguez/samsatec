var handleDataTableButtons = function() {
    "use strict";
    if ($('#data-table-class').length !== 0) {
        $('#data-table-class').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [2, 3, 4] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [2, 3, 4] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [2, 3, 4] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-voucher').length !== 0) {
        $('#data-table-voucher').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-product').length !== 0) {
        $('#data-table-product').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-discount').length !== 0) {
        $('#data-table-discount').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-exoneration').length !== 0) {
        $('#data-table-exoneration').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2, 3, 4, 5, 6] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2, 3, 4, 5, 6] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2, 3, 4, 5, 6] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-tax').length !== 0) {
        $('#data-table-tax').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2, 3, 4, 5, 6] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2, 3, 4, 5, 6] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2, 3, 4, 5, 6] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-lot').length !== 0) {
        $('#data-table-lot').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-zone').length !== 0) {
        $('#data-table-zone').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-family').length !== 0) {
        $('#data-table-family').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-plan').length !== 0) {
        $('#data-table-plan').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-category').length !== 0) {
        $('#data-table-category').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-provider').length !== 0) {
        $('#data-table-provider').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
    if ($('#data-table-document').length !== 0) {
        $('#data-table-document').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [2, 3, 4, 5, 6, 8, 9, 10, 11, 12] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [2, 3, 4, 5, 6, 8, 9, 10, 11, 12] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [2, 3, 4, 5, 6, 8, 9, 10, 11, 12] }, className: 'btn-sm' },
            ],
            responsive: true,
        });
    }
    if ($('#data-table-count').length !== 0) {
        $('#data-table-count').DataTable({
            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-xl-7 d-block d-sm-flex d-xl-block justify-content-center"<"d-block d-lg-inline-flex mr-0 mr-sm-3"l><"d-block d-lg-inline-flex"B>><"col-xl-5 d-flex d-xl-block justify-content-center"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>>',
            buttons: [
                { extend: 'excel', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'pdf', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
                { extend: 'print', exportOptions: { columns: [1, 2] }, className: 'btn-sm' },
            ],
            responsive: true
        });
    }
};

var handleSelect2 = function() {
    $(".count-select2").select2();
    $(".sku-select2").select2();
    $(".skuU-select2").select2();
    $(".ea-select2").select2();
    $(".default-select2").select2();
    $(".multiple-select2").select2({ placeholder: " Click para seleccionar" });
};
var FormPlugins = function() {
    "use strict";
    return {
        //main function
        init: function() {
            handleDataTableButtons();
            handleSelect2();
        }
    };
}();

$(document).ready(function() {
    FormPlugins.init();

});