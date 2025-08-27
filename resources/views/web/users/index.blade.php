@if (isset($akses->users))
    @if ($akses->users->view == 1)
        <input type="hidden" id="update" value="{{ $akses->users->update }}">
        <input type="hidden" id="delete" value="{{ $akses->users->delete }}">
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
                                    {{-- <div class="btn-group focus-btn-group"><button type="button" class="btn btn-default"><span
                    class="glyphicon glyphicon-screenshot"></span> Export</button></div> --}}
                                    @if ($akses->users->insert == 1)
                                        <div class="btn-group dropdown-btn-group pull-right"><button type="button"
                                                class="btn btn-success" onclick="Users.add(this, event)"><i class="mdi mdi-plus me-1"></i>Tambah
                                                Data</button>
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
                                        class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Nama Roles</th>
                                                <th>Nik</th>
                                                <th>Nama Lengkap</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" colspan="6">Tidak ada data ditemukan</td>
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
