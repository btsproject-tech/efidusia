let Karyawan = {
    module: () => {
        return 'approval/karyawan';
    },

    moduleApi: () => {
        return 'api/' + Karyawan.module();
    },

    setSelect2: () => {
        if ($('.select2').length > 0) {
            $.each($('.select2'), function () {
                $(this).select2();
            });
        }
    },

    modal: () => {
        let modalHtml = `
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Excel File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <form id="uploadForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="excelFile" class="form-label">Pilih file Excel:</label>
                            <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls">
                        </div>
                        </form>
                        <div id="uploadedData" style="display:none;">
                            <h5>Data yang diunggah: <span id="dataCount"></span></h5>
                            <table class="table table-bordered" id="uploadedDataTable" style="table-layout: fixed; width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="wd-column">Username (NIK)</th>
                                        <th class="wd-column">Email</th>
                                        <th class="wd-column">Password</th>
                                        <th class="wd-column">Complete Name</th>
                                        <th class="wd-column">Zone Code</th>
                                        <th class="wd-column">Role Name</th>
                                        <th class="wd-column">Gelar</th>
                                        <th class="wd-column">Tempat Lahir</th>
                                        <th class="wd-column">Tanggal Lahir</th>
                                        <th class="wd-column">Provinsi</th>
                                        <th class="wd-column">Kota</th>
                                        <th class="wd-column">Kecamatan</th>
                                        <th class="wd-column">Kelurahan/Desa</th>
                                        <th class="wd-column">HandPhone</th>
                                        <th class="wd-column">Perusahaan</th>
                                        <th class="wd-column">Perusahaan Cabang</th>
                                        <th class="wd-column">Alamat</th>
                                        <th class="wd-column">Jabatan</th>
                                        <th class="wd-column">Domisili</th>
                                        <th class="wd-column">Aksi</th> <!-- Kolom untuk aksi hapus -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data dari file Excel akan ditampilkan di sini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="Karyawan.uploadData()">Submit</button>
                    </div>
                </div>
            </div>
        </div>`;

        $('body').append(modalHtml);

        $('#uploadModal').modal('show');

        $('#excelFile').on('change', function () {
            Karyawan.uploadFile();
        });

        $('#uploadModal').on('hidden.bs.modal', function () {
            $(this).remove();
        });
    },

    convertSerialToDate: (serialDate) => {
        const date = new Date((serialDate - 25569) * 86400 * 1000);
        return date;
    },

    convertToDDMMYY: (dateString) => {
        let date;
        if (typeof dateString === 'number') {
            date = Karyawan.convertSerialToDate(dateString);
        } else if (typeof dateString === 'string') {
            if (/^\d{2}-\d{2}-\d{2}$/.test(dateString)) {
                const parts = dateString.split('-');
                const day = parts[0];
                const month = parts[1];
                let year = parts[2];

                if (parseInt(year) < 50) {
                    year = '20' + year;
                } else {
                    year = '19' + year;
                }
                date = new Date(year, month - 1, day);
            } else {
                date = new Date(dateString);
            }
        } else {
            console.error("Tanggal tidak valid:", dateString);
            return "";
        }
        const formattedDay = String(date.getDate()).padStart(2, '0');
        const formattedMonth = String(date.getMonth() + 1).padStart(2, '0');
        const formattedYear = date.getFullYear();

        return `${formattedDay}-${formattedMonth}-${formattedYear}`;
    },

    uploadFile: () => {
        let formData = new FormData($('#uploadForm')[0]);
        $.ajax({
            url: url.base_url(Karyawan.moduleApi()) + 'upload',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#uploadedData').show();
                let tableBody = $('#uploadedDataTable tbody');
                tableBody.empty();
                response.slice(1).forEach((row, index) => {
                    const dateValue = Karyawan.convertToDDMMYY(row[8]);
                    const formattedDateValue = dateValue.split('-').reverse().join('-');
                    let newRow = `
                    <tr>
                        <td><input type="text" class="form-control" name="username" value="${row[0]}"></td>
                        <td><input type="text" class="form-control" name="email" value="${row[1]}"></td>
                        <td><input type="password" class="form-control" name="password" value="${row[2]}"></td>
                        <td><input type="text" class="form-control" name="complete_name" value="${row[3]}"></td>
                        <td><input type="text" class="form-control" name="zone_code" value="${row[4]}"></td>
                        <td><input type="text" class="form-control" name="role_name" value="${row[5]}"></td>
                        <td><input type="text" class="form-control" name="gelar" value="${row[6] ?? '-'}"></td>
                        <td><input type="text" class="form-control" name="tempat_lahir" value="${row[7]}"></td>
                        <td><input type="date" class="form-control" name="tanggal_lahir" value="${formattedDateValue}"></td>
                        <td><input type="text" class="form-control" name="provinsi" value="${row[9]}"></td>
                        <td><input type="text" class="form-control" name="kota" value="${row[10]}"></td>
                        <td><input type="text" class="form-control" name="kecamatan" value="${row[11]}"></td>
                        <td><input type="text" class="form-control" name="kelurahan" value="${row[12]}"></td>
                        <td><input type="text" class="form-control" name="no_handphone" value="${row[13]}"></td>
                        <td><input type="text" class="form-control" name="perusahaan" value="${row[14]}"></td>
                        <td><input type="text" class="form-control" name="perusahaan_cabang" value="${row[15]}"></td>
                        <td><input type="text" class="form-control" name="alamat" value="${row[16]}"></td>
                        <td><input type="text" class="form-control" name="jabatan" value="${row[17]}"></td>
                        <td><input type="text" class="form-control" name="domisili" value="${row[18]}"></td>
                        <td><button type="button" class="btn btn-danger" onclick="Karyawan.removeRow(this)"><i class="mdi mdi-delete"></i></button></td>
                    </tr>`;
                    tableBody.append(newRow);
                });

                $('#dataCount').text(`${response.length - 1}`);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'File berhasil di-upload dan data ditampilkan.',
                    timer: 3000,
                    showConfirmButton: false
                });
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat mengupload file.',
                });
            }
        });
    },

    removeRow: (btn) => {
        $(btn).closest('tr').remove();
    },

    uploadData: () => {
        const dataRows = [];
        $('#uploadedDataTable tbody tr').each(function () {
            const rowData = {};
            $(this).find('input').each(function () {
                const inputName = $(this).attr('name');
                const inputValue = $(this).val();
                rowData[inputName] = inputValue;
            });
            dataRows.push(rowData);
        });
        let userId = $('#user_id').val();
        let userName = $('#name').val();
        $.ajax({
            url: url.base_url(Karyawan.moduleApi()) + 'submit/' + 'dataExcel',
            method: 'POST',
            data: JSON.stringify({
                dataRows: dataRows,
                user_id: userId,
                name: userName,
            }),
            contentType: 'application/json',
            success: function (response) {
                console.log('Data berhasil dikirim:', response);
                $('#uploadModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    willClose: () => {
                        location.reload();
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan saat mengirim data:', error);
                let errorMessage = 'Terjadi kesalahan saat mengirim data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    footer: xhr.responseJSON && xhr.responseJSON.errors ? `<pre>${JSON.stringify(xhr.responseJSON.errors, null, 2)}</pre>` : ''
                });
            }
        });
    },

    cancel: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Karyawan.module()) + "/";
    },

    getPostInput: () => {
        let data = {
            'id': $('input#id').val(),
            'company': $('#company').val(),
            'nama': $('#nama').val(),
            'nik': $('#nik').val(),
            'jabatan': $('#jabatan').val(),
            'contact': $('#contact').val(),
            'email': $('#email').val(),
        };

        return data;
    },

    back: (elm) => {
        window.location.href = url.base_url(Karyawan.module()) + "/";
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
                "url": url.base_url(Karyawan.moduleApi()) + `getData`,
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
                    "data": "nama_lengkap",
                },
                {
                    "data": "nik",
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
                            html += `<button type="button" data_id="${row.remarks}" onclick="Karyawan.delete(this, event)" class="btn btn-info editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-detail"></i></button>`;
                        } else {
                            if (approveAction == 1) {
                                html += `<button type="button" data_id="${row.id}" data-nama="${row.nama_lengkap}" data-nik="${row.nik}" data-contact="${row.contact}" onclick="Karyawan.approve(this, event)" class="btn btn-primary btn-sm waves-effect waves-light"><i class="bx bx-check"></i></button>&nbsp;`;
                            }
                            if (deleteAction == 1) {
                                html += `<button type="button" data_id="${row.id}" data-nama="${row.nama_lengkap}" data-nik="${row.nik}" data-contact="${row.contact}" onclick="Karyawan.cancel(this, event)" class="btn btn-danger btn-sm waves-effect waves-light"><i class="bx bx-x"></i></button>&nbsp;`;
                            }
                        }
                        return html;
                    }
                },
            ]
        });

        data.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $(".dataTables_length select").addClass("form-select form-select-sm"), $("#selection-datatable").DataTable({
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

    approve: (elm, e) => {
        e.preventDefault();
        let dataId = $(elm).attr('data_id');
        let nama_lengkap = $(elm).attr('data-nama');
        let nik = $(elm).attr('data-nik');
        let contact = $(elm).attr('data-contact');
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
                        nik: nik,
                        nama_lengkap: nama_lengkap,
                        user_id: userId,
                        contact: contact,
                        name: userName,
                    },
                    url: url.base_url(Karyawan.moduleApi()) + "submit",
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
        let nama = $(elm).attr('data-nama');
        let nik = $(elm).attr('data-nik');
        let contact = $(elm).attr('data-contact');
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
                        nik: nik,
                        nama_lengkap: nama,
                        remarks: cancelReason,
                        user_id: userId,
                        contact: contact,
                        name: userName,
                    },
                    url: url.base_url(Karyawan.moduleApi()) + "confirmReject",
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

    delete: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr('data_id');
        $.ajax({
            type: 'POST',
            dataType: 'html',
            data: params,
            url: url.base_url(Karyawan.moduleApi()) + "delete",
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

    downloadFile: () => {
        const downloadUrl = url.base_url(Karyawan.module()) + `download/`;
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = downloadUrl;
        document.body.appendChild(iframe);

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'File sedang diunduh.',
            timer: 3000,
            showConfirmButton: false
        });

        setTimeout(() => {
            document.body.removeChild(iframe);
        }, 5000);
    },

};

$(function () {
    Karyawan.setSelect2();
    Karyawan.getData();
});
