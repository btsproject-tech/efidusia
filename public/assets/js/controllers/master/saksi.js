let Saksi = {
    module: () => {
        return 'master/saksi';
    },

    moduleApi: () => {
        return 'api/' + Saksi.module();
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
        window.location.href = url.base_url(Saksi.module()) + "/";
    },

    add: (elm, e) => {
        e.preventDefault();
        window.location.href = url.base_url(Saksi.module()) + "add";
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
            'saksi': $('#saksi').val(),
            'provinsi_company': $('#provinsi_company').find(`option[value='${$('#provinsi_company').val()}']`).text(),
            'kota_company': $('#kota_company').find(`option[value='${$('#kota_company').val()}']`).text(),
            'kecamatan_company': $('#kecamatan_company').find(`option[value='${$('#kecamatan_company').val()}']`).text(),
            'keldesa_company': $('#keldesa_company').find(`option[value='${$('#keldesa_company').val()}']`).text()
        };

        return data;
    },

    submit: (elm, e) => {
        e.preventDefault();
        let form = $(elm).closest('div.row');
        if (validation.runWithElement(form)) {
            let params = Saksi.getPostInput();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: params,
                url: url.base_url(Saksi.moduleApi()) + "submit",
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
                            Saksi.back();
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
        window.location.href = url.base_url(Saksi.module()) + "/";
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
                "url": url.base_url(Saksi.moduleApi()) + `getData`,
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
                    "data": "nik",
                    "render": function (data, type, row) {
                        return row.provinsi+" "+row.kota+" "+row.kecamatan+" "+row.kel_desa
                    }
                },
                {
                    "data": "status",
                },
                {
                    "data": "nama_company",
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        var html = "";
                        if (updateAction == 1) {
                            // html += `<a href='${url.base_url(Saksi.module())}ubah?id=${data}' data_id="${row.id}" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="bx bx-edit"></i></a>&nbsp;`;
                        }
                        if (deleteAction == 1) {
                            html += `<button type="button" data_id="${row.id}" onclick="Saksi.delete(this, event)" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="bx bx-trash-alt"></i></button>`;
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
            url: url.base_url(Saksi.moduleApi()) + "delete",
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
            url: url.base_url(Saksi.moduleApi()) + "confirmDelete",
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

    fetchProvinces: () => {
        $('#provinsi_company, #provinsi_user').empty().append('<option selected value="">--- PILIH ---</option>');

        $.ajax({
            type: 'GET',
            url: 'https://samarif085.github.io/api-wilayah-indonesia/api/provinces.json',
            dataType: 'json',
            success: function (data) {
                const selectProvinsiCompany = $('#provinsi_company');
                const selectProvinsiUser = $('#provinsi_user');

                if (data && data.length > 0) {
                    data.forEach(function (provinsi) {
                        selectProvinsiCompany.append('<option value="' + provinsi.id + '">' + provinsi.name + '</option>');
                        selectProvinsiUser.append('<option value="' + provinsi.id + '">' + provinsi.name + '</option>');
                    });
                } else {
                    selectProvinsiCompany.append('<option value="">No provinces found</option>');
                    selectProvinsiUser.append('<option value="">No provinces found</option>');
                }

                selectProvinsiCompany.on('change', function () {
                    const selectedOption = $(this).find('option:selected');
                    const selectedName = selectedOption.text();
                    $('#provinsi_name_company').val(selectedName);
                });

                selectProvinsiUser.on('change', function () {
                    const selectedOption = $(this).find('option:selected');
                    const selectedName = selectedOption.text();
                    $('#provinsi_name_user').val(selectedName);
                });

                selectProvinsiCompany.trigger('change');
                selectProvinsiUser.trigger('change');
            },
            error: function (jqXHR) {
                console.error('Error fetching provinces:', jqXHR);
                message.sweetError('Error', 'Terjadi kesalahan saat mengambil data provinsi.');
            }
        });
    },

    fetchCities: (provinsiId, formType) => {
        const kotaSelect = formType === 'user' ? $('#kota_user') : $('#kota_company');
        const kecamatanSelect = formType === 'user' ? $('#kecamatan_user') : $('#kecamatan_company');
        const keldesaSelect = formType === 'user' ? $('#keldesa_user') : $('#keldesa_company');

        kotaSelect.empty().append('<option selected value="">--- PILIH ---</option>');
        kotaSelect.prop('disabled', true);

        kecamatanSelect.empty().append('<option selected value="">--- PILIH ---</option>');
        kecamatanSelect.prop('disabled', true);

        keldesaSelect.empty().append('<option selected value="">--- PILIH ---</option>');
        keldesaSelect.prop('disabled', true);

        if (provinsiId) {
            $.ajax({
                type: 'GET',
                url: 'https://samarif085.github.io/api-wilayah-indonesia/api/regencies/' + provinsiId + '.json',
                dataType: 'json',
                success: function (data) {
                    kotaSelect.empty().append('<option selected value="">--- PILIH ---</option>');
                    data.forEach(function (city) {
                        kotaSelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                    kotaSelect.on('change', function () {
                        const selectedOption = $(this).find('option:selected');
                        const selectedName = selectedOption.text();
                        if (formType === 'user') {
                            $('#kota_name_user').val(selectedName);
                        } else {
                            $('#kota_name_company').val(selectedName);
                        }
                    });
                    kotaSelect.prop('disabled', false);
                    kotaSelect.trigger('change');
                },
                error: function (jqXHR) {
                    console.error('Error fetching cities:', jqXHR);
                    message.sweetError('Error', 'Terjadi kesalahan saat mengambil data kota.');
                }
            });
        }
    },

    fetchDistricts: (kotaId, formType) => {
        const kecamatanSelect = formType === 'user' ? $('#kecamatan_user') : $('#kecamatan_company');
        const keldesaSelect = formType === 'user' ? $('#keldesa_user') : $('#keldesa_company');

        kecamatanSelect.empty().append('<option selected value="">--- PILIH ---</option>');
        kecamatanSelect.prop('disabled', true);

        keldesaSelect.empty().append('<option selected value="">--- PILIH ---</option>');
        keldesaSelect.prop('disabled', true);

        if (kotaId) {
            $.ajax({
                type: 'GET',
                url: 'https://samarif085.github.io/api-wilayah-indonesia/api/districts/' + kotaId + '.json',
                dataType: 'json',
                success: function (data) {
                    kecamatanSelect.empty().append('<option selected value="">--- PILIH ---</option>');
                    data.forEach(function (district) {
                        kecamatanSelect.append('<option value="' + district.id + '">' + district.name + '</option>');
                    });
                    kecamatanSelect.on('change', function () {
                        const selectedOption = $(this).find('option:selected');
                        const selectedName = selectedOption.text();
                        if (formType === 'user') {
                            $('#kecamatan_name_user').val(selectedName);
                        } else {
                            $('#kecamatan_name_company').val(selectedName);
                        }
                    });
                    kecamatanSelect.prop('disabled', false);
                    kecamatanSelect.trigger('change');
                },
                error: function (jqXHR) {
                    console.error('Error fetching districts:', jqXHR);
                    message.sweetError('Error', 'Terjadi kesalahan saat mengambil data kecamatan.');
                }
            });
        }
    },

    fetchVillages: (kecId, formType) => {
        const keldesaSelect = formType === 'user' ? $('#keldesa_user') : $('#keldesa_company');

        keldesaSelect.empty().append('<option selected value="">--- PILIH ---</option>');
        keldesaSelect.prop('disabled', true);

        if (kecId) {
            $.ajax({
                type: 'GET',
                url: 'https://samarif085.github.io/api-wilayah-indonesia/api/villages/' + kecId + '.json',
                dataType: 'json',
                success: function (data) {
                    keldesaSelect.empty().append('<option selected value="">--- PILIH ---</option>');
                    data.forEach(function (villages) {
                        keldesaSelect.append('<option value="' + villages.name + '">' + villages.name + '</option>');
                    });
                    keldesaSelect.on('change', function () {
                        const selectedOption = $(this).find('option:selected');
                        const selectedName = selectedOption.text();
                        if (formType === 'user') {
                            $('#keldesa_name_user').val(selectedName);
                        } else {
                            $('#keldesa_name_company').val(selectedName);
                        }
                    });
                    keldesaSelect.prop('disabled', false);
                    keldesaSelect.trigger('change');
                },
                error: function (jqXHR) {
                    console.error('Error fetching villages:', jqXHR);
                    message.sweetError('Error', 'Terjadi kesalahan saat mengambil data desa.');
                }
            });
        }
    },

};

$(function () {
    Saksi.setSelect2();
    Saksi.fetchProvinces();
    Saksi.fetchCities();
    Saksi.fetchDistricts();
    Saksi.fetchVillages();
    Saksi.getData();
});
