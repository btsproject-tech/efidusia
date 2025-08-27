{{-- {{ dd($item->DataWarkah ) }} --}}
<div class="modal fade bs-example-modal-lg{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalWarkah"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content content-save-warkah">
            <div class="modal-header">
                <h5 class="modal-title" id="modalWarkah">
                    <strong>Warkah</strong>
                    <span class="badge bg-info">
                        <i class="mdi mdi-account"></i>
                        {{ $item->debitur }}
                    </span>
                    <span class="badge bg-light text-info">
                        {{ $item->contract_number }}
                    </span>
                    <span class="badge bg-warning">
                        <i class="mdi mdi-account-circle"></i>
                        {{ $item->UserDelegate->nama_lengkap }}
                    </span>
                    <span class="badge bg-light text-warning">
                        <i class="mdi mdi-account-circle"></i>
                        {{ $item->seq_number }}
                    </span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="id_item" id="id_item{{ $item->id }}"
                            value="{{ $item->id }}">
                        <div class="accordion" id="accordionExample{{ $item->id }}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="perjanjian_pembiayaan{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $item->id }}"
                                        aria-expanded="false" aria-controls="collapseOne{{ $item->id }}">
                                        <strong>Perjanjian Pembiayaan</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->perjanjian_pembiayaan_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseOne{{ $item->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="perjanjian_pembiayaan{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->perjanjian_pembiayaan_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'perjanjian_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="perjanjian_{{ $item->id }}" type="text"
                                                            readonly class="form-control" placeholder="Pilih Data File"
                                                            aria-label="Pilih Data File" src=""
                                                            error="Data File" aria-describedby="button-addon1"
                                                            value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->perjanjian_pembiayaan_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->perjanjian_pembiayaan_path . $item->DataWarkah->perjanjian_pembiayaan_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->perjanjian_pembiayaan_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="SKMJF{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo{{ $item->id }}"
                                        aria-expanded="false" aria-controls="collapseTwo{{ $item->id }}">
                                        <strong>SKMJF</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->skmjf_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseTwo{{ $item->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="SKMJF{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->skmjf_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'skmjf_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="skmjf_{{ $item->id }}" type="text" readonly
                                                            class="form-control" placeholder="Pilih Data File"
                                                            aria-label="Pilih Data File" src=""
                                                            error="Data File" aria-describedby="button-addon1"
                                                            value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->skmjf_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="skmjf_{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->skmjf_path . $item->DataWarkah->skmjf_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->skmjf_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                        aria-expanded="false" aria-controls="collapseThree">
                                        <strong>Data Kendaraan</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->data_kendaraan_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->data_kendaraan_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'data_kendaraan_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="data_kendaraan_{{ $item->id }}" type="text"
                                                            readonly class="form-control"
                                                            placeholder="Pilih Data File" aria-label="Pilih Data File"
                                                            src="" error="Data File"
                                                            aria-describedby="button-addon1" value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->data_kendaraan_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="data_kendaraan_{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->data_kendaraan_path . $item->DataWarkah->data_kendaraan_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->data_kendaraan_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="Four{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                        aria-expanded="false" aria-controls="collapseFour">
                                        <strong>Kartu Keluarga</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->kk_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse"
                                    aria-labelledby="Four{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->kk_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'kk_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="kk_{{ $item->id }}" type="text" readonly
                                                            class="form-control" placeholder="Pilih Data File"
                                                            aria-label="Pilih Data File" src=""
                                                            error="Data File" aria-describedby="button-addon1"
                                                            value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->kk_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="kk_{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->kk_path . $item->DataWarkah->kk_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->kk_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="Five{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                        aria-expanded="false" aria-controls="collapseFive">
                                        <strong>KTP Nama BPKB</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->ktp_bpkb_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse"
                                    aria-labelledby="Five{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->ktp_bpkb_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'ktp_bpkb_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="ktp_bpkb_{{ $item->id }}" type="text"
                                                            readonly class="form-control"
                                                            placeholder="Pilih Data File" aria-label="Pilih Data File"
                                                            src="" error="Data File"
                                                            aria-describedby="button-addon1" value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->ktp_bpkb_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="ktp_bpkb_{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->ktp_bpkb_path . $item->DataWarkah->ktp_bpkb_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->ktp_bpkb_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="Six{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false"
                                        aria-controls="collapseSix">
                                        <strong>KTP Debitur</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->ktp_debitur_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse"
                                    aria-labelledby="Six{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->ktp_debitur_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'ktp_debitur_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="ktp_debitur_{{ $item->id }}" type="text"
                                                            readonly class="form-control"
                                                            placeholder="Pilih Data File" aria-label="Pilih Data File"
                                                            src="" error="Data File"
                                                            aria-describedby="button-addon1" value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->ktp_debitur_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="ktp_debitur_{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->ktp_debitur_path . $item->DataWarkah->ktp_debitur_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->ktp_debitur_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="Seven{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseSeven"
                                        aria-expanded="false" aria-controls="collapseSeven">
                                        <strong>Form Perjanjian Nama BPKB</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->form_perjanjian_nama_bpkb_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse"
                                    aria-labelledby="Seven{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->form_perjanjian_nama_bpkb_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'form_perjanjian_nama_bpkb_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="form_perjanjian_nama_bpkb_{{ $item->id }}"
                                                            type="text" readonly class="form-control"
                                                            placeholder="Pilih Data File" aria-label="Pilih Data File"
                                                            src="" error="Data File"
                                                            aria-describedby="button-addon1" value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->form_perjanjian_nama_bpkb_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="form_perjanjian_nama_bpkb_{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->form_perjanjian_nama_bpkb_path . $item->DataWarkah->form_perjanjian_nama_bpkb_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->form_perjanjian_nama_bpkb_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="Eight{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseEight"
                                        aria-expanded="false" aria-controls="collapseEight">
                                        <strong>KTP Pasangan Nama BPKB</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->ktp_pasangan_nama_bpkp_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseEight" class="accordion-collapse collapse"
                                    aria-labelledby="Eight{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->ktp_pasangan_nama_bpkp_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'ktp_pasangan_nama_bpkp_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="ktp_pasangan_nama_bpkp_{{ $item->id }}"
                                                            type="text" readonly class="form-control"
                                                            placeholder="Pilih Data File" aria-label="Pilih Data File"
                                                            src="" error="Data File"
                                                            aria-describedby="button-addon1" value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->ktp_pasangan_nama_bpkp_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="ktp_pasangan_nama_bpkp_{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->ktp_pasangan_nama_bpkp_path . $item->DataWarkah->ktp_pasangan_nama_bpkp_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->ktp_pasangan_nama_bpkp_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="Nine{{ $item->id }}">
                                    <button class="accordion-button fw-medium collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseNine"
                                        aria-expanded="false" aria-controls="collapseNine">
                                        <strong>KTP Pasangan Debitur</strong>
                                        @if ($item->DataWarkah != null && $item->DataWarkah->ktp_pasangan_debitur_file != null)
                                            &nbsp; <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseNine" class="accordion-collapse collapse"
                                    aria-labelledby="Nine{{ $item->id }}"
                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                    <div class="accordion-body">
                                        @if ($item->DataWarkah == null || $item->DataWarkah->ktp_pasangan_debitur_file == null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    Upload File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="button-addon1"
                                                            onclick="RequestCertificate.addFile(this, 'ktp_pasangan_debitur_{{ $item->id }}')">
                                                            Choose File
                                                        </button>
                                                        <input id="ktp_pasangan_debitur_{{ $item->id }}"
                                                            type="text" readonly class="form-control"
                                                            placeholder="Pilih Data File" aria-label="Pilih Data File"
                                                            src="" error="Data File"
                                                            aria-describedby="button-addon1" value="">
                                                    </div>
                                                    <p class="text-mute">max size : 200kb</p>
                                                </div>
                                            </div>
                                        @elseif ($item->DataWarkah != null && $item->DataWarkah->ktp_pasangan_debitur_file != null)
                                            <div class="form-group row mb-4">
                                                <label for="sertificate_file{{ $item->id }}"
                                                    class="col-md-2 col-form-label">
                                                    File
                                                </label>
                                                <div class="col-md-10 m-auto">
                                                    <div class="input-group">
                                                        <a id="ktp_pasangan_debitur_{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->ktp_pasangan_debitur_path . $item->DataWarkah->ktp_pasangan_debitur_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->ktp_pasangan_debitur_file }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row w-100">
                        <div class="col text-start">
                            {{-- {{ dd($item->status) }} --}}
                            @if (
                                $item->DataWarkah != null &&
                                    ($item->DataWarkah->perjanjian_pembiayaan_file != null ||
                                        $item->DataWarkah->skmjf_file != null ||
                                        $item->DataWarkah->data_kendaraan_file != null ||
                                        $item->DataWarkah->kk_file != null ||
                                        $item->DataWarkah->ktp_bpkb_file != null ||
                                        $item->DataWarkah->ktp_debitur_file != null ||
                                        $item->DataWarkah->form_perjanjian_nama_bpkb_file != null ||
                                        $item->DataWarkah->ktp_pasangan_nama_bpkp_file != null ||
                                        $item->DataWarkah->ktp_pasangan_debitur_file != null))
                                <button type="button"
                                    class="btn btn-info {{ $item->status == 'COMPLETE' ? 'd-none' : '' }}"
                                    onclick="RequestCertificate.updateStatusKontrak('{{ $item->id }}')">
                                    <i class="mdi mdi-checkbox-marked-circle-outline"></i> Konfirmasi
                                </button>
                            @endif
                        </div>
                        <div class="col text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary"
                                onclick="RequestCertificate.saveWarkah('{{ $item->id }}')">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
