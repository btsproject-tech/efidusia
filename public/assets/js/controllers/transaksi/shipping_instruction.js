let ShippingInstruction = {
    module: () => {
        return "transaksi/shipping_instruction";
    },

    moduleApi: () => {
        return "api/" + ShippingInstruction.module();
    },

    moduleApiQuote: () => {
        return "api/transaksi/quotation";
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
        window.location.href = url.base_url(ShippingInstruction.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href =
            url.base_url(ShippingInstruction.module()) + "add";
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

    getPostInput: () => {
        let data = {
            id: $("input#id").val(),
            quotation: $("#quotation").attr("data_id"),
            instruction_date: $("#instruction_date").val(),
            shipper: $("#shipper").val(),
            consigne: $("#consigne").val(),
            tipe_shipment: $("div.content-quantity-x-type").attr(
                "tipe_shipment"
            ),
            notify_party: $("#notify_party").val(),
            data_quantity: ShippingInstruction.getPostQuantity(),
            freight: $("#freight").is(':checked') ? 1 : 0,
            emkl: $("#emkl").is(':checked') ? 1 : 0,
            overseas_emkl: $("#overseas_emkl").is(':checked') ? 1 : 0,
            overseas_agen: $("#overseas_agen").val(),
            pic_agen: $("#pic_agen").val(),
            pickup_address: $("#pickup_address").val(),
            delivery_address: $("#delivery_address").val(),
            shipping_line: $("#shipping_line").attr('data_id'),
            booking_via: $("#booking_via").val(),
            no_service_contract: $("#no_service_contract").val(),
            vessel_voyage: $("#vessel_voyage").val(),
            etd: $("#etd").val(),
            eta: $("#eta").val(),
            stuffing_date: $("#stuffing_date").val(),
            name_of_goods: $("#name_of_goods").val(),
            gross_weight: $("#gross_weight").val(),
            volume: $("#volume").val(),
            dimension: $("#dimension").val(),
            number_of_bl: $("#number_of_bl").val(),
            term_of_payment: $("#term_of_payment").val(),
            roe: $("#roe").val(),
            salesman: $("#salesman").val(),
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
            let params = ShippingInstruction.getPostInput();
            // if (params.data_item.length == 0) {
            //     message.sweetError("Informasi", "Table Rate Harus Diisi");
            //     return;
            // }
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(ShippingInstruction.moduleApi()) + "submit",
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
                            ShippingInstruction.back();
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
                url: url.base_url(ShippingInstruction.moduleApi()) + `getData`,
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
                    data: "no_shipping",
                },
                {
                    data: "instruction_date",
                },
                {
                    data: "no_quotation",
                    render: function (data, type, row) {
                        if(data == null){
                            return '';
                        }
                        return `<a href='${url.base_url(
                            ShippingInstruction.module()
                        )}detail?id=${row.id}' data_id="${
                            row.id
                        }">${data}</a>`;
                    }
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
                        var html = `<button type="button" data_id="${row.id}" onclick="ShippingInstruction.duplicate(this, event)" class="btn btn-warning editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-duplicate"></i></button>&nbsp;`;
                        if (updateAction == 1) {
                            html += `<a href='${url.base_url(
                                ShippingInstruction.module()
                            )}detail?id=${data}' data_id="${
                                row.id
                            }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                            if (row.status == "CREATED") {
                                html += `<a href='${url.base_url(
                                    ShippingInstruction.module()
                                )}ubah?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            if(row.status != 'CONFIRMED'){
                                html += `<button type="button" data_id="${row.id}" onclick="ShippingInstruction.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
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
                url: url.base_url(ShippingInstruction.moduleApi()) + `getData`,
                type: "POST",
                data: params
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
                    data: "no_shipping",
                },
                {
                    data: "instruction_date",
                },
                {
                    data: "no_quotation",
                    render: function (data, type, row) {
                        if(data == null){
                            return '';
                        }
                        return `<a href='${url.base_url(
                            ShippingInstruction.module()
                        )}detail?id=${row.id}' data_id="${
                            row.id
                        }">${data}</a>`;
                    }
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
                        var html = `<button type="button" data_id="${row.id}" onclick="ShippingInstruction.duplicate(this, event)" class="btn btn-warning editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-duplicate"></i></button>&nbsp;`;
                        if (updateAction == 1) {
                            html += `<a href='${url.base_url(
                                ShippingInstruction.module()
                            )}detail?id=${data}' data_id="${
                                row.id
                            }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                            if (row.status == "CREATED") {
                                html += `<a href='${url.base_url(
                                    ShippingInstruction.module()
                                )}ubah?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            if(row.status != 'CONFIRMED'){
                                html += `<button type="button" data_id="${row.id}" onclick="ShippingInstruction.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
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
            url: url.base_url(ShippingInstruction.moduleApi()) + "delete",
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
                url.base_url(ShippingInstruction.moduleApi()) + "confirmDelete",
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
            url:
                url.base_url(ShippingInstruction.moduleApi()) +
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
                ShippingInstruction.getDataQuotation(type);
            },
        });
    },

    showDataShippingLine: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url:
                url.base_url(ShippingInstruction.moduleApi()) +
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
                ShippingInstruction.getDataAirline(type);
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
                url:
                    url.base_url(ShippingInstruction.moduleApiAirline()) +
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
                    data: "nama_agen",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        onclick="ShippingInstruction.pilihDataAirline(this, event)"
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
                    url.base_url(ShippingInstruction.moduleApiQuote()) +
                    `getDataQuotationConfirm`,
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
                    data: "created_at",
                    // render: function (data, type, row) {

                    // }
                },
                {
                    data: "no_quotation",
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
                    data: "jenis_shipment",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        html += `<a href=''
                        port="${row.port}"
                        code="${row.no_quotation}"
                        tipe_shipment="${row.jenis_shipment}"
                        customer="${row.nama_customer}"
                        pic_customer="${row.pic_customer}"
                        email_customer="${row.email_customer}"
                        contact_customer="${row.contact_customer}"
                        salesman="${row.salesman}"
                        onclick="ShippingInstruction.pilihData(this, event)"
                        data_id="${row.id}"
                        quotation_date="${row.tgl_quotation}"
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
        window.location.href = url.base_url(ShippingInstruction.module()) + "/";
    },

    pilihData: (elm, e) => {
        e.preventDefault();
        let quotation_date = $(elm).attr("quotation_date");
        let quotation = $(elm).attr("data_id");
        let no_quotation = $(elm).attr("code");
        let customer = $(elm).attr("customer");
        let pic_customer = $(elm).attr("pic_customer");
        let email_customer = $(elm).attr("email_customer");
        let contact_customer = $(elm).attr("contact_customer");
        let salesman = $(elm).attr("salesman");
        let tipe_shipment = $(elm).attr("tipe_shipment");
        $("#quotation_date").val(quotation_date);
        $("#quotation").attr("data_id", quotation);
        $("#quotation").val(no_quotation);
        $("#customer").val(customer);
        $("#pic").val(pic_customer);
        $("#email").val(email_customer);
        $("#contact").val(contact_customer);
        $("#salesman").val(salesman);
        $("div.content-quantity-x-type").attr("tipe_shipment", tipe_shipment);
        if (tipe_shipment == "Ocean Freight") {
            $("div.content-quantity-x-type").removeClass("hide");
        } else {
            $("div.content-quantity-x-type").addClass("hide");
        }
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
                // startDate: new Date(),
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
            url: url.base_url(ShippingInstruction.moduleApi()) + "confirm",
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
                        ShippingInstruction.back();
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
                `<button type="button" onclick="ShippingInstruction.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
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

    print: (elm, e) => {
        e.preventDefault();
        var element = document.getElementById("print-content");
        fileName = $(elm).attr("filename");
        // html2pdf(element);
        let opt = {
            margin: 0.5,
            filename: `${fileName}.pdf`,
            image: { type: "jpeg", quality: 1 },
            html2canvas: { scale: 2, scrollX: 0, scrollY: 0, width: 735},
            jsPDF: { unit: "in", format: "A4", orientation: "portrait" },
        };

        html2pdf().set(opt).from(element).save();
    },

    duplicate:(elm, e)=>{
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr('data_id');

        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(ShippingInstruction.moduleApi()) + "duplicate",
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
    ShippingInstruction.setSelect2();
    ShippingInstruction.setDate();
    ShippingInstruction.getData();
});
