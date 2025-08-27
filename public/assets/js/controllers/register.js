let FormComUser = {

    uploadedFiles: [],

    module: (formType) => {
        return formType === 'company' ? 'register/company' : 'register/user';
    },

    moduleApi: (formType) => {
        return formType === 'company' ? 'submit' : 'submit';
    },

    getPostInput: (formType) => {
        let formId = formType === 'company' ? 'company-form' : 'user-form';
        let formData = new FormData(document.getElementById(formId));

        FormComUser.uploadedFiles.forEach(file => {
            formData.append('files[]', file);
        });
        return formData;
    },

    submit: (e, formType) => {
        e.preventDefault();
        const formData = FormComUser.getPostInput(formType);
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (formType === 'company') {
            const companyContactInput = document.getElementById('no_hp');
            const companyContact = companyContactInput.value.trim();
            if (companyContact) {
                const formattedContact = '0' + companyContact;
                formData.set('no_hp', formattedContact);
            }
        }

        if (formType === 'user') {
            const userContactInput = document.getElementById('user-contact');
            const userContact = userContactInput.value.trim();
            if (userContact) {
                const formattedUserContact = '0' + userContact;
                formData.set('user-contact', formattedUserContact);
            }
        }

        if (formType === 'company') {
            $('#type-error-text, #nohp-error-text, #email-validation-text').text('');
        } else {
            $('#user-email-validation-text, #nohp-error-text, #password-match-text').text('');
        }

        Swal.fire({
            title: 'Informasi',
            html: 'Processing...',
            icon: 'info',
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            $.ajax({
                type: 'POST',
                url: url.base_url('api/' + FormComUser.module(formType)) + FormComUser.moduleApi(formType),
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                success: (data) => {
                    Swal.close();
                    if (data.message === 'Data berhasil disimpan') {
                        Swal.fire({
                            title: 'Informasi',
                            html: 'Data berhasil disimpan <br> Mengalihkan ke halaman login...',
                            icon: 'success',
                            confirmButtonColor: '#5664d2',
                        });
                        setTimeout(() => {
                            window.location.href = url.base_url('login');
                        }, 3000);
                    } else {
                        handleErrors(data.errors, formType);
                    }
                },
                error: (jqXHR) => {
                    Swal.close();
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        handleErrors(jqXHR.responseJSON.errors, formType);
                    } else {
                        message.sweetError('Error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                }
            });
        }, 5000);

        function handleErrors(errors, formType) {
            console.log('Errors:', errors);
            let errorMessages = '';

            const errorKeys = formType === 'company' ? ['nama_company', 'alamat', 'provinsi_name_company', 'kota_name_company', 'kecamatan_name_company', 'keldesa_name_company', 'type', 'no_hp', 'email', 'çabang', 'npwp'] : ['nama_lengkap', 'userAlamat', 'company', 'user-contact', 'user-email', 'username', 'jabatan', 'nik', 'userType', 'password', 'tgl_lahir', 'tmp_lahir', 'provinsi_name_user', 'kota_name_user', 'kecamatan_name_user', 'keldesa_name_user'];

            errorKeys.forEach(key => {
                if (errors[key] && Array.isArray(errors[key]) && errors[key].length > 0) {
                    errorMessages += `${errors[key][0]}<br>`;
                }
            });

            if (errorMessages) {
                message.sweetError('Error', errorMessages);
            }
        }
    },

    addFileToList: function (file) {
        const allowedExtensions = /(\.doc|\.docx|\.pdf)$/i;

        if (!allowedExtensions.exec(file.name)) {
            Swal.fire({
                title: 'Invalid File Type',
                text: 'Please upload only .doc, .docx, or .pdf files.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        const fileList = document.getElementById('file-list');
        let header = document.getElementById('file-list-header');
        if (!header) {
            header = document.createElement('h6');
            header.id = 'file-list-header';
            header.textContent = 'List File Dokumen';
            fileList.parentNode.insertBefore(header, fileList);
        }

        const listItem = document.createElement('li');
        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        listItem.textContent = file.name;

        const removeButton = document.createElement('button');
        removeButton.className = 'btn btn-danger btn-sm';
        removeButton.textContent = 'Remove';
        removeButton.onclick = () => {
            this.removeFile(file);
            fileList.removeChild(listItem);
            this.checkFileList();
        };

        listItem.appendChild(removeButton);
        fileList.appendChild(listItem);
    },

    checkFileList: function () {
        const fileList = document.getElementById('file-list');
        const header = document.getElementById('file-list-header');

        if (fileList.children.length === 0 && header) {
            header.remove();
        }
    },

    removeFile: function (file) {
        this.uploadedFiles = this.uploadedFiles.filter(f => f.name !== file.name);
        this.checkFileList();
    },

    handleFileUpload: function () {
        const fileInput = document.getElementById('file-input');
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];

            if (file) {
                this.uploadedFiles.push(file);
                this.addFileToList(file);

                fileInput.value = '';
            }
        });
    },

    setSelect2: () => {
        if ($('.select2').length > 0) {
            $.each($('.select2'), function () {
                $(this).select2();
            });
        }
    },

    setSelect3: () => {
        if ($(".select3").length > 0) {
            $(".select3").select2().on('change', function () {
                const selectedOption = $(this).find(':selected');
                const selectedCompanyId = selectedOption.val();
                const selectedGroupName = selectedOption.data('group-name');
                const selectedType = selectedOption.data('type');

                console.log('Selected Company ID:', selectedCompanyId);
                if (selectedCompanyId) {
                    FormComUser.fetchBranches(selectedCompanyId);
                    $('#userType').val(selectedGroupName);
                    $('#userTypeValue').val(selectedType);
                } else {
                    $('#userType').val('');
                    $('#userTypeValue').val('');
                }
            });
        }
    },

    setSelect4: () => {
        if ($('.select4').length > 0) {
            $.each($('.select4'), function () {
                $(this).select2({
                    width: "400px",
                });
            });
        }
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

    fetchBranches: (companyId) => {
        // Clear existing options in the 'cabang' select field
        $('#cabang').empty();

        // Add a default option
        $('#cabang').append('<option value="">Pilih Cabang</option>');

        // Add new branch options dynamically using branchData from Laravel
        branchData.forEach(branch => {
            $('#cabang').append(`<option value="${branch.id}">${branch.nama_cabang}</option>`);
        });

        // Reinitialize select2 (if needed)
        $('#cabang').select2();
    },

    validateFile: (fileInput, errorTextElement) => {
        const allowedExtensions = /(\.doc|\.docx|\.pdf)$/i;
        const filePath = fileInput.value;

        errorTextElement.textContent = '';

        if (!allowedExtensions.exec(filePath)) {
            errorTextElement.textContent = 'Hanya file dengan format .doc, .docx, atau .pdf yang diperbolehkan.';
            fileInput.value = '';
            return false;
        } else {
            const fileSize = fileInput.files[0].size / 1024 / 1024;
            if (fileSize > 5) {
                errorTextElement.textContent = 'Ukuran file tidak boleh lebih dari 5MB.';
                fileInput.value = '';
                return false;
            }
        }

        return true;
    },

    validateNIK: () => {
        const nik = document.getElementById('nik').value;
        const nikErrorText = document.getElementById('nik-error-text');
        const nikPattern = /^\d{16}$/;

        if (!nikPattern.test(nik)) {
            nikErrorText.textContent = 'NIK harus berupa angka dan berjumlah 16 digit.';
            return false;
        } else {
            nikErrorText.textContent = '';
            return true;
        }
    },

    validateContact: () => {
        const userContact = document.getElementById('user-contact').value;
        const contactValidationTextUser = document.getElementById('user-contact-validation-text');
        const validateUserContact = /^\d{10,12}$/;
        if (!validateUserContact.test(userContact)) {
            contactValidationTextUser.textContent = "Nomor handphone harus 11 - 13 digit";
            return false;
        } else {
            contactValidationTextUser.textContent = "";
            return true;
        }
    },

    validateContactCompany: () => {
        const CompanyContact = document.getElementById('no_hp').value.trim();
        const contactValidationText = document.getElementById('nohp-error-text');
        const validateCompany = /^\d{10,12}$/;
        if (!validateCompany.test(CompanyContact)) {
            contactValidationText.textContent = "Nomer Telepon Perusahaan harus 11 - 13 digit.";
            return false;
        } else {
            contactValidationText.textContent = "";
            return true;
        }
    },

    validateNPWP: () => {
        const NPWP = document.getElementById('npwp').value.trim();
        const contactValidationText = document.getElementById('npwp-error-text');
        const validateCompany = /^\d{16}$/;
        if (!validateCompany.test(NPWP)) {
            contactValidationText.textContent = "Nomer NPWP harus 16 digit.";
            return false;
        } else {
            contactValidationText.textContent = "";
            return true;
        }
    },

    validateEmail: () => {
        const email = document.getElementById('email').value;
        const emailValidationText = document.getElementById('email-validation-text');
        const registerButton = document.getElementById('register-btn');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {
            emailValidationText.textContent = "Format email tidak valid!";
            registerButton.disabled = true;
        } else {
            emailValidationText.textContent = "";
            registerButton.disabled = false;
        }
    },

    validateUserEmail: () => {
        const email = document.getElementById('user-email').value;
        const emailValidationText = document.getElementById('user-email-validation-text');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            emailValidationText.textContent = "Format email tidak valid!";
        } else {
            emailValidationText.textContent = "";
        }
    },

    checkPasswordStrength: () => {
        const password = document.getElementById('password').value;
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('password-strength-text');
        let strength = 0;

        if (password.length >= 8) {
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/\d/.test(password)) strength += 25;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 25;
        }

        strengthBar.style.width = strength + '%';
        if (strength <= 50) {
            strengthBar.className = 'progress-bar bg-danger';
            strengthText.textContent = "Kekuatan Password: Lemah";
        } else if (strength <= 75) {
            strengthBar.className = 'progress-bar bg-warning';
            strengthText.textContent = "Kekuatan Password: Sedang";
        } else {
            strengthBar.className = 'progress-bar bg-success';
            strengthText.textContent = "Kekuatan Password: Kuat";
        }

        FormComUser.checkPasswordMatch();
    },

    checkPasswordMatch: () => {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const matchText = document.getElementById('password-match-text');
        const registerBtn = document.getElementById('user-register-btn');

        if (password === confirmPassword && password.length > 0) {
            matchText.textContent = "";
            registerBtn.disabled = false;
        } else {
            matchText.textContent = "Password tidak sama!";
            registerBtn.disabled = true;
        }
    },

    togglePasswordVisibility: (input) => {
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    },

    fetchBranches: (companyId) => {
        $('#branch').empty().append('<option selected value="">--- PILIH ---</option>');

        if (companyId) {
            $.ajax({
                type: 'POST',
                url: url.base_url('company-branches/' + companyId),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    const selectBranch = $('#branch');
                    selectBranch.empty();
                    selectBranch.append('<option selected value="">--- PILIH ---</option>');
                    data.forEach(function (branch) {
                        selectBranch.append('<option value="' + branch.id + '">' + branch.branch + '</option>');
                    });
                    selectBranch.trigger('change');
                },
                error: function (jqXHR) {
                    console.error('Error fetching branches:', jqXHR);
                    message.sweetError('Error', 'Terjadi kesalahan saat mengambil data cabang.');
                }
            });
        }
    },

    initListeners: () => {
        document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.getElementById('email');
            const companyForm = document.getElementById('company-form');
            const userEmailInput = document.getElementById('user-email');
            const userForm = document.getElementById('user-form');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const passwordToggleBtn = document.getElementById('password-addon-toggle');
            const confirmPasswordToggleBtn = document.getElementById('password-confirm-addon-toggle');
            const nik = document.getElementById('nik');
            const userContact = document.getElementById('user-contact');
            const CompanyContact = document.getElementById('no_hp');
            const CompanyNPWP = document.getElementById('npwp');
            const formMessage = document.getElementById('form-message');
            const usernameInput = document.getElementById('username');
            const companySelect = document.getElementById('company');
            const userTypeInput = document.getElementById('userType');
            const userTypeValueInput = document.getElementById('userTypeValue');
            const formCompany = document.getElementById('company-form');
            const formUsers = document.getElementById('user-form');
            const fileInput = document.getElementById('file');
            const fileErrorText = document.getElementById('file-error-text');
            const nonPTCheckbox = document.getElementById('nonPT');

            const originalOptions = Array.from(companySelect.options).map(option => {
                return {
                    value: option.value,
                    text: option.text,
                    category: option.getAttribute('data-category'),
                    groupName: option.getAttribute('data-group-name'),
                    type: option.getAttribute('data-type')
                };
            });

            nonPTCheckbox.addEventListener('change', filterAndSelectCompanies);

            function filterAndSelectCompanies() {
                const isChecked = nonPTCheckbox.checked;

                companySelect.innerHTML = '';
                companySelect.add(new Option('--- PILIH ---', '', true));

                if (isChecked) {
                    originalOptions.forEach(option => {
                        if (option.category && option.category.toLowerCase() !== 'pt') {
                            const newOption = new Option(option.text, option.value);
                            newOption.setAttribute('data-group-name', option.groupName);
                            newOption.setAttribute('data-type', option.type);
                            companySelect.add(newOption);
                        }
                    });
                } else {
                    originalOptions.forEach(option => {
                        if (option.category && option.category.toLowerCase() === 'pt') {
                            const newOption = new Option(option.text, option.value);
                            newOption.setAttribute('data-group-name', option.groupName);
                            newOption.setAttribute('data-type', option.type);
                            companySelect.add(newOption);
                        }
                    });
                }

                companySelect.addEventListener('change', function () {
                    const selectedOption = companySelect.options[companySelect.selectedIndex];
                    const selectedGroupName = selectedOption.getAttribute('data-group-name');
                    const selectedType = selectedOption.getAttribute('data-type');

                    if (selectedOption.value) {
                        userTypeInput.value = selectedGroupName;
                        userTypeValueInput.value = selectedType;
                    } else {
                        userTypeInput.value = '';
                        userTypeValueInput.value = '';
                    }
                });

                if (companySelect.selectedIndex > -1 && !companySelect.options[companySelect.selectedIndex]) {
                    companySelect.selectedIndex = 0;
                }
            }

            filterAndSelectCompanies();

            if (nik && usernameInput) {
                nik.addEventListener('input', function () {
                    usernameInput.value = nik.value;
                });
            }

            if (emailInput) emailInput.addEventListener('input', FormComUser.validateEmail);

            if (nik) nik.addEventListener('input', FormComUser.validateNIK);

            if (userContact) userContact.addEventListener('input', FormComUser.validateContact);

            if (CompanyContact) {
                CompanyContact.addEventListener('input', FormComUser.validateContactCompany);
            }
            if (CompanyNPWP) {
                CompanyNPWP.addEventListener('input', FormComUser.validateNPWP);
            }

            if (companyForm) {
                companyForm.addEventListener('submit', (e) => FormComUser.submit(e, 'company'));
            }

            if (userEmailInput) userEmailInput.addEventListener('input', FormComUser.validateUserEmail);

            if (userForm) userForm.addEventListener('submit', (e) => FormComUser.submit(e, 'user'));

            if (passwordInput) passwordInput.addEventListener('input', FormComUser.checkPasswordStrength);

            if (passwordConfirmationInput) passwordConfirmationInput.addEventListener('input', FormComUser.checkPasswordMatch);

            if (passwordToggleBtn) passwordToggleBtn.addEventListener('click', function () {
                FormComUser.togglePasswordVisibility(passwordInput);
            });

            if (confirmPasswordToggleBtn) confirmPasswordToggleBtn.addEventListener('click', function () {
                FormComUser.togglePasswordVisibility(passwordConfirmationInput);
            });

            document.querySelectorAll('#formTabs a[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (e) {
                    const activeTab = e.target.getAttribute('href');

                    if (activeTab === '#company-tab') {
                        formCompany.style.display = 'block';
                        formUsers.style.display = 'none';
                    } else if (activeTab === '#users-tab') {
                        formUsers.style.display = 'block';
                        formCompany.style.display = 'none';
                    }

                    formMessage.style.display = 'none';
                });
            });

            formCompany.style.display = 'block';
            formUsers.style.display = 'none';

            formCompany.addEventListener('input', function () {
                formMessage.style.display = 'none';
            });

            formUsers.addEventListener('input', function () {
                formMessage.style.display = 'none';
            });

            if (fileInput) {
                fileInput.addEventListener('change', function () {
                    FormComUser.validateFile(fileInput, fileErrorText);
                });
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

FormComUser.initListeners();

$(function () {
    FormComUser.fetchProvinces();
    FormComUser.fetchCities();
    FormComUser.fetchDistricts();
    FormComUser.fetchVillages();
    FormComUser.setSelect2();
    FormComUser.setSelect3();
    FormComUser.setSelect4();
    FormComUser.handleFileUpload();
    FormComUser.setDate();
});
