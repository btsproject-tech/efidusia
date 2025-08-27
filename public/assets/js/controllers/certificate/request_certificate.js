let RequestCertificate = {
    module: () => {
        return "certificate/request-certificate";
    },

    moduleApi: () => {
        return "api/" + RequestCertificate.module();
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
        window.location.href = url.base_url(RequestCertificate.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href =
            url.base_url(RequestCertificate.module()) + "add";
    },

    getPostInput: () => {
        let data = {
            data_request: RequestCertificate.getPostRequest(),
            data_contract: RequestCertificate.getPostContract(),
            // data_warkah: RequestCertificate.getPostdataWarkah(),
        };

        return data;
    },
    getPostRequest: () => {
        let data = {
            id: $("input#id").val(),
            no_request: $("#no_request").val(),
            creator: $("#creator").val(),
            date_request: $("#date_request").val(),
            // remarks: tinymce.get("remarks").getContent(),
            remarks: $("#remarks").val(),
        };

        return data;
    },
    getPostContract: () => {
        let data = [];
        let table = $("#table-rate").find("tbody").find("tr.input");
        $.each(table, function () {
            let params = {};
            params.debitur = $(this).find("#debitur").val();
            params.contract_number = $(this).find("#contract_number").val();
            params.no_perjanjian_kontrak = $(this)
                .find("#no_perjanjian_kontrak")
                .val();
            params.pemberi_fidusia = $(this).find("#pemberi_fidusia").val();
            params.jenis_kelamin = $(this).find("#jenis_kelamin").val();
            params.tempat_lahir = $(this).find("#tempat_lahir").val();
            params.tanggal_lahir = $(this).find("#tanggal_lahir").val();
            params.pekerjaan = $(this).find("#pekerjaan").val();
            params.alamat = $(this).find("#alamat").val();
            params.rt = $(this).find("#rt").val();
            params.rw = $(this).find("#rw").val();
            params.kelurahan = $(this).find("#kelurahan").val();
            params.kecamatan = $(this).find("#kecamatan").val();
            params.kabupaten = $(this).find("#kabupaten").val();
            params.provinsi = $(this).find("#provinsi").val();
            params.kode_pos = $(this).find("#kode_pos").val();
            params.ktp = $(this).find("#ktp").val();
            params.npwp = $(this).find("#npwp").val();
            params.no_telp = $(this).find("#no_telp").val();
            params.status_perkawinan = $(this).find("#status_perkawinan").val();
            params.nama_pasangan = $(this).find("#nama_pasangan").val();
            params.tanggal_kuasa = $(this).find("#tanggal_kuasa").val();
            params.hutang_pokok = $(this).find("#hutang_pokok").val();
            params.nilai_jaminan = $(this).find("#nilai_jaminan").val();
            params.hutang_barang = $(this).find("#hutang_barang").val();
            params.merk = $(this).find("#merk").val();
            params.tipe = $(this).find("#tipe").val();
            params.tahun = $(this).find("#tahun").val();
            params.warna = $(this).find("#warna").val();
            params.no_rangka = $(this).find("#no_rangka").val();
            params.no_mesin = $(this).find("#no_mesin").val();
            params.nopol = $(this).find("#nopol").val();
            params.pemilik_bpkb = $(this).find("#pemilik_bpkb").val();
            params.nomor_bpkb = $(this).find("#nomor_bpkb").val();
            params.customer_tipe = $(this).find("#customer_tipe").val();
            params.tgl_awal_tenor = $(this).find("#tgl_awal_tenor").val();
            params.tgl_akhir_tenor = $(this).find("#tgl_akhir_tenor").val();
            params.type_produk = $(this).find("#type_produk").val();
            params.cab = $(this).find("#cab").val();
            params.rep = $(this).find("#rep").val();
            params.kondisi = $(this).find("#kondisi").val();
            data.push(params);
        });
        return JSON.stringify(data);
    },

    submit: (elm, e) => {
        e.preventDefault();
        let form = $(elm).closest("div.content-save");
        if (validation.runWithElement(form)) {
            let params = RequestCertificate.getPostInput();

            // return message.loadingProses("Proses Simpan Data...");
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(RequestCertificate.moduleApi()) + "submit",
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
                        // return console.log(resp);

                        setTimeout(function () {
                            RequestCertificate.back();
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
        let date = new Date();
        let dateTimeNow =
            date.getFullYear() +
            "-" +
            ("0" + (date.getMonth() + 1)).slice(-2) +
            "-" +
            ("0" + date.getDate()).slice(-2) +
            " " +
            ("0" + date.getHours()).slice(-2) +
            ":" +
            ("0" + date.getMinutes()).slice(-2) +
            ":" +
            ("0" + date.getSeconds()).slice(-2);

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
                url: url.base_url(RequestCertificate.moduleApi()) + `getData`,
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
                            html = `<span class="badge bg-primary" style="font-size:12px;">${data}</span>`;
                        } else if (data == "FINISHED") {
                            html = `<span class="badge bg-dark" style="font-size:12px;">${data}</span>`;
                        } else if (data == "DONE") {
                            html = `<span class="badge bg-primary" style="font-size:12px;">${data}</span>`;
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
                        if (status == "DRAFT" && dateTimeNow > row.created_at) {
                            var html = `<a href='${url.base_url(
                                RequestCertificate.module()
                            )}ubah?id=${row.id}' data_id="${
                                row.id
                            }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light disabled"><i class="bx bx-edit"></i></a>&nbsp;`;
                            if (updateAction == 1) {
                                html += `<a href='${url.base_url(
                                    RequestCertificate.module()
                                )}detail?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                                if (row.status == "CREATED") {
                                    html += `<a href='${url.base_url(
                                        RequestCertificate.module()
                                    )}ubah?id=${data}' data_id="${
                                        row.id
                                    }" class="btn btn-success editable-submit btn-sm waves-effect waves-light disabled"><i class="bx bx-edit"></i></a>&nbsp;`;
                                }
                            }
                            if (deleteAction == 1) {
                                if (row.status != "CONFIRMED") {
                                    html += `<button type="button" data_id="${row.id}" onclick="RequestCertificate.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light disabled"><i class="bx bx-trash-alt"></i></button>`;
                                }
                            }
                            return html;
                        }

                        if (status == "DRAFT") {
                            var html = `<a href='${url.base_url(
                                RequestCertificate.module()
                            )}ubah?id=${row.id}' data_id="${
                                row.id
                            }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            if (updateAction == 1) {
                                html += `<a href='${url.base_url(
                                    RequestCertificate.module()
                                )}detail?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                                if (row.status == "CREATED") {
                                    html += `<a href='${url.base_url(
                                        RequestCertificate.module()
                                    )}ubah?id=${data}' data_id="${
                                        row.id
                                    }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                                }
                            }
                            if (deleteAction == 1) {
                                if (row.status != "CONFIRMED") {
                                    html += `<button type="button" data_id="${row.id}" onclick="RequestCertificate.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                                }
                            }
                            return html;
                        }

                        return `<a href='${url.base_url(
                            RequestCertificate.module()
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

        let date = new Date();
        let dateTimeNow =
            date.getFullYear() +
            "-" +
            ("0" + (date.getMonth() + 1)).slice(-2) +
            "-" +
            ("0" + date.getDate()).slice(-2) +
            " " +
            ("0" + date.getHours()).slice(-2) +
            ":" +
            ("0" + date.getMinutes()).slice(-2) +
            ":" +
            ("0" + date.getSeconds()).slice(-2);

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
                url: url.base_url(RequestCertificate.moduleApi()) + `getData`,
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
                            html = `<span class="badge bg-primary" style="font-size:12px;">${data}</span>`;
                        } else if (data == "FINISHED") {
                            html = `<span class="badge bg-dark" style="font-size:12px;">${data}</span>`;
                        } else if (data == "DONE") {
                            html = `<span class="badge bg-primary" style="font-size:12px;">${data}</span>`;
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
                        if (status == "DRAFT" && dateTimeNow > row.created_at) {
                            var html = `<a href='${url.base_url(
                                RequestCertificate.module()
                            )}ubah?id=${row.id}' data_id="${
                                row.id
                            }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light disabled"><i class="bx bx-edit"></i></a>&nbsp;`;
                            if (updateAction == 1) {
                                html += `<a href='${url.base_url(
                                    RequestCertificate.module()
                                )}detail?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                                if (row.status == "CREATED") {
                                    html += `<a href='${url.base_url(
                                        RequestCertificate.module()
                                    )}ubah?id=${data}' data_id="${
                                        row.id
                                    }" class="btn btn-success editable-submit btn-sm waves-effect waves-light disabled"><i class="bx bx-edit"></i></a>&nbsp;`;
                                }
                            }
                            if (deleteAction == 1) {
                                if (row.status != "CONFIRMED") {
                                    html += `<button type="button" data_id="${row.id}" onclick="RequestCertificate.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light disabled"><i class="bx bx-trash-alt"></i></button>`;
                                }
                            }
                            return html;
                        }

                        if (status == "DRAFT") {
                            var html = `<a href='${url.base_url(
                                RequestCertificate.module()
                            )}ubah?id=${row.id}' data_id="${
                                row.id
                            }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            if (updateAction == 1) {
                                html += `<a href='${url.base_url(
                                    RequestCertificate.module()
                                )}detail?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                                if (row.status == "CREATED") {
                                    html += `<a href='${url.base_url(
                                        RequestCertificate.module()
                                    )}ubah?id=${data}' data_id="${
                                        row.id
                                    }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                                }
                            }
                            if (deleteAction == 1) {
                                if (row.status != "CONFIRMED") {
                                    html += `<button type="button" data_id="${row.id}" onclick="RequestCertificate.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                                }
                            }
                            return html;
                        }

                        return `<a href='${url.base_url(
                            RequestCertificate.module()
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
            url: url.base_url(RequestCertificate.moduleApi()) + "delete",
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
            url: url.base_url(RequestCertificate.moduleApi()) + "confirmDelete",
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
        window.location.href = url.base_url(RequestCertificate.module()) + "/";
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
        // Tentukan apakah origin atau destination
        $("button.btn-close").trigger("click");
        // Set data origin
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
            url: url.base_url(RequestCertificate.moduleApi()) + "confirm",
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
                        RequestCertificate.back();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", resp.message);
                }
            },
        });
    },

    addItem: (elm, e) => {
        e.preventDefault();
        let table = $("#table-rate").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="RequestCertificate.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
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
                                        RequestCertificate.processData(
                                            dataJson
                                        );

                                    // Iterasi data dari file Excel
                                    $.each(dataJson, function (index, val) {
                                        if (
                                            typeof val["PemberiFidusia"] != null
                                        ) {
                                            if (emptyRows.length > 0) {
                                                // Jika ada baris kosong, isi baris tersebut
                                                let currentRow =
                                                    emptyRows.eq(0); // Ambil baris pertama yang kosong
                                                currentRow
                                                    .find("#pemberi_fidusia")
                                                    .val(
                                                        val["PemberiFidusia"] ==
                                                            null ||
                                                            val[
                                                                "PemberiFidusia"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "PemberiFidusia"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#jenis_kelamin")
                                                    .val(
                                                        val["JenisKelamin"] ==
                                                            null ||
                                                            val[
                                                                "JenisKelamin"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "JenisKelamin"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#tempat_lahir")
                                                    .val(
                                                        val["TempatLahir"] ==
                                                            null ||
                                                            val[
                                                                "TempatLahir"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val["TempatLahir"]
                                                    );
                                                currentRow
                                                    .find("#tanggal_lahir")
                                                    .val(
                                                        val["TanggalLahir"] ==
                                                            null ||
                                                            val[
                                                                "TanggalLahir"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "TanggalLahir"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#pekerjaan")
                                                    .val(
                                                        val["Pekerjaan"] ==
                                                            null ||
                                                            val["Pekerjaan"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Pekerjaan"]
                                                    );
                                                currentRow
                                                    .find("#alamat")
                                                    .val(
                                                        val["Alamat"] == null ||
                                                            val["Alamat"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Alamat"]
                                                    );
                                                currentRow
                                                    .find("#rt")
                                                    .val(
                                                        val["RT"] == null ||
                                                            val["RT"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["RT"]
                                                    );
                                                currentRow
                                                    .find("#rw")
                                                    .val(
                                                        val["RW"] == null ||
                                                            val["RW"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["RW"]
                                                    );
                                                currentRow
                                                    .find("#kelurahan")
                                                    .val(
                                                        val["Kelurahan"] ==
                                                            null ||
                                                            val["Kelurahan"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Kelurahan"]
                                                    );
                                                currentRow
                                                    .find("#kecamatan")
                                                    .val(
                                                        val["Kecamatan"] ==
                                                            null ||
                                                            val["Kecamatan"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Kecamatan"]
                                                    );
                                                currentRow
                                                    .find("#kabupaten")
                                                    .val(
                                                        val["Kabupaten"] ==
                                                            null ||
                                                            val["Kabupaten"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Kabupaten"]
                                                    );
                                                currentRow
                                                    .find("#provinsi")
                                                    .val(
                                                        val["Provinsi"] ==
                                                            null ||
                                                            val["Provinsi"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Provinsi"]
                                                    );
                                                currentRow
                                                    .find("#kode_pos")
                                                    .val(
                                                        val["KodePos"] ==
                                                            null ||
                                                            val["KodePos"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["KodePos"]
                                                    );
                                                currentRow
                                                    .find("#ktp")
                                                    .val(
                                                        val["KTP"] == null ||
                                                            val["KTP"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["KTP"]
                                                    );
                                                currentRow
                                                    .find("#npwp")
                                                    .val(
                                                        val["NPWP"] == null ||
                                                            val["NPWP"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["NPWP"]
                                                    );
                                                currentRow
                                                    .find("#no_telp")
                                                    .val(
                                                        val["NoTelp"] == null ||
                                                            val["NoTelp"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["NoTelp"]
                                                    );
                                                currentRow
                                                    .find("#status_perkawinan")
                                                    .val(
                                                        val[
                                                            "StatusPerkawinan"
                                                        ] == null ||
                                                            val[
                                                                "StatusPerkawinan"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "StatusPerkawinan"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#nama_pasangan")
                                                    .val(
                                                        val["NamaPasangan"] ==
                                                            null ||
                                                            val[
                                                                "NamaPasangan"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "NamaPasangan"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#tanggal_kuasa")
                                                    .val(
                                                        val["TanggalKuasa"] ==
                                                            null ||
                                                            val[
                                                                "TanggalKuasa"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "TanggalKuasa"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#hutang_pokok")
                                                    .val(
                                                        val["HutangPokok"] ==
                                                            null ||
                                                            val[
                                                                "HutangPokok"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val["HutangPokok"]
                                                    );
                                                currentRow
                                                    .find("#nilai_jaminan")
                                                    .val(
                                                        val["NilaiJaminan"] ==
                                                            null ||
                                                            val[
                                                                "NilaiJaminan"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "NilaiJaminan"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#hutang_barang")
                                                    .val(
                                                        val["NilaiBarang"] ==
                                                            null ||
                                                            val[
                                                                "NilaiBarang"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val["NilaiBarang"]
                                                    );
                                                currentRow
                                                    .find("#merk")
                                                    .val(
                                                        val["Merk"] == null ||
                                                            val["Merk"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Merk"]
                                                    );
                                                currentRow
                                                    .find("#tipe")
                                                    .val(
                                                        val["Tipe"] == null ||
                                                            val["Tipe"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Tipe"]
                                                    );
                                                currentRow
                                                    .find("#tahun")
                                                    .val(
                                                        val["Tahun"] == null ||
                                                            val["Tahun"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Tahun"]
                                                    );
                                                currentRow
                                                    .find("#warna")
                                                    .val(
                                                        val["Warna"] == null ||
                                                            val["Warna"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Warna"]
                                                    );
                                                currentRow
                                                    .find("#no_rangka")
                                                    .val(
                                                        val["NomorRangka"] ==
                                                            null ||
                                                            val[
                                                                "NomorRangka"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val["NomorRangka"]
                                                    );
                                                currentRow
                                                    .find("#no_mesin")
                                                    .val(
                                                        val["NomorMesin"] ==
                                                            null ||
                                                            val["NomorMesin"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["NomorMesin"]
                                                    );
                                                currentRow
                                                    .find("#nopol")
                                                    .val(
                                                        val["NomorPolisi"] ==
                                                            null ||
                                                            val[
                                                                "NomorPolisi"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val["NomorPolisi"]
                                                    );
                                                currentRow
                                                    .find("#pemilik_bpkb")
                                                    .val(
                                                        val["PemilikBPKB"] ==
                                                            null ||
                                                            val[
                                                                "PemilikBPKB"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val["PemilikBPKB"]
                                                    );
                                                currentRow
                                                    .find("#nomor_bpkb")
                                                    .val(
                                                        val["NomorBPKB"] ==
                                                            null ||
                                                            val["NomorBPKB"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["NomorBPKB"]
                                                    );
                                                currentRow
                                                    .find("#customer_tipe")
                                                    .val(
                                                        val["CustomerType"] ==
                                                            null ||
                                                            val[
                                                                "CustomerType"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "CustomerType"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#tgl_awal_tenor")
                                                    .val(
                                                        val[
                                                            "TanggalAwalTenor"
                                                        ] == null ||
                                                            val[
                                                                "TanggalAwalTenor"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "TanggalAwalTenor"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#tgl_akhir_tenor")
                                                    .val(
                                                        val[
                                                            "TanggalAkhirTenor"
                                                        ] == null ||
                                                            val[
                                                                "TanggalAkhirTenor"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "TanggalAkhirTenor"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#type_produk")
                                                    .val(
                                                        val["TypeProduk"] ==
                                                            null ||
                                                            val["TypeProduk"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["TypeProduk"]
                                                    );
                                                currentRow
                                                    .find("#cab")
                                                    .val(
                                                        val["Cab"] == null ||
                                                            val["Cab"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Cab"]
                                                    );
                                                currentRow
                                                    .find("#rep")
                                                    .val(
                                                        val["Rep"] == null ||
                                                            val["Rep"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Rep"]
                                                    );
                                                currentRow
                                                    .find("#kondisi")
                                                    .val(
                                                        val["Kondisi"] ==
                                                            null ||
                                                            val["Kondisi"] ==
                                                                "undefined"
                                                            ? "-"
                                                            : val["Kondisi"]
                                                    );
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
                                                    .find(
                                                        "#no_perjanjian_kontrak"
                                                    )
                                                    .val(
                                                        val[
                                                            "No_Perjanjian_Kontrak"
                                                        ] == null ||
                                                            val[
                                                                "No_Perjanjian_Kontrak"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val[
                                                                  "No_Perjanjian_Kontrak"
                                                              ]
                                                    );
                                                currentRow
                                                    .find("#debitur")
                                                    .val(
                                                        val["NamaDebitur"] ==
                                                            null ||
                                                            val[
                                                                "NamaDebitur"
                                                            ] == "undefined"
                                                            ? "-"
                                                            : val["NamaDebitur"]
                                                    );

                                                // Hapus baris kosong dari daftar setelah diisi
                                                emptyRows = emptyRows.slice(1);
                                            } else {
                                                // Jika tidak ada baris kosong, tambahkan baris baru
                                                let row = `<tr class="input" data_id="">
                                                    <td>
                                                        <input type="text" class="form-control required" id="pemberi_fidusia" error="Pemberi Fidusia" placeholder="Pemberi Fidusia" value="${
                                                            val[
                                                                "PemberiFidusia"
                                                            ]
                                                                ? val[
                                                                      "PemberiFidusia"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="jenis_kelamin" error="Jenis Kelamin" placeholder="Jenis Kelamin" value="${
                                                            val["JenisKelamin"]
                                                                ? val[
                                                                      "JenisKelamin"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="tempat_lahir" error="Tempat Lahir" placeholder="Tempat Lahir" value="${
                                                            val["TempatLahir"]
                                                                ? val[
                                                                      "TempatLahir"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control required" id="tanggal_lahir" error="Tanggal Lahir" placeholder="Tanggal Lahir" value="${
                                                            val["TanggalLahir"]
                                                                ? val[
                                                                      "TanggalLahir"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="pekerjaan" error="Pekerjaan" placeholder="Pekerjaan" value="${
                                                            val["Pekerjaan"]
                                                                ? val[
                                                                      "Pekerjaan"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="alamat" error="Alamat" placeholder="Alamat" value="${
                                                            val["Alamat"]
                                                                ? val["Alamat"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="rt" error="RT" placeholder="RT" value="${
                                                            val["RT"]
                                                                ? val["RT"]
                                                                : "-"
                                                        }" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="rw" error="RW" placeholder="RW" value="${
                                                            val["RW"]
                                                                ? val["RW"]
                                                                : "-"
                                                        }" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="kelurahan" error="Kelurahan" placeholder="Kelurahan" value="${
                                                            val["Kelurahan"]
                                                                ? val[
                                                                      "Kelurahan"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="kecamatan" error="Kecamatan" placeholder="Kecamatan" value="${
                                                            val["Kecamatan"]
                                                                ? val[
                                                                      "Kecamatan"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="kabupaten" error="Kabupaten" placeholder="Kabupaten" value="${
                                                            val["Kabupaten"]
                                                                ? val[
                                                                      "Kabupaten"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="provinsi" error="Provinsi" placeholder="Provinsi" value="${
                                                            val["Provinsi"]
                                                                ? val[
                                                                      "Provinsi"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="kode_pos" error="Kode POS" placeholder="Kode POS" value="${
                                                            val["KodePos"]
                                                                ? val["KodePos"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="ktp" error="KTP" placeholder="KTP" value="${
                                                            val["KTP"]
                                                                ? val["KTP"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="npwp" error="NPWP" placeholder="NPWP" value="${
                                                            val["NPWP"]
                                                                ? val["NPWP"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="no_telp" error="No Telp" placeholder="No Telp" value="${
                                                            val["NoTelp"]
                                                                ? val["NoTelp"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="status_perkawinan" error="Status Perkawinan" placeholder="Status Perkawinan" value="${
                                                            val[
                                                                "StatusPerkawinan"
                                                            ]
                                                                ? val[
                                                                      "StatusPerkawinan"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="nama_pasangan" error="Nama Pasangan" placeholder="Nama Pasangan" value="${
                                                            val["NamaPasangan"]
                                                                ? val[
                                                                      "NamaPasangan"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control required" id="tanggal_kuasa" error="Tanggal Kuasa" placeholder="Tanggal Kuasa" value="${
                                                            val["TanggalKuasa"]
                                                                ? val[
                                                                      "TanggalKuasa"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="contract_number" error="Nomor Kontrak" placeholder="Nomor Kontrak"
                                                            error="Nomor Kontrak" value="${
                                                                val["NoKontrak"]
                                                                    ? val[
                                                                          "NoKontrak"
                                                                      ]
                                                                    : "-"
                                                            }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="no_perjanjian_kontrak" error="Nomor Perjanjian Kontrak" placeholder="Nomor Perjanjian Kontrak"
                                                            error="Nomor Perjanjian Kontrak" value="${
                                                                val[
                                                                    "No_Perjanjian_Kontrak"
                                                                ]
                                                                    ? val[
                                                                          "No_Perjanjian_Kontrak"
                                                                      ]
                                                                    : "-"
                                                            }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="debitur" error="Nama Debitur" placeholder="Nama Debitur"
                                                            error="Nama Debitur" value="${
                                                                val[
                                                                    "NamaDebitur"
                                                                ]
                                                                    ? val[
                                                                          "NamaDebitur"
                                                                      ]
                                                                    : "-"
                                                            }">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="hutang_pokok" error="Hutang Pokok" placeholder="Hutang Pokok" value="${
                                                            val["HutangPokok"]
                                                                ? val[
                                                                      "HutangPokok"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="nilai_jaminan" error="Nilai Jaminan" placeholder="Nilai Jaminan" value="${
                                                            val["NilaiJaminan"]
                                                                ? val[
                                                                      "NilaiJaminan"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="hutang_barang" error="Biaya OTR" placeholder="Biaya OTR" value="${
                                                            val["NilaiBarang"]
                                                                ? val[
                                                                      "NilaiBarang"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="merk" error="Merk" placeholder="Merk" value="${
                                                            val["Merk"]
                                                                ? val["Merk"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="tipe" error="Tipe" placeholder="Tipe" value="${
                                                            val["Tipe"]
                                                                ? val["Tipe"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="tahun" error="Tahun" placeholder="Tahun" value="${
                                                            val["Tahun"]
                                                                ? val["Tahun"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="warna" error="Warna" placeholder="Warna" value="${
                                                            val["Warna"]
                                                                ? val["Warna"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="no_rangka" error="Nomor Rangka" placeholder="Nomor Rangka" value="${
                                                            val["NomorRangka"]
                                                                ? val[
                                                                      "NomorRangka"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="no_mesin" error="Nomor Mesin" placeholder="Nomor Mesin" value="${
                                                            val["NomorMesin"]
                                                                ? val[
                                                                      "NomorMesin"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="nopol" error="Nomor Polisi" placeholder="Nomor Polisi" value="${
                                                            val["NomorPolisi"]
                                                                ? val[
                                                                      "NomorPolisi"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="pemilik_bpkb" error="Pemilik BPKB" placeholder="Pemilik BPKB" value="${
                                                            val["PemilikBPKB"]
                                                                ? val[
                                                                      "PemilikBPKB"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="nomor_bpkb" error="Nomor BPKB" placeholder="Nomor BPKB" value="${
                                                            val["NomorBPKB"]
                                                                ? val[
                                                                      "NomorBPKB"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="customer_tipe" error="Customer Type" placeholder="Customer Type" value="${
                                                            val["CustomerType"]
                                                                ? val[
                                                                      "CustomerType"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control required" id="tgl_awal_tenor" error="Tanggal Awal Tenor" placeholder="Tanggal Awal Tenor" value="${
                                                            val[
                                                                "TanggalAwalTenor"
                                                            ]
                                                                ? val[
                                                                      "TanggalAwalTenor"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control required" id="tgl_akhir_tenor" error="Tanggal Akhir Tenor" placeholder="Tanggal Akhir Tenor" value="${
                                                            val[
                                                                "TanggalAkhirTenor"
                                                            ]
                                                                ? val[
                                                                      "TanggalAkhirTenor"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="type_produk" error="Type Produk" placeholder="Type Produk" value="${
                                                            val["TypeProduk"]
                                                                ? val[
                                                                      "TypeProduk"
                                                                  ]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="cab" error="Cab" placeholder="Cab" value="${
                                                            val["Cab"]
                                                                ? val["Cab"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="rep" error="Rep" placeholder="Rep" value="${
                                                            val["Rep"]
                                                                ? val["Rep"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control required" id="kondisi" error="Kondisi" placeholder="Kondisi" value="${
                                                            val["Kondisi"]
                                                                ? val["Kondisi"]
                                                                : "-"
                                                        }">
                                                    </td>
                                                    <td>
                                                    <button type="button" onclick="RequestCertificate.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>
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

        dataJson.forEach((person) => {
            const key = `${person.NamaDebitur}-${person.Alamat}`; // Gabungkan nama dan alamat sebagai kunci unik

            if (!seen.has(key)) {
                seen.add(key);
                processedData.push(person);
            } else {
                // console.log( person);
                message.showDialog(
                    `<b>Ada data dengan nama dan alamat yang sama ditemukan dan diambil 1 data saja : </b> <br>
            ${person.NamaDebitur} - ${person.Alamat}
            `
                );
            }
        });

        return processedData;
    },

    DownloadTemplate: (fileName, filePath) => {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda akan mengunuduh data template file ${fileName}.xlxs ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Download!",
        }).then((result) => {
            if (result.value) {
                let url_path = `${filePath}`;
                window.location.href = url.base_url(url_path);
            }
        });
    },
    index: 0,
    addItemWarkah: (elm, e) => {
        let table = $("#table-warkah").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.find("textarea").val("");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="RequestCertificate.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },
    addFile: (elm, id_item) => {
        // Buat uploader secara dinamis
        var uploader = $(
            `<input type="file" id="${id_item}" accept="image/*,application/pdf" />`
        );
        var src_foto = $(`#${id_item}`);
        uploader.click();

        // Ketika ada perubahan (file terpilih)
        uploader.on("change", function () {
            var files = uploader.get(0).files[0];

            if (files) {
                var maxSize = 200 * 1024; // Maksimum ukuran file 200KB

                if (files.size > maxSize) {
                    // Jika ukuran file lebih besar dari batas maksimal
                    message.sweetError("Gagal", "Ukuran file maksimal 200KB");
                    uploader.remove();
                    return;
                }

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

    saveWarkah: (id_item) => {
        let params = RequestCertificate.getPostdataWarkah(id_item);
        // return console.log(params);

        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(RequestCertificate.moduleApi()) + "submitWarkah",
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
    },

    updateStatusKontrak: (id_kontrak) => {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda akan mengkonfirmasi data kontrak ini ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Konfirmasi!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url:
                        url.base_url(RequestCertificate.moduleApi()) +
                        "updateStatusKontrak",
                    data: {
                        id_kontrak: id_kontrak,
                    },
                    dataType: "json",
                    beforeSend: () => {
                        message.loadingProses("Proses Pengambilan Data...");
                    },

                    error: function () {
                        message.closeLoading();
                        message.sweetError("Informasi", "Gagal");
                    },

                    success: function (resp) {
                        if (resp.is_valid) {
                            message.closeLoading();
                            message.sweetSuccess("Informasi", resp.message);
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

    getPostdataWarkah: (id_item) => {
        let data = {
            id: $(`input#id_item${id_item}`).val(),
            perjanjian_file_name: $(`input#perjanjian_${id_item}`).val(),
            perjanjian_file: $(`input#perjanjian_${id_item}`).attr("src"),
            perjanjian_tipe: $(`input#perjanjian_${id_item}`).attr("tipe"),

            skmjf_file_name: $(`input#skmjf_${id_item}`).val(),
            skmjf_file: $(`input#skmjf_${id_item}`).attr("src"),
            skmjf_tipe: $(`input#skmjf_${id_item}`).attr("tipe"),

            data_kendaraan_file_name: $(
                `input#data_kendaraan_${id_item}`
            ).val(),
            data_kendaraan_file: $(`input#data_kendaraan_${id_item}`).attr(
                "src"
            ),
            data_kendaraan_tipe: $(`input#data_kendaraan_${id_item}`).attr(
                "tipe"
            ),

            kk_file_name: $(`input#kk_${id_item}`).val(),
            kk_file: $(`input#kk_${id_item}`).attr("src"),
            kk_tipe: $(`input#kk_${id_item}`).attr("tipe"),

            ktp_bpkb_file_name: $(`input#ktp_bpkb_${id_item}`).val(),
            ktp_bpkb_file: $(`input#ktp_bpkb_${id_item}`).attr("src"),
            ktp_bpkb_tipe: $(`input#ktp_bpkb_${id_item}`).attr("tipe"),

            ktp_debitur_file_name: $(`input#ktp_debitur_${id_item}`).val(),
            ktp_debitur_file: $(`input#ktp_debitur_${id_item}`).attr("src"),
            ktp_debitur_tipe: $(`input#ktp_debitur_${id_item}`).attr("tipe"),

            ktp_pasangan_nama_bpkp_file_name: $(
                `input#ktp_pasangan_nama_bpkp_${id_item}`
            ).val(),
            ktp_pasangan_nama_bpkp_file: $(
                `input#ktp_pasangan_nama_bpkp_${id_item}`
            ).attr("src"),
            ktp_pasangan_nama_bpkp_tipe: $(
                `input#ktp_pasangan_nama_bpkp_${id_item}`
            ).attr("tipe"),

            ktp_pasangan_debitur_file_name: $(
                `input#ktp_pasangan_debitur_${id_item}`
            ).val(),
            ktp_pasangan_debitur_file: $(
                `input#ktp_pasangan_debitur_${id_item}`
            ).attr("src"),
            ktp_pasangan_debitur_tipe: $(
                `input#ktp_pasangan_debitur_${id_item}`
            ).attr("tipe"),

            form_perjanjian_nama_bpkb_file_name: $(
                `input#form_perjanjian_nama_bpkb_${id_item}`
            ).val(),
            form_perjanjian_nama_bpkb_file: $(
                `input#form_perjanjian_nama_bpkb_${id_item}`
            ).attr("src"),
            form_perjanjian_nama_bpkb_tipe: $(
                `input#form_perjanjian_nama_bpkb_${id_item}`
            ).attr("tipe"),
        };
        return data;
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
        let params = {};
        params.no_request = $(`#no_request`).val();
        $.ajax({
            type: "post",
            url: url.base_url(RequestCertificate.moduleApi()) + "export",
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
                        RequestCertificate.exportXlsXFile(
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
                    url: url.base_url(RequestCertificate.moduleApi()) + "downloadAll",
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
    RequestCertificate.setSelect2();
    RequestCertificate.setDate();
    RequestCertificate.getData();

    // datatable
    $("#detail_contract").DataTable();
});
