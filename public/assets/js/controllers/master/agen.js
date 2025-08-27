let Agen = {
    module: () => {
        return 'master/agen';
    },

    moduleApi: () => {
        return 'api/' + Agen.module();
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
        window.location.href = url.base_url(Agen.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Agen.module()) + "add";
    },

    getPostInput: () => {
        let data = {
            'id': $('input#id').val(),
            'company': $('#company').val(),
            'nama_agen': $('#nama_agen').val(),
            'pic': $('#pic').val(),
            'phone': $('#phone').val(),
            'mobile_phone': $('#mobile_phone').val(),
            'email': $('#email').val(),
        };

        return data;
    },

    submit: (elm, e) => {
        e.preventDefault();
        let form = $(elm).closest('div.row');
        if (validation.runWithElement(form)) {
            let params = Agen.getPostInput();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: params,
                url: url.base_url(Agen.moduleApi()) + "submit",
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
                            Agen.back();
                            // window.location.reload();
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
        window.location.href = url.base_url(Agen.module()) + "/";
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
                "url": url.base_url(Agen.moduleApi()) + `getData`,
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
                    "data": "nama_agen",
                },
                {
                    "data": "pic",
                },
                {
                    "data": "phone",
                },
                {
                    "data": "email",
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        var html = "";
                        if (updateAction == 1) {
                            html += `<a href='${url.base_url(Agen.module())}ubah?id=${data}' data_id="${row.id}" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                        }
                        if (deleteAction == 1) {
                            html += `<button type="button" data_id="${row.id}" onclick="Agen.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
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
            url: url.base_url(Agen.moduleApi()) + "delete",
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
            url: url.base_url(Agen.moduleApi()) + "confirmDelete",
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
    Agen.setSelect2();
    Agen.getData();
});
