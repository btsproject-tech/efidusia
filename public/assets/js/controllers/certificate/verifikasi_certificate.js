let VerifikasiCertificate = {
    module: () => {
        return "certificate/verifikasi-certificate";
    },

    moduleApi: () => {
        return "api/" + VerifikasiCertificate.module();
    },

    setSelect2: () => {
        if ($(".select2").length > 0) {
            $.each($(".select2"), function () {
                $(this).select2({
                    width: "500px",
                });
            });
        }
    },

    cancel: (elm, e) => {
        e.preventDefault();
        window.location.href =
            url.base_url(VerifikasiCertificate.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href =
            url.base_url(VerifikasiCertificate.module()) + "add";
    },

    getPostInput: (id_item) => {
        let data = {
            data_request: VerifikasiCertificate.getPostRequest(id_item),
            data_contract: VerifikasiCertificate.getPostContract(id_item),
            data_sertificate:
                VerifikasiCertificate.getPostDataSertificate(id_item),
        };

        return data;
    },
    getPostRequest: (id_item) => {
        let data = {
            // id: $("input#id").val(),
            no_request: $(`#no_request${id_item}`).val(),
            // creator: $("#creator").val(),
            // date_request: $("#date_request").val(),
        };

        return data;
    },
    getPostContract: (id_item) => {
        let data = {
            id: $(`input#id_item${id_item}`).val(),
            // status: $(`select#status_${id_item}`).val(),
            // remarks_verify: $(`#remarks_verify${id_item}`).val(),
            // billing_number_ahu: $(`#billing_number_ahu${id_item}`).val(),
            // date_input_ahu: $(`#date_input_ahu${id_item}`).val(),
            // name_pnbp: $(`#name_pnbp${id_item}`).val(),
            // updater: $(`#updater${id_item}`).val(),
        };

        return data;
    },
    getPostDataSertificate: (id_item) => {
        let data = {
            file: $(`input#file${id_item}`).attr("src"),
            tipe: $(`input#file${id_item}`).attr("tipe"),
            file_name: $(`input#file${id_item}`).val(),
            no_sk: $(`input#no_sk${id_item}`).val(),
            waktu_sk: $(`input#waktu_sk${id_item}`).val(),
            tanggal_sertifikat: $(`input#tanggal_sertifikat${id_item}`).val(),
        };

        return data;
    },

    submit: (elm, id_item) => {
        let form = $(elm).closest(`div#modal-body${id_item}`);
        if (validation.runWithElement(form)) {
            let params = VerifikasiCertificate.getPostInput(id_item);
            // if (params.data_item.length == 0) {
            //     message.sweetError("Informasi", "Table Rate Harus Diisi");
            //     return;
            // }
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(VerifikasiCertificate.moduleApi()) + "submit",
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
                            //   reload
                            window.location.reload();
                        }, 1000);
                    } else {
                        message.sweetError("Informasi", resp.message);
                    }
                },
            });
        } else {
            message.sweetError("Informasi", "Data Belum Lengkap");
        }
    },

    getData: async () => {
        let tableData = $("table#table-data");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();
        let akses = $("#akses").val();
        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
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
                url:
                    url.base_url(VerifikasiCertificate.moduleApi()) + `getData`,
                type: "POST",
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
                    data: "date_request",
                },
                {
                    data: "no_request",
                },
                {
                    data: "creator",
                    render: function (data, type, row) {
                        // return console.log(row.creator.username.toUpperCase());

                        let creator_name = row.creator.username.toUpperCase();
                        return creator_name;
                    },
                },

                {
                    data: "remarks",
                    render: function (data, type, row) {
                        let remarks = row.remarks;
                        let remarks_verify = row.remarks_verify;

                        let html = `${remarks} <br> ${
                            remarks_verify == null ? "" : remarks_verify
                        }`;

                        return html;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        // return console.log(row.request_contract.length);

                        let qty_contract = row.request_contract.length;
                        return qty_contract;
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
                    data: "id",
                    render: function (data, type, row) {
                        let total_draft = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "DRAFT") {
                                    total_draft.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_draft.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_on_process = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "ON PROCESS") {
                                    total_on_process.push(
                                        elementOrValue.status
                                    );
                                }
                            }
                        );

                        return total_on_process.length;
                    },
                    data: "id",
                    render: function (data, type, row) {
                        let total_on_process = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "ON PROCESS") {
                                    total_on_process.push(
                                        elementOrValue.status
                                    );
                                }
                            }
                        );

                        return total_on_process.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_reject = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "REJECT") {
                                    total_reject.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_reject.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_approve = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "APPROVE") {
                                    total_approve.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_approve.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_done = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "DONE") {
                                    total_done.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_done.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_complete = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "COMPLETE") {
                                    total_complete.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_complete.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_finished = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "FINISHED") {
                                    total_finished.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_finished.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let status = row.status;
                        // console.log(akses);
                        var html = ``;
                        if (
                            (status == "DRAFT" ||
                                status == "ON PROCESS" ||
                                status == "APPROVE") &&
                            akses != "notaris"
                        ) {
                            if (akses != "admin minuta") {
                                html += `<a href='${url.base_url(
                                    VerifikasiCertificate.module()
                                )}ubah?id=${row.id}' data_id="${
                                    row.id
                                }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                            if (updateAction == 1) {
                                html += `<a href='${url.base_url(
                                    VerifikasiCertificate.module()
                                )}detail?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                                if (row.status == "CREATED") {
                                    html += `<a href='${url.base_url(
                                        VerifikasiCertificate.module()
                                    )}ubah?id=${data}' data_id="${
                                        row.id
                                    }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                                }
                            }
                            if (deleteAction == 1) {
                                if (row.status != "CONFIRMED") {
                                    // html += `<button type="button" data_id="${row.id}" onclick="VerifikasiCertificate.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                                }
                            }
                            return html;
                        }

                        return `<a href='${url.base_url(
                            VerifikasiCertificate.module()
                        )}detail?id=${data}' data_id="${
                            row.id
                        }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;
                    },
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
                url:
                    url.base_url(VerifikasiCertificate.moduleApi()) + `getData`,
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
                    data: "date_request",
                },
                {
                    data: "no_request",
                },
                {
                    data: "creator",
                    render: function (data, type, row) {
                        // return console.log(row.creator.username.toUpperCase());

                        let creator_name = row.creator.username.toUpperCase();
                        return creator_name;
                    },
                },
                {
                    data: "remarks_verify",
                    render: function (data, type, row) {
                        if (data == null) {
                            return "";
                        }
                    },
                },
                {
                    data: "remarks",
                    render: function (data, type, row) {
                        if (data == null) {
                            return "";
                        }
                        // Biarkan DataTables menampilkan HTML
                        return type === "display"
                            ? data
                            : $("<div>").text(data).html();
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
                    data: "id",
                    render: function (data, type, row) {
                        let total_draft = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "DRAFT") {
                                    total_draft.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_draft.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_on_process = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "ON PROCESS") {
                                    total_on_process.push(
                                        elementOrValue.status
                                    );
                                }
                            }
                        );

                        return total_on_process.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_reject = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "REJECT") {
                                    total_reject.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_reject.length;
                    },
                },

                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_approve = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "APPROVE") {
                                    total_approve.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_approve.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_done = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "DONE") {
                                    total_done.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_done.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_complete = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "COMPLETE") {
                                    total_complete.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_complete.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let total_finished = [];
                        $.map(
                            row.request_contract,
                            function (elementOrValue, indexOrKey) {
                                if (elementOrValue.status == "FINISHED") {
                                    total_finished.push(elementOrValue.status);
                                }
                            }
                        );

                        return total_finished.length;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let status = row.status;
                        if (
                            status == "DRAFT" ||
                            status == "ON PROCESS" ||
                            status == "APPROVE"
                        ) {
                            var html = `<a href='${url.base_url(
                                VerifikasiCertificate.module()
                            )}ubah?id=${row.id}' data_id="${
                                row.id
                            }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            if (updateAction == 1) {
                                html += `<a href='${url.base_url(
                                    VerifikasiCertificate.module()
                                )}detail?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                                if (row.status == "CREATED") {
                                    html += `<a href='${url.base_url(
                                        VerifikasiCertificate.module()
                                    )}ubah?id=${data}' data_id="${
                                        row.id
                                    }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                                }
                            }
                            if (deleteAction == 1) {
                                if (row.status != "CONFIRMED") {
                                    // html += `<button type="button" data_id="${row.id}" onclick="VerifikasiCertificate.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                                }
                            }
                            return html;
                        }

                        return `<a href='${url.base_url(
                            VerifikasiCertificate.module()
                        )}detail?id=${data}' data_id="${
                            row.id
                        }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;
                    },
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

    delete: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr("data_id");
        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(VerifikasiCertificate.moduleApi()) + "delete",
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
            url:
                url.base_url(VerifikasiCertificate.moduleApi()) +
                "confirmDelete",
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
        window.location.href =
            url.base_url(VerifikasiCertificate.module()) + "/";
    },

    setDate: () => {
        // Set data destination
        let dataDate = $(".data-date");
        $.each(dataDate, function () {
            // console.log($(this));
            // Close modal
            $(this).datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                autoclose: true,
                startDate: new Date(),
            });

            // Ubah gaya CSS agar teks rata kiri
            $(this).css("text-align", "left");
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
            url: url.base_url(VerifikasiCertificate.moduleApi()) + "confirm",
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
                        VerifikasiCertificate.back();
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

    editor: () => {
        if ($(".texteditor").length > 0) {
            tinymce.remove("textarea.texteditor");
            // tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'textarea.texteditor');
            tinymce.init({
                selector: "textarea.texteditor",
                menubar: false,
                plugins: ["lists", "code"],
                toolbar:
                    "undo redo | blocks | " +
                    "bold italic backcolor | alignleft aligncenter " +
                    "alignright alignjustify | bullist numlist outdent indent | " +
                    "removeformat code",
                height: "300",
            });
            document.addEventListener("focusin", function (e) {
                if (
                    e.target.closest(
                        ".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root"
                    ) !== null
                ) {
                    e.stopImmediatePropagation();
                }
            });
        }
    },

    duplicate: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr("data_id");

        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(VerifikasiCertificate.moduleApi()) + "duplicate",
            beforeSend: () => {
                message.loadingProses("Proses Duplikasi Data...");
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
                        window.location.reload();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", resp.message);
                }
            },
        });
    },

    changeStatus: (elm, id_item) => {
        let status = $(`#status_${id_item}`).val();
        // console.log(status);
        let html = `
            <div class="form-group row mb-4">
                <label for="remarks_verify${id_item}" class="col-md-2 col-form-label">Remarks Verify</label>
                <div class="col-md-10">
                    <textarea class="form-control required" name="remarks_verify" id="remarks_verify${id_item}" cols="30"
                        rows="10" error="Remarks Verify"></textarea>
                </div>
            </div>
        `;
        if (status == "REJECT") {
            $(`#content_remarks_verify${id_item}`).html(html);
        } else {
            $(`#content_remarks_verify${id_item}`).html("");
        }
    },

    showDataUserNotaris: (elm) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url:
                url.base_url(VerifikasiCertificate.moduleApi()) +
                "showDataUserNotaris",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (resp) {
                // return console.log(resp);

                message.closeLoading();
                $("#content-modal-form").html(resp);
                $("#btn-show-modal").trigger("click");
                VerifikasiCertificate.getDataUserNotaris();
            },
        });
    },
    getDataUserNotaris: () => {
        let tableData = $("table#table-data-port");
        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            order: [[0, "asc"]],
            aLengthMenu: [
                [25, 50, 100],
                [25, 50, 100],
            ],
            // lengthChange: !1,
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
                url:
                    url.base_url(VerifikasiCertificate.moduleApi()) +
                    `getDataUserNotaris`,
                type: "POST",
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
                    data: "nik",
                },
                {
                    data: "nama_lengkap",
                },
                {
                    data: "nama_company",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `
                            <button type="button" nama_lengkap="${row.nama_lengkap}" onclick="VerifikasiCertificate.pilihData(this, '')" data_id="${row.nik}" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></button>
                        `;
                        return html;
                    },
                },
            ],
        });
    },

    pilihData: (elm) => {
        // console.log();
        let nama_lengkap = $(elm).attr("nama_lengkap");
        let nik = $(elm).attr("data_id");
        // console.log("DATA ", nama_lengkap, nik);
        $(`#delegate_to`).val(nik + " - " + nama_lengkap);
        $("button.btn-close").trigger("click");
    },

    searchReset: () => {
        window.location.reload();
    },

    addFile: (elm, id_item) => {
        // Buat uploader secara dinamis
        var uploader = $(
            `<input type="file" id="file${id_item}" accept="image/*,application/pdf" />`
        );
        var src_foto = $(`#file${id_item}`);

        // Tambahkan uploader ke body
        $("body").append(uploader);
        uploader.click();

        // Ketika ada perubahan (file terpilih)
        uploader.on("change", function () {
            var files = uploader.get(0).files[0];

            if (files) {
                var reader = new FileReader();
                var filename = files.name;
                var data_from_file = filename.split(".");
                var type_file = $.trim(
                    data_from_file[data_from_file.length - 1]
                ).toLowerCase();

                // Cek jika format file sesuai
                if (["pdf"].includes(type_file)) {
                    reader.onload = function (event) {
                        // let params = {};
                        var data = event.target.result;
                        src_foto.val(filename);
                        src_foto.attr("tipe", type_file);
                        src_foto.attr("src", data);
                    };
                    reader.readAsDataURL(files);
                } else {
                    // Jika format tidak sesuai
                    message.sweetError(
                        "Gagal",
                        "Format file salah, hanya bisa pdf"
                    );
                    return;
                }
            }
            // Hapus uploader setelah file dipilih atau proses selesai
            uploader.remove();

            // Gunakan FormData untuk mengirim file
            let formData = new FormData();
            formData.append("file", files); // Menambahkan file ke dalam formData
            formData.append("tipe", src_foto.attr("tipe"));
            formData.append("file_name", src_foto.val());

            $.ajax({
                type: "POST",
                url:
                    url.base_url(VerifikasiCertificate.moduleApi()) + "scanPDF",
                data: formData, // Kirim FormData
                processData: false, // Jangan memproses data karena formData sudah diatur
                contentType: false, // Jangan tentukan contentType agar browser menanganinya sendiri
                dataType: "json",
                beforeSend: () => {
                    message.loadingProses("Proses upload SK....");
                },
                error: function () {
                    message.closeLoading();
                    message.sweetError("Informasi", "Gagal");
                },
                success: function (resp) {
                    message.closeLoading();
                    if (resp.is_valid) {
                        message.sweetSuccess();
                        let data_pdf = resp.data_pdf;

                        $(`input#no_sk${id_item}`).val(
                            `${data_pdf.nomor} TAHUN ${data_pdf.tahun}`
                        );
                        $(`input#tanggal_sertifikat${id_item}`).val(
                            data_pdf.tanggal
                        );
                        $(`input#waktu_sk${id_item}`).val(data_pdf.jam);
                    } else {
                        message.sweetError("Informasi", resp.message);
                    }
                },
            });
        });
    },

    confirmDownload: (fileName, filePath) => {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda akan mengunuduh data file ${fileName}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Download!",
        }).then((result) => {
            if (result.value) {
                let url_path = `${filePath}`;
                let url = window.location.origin;
                window.location.href = url + url_path;
            }
        });
    },

    dump_id: 0,

    addItemMinuta: (elm, e) => {
        let table = $("#table-minuta").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.find("textarea").val("");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="VerifikasiCertificate.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },
    deleteItem: (elm) => {
        let data_id = $(elm).closest("tr").attr("data_id");
        // if (data_id == '') {
        $(elm).closest("tr").remove();
        // } else {
        //     $(elm).closest('tr').addClass('remove');
        //     $(elm).closest('tr').addClass('hide');
        // }
    },

    addFileMinuta: (elm) => {
        // Buat uploader secara dinamis
        var uploader = $(
            '<input type="file" accept="image/*;capture=camera" />'
        );
        var src_foto = $(elm).closest("tr").find("#file");
        uploader.click();

        // Ketika ada perubahan (file terpilih)
        uploader.on("change", function () {
            var files = uploader.get(0).files[0];

            if (files) {
                var reader = new FileReader();
                var filename = files.name;
                var data_from_file = filename.split(".");
                var type_file = $.trim(
                    data_from_file[data_from_file.length - 1]
                ).toLowerCase();

                // Cek jika format file sesuai
                if (
                    ["jpg", "jpeg", "png", "pdf", "doc", "docx"].includes(
                        type_file
                    )
                ) {
                    reader.onload = function (event) {
                        var data = event.target.result;
                        src_foto.val(filename);
                        src_foto.attr("tipe", type_file);
                        src_foto.attr("src", data);
                    };
                    reader.readAsDataURL(files);
                } else {
                    // Jika format tidak sesuai
                    message.sweetError(
                        "Gagal",
                        "Format file salah, hanya bisa jpg, jpeg, png, dan pdf"
                    );
                }
            }
            // Hapus uploader setelah file dipilih atau proses selesai
            uploader.remove();
        });
    },
    saveMinuta: (elm, e) => {
        e.preventDefault();
        let form = $(elm).closest("div.content-save-minuta");
        if (validation.runWithElement(form)) {
            let params = VerifikasiCertificate.getPostdataMinuta();
            // return console.log(params);

            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url:
                    url.base_url(VerifikasiCertificate.moduleApi()) +
                    "submitMinuta",
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
                            window.location.reload();
                        }, 1000);
                    } else {
                        message.sweetError("Informasi", resp.message);
                    }
                },
            });
        } else {
            message.sweetError("Informasi", "Data Belum Lengkap");
        }
    },

    getPostdataMinuta: () => {
        let data = [];

        let tableData = $("table#table-minuta").find("tbody").find("tr.input");
        $.each(tableData, function () {
            let tr = $(this);
            let params = {};
            params.id = tr.attr("data_id");
            params.request_sertificate_contract = tr
                .find("#request_sertificate_contract")
                .val();
            params.file = tr.find("input#file").attr("src");
            params.tipe = tr.find("input#file").attr("tipe");
            params.file_name = tr.find("input#file").val();
            params.keterangan = tr.find("#keterangan").val();
            data.push(params);
        });

        let data_input = {
            data_minuta: data,
        };
        return data_input;
    },

    sendNotifikasi: (elm, e) => {
        let params = {};
        params.no_request = $(`#no_request`).val();
        params.notaris = $(`#notaris`).val();
        if (params.notaris == "") {
            message.sweetError("Informasi", "Pilih Notaris");
            return false;
        }
        $.ajax({
            type: "POST",
            url:
                url.base_url(VerifikasiCertificate.moduleApi()) +
                "sendNotifikasi",
            data: params,
            dataType: "json",
            beforeSend: () => {
                message.loadingProses("Proses Mengirim Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
            success: function (response) {
                message.closeLoading();
                if (response.is_valid) {
                    message.sweetSuccess();
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", response.message);
                }
            },
        });
    },

    submitDelegate: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.no_request = $(`#no_request`).val();
        params.delegate_to = $(`#delegate_to`).val();
        params.id_kontrak = [];
        $('input[name="checkbox[]"]:checked').each(function () {
            params.id_kontrak.push($(this).val());
        });
        if (params.delegate_to == "") {
            return message.sweetError(
                "Informasi",
                "Data Delegate Belum Dipilih"
            );
        } else if (params.id_kontrak.length == 0) {
            return message.sweetError(
                "Informasi",
                "Data Kontrak Belum Dipilih"
            );
        }

        $.ajax({
            type: "POST",
            url:
                url.base_url(VerifikasiCertificate.moduleApi()) +
                "submitDelegate",
            data: params,
            dataType: "json",

            beforeSend: () => {
                message.loadingProses("Proses Mengirim Data...");
            },
            success: function (response) {
                message.closeLoading();
                if (response.is_valid) {
                    message.sweetSuccess();
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", response.message);
                }
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
        });
    },

    copyToClipboard: (id) => {
        let copyText = $(`#${id}`).val();
        navigator.clipboard
            .writeText(copyText)
            .then(() => {
                toastr.success("Success", "Text Berhasil disalin", {
                    positionClass: "toast-top-center",
                    closeButton: true,
                    progressBar: true,
                });
            })
            .catch(() => {
                toastr.error("Error", "Gagal", {
                    positionClass: "toast-top-center",
                    closeButton: true,
                    progressBar: true,
                });
            });

        return false;
    },

    exportXlsContract: (type) => {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda akan mengeksport data ${type}?, hanya data yang berstatus minimal APPROVE saja yang bisa di`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Export!",
        }).then((result) => {
            if (result.value) {
                let params = {};
                params.no_request = $(`#no_request`).val();
                params.notaris = $(`#notaris`).val();
                if (params.notaris == "") {
                    message.sweetError("Informasi", "Pilih Notaris");
                    return false;
                }
                $.ajax({
                    type: "post",
                    url:
                        url.base_url(VerifikasiCertificate.moduleApi()) +
                        "export",
                    data: params,
                    // dataType: "json",
                    beforeSend: () => {
                        message.loadingProses("Proses Pengambilan Data...");
                    },

                    error: function () {
                        message.closeLoading();
                        message.sweetError("Informasi", "Gagal");
                    },

                    success: function (response) {
                        if (response.is_valid) {
                            message.closeLoading();
                            if (type == "xlsx") {
                                VerifikasiCertificate.exportXlsXFile(
                                    response.header,
                                    response.order_detail,
                                    "DataContract-" + response.date_export
                                );
                            }
                        } else {
                            message.closeLoading();
                            message.sweetError("Informasi", response.message);
                        }
                    },
                });
            }
        });
    },
    exportXlsXFile: (headers, items, fileTitle) => {
        // if (headers) {
        //     items.unshift(headers);
        // }

        // Convert Object to JSON
        // var jsonObject = JSON.stringify(items);
        // console.log(items);

        const worksheet = XLSX.utils.json_to_sheet(items);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet);
        XLSX.writeFile(workbook, fileTitle + ".xlsx");
    },

    exportMinuta: (id) => {
        $.ajax({
            type: "post",
            url:
                url.base_url(VerifikasiCertificate.moduleApi()) +
                "exportMinuta",
            data: {
                id: id,
            },
            dataType: "json",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },

            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
            success: function (response) {
                if (response.is_valid) {
                    message.closeLoading();

                    Swal.fire({
                        title: "Apakah Anda Yakin?",
                        text: `Anda akan mengunuduh data file minuta ?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Download!",
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = url.base_url(
                                response.file_path
                            );
                        }

                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    });
                } else {
                    message.closeLoading();
                    message.sweetError("Informasi", response.message);
                }
            },
        });
        // php to word
    },
    buttonHide: (elm) => {
        let terikirim = $(elm).find(":selected").attr("terikirim");
        if (terikirim == "TERKIRIM") {
            $("#sendNotaris").addClass("d-none");
        } else {
            $("#sendNotaris").removeClass("d-none");
        }
    },

    ambilData: (elm, e) => {
        var uploader = $('<input type="file" />');
        uploader.click();

        uploader.on("change", function () {
            var reader = new FileReader();
            reader.onload = function (event) {
                var files = $(uploader).get(0).files[0];
                var filename = files.name;
                var data_from_file = filename.split(".");
                var type_file = $.trim(
                    data_from_file[data_from_file.length - 1]
                );

                if (
                    type_file == "xls" ||
                    type_file == "xlsx" ||
                    type_file == "csv"
                ) {
                    $("#import-file").val(filename);
                    $("#btn-import").removeClass("disabled");

                    let data = event.target.result;
                    let workbook = XLSX.read(data, {
                        type: "binary",
                        cellDates: true,
                    });
                    message.loadingProses("Proses Ambil Data...");
                    // console.log("loading....");

                    setTimeout(() => {
                        workbook.SheetNames.forEach(function (sheetName) {
                            var XL_row_object =
                                XLSX.utils.sheet_to_row_object_array(
                                    workbook.Sheets[sheetName]
                                );
                            var json_object = JSON.stringify(XL_row_object);
                            let dataJson = JSON.parse(json_object);
                            let dataFileNotValid = [];

                            if (dataJson.length > 0) {
                                if (typeof dataJson[0]["NoKontrak"] == null) {
                                    message.sweetError(
                                        "Template Tidak Sesuai Mohon Cek Kembali"
                                    );
                                    return;
                                } else {
                                    // Ambil semua baris kosong yang sudah ada di tabel
                                    let emptyRows = $(
                                        "#table-rate tbody tr.input"
                                    );

                                    dataJson =
                                        VerifikasiCertificate.processData(
                                            dataJson
                                        );

                                    // Iterasi data dari file Excel
                                    $.each(dataJson, function (index, val) {
                                        if (typeof val["NoKontrak"] != null) {
                                            if (emptyRows.length > 0) {
                                                // Jika ada baris kosong, isi baris tersebut
                                                let currentRow =
                                                    emptyRows.eq(0); // Ambil baris pertama yang kosong
                                                currentRow
                                                    .find("#contract_number")
                                                    .val(
                                                        val["NoKontrak"] ==
                                                            null ||
                                                            val["NoKontrak"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["NoKontrak"]
                                                    );
                                                currentRow
                                                    .find("#id_kontrak")
                                                    .val(
                                                        val["IdTransaksi"] ==
                                                            null ||
                                                            val[
                                                                "IdTransaksi"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val["IdTransaksi"]
                                                    );
                                                currentRow
                                                    .find("#no_minuta")
                                                    .val(
                                                        val["NoMinuta"] ==
                                                            null ||
                                                            val["NoMinuta"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["NoMinuta"]
                                                    );
                                                currentRow
                                                    .find("#billId")
                                                    .val(
                                                        val["BillId"] == null ||
                                                            val["BillId"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["BillId"]
                                                    );
                                                currentRow
                                                    .find("#biaya_pnbp")
                                                    .val(
                                                        val["Biaya PNBP"] ==
                                                            null ||
                                                            val["Biaya PNBP"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Biaya PNBP"]
                                                    );
                                                // Hapus baris kosong dari daftar setelah diisi
                                                emptyRows = emptyRows.slice(1);
                                            } else {
                                                // Jika tidak ada baris kosong, tambahkan baris baru
                                                let row = `<tr class="input" data_id="">
                                                    <td>
                                                        <input type="text" class="form-control required" id="id_kontrak" error="ID Transaksi" placeholder="ID Transaksi" value="${
                                                            val["IdTransaksi"]
                                                                ? val[
                                                                      "IdTransaksi"
                                                                  ]
                                                                : "-"
                                                        }" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="contract_number" error="Nomor Kontrak" placeholder="Nomor Kontrak" value="${
                                                            val["NoKontrak"]
                                                                ? val[
                                                                      "NoKontrak"
                                                                  ]
                                                                : "-"
                                                        }" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="no_minuta" error="Nomor Minuta" placeholder="Nomor Minuta" value="${
                                                            val["NoMinuta"]
                                                                ? val[
                                                                      "NoMinuta"
                                                                  ]
                                                                : "-"
                                                        }" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="billId" error="Bill ID" placeholder="Bill ID" value="${
                                                            val["BillId"]
                                                                ? val["BillId"]
                                                                : "-"
                                                        }" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="biaya_pnbp" error="Biaya PNBP" placeholder="Biaya PNBP" value="${
                                                            val["Biaya PNBP"]
                                                                ? val[
                                                                      "Biaya PNBP"
                                                                  ]
                                                                : "-"
                                                        }" readonly>
                                                    </td>
                                                </tr>`;
                                                $("#table-rate tbody").prepend(
                                                    row
                                                );
                                            }
                                        }
                                    });

                                    if (dataFileNotValid.length > 0) {
                                        message.sweetError(
                                            "Informasi",
                                            "Terdapat Data Yang Tidak Lengkap Cek Kembali Data Anda"
                                        );
                                    }
                                    message.closeLoading();
                                }
                            }
                        });
                    }, 100);
                } else {
                    message.sweetError(
                        "Informasi",
                        "File Harus Berupa Dokumen Bertipe XLXS, XLS, CSV"
                    );
                }
            };

            message.closeLoading();
            reader.readAsBinaryString(uploader[0].files[0]);
        });
    },
    processData: (dataJson) => {
        let processedData = [];
        let seen = new Set();
        // return console.log(dataJson);

        dataJson.forEach((person) => {
            const key = `${person.NoKontrak}`; // Gabungkan nama dan alamat sebagai kunci unik
            // return console.log(person);
            if (!seen.has(key)) {
                seen.add(key);
                processedData.push(person);
            } else {
                // console.log( person);
                message.showDialog(
                    `<b>Ada data dengan nama dan alamat yang sama ditemukan dan diambil 1 data saja : </b> <br>
            ${person.NoKontrak}
            `
                );
            }
        });

        return processedData;
    },
    getPostContractImport: () => {
        let data = [];
        let table = $("#table-rate").find("tbody").find("tr.input");
        $.each(table, function () {
            let params = {};

            params.contract_number = $(this).find("#contract_number").val();
            params.no_minuta = $(this).find("#no_minuta").val();
            params.id_kontrak = $(this).find("#id_kontrak").val();
            params.biaya_pnbp = $(this).find("#biaya_pnbp").val();
            params.billId = $(this).find("#billId").val();

            data.push(params);
        });
        return JSON.stringify(data);
    },
    submitImport: () => {
        let notaris = $("#notaris").val();
        if (notaris == "") {
            message.sweetError(
                "Informasi",
                "Silahkan Pilih Notaris Terlebih Dahulu"
            );
            return false;
        }

        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda akan mengupdate dan umenambahkan BILL ID data kontrak ini ?, dan otomatis akan mengirimkan notifikasi pada pihak notaris`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Konfirmasi!",
        }).then((result) => {
            if (result.value) {
                let data = VerifikasiCertificate.getPostContractImport();

                $.ajax({
                    type: "POST",
                    url:
                        url.base_url(VerifikasiCertificate.moduleApi()) +
                        `import`,
                    data: {
                        data_contract: data,
                        notaris: notaris,
                    },
                    dataType: "json",
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
                                window.location.reload();
                            }, 1000);
                        } else {
                            message.sweetError("Informasi", resp.message);
                        }
                    },
                });
            }
        });
    },

    showDataUserSaksi: (saksi, id_item) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url:
                url.base_url(VerifikasiCertificate.moduleApi()) +
                "showDataUserSaksi",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (resp) {
                // return console.log(resp);

                message.closeLoading();
                $("#content-modal-form").html(resp);
                $("#btn-show-modal").trigger("click");
                $("#btn_back").attr("data-bs-target", `#detailModal${id_item}`);
                VerifikasiCertificate.getDataUserSaksi(saksi, id_item);
            },
        });
    },
    getDataUserSaksi: (saksi, id_item) => {
        let tableData = $("table#table-data-port");
        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            order: [[0, "asc"]],
            aLengthMenu: [
                [25, 50, 100],
                [25, 50, 100],
            ],
            // lengthChange: !1,
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
                url:
                    url.base_url(VerifikasiCertificate.moduleApi()) +
                    `getDataUserSaksi`,
                type: "POST",
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
                    data: "nik",
                },
                {
                    data: "nama_lengkap",
                },
                {
                    data: "nama_company",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `
                            <button type="button" nama_lengkap="${row.nama_lengkap}" onclick="VerifikasiCertificate.pilihDataSaksi(this,'${saksi}', '${id_item}')" data_id="${row.nik}" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></button>
                        `;
                        return html;
                    },
                },
            ],
        });
    },

    pilihDataSaksi: (elm, saksi, id_item) => {
        // console.log();
        let nama_lengkap = $(elm).attr("nama_lengkap");
        let nik = $(elm).attr("data_id");
        // console.log("DATA ", nama_lengkap, nik);
        $(`#${saksi + id_item}`).val(nik + " - " + nama_lengkap);
        $("#btn_back").trigger("click");
    },

    downloadAllWarkah: (id_item) => {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda akan mengunuduh semua data warkah ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Download!",
        }).then((result) => {
            if (result.value) {
                let params = {};
                params.id_kontrak = id_item;

                $.ajax({
                    type: "post",
                    url:
                        url.base_url(VerifikasiCertificate.moduleApi()) +
                        "downloadAll",
                    data: params,
                    dataType: "json",
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
                            window.location.href = resp.file_url;
                        } else {
                            message.sweetError("Informasi", resp.message);
                        }
                    },
                });
            }
        });
    },
};

$(function () {
    // VerifikasiCertificate.editor();
    VerifikasiCertificate.setSelect2();
    VerifikasiCertificate.setDate();
    VerifikasiCertificate.getData();
});
