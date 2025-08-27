<div class="modal fade bs-example-modal-lg modalSK{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalSk"
    aria-hidden="true" id="modal-warkah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content content-save{{ $item->id }}">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSk">
                    Insert SK di No. Kontrak # {{ $item->contract_number}}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body{{ $item->id }}">
                <!-- Nav tabs -->
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link
                        {{ $item->status == 'ON PROCESS' ? 'active' : '' }}
                        " data-bs-toggle="tab" href="#home-{{ $item->id }}" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span class="d-none d-sm-block">Lengkapi AHU</span>
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link
                        @if (
                        $item->status == 'ON PROCESS' ||
                        $item->date_input_ahu  == null ||
                        $item->name_pnbp  == null ||
                        $item->billing_number_ahu  == null
                        )
                            disabled

                            @elseif ($item->status == 'APPROVE' )
                            active
                        @endif
                        " data-bs-toggle="tab" href="#profile-{{ $item->id }}" role="tab">
                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                            <span class="d-none d-sm-block">Upload SK</span>
                        </a>
                    </li>

                </ul>
                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane
                    {{ $item->status == 'ON PROCESS' ? 'active' : '' }}
                    " id="home-{{ $item->id }}" role="tabpanel">
                        <input type="hidden" name="id_item" id="id_item{{ $item->id }}" value="{{ $item->id }}">
                        <input type="hidden" name="no_request" id="no_request{{ $item->id }}"
                            value="{{ isset($data->no_request) ? $data->no_request : '' }}">

                        <div class="form-group row mb-4">
                            <label for="date_input_ahu{{ $item->id }}" class="col-md-2 col-form-label">Date
                                Input
                                AHU</label>
                            <div class="col-md-10">
                                <input class="form-control required" type="datetime-local"
                                    id="date_input_ahu{{ $item->id }}" placeholder="Date Input AHU"
                                    error="Date Input AHU"
                                    value="{{ isset($item->date_input_ahu ) ? $item->date_input_ahu : '' }}"
                                    @if($item->status == 'APPROVE')
                                readonly
                                @endif>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="name_pnbp{{ $item->id }}" class="col-md-2 col-form-label">Nama PNPB
                            </label>
                            <div class="col-md-10">
                                <input type="text" class="form-control required" id="name_pnbp{{ $item->id }}"
                                    placeholder="Nama PNPB" error="Name PNPB"
                                    value="{{ isset($item->name_pnbp ) ? $item->name_pnbp : '' }}" @if ($item->status ==
                                'APPROVE') readonly @endif >
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="billing_number_ahu{{ $item->id }}" class="col-md-2 col-form-label">Billing
                                Number AHU</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control required" id="billing_number_ahu{{ $item->id }}"
                                    placeholder="Billing Number AHU" error="Billing Number AHU"
                                    value="{{ isset($item->billing_number_ahu ) ? $item->billing_number_ahu  : '' }}"
                                    @if ($item->status == 'APPROVE') readonly @endif>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="biaya_pnbp{{ $item->contract_number }}" class="col-md-2 col-form-label">
                                Biaya PNBP (Rp.)
                            </label>
                            <div class="col-md-10">
                                <input type="text" class="form-control required"
                                    id="biaya_pnbp{{ $item->contract_number }}" placeholder="Biaya PNPB"
                                    error="Biaya PNPB"
                                    value="{{ isset($item->hutang_barang ) ? format_rp(cari_biaya_barang($item->hutang_barang)) : '' }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    @if ($item->status == 'APPROVE')
                    <div class="tab-pane
                    {{ $item->status == 'APPROVE' ? 'active' : '' }}
                    " id="profile-{{ $item->id }}" role="tabpanel">
                        {{-- UPLOAD SK --}}
                        <div class="form-group row mb-4">
                            <label for="sertificate_file{{ $item->id }}" class="col-md-2 col-form-label">Upload
                                Sertificate
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
                                    <button class="btn btn-outline-secondary" type="button" id="button-addon1"
                                        onclick="VerifikasiCertificate.addFile(this, '{{ $item->id }}')">Choose
                                        File</button>
                                    <input id="file{{ $item->id }}" type="text" readonly class="form-control required"
                                        placeholder="Pilih Data File" aria-label="Pilih Data File" src=""
                                        error="Data File" aria-describedby="button-addon1" value="">
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="no_sk{{ $item->id }}" class="col-md-2 col-form-label">Nomor SK</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control required" id="no_sk{{ $item->id }}"
                                    placeholder="No SK" error="No SK"
                                    value="{{ isset($item->no_sk ) ? $item->no_sk  : '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="no_akta{{ $item->id }}" class="col-md-2 col-form-label">Nomor Akta</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control required" id="no_akta{{ $item->id }}"
                                    placeholder="No Akta" error="No Akta"
                                    value="{{ isset($item->no_akta ) ? $item->no_akta  : '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="tanggal_akta{{ $item->id }}" class="col-md-2 col-form-label">Tanggal Akta</label>
                            <div class="col-md-10">
                                <input type="date" class="form-control required" id="tanggal_akta{{ $item->id }}"
                                    placeholder="Tanggal Akta" error="Tanggal Akta"
                                    value="{{ isset($item->tanggal_akta ) ? $item->tanggal_akta  : '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="tanggal_sertifikat{{ $item->id }}" class="col-md-2 col-form-label">Tanggal Sertifikat</label>
                            <div class="col-md-10">
                                <input type="date" class="form-control required" id="tanggal_sertifikat{{ $item->id }}"
                                    placeholder="Tanggal Sertifikat" error="Tanggal Sertifikat"
                                    value="{{ isset($item->tanggal_sertifikat ) ? $item->tanggal_sertifikat  : '' }}">
                            </div>
                        </div>
                        {{-- END UPLOAD SK --}}
                    </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @if ($item->status == 'ON PROCESS' || $item->status == 'APPROVE')
                    <button type="button" class="btn btn-primary"
                        onclick="VerifikasiCertificate.submit(this,'{{ $item->id }}')">Submit</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
