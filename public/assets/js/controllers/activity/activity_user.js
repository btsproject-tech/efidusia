let Activity = {
    module: () => {
        return "activity";
    },

    moduleApi: () => {
        return "api/" + Activity.module();
    },

    getData: async () => {
        let tableData = $("table#table-data");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();
        let printAction = $("#print").val();
        let params = {};
        params.tgl_awal = $("#tgl_awal").val();
        params.tgl_akhir = $("#tgl_akhir").val();

        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            destroy: true,
            order: [[0, "asc"]],
            aLengthMenu: [
                [25, 50, 100],
                [25, 50, 100],
            ],
            lengthChange: !1,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                );
            },
            ajax: {
                url: url.base_url(Activity.moduleApi()) + `getData`,
                type: "POST",
                data: params,
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            columns: [
                {
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "nama_lengkap",
                    render: function (data) {
                        return data.toUpperCase();
                    },
                },
                {
                    data: "action",
                },
                {
                    data: "created_at",
                    render: function (data, type, row) {
                        function formatDate(dateString) {
                            const date = new Date(dateString);
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(
                                2,
                                "0"
                            ); // Months are 0-based
                            const day = String(date.getDate()).padStart(2, "0");
                            return `${year}-${month}-${day}`; // Return formatted date
                        }

                        // Format the date
                        const pengajuan = formatDate(data);
                        return pengajuan;
                    },
                },
                // {
                //     data: null,
                //     render: function (data, type, row) {
                //         console.log(row);
                //         return `
                //             <button type="button" class="btn btn-info editable-submit btn-sm waves-effect waves-light m-1" onclick="Activity.showDetail()">
                //                 <i class="bx bx-detail"></i>
                //             </button>
                //         `;
                //     },
                // },
            ],
        });

        data
            .buttons()
            .container()
            .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
            $(".dataTables_length select").addClass(
                "form-select form-select-sm"
            ),
            $("#selection-datatable").DataTable({
                select: {
                    style: "multi",
                },
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>",
                    },
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass(
                        "pagination-rounded"
                    );
                },
            });
    },

    showDetail: (elm) => {
        // console.log(elm);
        $.ajax({
            type: "POST",
            dataType: "html",
            url: url.base_url(Activity.moduleApi()) + "getDetaildata",

            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data");
            },

            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (resp) {
                message.closeLoading();
                $("#content-modal-form").html(resp);
                $("#btn-show-modal-detail").trigger("click");
                // Invoice.getDataIbacth(elm);
            },
        });
    },

    setDate: () => {
        let dataDate = $(".data-date");
        $.each(dataDate, function () {
            // console.log($(this));
            $(this).datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                autoclose: true,
                startDate: new Date(),
            });
        });
    },
};

$(function () {
    Activity.setDate();
    Activity.getData();
});
