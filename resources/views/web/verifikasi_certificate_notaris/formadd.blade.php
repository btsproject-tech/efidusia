{{-- {{ dd($data) }} --}}
@php
$data_status = [ 'APPROVE', 'REJECT'];
@endphp
<input type="hidden" id="id" value="{{ isset($id) ? $id : '' }}">
<div class="row mb-4">
    <div class="col-sm-12 text-end">
        <a href="" onclick="RequestCertificateNotaris.cancel(this, event)" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Kembali </a>
    </div>
</div>
<button type="button" id="btn-show-modal" class="" style="display: none;" data-bs-toggle="modal"
    data-bs-target="#data-modal-port"></button>
<div id="content-modal-form"></div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4 class="card-title">Request Sertificate Data</h4>
                    <form>
                        <div class="form-group row ">
                            <label for="no_request" class="col-md-2 col-form-label">No Request</label>
                            <div class="col-md-10">
                                <b>:</b> {{ isset($data->no_request) ? $data->no_request : '' }}
                            </div>
                            <input type="hidden" name="no_request" id="no_request"
                                value="{{ isset($data->id) ? $data->id : '' }}">
                        </div>
                        <div class="form-group row ">
                            <label for="date_request" class="col-md-2 col-form-label">Tanggal Request</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> {{ isset($data->date_request) ? $data->date_request : '' }}
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="remarks" class="col-md-2 col-form-label">Remarks</label>
                            <div class="col-md-10 m-auto">
                                <div>
                                    <b>:</b> {!! isset($data->remarks) ? $data->remarks : '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="creator" class="col-md-2 col-form-label">Requestor</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> {{ isset($data->Creator->name ) ? $data->Creator->name : '' }}
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="status" class="col-md-2 col-form-label">Status</label>
                            <div class="col-md-10 m-auto">
                                <span class="badge
                                @if ($data->status == 'DRAFT')
                                bg-warning
                                @elseif ($data->status == 'REJECT')
                                bg-danger
                                @elseif ($data->status == 'APPROVE')
                                bg-success
                                @elseif ($data->status == 'ON PROCESS')
                                bg-secondary
                                @elseif ($data->status == 'FINISHED')
                                bg-dark
                                @elseif ($data->status == 'COMPLETE')
                                bg-secondary
                                @endif
                                " style="font-size:12px;">{{
                                    $data->status }}
                                </span>
                            </div>
                        </div>

                    </form>
                </div>
                <hr>
                <div class="mb-4"></div>

                {{-- DARI REQUESTOR --}}
                <div>
                    <h4 class="card-title">List Data Kontrak</h4>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group row mb-4">
                                <label for="delegate_to" class="col-md-2 col-form-label">Delegate
                                    To</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <button class="btn btn-outline-primary" type="button" id="button-addon1"
                                            onclick="RequestCertificateNotaris.showDataUserNotaris(this,event)">Pilih</button>
                                        <input readonly id="delegate_to" type="text" class="form-control"
                                            error="Pilih Delegate to" placeholder="Pilih Delegate to"
                                            aria-label="Pilih Delegate to" aria-describedby="button-addon1" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success" type="button"
                                onclick="RequestCertificateNotaris.submitDelegate(this,event)">Submit</button>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                                        <table id="datatable"
                                            class="table table-centered datatable dt-responsive nowrap dataTable no-footer dtr-inline collapsed"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead class="table-light">

                                                <tr>
                                                    <th>No</th>
                                                    <th>Status</th>
                                                    <th>No. Kontrak</th>
                                                    <th>Nama Debitur</th>
                                                    <th>No. Telp</th>
                                                    <th>KTP</th>
                                                    <th>Action</th>
                                                    <th>Check</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data->RequestContract as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        @if ($item->status == 'DRAFT')
                                                        <span class="badge bg-warning">{{ $item->status }}</span>
                                                        @elseif ($item->status == 'REJECT')
                                                        <span class="badge bg-danger">{{ $item->status }}</span>
                                                        @elseif ($item->status == 'APPROVE')
                                                        <span class="badge bg-success">{{ $item->status }}</span>
                                                        @elseif ($item->status == 'ON PROCESS')
                                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                                        @elseif ($item->status == 'FINISHED')
                                                        <span class="badge bg-dark">{{ $item->status }}</span>
                                                        @elseif ($item->status == 'COMPLETE')
                                                        <span class="badge bg-primary">{{ $item->status }}</span>
                                                        @if (session('akses') != 'notaris')
                                                        <br>
                                                        <button type="button"
                                                            class="btn btn-outline-success btn-sm mt-1 waves-effect"
                                                            onclick="RequestCertificateNotaris.showModalMinuta('{{ $item->id }}','{{ $item->contract_number }}')">
                                                            <i class="mdi mdi-plus"></i> Tambahkan Minuta
                                                        </button>
                                                        @endif
                                                        @elseif ($item->status == 'DONE')
                                                        <span class="badge bg-primary">{{ $item->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->contract_number }}</td>
                                                    <td>{{ $item->debitur }}</td>
                                                    <td>{{ $item->no_telp }}</td>
                                                    <td>{{ $item->ktp }}</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-sm btn-success waves-effect"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".bs-example-modal-xl{{ $item->id }}">
                                                            <i class="mdi mdi-eye"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        @if ($item->status == 'DRAFT')
                                                        <input type="checkbox" name="checkbox[]"
                                                            value="{{ $item->id }}">
                                                        @endif
                                                    </td>
                                                </tr>

                                                @include('web.verifikasi_certificate_notaris.modal.detailModal')
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

{{-- @include('web.verifikasi_certificate_notaris.modal.modalMinuta') --}}
