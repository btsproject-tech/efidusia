let Cost = {
    module: () => {
        return "transaksi/cost";
    },

    moduleApi: () => {
        return "api/" + Cost.module();
    },

    moduleApiShipping: () => {
        return "api/transaksi/shipping_execution";
    },

    modulePort: () => {
        return "api/master/port";
    },

    moduleApiAirline: () => {
        return "api/master/linier_airline";
    },

    moduleApiVendor: () => {
        return "api/master/vendor";
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
        window.close();
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Cost.module()) + "add";
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
            data.push(params);
        });
        return data;
    },

    getPostInput: () => {
        let data = {
            id: $("input#id").val(),
            data_cost: Cost.getPostCost(),
            data_revenue: Cost.getPostRevenue(),
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
        let form = $(elm).closest("div.content-save");
        if (validation.runWithElement(form)) {
            let params = Cost.getPostInput();
            // if (params.data_item.length == 0) {
            //     message.sweetError("Informasi", "Table Rate Harus Diisi");
            //     return;
            // }
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(Cost.moduleApi()) + "submit",
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
                            Cost.back();
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
                url: url.base_url(Cost.moduleApi()) + `getData`,
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
                    data: "no_document",
                },
                {
                    data: "total_cost",
                },
                {
                    data: "status",
                    render: function (data, type, row) {
                        var html = `<span class="badge bg-warning" style="font-size:12px;">${data}</span>`;
                        if (data == "CONFIRMED") {
                            html = `<span class="badge bg-success" style="font-size:12px;">${data}</span>`;
                        }
                        return html;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        if (updateAction == 1) {
                            html += `<a href='${url.base_url(
                                Cost.module()
                            )}detail?id=${data}' data_id="${
                                row.id
                            }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                            if (row.status == "CREATED") {
                                html += `<a href='${url.base_url(
                                    Cost.module()
                                )}ubah?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            if (row.status != "CONFIRMED") {
                                html += `<button type="button" data_id="${row.id}" onclick="Cost.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
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
            url: url.base_url(Cost.moduleApi()) + "delete",
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
            url: url.base_url(Cost.moduleApi()) + "confirmDelete",
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
            url: url.base_url(Cost.moduleApi()) + "showDataQuotation",

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
                Cost.getDataQuotation(elm, type);
            },
        });
    },

    showDataVendor: (elm, type) => {
        let params = {};
        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(Cost.moduleApi()) + "showDataVendor",

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
                Cost.getDataVendor(elm);
            },
        });
    },

    showDataPortOrigin: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(Cost.moduleApi()) + "showDataPortOrigin",

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
                Cost.getDataPortOrigin(type);
            },
        });
    },

    showDataPortDst: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(Cost.moduleApi()) + "showDataPortDst",

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
                Cost.getDataPortDst(type);
            },
        });
    },

    showDataShippingLine: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(Cost.moduleApi()) + "showDataShippingLine",

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
                Cost.getDataAirline(type);
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
                url: url.base_url(Cost.moduleApiAirline()) + `getData`,
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
                    data: "nama_agen",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        onclick="Cost.pilihDataAirline(this, event)"
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

    getDataVendor: (elmTable) => {
        let tableData = $("table#table-data-vendor");
        let indexTr = $(elmTable).closest("tr").index();
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
                url: url.base_url(Cost.moduleApiVendor()) + `getData`,
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
                    data: "nama_vendor",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        onclick="Cost.pilihDataVendor(this, event)"
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

    getDataQuotation: (elm, type) => {
        let tipePort = type;
        let indexTr = $(elm).closest("tr").index();
        let tableData = $("table#table-data-quotation");
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
                    url.base_url(Cost.moduleApiShipping()) +
                    `getDataShippingExecutionConfirm`,
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
                        onclick="Cost.pilihData(this, event)"
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

    getDataPortOrigin: (type) => {
        let tipePort = type;
        let tableData = $("table#table-data-origin");
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
                url: url.base_url(Cost.modulePort()) + `getData`,
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
                    data: "country",
                },
                {
                    data: "port",
                },
                {
                    data: "code",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        country="${row.country}"
                        code="${row.code}"
                        port="${row.port}"
                        data_id="${row.id}"
                        onclick="Cost.pilihPortOrigin(this, event)"
                        class="btn btn-info editable-submit btn-sm waves-effect waves-light">
                        <i class="bx bx-edit"></i>
                        </a>&nbsp;`;
                        return html;
                    },
                },
            ],
        });
    },

    getDataPortDst: (type) => {
        let tipePort = type;
        let tableData = $("table#table-data-dst");
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
                url: url.base_url(Cost.modulePort()) + `getData`,
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
                    data: "country",
                },
                {
                    data: "port",
                },
                {
                    data: "code",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        country="${row.country}"
                        code="${row.code}"
                        port="${row.port}"
                        data_id="${row.id}"
                        onclick="Cost.pilihPortDst(this, event)"
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
        window.location.href = url.base_url(Cost.module()) + "/";
    },

    pilihData: (elm, e) => {
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

    pilihPortOrigin: (elm, e) => {
        e.preventDefault();
        let id = $(elm).attr("data_id");
        let code = $(elm).attr("code");
        let country = $(elm).attr("country");
        let port = $(elm).attr("port");
        $("#origin").attr("data_id", id);
        $("#origin").val(port + " - " + code);
        $("button.btn-close").trigger("click");
    },

    pilihPortDst: (elm, e) => {
        e.preventDefault();
        let id = $(elm).attr("data_id");
        let code = $(elm).attr("code");
        let country = $(elm).attr("country");
        let port = $(elm).attr("port");
        $("#destinations").attr("data_id", id);
        $("#destinations").val(port + " - " + code);
        $("button.btn-close").trigger("click");
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
            url: url.base_url(Cost.moduleApi()) + "confirm",
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
                        Cost.back();
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
                `<button type="button" onclick="Cost.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },

    addItemRevenue: (elm, e) => {
        e.preventDefault();
        let table = $("#table-revenue").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="Cost.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
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
                `<button type="button" onclick="Cost.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
        Cost.setDate();
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
                quality: 1,
            },
            html2canvas: {
                scale: 1,
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

        /*PRINT GOODS */
        element = document.getElementById("print-content-nego");
        fileName = "WAYBILL-" + $(elm).attr("filename");
        // html2pdf(element);
        let optGoods = {
            margin: 0.3,
            filename: `${fileName}.pdf`,
            image: {
                type: "jpeg",
                quality: 1,
            },
            html2canvas: {
                scale: 1,
                scrollX: 0,
                scrollY: 0,
                width: 750,
            },
            jsPDF: {
                unit: "in",
                format: "A4",
                orientation: "portrait",
            },
        };

        html2pdf().set(optGoods).from(element).save();
        /*PRINT GOODS */
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

    hitungTotalEstimasiGP: () => {
        let gp = $('#estimasi_gp');
        if(gp.length > 0){
            let cost = $('#total-cost').attr('total');
            cost = isNaN(cost) ? 0 : parseFloat(cost);
            let revenue = $('#total-revenue').attr('total');
            revenue = isNaN(revenue) ? 0 : parseFloat(revenue);

            let total = revenue - cost;
            total = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'USD' }).format(total);
            gp.html(total.replaceAll('$', ''));
        }
    },
};

$(function () {
    Cost.setSelect2();
    Cost.setDate();
    Cost.getData();
    Cost.hitungTotalEstimasiGP();
});
