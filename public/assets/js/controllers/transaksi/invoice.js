let Invoice = {
    module: () => {
        return "transaksi/invoice";
    },

    moduleApi: () => {
        return "api/" + Invoice.module();
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

    setSelect3: () => {
        if ($(".select3").length > 0) {
            $.each($(".select3"), function () {
                $(this).select2({
                    width: "500px",
                });
            });
        }
    },

    cancel: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Invoice.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Invoice.module()) + "add";
    },

    getPostInputN: () => {
        let data = {
            id: $("input#id").val(),
            // shipping_execution: $("#shipping_execution").attr("data_id"),
            invoice_date: $("#invoice_date_n").val(),
            customer: $("#customer_name_n").val(),
            tipe_invoice: $("input#notaris").val(),
            roe: $("#roe_n").val(),
            amount: $("#amount_n").val(),
            total: $("#grand_total_n").val(),
            tax: $("#tax_n").val(),
            materai: $("#biaya_meterai_n").val(),
            data_item: Invoice.getPostItemN(),
        };

        return data;
    },

    getPostItemN: () => {
        let data = [];
        let table = $("#table-rate_n").find("tbody").find("tr.input");
        $.each(table, function () {
            let subject = $(this).find("#subject_n").val();
            let currency = $(this).find("#currency_n").val();
            let rate = $(this).find("#rate_n").val();
            let unit = $(this).find("#unit_n").val();
            let qty = $(this).find("#qty_n").val();
            // let remarks = $(this).find("#remarks").val();

            if (!subject || !currency || !rate || !unit || !qty) {
                message.sweetError("Informasi", "Data Item Belum Lengkap");
                data = [];
                return false;
            }

            let params = {
                subject: subject,
                currency: currency,
                rate: rate,
                unit: unit,
                qty: qty,
                // remarks: remarks,
            };

            data.push(params);
        });
        return data;
    },

    getPostInputF: () => {
        let data = {
            id: $("input#id").val(),
            // shipping_execution: $("#shipping_execution").attr("data_id"),
            invoice_date: $("#invoice_date_f").val(),
            customer: $("#company").val(),
            batch: $("#batch").val(),
            tipe_invoice: $("input#finance").val(),
            roe: $("#roe_f").val(),
            amount: $("#amount_f").val(),
            total: $("#grand_total_f").val(),
            tax: $("#tax_f").val(),
            // materai: $("#biaya_meterai_f").val(),
            data_item: Invoice.getPostItemF(),
        };

        console.log(data);
        return data;
    },

    getPostItemF: () => {
        let data = [];

        let table1 = $("#table-rate_f").find("tbody").find("tr.inputF");

        let table2 = $("#table-rate_f2").find("tbody").find("tr.inputF2");

        const processTable = (table) => {
            $.each(table, function () {
                let subject = $(this).find(".subject_f").val();
                let currency = $(this).find(".currency_f").val();
                let rate = $(this).find(".charge_f").val();
                let unit = $(this).find(".unit_f").val();
                let qty = $(this).find(".qty_f").val();

                if (!subject || !currency || !rate || !unit || !qty) {
                    message.sweetError("Informasi", "Data Belum Lengkap");
                    data = [];
                    return false;
                }

                let params = {
                    subject: subject,
                    currency: currency,
                    rate: rate,
                    unit: unit,
                    qty: qty,
                };
                data.push(params);
            });
        };

        processTable(table1);
        processTable(table2);
        console.log(data);

        return data;
    },

    submit: (element, elm, e) => {
        e.preventDefault();
        // if (element.toUpperCase() === "NOTARIS") {
        //     let form = $(elm).closest("div.notaris");
        //     if (validation.runWithElement(form)) {
        //         let params = Invoice.getPostInputN();
        //         $.ajax({
        //             type: "POST",
        //             dataType: "json",
        //             data: params,
        //             url: url.base_url(Invoice.moduleApi()) + "submit",
        //             beforeSend: () => {
        //                 message.loadingProses("Proses Simpan Data...");
        //             },
        //             error: function () {
        //                 message.closeLoading();
        //                 message.sweetError("Informasi", "Gagal");
        //             },

        //             success: function (resp) {
        //                 message.closeLoading();
        //                 if (resp.is_valid) {
        //                     message.sweetSuccess();
        //                     setTimeout(function () {
        //                         Invoice.back();
        //                     }, 1000);
        //                 } else {
        //                     message.sweetError("Informasi", resp.message);
        //                 }
        //             },
        //         });
        //     } else {
        //         message.sweetError("Informasi", "Data Belum Lengkap");
        //     }
        // } else if (element.toUpperCase() === "FINANCE") {
        let form = $(elm).closest("div.finance");
        if (validation.runWithElement(form)) {
            let params = Invoice.getPostInputF();
            $.ajax({
                type: "POST",
                dataType: "json",
                data: params,
                url: url.base_url(Invoice.moduleApi()) + "submit",
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
                            Invoice.back();
                        }, 1000);
                    } else {
                        message.sweetError("Informasi", resp.message);
                    }
                },
            });
        } else {
            message.sweetError("Informasi", "Data Belum Lengkap");
        }
        // }
    },

    getData: async () => {
        let tableData = $("table#table-data");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();
        let printAction = $("#print").val();
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
                url: url.base_url(Invoice.moduleApi()) + `getData`,
                type: "POST",
                data: params,
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) { },
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
                    render: function (data, type, row) {
                        return `<a href='${url.base_url(
                            Invoice.module()
                        )}detail?id=${btoa(row.id)}' data_id="${row.id
                            }">${data}</a>`;
                    },
                },
                {
                    data: "invoice_date",
                },
                {
                    data: "nama_lengkap",
                    render: function (data) {
                        return data.toUpperCase();
                    },
                },
                {
                    data: "tipe_invoice",
                },
                {
                    data: "status",
                    render: function (data, type, row) {
                        var html = `<span class="badge bg-warning" style="font-size:12px;">${data}</span>`;
                        if (data == "CONFIRMED") {
                            html = `<span class="badge bg-success" style="font-size:12px;">${data}</span>`;
                        }
                        if (data == "PAID") {
                            html = `<span class="badge bg-secondary" style="font-size:12px;">${data}</span>`;
                        }
                        return html;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        if (printAction == 1) {
                            html = `<a href='${url.base_url(
                                Invoice.module()
                            )}detail?id=${btoa(
                                data
                            )}' class="btn btn-info editable-submit btn-sm waves-effect waves-light m-1"><i class="bx bx-detail"></i></a>&nbsp;`;
                            html += `<button type="button" data_id="${row.id}" data_batch="${row.no_batch}" data_invoice="${row.no_invoice}" onclick="Invoice.exportDataInvoicing(this, event)" class="btn btn-success editable-cancel btn-sm waves-effect waves-light m-1"><i class="bx bx-download"></i></button>`;
                        }

                        if (updateAction == 1) {
                            if (row.status == "DRAFT") {
                                html += `<a href='${url.base_url(
                                    Invoice.module()
                                )}ubah?id=${btoa(
                                    data
                                )}' class="btn btn-warning editable-submit btn-sm waves-effect waves-light m-1"><i class="bx bx-edit"></i></a>&nbsp;`;
                            }
                        }
                        if (deleteAction == 1) {
                            if (
                                row.status != "CONFIRMED" &&
                                row.status != "PAID"
                            ) {
                                html += `<button type="button" data_id="${row.id}" onclick="Invoice.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light m-1"><i class="bx bx-trash-alt"></i></button>`;
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

    showDataBatch: (elm) => {
        // console.log(elm);
        $.ajax({
            type: "POST",
            dataType: "html",
            url: url.base_url(Invoice.moduleApi()) + "showDataBatch",

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
                Invoice.getDataIbacth(elm);
            },
        });
    },

    getDataIbacth: (elm) => {
        let tableData = $("table#table-data-batch");
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
                url: url.base_url(Invoice.moduleApi()) + `getDataBatch`,
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
                    data: "id",
                },
                {
                    data: "user_notaris.nama_lengkap",
                },
                {
                    data: "data_request_certificate.no_request",
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        let qty_contract = row.request_contract.length;
                        return qty_contract;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var html = "";
                        // console.log(
                        //     row.data_request_certificate.creator.karyawan
                        //         .company_karyawan.nama_company
                        // );
                        let biaya_total = row.request_contract.reduce(
                            (total, contract) => {
                                return (
                                    total + parseFloat(contract.biaya_pnbp || 0)
                                );
                            },
                            0
                        );

                        let qty = row.request_contract.length;
                        let company =
                            row.data_request_certificate.creator.karyawan
                                .company_karyawan.id;
                        let company_name =
                            row.data_request_certificate.creator.karyawan
                                .company_karyawan.nama_company;
                        let biaya_jasa =
                            row.data_request_certificate.creator.karyawan
                                .company_karyawan.biaya_jasa;

                        let bacth = row.request_notaris_bacth;
                        if (bacth != null) {
                            if (bacth.deleted != null) {
                                html = `<a href='#'
                                    onclick="Invoice.pilihData(this, event)"
                                    data_bacth="${row.id}"
                                    nama_notaris="${row.user_notaris.nama_lengkap}"
                                    total="${biaya_total}"
                                    qty="${qty}"
                                    companyFinance="${company_name}"
                                    biaya_jasa="${biaya_jasa}"
                                    company="${company}"
                                    class="btn btn-info editable-submit btn-sm waves-effect waves-light">
                                    <i class="bx bx-edit"></i>
                                    </a>&nbsp;`;
                            } else {
                                html = `<span class="badge bg-primary" style="font-size:12px;">IN THE BILL</span>`;
                            }
                        } else {
                            for (
                                var i = 0;
                                i < row.request_contract.length;
                                i++
                            ) {
                                if (
                                    row.request_contract[i].status !==
                                    "COMPLETE" &&
                                    row.request_contract[i].status !==
                                    "FINISHED"
                                ) {
                                    html = `<span class="badge bg-warning" style="font-size:12px;">ON PROCESS</span>`;
                                } else {
                                    html = `<a href='#'
                                    onclick="Invoice.pilihData(this, event)"
                                    data_bacth="${row.id}"
                                    nama_notaris="${row.user_notaris.nama_lengkap}"
                                    total="${biaya_total}"
                                    qty="${qty}"
                                    companyFinance="${company_name}"
                                    biaya_jasa="${biaya_jasa}"
                                    company="${company}"
                                    class="btn btn-info editable-submit btn-sm waves-effect waves-light">
                                    <i class="bx bx-edit"></i>
                                    </a>&nbsp;`;
                                }
                            }
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
        params.data_bacth = $(elm).attr("data_bacth");
        params.nama_notaris = $(elm).attr("nama_notaris");
        params.total = $(elm).attr("total");
        params.qty = $(elm).attr("qty");
        params.company = $(elm).attr("company");
        params.companyFinance = $(elm).attr("companyFinance");
        params.biaya_jasa = $(elm).attr("biaya_jasa");

        $("#notaris_name").val(params.data_bacth + `-` + params.nama_notaris);
        $("#company").val(params.company);
        $("#batch").val(params.data_bacth);
        $("#financeCompany").val(params.companyFinance);
        $("#biayaJasa").val(params.biaya_jasa);

        let tableRate = $("table#table-rate_f2").find("tbody");
        let trHtmlRate = "";
        trHtmlRate +=
            `<tr class="inputF2">
            <td>
            <input id="subject" type="text" class="form-control required subject_f" value=""/>
            </td>
            <td>
            <input id="unit" type="text" readonly class="form-control unit_f" value="berkas"/>
            </td>
            <td>
            <input id="qty" type="number" readonly class="form-control qty_f" value="` +
            params.qty +
            `"/>
            </td>
            <td>
            <input id="currency" type="text" readonly class="form-control currency_f" value="IDR"/>
            </td>
            <td>
            <input id="rate" type="number" class="form-control charge_f" readonly onchange="Invoice.hitungGrandTotalF(this)" pnbp value="` +
            params.total +
            `"/>
            </td>
            </tr>`;
        tableRate.html(trHtmlRate);
        $("button.btn-close").trigger("click");
        // console.log(params);

        // $.ajax({
        //     type: "POST",
        //     url: url.base_url(Invoice.moduleApi()) + "detailBacth",
        //     data: params,
        //     beforeSend: function () {
        //         message.loadingProses("Proses ambil data ...");
        //     },
        //     success: function (response) {
        //         message.closeLoading();
        //         // $("#data_invoice").val(id);
        //         $("#notaris_name").val(params.id + `-` + params.nama_notaris);
        //         // $("#roe").val(response.data.roe);
        //         // $("#amount").val(response.data.amount);
        //         // $("#tax").val(response.data.tax);
        //         // $("#grand_total").val(response.data.total);
        //         // $("#biaya_materai").val(response.data.materai);
        //         // $("#quantity").val(response.data.total_qty);
        //         // let item = response.data.item;
        //         // let tableRate = $("table#table-rate").find("tbody");
        //         // let trHtmlRate = "";
        //         // for (let i = 0; i < item.length; i++) {
        //         // let data = item[i];
        //         trHtmlRate += `<tr class="inputF2">
        //                     <td>
        //                     <input id="subject" type="text" readonly class="form-control" value="${data.subject}"/>
        //                     </td>
        //                     <td>
        //                         <input id="unit" type="text" readonly class="form-control" value="${data.unit}"/>
        //                     </td>
        //                     <td>
        //                         <input id="qty" type="number" readonly class="form-control" value="${data.qty}"/>
        //                     </td>
        //                     <td>
        //                         <input id="currency" type="text" readonly class="form-control" value="${data.currency}"/>
        //                     </td>
        //                     <td>
        //                         <input id="rate" type="number" readonly class="form-control" value="${data.rate}"/>
        //                     </td>
        //                     </tr>`;
        //         // }

        //         tableRate.html(trHtmlRate);
        //         $("button.btn-close").trigger("click");
        //     },
        // });
    },

    delete: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr("data_id");
        $.ajax({
            type: "POST",
            dataType: "html",
            data: params,
            url: url.base_url(Invoice.moduleApi()) + "delete",
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
            url: url.base_url(Invoice.moduleApi()) + "confirmDelete",
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
                        Invoice.getData();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", resp.message);
                }
            },
        });
    },

    hitungGrandTotalN: (elm) => {
        let total = 0;
        $(".charge_n").each(function (index) {
            let charge = parseFloat($(this).val()) || 0;
            let qty = parseFloat($(".qty_n").eq(index).val()) || 0;
            let totalRow = charge * qty;
            total += totalRow;
            $(".total_row_n").eq(index).val(Math.round(totalRow));
        });
        let stamp = parseFloat($("#biaya_meterai_n").val()) || 0;
        let totalAkhir = total + stamp;
        $("#amount_n").val(Math.round(totalAkhir));
        let tax = parseFloat($("#tax_n").val()) || 0;
        let totalTax = (tax / 100) * totalAkhir;
        let grandTotal = totalAkhir + totalTax;
        $("#grand_total_n").val(Math.round(grandTotal));
    },

    hitungGrandTotalF: (elm) => {
        let total = 0;
        $("#table-rate_f")
            .find(".charge_f")
            .each(function (index) {
                let charge = parseFloat($(this).val()) || 0;
                let qty =
                    parseFloat(
                        $("#table-rate_f").find(".qty_f").eq(index).val()
                    ) || 0;
                let totalRow = charge * qty;
                total += totalRow;
                $("#table-rate_f")
                    .find(".total_row_f")
                    .eq(index)
                    .val(Math.round(totalRow));
            });
        // let stamp = parseFloat($("#biaya_meterai_f").val()) || 0;
        let pnbp = parseFloat($("[pnbp]").val()) || 0;
        let totalAkhir = total;
        $("#amount_f").val(Math.round(totalAkhir));
        let tax = parseFloat($("#tax_f").val()) || 0;
        let totalNonTax = totalAkhir / (1 + tax / 100);
        // $("#amount_f").val(Math.round(totalNonTax));
        let totalTax = (tax / 100) * totalNonTax;
        let grandTotal = totalNonTax + totalTax + pnbp;
        let test = totalTax + totalNonTax;
        $("#grand_total_f").val(Math.round(grandTotal));
    },

    back: (elm) => {
        window.location.href = url.base_url(Invoice.module()) + "/";
    },

    setDate: () => {
        let dataDate = $(".data-date");
        $.each(dataDate, function () {
            // console.log($(this));
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
        params.no_invoice = $("#no_invoice").val();

        $.ajax({
            type: "POST",
            dataType: "json",
            data: params,
            url: url.base_url(Invoice.moduleApi()) + "confirm",
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
                        Invoice.back();
                    }, 1000);
                } else {
                    message.sweetError("Informasi", resp.message);
                }
            },
        });
    },

    deleteItem: (elm) => {
        // let data_id = $(elm).closest("tr").attr("data_id");
        $(elm).closest("tr").remove();
        Invoice.hitungGrandTotalN();
        Invoice.hitungGrandTotalF();
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

    addItemInvoiceN: (elm, e) => {
        e.preventDefault();
        let table = $("#table-rate_n").find("tbody").find("tr.input:last");
        let newTr = table.clone();
        newTr.find("input").not("#currency_n").val("");
        newTr.find("#currency_n").val(table.find("#currency_n").val());
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="Invoice.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },

    addItemInvoiceF: (elm, e) => {
        e.preventDefault();
        let table = $("#table-rate_f").find("tbody").find("tr.inputF:last");
        let newTr = table.clone();
        newTr.find("input").not("#currency_f").val("");
        newTr.find("#currency_f").val(table.find("#currency_f").val());
        newTr.attr("data_id", "");
        newTr
            .find("td#action")
            .html(
                `<button type="button" onclick="Invoice.deleteItem(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`
            );
        table.after(newTr);
    },

    exportDataInvoicing: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.data_id = btoa($(elm).attr("data_id"));
        params.data_batch = btoa($(elm).attr("data_batch"));
        params.data_invoice = $(elm).attr("data_invoice");

        $.ajax({
            type: "post",
            url: url.base_url(Invoice.moduleApi()) + "exportDataInvoicing",
            data: params,
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
                    Invoice.exportXlsXFile(
                        response.header,
                        response.order_detail,
                        params.data_invoice + "-" + response.date_export
                    );
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
};

$(function () {
    Invoice.setSelect2();
    Invoice.setSelect3();
    Invoice.setDate();
    Invoice.getData();
});
