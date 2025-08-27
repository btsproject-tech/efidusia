let DailyReport = {
    module: () => {
        return "report/report-daily";
    },

    moduleApi: () => {
        return "api/" + DailyReport.module();
    },

    moduleApiPort: () => {
        return "api/master/port";
    },

    setSelect2: () => {
        if ($(".select2").length > 0) {
            $.each($(".select2"), function () {
                $(this).select2();
            });
        }
    },

    cancel: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(DailyReport.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(DailyReport.module()) + "add";
    },

    getData: async () => {
        let tableData = $("table#table-data");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();

        let params = {};
        params.tgl_awal = $("#tgl_awal").val();
        params.tgl_akhir = $("#tgl_akhir").val();

        var data = tableData.DataTable({
            processing: true,
            // responsive: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            searching: true,
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
                url: url.base_url(DailyReport.moduleApi()) + `getData`,
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
                    data: "contract_number",
                },
                {
                    data: "seq_number",
                    render: function (data, type, row) {
                        if (data) {
                            data;
                        } else {
                            data = "-";
                        }
                        return data;
                    },
                },
                // {
                //     data: null,
                //     render: function (data, type, row) {
                //         if (
                //             row.sertificate_minuta &&
                //             row.sertificate_minuta.length > 0
                //         ) {
                //             var lastStatusIndex =
                //                 row.sertificate_minuta.length - 1;
                //             var lastStatus =
                //                 row.sertificate_minuta[lastStatusIndex]
                //                     .seq_number;
                //             if (lastStatus) {
                //                 lastStatus += "-";
                //             }
                //         } else {
                //             lastStatus = "-";
                //         }
                //         return lastStatus;
                //     },
                // },
                {
                    data: "debitur",
                },
                {
                    data: "debitur_address",
                    render: function (data, type, row) {
                        if (data) {
                            data;
                        } else {
                            data = "-";
                        }
                        return data;
                    },
                },
                {
                    data: "debitur_price",
                    render: function (data, type, row) {
                        if (data) {
                            data;
                        } else {
                            data = "-";
                        }
                        return data;
                    },
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
                {
                    data: "status",
                    render: function (data, type, row) {
                        var html = `<span class="badge bg-warning" style="font-size:12px;">${data}</span>`;
                        if (data == "APPROVE") {
                            html = `<span class="badge bg-success" style="font-size:12px;">${data}</span>`;
                        } else if (data == "REJECT") {
                            html = `<span class="badge bg-danger" style="font-size:12px;">${data}</span>`;
                        } else if (data == "ON PROCESS") {
                            html = `<span class="badge bg-secondary" style="font-size:12px;">${data}</span>`;
                        } else if (data == "COMPLETE") {
                            html = `<span class="badge bg-primary" style="font-size:12px;">${data}</span>
                           `;
                        } else if (data == "FINISHED") {
                            html = `<span class="badge bg-dark" style="font-size:12px;">${data}</span>
                           `;
                        } else if (data == "DONE") {
                            html = `<span class="badge bg-primary" style="font-size:12px;">${data}</span>
                           `;
                        }
                        return html;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (
                            row.request_contract_status_daily_report &&
                            row.request_contract_status_daily_report.length > 0
                        ) {
                            var lastStatusIndex =
                                row.request_contract_status_daily_report
                                    .length - 1;
                            var lastStatus =
                                row.request_contract_status_daily_report[
                                    lastStatusIndex
                                ].remarks;
                        } else {
                            lastStatus = "-";
                        }
                        return lastStatus;
                    },
                },
                // {
                //     data: "id",
                //     render: function (data, type, row) {
                //         var html = `<button type="button" data_id="${row.id}" onclick="DailyReport.duplicate(this, event)" class="btn btn-warning editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-duplicate"></i></button>&nbsp;`;
                //         if (updateAction == 1) {
                //             html += `<a href='${url.base_url(
                //                 DailyReport.module()
                //             )}detail?id=${data}' data_id="${
                //                 row.id
                //             }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                //             if (row.status == "CREATED") {
                //                 html += `<a href='${url.base_url(
                //                     DailyReport.module()
                //                 )}ubah?id=${data}' data_id="${
                //                     row.id
                //                 }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                //             }
                //         }
                //         if (deleteAction == 1) {
                //             // if(row.status != 'CONFIRMED'){
                //             html += `<button type="button" data_id="${row.id}" onclick="DailyReport.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                //             // }
                //         }
                //         return html;
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

    filterData: async (elm) => {
        let tableData = $("table#table-data");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();
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
                url: url.base_url(DailyReport.moduleApi()) + `getData`,
                type: "POST",
                data: params,
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            dom: "Bftrip",
            buttons: [
                {
                    extend: "excel",
                    title: "Tracking Report",
                },
            ],
            columns: [
                {
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "contract_number",
                },
                {
                    data: "contract_number",
                },
                {
                    data: "debitur",
                },
                {
                    data: "debitur_address",
                },
                {
                    data: "debitur_price",
                },
                {
                    data: "created_at",
                    // render: function (data, type, row) {
                    //     var html = `<span class="badge bg-warning" style="font-size:12px;">${data}</span>`;
                    // },
                },
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

    ezportData: () => {
        let params = {};
        params.tgl_awal = $("#tgl_awal").val();
        params.tgl_akhir = $("#tgl_akhir").val();

        $.ajax({
            type: "POST",
            url: url.base_url(DailyReport.moduleApi()) + `exportExcel`,
            data: params,
            // dataType: "dataType",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },

            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (response) {
                message.closeLoading();
                // console.log(response);

                var today = new Date();
                var year = today.getFullYear();
                var month = ("0" + (today.getMonth() + 1)).slice(-2);
                var day = ("0" + today.getDate()).slice(-2);
                var hours = ("0" + today.getHours()).slice(-2);
                var minutes = ("0" + today.getMinutes()).slice(-2);
                var seconds = ("0" + today.getSeconds()).slice(-2);

                var blob = new Blob([response], { type: "text/csv" });
                var link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download =
                    "MAS_" +
                    year +
                    month +
                    day +
                    "_" +
                    hours +
                    minutes +
                    seconds +
                    ".csv";
                link.click();
                message.sweetSuccess("Informasi", "Data Berhasil Diexport");
            },
        });
    },

    delete: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr("data_id");
        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(DailyReport.moduleApi()) + "delete",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (resp) {
                message.closeLoading();
                $("#content-confirm-delete").html(resp);
                $("#confirm-delete-btn").trigger("click");
            },
        });
    },

    confirmDelete: (elm) => {
        let params = {};
        params.id = $(elm).attr("data_id");
        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(DailyReport.moduleApi()) + "confirmDelete",
            beforeSend: () => {
                message.loadingProses("Proses Simpan Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (resp) {
                message.closeLoading();
                if (resp.is_valid) {
                    message.sweetSuccess("Informasi", "Data Berhasil Dihapus");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", resp.message);
                }
            },
        });
    },

    back: (elm) => {
        window.location.href = url.base_url(DailyReport.module()) + "/";
    },

    pilihData: (elm, e) => {
        e.preventDefault();
        let country = $(elm).attr("country");
        let code = $(elm).attr("code");
        let id = $(elm).attr("data_id");
        let type = $(elm).attr("type");
        if (type == "origin") {
            $("#origin").attr("data_id", id);
            $("#origin").val(code + " - " + country);
        } else {
            $("#destinations").attr("data_id", id);
            $("#destinations").val(code + " - " + country);
        }
        $("button.btn-close").trigger("click");
    },

    setDate: () => {
        let dataDate = $(".data-date");
        $.each(dataDate, function () {
            console.log($(this));
            $(this).datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                autoclose: true,
                startDate: new Date(),
            });
        });
    },

    confirm: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $("#id").val();
        params.no_document = $("#no_document").val();

        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(DailyReport.moduleApi()) + "confirm",
            beforeSend: () => {
                message.loadingProses("Proses Simpan Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (resp) {
                message.closeLoading();
                if (resp.is_valid) {
                    message.sweetSuccess();
                    setTimeout(function () {
                        DailyReport.back();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", resp.message);
                }
            },
        });
    },

    print: (elm, e) => {
        e.preventDefault();
        var element = document.getElementById("print-content");
        fileName = $(elm).attr("filename");
        // html2pdf(element);
        let opt = {
            margin: 0.5,
            filename: `${fileName}.pdf`,
            image: { type: "jpeg", quality: 1 },
            html2canvas: { scale: 2, scrollX: 0, scrollY: 0, width: 1038 },
            jsPDF: { unit: "in", format: "A3", orientation: "portrait" },
        };

        html2pdf().set(opt).from(element).save();
    },
};

$(function () {
    DailyReport.setSelect2();
    DailyReport.setDate();
    DailyReport.getData();
});
