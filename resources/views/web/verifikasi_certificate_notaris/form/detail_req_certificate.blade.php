{{-- {{ dd($data->RequestContract) }} --}}
@php
$data_billId = [];
$status_pembayaran = [];
foreach ($data->RequestContract as $key => $value) {
    if ($value->billId != null) {
        $data_billId[] = $value->billId;
        $status_pembayaran[]= $value->status_pembayaran;
    }
}



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
                                <b>:</b> {{ isset($data->DataRequestCertificate->no_request) ?
                                $data->DataRequestCertificate->no_request : '' }}
                                <input type="hidden" name="idr_notaris" id="idr_notaris" value="{{ isset($data->id) ?
                                $data->id : '' }}">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="date_request" class="col-md-2 col-form-label">Tanggal Request</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> {{ isset($data->DataRequestCertificate->date_request) ?
                                $data->DataRequestCertificate->date_request : '' }}
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="remarks" class="col-md-2 col-form-label">Remarks</label>
                            <div class="col-md-10 m-auto">
                                <div>
                                    <b>:</b> {!! isset($data->DataRequestCertificate->remarks) ?
                                    $data->DataRequestCertificate->remarks : '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="creator" class="col-md-2 col-form-label">Requestor</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> {{ isset($data->DataRequestCertificate->Creator->name ) ?
                                $data->DataRequestCertificate->Creator->name : '' }}
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
                                @elseif ($data->status == 'DONE')
                                bg-primary
                                @endif
                                " style="font-size:12px;">{{
                                    $data->status }}
                                </span>
                            </div>
                        </div>
                        <div class="form-group row" id="generate-number">
                            <label for="creator" class="col-md-2 col-form-label">Generate No. Minuta</label>
                            <div class="col-md-2 m-auto">
                                <input type="number" class="form-control required" name="no_notaris" id="no_notaris"
                                    error="No Notaris" placeholder="(Notaris) Exp: 1">
                                <input type="hidden" class="form-control" name="idr_notaris" id="idr_notaris"
                                    value="{{ $data->id }}">
                            </div>
                            <div class="col-md-2 m-auto">
                                <input type="time" class="form-control required" name="waktu" id="waktu" error="Waktu">
                            </div>
                            <div class="col-md-2 m-auto">
                                <input type="number" class="form-control required" name="waktu_jeda" id="waktu_jeda"
                                    error="Waktu Jeda" placeholder="(Menit) Exp: 1">
                            </div>
                            <div class="col-md-4 m-auto">
                                <button class="btn btn-primary {{ $data->status == 'DONE' ? 'disabled' : '' }}"
                                    type="button"
                                    onclick="RequestCertificateNotaris.inputNoNotaris(this,event)">Generate
                                </button>
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
                        <div class="col-12 text-end">
                            <button class="btn btn-primary {{ $data->status == 'DONE' ? 'disabled' : '' }}"
                                type="button" onclick="RequestCertificateNotaris.sendNotifikasi(this,event)">
                                <i class="mdi mdi-check"></i> Pekerjaan Selesai
                            </button>
                            @if ($data->status == 'DONE' && $data_billId != null)
                            <button class="btn btn-info {{ in_array('DONE', $status_pembayaran) ? 'disabled' : '' }}"
                                type="button" onclick="RequestCertificateNotaris.konfirmasiPembayaran(this,event)">
                                <i class="mdi mdi-check"></i> Konfirmasi Pembayaran
                            </button>
                            @endif
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
                                                    <th>
                                                        No. Minuta
                                                        <br>
                                                        Waktu
                                                    </th>
                                                    <th>No. Kontrak</th>
                                                    <th>Nama Debitur</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Action</th>
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
                                                        @elseif ($item->status == 'DONE')
                                                        <span class="badge bg-primary">{{ $item->status }}</span>
                                                        @elseif ($item->status == 'DONE BY NOTARIS')
                                                        <span class="badge bg-primary">{{ $item->status }}</span>
                                                        @endif

                                                        @if ($item->seq_number != null && $data->status != 'DONE')
                                                        <br>
                                                        <button type="button"
                                                            class="btn btn-sm btn-warning waves-effect text-black"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".bs-example-modal-lg{{ $item->id }}">
                                                            <i class="mdi mdi-plus"></i>
                                                            Add akta sela
                                                        </button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $item->seq_number }}
                                                        <br>
                                                        {{ formatTanggalWaktu($item->waktu_tgl_notaris) }}
                                                    </td>
                                                    <td>{{ $item->contract_number }}</td>
                                                    <td>{{ $item->debitur }}</td>
                                                    <td>{{ $item->jenis_kelamin }}</td>

                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-sm btn-success waves-effect"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".bs-example-modal-xl{{ $item->id }}">
                                                            <i class="mdi mdi-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                @include('web.verifikasi_certificate_notaris.modal.detailModal')
                                                @include('web.verifikasi_certificate_notaris.modal.aktaSelaModal')
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
