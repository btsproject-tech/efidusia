let Manifest = {
    module: () => {
        return "transaksi/manifest";
    },
   
    moduleSe: () => {
        return "transaksi/shipping_execution";
    },

    moduleApi: () => {
        return "api/" + Manifest.module();
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

    setSelect2: () => {
        if ($(".select2").length > 0) {
            $.each($(".select2"), function () {
                $(this).select2();
            });
        }
    },

    cancel: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Manifest.module()) + "/";
    },
   
    cancelSe: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Manifest.moduleSe()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href =
            url.base_url(Manifest.module()) + "add";
    },

    getPostQuantity: () => {
        let data = [];
        let table = $("table#quantity_x_type").find("tbody").find("tr");
        $.each(table, function () {
            let params = {};
            params.size = $(this).find('td#size').attr('term_id');
            params.remarks = $(this).find('input#size').val();
            data.push(params);
        });
        return data;
    },

    getPostGoods: () => {
        let data = [];
        let table = $('#table-good').find('tbody').find('tr.input');
        $.each(table, function () {
            let params = {};
            params.mark_no = $(this).find('#mark_no').val();
            params.description = $(this).find('#description').val();
            params.qty = $(this).find('#qty').val();
            params.weight = $(this).find('#weight').val();
            params.volume = $(this).find('#volume').val();
            data.push(params);
        });
        return data;
    },

    getPostInput: () => {
        let data = {
            id: $("input#id").val(),
            shipping_execution: $("#shipping_execution").attr("data_id"),
            quotation: $("#shipping_execution").attr("quotation"),
            truck_no: $("#truck_no").val(),
            // origin: $("#origin").attr('data_id'),
            // destinations: $("#destinations").attr('data_id'),
            origin : tinymce.get("origin").getContent(),
            destinations: tinymce.get("destinations").getContent(),
            delivery_by: $("#delivery_by").val(),
            date: $("#date").val(),
            data_good: Manifest.getPostGoods(),
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
            let params = Manifest.getPostInput();
            // if (params.data_item.length == 0) {
            //     message.sweetError("Informasi", "Table Rate Harus Diisi");
            //     return;
            // }
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(Manifest.moduleApi()) + "submit",
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
                            Manifest.back();
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
                url: url.base_url(Manifest.moduleApi()) + `getData`,
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
                    data: "no_manifest",
                },
                {
                    data: "no_quotation",
                },
                {
                    data: "origin",
                },
                {
                    data: "destination",
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
                                Manifest.module()
                            )}detail?id=${data}' data_id="${
                                row.id
                            }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                            if (row.status == "CREATED") {
                                html += `<a href='${url.base_url(
                                    Manifest.module()
                                )}ubah?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            if(row.status != 'CONFIRMED'){
                                html += `<button type="button" data_id="${row.id}" onclick="Manifest.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
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
            url: url.base_url(Manifest.moduleApi()) + "delete",
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
            url: url.base_url(Manifest.moduleApi()) + "confirmDelete",
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
            url: url.base_url(Manifest.moduleApi()) +
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
                Manifest.getDataQuotation(type);
            },
        });
    },

    showDataPortOrigin: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(Manifest.moduleApi()) +
                "showDataPortOrigin",

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
                Manifest.getDataPortOrigin(type);
            },
        });
    },

    showDataPortDst: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(Manifest.moduleApi()) +
                "showDataPortDst",

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
                Manifest.getDataPortDst(type);
            },
        });
    },

    showDataShippingLine: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(Manifest.moduleApi()) +
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
                Manifest.getDataAirline(type);
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
                url: url.base_url(Manifest.moduleApiAirline()) +
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
                        onclick="Manifest.pilihDataAirline(this, event)"
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
                url: url.base_url(Manifest.moduleApiShipping()) +
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
                        onclick="Manifest.pilihData(this, event)"
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
                url: url.base_url(Manifest.modulePort()) +
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
                        onclick="Manifest.pilihPortOrigin(this, event)"
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
                url: url.base_url(Manifest.modulePort()) +
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
                        onclick="Manifest.pilihPortDst(this, event)"
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
        window.location.href = url.base_url(Manifest.module()) + "/";
    },

    pilihData: (elm, e) => {
        e.preventDefault();
        let id = $(elm).attr("se_id");
        let job_number = $(elm).attr("job_number");
        let quotation = $(elm).attr("quotation");
        $("#shipping_execution").attr('data_id', id);
        $("#shipping_execution").attr('quotation', quotation);
        $("#shipping_execution").val(job_number);
        $("button.btn-close").trigger("click");
    },

    pilihPortOrigin: (elm, e) => {
        e.preventDefault();
        let id = $(elm).attr("data_id");
        let code = $(elm).attr("code");
        let country = $(elm).attr("country");
        let port = $(elm).attr("port");
        $("#origin").attr('data_id', id);
        $("#origin").val(port+" - "+code);
        $("button.btn-close").trigger("click");
    },

    pilihPortDst: (elm, e) => {
        e.preventDefault();
        let id = $(elm).attr("data_id");
        let code = $(elm).attr("code");
        let country = $(elm).attr("country");
        let port = $(elm).attr("port");
        $("#destinations").attr('data_id', id);
        $("#destinations").val(port+" - "+code);
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
            url: url.base_url(Manifest.moduleApi()) + "confirm",
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
                        Manifest.back();
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
                `<button type="button" onclick="Manifest.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
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
                `<button type="button" onclick="Manifest.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
        Manifest.setDate();
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
                quality: 1
            },
            html2canvas: {
                scale: 4,
                scrollX: 0,
                scrollY: 0,
                width: 750
            },
            jsPDF: {
                unit: "in",
                format: "A4",
                orientation: "portrait"
            },
        };

        html2pdf().set(opt).from(element).save();

        /*PRINT GOODS */
        element = document.getElementById("print-content-nego");
        fileName = "WAYBILL-"+$(elm).attr("filename");
        // html2pdf(element);
        let optGoods = {
            margin: 0.3,
            filename: `${fileName}.pdf`,
            image: {
                type: "jpeg",
                quality: 1
            },
            html2canvas: {
                scale: 1,
                scrollX: 0,
                scrollY: 0,
                width: 750
            },
            jsPDF: {
                unit: "in",
                format: "A4",
                orientation: "portrait"
            },
        };

        html2pdf().set(optGoods).from(element).save();
        /*PRINT GOODS */
    },

    editor: () => {
        if($('.texteditor').length > 0){
            tinymce.remove('textarea.texteditor');
            // tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'textarea.texteditor');
            tinymce.init({
                selector: 'textarea.texteditor',
                menubar: false,
                plugins:["lists","code"],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat code',
            });
            document.addEventListener('focusin', function (e) {
                if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) {
                  e.stopImmediatePropagation();
                }
            });
        }
    },
};

$(function () {
    Manifest.editor();
    Manifest.setSelect2();
    Manifest.setDate();
    Manifest.getData();
});
