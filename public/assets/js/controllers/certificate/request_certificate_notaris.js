let RequestCertificateNotaris = {
    module: () => {
        return "certificate/verifikasi-certificate-notaris";
    },

    moduleApi: () => {
        return "api/" + RequestCertificateNotaris.module();
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
        window.location.href =
            url.base_url(RequestCertificateNotaris.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href =
            url.base_url(RequestCertificateNotaris.module()) + "add";
    },

    getPostInput: (id_item) => {
        let data = {
            data_request: RequestCertificateNotaris.getPostRequest(id_item),
            data_contract: RequestCertificateNotaris.getPostContract(id_item),
            data_sertificate:
                RequestCertificateNotaris.getPostDataSertificate(id_item),
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
            delegate_to: $(`input#delegate_to${id_item}`).val(),
            date_delegate: $(`#date_delegate${id_item}`).val(),
            seq_numbers: $(`#seq_numbers${id_item}`).val(),
            status: $(`select#status_${id_item}`).val(),
            remarks_verify: $(`#remarks_verify${id_item}`).val(),
            billing_number_ahu: $(`#billing_number_ahu${id_item}`).val(),
            date_input_ahu: $(`#date_input_ahu${id_item}`).val(),
            name_pnbp: $(`#name_pnbp${id_item}`).val(),
            // updater: $(`#updater${id_item}`).val(),
        };

        return data;
    },
    getPostDataSertificate: (id_item) => {
        let data = {
            file: $(`input#file${id_item}`).attr("src"),
            tipe: $(`input#file${id_item}`).attr("tipe"),
            file_name: $(`input#file${id_item}`).val(),
        };

        return data;
    },

    submit: (elm, id_item) => {
        let form = $(elm).closest(`div#content-save${id_item}`);
        if (validation.runWithElement(form)) {
            let params = RequestCertificateNotaris.getPostInput(id_item);
            // if (params.data_item.length == 0) {
            //     message.sweetError("Informasi", "Table Rate Harus Diisi");
            //     return;
            // }
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url:
                    url.base_url(RequestCertificateNotaris.moduleApi()) +
                    "submit",
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
                    url.base_url(RequestCertificateNotaris.moduleApi()) +
                    `getData`,
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
                    data: "request_sertificate",
                    render: function (data, type, row) {
                        // return console.log(row.request_contract.length);

                        return row.data_request_certificate.date_request;
                    },
                },
                {
                    data: "request_sertificate",
                    render: function (data, type, row) {
                        // return console.log(row.request_contract.length);

                        return row.data_request_certificate.no_request;
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
                        let status = row.status;
                        if (
                            (status == "DRAFT" ||
                                status == "ON PROCESS" ||
                                status == "APPROVE") &&
                            akses != "notaris"
                        ) {
                            var html = `<a href='${url.base_url(
                                RequestCertificateNotaris.module()
                            )}ubah?id=${row.id}' data_id="${
                                row.id
                            }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            if (updateAction == 1) {
                                html += `<a href='${url.base_url(
                                    RequestCertificateNotaris.module()
                                )}detail?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                                if (row.status == "CREATED") {
                                    html += `<a href='${url.base_url(
                                        RequestCertificateNotaris.module()
                                    )}ubah?id=${data}' data_id="${
                                        row.id
                                    }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                                }
                            }
                            if (deleteAction == 1) {
                                if (row.status != "CONFIRMED") {
                                    // html += `<button type="button" data_id="${row.id}" onclick="RequestCertificateNotaris.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                                }
                            }
                            return html;
                        }

                        return `<a href='${url.base_url(
                            RequestCertificateNotaris.module()
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
                    url.base_url(RequestCertificateNotaris.moduleApi()) +
                    `getData`,
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
                                RequestCertificateNotaris.module()
                            )}ubah?id=${row.id}' data_id="${
                                row.id
                            }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            if (updateAction == 1) {
                                html += `<a href='${url.base_url(
                                    RequestCertificateNotaris.module()
                                )}detail?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                                if (row.status == "CREATED") {
                                    html += `<a href='${url.base_url(
                                        RequestCertificateNotaris.module()
                                    )}ubah?id=${data}' data_id="${
                                        row.id
                                    }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                                }
                            }
                            if (deleteAction == 1) {
                                if (row.status != "CONFIRMED") {
                                    // html += `<button type="button" data_id="${row.id}" onclick="RequestCertificateNotaris.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                                }
                            }
                            return html;
                        }

                        return `<a href='${url.base_url(
                            RequestCertificateNotaris.module()
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
            url: url.base_url(RequestCertificateNotaris.moduleApi()) + "delete",
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
                url.base_url(RequestCertificateNotaris.moduleApi()) +
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
            url.base_url(RequestCertificateNotaris.module()) + "/";
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
            url:
                url.base_url(RequestCertificateNotaris.moduleApi()) + "confirm",
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
                        RequestCertificateNotaris.back();
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
            url:
                url.base_url(RequestCertificateNotaris.moduleApi()) +
                "duplicate",
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
                url.base_url(RequestCertificateNotaris.moduleApi()) +
                "showDataUserNotaris",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (resp) {
                message.closeLoading();
                $("#content-modal-form").html(resp);
                $("#btn-show-modal").trigger("click");
                RequestCertificateNotaris.getDataUserNotaris();
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
                    url.base_url(RequestCertificateNotaris.moduleApi()) +
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
                            <button type="button" nama_lengkap="${row.nama_lengkap}" onclick="RequestCertificateNotaris.pilihData(this, '')" data_id="${row.nik}" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></button>
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

    searchItem: () => {
        let no_contract = $(`#search_item_no_contract`).val();
        let status = $(`#search_item_status`).val();
        let id = $(`#id`).val();
        let params = {};
        params.no_kontrak = no_contract;
        params.status = status == "OUTSTANDING" ? "DRAFT" : status;
        params.id_request = id;

        $.ajax({
            type: "POST",
            url:
                url.base_url(RequestCertificateNotaris.moduleApi()) +
                "searchDataItem",
            data: params,
            dataType: "json",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },
            success: function (response) {
                message.closeLoading();
                let data = response.data;
                let no = 1;
                let html = ``;
                const data_status = ["REJECT", "APPROVE"];
                // return console.log(data.length > 0);
                if (data.length > 0) {
                    $.map(data, function (elementOrValue, indexOrKey) {
                        if (elementOrValue.status == "DRAFT") {
                            html += `
                    <div class="accordion-item" id="content-save${
                        elementOrValue.contract_number
                    }">
                        <h2 class="accordion-header" id="flush-heading${no}">
                            <button class="accordion-button fw-medium collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse${no}"
                                aria-expanded="true"
                                aria-controls="flush-collapse${no}">
                                #${no} / &nbsp
                                No. Kontrak: ${
                                    elementOrValue.contract_number
                                } / &nbsp
                                <span class="badge
                                    ${
                                        elementOrValue.status === "DRAFT"
                                            ? "bg-warning"
                                            : elementOrValue.status === "REJECT"
                                            ? "bg-danger"
                                            : "bg-success"
                                    }"
                                    style="font-size:12px;">
                                    ${elementOrValue.status}
                                </span>
                            </button>
                        </h2>
                        <div id="flush-collapse${no}" class="accordion-collapse collapse" aria-labelledby="flush-heading${no}" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            <button type="button" class="btn btn-sm btn-success waves-effect mb-4" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg572">
                                                                <i class="mdi mdi-eye"></i> Lihat Detail Data
                                                            </button>
                                <div>
                                    <form>
                                        ${generateFormRow(
                                            "Remarks Requestor",
                                            elementOrValue.remarks
                                        )}
                                        ${generateFormRow(
                                            "Nomor Kontrak",
                                            elementOrValue.contract_number
                                        )}
                                        ${generateFormRow(
                                            "Job Kontrak",
                                            elementOrValue.contract_job
                                        )}
                                        ${generateFormRow(
                                            "Debitur Finance",
                                            elementOrValue.debitur
                                        )}
                                        ${generateFormRow(
                                            "Alamat Debitur",
                                            elementOrValue.debitur_address
                                        )}
                                        ${generateFormRow(
                                            "Price Debitur",
                                            `Rp. ${elementOrValue.debitur_price}`
                                        )}
                                    </form>
                                </div>

                                ${
                                    elementOrValue.status === "DRAFT"
                                        ? generateDraftSection2(
                                              elementOrValue,
                                              data_status
                                          )
                                        : generateReadOnlySection(
                                              elementOrValue
                                          )
                                }

                            </div>
                        </div>
                    </div>
                    `;
                        } else if (elementOrValue.status == "REJECT") {
                            html += `
                        <div class="accordion-item" id="content-save${
                            elementOrValue.contract_number
                        }">
                            <h2 class="accordion-header" id="flush-heading${no}">
                                <button class="accordion-button fw-medium collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse${no}"
                                    aria-expanded="true"
                                    aria-controls="flush-collapse${no}">
                                    #${no} / &nbsp
                                    No. Kontrak: ${
                                        elementOrValue.contract_number
                                    } / &nbsp
                                <span class="badge
                                    ${
                                        elementOrValue.status === "DRAFT"
                                            ? "bg-warning"
                                            : elementOrValue.status === "REJECT"
                                            ? "bg-danger"
                                            : elementOrValue.status === "DONE"
                                            ? "bg-primary"
                                            : elementOrValue.status ===
                                              "COMPLETE"
                                            ? "bg-primary"
                                            : elementOrValue.status ===
                                              "FINISHED"
                                            ? "bg-dark"
                                            : "bg-success"
                                    }
                                    style="font-size:12px;">
                                    ${elementOrValue.status}
                                </span>
                                </button>
                            </h2>
                            <div id="flush-collapse${no}" class="accordion-collapse collapse" aria-labelledby="flush-heading${no}" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div>
                                        <form>
                                            ${generateFormRow(
                                                "Remarks Requestor",
                                                elementOrValue.remarks
                                            )}
                                            ${generateFormRow(
                                                "Nomor Kontrak",
                                                elementOrValue.contract_number
                                            )}
                                            ${generateFormRow(
                                                "Job Kontrak",
                                                elementOrValue.contract_job
                                            )}
                                            ${generateFormRow(
                                                "Debitur Finance",
                                                elementOrValue.debitur
                                            )}
                                            ${generateFormRow(
                                                "Alamat Debitur",
                                                elementOrValue.debitur_address
                                            )}
                                            ${generateFormRow(
                                                "Price Debitur",
                                                `Rp. ${elementOrValue.debitur_price}`
                                            )}
                                        </form>
                                    </div>

                                    ${
                                        elementOrValue.status === "DRAFT"
                                            ? generateDraftSection(
                                                  elementOrValue,
                                                  data_status
                                              )
                                            : generateReadOnlySection(
                                                  elementOrValue
                                              )
                                    }

                                </div>
                            </div>
                        </div>
                        `;
                        } else if (
                            elementOrValue.status == "APPROVE" ||
                            elementOrValue.status == "COMPLETE" ||
                            elementOrValue.status == "FINISHED" ||
                            elementOrValue.status == "DONE"
                        ) {
                            html += `
                        <div class="accordion-item" id="content-save${
                            elementOrValue.contract_number
                        }">
                            <h2 class="accordion-header" id="flush-heading${no}">
                                <button class="accordion-button fw-medium collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse${no}"
                                    aria-expanded="true"
                                    aria-controls="flush-collapse${no}">
                                    #${no} / &nbsp
                                    No. Kontrak: ${
                                        elementOrValue.contract_number
                                    } / &nbsp
                                <span class="badge
                                    ${
                                        elementOrValue.status === "DRAFT"
                                            ? "bg-warning"
                                            : elementOrValue.status === "REJECT"
                                            ? "bg-danger"
                                            : elementOrValue.status === "DONE"
                                            ? "bg-primary"
                                            : elementOrValue.status ===
                                              "COMPLETE"
                                            ? "bg-secondary"
                                            : elementOrValue.status ===
                                              "FINISHED"
                                            ? "bg-dark"
                                            : "bg-success"
                                    }
                                    style="font-size:12px;">
                                    ${elementOrValue.status}
                                </span>
                                </button>
                            </h2>
                            <div id="flush-collapse${no}" class="accordion-collapse collapse" aria-labelledby="flush-heading${no}" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div>
                                        <form>
                                            ${generateFormRow(
                                                "Remarks Requestor",
                                                elementOrValue.remarks
                                            )}
                                            ${generateFormRow(
                                                "Nomor Kontrak",
                                                elementOrValue.contract_number
                                            )}
                                            ${generateFormRow(
                                                "Job Kontrak",
                                                elementOrValue.contract_job
                                            )}
                                            ${generateFormRow(
                                                "Debitur Finance",
                                                elementOrValue.debitur
                                            )}
                                            ${generateFormRow(
                                                "Alamat Debitur",
                                                elementOrValue.debitur_address
                                            )}
                                            ${generateFormRow(
                                                "Price Debitur",
                                                `Rp. ${elementOrValue.debitur_price}`
                                            )}
                                        </form>
                                    </div>

                                    ${
                                        elementOrValue.status === "DRAFT"
                                            ? generateDraftSection2(
                                                  elementOrValue,
                                                  data_status
                                              )
                                            : generateReadOnlySection2(
                                                  elementOrValue
                                              )
                                    }

                                </div>
                            </div>
                        </div>
                        `;
                        }

                        no++;
                    });
                } else {
                    html += `
                        <div class="alert alert-secondary" role="alert">
                            Tidak ada data
                        </div>
                    `;
                }

                return $(`#accordionFlushExample`).html(html);
                function generateFormRow(label, value) {
                    return `
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">${label}</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> ${value}
                            </div>
                        </div>
                    `;
                }
                function generateFormRow2(label, value) {
                    return `
                        <div class="form-group row mb-4">
                            <label class="col-md-2 col-form-label">${label}</label>
                            <div class="col-md-10 m-auto">
                                ${value}
                            </div>
                        </div>
                    `;
                }

                function generateDraftSection(element, dataStatus) {
                    return `
                        <div>
                            <div class="mb-4"></div>
                            <h4 class="card-title">Contract Data Certificate</h4>
                            <div class="mb-4"></div>
                            <form>
                                <input type="hidden" name="id_item" id="id_item${
                                    element.contract_number
                                }" value="${element.id}">
                                <input type="hidden" name="no_request" id="no_request${
                                    element.contract_number
                                }" value="${element.no_request}">
                                ${generateDelegateForm(element)}
                                ${generateFormRow(
                                    "No. Minuta",
                                    generateInputField(
                                        "seq_numbers",
                                        element.contract_number,
                                        element.seq_number
                                    )
                                )}
                                ${generateStatusDropdown(
                                    "Status",
                                    element.contract_number,
                                    dataStatus
                                )}
                                <div id="content_remarks_verify${
                                    element.contract_number
                                }"></div>
                                ${generateFormRow(
                                    "Billing Number AHU",
                                    generateInputField(
                                        "billing_number_ahu",
                                        element.contract_number,
                                        element.billing_number_ahu
                                    )
                                )}
                                ${generateFormRow(
                                    "Date Input AHU",
                                    generateInputField(
                                        "date_input_ahu",
                                        element.contract_number,
                                        element.date_input_ahu,
                                        "datetime-local"
                                    )
                                )}
                                ${generateFormRow(
                                    "Nama PNPB",
                                    generateInputField(
                                        "name_pnbp",
                                        element.contract_number,
                                        element.name_pnbp
                                    )
                                )}
                                ${generateFormRow(
                                    "Nilai Barang",
                                    generateInputFieldReadOnly(
                                        "hutang_barang",
                                        element.contract_number,
                                        element.hutang_barang
                                    )
                                )}
                                ${generateFormRow(
                                    "Biaya PNBP",
                                    generateInputFieldReadOnly(
                                        "biaya_pnpb",
                                        element.contract_number,
                                        getCariBiaya(
                                            element.hutang_barang,
                                            "biaya_pnpb",
                                            element.contract_number
                                        )
                                    )
                                )}
                            </form>
                        </div>
                        <div class="text-end">
                            <button type="button" onclick="RequestCertificateNotaris.submit(this, '${
                                element.contract_number
                            }')" class="btn btn-success">
                                <i class="mdi mdi-check me-1"></i> Submit
                            </button>
                        </div>
                    `;
                }
                function generateDraftSection2(element, dataStatus) {
                    return `
                        <div>
                            <div class="mb-4"></div>
                            <h4 class="card-title">Contract Data Certificate</h4>
                            <div class="mb-4"></div>
                            <form>
                                <input type="hidden" name="id_item" id="id_item${
                                    element.contract_number
                                }" value="${element.id}">
                                <input type="hidden" name="no_request" id="no_request${
                                    element.contract_number
                                }" value="${element.no_request}">
                                <div class="form-group row mb-4">
                                                                <label for="delegate_to"
                                                                    class="col-md-2 col-form-label">Delegate
                                                                    To</label>
                                                                <div class="col-md-10">
                                                                    <div class="input-group">
                                                                        <button class="btn btn-outline-primary"
                                                                            type="button" id="button-addon1"
                                                                            onclick="RequestCertificateNotaris.showDataUserNotaris(this,'${
                                                                                element.contract_number
                                                                            }')">Pilih</button>
                                                                        <input readonly
                                                                            id_item="${
                                                                                element.contract_number
                                                                            }"
                                                                            id="delegate_to${
                                                                                element.contract_number
                                                                            }"
                                                                            type="text" class="form-control required"
                                                                            error="Pilih Delegate to"
                                                                            placeholder="Pilih Delegate to"
                                                                            aria-label="Pilih Delegate to"
                                                                            aria-describedby="button-addon1"
                                                                            value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                ${generateFormRow2(
                                    "No. Minuta",
                                    generateInputField(
                                        "No. Minuta",
                                        "seq_numbers",
                                        element.contract_number,
                                        element.seq_number
                                    )
                                )}
                                <div class="form-group row mb-4">
                                                                <label for="date_delegate${
                                                                    element.contract_number
                                                                }"
                                                                    class="col-md-2 col-form-label">Date
                                                                    Delegate</label>
                                                                <div class="col-md-10">
                                                                    <input type="date"
                                                                        class="form-control required"
                                                                        id="date_delegate${
                                                                            element.contract_number
                                                                        }"
                                                                        placeholder="Date Delegate"
                                                                        error="Date Delegate"
                                                                        value="">
                                                                </div>
                                                            </div>
                                ${generateStatusDropdown(
                                    "Status",
                                    element.contract_number,
                                    dataStatus
                                )}
                                <div id="content_remarks_verify${
                                    element.contract_number
                                }"></div>
                                ${generateFormRow2(
                                    "Billing Number AHU",
                                    generateInputField(
                                        "Billing Number AHU",
                                        "billing_number_ahu",
                                        element.contract_number,
                                        element.billing_number_ahu
                                    )
                                )}
                                ${generateFormRow2(
                                    "Date Input AHU",
                                    generateInputField(
                                        "Date Input AHU",
                                        "date_input_ahu",
                                        element.contract_number,
                                        element.date_input_ahu,
                                        "datetime-local"
                                    )
                                )}
                                ${generateFormRow2(
                                    "Nama PNPB",
                                    generateInputField(
                                        "Nama PNPB",
                                        "name_pnbp",
                                        element.contract_number,
                                        element.name_pnbp
                                    )
                                )}
                                ${generateFormRow2(
                                    "Nilai Barang",
                                    generateInputFieldReadOnly(
                                        "Nilai Barang",
                                        "hutang_barang",
                                        element.contract_number,
                                        element.hutang_barang
                                    )
                                )}
                                ${generateFormRow2(
                                    "Biaya PNBP",
                                    generateInputFieldReadOnly(
                                        "Biaya PNBP",
                                        "biaya_pnpb",
                                        element.contract_number,
                                        getCariBiaya(
                                            element.hutang_barang,
                                            "biaya_pnpb",
                                            element.contract_number
                                        )
                                    )
                                )}

                            </form>
                        </div>
                        <div class="text-end">
                            <button type="button" onclick="RequestCertificateNotaris.submit(this, '${
                                element.contract_number
                            }')" class="btn btn-success">
                                <i class="mdi mdi-check me-1"></i> Submit
                            </button>
                        </div>
                    `;
                }

                function generateDelegateForm(element) {
                    return `
                        <div class="form-group row mb-4">
                            <label class="col-md-2 col-form-label">Delegate To</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <button class="btn btn-outline-primary" type="button" id="button-addon1"
                                        onclick="RequestCertificateNotaris.showDataUserNotaris(this,'${
                                            element.contract_number
                                        }')">
                                        Pilih
                                    </button>
                                    <input readonly id_item="${
                                        element.contract_number
                                    }" id="delegate_to${
                        element.contract_number
                    }"
                                        type="text" class="form-control required" placeholder="Pilih Delegate to"
                                        value="${
                                            element.delegate_to
                                                ? element.delegate_to +
                                                  " - " +
                                                  element.user_delegate
                                                      .nama_lengkap
                                                : ""
                                        }">
                                </div>
                            </div>
                        </div>
                    `;
                }

                function generateStatusDropdown(
                    label,
                    contractNumber,
                    dataStatus
                ) {
                    // console.log(contractNumber);

                    return `
                        <div class="form-group row mb-4">
                            <label class="col-md-2 col-form-label">${label}</label>
                            <div class="col-md-10">
                                <select onchange="RequestCertificateNotaris.changeStatus(this, '${contractNumber}')"
                                    class="form-select select2 required" id="status_${contractNumber}" error="${label}">
                                    <option value="">Pilih Status</option>
                                    ${dataStatus
                                        .map(
                                            (status) =>
                                                `<option value="${status}">${status}</option>`
                                        )
                                        .join("")}
                                </select>
                            </div>
                        </div>
                    `;
                }

                function generateInputField(
                    label,
                    id,
                    contractNumber,
                    value,
                    type = "text"
                ) {
                    return `<input type="${type}" class="form-control required" id="${id}${contractNumber}" placeholder="${label}" error="${label}" value="${
                        value == null ? "" : value
                    }">`;
                }
                function generateInputFieldReadOnly(
                    label,
                    id,
                    contractNumber,
                    value,
                    type = "text"
                ) {
                    return `<input type="${type}" class="form-control required" id="${id}${contractNumber}" placeholder="${label}" error="${label}" value="${
                        value == null ? "" : value
                    }" readonly>`;
                }

                function generateReadOnlySection(element) {
                    return `
                        <div>
                            <div class="mb-4"></div>
                            <h4 class="card-title">Contract Data Certificate</h4>
                            <div class="mb-4"></div>
                            <form>
                                ${generateFormRow(
                                    "Delegate To",
                                    element.delegate_to
                                        ? element.delegate_to +
                                              " - " +
                                              element.user_delegate.nama_lengkap
                                        : ""
                                )}
                                ${generateFormRow(
                                    "Date Delegate",
                                    element.date_delegate
                                )}
                                ${generateFormRow(
                                    "No. Minuta",
                                    element.seq_number
                                )}
                                ${generateFormRow(
                                    "Status",
                                    `<span class="badge
                                        ${
                                            element.status === "DRAFT"
                                                ? "bg-warning"
                                                : element.status === "REJECT"
                                                ? "bg-danger"
                                                : element.status === "DONE"
                                                ? "bg-primary"
                                                : element.status === "COMPLETE"
                                                ? "bg-secondary"
                                                : element.status === "FINISHED"
                                                ? "bg-dark"
                                                : "bg-success"
                                        }
                                        style="font-size:12px;">
                                        ${element.status}
                                    </span>`
                                )}
                                ${generateFormRow(
                                    "Remarks Verify",
                                    element.remarks_verify == null
                                        ? ""
                                        : element.remarks_verify
                                )}
                                ${generateFormRow(
                                    "Billing Number AHU",
                                    element.billing_number_ahu
                                )}
                                ${generateFormRow(
                                    "Date Input AHU",
                                    element.date_input_ahu
                                )}
                                ${generateFormRow(
                                    "Nama PNPB",
                                    element.name_pnbp
                                )}
                                ${generateFormRow(
                                    "Nilai barang",
                                    element.hutang_barang
                                )}
                                ${generateFormRow(
                                    "Biaya PNBP",
                                    getCariBiaya(
                                        element.hutang_barang,
                                        "biaya_pnpb",
                                        element.contract_number
                                    )
                                )}
                            </form>
                        </div>
                    `;
                }
                function generateReadOnlySection2(element) {
                    return `
                        <div>
                            <div class="mb-4"></div>
                            <h4 class="card-title">Contract Data Certificate</h4>
                            <div class="mb-4"></div>
                            <form>
                                <input type="hidden" name="id_item" id="id_item${
                                    element.contract_number
                                }" value="${element.id}">
                                ${generateFormRow(
                                    "Delegate To",
                                    element.delegate_to
                                        ? element.delegate_to +
                                              " - " +
                                              element.user_delegate.nama_lengkap
                                        : ""
                                )}
                                ${generateFormRow(
                                    "Date Delegate",
                                    element.date_delegate
                                )}
                                ${generateFormRow(
                                    "No. Minuta",
                                    element.seq_number
                                )}
                                ${generateFormRow(
                                    "Status",
                                    `<span class="badge
                                        ${
                                            element.status === "DRAFT"
                                                ? "bg-warning"
                                                : element.status === "REJECT"
                                                ? "bg-danger"
                                                : element.status === "DONE"
                                                ? "bg-primary"
                                                : element.status === "COMPLETE"
                                                ? "bg-secondary"
                                                : element.status === "FINISHED"
                                                ? "bg-dark"
                                                : "bg-success"
                                        }
                                        style="font-size:12px;">
                                        ${element.status}
                                    </span>`
                                )}
                                ${generateFormRow(
                                    "Remarks Verify",
                                    element.remarks_verify == null
                                        ? ""
                                        : element.remarks_verify
                                )}
                                ${generateFormRow(
                                    "Billing Number AHU",
                                    element.billing_number_ahu
                                )}
                                ${generateFormRow(
                                    "Date Input AHU",
                                    element.date_input_ahu
                                )}
                                ${generateFormRow(
                                    "Nama PNPB",
                                    element.name_pnbp
                                )}

                                <div class="form-group row">
                                    <label for="sertificate_file${
                                        element.contract_number
                                    }" class="col-md-2 col-form-label">Upload Sertificate File</label>
                                    <div class="col-md-10 m-auto">
                                        ${
                                            element.sertificate_file != null
                                                ? `
                                        <a href="#"  onclick="return RequestCertificateNotaris.confirmDownload('${
                                            element.sertificate_file
                                        }', '${
                                                      element.sertificate_path +
                                                      element.sertificate_file
                                                  }')">
                                                    ${
                                                        element.sertificate_file
                                                    }</a>
                                        `
                                                : `
                                        <div class="input-group">
                                            <input id="file${element.contract_number}" type="text" readonly class="form-control required"
                                                placeholder="Pilih Data File" aria-label="Pilih Data File" src="" error="Data File"
                                                aria-describedby="button-addon1" value="">
                                            <button class="btn btn-outline-secondary" type="button" id="button-addon1"
                                                onclick="RequestCertificateNotaris.addFile(this, '${element.contract_number}')">Choose File</button>
                                        </div>
                                        <br>
                                        <div class="text-end mb-4">
                                            <button type="button" onclick="RequestCertificateNotaris.submit(this, '${element.contract_number}')"
                                                class="btn btn-success">
                                                <i class="mdi mdi-check me-1"></i> Submit
                                            </button>
                                        </div>
                                        `
                                        }
                                    </div>
                                </div>
                            </form>
                        </div>
                    `;
                }

                function getCariBiaya(nilai_barang, id, element) {
                    let data = null;
                    $.ajax({
                        type: "POST",
                        url:
                            url.base_url(
                                RequestCertificateNotaris.moduleApi()
                            ) + "cariBiaya",
                        data: {
                            nilai_barang: nilai_barang,
                        },
                        dataType: "json",
                        success: function (response) {
                            data = response;

                            if (data != null) {
                                $(`#${id + element}`).val(data);
                            } else {
                                $(`#${id + element}`).val("");
                            }
                        },
                    });

                    return data;
                }
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
        });

        // return console.log(params);
    },
    searchReset: () => {
        window.location.reload();
    },

    addFile: (elm, id_item) => {
        // Buat uploader secara dinamis
        var uploader = $(
            `<input type="file" id="file${id_item}" accept="image/*;capture=camera" />`
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
                if (["jpg", "jpeg", "png", "pdf"].includes(type_file)) {
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
    showModalMinuta: (id, no_kontrak) => {
        $("#modal-warkah").modal("show");
        $("#modalMinuta").html(`Insert Minuta di No. Request # ${no_kontrak}`);
        if (id != RequestCertificateNotaris.dump_id) {
            let html = `
                            <tr class="input" data_id="${id}">
                                <td>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="button-addon1"
                                            onclick="RequestCertificateNotaris.addFileMinuta(this)">Choose File</button>
                                        <input id="file" type="text" readonly class="form-control required"
                                            placeholder="Pilih Data File" aria-label="Pilih Data File" src=""
                                            error="Data File" aria-describedby="button-addon1" value="">
                                            <input type="hidden" name="request_sertificate_contract" id="request_sertificate_contract" value="${id}">

                                    </div>
                                </td>
                                <td>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                        error="Keterangan" placeholder="Masukkan Keterangan..."></textarea>
                                </td>


                                <td id="action"></td>
                            </tr>
                            <tr id="add-item-row">
                                <td colspan="2" class="text-start">
                                    <a href="#" onclick="RequestCertificateNotaris.addItemMinuta(this, event)">Tambah
                                        Item</a>
                                </td>
                            </tr>
                            `;
            $("#table-minuta").find("tbody").html(html);
            RequestCertificateNotaris.dump_id = id;
        }

        $("tr.input").attr("data_id", id);
    },

    addItemMinuta: (elm, e) => {
        let table = $("#table-minuta").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.find("textarea").val("");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="RequestCertificateNotaris.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
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
            let params = RequestCertificateNotaris.getPostdataMinuta();
            // return console.log(params);

            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url:
                    url.base_url(RequestCertificateNotaris.moduleApi()) +
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
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda akan mengakhiri pekerjaan dibatch ini ?, dan akan otomatis mengirimkan notifikasi ke vendor`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Seleaikan!",
        }).then((result) => {
            if (result.value) {
                let params = {};
                params.idr_notaris = $(`#idr_notaris`).val();
                // return console.log(params);

                $.ajax({
                    type: "POST",
                    url:
                        url.base_url(RequestCertificateNotaris.moduleApi()) +
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
            }
        });
    },
    konfirmasiPembayaran: (elm, e) => {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda akan mengkonfirmasi pembayaran pekerjaan dibatch ini ?, dan otomatis akan mengirimkan notifikasi ke vendor`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Konfirmasi!",
        }).then((result) => {
            if (result.value) {
                let params = {};
                params.idr_notaris = $(`#idr_notaris`).val();
                // return console.log(params);

                $.ajax({
                    type: "POST",
                    url:
                        url.base_url(RequestCertificateNotaris.moduleApi()) +
                        "konfirmasiPembayaran",
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
            }
        });
    },

    inputNoNotaris: (elm, e) => {
        let form = $(elm).closest(`div#generate-number`);
        if (validation.runWithElement(form)) {
            e.preventDefault();
            let params = {};
            params.no_notaris = $(`#no_notaris`).val();
            params.idr_notaris = $(`#idr_notaris`).val();
            params.waktu = $(`#waktu`).val();
            params.waktu_jeda = $(`#waktu_jeda`).val();

            $.ajax({
                type: "POST",
                url:
                    url.base_url(RequestCertificateNotaris.moduleApi()) +
                    "generateNumber",
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
        } else {
            message.sweetError("Informasi", "Data Belum Lengkap");
        }
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

    inputAktaSela: (id_kontrak) => {
        // return console.log(id_kontrak);
        let form = $(`div#content-modal-save${id_kontrak}`);
        if (validation.runWithElement(form)) {
            let params = {};
            params.no_notaris = $(`#no_notaris${id_kontrak}`).val();
            params.new_number_generate = $(
                `#new_number_generate${id_kontrak}`
            ).val();
            params.waktu = $(`#waktu${id_kontrak}`).val();
            params.no_minuta = $(`#no_minuta${id_kontrak}`).val();
            params.waktu_jeda = $(`#waktu_jeda${id_kontrak}`).val();
            $.ajax({
                type: "POST",
                url:
                    url.base_url(RequestCertificateNotaris.moduleApi()) +
                    "inputAktaSela",
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
        } else {
            message.sweetError("Informasi", "Data Belum Lengkap");
        }
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
                    url: url.base_url(RequestCertificateNotaris.moduleApi()) + "downloadAll",
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
    // RequestCertificateNotaris.editor();
    // RequestCertificateNotaris.setSelect2();
    RequestCertificateNotaris.setDate();
    RequestCertificateNotaris.getData();
});
