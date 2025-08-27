@php
$data_status = [ 'APPROVE', 'REJECT'];
$data_status_item = ['APPROVE', 'REJECT','OUTSTANDING','COMPLETE','DONE','FINISHED'];
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
                    <div>
                        <div class="form-group row ">
                            <label for="no_request" class="col-md-2 col-form-label">No Request</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> {{ isset($data->no_request) ? $data->no_request : '' }}
                                <input type="hidden" name="no_request" id="no_request"
                                    value="{{ isset($data->no_request) ? $data->no_request : '' }}">
                            </div>
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
                                @elseif ($data->status == 'COMPLETE')
                                bg-secondary
                                @elseif ($data->status == 'FINISHED')
                                bg-dark
                                @elseif ($data->status == 'DONE')
                                bg-primary
                                @endif
                                " style="font-size:12px;">{{
                                    $data->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4"></div>

                {{-- DARI REQUESTOR --}}
                <div>
                    <h4 class="card-title">List Data Kontrak</h4>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <input type="text" class="form-control" name="search_item_no_contract"
                                    id="search_item_no_contract" placeholder="Search By No. Kontrak...">
                            </div>
                            <div class="col-4">
                                <select name="search_item_status" class="form-select select2" id="search_item_status">
                                    <option value="">All Status</option>
                                    @foreach ($data_status_item as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-primary" onclick="VerifikasiCertificate.searchItem(this, event)">
                                    <i class="mdi mdi-magnify me-1"></i> Search
                                </button>
                                <button class="btn btn-secondary"
                                    onclick="VerifikasiCertificate.searchReset(this, event)">
                                    <i class="mdi mdi-refresh me-1"></i> Reset
                                </button>
                                <button type="button" class="btn btn-success"
                                    onclick="VerifikasiCertificate.sendNotifikasi(this, event)">
                                    <i class="mdi mdi-phone"></i> Send Notifikasi
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <br>
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    @foreach ($data->RequestContract as $item)
                                    @include('web.verifikasi_certificate.modal.detailModal')
                                    <div class="accordion-item" id="content-save{{ $item->contract_number }}">
                                        <h2 class="accordion-header" id="flush-heading{{ $loop->iteration }}">
                                            <button class="accordion-button fw-medium collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse{{ $loop->iteration }}"
                                                aria-expanded="true"
                                                aria-controls="flush-collapse{{ $loop->iteration }}">
                                                #{{ $loop->iteration }} / &nbsp
                                                No. Kontrak : {{ $item->contract_number }} / &nbsp
                                                <span class="badge
                                                @if ($item->status == 'DRAFT')
                                                bg-warning
                                                @elseif ($item->status == 'REJECT')
                                                bg-danger
                                                @elseif ($item->status == 'APPROVE')
                                                bg-success
                                                @elseif ($item->status == 'DONE')
                                                bg-primary
                                                @elseif ($item->status == 'COMPLETE')
                                                bg-secondary
                                                @elseif ($item->status == 'FINISHED')
                                                bg-dark
                                                @endif
                                                " style="font-size:12px;">{{
                                                    $item->status }}
                                                </span>
                                                @if ($item->status == 'APPROVE' && $item->flag_notif != null)
                                                &nbsp / &nbsp
                                                ✅
                                                @endif
                                            </button>
                                        </h2>
                                        <div id="flush-collapse{{ $loop->iteration }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="flush-heading{{ $loop->iteration }}"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div>
                                                    <form>
                                                        <input type="hidden" name="id_item"
                                                            id="id_item{{ $item->contract_number }}"
                                                            value="{{ $item->id }}">
                                                        <button type="button"
                                                            class="btn btn-sm btn-success waves-effect mb-4"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".bs-example-modal-xl{{ $item->id }}">
                                                            <i class="mdi mdi-eye"></i> Lihat Detail Data
                                                        </button>
                                                    </form>
                                                </div>
                                                {{-- FORM --}}
                                                @if ($item->status == 'DRAFT')
                                                <div>
                                                    <h4 class="card-title">Contract Data Certificate</h4>
                                                    <div>
                                                        <input type="hidden" name="no_request"
                                                            id="no_request{{ $item->contract_number }}"
                                                            value="{{ isset($data->no_request) ? $data->no_request : '' }}">
                                                        <div class="form-group row mb-4">
                                                            <label for="delegate_to"
                                                                class="col-md-2 col-form-label">Delegate
                                                                To</label>
                                                            <div class="col-md-10">
                                                                <div class="input-group">
                                                                    <button class="btn btn-outline-primary"
                                                                        type="button" id="button-addon1"
                                                                        onclick="VerifikasiCertificate.showDataUserNotaris(this,'{{ $item->contract_number }}')">Pilih</button>
                                                                    <input readonly
                                                                        id_item="{{ $item->contract_number }}"
                                                                        id="delegate_to{{ $item->contract_number }}"
                                                                        type="text" class="form-control required"
                                                                        error="Pilih Delegate to"
                                                                        placeholder="Pilih Delegate to"
                                                                        aria-label="Pilih Delegate to"
                                                                        aria-describedby="button-addon1"
                                                                        value="{{ isset($item->delegate_to) ? $item->delegate_to .' - ' .$item->UserDelegate->nama_lengkap : '' }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-4">
                                                            <label for="date_delegate{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Date
                                                                Delegate</label>
                                                            <div class="col-md-10">
                                                                <input type="text"
                                                                    class="form-control required data-date"
                                                                    id="date_delegate{{ $item->contract_number }}"
                                                                    placeholder="Date Delegate" error="Date Delegate"
                                                                    readonly
                                                                    value="{{ isset($item->date_delegate ) ? $item->date_delegate  : '' }}">
                                                                <input type="hidden" class="form-control "
                                                                    id="updater{{ $item->contract_number }}"
                                                                    placeholder="updater"
                                                                    value="{{ isset($item->updater) ? $item->updater : session('user_id') }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-4">
                                                            <label for="seq_numbers{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">No. Minuta</label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control required"
                                                                    id="seq_numbers{{ $item->contract_number }}"
                                                                    error="No. Minuta" placeholder="No. Minuta"
                                                                    value="{{ isset($item->seq_number ) ? $item->seq_number  : '' }}">

                                                            </div>
                                                        </div>

                                                        {{-- FORM SELECT --}}
                                                        <div class="form-group row mb-4">
                                                            <label for="status_{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Status</label>
                                                            <div class="col-md-10">
                                                                <select
                                                                    onchange="VerifikasiCertificate.changeStatus(this, '{{ $item->contract_number }}')"
                                                                    class="form-select select2 required" error="Status"
                                                                    id="status_{{ $item->contract_number }}"
                                                                    id_item="{{ $item->contract_number }}">
                                                                    <option value="">Pilih Status</option>
                                                                    @foreach ($data_status as $i)
                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="content_remarks_verify{{ $item->contract_number }}">
                                                        </div>

                                                        <div class="form-group row mb-4">
                                                            <label for="billing_number_ahu{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Billing
                                                                Number AHU</label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control required"
                                                                    id="billing_number_ahu{{ $item->contract_number }}"
                                                                    placeholder="Billing Number AHU"
                                                                    error="Billing Number AHU"
                                                                    value="{{ isset($item->billing_number_ahu ) ? $item->billing_number_ahu  : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-4">
                                                            <label for="date_input_ahu{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Date
                                                                Input
                                                                AHU</label>
                                                            <div class="col-md-10">
                                                                <input class="form-control required"
                                                                    type="datetime-local"
                                                                    id="date_input_ahu{{ $item->contract_number }}"
                                                                    placeholder="Date Input AHU" error="Date Input AHU"
                                                                    value="{{ isset($item->date_input_ahu ) ? $item->date_input_ahu : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-4">
                                                            <label for="name_pnbp{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Nama PNPB
                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control required"
                                                                    id="name_pnbp{{ $item->contract_number }}"
                                                                    placeholder="Nama PNPB" error="Name PNPB"
                                                                    value="{{ isset($item->name_pnbp ) ? $item->name_pnbp : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-4">
                                                            <label for="hutang_barang{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">
                                                                Nilai Barang
                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control required"
                                                                    id="hutang_barang{{ $item->contract_number }}"
                                                                    placeholder="Nilai Barang" error="Nilai Barang"
                                                                    value="{{ isset($item->hutang_barang ) ? $item->hutang_barang : '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-4">
                                                            <label for="biaya_pnbp{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">
                                                                Biaya PNBP
                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control required"
                                                                    id="biaya_pnbp{{ $item->contract_number }}"
                                                                    placeholder="Biaya PNPB" error="Biaya PNPB"
                                                                    value="{{ isset($item->hutang_barang ) ? cari_biaya_barang($item->hutang_barang) : '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- SUBMIT --}}
                                                <div class="text-end">
                                                    <button type="button"
                                                        onclick="VerifikasiCertificate.submit(this,'{{ $item->contract_number }}')"
                                                        class="btn btn-success">
                                                        <i class="mdi mdi-check me-1"></i> Submit</button>
                                                </div>
                                                {{-- END FORM --}}
                                                @else
                                                <div>
                                                    <h4 class="card-title">Contract Data Certificate</h4>
                                                    <div>
                                                        <div class="form-group row">
                                                            <label for="delegate_to"
                                                                class="col-md-2 col-form-label">Delegate
                                                                To</label>
                                                            <div class="col-md-10 m-auto">
                                                                <b>:</b> {{ isset($item->delegate_to) ?
                                                                $item->delegate_to .' - '
                                                                .$item->UserDelegate->nama_lengkap : '' }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="date_delegate{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Date
                                                                Delegate</label>
                                                            <div class="col-md-10 m-auto">
                                                                <b>:</b> {{ isset($item->date_delegate ) ?
                                                                $item->date_delegate : '' }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="seq_numbers{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Seq
                                                                Number</label>
                                                            <div class="col-md-10 m-auto">
                                                                <b>:</b> {{ isset($item->seq_number ) ?
                                                                $item->seq_number : '' }}

                                                            </div>
                                                        </div>

                                                        {{-- FORM SELECT --}}
                                                        <div class="form-group row">
                                                            <label for="status_{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Status</label>
                                                            <div class="col-md-10 m-auto">
                                                                <b>:</b> <span class="badge
                                                                @if ($item->status == 'DRAFT')
                                                                bg-warning
                                                                @elseif ($item->status == 'REJECT')
                                                                bg-danger
                                                                @elseif ($item->status == 'APPROVE')
                                                                bg-success
                                                                @elseif ($item->status == 'DONE')
                                                                bg-primary
                                                                @elseif ($item->status == 'COMPLETE')
                                                                bg-secondary
                                                                @elseif ($item->status == 'FINISHED')
                                                                bg-dark
                                                                @endif
                                                                " style="font-size:12px;">{{
                                                                    $item->status }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="remarks_verify{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Remarks
                                                                Verify</label>
                                                            <div class="col-md-10 m-auto">
                                                                <b>:</b> {{ isset($item->remarks_verify ) ?
                                                                $item->remarks_verify : '' }}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="billing_number_ahu{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Billing
                                                                Number AHU</label>
                                                            <div class="col-md-10 m-auto">
                                                                <b>:</b> {{ isset($item->billing_number_ahu ) ?
                                                                $item->billing_number_ahu : '' }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="date_input_ahu{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Date
                                                                Input
                                                                AHU</label>
                                                            <div class="col-md-10 m-auto">
                                                                <b>:</b> {{ isset($item->date_input_ahu ) ?
                                                                $item->date_input_ahu : '' }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="name_pnbp{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Nama PNPB
                                                            </label>
                                                            <div class="col-md-10 m-auto">
                                                                <b>:</b> {{ isset($item->name_pnbp ) ?
                                                                $item->name_pnbp : '' }}
                                                            </div>
                                                        </div>

                                                        {{-- FORM FILE SERTIFICATE --}}
                                                        @if ($item->status == 'APPROVE' || $item->status == 'DONE')
                                                        <div class="form-group row">
                                                            <label for="sertificate_file{{ $item->contract_number }}"
                                                                class="col-md-2 col-form-label">Upload Sertificate
                                                                File
                                                            </label>
                                                            <div class="col-md-10 m-auto">
                                                                @if (isset($item->sertificate_file) &&
                                                                $item->sertificate_file != null)
                                                                <a href="#"
                                                                    onclick="return VerifikasiCertificate.confirmDownload('{{ $item->sertificate_file }}','{{ $item->sertificate_path . $item->sertificate_file }}')">
                                                                    {{ $item->sertificate_file }}</a>
                                                                @else
                                                                <div class="input-group">
                                                                    <input id="file{{ $item->contract_number }}"
                                                                        type="text" readonly
                                                                        class="form-control required"
                                                                        placeholder="Pilih Data File"
                                                                        aria-label="Pilih Data File" src=""
                                                                        error="Data File"
                                                                        aria-describedby="button-addon1" value="">
                                                                    <button class="btn btn-outline-secondary"
                                                                        type="button" id="button-addon1"
                                                                        onclick="VerifikasiCertificate.addFile(this, '{{ $item->contract_number }}')">Choose
                                                                        File</button>
                                                                </div>
                                                                <br>
                                                                <div class="text-end mb-4">
                                                                    <button type="button"
                                                                        onclick="VerifikasiCertificate.submit(this,'{{ $item->contract_number }}')"
                                                                        class="btn btn-success">
                                                                        <i class="mdi mdi-check me-1"></i>
                                                                        Submit</button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif

                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
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
