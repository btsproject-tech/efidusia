let SalesReport = {
    module: () => {
        return "report/salesreport";
    },

    moduleApi: () => {
        return "api/" + SalesReport.module();
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
        window.location.href = url.base_url(SalesReport.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(SalesReport.module()) + "add";
    },

    getPostInput: () => {
        let data = {
            id: $("input#id").val(),
            customer: $("#customer").val(),
            pic: $("#pic").val(),
            email: $("#email").val(),
            contact: $("#contact").val(),
            pic_lumiship: $("#pic_lumiship").val(),
            email_karyawan: $("#email_karyawan").val(),
            contact_karyawan: $("#contact_karyawan").val(),
            origin: $("#origin").attr("data_id"),
            destinations: $("#destinations").attr("data_id"),
            delivery_type: $("#delivery_type").val(),
            incoterm: $("#incoterm").val(),
            comodity: $("#comodity").val(),
            validity: $("#validity").val(),
            shipment_type: $("#shipment_type").val(),
            term_conditons: tinymce.get("term_conditons").getContent(),
            data_item: SalesReport.getPostItem(),
            data_item_buy: SalesReport.getPostItemBuy(),
            // data_term: SalesReport.getPostItemTerm(),
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

    getPostItemBuy: () => {
        let data = [];
        let table = $("#table-buyrate").find("tbody").find("tr.input");
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

    getPostItemTerm: () => {
        let data = [];
        let table = $("#table-term").find("tbody").find("tr.input");
        $.each(table, function () {
            let params = {};
            params.term_conditions = $(this).find("#term_conditons").val();
            // params.term_conditions = tinymce.get("term_conditons").getContent(),
            data.push(params);
        });
        return data;
    },

    submit: (elm, e) => {
        e.preventDefault();
        let form = $(elm).closest("div.content-save");
        if (validation.runWithElement(form)) {
            let params = SalesReport.getPostInput();
            // if (params.data_item.length == 0) {
            //     message.sweetError("Informasi", "Table Rate Harus Diisi");
            //     return;
            // }
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(SalesReport.moduleApi()) + "submit",
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
                            SalesReport.back();
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
            searching: false,
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
                url: url.base_url(SalesReport.moduleApi()) + `getData`,
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
                    data: "tgl_quotation",
                },
                {
                    data: "no_quotation",
                    render: function (data, type, row) {
                        return `<a href='${url.base_url(
                            SalesReport.module()
                        )}detail?id=${row.id}' data_id="${row.id}">${data}</a>`;
                    },
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
                        var html = `<button type="button" data_id="${row.id}" onclick="SalesReport.duplicate(this, event)" class="btn btn-warning editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-duplicate"></i></button>&nbsp;`;
                        if (updateAction == 1) {
                            html += `<a href='${url.base_url(
                                SalesReport.module()
                            )}detail?id=${data}' data_id="${
                                row.id
                            }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;

                            if (row.status == "CREATED") {
                                html += `<a href='${url.base_url(
                                    SalesReport.module()
                                )}ubah?id=${data}' data_id="${
                                    row.id
                                }" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            // if(row.status != 'CONFIRMED'){
                            html += `<button type="button" data_id="${row.id}" onclick="SalesReport.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
                            // }
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
                url: url.base_url(SalesReport.moduleApi()) + `getData`,
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
                    title: "Sales Report 1",
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
                    data: "tgl_quotation",
                },
                {
                    data: "no_quotation",
                },
                {
                    data: "nama_customer",
                },
                {
                    data: "pic_customer",
                },
                {
                    data: "email_customer",
                },
                {
                    data: "contact_customer",
                },
                {
                    data: "pic",
                },
                {
                    data: "email_karyawan",
                },
                {
                    data: "contact_karyawan",
                },
                {
                    data: "origin_port",
                },
                {
                    data: "dst_port",
                },
                {
                    data: "delivery",
                },
                {
                    data: "term_and_conditions",
                },
                {
                    data: "comodity",
                },
                {
                    data: "validity",
                },
                {
                    data: "total_idr",
                },
                {
                    data: "total_usd",
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

    filterDataReport2: async (elm) => {
        let tableData = $("table#table-data-report");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();
        let params = {};
        params.tgl_awal = $("#tgl_awal_report").val();
        params.tgl_akhir = $("#tgl_akhir_report").val();

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
                url: url.base_url(SalesReport.moduleApi()) + `getDataReport`,
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
                    title: "Sales Report 2",
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
                    data: "instruction_date",
                },
                {
                    data: "no_shipping",
                },
                {
                    data: "no_quotation",
                },
                {
                    data: "nama_customer",
                },
                {
                    data: "pic_customer",
                },
                {
                    data: "email_customer",
                },
                {
                    data: "contact_customer",
                },
                {
                    data: "shipper",
                },
                {
                    data: "consignee",
                },
                {
                    data: "notify_party",
                },
                {
                    data: "incoterm_label",
                },
                {
                    data: "origin_port",
                },
                {
                    data: "dst_port",
                },
                {
                    data: "20ft",
                },
                {
                    data: "40ft",
                },
                {
                    data: "40hc",
                },
                {
                    data: "45ft",
                },
                {
                    data: "others",
                },
                {
                    data: "no_service_contract",
                },
                {
                    data: "agen_over",
                },
                {
                    data: "pic_shipping",
                },
                {
                    data: "shipping_line",
                },
                {
                    data: "booking_via",
                },
                {
                    data: "no_service_contract",
                },
                {
                    data: "vessel_voyage",
                },
                {
                    data: "etd",
                },
                {
                    data: "eta",
                },
                {
                    data: "stuffing_date",
                },
                {
                    data: "name_of_good",
                },
                {
                    data: "gross_weight",
                },
                {
                    data: "volume",
                },
                {
                    data: "dimension",
                },
                {
                    data: "number_of_bl",
                },
                {
                    data: "top_ship",
                },
                {
                    data: "roe_ship",
                },
                {
                    data: "salesname",
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
            url: url.base_url(SalesReport.moduleApi()) + "delete",
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
            url: url.base_url(SalesReport.moduleApi()) + "confirmDelete",
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

    showDataPort: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(SalesReport.moduleApi()) + "showDataPort",

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
                SalesReport.getDataPort(type);
            },
        });
    },

    getDataPort: (type) => {
        let tipePort = type;
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
                url: url.base_url(SalesReport.moduleApiPort()) + `getData`,
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
                        html += `<a href='' type="${tipePort}" country="${row.country}" code="${row.code}" onclick="SalesReport.pilihData(this, event)" data_id="${row.id}" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                        return html;
                    },
                },
            ],
        });
    },

    back: (elm) => {
        window.location.href = url.base_url(SalesReport.module()) + "/";
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
            url: url.base_url(SalesReport.moduleApi()) + "confirm",
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
                        SalesReport.back();
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
                `<button type="button" onclick="SalesReport.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },

    addItemBuy: (elm, e) => {
        e.preventDefault();
        let table = $("#table-buyrate").find("tbody").find("tr.input:last");
        // console.log(table);
        let newTr = table.clone();
        newTr.find("input").val("");
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="SalesReport.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },

    addItemTerm: (elm, e) => {
        e.preventDefault();
        let table = $("#table-term").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("textarea").val("");
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="SalesReport.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
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
            url: url.base_url(SalesReport.moduleApi()) + "duplicate",
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

    wizard: () => {
        $("#vertical-menu-btn").trigger("click");
    },
};

$(function () {
    SalesReport.wizard();
    SalesReport.editor();
    SalesReport.setSelect2();
    SalesReport.setDate();
    SalesReport.filterData();
});
