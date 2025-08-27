let Branch = {
    module: () => {
        return 'master/branch';
    },

    moduleApi: () => {
        return 'api/' + Branch.module();
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
        window.location.href = url.base_url(Branch.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Branch.module()) + "add";
    },

    getPostInput: () => {
        let data = {
            'id': $('input#id').val(),
            'nama_cabang': $('input#nama_cabang').val(),
            'kode_cabang': $('#kode_cabang').val(),
        };

        return data;
    },

    submit: (elm, e) => {
        e.preventDefault();
        let form = $(elm).closest('div.row');
        if (validation.runWithElement(form)) {
            let params = Branch.getPostInput();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: params,
                url: url.base_url(Branch.moduleApi()) + "submit",
                beforeSend: () => {
                    message.loadingProses('Proses Simpan Data...');
                },
                error: function () {
                    message.closeLoading();
                    message.sweetError('Informasi', 'Gagal');
                },

                success: function (resp) {
                    message.closeLoading();
                    if (resp.is_valid) {
                        message.sweetSuccess();
                        setTimeout(function () {
                            // window.location.reload();
                            Branch.back();
                        }, 1000);
                    } else {
                        message.sweetError('Informasi', resp.message);
                    }
                }
            });
        } else {
            message.sweetError('Informasi', 'Data Belum Lengkap');
        }
    },

    back: (elm) => {
        window.location.href = url.base_url(Branch.module()) + "/";
    },

    getData: async () => {
        let tableData = $('table#table-data');

        let updateAction = $('#update').val();
        let deleteAction = $('#delete').val();

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
                "url": url.base_url(Branch.moduleApi()) + `getData`,
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
                    "data": "kode_cabang",
                },
                {
                    "data": "nama_cabang",
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        var html = "";
                        if (updateAction == 1) {
                            html += `<a href='${url.base_url(Branch.module())}ubah?id=${data}' data_id="${row.id}" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                        }
                        if (deleteAction == 1) {
                            html += `<button type="button" data_id="${row.id}" onclick="Branch.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
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

    delete: (elm, e) => {
        e.preventDefault();
        let params = {};
        params.id = $(elm).attr('data_id');
        $.ajax({
            type: 'POST',
            dataType: 'html',
            data: params,
            url: url.base_url(Branch.moduleApi()) + "delete",
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

    confirmDelete: (elm) => {
        let params = {};
        params.id = $(elm).attr('data_id');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: params,
            url: url.base_url(Branch.moduleApi()) + "confirmDelete",
            beforeSend: () => {
                message.loadingProses('Proses Simpan Data...');
            },
            error: function () {
                message.closeLoading();
                message.sweetError('Informasi', 'Gagal');
            },

            success: function (resp) {
                message.closeLoading();
                if (resp.is_valid) {
                    message.sweetSuccess('Informasi', 'Data Berhasil Dihapus');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    message.sweetError('Informasi', resp.message);
                }
            }
        });
    },

};

$(function () {
    Branch.setSelect2();
    Branch.getData();
});
