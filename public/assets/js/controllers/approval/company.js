let Company = {
    module: () => {
        return 'approval/perusahaan';
    },

    moduleApi: () => {
        return 'api/' + Company.module();
    },

    setSelect2: () => {
        if ($('.select2').length > 0) {
            $.each($('.select2'), function () {
                $(this).select2();
            });
        }
    },

    cancel: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Company.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Company.module()) + "add";
    },

    getPostInput: () => {
        let data = {
            'id': $('input#id').val(),
            'nama': $('input#nama').val(),
            'alamat': $('#alamat').val(),
            'type': $('#type').val(),
            'no_hp': $('#no_hp').val(),
            'email': $('#email').val(),
        };

        return data;
    },

    back: (elm) => {
        window.location.href = url.base_url(Company.module()) + "/";
    },

    showModalFile: (id, nama_company, filePaths, originalFileNames, fileIds) => {
        $("#modalFilePendukungLabel").html(`File Pendukung ${nama_company}`);
        const fileArray = filePaths ? filePaths.split(",") : [];
        const nameArray = originalFileNames ? originalFileNames.split(",") : [];
        const idArray = fileIds ? fileIds.split(",") : [];

        let fileLinks = '<ul>';
        fileArray.forEach((_fileHash, index) => {
            const originalFileName = nameArray[index] ? nameArray[index].trim() : "Unknown File";
            const fileId = idArray[index] ? idArray[index].trim() : null;

            if (fileId) {
                fileLinks += `
                    <li>
                        <button
                            type="button"
                            class="btn btn-link p-0"
                            onclick="Company.downloadFile('${fileId}')">
                            ${originalFileName} <i class="fas fa-download"></i>
                        </button>
                    </li>`;
            }
        });
        fileLinks += '</ul>';

        $("#modalFilePendukungBody").html(fileLinks.length > 0 ? fileLinks : 'No Files Available');
        $("#modal-file").modal("show");

        $("tr.input").attr("data_id", id);
    },

    downloadFile: (fileId) => {
        const downloadUrl = url.base_url(Company.module()) + `download/` + fileId;
        window.location.href = downloadUrl;

        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'File sedang diunduh.',
                timer: 3000,
                showConfirmButton: false
            });
        }, 1000);
    },

    closeModalFile: () => {
        $("#modal-file").modal("hide");
    },

    getData: async () => {
        let tableData = $('table#table-data');
        let deleteAction = $('#delete').val();
        let approveAction = $('#approve').val();

        var data = tableData.DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": true,
            "autoWidth": false,
            "order": [
                [0, 'asc']
            ],
            "aLengthMenu": [
                [25, 50, 100],
                [25, 50, 100]
            ],
            lengthChange: !1,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            },
            "ajax": {
                "url": url.base_url(Company.moduleApi()) + `getData`,
                "type": "POST",
            },
            "deferRender": true,
            "createdRow": function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            "columns": [{
                    "data": "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "nama_company",
                },
                {
                    "data": "type",
                },
                {
                    "data": "company_file_names",
                    render: function (data, type, row) {
                        if (data) {
                            const originalFileNames = data.split(", ");
                            const filePaths = row.company_file_paths.split(", ");
                            const fileIds = row.company_file_ids.split(", ");

                            return `
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="Company.showModalFile(${row.id}, '${row.nama_company}', '${filePaths.join(",")}', '${originalFileNames.join(",")}', '${fileIds.join(",")}')">
                                    View Files
                                </button>`;
                        } else {
                            return 'No File';
                        }
                    }
                },
                {
                    data: "status",
                    render: function (data, type, row) {
                        var html = `<span class="badge bg-warning" style="font-size:12px;">${data}</span>`;
                        if (data === "APPROVED") {
                            html = `<span class="badge bg-success" style="font-size:12px;">${data}</span>`;
                        } else if (data === "REJECTED") {
                            html = `<span class="badge bg-danger" style="font-size:12px;">${data}</span>`;
                        }
                        return html;
                    },
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        var html = "";
                        if (row.status === "APPROVED") {
                            html += `<button type="button" class="btn btn-success btn-sm waves-effect waves-light">Verified <i class="bx bx-check"></i></button>&nbsp;`;
                        } else if (row.status === "REJECTED") {
                            html += `<button type="button" data_id="${row.remarks}" onclick="Company.delete(this, event)" class="btn btn-info editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></button>`;
                        } else {
                            if (approveAction == 1) {
                                html += `<button type="button" data_id="${row.id}" data-nama="${row.nama_company}" data-telp="${row.no_hp}" onclick="Company.approve(this, event)" class="btn btn-primary btn-sm waves-effect waves-light"><i class="bx bx-check"></i></button>&nbsp;`;
                            }
                            if (deleteAction == 1) {
                                html += `<button type="button" data_id="${row.id}" data-nama="${row.nama_company}" data-telp="${row.no_hp}" onclick="Company.cancel(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-x"></i></button>`;
                            }
                        }
                        return html;
                    }
                },
            ]
        });

        data.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
            $(".dataTables_length select").addClass("form-select form-select-sm"),
            $("#selection-datatable").DataTable({
                select: {
                    style: "multi"
                },
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
    },

    delete: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr('data_id');
        $.ajax({
            type: 'POST',
            dataType: 'html',
            data: params,
            url: url.base_url(Company.moduleApi()) + "delete",
            beforeSend: () => {
                message.loadingProses('Proses Pengambilan Data...');
            },
            error: function () {
                message.closeLoading();
                message.sweetError('Informasi', 'Gagal');
            },

            success: function (resp) {
                message.closeLoading();
                $('#content-confirm-delete').html(resp);
                $('#confirm-delete-btn').trigger('click');
            }
        });
    },

    approve: (elm, e) => {
        e.preventDefault();
        let dataId = $(elm).attr('data_id');
        let company = $(elm).attr('data-nama');
        let telp = $(elm).attr('data-telp');
        let userId = $('#user_id').val();
        let userName = $('#name').val();

        Swal.fire({
            title: 'Konfirmasi Approve',
            text: "Apakah kamu yakin ingin meng-approve data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Approve!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: dataId,
                        company: company,
                        user_id: userId,
                        name: userName,
                        telp: telp,
                    },
                    url: url.base_url(Company.moduleApi()) + "submit",
                    beforeSend: () => {
                        message.loadingProses('Proses Approve Data...');
                    },
                    success: function (resp) {
                        message.closeLoading();
                        Swal.close();
                        if (resp.is_valid) {
                            Swal.fire(
                                'Approved!',
                                'Data telah berhasil di-approve.',
                                'success'
                            );
                            setTimeout(function () {
                                window.location.reload();
                            }, 3000);
                        } else {
                            message.sweetError('Informasi', resp.message);
                        }
                    },
                    error: function () {
                        message.closeLoading();
                        Swal.close();
                        message.sweetError('Informasi', 'Gagal melakukan approve');
                    }
                });
            }
        });
    },

    cancel: (elm, e) => {
        e.preventDefault();
        let dataId = $(elm).attr('data_id');
        let company = $(elm).attr('data-nama');
        let telp = $(elm).attr('data-telp');
        let userId = $('#user_id').val();
        let userName = $('#name').val();

        Swal.fire({
            title: 'Konfirmasi Cancel',
            text: "Apakah kamu yakin ingin membatalkan data ini? Berikan alasan pembatalan:",
            icon: 'warning',
            input: 'text',
            inputPlaceholder: 'Masukkan alasan pembatalan...',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, cancel!',
            cancelButtonText: 'Batal',
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Alasan pembatalan tidak boleh kosong');
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let cancelReason = result.value;
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: dataId,
                        company: company,
                        user_id: userId,
                        remarks: cancelReason,
                        name: userName,
                        telp: telp,
                    },
                    url: url.base_url(Company.moduleApi()) + "confirmReject",
                    beforeSend: () => {
                        message.loadingProses('Proses Cancel Data...');
                    },
                    success: function (resp) {
                        message.closeLoading();
                        if (resp.is_valid) {
                            Swal.fire(
                                'Cancelled!',
                                'Data telah berhasil dibatalkan.',
                                'success'
                            );
                            setTimeout(function () {
                                window.location.reload();
                            }, 3000);
                        } else {
                            message.sweetError('Informasi', resp.message);
                        }
                    },
                    error: function () {
                        message.closeLoading();
                        message.sweetError('Informasi', 'Gagal melakukan cancel');
                    }
                });
            }
        });
    },

};

$(function () {
    Company.setSelect2();
    Company.getData();
    Company.closeModalFile();
});
