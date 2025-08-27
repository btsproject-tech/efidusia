let ShippingExecution = {
    module: () => {
        return "transaksi/shipping_execution";
    },

    moduleApi: () => {
        return "api/" + ShippingExecution.module();
    },

    moduleApiShipping: () => {
        return "api/transaksi/shipping_instruction";
    },

    moduleApiCost: () => {
        return "api/transaksi/cost";
    },

    moduleApiAirline: () => {
        return "api/master/linier_airline";
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
        window.location.href = url.base_url(ShippingExecution.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(ShippingExecution.module()) + "add";
    },

    getPostQuantity: () => {
        let data = [];
        let table = $("table#quantity_x_type").find("tbody").find("tr");
        $.each(table, function () {
            let params = {};
            params.size = $(this).find("td#size").attr("term_id");
            params.remarks = $(this).find("input#size").val();
            data.push(params);
        });
        return data;
    },

    getPostContainer: () => {
        let data = [];
        let table = $("#table-container").find("tbody").find("tr.input");
        $.each(table, function () {
            let params = {};
            params.container_no = $(this).find("#container_no").val();
            params.container_size = $(this).find("#container_size").val();
            params.container_type = $(this).find("#container_type").val();
            params.commodity = $(this).find("#commodity").val();
            data.push(params);
        });
        return data;
    },

    getPostGoods: () => {
        let data = [];
        let table = $("#table-good").find("tbody").find("tr.input");
        $.each(table, function () {
            let params = {};
            params.kind_of_package = $(this).find("#kind_of_package").val();
            params.description = $(this).find("#description").val();
            params.mark_number = $(this).find("#mark_number").val();
            params.container_number = $(this).find("#container_number").val();
            params.seal_no = $(this).find("#seal_no").val();
            params.gross_weight = $(this).find("#gross_weight").val();
            params.measurement = $(this).find("#measurement").val();
            // params.place_issue = $(this).find('#place_issue').val();
            // params.total_number = $(this).find('#total_number').val();
            // params.onboard_date = $(this).find('#onboard_date').val();
            // params.date_issue = $(this).find('#date_issue').val();
            data.push(params);
        });
        return data;
    },

    getPostGoodsManifest: (elm) => {
        let data = [];
        let table = $(elm)
            .find("#table-good-manifest")
            .find("tbody")
            .find("tr.input");
        $.each(table, function () {
            let params = {};
            params.mark_no = $(this).find("#mark_no").val();
            params.description = $(this).find("#description").val();
            params.qty = $(this).find("#qty").val();
            params.weight = $(this).find("#weight").val();
            params.volume = $(this).find("#volume").val();
            data.push(params);
        });
        return data;
    },

    getPostCost: () => {
        let data = [];
        let table = $("#table-cost").find("tbody").find("tr.input");
        $.each(table, function () {
            let params = {};
            params.activity = $(this).find("#activity").val();
            params.qty = $(this).find("#qty").val();
            params.rate = $(this).find("#rate").val();
            params.sub_total = $(this).find("#sub_total").val();
            params.vendor = $(this).find("#vendor").attr("data_id");
            params.invoice_number = $(this).find("#invoice_number").val();
            data.push(params);
        });
        return data;
    },

    getPostRevenue: () => {
        let data = [];
        let table = $("#table-revenue").find("tbody").find("tr.input");
        $.each(table, function () {
            let params = {};
            params.activity = $(this).find("#activity").val();
            params.qty = $(this).find("#qty").val();
            params.rate = $(this).find("#rate").val();
            params.sub_total = $(this).find("#sub_total").val();
            params.quotation = $(this).find("#quotation").attr("data_id");
            params.status = $(this).find("#check_status").is(":checked") ?
                1 :
                0;
            data.push(params);
        });
        return data;
    },

    getPostInputManifest: () => {
        let data = [];
        let manif = $("div.inputform");
        $.each(manif, function () {
            let index = $(this).attr('index');
            let params = {
                quotation: $(this)
                    .find("#shipping_instruction")
                    .attr("quotation"),
                truck_no: $(this).find("#truck_no").val(),
                origin: tinymce.get("origin-" + index).getContent(),
                destinations: tinymce.get("destinations-" + index).getContent(),
                delivery_by: $(this).find("#delivery_by").val(),
                date: $(this).find("#date").val(),
                data_good: ShippingExecution.getPostGoodsManifest($(this)),
            };
            data.push(params);
        });

        return data;
    },

    getPostInput: () => {
        let data = {
            id: $("input#id").val(),
            shipping_instruction: $("#shipping_instruction").attr("data_id"),
            quotation: $("#shipping_instruction").attr("quotation"),
            shipper: tinymce.get("shipper").getContent(),
            consigne: tinymce.get("consigne").getContent(),
            notify_party: tinymce.get("notify_party").getContent(),
            etd: $("#etd").val(),
            eta: $("#eta").val(),
            payment_term: $("#payment_term").val(),
            vessel_voyage: $("#vessel_voyage").val(),
            booking_no: $("#booking_no").val(),
            bl_no: $("#bl_no").val(),
            hbl_no: $("#hbl_no").val(),
            bl_type: $("#bl_type").val(),
            place_issue: $("#place_issue").val(),
            total_number: $("#total_number").val(),
            onboard_date: $("#onboard_date").val(),
            date_issue: $("#date_issue").val(),
            data_container: ShippingExecution.getPostContainer(),
            data_good: ShippingExecution.getPostGoods(),
            manifest: ShippingExecution.getPostInputManifest(),
            cost: {
                data_cost: ShippingExecution.getPostCost(),
                data_revenue: ShippingExecution.getPostRevenue(),
            },
            review: {
                data_export: {
                    book_linier: $("#book_linier").is(":checked") ? 1 : 0,
                    sc: $("#sc").is(":checked") ? 1 : 0,
                    dapat_do: $("#dapat_do").is(":checked") ? 1 : 0,
                    dp_shipper: $("#dp_shipper").is(":checked") ? 1 : 0,
                    book_truck: $("#book_truck").is(":checked") ? 1 : 0,
                    stuffing_kapan: $("#stuffing_kapan").is(":checked") ? 1 : 0,
                    peb_shipper: $("#peb_shipper").is(":checked") ? 1 : 0,
                    peb_ok: $("#peb_ok").is(":checked") ? 1 : 0,
                    bl_gm: $("#bl_gm").is(":checked") ? 1 : 0,
                    bl_ok: $("#bl_ok").is(":checked") ? 1 : 0,
                    local_charge: $("#local_charge").is(":checked") ? 1 : 0,
                    ambil_bl: $("#ambil_bl").is(":checked") ? 1 : 0,
                    sudah_invoice: $("#sudah_invoice").is(":checked") ? 1 : 0,
                    dapat_sj: $("#dapat_sj").is(":checked") ? 1 : 0,
                    invoice_vendor: $("#invoice_vendor").is(":checked") ? 1 : 0,
                    doc_shipper: $("#doc_shipper").is(":checked") ? 1 : 0,
                    remarks_linier: $("#remarks_linier").val(),
                    remarks_sc: $("#remarks_sc").val(),
                    remarks_do: $("#remarks_do").val(),
                    remarks_doship: $("#remarks_doship").val(),
                    remarks_vdntruck: $("#remarks_vdntruck").val(),
                    remarks_stuff: $("#remarks_stuff").val(),
                    remarks_peb: $("#remarks_peb").val(),
                    remarks_pebok: $("#remarks_pebok").val(),
                    remarks_bl: $("#remarks_bl").val(),
                    remarks_blok: $("#remarks_blok").val(),
                    remarks_bllinier: $("#remarks_bllinier").val(),
                    remarks_invoicing: $("#remarks_invoicing").val(),
                    remarks_sj: $("#remarks_sj").val(),
                    remarks_vendor: $("#remarks_vendor").val(),
                    remarks_dokumen: $("#remarks_dokumen").val(),
                },
                data_import: {
                    hbl_ok: $("#hbl_ok").is(":checked") ? 1 : 0,
                    mbl_agent: $("#mbl_agent").is(":checked") ? 1 : 0,
                    mbl_ok: $("#mbl_ok").is(":checked") ? 1 : 0,
                    manifest: $("#manifest").is(":checked") ? 1 : 0,
                    manifest_ok: $("#manifest_ok").is(":checked") ? 1 : 0,
                    manifest_submit: $("#manifest_submit").is(":checked") ?
                        1 : 0,
                    manifest_bukti: $("#manifest_bukti").is(":checked") ? 1 : 0,
                    invoice_copy: $("#invoice_copy").is(":checked") ? 1 : 0,
                    draft_sk: $("#draft_sk").is(":checked") ? 1 : 0,
                    lumi_linier: $("#lumi_linier").is(":checked") ? 1 : 0,
                    noa: $("#noa").is(":checked") ? 1 : 0,
                    manifest_kirim: $("#manifest_kirim").is(":checked") ? 1 : 0,
                    recon: $("#recon").is(":checked") ? 1 : 0,
                    noa_kirim: $("#noa_kirim").is(":checked") ? 1 : 0,
                    invoice_do: $("#invoice_do").is(":checked") ? 1 : 0,
                    slip_tt: $("#slip_tt").is(":checked") ? 1 : 0,
                    sk_edo: $("#sk_edo").is(":checked") ? 1 : 0,
                    edo_ready: $("#edo_ready").is(":checked") ? 1 : 0,
                    vendor_truck: $("#vendor_truck").is(":checked") ? 1 : 0,
                    pib_cnee: $("#pib_cnee").is(":checked") ? 1 : 0,
                    invoice_ppjk: $("#invoice_ppjk").is(":checked") ? 1 : 0,
                    sk_cnee_ada: $("#sk_cnee_ada").is(":checked") ? 1 : 0,
                    pib_ok: $("#pib_ok").is(":checked") ? 1 : 0,
                    sppb: $("#sppb").is(":checked") ? 1 : 0,
                    inv_consigne: $("#inv_consigne").is(":checked") ? 1 : 0,
                    tgl_resi: $("#tgl_resi").is(":checked") ? 1 : 0,
                    pendok: $("#pendok").is(":checked") ? 1 : 0,
                    remarksim_hbl: $("#remarksim_hbl").val(),
                    remarksim_mbl: $("#remarksim_mbl").val(),
                    remarksim_dragent: $("#remarksim_dragent").val(),
                    remarksim_manifest: $("#remarksim_manifest").val(),
                    remarksim_manifestok: $("#remarksim_manifestok").val(),
                    remarksim_manifestsub: $("#remarksim_manifestsub").val(),
                    remarksim_buktimanifest: $(
                        "#remarksim_buktimanifest"
                    ).val(),
                    remarksim_cnee: $("#remarksim_cnee").val(),
                    remarksim_skcnee: $("#remarksim_skcnee").val(),
                    remarksim_sklumi: $("#remarksim_sklumi").val(),
                    remarksim_noa: $("#remarksim_noa").val(),
                    remarksim_linier: $("#remarksim_linier").val(),
                    remarksim_recon: $("#remarksim_recon").val(),
                    remarksim_nocnee: $("#remarksim_nocnee").val(),
                    remarksim_dolinier: $("#remarksim_dolinier").val(),
                    remarksim_slip: $("#remarksim_slip").val(),
                    remarksim_edo: $("#remarksim_edo").val(),
                    remarksim_edoready: $("#remarksim_edoready").val(),
                    remarksim_vendortruck: $("#remarksim_vendortruck").val(),
                    remarksim_pib: $("#remarksim_pib").val(),
                    remarksim_ppjk: $("#remarksim_ppjk").val(),
                    remarksim_dokumen: $("#remarksim_dokumen").val(),
                    remarksim_pibok: $("#remarksim_pibok").val(),
                    remarksim_sppb: $("#remarksim_sppb").val(),
                    remarksim_consigne: $("#remarksim_consigne").val(),
                    remarksim_tglresi: $("#remarksim_tglresi").val(),
                    remarksim_sipintar: $("#remarksim_sipintar").val(),
                },
            },
        };

        return data;
    },

    getPostItem: () => {
        let data = [];
        let table = $("#table-rate").find("tbody").find("tr.input");
        $.each(table, function () {
            let params = {};
            params.subject = $(this).find("#subject").val();
            params.currency = $(this).find("#currency").val();
            params.rate = $(this).find("#rate").val();
            params.unit = $(this).find("#unit").val();
            params.qty = $(this).find("#qty").val();
            params.remarks = $(this).find("#remarks").val();
            data.push(params);
        });
        return data;
    },

    submit: (elm, e) => {
        e.preventDefault();
        let form = $("#form-execution");
        if (validation.runWithElement(form)) {
            let params = ShippingExecution.getPostInput();
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(ShippingExecution.moduleApi()) + "submit",
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
                        let tablist = $("div.steps").find("ul");
                        let counter = 0;
                        let indexTabList = 0;
                        $.each(tablist.find("li"), function () {
                            if ($(this).hasClass("current")) {
                                indexTabList = counter;
                            }
                            counter += 1;
                        });
                        setTimeout(function () {
                            // window.location.reload();
                            resp.indextab = indexTabList;
                            ShippingExecution.ubah(resp);
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

    ubah: (resp) => {
        window.location.href =
            url.base_url(ShippingExecution.module()) +
            `ubah?id=${resp.id}&tab=${resp.indextab}`;
    },

    getUrlParams: () => {
        let url = window.location.href;
        var params = {};
        (url + "?")
        .split("?")[1]
            .split("&")
            .forEach(function (pair) {
                pair = (pair + "=").split("=").map(decodeURIComponent);
                if (pair[0].length) {
                    params[pair[0]] = pair[1];
                }
            });

        return params;
    },

    getData: async () => {
        let tableData = $("table#table-data");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();

        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            order: [
                [0, "asc"]
            ],
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
                url: url.base_url(ShippingExecution.moduleApi()) + `getData`,
                type: "POST",
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            columns: [{
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "tgl_job",
                },
                {
                    data: "job_number",
                    render: function (data, type, row) {
                        return `<a href='${url.base_url(
                            ShippingExecution.module()
                        )}ubah?id=${row.id}' data_id="${
                            row.id
                        }">${data}</a>`;
                    }
                },
                {
                    data: "no_quotation",
                },
                {
                    data: "nama_customer",
                },
                {
                    data: "ori_port",
                },
                {
                    data: "dst_port",
                },
                // {
                //     data: "status",
                //     render: function (data, type, row) {
                //         var html = `<span class="badge bg-warning" style="font-size:12px;">${data}</span>`;
                //         if (data == "CONFIRMED") {
                //             html = `<span class="badge bg-success" style="font-size:12px;">${data}</span>`;
                //         }
                //         return html;
                //     },
                // },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = `<button type="button" data_id="${row.id}" onclick="ShippingExecution.duplicate(this, event)" class="btn btn-warning editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-duplicate"></i></button>&nbsp;`;
                        if (updateAction == 1) {
                            html += `<a href='${url.base_url(
                                ShippingExecution.module()
                            )}detail?id=${data}' data_id="${
                                row.id
                            }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                            if (row.status == "CREATED") {
                                html += `<a href='${url.base_url(
                                    ShippingExecution.module()
                                )}ubah?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            if (row.status != "CONFIRMED") {
                                html += `<button type="button" data_id="${row.id}" onclick="ShippingExecution.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                            }
                        }
                        return html;
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
        params.tgl_awal = $('#tgl_awal').val();
        params.tgl_akhir = $('#tgl_akhir').val();

        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            destroy: true,
            order: [
                [0, "asc"]
            ],
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
                url: url.base_url(ShippingExecution.moduleApi()) + `getData`,
                type: "POST",
                data: params,
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            columns: [{
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "tgl_job",
                },
                {
                    data: "job_number",
                    render: function (data, type, row) {
                        return `<a href='${url.base_url(
                            ShippingExecution.module()
                        )}ubah?id=${row.id}' data_id="${
                            row.id
                        }">${data}</a>`;
                    }
                },
                {
                    data: "no_quotation",
                },
                {
                    data: "nama_customer",
                },
                {
                    data: "ori_port",
                },
                {
                    data: "dst_port",
                },
                // {
                //     data: "status",
                //     render: function (data, type, row) {
                //         var html = `<span class="badge bg-warning" style="font-size:12px;">${data}</span>`;
                //         if (data == "CONFIRMED") {
                //             html = `<span class="badge bg-success" style="font-size:12px;">${data}</span>`;
                //         }
                //         return html;
                //     },
                // },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = `<button type="button" data_id="${row.id}" onclick="ShippingExecution.duplicate(this, event)" class="btn btn-warning editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-duplicate"></i></button>&nbsp;`;
                        if (updateAction == 1) {
                            html += `<a href='${url.base_url(
                                ShippingExecution.module()
                            )}detail?id=${data}' data_id="${
                                row.id
                            }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                            if (row.status == "CREATED") {
                                html += `<a href='${url.base_url(
                                    ShippingExecution.module()
                                )}ubah?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            if (row.status != "CONFIRMED") {
                                html += `<button type="button" data_id="${row.id}" onclick="ShippingExecution.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                            }
                        }
                        return html;
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
            url: url.base_url(ShippingExecution.moduleApi()) + "delete",
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
            url: url.base_url(ShippingExecution.moduleApi()) + "confirmDelete",
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

    selectPic: (elm, e) => {
        e.preventDefault();
        let pic = $("#customer");
        let picSelected = pic.val();
        let email = pic.find(`option[value="${picSelected}"]`).attr("email");
        let contact = pic
            .find(`option[value="${picSelected}"]`)
            .attr("contact");
        let picVal = pic.find(`option[value="${picSelected}"]`).attr("pic");
        $("#email").val(email);
        $("#contact").val(contact);
        $("#pic").val(picVal);
    },

    getPicAgen: (elm, e) => {
        let pic = $("#overseas_agen");
        let picSelected = pic.val();
        let email = pic.find(`option[value="${picSelected}"]`).attr("email");
        let contact = pic
            .find(`option[value="${picSelected}"]`)
            .attr("contact");
        let picVal = pic.find(`option[value="${picSelected}"]`).attr("pic");
        $("#pic_agen").val(picVal);
    },

    selectPicLumiship: (elm, e) => {
        e.preventDefault();
        let pic = $("#pic_lumiship");
        let picSelected = pic.val();
        let email = pic.find(`option[value="${picSelected}"]`).attr("email");
        let contact = pic
            .find(`option[value="${picSelected}"]`)
            .attr("contact");
        $("#email_karyawan").val(email);
        $("#contact_karyawan").val(contact);
    },

    showDataQuotation: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(ShippingExecution.moduleApi()) +
                "showDataQuotation",

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
                $("#btn-show-modal").trigger("click");
                ShippingExecution.getDataQuotation(type);
            },
        });
    },

    showDataShippingLine: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(ShippingExecution.moduleApi()) +
                "showDataShippingLine",

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
                $("#btn-show-modal-airline").trigger("click");
                ShippingExecution.getDataAirline(type);
            },
        });
    },

    getDataAirline: (type) => {
        let tipePort = type;
        let tableData = $("table#table-data-airline");
        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            order: [
                [0, "asc"]
            ],
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
                url: url.base_url(ShippingExecution.moduleApiAirline()) +
                    `getData`,
                type: "POST",
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            columns: [{
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "nama_agen",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        onclick="ShippingExecution.pilihDataAirline(this, event)"
                        data_id="${row.id}"
                        nama_agen="${row.nama_agen}"
                        class="btn btn-info editable-submit btn-sm waves-effect waves-light">
                        <i class="bx bx-edit"></i>
                        </a>&nbsp;`;
                        return html;
                    },
                },
            ],
        });
    },

    getDataQuotation: (type) => {
        let tipePort = type;
        let tableData = $("table#table-data-quotation");
        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            order: [
                [0, "asc"]
            ],
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
                url: url.base_url(ShippingExecution.moduleApiShipping()) +
                    `getDataShippingConfirm`,
                type: "POST",
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            columns: [{
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "no_quotation",
                },
                {
                    data: "no_shipping",
                },
                {
                    data: "jenis_shipment",
                },
                {
                    data: "nama_customer",
                },
                {
                    data: "origin_port",
                },
                {
                    data: "dst_port",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        port="${row.port}"
                        code="${row.no_quotation}"
                        no_shipping="${row.no_shipping}"
                        tipe_shipment="${row.jenis_shipment}"
                        customer="${row.nama_customer}"
                        pic_customer="${row.pic_customer}"
                        email_customer="${row.email_customer}"
                        contact_customer="${row.contact_customer}"
                        salesman="${row.salesman}"
                        delivery_type="${row.delivery_type}"
                        onclick="ShippingExecution.pilihData(this, event)"
                        data_id="${row.id}"
                        quotation="${row.quotation}"
                        quotation_date="${row.tgl_quotation}"
                        origin_port="${row.origin_port}"
                        dst_port="${row.dst_port}"
                        incoterm="${row.incoterm}"
                        shipping_line="${row.shipping_line}"
                        class="btn btn-info editable-submit btn-sm waves-effect waves-light">
                        <i class="bx bx-edit"></i>
                        </a>&nbsp;`;
                        return html;
                    },
                },
            ],
        });
    },

    back: (elm) => {
        window.location.href = url.base_url(ShippingExecution.module()) + "/";
    },

    pilihData: (elm, e) => {
        e.preventDefault();
        let delivery_type = $(elm).attr("delivery_type");
        let id = $(elm).attr("data_id");
        let no_shipping = $(elm).attr("no_shipping");
        let origin_port = $(elm).attr("origin_port");
        let dst_port = $(elm).attr("dst_port");
        let incoterm = $(elm).attr("incoterm");
        let shipping_line = $(elm).attr("shipping_line");
        let billing_party = $(elm).attr("customer");
        let quotation = $(elm).attr("quotation");
        // console.log('billing_party', billing_party);
        $("#shipping_instruction").attr("data_id", id);
        $("#shipping_instruction").attr("quotation", quotation);
        $("#shipping_instruction").val(no_shipping);
        $("#delivery_type").val(delivery_type);
        $("#origin").val(origin_port);
        $("#destinations").val(dst_port);
        $("#incoterm").val(incoterm);
        $("#shipping_line").val(shipping_line);
        $("#billing_party").val(billing_party);
        $("button.btn-close").trigger("click");

        ShippingExecution.getListRateQuotation(quotation);
    },

    getListRateQuotation: (quotation) => {
        let params = {};
        params.quotation = quotation;

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(ShippingExecution.moduleApi()) +
                "getListRateQuotation",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },

            success: function (resp) {
                message.closeLoading();
                $("#table-revenue").find("tbody").html(resp);
            },
        });
    },

    pilihDataAirline: (elm, e) => {
        e.preventDefault();
        let nama_agen = $(elm).attr("nama_agen");
        let id = $(elm).attr("data_id");
        $("#shipping_line").val(nama_agen);
        $("#shipping_line").attr("data_id", id);
        $("button.btn-close").trigger("click");
    },

    setDate: () => {
        let dataDate = $(".data-date");
        $.each(dataDate, function () {
            // console.log($(this));
            $(this).datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                autoclose: true,
                // startDate: new Date(),
            });
        });
    },

    printManifest: (elm, e) => {
        e.preventDefault();
        let url = $(elm).attr("href");
        window.open(url);
    },

    printCost: (elm, e) => {
        e.preventDefault();
        let url = $(elm).attr("href");
        window.open(url);
    },

    detail: (elm, e) => {
        e.preventDefault();
        let url = $(elm).attr("href");
        window.open(url);
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
            url: url.base_url(ShippingExecution.moduleApi()) + "confirm",
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
                        ShippingExecution.back();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", resp.message);
                }
            },
        });
    },

    addItem: (elm, e) => {
        e.preventDefault();
        let table = $("#table-container").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="ShippingExecution.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },

    addItemGood: (elm, e) => {
        e.preventDefault();
        let table = $("#table-good").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="ShippingExecution.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
        ShippingExecution.setDate();
    },

    addItemGoodManifest: (elm) => {
        let table = $("#table-good-manifest")
            .find("tbody")
            .find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="ShippingExecution.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
        ShippingExecution.setDate();
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

    print: (elm, e) => {
        e.preventDefault();
        var element = document.getElementById("print-content");
        fileName = $(elm).attr("filename");

        let opt = {
            margin: 0.3,
            filename: `${fileName}.pdf`,
            image: {
                type: "jpeg",
                quality: 4,
            },
            html2canvas: {
                scale: 4,
                scrollX: 0,
                scrollY: 0,
                width: 1080,
            },
            jsPDF: {
                unit: "in",
                format: "A3",
                orientation: "portrait",
            },
        };

        html2pdf().set(opt).from(element).save();
    },

    printWaybill: (elm, e) => {
        e.preventDefault();

        /*PRINT GOODS */
        var element = document.getElementById("print-content-nego");
        var fileName = "WAYBILL-" + $(elm).attr("filename");
        // html2pdf(element);
        let optGoods = {
            margin: 0.3,
            filename: `${fileName}.pdf`,
            image: {
                type: "jpeg",
                quality: 4,
            },
            html2canvas: {
                scale: 4,
                scrollX: 0,
                scrollY: 0,
                width: 1080,
            },
            jsPDF: {
                unit: "in",
                format: "A3",
                orientation: "portrait",
            },
        };

        html2pdf().set(optGoods).from(element).save();
        /*PRINT GOODS */
    },

    editor: () => {
        if ($(".texteditor").length > 0) {
            tinymce.remove("textarea.texteditor");
            // tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'textarea.texteditor');
            tinymce.init({
                selector: "textarea.texteditor",
                menubar: false,
                plugins: ["lists", "code"],
                toolbar: "undo redo | blocks | " +
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

    setBarcode: () => {
        if ($("#code128-jobnumber").length > 0) {
            JsBarcode(
                "#code128-jobnumber",
                $("#code128-jobnumber").attr("job_number"), {
                    height: 70,
                    fontSize: 18
                }
            );
        }
    },

    wizard: () => {
        $("#form-execution").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slide",
            enablePagination: false,
            enableAllSteps: true,
        });
        $("#vertical-menu-btn").trigger("click");
    },

    addItemCost: (elm, e) => {
        e.preventDefault();
        let table = $("#table-cost").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="ShippingExecution.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
        ShippingExecution.setDate();
    },

    showDataVendor: (elm, type) => {
        let params = {};
        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(ShippingExecution.moduleApiCost()) +
                "showDataVendor",

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
                $("#btn-show-modal-vendor").trigger("click");
                ShippingExecution.getDataVendor(elm);
            },
        });
    },

    moduleApiVendor: () => {
        return "api/master/vendor";
    },

    getDataVendor: (elmTable) => {
        let tableData = $("table#table-data-vendor");
        let indexTr = $(elmTable).closest("tr").index();
        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            order: [
                [0, "asc"]
            ],
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
                url: url.base_url(ShippingExecution.moduleApiVendor()) +
                    `getData`,
                type: "POST",
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            columns: [{
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "nama_vendor",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        onclick="ShippingExecution.pilihDataVendor(this, event)"
                        data_id="${row.id}"
                        nama_vendor="${row.nama_vendor}"
                        indexTr="${indexTr}"
                        class="btn btn-info editable-submit btn-sm waves-effect waves-light">
                        <i class="bx bx-edit"></i>
                        </a>&nbsp;`;
                        return html;
                    },
                },
            ],
        });
    },

    pilihDataVendor: (elm, e) => {
        e.preventDefault();
        let data_id = $(elm).attr("data_id");
        let nama_vendor = $(elm).attr("nama_vendor");
        let indexTr = $(elm).attr("indexTr");

        $(`table#table-cost`)
            .find("tbody")
            .find(`tr:eq(${indexTr})`)
            .find(`#vendor`)
            .val(nama_vendor);
        $(`table#table-cost`)
            .find("tbody")
            .find(`tr:eq(${indexTr})`)
            .find(`#vendor`)
            .attr("data_id", data_id);
        $("button.btn-close").trigger("click");
    },

    hitungSubTotalCost: (elm) => {
        let tr = $(elm).closest("tr");
        let qty = tr.find("#qty").val();
        let rate = tr.find("#rate").val();
        qty = isNaN(parseFloat(qty)) ? 0 : parseFloat(qty);
        rate = isNaN(parseFloat(rate)) ? 0 : parseFloat(rate);
        let subTotal = qty * rate;
        tr.find("#sub_total").val(subTotal);
    },

    hitungSubTotalCostRev: (elm) => {
        let tr = $(elm).closest("tr");
        let qty = tr.find("#qty").val();
        let rate = tr.find("#rate").val();
        qty = isNaN(parseFloat(qty)) ? 0 : parseFloat(qty);
        rate = isNaN(parseFloat(rate)) ? 0 : parseFloat(rate);
        let subTotal = qty * rate;
        tr.find("#sub_total").val(subTotal);
    },

    addItemRevenue: (elm, e) => {
        e.preventDefault();
        let table = $("#table-revenue").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.find("input#check_status").prop("checked", false);
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="ShippingExecution.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },

    showDataQuotationCost: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(ShippingExecution.moduleApiCost()) +
                "showDataQuotation",

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
                $("#btn-show-modal").trigger("click");
                ShippingExecution.getDataQuotationCost(elm, type);
            },
        });
    },

    getDataQuotationCost: (elm, type) => {
        let tipePort = type;
        let indexTr = $(elm).closest("tr").index();
        let tableData = $("table#table-data-quotation");
        var data = tableData.DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            order: [
                [0, "asc"]
            ],
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
                url: url.base_url(ShippingExecution.moduleApi()) +
                    `getDataShippingExecutionConfirm`,
                type: "POST",
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            columns: [{
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "no_quotation",
                },
                {
                    data: "job_number",
                },
                {
                    data: "jenis_shipment",
                },
                {
                    data: "nama_customer",
                },
                {
                    data: "origin_port",
                },
                {
                    data: "dst_port",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        indexTr="${indexTr}"
                        port="${row.port}"
                        code="${row.no_quotation}"
                        no_shipping="${row.no_shipping}"
                        tipe_shipment="${row.jenis_shipment}"
                        customer="${row.nama_customer}"
                        pic_customer="${row.pic_customer}"
                        email_customer="${row.email_customer}"
                        contact_customer="${row.contact_customer}"
                        salesman="${row.salesman}"
                        delivery_type="${row.delivery_type}"
                        onclick="ShippingExecution.pilihDataQuoteCost(this, event)"
                        data_id="${row.id}"
                        se_id="${row.se_id}"
                        job_number="${row.job_number}"
                        quotation="${row.quotation}"
                        quotation_date="${row.tgl_quotation}"
                        origin_port="${row.origin_port}"
                        dst_port="${row.dst_port}"
                        incoterm="${row.incoterm}"
                        shipping_line="${row.shipping_line}"
                        class="btn btn-info editable-submit btn-sm waves-effect waves-light">
                        <i class="bx bx-edit"></i>
                        </a>&nbsp;`;
                        return html;
                    },
                },
            ],
        });
    },

    pilihDataQuoteCost: (elm, e) => {
        e.preventDefault();
        let quotation = $(elm).attr("quotation");
        let code = $(elm).attr("code");
        let indexTr = $(elm).attr("indexTr");
        console.log("indexTr " + indexTr);
        console.log(
            $(`table#table-revenue`)
            .find("tbody")
            .find(`tr:eq(${indexTr})`)
            .find("#quotation")
        );
        $(`table#table-revenue`)
            .find("tbody")
            .find(`tr:eq(${indexTr})`)
            .find(`#quotation`)
            .val(code);
        $(`table#table-revenue`)
            .find("tbody")
            .find(`tr:eq(${indexTr})`)
            .find(`#quotation`)
            .attr("data_id", quotation);
        $("button.btn-close").trigger("click");
    },

    tablistSelected: (index) => {
        let tablist = $("div.steps").find("ul");
        $(`#form-execution-t-${index}`).trigger("click");
    },

    checkTabListIndex: () => {
        let params = ShippingExecution.getUrlParams();
        if (typeof params.tab != "undefined") {
            ShippingExecution.tablistSelected(params.tab);
        }
    },

    addManifest: (elm) => {
        let row = $(elm)
            .closest("div.content-manifest")
            .find("div.inputform:last");
        let index = row.attr('index');
        let dataOriginBefore = tinymce.get("origin-" + index).getContent();
        let dataDstBefore = tinymce.get("destinations-" + index).getContent();


        index = isNaN(parseInt(index)) ? 0 : parseInt(index);
        let newIndex = index + 2;
        let newRow = row.clone();
        console.log('newIndex',newIndex);
        newRow.attr('index', newIndex);
        newRow.find("input").val("");
        newRow.find('#delivery_by').val(row.find('#delivery_by').val());
        newRow.find('div.accordion').attr('id','manifest-accrodion-' + newIndex);
        newRow.find('h2.accordion-header').attr('id','headingOne-' + newIndex);
        newRow.find('h2.accordion-header').html(`<button class="accordion-button fw-medium"
        type="button" data-bs-toggle="collapse"
        data-bs-target="#collapseOne-${newIndex}" aria-expanded="true"
        aria-controls="collapseOne-${newIndex}">
        Manifest #${newIndex}
        </button>`);
        newRow.find('div.accordion-collapse').attr('id','collapseOne-' + newIndex);
        newRow.find('div.accordion-collapse').attr('aria-labelledby','headingOne-' + newIndex);
        newRow.find('div.accordion-collapse').attr('data-bs-parent','#manifest-accrodion-' + newIndex);
        newRow.find('div.accordion-collapse').find('div.accordion-body').find('div.row').attr('index', newIndex);

        newRow.find(".content-origin")
            .html(`<label for="origin" class="col-md-2 col-form-label">Origin</label>
        <div class="col-md-10">
            <textarea name="" id="origin-${newIndex}" class="form-control texteditor" error="Origin">${dataOriginBefore}</textarea>
        </div>`);
        newRow.find(".content-destinations")
            .html(`<label for="destinations" class="col-md-2 col-form-label">Destination</label>
        <div class="col-md-10">
            <textarea name="" id="destinations-${newIndex}" class="form-control texteditor" error="Destinations">${dataDstBefore}</textarea>
        </div>`);

        let actionDelete = `<div class="col-md-12 text-end">
        <a href=""
            onclick="ShippingExecution.deleteManifest(this, event)" class="btn btn-danger waves-effect waves-light me-1"><i
            class="fa fa-trash"></i></a>
    </div><br/>`;
        newRow.find(".content-manifest-action").html(actionDelete);
        row.after(newRow);

        ShippingExecution.editor();
        ShippingExecution.setDate();
    },

    deleteManifest: (elm, e) => {
        e.preventDefault();
        $(elm).closest("div.inputform").remove();
    },

    duplicate:(elm, e)=>{
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr('data_id');

        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(ShippingExecution.moduleApi()) + "duplicate",
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
    }
};

$(function () {
    ShippingExecution.wizard();
    ShippingExecution.setSelect2();
    ShippingExecution.setDate();
    ShippingExecution.getData();
    ShippingExecution.editor();
    ShippingExecution.setBarcode();
    ShippingExecution.checkTabListIndex();
});
