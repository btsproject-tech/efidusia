<!doctype html>
<html lang="en">


<!-- Mirrored from themesbrand.com/skote-django/layouts/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 18 Aug 2022 01:31:45 GMT -->

<head>
    <meta charset="utf-8" />
    <title>Register | Notaris APP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Notaris APP" name="description" />
    <meta content="Notaris APP" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('skote/assets/images/favicon.ico') }}">
    <!-- Bootstrap Css -->
    <link href="{{ asset('skote/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('skote/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('skote/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('skote/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('skote/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('skote/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet">
    <style>
        .input-group .select2-container {
            width: 100% !important;
        }

        .small-text {
            font-size: 0.8em;
            color: red;
        }

        .input-group .select2-selection {
            height: 100% !important;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>

    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Registrasi !</h5>
                                        <p>Pilih Formulir yang mau disi.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('assets/images/logodfidusia-removebg-preview.png') }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="#" class="auth-logo-light">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('skote/assets/images/logo-light.svg') }}" alt=""
                                                class="rounded-circle" width="100">
                                        </span>
                                    </div>
                                </a>

                                <a href="#" class="auth-logo-dark">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets/images/logo_kecil_dfidusia.png') }}" alt=""
                                                class="rounded-circle" width="50">
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="p-2">
                                @if (isset($error))
                                <div class="alert alert-danger" role="alert">
                                    {{ $error }}
                                </div>
                                @endif

                                @if (isset($success))
                                <div class="alert alert-success" role="alert">
                                    {{ $success }}
                                </div>
                                @endif

                                <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="nav-company-tab" data-bs-toggle="tab"
                                            href="#company-tab" role="tab" aria-controls="company-tab"
                                            aria-selected="true">Perusahaan</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="nav-users-tab" data-bs-toggle="tab" href="#users-tab"
                                            role="tab" aria-controls="users-tab" aria-selected="false">User</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="formTabsContent">
                                    <div class="tab-pane fade show active" id="company-tab" role="tabpanel"
                                        aria-labelledby="nav-company-tab">
                                        <!-- Company form -->
                                        <form class="form-horizontal" method="POST" id="company-form"
                                            style="display: none;">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="nama_company" class="form-label">Nama Perusahaan</label>
                                                <input type="text" class="form-control" name="nama_company"
                                                    id="nama_company" placeholder="Enter company name">
                                                <small id="nama_company-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat Perusahaan</label>
                                                <textarea class="form-control" name="alamat" id="alamat"
                                                    placeholder="Enter address"></textarea>
                                                <small id="alamat-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="provinsi" class="form-label">Provinsi</label>
                                                <select class="form-control select2" id="provinsi_company"
                                                    onchange="FormComUser.fetchCities(this.value,'company')">
                                                    <option value="">Pilih Provinsi</option>
                                                </select>
                                                <small id="provinsi-error-text" class="form-text"
                                                    style="color: red;"></small>
                                                <input type="hidden" id="provinsi_name_company"
                                                    name="provinsi_name_company" value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="kota" class="form-label">Kota</label>
                                                <select class="form-control select2" id="kota_company" disabled
                                                    onchange="FormComUser.fetchDistricts(this.value,'company')">
                                                    <option value="">Pilih Kota</option>
                                                </select>
                                                <small id="kota-error-text" class="form-text"
                                                    style="color: red;"></small>
                                                <input type="hidden" id="kota_name_company" name="kota_name_company"
                                                    value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                                <select class="form-control select2" id="kecamatan_company" disabled
                                                    onchange="FormComUser.fetchVillages(this.value,'company')">
                                                    <option value="">Pilih Kecamatan</option>
                                                </select>
                                                <small id="kecamatan-error-text" class="form-text"
                                                    style="color: red;"></small>
                                                <input type="hidden" id="kecamatan_name_company"
                                                    name="kecamatan_name_company" value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="keldesa" class="form-label">Kelurahan/Desa</label>
                                                <select class="form-control select2" id="keldesa_company" disabled>
                                                    <option value="">Pilih Kelurahan/Desa</option>
                                                </select>
                                                <small id="keldesa-error-text" class="form-text"
                                                    style="color: red;"></small>
                                                <input type="hidden" id="keldesa_name_company"
                                                    name="keldesa_name_company" value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="type" class="form-label">Type</label>
                                                <select class="form-control select2" name="type" id="type">
                                                    <option disabled selected value="">--- PILIH ---</option>
                                                    <option value="FINANCE">FINANCE</option>
                                                    <option value="NOTARIS">NOTARIS</option>
                                                    </option>
                                                </select>
                                                <small id="user-type-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            {{-- <div class="mb-3">
                                                <label for="cabang" class="form-label">Cabang Perusahaan</label>
                                                <select class="form-select select2" name="cabang[]" id="cabang"
                                                    aria-label="Cabang Perusahaan">
                                                    <option value="" selected>Pilih Cabang</option>
                                                    @foreach($branch as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_cabang }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <small id="cabang-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div> --}}

                                            <div class="mb-3">
                                                <label for="cabang" class="form-label">Cabang Perusahaan</label>
                                                <select class="form-select select2" name="cabang[]" id="cabang"
                                                    aria-label="Cabang Perusahaan" multiple
                                                    placeholder="Enter telepon perusahaan">
                                                    <option value="" disabled>Pilih Cabang</option>
                                                    @foreach($branch as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_cabang }}</option>
                                                    @endforeach
                                                </select>
                                                <small id="cabang-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="npwp" class="form-label">NPWP</label>
                                                <input type="number" class="form-control" name="npwp" id="npwp"
                                                    placeholder="Enter NPWP">
                                                <small id="npwp-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="no_hp" class="form-label">Nomer Telepon Perusahaan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">+62</span>
                                                    <input type="number" class="form-control" name="no_hp" id="no_hp"
                                                        placeholder="Enter telepon perusahaan">
                                                </div>
                                                <small id="nohp-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="file-input" class="form-label">Upload Data Pendukung</label>
                                                <input type="file" class="form-control" id="file-input" multiple
                                                    placeholder="Upload file kontrak" accept=".doc,.docx,.pdf">
                                                <small id="file-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <ul id="file-list" class="list-group mt-2">

                                            </ul>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    placeholder="Enter email" oninput="validateEmail()">
                                                <small id="email-validation-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mt-3 d-grid">
                                                <button class="btn btn-primary waves-effect waves-light" type="submit"
                                                    id="register-btn" disabled>Register</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="users-tab" role="tabpanel"
                                        aria-labelledby="nav-users-tab">
                                        <!-- Users form -->
                                        <form class="form-horizontal" method="POST" id="user-form"
                                            style="display: none;">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" name="nama_lengkap"
                                                    id="nama_lengkap" placeholder="Enter full name">
                                                <small id="nama_lengkap-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="gelar" class="form-label">
                                                    Gelar
                                                    <span class="small-text">( *jika tidak ada, kosongkan)</span>
                                                </label>
                                                <input type="text" class="form-control" name="gelar" id="gelar"
                                                    placeholder="Enter title ">
                                                <small id="gelar-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tmp_lahir" class="form-label">Tempat Lahir
                                                </label>
                                                <input type="text" class="form-control" name="tmp_lahir" id="tmp_lahir"
                                                    placeholder="Enter Place of birth">
                                                <small id="tmp_lahir-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tgl_lahir" class="form-label">Tanggal Lahir
                                                </label>
                                                <input type="text" class="form-control data-date" name="tgl_lahir"
                                                    id="tgl_lahir" placeholder="Enter Date of birth" readonly>
                                                <small id="tgl_lahir-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="provinsi" class="form-label">Provinsi</label>
                                                <select class="form-control select4" id="provinsi_user"
                                                    onchange="FormComUser.fetchCities(this.value, 'user')">
                                                    <option value="">Pilih Provinsi</option>
                                                </select>
                                                <small id="provinsi-error-text" class="form-text"
                                                    style="color: red;"></small>
                                                <input type="hidden" id="provinsi_name_user" name="provinsi_name_user"
                                                    value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="kota" class="form-label">Kota</label>
                                                <select class="form-control select4" id="kota_user" disabled
                                                    onchange="FormComUser.fetchDistricts(this.value, 'user')">
                                                    <option value="">Pilih Kota</option>
                                                </select>
                                                <small id="kota-error-text" class="form-text"
                                                    style="color: red;"></small>
                                                <input type="hidden" id="kota_name_user" name="kota_name_user" value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                                <select class="form-control select4" id="kecamatan_user" disabled
                                                    onchange="FormComUser.fetchVillages(this.value, 'user')">
                                                    <option value="">Pilih Kecamatan</option>
                                                </select>
                                                <small id="kecamatan-error-text" class="form-text"
                                                    style="color: red;"></small>
                                                <input type="hidden" id="kecamatan_name_user" name="kecamatan_name_user"
                                                    value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="keldesa" class="form-label">Kelurahan/Desa</label>
                                                <select class="form-control select4" id="keldesa_user" disabled>
                                                    <option value="">Pilih Kelurahan/Desa</option>
                                                </select>
                                                <small id="keldesa-error-text" class="form-text"
                                                    style="color: red;"></small>
                                                <input type="hidden" id="keldesa_name_user" name="keldesa_name_user"
                                                    value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="domisili" class="form-label">Domisili
                                                </label>
                                                <input type="text" class="form-control" name="domisili" id="domisili"
                                                    placeholder="Enter Domisili">
                                                <small id="domisili-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="nik" class="form-label">Nomor Induk Kependudukan
                                                    (NIK)</label>
                                                <input type="number" class="form-control" name="nik" id="nik"
                                                    placeholder="Enter NIK">
                                                <small id="nik-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="user-contact" class="form-label">Nomer Handphone</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">+62</span>
                                                    <input type="number" class="form-control" name="user-contact"
                                                        id="user-contact" placeholder="Enter nomer handphone">
                                                </div>
                                                <small id="user-contact-validation-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="userAlamat" class="form-label">Alamat</label>
                                                <textarea class="form-control" name="userAlamat" id="userAlamat"
                                                    placeholder="Enter address"></textarea>
                                                <small id="user-alamat-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="form-group row">
                                                <div class="form-group">
                                                    <label for="company" class="form-label">Perusahaan</label>
                                                    <div class="col-md-10 input-group">
                                                        <select class="form-control select3" name="company"
                                                            id="company">
                                                            <option selected value="">--- PILIH ---</option>
                                                            @foreach ($companys as $company)
                                                            <option value="{{ $company['id'] }}"
                                                                data-type="{{ $company['groupId'] }}"
                                                                data-group-name="{{ strtoupper($company['group_name']) }}"
                                                                data-category="{{ strtolower($company['category']) }}">
                                                                {{ strtoupper($company['nama_company']) }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class=" float-end">
                                                        <label class="form-check-label" for="nonPT">Non PT</label>
                                                        <input type="checkbox" class="form-check-input" id="nonPT"
                                                            name="nonPT">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label for="branch" class="form-label">Cabang Perusahaan</label>
                                                <div class="col-md-10 input-group">
                                                    <select class="form-control select2" name="branch" id="branch">
                                                        <option selected value="">--- PILIH ---</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="userType" class="form-label">Type</label>
                                                <input type="hidden" name="userTypeValue" id="userTypeValue">
                                                <input type="text" class="form-control" id="userType"
                                                    placeholder="Isi kolom Perusahaan terlebih dahulu" readonly>
                                                <small id="userType-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="jabatan" class="form-label">Jabatan</label>
                                                <input type="text" class="form-control" name="jabatan" id="jabatan"
                                                    placeholder="Enter job title">
                                                <small id="jabatan-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username" id="username"
                                                    placeholder="Isi kolom NIK terlebih dahulu" readonly>
                                                <small id="username-error-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="user-email" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="user-email"
                                                    id="user-email" placeholder="Enter email"
                                                    oninput="validateUserEmail()">
                                                <small id="user-email-validation-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control"
                                                        placeholder="Enter password" aria-label="Password"
                                                        aria-describedby="password-addon" name="password" id="password">
                                                    <button class="btn btn-light" type="button"
                                                        id="password-addon-toggle"><i
                                                            class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                                <div class="progress mt-2">
                                                    <div id="password-strength-bar" class="progress-bar"
                                                        role="progressbar" style="width: 0%;"></div>
                                                </div>
                                                <small id="password-strength-text" class="form-text"></small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Password Ulang</label>
                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control"
                                                        placeholder="Enter password again" aria-label="Password"
                                                        aria-describedby="password-confirm-addon"
                                                        name="password_confirmation" id="password_confirmation">
                                                    <button class="btn btn-light" type="button"
                                                        id="password-confirm-addon-toggle"><i
                                                            class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                                <small id="password-match-text" class="form-text"
                                                    style="color: red;"></small>
                                            </div>

                                            <div class="mt-3 d-grid">
                                                <button class="btn btn-primary waves-effect waves-light" type="submit"
                                                    id="user-register-btn" disabled>Register</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ url('login') }}"
                                        style="text-decoration: underline; font-weight: bold;"><i
                                            class="mdi mdi-arrow-left me-1"></i> Back Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <div>©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Dappsolutions.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end account-pages -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('skote/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asseT('skote/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('skote/assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/utils/url.js') }}"></script>
    <!-- Utils js -->
    <script src="{{ asset('assets/utils/message.js') }}"></script>

    <!-- register js -->
    <script src="{{ asset('assets/js/controllers/register.js') }}"></script>

    <script>
        // Pass branch data from Laravel to JavaScript
        let branchData = @json($branch);
    </script>

</body>

<!-- Mirrored from themesbrand.com/skote-django/layouts/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 18 Aug 2022 01:31:45 GMT -->

</html>
