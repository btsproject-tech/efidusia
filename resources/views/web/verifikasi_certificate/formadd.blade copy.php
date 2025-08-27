{{-- {{ dd($data) }} --}}
@php
$data_status = [ 'APPROVE', 'REJECT'];
@endphp
<input type="hidden" id="id" value="{{ isset($id) ? $id : '' }}">
<div class="row mb-4">
    <div class="col-sm-12 text-end">
        <a href="" onclick="VerifikasiCertificate.cancel(this, event)" class="btn btn-secondary">
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
                                @elseif ($data->status == 'DONE')
                                bg-primary
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
                                            onclick="VerifikasiCertificate.showDataUserNotaris(this,event)">Pilih</button>
                                        <input readonly id="delegate_to" type="text" class="form-control"
                                            error="Pilih Delegate to" placeholder="Pilih Delegate to"
                                            aria-label="Pilih Delegate to" aria-describedby="button-addon1" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-success" type="button"
                                onclick="VerifikasiCertificate.submitDelegate(this,event)">Submit</button>
                        </div>
                        @php
                            $data_status = [];
                            $data_sk = [];
                            $data_terkirim = [];
                        @endphp
                        @foreach ($data->RequestContract as $i)
                            @php
                                $data_status[] = $i->status;
                                if ($i->no_sk != null) {
                                    $data_sk[] = $i->no_sk;
                                } elseif ($i->flag_notif != null) {
                                    $data_terkirim[] = $i->flag_notif;
                                }
                            @endphp
                        @endforeach

                        {{-- jika didalam $data_status itu APPROVE semua dan $data_sk itu jumlah nya sama dengan $data_status --}}
                        @if (in_array('APPROVE', $data_status) && count($data_terkirim) == 0)
                        <div class="col-3">
                            <button class="btn btn-info float-end" type="button" onclick="VerifikasiCertificate.sendNotifikasi(this,event)">
                                <i class="mdi mdi-send"></i> Tagihkan ke notaris
                            </button>
                        </div>

                        @endif
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
                                                        @elseif ($item->status == 'DONE BY NOTARIS')
                                                        <span class="badge bg-primary">{{ $item->status }}</span>
                                                        @elseif ($item->status == 'DONE')
                                                        <span class="badge bg-primary">{{ $item->status }}</span>
                                                        @endif

                                                        @if ($item->request_sertificate_notaris != null)
                                                        <br>
                                                        <i class="mdi mdi-recycle"></i>
                                                        {{ $item->UserDelegate->CompanyKaryawan->nama_company }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->contract_number }}</td>
                                                    <td>{{ $item->debitur }}</td>
                                                    <td>{{ $item->no_telp }}</td>
                                                    <td>{{ $item->ktp }}</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-sm btn-info waves-effect m-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".bs-example-modal-xl{{ $item->id }}">
                                                            <i class="mdi mdi-eye"></i>
                                                        </button>
                                                        @if (($item->status == 'ON PROCESS' &&
                                                        $item->status_dari_notaris != null)|| $item->status ==
                                                        'APPROVE')
                                                            @if ($item->no_sk == null )
                                                            <button type="button"
                                                                class="btn btn-{{ $item->status == 'APPROVE' ? 'success' : 'secondary' }} btn-sm waves-effect"
                                                                data-bs-toggle="modal"
                                                                data-bs-target=".modalSK{{ $item->id }}">
                                                                <i class="mdi mdi-plus-outline"></i> Upload SK
                                                            </button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->status == 'DRAFT')
                                                        <input type="checkbox" class="form-check-input"
                                                            name="checkbox[]" value="{{ $item->id }}">
                                                        @endif
                                                    </td>
                                                </tr>

                                                @include('web.verifikasi_certificate.modal.detailModal')
                                                @include('web.verifikasi_certificate.modal.modalAhu')
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
