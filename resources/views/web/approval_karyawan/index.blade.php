@if (isset($akses->pengajuan_pengguna))
@if ($akses->pengajuan_pengguna->view == 1)
<input type="hidden" id="approve" value="{{ $akses->pengajuan_pengguna->update }}">
<input type="hidden" id="delete" value="{{ $akses->pengajuan_pengguna->update }}">
<input type="hidden" id="user_id" value="{{ $user_id }}">
<input type="hidden" id="name" value="{{ $name }}">
<button type="button" id="confirm-delete-btn" class="" style="display: none;" data-bs-toggle="modal"
    data-bs-target="#konfirmasi-delete"></button>
<div id="content-confirm-delete"></div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-rep-plugin">
                    <div class="table-wrapper">
                        <div class="btn-toolbar">
                            @if ($akses->pengajuan_pengguna->insert == 1)
                            <div class="btn-group dropdown-btn-group pull-right">
                                <button type="button" class="btn btn-info me-1"
                                    onclick="Karyawan.downloadFile(this, event)">
                                    <i class="mdi mdi-download me-1"></i>Template
                                </button>
                                <button type="button" class="btn btn-success" onclick="Karyawan.modal(this, event)">
                                    <i class="mdi mdi-plus me-1"></i>Tambah Data
                                </button>
                            </div>
                            @endif
                        </div>

                        <br>
                        <br>

                        <ul class="nav nav-tabs nav-tabs-custom mb-4">
                            <li class="nav-item">
                                <a class="nav-link fw-bold p-3 active" href="#">Daftar
                                    {{ $title }}</a>
                            </li>
                        </ul>

                        <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <table id="table-data"
                                class="table table-centered datatable dt-responsive nowrap dataTable no-footer dtr-inline collapsed"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Nik</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" colspan="4">Tidak ada data ditemukan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@else
@include('web.alert.message')
@endif
@else
@include('web.alert.message')
@endif
