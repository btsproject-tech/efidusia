{{-- {{ dd($data) }} --}}
<div class="modal fade bs-example-modal-xl{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Detail Data
                    <span class="badge bg-info">
                        <i class="mdi mdi-account"></i>
                        {{ $item->debitur }}
                    </span>
                    <span class="badge bg-light text-info">
                        {{ $item->contract_number }}
                    </span>
                    <span class="badge bg-warning">
                        <i class="mdi-home-modern"></i>
                        {{ $data->DataRequestCertificate->Creator->Karyawan->CompanyKaryawan->nama_company }}
                    </span>
                    <span class="badge bg-light text-warning">
                        <i class="mdi mdi-account-circle"></i>
                        {{ $data->DataRequestCertificate->Creator->Karyawan->nama_lengkap }}
                    </span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#kontrak{{ $item->id }}"
                                    role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Kontrak</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#profile{{ $item->id }}" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Profile</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#dataPeminjam{{ $item->id }}" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">Data Peminjam</span>
                                </a>
                            </li>

                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="kontrak{{ $item->id }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-6">
                                        {{-- no kontrak --}}
                                        <div class="form-group row">
                                            <label for="contract_number" class="col-4 col-form-label">
                                                <i class="mdi mdi-file-document"></i> Nomor Kontrak
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->contract_number ) ?
                                                $item->contract_number :
                                                '' }}
                                                <input type="hidden" name="contract_number"
                                                    id="contract_number{{ $item->id }}"
                                                    value="{{ isset($item->contract_number ) ? $item->contract_number : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('contract_number{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- type produk --}}
                                        <div class="form-group row">
                                            <label for="type_produk" class="col-4 col-form-label">
                                                <i class="mdi-parking"></i> Tipe Produk
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->type_produk ) ? $item->type_produk : '' }}
                                                <input type="hidden" name="type_produk" id="type_produk{{ $item->id }}"
                                                    value="{{ isset($item->type_produk ) ? $item->type_produk : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('type_produk{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- tgl awal tenor --}}
                                        <div class="form-group row">
                                            <label for="tgl_awal_tenor" class="col-4 col-form-label">
                                                <i class="mdi mdi-timetable"></i> Tanggal Awal Tenor
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->tgl_awal_tenor ) ? $item->tgl_awal_tenor : ''
                                                }}
                                                <input type="hidden" name="tgl_awal_tenor"
                                                    id="tgl_awal_tenor{{ $item->id }}"
                                                    value="{{ isset($item->tgl_awal_tenor ) ? $item->tgl_awal_tenor : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('tgl_awal_tenor{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- tgl akhir tenor --}}
                                        <div class="form-group row">
                                            <label for="tgl_akhir_tenor" class="col-4 col-form-label">
                                                <i class="mdi mdi-timetable"></i> Tanggal Akhir Tenor
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->tgl_akhir_tenor ) ? $item->tgl_akhir_tenor : ''
                                                }}
                                                <input type="hidden" name="tgl_akhir_tenor"
                                                    id="tgl_akhir_tenor{{ $item->id }}"
                                                    value="{{ isset($item->tgl_akhir_tenor ) ? $item->tgl_akhir_tenor : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('tgl_akhir_tenor{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- remarks --}}
                                        <div class="form-group row">
                                            <label for="remarks" class="col-4 col-form-label">
                                                <i class="mdi mdi-format-list-bulleted"></i> Keterangan Dokumen Kontrak
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->remarks ) ? $item->remarks : '' }}
                                                <input type="hidden" name="remarks" id="remarks{{ $item->id }}"
                                                    value="{{ isset($item->remarks ) ? $item->remarks : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('remarks{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- no. minuta --}}
                                        <div class="form-group row">
                                            <label for="seq_numbers" class="col-4 col-form-label">
                                                <i class="mdi mdi-file-document"></i> No. Minuta
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->seq_number ) ? $item->seq_number : '' }}
                                                <input type="hidden" name="seq_numbers" id="seq_numbers{{ $item->id }}"
                                                    value="{{ isset($item->seq_number ) ? $item->seq_number : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('seq_numbers{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- no. SK --}}
                                        <div class="form-group row">
                                            <label for="no_sk" class="col-4 col-form-label">
                                                <i class="mdi mdi-file-document"></i> Nomor SK
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->no_sk ) ? $item->no_sk : '' }}
                                                @if (isset($item->no_sk ) && $item->no_sk != null)
                                                <input type="hidden" name="no_sk" id="no_sk{{ $item->id }}"
                                                    value="{{ isset($item->no_sk ) ? $item->no_sk : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('no_sk{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        {{-- kode cabang --}}
                                        <div class="form-group row">
                                            <label for="cab" class="col-4 col-form-label">
                                                <i class="mdi mdi-qrcode"></i> Kode Cabang
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->cab ) ? $item->cab : '' }}
                                                <input type="hidden" name="cab" id="kode_cabang{{ $item->id }}"
                                                    value="{{ isset($item->cab ) ? $item->cab : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('kode_cabang{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Cab --}}
                                        <div class="form-group row">
                                            <label for="cab" class="col-4 col-form-label">
                                                <i class="mdi mdi-arrange-bring-to-front"></i> Cabang
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{
                                                isset($data->DataRequestCertificate->Creator->Karyawan->nama_lengkap ) ?
                                                $data->DataRequestCertificate->Creator->Karyawan->nama_lengkap : '' }}
                                                <input type="hidden" name="cab" id="cab{{ $item->id }}"
                                                    value="{{ isset($data->DataRequestCertificate->Creator->Karyawan->nama_lengkap ) ? $data->DataRequestCertificate->Creator->Karyawan->nama_lengkap : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('cab{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Tanggal Kontrak --}}
                                        <div class="form-group row">
                                            <label for="cab" class="col-4 col-form-label">
                                                <i class="mdi mdi-timetable"></i>
                                                Tanggal Kontrak
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->tanggal_kuasa ) ? $item->tanggal_kuasa : '' }}
                                                <input type="hidden" name="cab" id="tanggal_kuasa{{ $item->id }}"
                                                    value="{{ isset($item->tanggal_kuasa ) ? $item->tanggal_kuasa : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('tanggal_kuasa{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Jenis Pelanggan --}}
                                        <div class="form-group row">
                                            <label for="cab" class="col-4 col-form-label">
                                                <i class="mdi mdi-account-multiple"></i> Jenis Pelanggan
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{
                                                isset($data->DataRequestCertificate->Creator->Karyawan->CompanyKaryawan->nama_company
                                                ) ?
                                                $data->DataRequestCertificate->Creator->Karyawan->CompanyKaryawan->nama_company
                                                : ''
                                                }}
                                                <input type="hidden" name="cab" id="jenis_pelanggan{{ $item->id }}"
                                                    value="{{ isset($data->DataRequestCertificate->Creator->Karyawan->CompanyKaryawan->nama_company ) ? $data->DataRequestCertificate->Creator->Karyawan->CompanyKaryawan->nama_company : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('jenis_pelanggan{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Sertificate File --}}
                                        <div class="form-group row">
                                            <label for="sertificate_file" class="col-4 col-form-label">
                                                <i class="mdi mdi-file"></i> Sertificate File
                                            </label>
                                            <div class="col-8 m-auto">
                                                @if (isset($item->sertificate_file) &&
                                                $item->sertificate_file != null)
                                                <b>:</b> <a href="#"
                                                    onclick="return RequestCertificateNotaris.confirmDownload('{{ $item->sertificate_file }}','{{ $item->sertificate_path . $item->sertificate_file }}')">
                                                    {{ $item->sertificate_file }}</a>
                                                @else
                                                <b>:</b> File Not Found
                                                @endif
                                            </div>
                                        </div>
                                        {{-- Data Minuta --}}
                                        <div class="form-group row">
                                            <label for="minuta{{ $item->contract_number }}"
                                                class="col-md-2 col-form-label">
                                                <i class="mdi mdi-file"></i> Data Minuta
                                            </label>
                                            <div class="col-8">
                                                <ul>
                                                    @foreach ($item->DataMinuta as $m)
                                                    <li>
                                                        @if (isset($m->sertificate_file) && $m->sertificate_file !=
                                                        null)
                                                        File : <a href="#"
                                                            onclick="return RequestCertificateNotaris.confirmDownload('{{ $m->sertificate_file }}','{{ $m->sertificate_path . $m->sertificate_file }}')">
                                                            {{ $m->sertificate_file }}</a>
                                                        <br>
                                                        Keterangan : {{ $m->keterangan_sertificate }}
                                                        @else
                                                        File Not Found
                                                        @endif
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    @if ($item->status == 'COMPLETE' || $item->status == 'FINISHED')
                                    <div class="col-12 text-end">
                                        <button class="btn btn-outline-success btn-sm mb-2 " type="button"
                                            onclick="RequestCertificateNotaris.downloadAllWarkah({{ $item->id }})">
                                            <i class="mdi mdi-download"></i>
                                            Download Semua File Warkah
                                        </button>
                                    </div>
                                    @endif
                                    <div class="col-12">
                                        {{-- Warkah --}}
                                        <div class="accordion" id="accordionExample{{ $item->id }}">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="perjanjian_pembiayaan{{ $item->id }}">
                                                    <button class="accordion-button fw-medium collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne{{ $item->id }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapseOne{{ $item->id }}">
                                                        <strong>Data Warkah</strong>
                                                    </button>
                                                </h2>
                                                <div id="collapseOne{{ $item->id }}" class="accordion-collapse collapse"
                                                    aria-labelledby="perjanjian_pembiayaan{{ $item->id }}"
                                                    data-bs-parent="#accordionExample{{ $item->id }}">
                                                    <div class="accordion-body">
                                                        @if ($item->DataWarkah == null ||
                                                        $item->DataWarkah->perjanjian_pembiayaan_file
                                                        == null)
                                                        <b>- Perjanjian Pembiayaan</b> : File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->perjanjian_pembiayaan_file != null)
                                                        <b>- Perjanjian Pembiayaan</b> :
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->perjanjian_pembiayaan_path . $item->DataWarkah->perjanjian_pembiayaan_file) }}"
                                                            target="_blank">{{
                                                            $item->DataWarkah->perjanjian_pembiayaan_file
                                                            }}</a>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                        @if ($item->DataWarkah == null || $item->DataWarkah->skmjf_file
                                                        == null)
                                                        <b>- SKMJF </b>: File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->skmjf_file != null)
                                                        <b>- SKMJF </b>:
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->skmjf_path . $item->DataWarkah->skmjf_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->skmjf_file
                                                            }}</a>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                        @if ($item->DataWarkah == null ||
                                                        $item->DataWarkah->data_kendaraan_file
                                                        == null)
                                                        <b>- Data Kendaraan</b> : File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->data_kendaraan_file != null)
                                                        <b>- Data Kendaraan</b> :
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->data_kendaraan_path . $item->DataWarkah->data_kendaraan_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->data_kendaraan_file
                                                            }}</a>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                        @if ($item->DataWarkah == null || $item->DataWarkah->kk_file
                                                        == null)
                                                        <b>- Kartu Keluarga</b> : File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->kk_file != null)
                                                        <b>- Kartu Keluarga</b> :
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->kk_path . $item->DataWarkah->kk_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->kk_file
                                                            }}</a>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                        @if ($item->DataWarkah == null ||
                                                        $item->DataWarkah->ktp_bpkb_file
                                                        == null)
                                                        <b>- KTP Nama BPKB</b> : File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->ktp_bpkb_file != null)
                                                        <b>- KTP Nama BPKB</b> :
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->ktp_bpkb_path . $item->DataWarkah->ktp_bpkb_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->ktp_bpkb_file
                                                            }}</a>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                        @if ($item->DataWarkah == null ||
                                                        $item->DataWarkah->ktp_debitur_file
                                                        == null)
                                                        <b>- KTP Debitur</b> : File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->ktp_debitur_file != null)
                                                        <b>- KTP Debitur</b> :
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->ktp_debitur_path . $item->DataWarkah->ktp_debitur_file) }}"
                                                            target="_blank">{{ $item->DataWarkah->ktp_debitur_file
                                                            }}</a>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                        @if ($item->DataWarkah == null ||
                                                        $item->DataWarkah->form_perjanjian_nama_bpkb_file
                                                        == null)
                                                        <b>- Form Perjanjian Nama BPKB</b> : File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->form_perjanjian_nama_bpkb_file != null)
                                                        <b>- Form Perjanjian Nama BPKB</b> :
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->form_perjanjian_nama_bpkb_path . $item->DataWarkah->form_perjanjian_nama_bpkb_file) }}"
                                                            target="_blank">{{
                                                            $item->DataWarkah->form_perjanjian_nama_bpkb_file
                                                            }}</a>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                        @if ($item->DataWarkah == null ||
                                                        $item->DataWarkah->ktp_pasangan_nama_bpkp_file
                                                        == null)
                                                        <b>- KPT Pasangan Nama BPKB</b> : File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->ktp_pasangan_nama_bpkp_file != null)
                                                        <b>- KPT Pasangan Nama BPKB</b> :
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->ktp_pasangan_nama_bpkp_path . $item->DataWarkah->ktp_pasangan_nama_bpkp_file) }}"
                                                            target="_blank">{{
                                                            $item->DataWarkah->ktp_pasangan_nama_bpkp_file
                                                            }}</a>
                                                        @endif
                                                        <br>
                                                        <hr>
                                                        @if ($item->DataWarkah == null ||
                                                        $item->DataWarkah->ktp_pasangan_debitur_file
                                                        == null)
                                                        <b>- KPT Pasangan Debitur</b> : File Not Found
                                                        @elseif ($item->DataWarkah != null &&
                                                        $item->DataWarkah->ktp_pasangan_debitur_file != null)
                                                        <b>- KPT Pasangan Debitur</b> :
                                                        <a id="file{{ $item->id }}"
                                                            href="{{ asset($item->DataWarkah->ktp_pasangan_debitur_path . $item->DataWarkah->ktp_pasangan_debitur_file) }}"
                                                            target="_blank">{{
                                                            $item->DataWarkah->ktp_pasangan_debitur_file
                                                            }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="profile{{ $item->id }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-6">
                                        {{-- Nama --}}
                                        <div class="form-group row">
                                            <label for="nama" class="col-4 col-form-label">
                                                <i class="mdi mdi-account"></i> Nama
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->debitur) ? $item->debitur : '' }}
                                                <input type="hidden" name="nama" id="nama{{ $item->id }}"
                                                    value="{{ isset($item->debitur) ? $item->debitur : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('nama{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Jenis Kelamin --}}
                                        <div class="form-group row">
                                            <label for="jenis_kelamin" class="col-4 col-form-label">
                                                <i class="mdi-gender-transgender"></i> Jenis Kelamin
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->jenis_kelamin) ? $item->jenis_kelamin : '' }}
                                                <input type="hidden" name="jenis_kelamin"
                                                    id="jenis_kelamin{{ $item->id }}"
                                                    value="{{ isset($item->jenis_kelamin) ? $item->jenis_kelamin : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('jenis_kelamin{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Status Perkawinan --}}
                                        <div class="form-group row">
                                            <label for="status_perkawinan" class="col-4 col-form-label">
                                                <i class="mdi mdi-heart-outline"></i> Status
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->status_perkawinan) ? $item->status_perkawinan :
                                                '' }}
                                                <input type="hidden" name="status_perkawinan"
                                                    id="status_perkawinan{{ $item->id }}"
                                                    value="{{ isset($item->status_perkawinan) ? $item->status_perkawinan : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('status_perkawinan{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Telepon --}}
                                        <div class="form-group row">
                                            <label for="no_telp" class="col-4 col-form-label">
                                                <i class="mdi mdi-phone"></i> Telepon
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->no_telp) ? $item->no_telp : '' }}
                                                <input type="hidden" name="no_telp" id="no_telp{{ $item->id }}"
                                                    value="{{ isset($item->no_telp) ? $item->no_telp : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('no_telp{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- NIK --}}
                                        <div class="form-group row">
                                            <label for="ktp" class="col-4 col-form-label">
                                                <i class="mdi mdi-clipboard-account"></i> NIK
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->ktp) ? $item->ktp : '' }}
                                                <input type="hidden" name="ktp" id="ktp{{ $item->id }}"
                                                    value="{{ isset($item->ktp) ? $item->ktp : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('ktp{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Pekerjaan --}}
                                        <div class="form-group row">
                                            <label for="pekerjaan" class="col-4 col-form-label">
                                                <i class="mdi mdi-wallet-travel"></i> Pekerjaan
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->pekerjaan) ? $item->pekerjaan : '' }}
                                                <input type="hidden" name="pekerjaan" id="pekerjaan{{ $item->id }}"
                                                    value="{{ isset($item->pekerjaan) ? $item->pekerjaan : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('pekerjaan{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Alamat --}}
                                        <div class="form-group row">
                                            <label for="alamat" class="col-4 col-form-label">
                                                <i class="mdi mdi-google-maps"></i> Alamat
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->alamat) ? $item->alamat : '' }}
                                                <input type="hidden" name="alamat" id="alamat{{ $item->id }}"
                                                    value="{{ isset($item->alamat) ? $item->alamat : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('alamat{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- RT / RW --}}
                                        <div class="form-group row">
                                            <label for="rt" class="col-4 col-form-label">
                                                <i class="mdi mdi-google-maps"></i> RT / RW
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->rt) ? $item->rt .'/'. $item->rw : '' }}
                                                <input type="hidden" name="rt" id="rt{{ $item->id }}"
                                                    value="{{ isset($item->rt) ? $item->rt .'/'. $item->rw : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('rt{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Kelurahan --}}
                                        <div class="form-group row">
                                            <label for="kelurahan" class="col-4 col-form-label">
                                                <i class="mdi mdi-google-maps"></i> Kelurahan
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->kelurahan) ? $item->kelurahan : '' }}
                                                <input type="hidden" name="kelurahan" id="kelurahan{{ $item->id }}"
                                                    value="{{ isset($item->kelurahan) ? $item->kelurahan : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('kelurahan{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Kecamatan --}}
                                        <div class="form-group row">
                                            <label for="kecamatan" class="col-4 col-form-label">
                                                <i class="mdi mdi-google-maps"></i> Kecamatan
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->kecamatan) ? $item->kecamatan : '' }}
                                                <input type="hidden" name="kecamatan" id="kecamatan{{ $item->id }}"
                                                    value="{{ isset($item->kecamatan) ? $item->kecamatan : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('kecamatan{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-6">
                                        {{-- Kabupaten --}}
                                        <div class="form-group row">
                                            <label for="kabupaten" class="col-4 col-form-label">
                                                <i class="mdi mdi-google-maps"></i> Kabupaten
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->kabupaten) ? $item->kabupaten : '' }}
                                                <input type="hidden" name="kabupaten" id="kabupaten{{ $item->id }}"
                                                    value="{{ isset($item->kabupaten) ? $item->kabupaten : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('kabupaten{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Provinsi --}}
                                        <div class="form-group row">
                                            <label for="provinsi" class="col-4 col-form-label">
                                                <i class="mdi mdi-google-maps"></i> Provinsi
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->provinsi) ? $item->provinsi : '' }}
                                                <input type="hidden" name="provinsi" id="provinsi{{ $item->id }}"
                                                    value="{{ isset($item->provinsi) ? $item->provinsi : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('provinsi{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Kode Pos --}}
                                        <div class="form-group row">
                                            <label for="kode_pos" class="col-4 col-form-label">
                                                <i class="mdi mdi-email"></i> Kode Pos
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->kode_pos) ? $item->kode_pos : '' }}
                                                <input type="hidden" name="kode_pos" id="kode_pos{{ $item->id }}"
                                                    value="{{ isset($item->kode_pos) ? $item->kode_pos : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('kode_pos{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Tempat Lahir --}}
                                        <div class="form-group row">
                                            <label for="tempat_lahir" class="col-4 col-form-label">
                                                <i class="mdi mdi-snowman"></i> Tempat Lahir
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->tempat_lahir) ? $item->tempat_lahir : '' }}
                                                <input type="hidden" name="tempat_lahir"
                                                    id="tempat_lahir{{ $item->id }}"
                                                    value="{{ isset($item->tempat_lahir) ? $item->tempat_lahir : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('tempat_lahir{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Nama Pasangan --}}
                                        <div class="form-group row">
                                            <label for="nama_pasangan" class="col-4 col-form-label">
                                                <i class="mdi mdi-account"></i> Nama Pasangan
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->nama_pasangan) ? $item->nama_pasangan : '' }}
                                                <input type="hidden" name="nama_pasangan"
                                                    id="nama_pasangan{{ $item->id }}"
                                                    value="{{ isset($item->nama_pasangan) ? $item->nama_pasangan : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('nama_pasangan{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Nama Pemilik BPKB --}}
                                        <div class="form-group row">
                                            <label for="pemilik_bpkb" class="col-4 col-form-label">
                                                <i class="mdi mdi-credit-card"></i> Nama Pemilik BPKB
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->pemilik_bpkb) ? $item->pemilik_bpkb : '' }}
                                                <input type="hidden" name="pemilik_bpkb"
                                                    id="pemilik_bpkb{{ $item->id }}"
                                                    value="{{ isset($item->pemilik_bpkb) ? $item->pemilik_bpkb : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('pemilik_bpkb{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- NPWP --}}
                                        <div class="form-group row">
                                            <label for="npwp" class="col-4 col-form-label">
                                                <i class="mdi mdi-credit-card"></i> NPWP
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->npwp) ? $item->npwp : '' }}
                                                <input type="hidden" name="npwp" id="npwp{{ $item->id }}"
                                                    value="{{ isset($item->npwp) ? $item->npwp : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('npwp{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Tanggal Lahir --}}
                                        <div class="form-group row">
                                            <label for="tanggal_lahir" class="col-4 col-form-label">
                                                <i class="mdi mdi-table"></i> Tanggal Lahir
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->tanggal_lahir) ? $item->tanggal_lahir : '' }}
                                                <input type="hidden" name="tanggal_lahir"
                                                    id="tanggal_lahir{{ $item->id }}"
                                                    value="{{ isset($item->tanggal_lahir) ? $item->tanggal_lahir : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('tanggal_lahir{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Nama Debitur --}}
                                        <div class="form-group row">
                                            <label for="debitur" class="col-4 col-form-label">
                                                <i class="mdi mdi-account"></i> Nama Debitur
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->debitur) ? $item->debitur : '' }}
                                                <input type="hidden" name="debitur" id="debitur{{ $item->id }}"
                                                    value="{{ isset($item->debitur) ? $item->debitur : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('debitur{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="dataPeminjam{{ $item->id }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-6">
                                        {{-- Nilai Hutang Pokok --}}
                                        <div class="form-group row">
                                            <label for="hutang_pokok" class="col-4 col-form-label">
                                                <i class="mdi mdi-cash"></i> Nilai Hutang Pokok
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->hutang_pokok) ? $item->hutang_pokok : '' }}
                                                <input type="hidden" name="hutang_pokok"
                                                    id="hutang_pokok{{ $item->id }}"
                                                    value="{{ isset($item->hutang_pokok) ? $item->hutang_pokok : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('hutang_pokok{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- OTR --}}
                                        <div class="form-group row">
                                            <label for="hutang_barang" class="col-4 col-form-label">
                                                <i class="mdi mdi-cash-multiple"></i> OTR
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->hutang_barang) ? $item->hutang_barang : '' }}
                                                <input type="hidden" name="hutang_barang"
                                                    id="hutang_barang{{ $item->id }}"
                                                    value="{{ isset($item->hutang_barang) ? $item->hutang_barang : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('hutang_barang{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Nilai Object Peminjam --}}
                                        <div class="form-group row">
                                            <label for="nilai_jaminan" class="col-4 col-form-label">
                                                <i class="mdi mdi-cash-usd"></i> Nilai Object Peminjam
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->nilai_jaminan) ? $item->nilai_jaminan : '' }}
                                                <input type="hidden" name="nilai_jaminan"
                                                    id="nilai_jaminan{{ $item->id }}"
                                                    value="{{ isset($item->nilai_jaminan) ? $item->nilai_jaminan : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('nilai_jaminan{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Tipe/Merk Kendaraan --}}
                                        <div class="form-group row">
                                            <label for="merk" class="col-4 col-form-label">
                                                <i class="mdi mdi-car"></i> Tipe/Merk Kendaraan
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->merk) ? $item->merk : '' }}
                                                <input type="hidden" name="merk" id="merk{{ $item->id }}"
                                                    value="{{ isset($item->merk) ? $item->merk : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('merk{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Warna --}}
                                        <div class="form-group row">
                                            <label for="warna" class="col-4 col-form-label">
                                                <i class="mdi mdi-format-color-fill"></i> Warna
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->warna) ? $item->warna : '' }}
                                                <input type="hidden" name="warna" id="warna{{ $item->id }}"
                                                    value="{{ isset($item->warna) ? $item->warna : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('warna{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Deskripsi Asset --}}
                                        <div class="form-group row">
                                            <label for="tipe" class="col-4 col-form-label">
                                                <i class="mdi mdi-deskphone"></i> Deskripsi Asset
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->tipe) ? $item->tipe : '' }}
                                                <input type="hidden" name="tipe" id="tipe{{ $item->id }}"
                                                    value="{{ isset($item->tipe) ? $item->tipe : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('tipe{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        {{-- Nomor Rangka --}}
                                        <div class="form-group row">
                                            <label for="no_rangka" class="col-4 col-form-label">
                                                <i class="mdi mdi-setting"></i> Nomor Rangka
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->no_rangka) ? $item->no_rangka : '' }}
                                                <input type="hidden" name="no_rangka" id="no_rangka{{ $item->id }}"
                                                    value="{{ isset($item->no_rangka) ? $item->no_rangka : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('no_rangka{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Nomor Mesin --}}
                                        <div class="form-group row">
                                            <label for="no_mesin" class="col-4 col-form-label">
                                                <i class="mdi mdi-setting-box"></i> Nomor Mesin
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->no_mesin) ? $item->no_mesin : '' }}
                                                <input type="hidden" name="no_mesin" id="no_mesin{{ $item->id }}"
                                                    value="{{ isset($item->no_mesin) ? $item->no_mesin : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('no_mesin{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Nomor Polisi --}}
                                        <div class="form-group row">
                                            <label for="nopol" class="col-4 col-form-label">
                                                <i class="mdi mdi-receipt"></i> Nomor Polisi
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->nopol) ? $item->nopol : '' }}
                                                <input type="hidden" name="nopol" id="nopol{{ $item->id }}"
                                                    value="{{ isset($item->nopol) ? $item->nopol : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('nopol{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Nomor BPKB --}}
                                        <div class="form-group row">
                                            <label for="nomor_bpkb" class="col-4 col-form-label">
                                                <i class="mdi  mdi-equal-box"></i> Nomor BPKB
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->nomor_bpkb) ? $item->nomor_bpkb : '' }}
                                                <input type="hidden" name="nomor_bpkb" id="nomor_bpkb{{ $item->id }}"
                                                    value="{{ isset($item->nomor_bpkb) ? $item->nomor_bpkb : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('nomor_bpkb{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Tahun Pembuatan --}}
                                        <div class="form-group row">
                                            <label for="tahun" class="col-4 col-form-label">
                                                <i class="mdi mdi-calendar"></i> Tahun Pembuatan
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->tahun) ? $item->tahun : '' }}
                                                <input type="hidden" name="tahun" id="tahun{{ $item->id }}"
                                                    value="{{ isset($item->tahun) ? $item->tahun : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('tahun{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Kondisi --}}
                                        <div class="form-group row">
                                            <label for="kondisi" class="col-4 col-form-label">
                                                <i class="mdi mdi-flag"></i> Kondisi
                                            </label>
                                            <div class="col-8 m-auto">
                                                <b>:</b> {{ isset($item->kondisi) ? $item->kondisi : '' }}
                                                <input type="hidden" name="kondisi" id="kondisi{{ $item->id }}"
                                                    value="{{ isset($item->kondisi) ? $item->kondisi : '' }}">
                                                <button class="btn btn-outline-success btn-sm float-end" type="button"
                                                    onclick="RequestCertificateNotaris.copyToClipboard('kondisi{{ $item->id }}')">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Timeline --}}
                            <hr>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <h4 class="card-title mb-4">Timeline History</h4>
                                    <div>
                                        <ul class="verti-timeline list-unstyled">
                                            @if ($item->RequestContractStatusDailyReport->isEmpty())
                                            <p>No data available.</p>
                                            @else
                                            @foreach ($item->RequestContractStatusDailyReport as $i)
                                            @if ($i->status == 'DRAFT')
                                            @continue
                                            @else
                                            <li class="event-list">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bx-right-arrow-circle font-size-18"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <span class="badge
                                                                            @if ($i['status'] == 'APPROVE')
                                                                            bg-success
                                                                            @elseif ($i['status'] == 'COMPLETE')
                                                                            bg-secondary
                                                                            @elseif ($i['status'] == 'FINISHED')
                                                                            bg-dark
                                                                            @elseif ($i['status'] == 'VERIFIED')
                                                                            bg-success
                                                                            @elseif ($i['status'] == 'DONE')
                                                                            bg-primary
                                                                            @endif
                                                                            " style="font-size:12px;">
                                                            {{$i['status'] }}
                                                        </span>
                                                        <i
                                                            class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="font-size-14">
                                                            {{ $i['created_at'] }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                            @endforeach
                                            @foreach (get_list_status_verification($i->status) as $j)
                                            {!! $j !!}
                                            @endforeach
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                                @if (session('akses') == 'admin minuta' && $item->status == 'COMPLETE')
                                <div class="col-6">
                                    <h4 class="card-title mb-4">Ambil Data Minuta</h4>
                                    <button type="button" class="btn btn-info waves-effect m-1"
                                        onclick="RequestCertificateNotaris.exportMinuta('{{ $item->id }}')">
                                        <i class="bx bx-export"></i>
                                        Export .docx
                                    </button>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
