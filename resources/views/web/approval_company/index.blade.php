@if (isset($akses->perusahaan))

@if ($akses->perusahaan->view == 1)
<input type="hidden" id="delete" value="{{ $akses->perusahaan->update }}">
<input type="hidden" id="approve" value="{{ $akses->perusahaan->update }}">
<input type="hidden" id="user_id" value="{{ $user_id }}">
<input type="hidden" id="name" value="{{ $name }}">

<button type="button" id="confirm-delete-btn" class="" style="display: none;" data-bs-toggle="modal"
    data-bs-target="#konfirmasi-delete"></button>
<div id="content-confirm-delete"></div>


<div class="modal fade" id="modal-file" tabindex="-1" aria-labelledby="modalFilePendukungLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFilePendukungLabel">Upload File Pendukung</h5>
            </div>
            <div class="modal-body" id="modalFilePendukungBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="Company.closeModalFile()">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-rep-plugin">
                    <div class="table-wrapper">
                        <div class="btn-toolbar">
                            <div class="btn-toolbar">
                                {{-- @if ($akses->perusahaan->insert == 1)
                                <div class="btn-group dropdown-btn-group pull-right">
                                    <button type="button" class="btn btn-success" onclick="Company.add(this, event)">
                                        <i class="mdi mdi-plus me-1"></i>Tambah Data
                                    </button>
                                </div>
                                @endif --}}
                            </div>
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
                                        <th>Nama Perusahaan</th>
                                        <th>Jenis Perusahaan</th>
                                        <th>File Pendukung</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" colspan="5">Tidak ada data ditemukan</td>
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
