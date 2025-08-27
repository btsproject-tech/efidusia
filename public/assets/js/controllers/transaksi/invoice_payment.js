let InvoicePayment = {
    module: () => {
        return "transaksi/invoice-payment";
    },

    moduleApi: () => {
        return "api/" + InvoicePayment.module();
    },

    moduleApiInvoicing: () => {
        return "api/transaksi/invoice";
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
        window.location.href = url.base_url(InvoicePayment.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(InvoicePayment.module()) + "add";
    },

    getPostInput: () => {
        let data = {
            id: $("input#id").val(),
            payment_date: $("input#payment_date").val(),
            invoicing: $("#data_invoice").val(),
            no_invoice: $("#no_invoice").val(),
            total: $("#grand_total").val(),
            remarks: $("#remarks").val(),
            bukti_transfer: InvoicePayment.getPostDataFile(),
        };

        return data;
    },

    getPostDataFile: () => {
        let data = {
            file: $("input#file").attr("src"),
            tipe: $("input#file").attr("tipe"),
            file_name: $("input#file").val(),
        };

        return data;
    },

    addFile: (elm) => {
        // Buat uploader secara dinamis
        var uploader = $(
            '<input type="file" accept="image/*, .pdf" style="display:none" />'
        );
        var src_foto = $("#file");

        // Tambahkan uploader ke body
        $("body").append(uploader);
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

    submit: (elm, e) => {
        e.preventDefault();
        let form = $(elm).closest("div.content-save");
        if (validation.runWithElement(form)) {
            let params = InvoicePayment.getPostInput();
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(InvoicePayment.moduleApi()) + "submit",
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
                            InvoicePayment.back();
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

    getData: async (elm) => {
        let tableData = $("table#table-data");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();
        let print = $("#print").val();

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
                url: url.base_url(InvoicePayment.moduleApi()) + `getData`,
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
                    data: "no_payment",
                    render: function (data, type, row) {
                        return `<a href='${url.base_url(
                            InvoicePayment.module()
                        )}detail?id=${btoa(row.id)}' data_id="${btoa(row.id)}">${data}</a>`;
                    },
                },
                {
                    data: "invoice_no",
                },
                {
                    data: "invoice_date",
                },
                {
                    data: "payment_date",
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
                        if (print == 1) {
                            html = `<a href='${
                                window.location.origin +
                                row.path_file +
                                row.file_payment
                            }' target="_balnk" rel="noopener noreferrer" class="btn btn-primary editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-file"></i></a>&nbsp;`;

                            html += `<a href='${url.base_url(
                                InvoicePayment.module()
                            )}detail?id=${btoa(data)}' data_id="${
                                row.id
                            }" class="btn btn-info editable-submit btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></a>&nbsp;`;
                        }
                        if (updateAction == 1) {
                            if (row.status == "DRAFT") {
                                html += `<a href='${url.base_url(
                                    InvoicePayment.module()
                                )}ubah?id=${btoa(data)}' data_id="${
                                    row.id
                                }" class="btn btn-warning editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            if (row.status != "CONFIRMED") {
                                html += `<button type="button" data_id="${btoa(
                                    row.id
                                )}" invoice_id="${btoa(
                                    row.invoicing
                                )}" onclick="InvoicePayment.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
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

    viewData: (elm) => {
        let params = {};
        params.id = $(elm).attr("data_id");
        params.file_path = $(elm).attr("data_file");
        params.nama_file = $(elm).attr("data_name");
        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(InvoicePayment.moduleApi()) + "showBukti",
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data...");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
            success: function (resp) {
                message.closeLoading();
                // console.log(resp);

                $("#content-modal-form").html(resp);
                $("#btn-show-data").trigger("click");
            },
        });
    },

    delete: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr("data_id");
        params.invoice_id = $(elm).attr("invoice_id");
        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(InvoicePayment.moduleApi()) + "delete",
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
        params.invoice_id = $(elm).attr("invoice_id");
        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(InvoicePayment.moduleApi()) + "confirmDelete",
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
                    $("#konfirmasi-delete").modal("hide");
                    message.sweetSuccess("Informasi", "Data Berhasil Dihapus");
                    setTimeout(function () {
                        // window.location.reload();
                        InvoicePayment.getData();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", resp.message);
                }
            },
        });
    },

    showDataInvoice: (elm, type) => {
        let params = {};

        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(InvoicePayment.moduleApi()) + "showDataInvoice",

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
                InvoicePayment.getDataInvoicing(elm, type);
            },
        });
    },

    getDataInvoicing: (type) => {
        let tableData = $("table#table-data-invoice");
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
                    url.base_url(InvoicePayment.moduleApiInvoicing()) +
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
                    data: "no_invoice",
                },
                {
                    data: "nama_company",
                },
                {
                    data: "tipe_invoice",
                },
                {
                    data: "invoice_date",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        return InvoicePayment.formatRupiah(
                            row.grand_total,
                            row.currency
                        );
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html = "";
                        // console.log(row);
                        html = `<a href=''
                        onclick="InvoicePayment.pilihData(this, event)"
                        data_id="${row.id}"
                        no_invoice="${row.no_invoice}"
                        class="btn btn-info editable-submit btn-sm waves-effect waves-light">
                        <i class="bx bx-edit"></i>
                        </a>&nbsp;`;
                        if (row.status == "DRAFT") {
                            html = `<span class="badge bg-warning" style="font-size:12px;">${row.status}</span>`;
                        }
                        if (row.status == "PAID") {
                            html = `<span class="badge bg-secondary" style="font-size:12px;">${row.status}</span>`;
                        }
                        return html;
                    },
                },
            ],
        });
    },

    pilihData: (elm, e) => {
        e.preventDefault();

        let params = {};
        // var selected = elm.options[elm.selectedIndex];
        params.id = $(elm).attr("data_id");
        params.invoice = $(elm).attr("no_invoice");

        $.ajax({
            type: "POST",
            url: url.base_url(InvoicePayment.moduleApi()) + "getDataInv",
            data: params,
            beforeSend: function () {
                message.loadingProses("Proses ambil data ...");
            },
            success: function (response) {
                message.closeLoading();
                $("#data_invoice").val(response.data.id);
                $("#no_invoice").val(response.data.no_invoice);
                $("#roe").val(response.data.roe);
                $("#amount").val(response.data.amount);
                $("#tax").val(response.data.tax);
                $("#grand_total").val(response.data.total);
                $("#biaya_materai").val(response.data.materai);
                $("#quantity").val(response.data.total_qty);
                let item = response.data.item;
                let tableRate = $("table#table-rate").find("tbody");
                let trHtmlRate = "";
                for (let i = 0; i < item.length; i++) {
                    let data = item[i];
                    trHtmlRate += `<tr class="input">
                            <td>
                                <input id="subject" type="text" readonly class="form-control" value="${data.subject}"/>
                            </td>
                            <td>
                                <input id="unit" type="text" readonly class="form-control" value="${data.unit}"/>
                            </td>
                            <td>
                                <input id="qty" type="number" readonly class="form-control" value="${data.qty}"/>
                            </td>
                            <td>
                                <input id="currency" type="text" readonly class="form-control" value="${data.currency}"/>
                            </td>
                            <td>
                                <input id="rate" type="number" readonly class="form-control" value="${data.rate}"/>
                            </td>
                            </tr>`;
                }

                tableRate.html(trHtmlRate);
                $("button.btn-close").trigger("click");
            },
        });
    },

    back: (elm) => {
        window.location.href = url.base_url(InvoicePayment.module()) + "/";
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
        params.no_payment = $("#no_invoice").val();

        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(InvoicePayment.moduleApi()) + "confirm",
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
                        InvoicePayment.back();
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

        let opt = {
            margin: 0.3,
            filename: `${fileName}.pdf`,
            image: {
                type: "jpeg",
                quality: 1,
            },
            html2canvas: {
                scale: 4,
                scrollX: 0,
                scrollY: 0,
                // width: 750
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

    duplicate: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr("data_id");

        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(InvoicePayment.moduleApi()) + "duplicate",
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

    formatRupiah: (angka, prefix) => {
        var numberString = angka.toString().replace(/[^,\d]/g, ""),
            split = numberString.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
        return prefix === undefined ? rupiah : rupiah ? "IDR " + rupiah : "";
    },
};

$(function () {
    InvoicePayment.setSelect2();
    InvoicePayment.setDate();
    InvoicePayment.getData();
});
