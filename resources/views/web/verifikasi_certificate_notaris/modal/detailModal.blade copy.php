<div class="modal fade bs-example-modal-xl{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Detail Data No. Kontrak #{{ $item->contract_number }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="form-group row">
                                <label for="pemberi_fidusia" class="col-4 col-form-label">Pemberi Fidusia</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->pemberi_fidusia ) ? $item->pemberi_fidusia
                                    :
                                    '' }}
                                    @if (isset($item->pemberi_fidusia ) && $item->pemberi_fidusia != null)
                                    <input type="hidden" name="pemberi_fidusia" id="pemberi_fidusia{{ $item->id }}"
                                        value="{{ isset($item->pemberi_fidusia ) ? $item->pemberi_fidusia : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('pemberi_fidusia{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="contract_number" class="col-4 col-form-label">Nomor
                                    Kontrak</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->contract_number ) ?
                                    $item->contract_number :
                                    '' }}
                                    <input type="hidden" name="contract_number" id="contract_number{{ $item->id }}"
                                        value="{{ isset($item->contract_number ) ? $item->contract_number : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('contract_number{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_perjanjian_kontrak" class="col-4 col-form-label">Nomor Perjanjian
                                    Kontrak</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->no_perjanjian_kontrak ) ?
                                    $item->no_perjanjian_kontrak :
                                    '' }}
                                    <input type="hidden" name="no_perjanjian_kontrak" id="no_perjanjian_kontrak{{ $item->id }}"
                                        value="{{ isset($item->no_perjanjian_kontrak ) ? $item->no_perjanjian_kontrak : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('no_perjanjian_kontrak{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jenis_kelamin" class="col-4 col-form-label">Jenis Kelamin</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->jenis_kelamin ) ?
                                    $item->jenis_kelamin : ''
                                    }}
                                    <input type="hidden" name="jenis_kelamin" id="jenis_kelamin{{ $item->id }}"
                                        value="{{ isset($item->jenis_kelamin ) ? $item->jenis_kelamin : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('jenis_kelamin{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ttl" class="col-4 col-form-label">Tempat Dan Tanggal Lahir </label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->tempat_lahir ) ? $item->tempat_lahir
                                    .', '.$item->tanggal_lahir:'' }}
                                    <input type="hidden" name="ttl" id="ttl{{ $item->id }}" value="{{ isset($item->tempat_lahir ) ? $item->tempat_lahir
                                    .', '.$item->tanggal_lahir:'' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('ttl{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pekerjaan" class="col-4 col-form-label">Pekerjaan</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->pekerjaan ) ? $item->pekerjaan : '' }}
                                    <input type="hidden" name="pekerjaan" id="pekerjaan{{ $item->id }}"
                                        value="{{ isset($item->pekerjaan ) ? $item->pekerjaan : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('pekerjaan{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-4 col-form-label">Alamat</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ $item->debitur_address .',Kel.'.$item->kelurahan.',Kec.'.$item->kecamatan.','.
                                    $item->kabupaten .','. $item->provinsi .','. $item->kode_pos}}

                                    <input type="hidden" name="alamat" id="alamat{{ $item->id }}" value="{{ $item->debitur_address .',Kel.'.$item->kelurahan.',Kec.'.$item->kecamatan.','.
                                    $item->kabupaten .','. $item->provinsi .','. $item->kode_pos }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('alamat{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ktp" class="col-4 col-form-label">KTP</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->ktp ) ? $item->ktp : '' }}
                                    <input type="hidden" name="ktp" id="ktp{{ $item->id }}"
                                        value="{{ isset($item->ktp ) ? $item->ktp : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('ktp{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp" class="col-4 col-form-label">NPWP</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->npwp ) ? $item->npwp : '' }}
                                    <input type="hidden" name="npwp" id="npwp{{ $item->id }}"
                                        value="{{ isset($item->npwp ) ? $item->npwp : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('npwp{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_telp" class="col-4 col-form-label">Nomor Telp</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->no_telp ) ? $item->no_telp : '' }}
                                    <input type="hidden" name="no_telp" id="no_telp{{ $item->id }}"
                                        value="{{ isset($item->no_telp ) ? $item->no_telp : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('no_telp{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status_perkawinan" class="col-4 col-form-label">Status
                                    Perkawinan</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->status_perkawinan ) ? $item->status_perkawinan : '' }}
                                    <input type="hidden" name="status_perkawinan" id="status_perkawinan{{ $item->id }}"
                                        value="{{ isset($item->status_perkawinan ) ? $item->status_perkawinan : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('status_perkawinan{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama_pasangan" class="col-4 col-form-label">Nama Pasangan</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->nama_pasangan ) ? $item->nama_pasangan : '' }}
                                    <input type="hidden" name="nama_pasangan" id="nama_pasangan{{ $item->id }}"
                                        value="{{ isset($item->nama_pasangan ) ? $item->nama_pasangan : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('nama_pasangan{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_kuasa" class="col-4 col-form-label">Tanggal Kuasa</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->tanggal_kuasa ) ? $item->tanggal_kuasa : '' }}
                                    <input type="hidden" name="tanggal_kuasa" id="tanggal_kuasa{{ $item->id }}"
                                        value="{{ isset($item->tanggal_kuasa ) ? $item->tanggal_kuasa : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('tanggal_kuasa{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="debitur" class="col-4 col-form-label">Nama Debitur</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->debitur ) ? $item->debitur : '' }}
                                    <input type="hidden" name="debitur" id="debitur{{ $item->id }}"
                                        value="{{ isset($item->debitur ) ? $item->debitur : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('debitur{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hutang_pokok" class="col-4 col-form-label">Hutang Pokok</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> Rp. {{ isset($item->hutang_pokok ) ? format_rp($item->hutang_pokok) : '' }}
                                    <input type="hidden" name="hutang_pokok" id="hutang_pokok{{ $item->id }}"
                                        value="{{ isset($item->hutang_pokok ) ? $item->hutang_pokok : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('hutang_pokok{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nilai_jaminan" class="col-4 col-form-label">Nilai Jaminan</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> Rp. {{ isset($item->nilai_jaminan ) ? format_rp($item->nilai_jaminan) : '' }}
                                    <input type="hidden" name="nilai_jaminan" id="nilai_jaminan{{ $item->id }}"
                                        value="{{ isset($item->nilai_jaminan ) ? $item->nilai_jaminan : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('nilai_jaminan{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nilai_barang" class="col-4 col-form-label">Nilai Barang</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> Rp. {{ isset($item->hutang_barang ) ? format_rp($item->hutang_barang) : '' }}
                                    <input type="hidden" name="nilai_barang" id="nilai_barang{{ $item->id }}"
                                        value="{{ isset($item->hutang_barang ) ? $item->hutang_barang : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('nilai_barang{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="merk" class="col-4 col-form-label">Merk</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->merk ) ? $item->merk : '' }}
                                    <input type="hidden" name="merk" id="merk{{ $item->id }}"
                                        value="{{ isset($item->merk ) ? $item->merk : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('merk{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tipe" class="col-4 col-form-label">Tipe</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->tipe ) ? $item->tipe : '' }}
                                    <input type="hidden" name="tipe" id="tipe{{ $item->id }}"
                                        value="{{ isset($item->tipe ) ? $item->tipe : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('tipe{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tahun" class="col-4 col-form-label">Tahun</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->tahun ) ? $item->tahun : '' }}
                                    <input type="hidden" name="tahun" id="tahun{{ $item->id }}"
                                        value="{{ isset($item->tahun ) ? $item->tahun : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('tahun{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="warna" class="col-4 col-form-label">Warna</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->warna ) ? $item->warna : '' }}
                                    <input type="hidden" name="warna" id="warna{{ $item->id }}"
                                        value="{{ isset($item->warna ) ? $item->warna : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('warna{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_rangka" class="col-4 col-form-label">Nomor Rangka</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->no_rangka ) ? $item->no_rangka : '' }}
                                    @if (isset($item->no_rangka ) && $item->no_rangka != null)
                                    <input type="hidden" name="no_rangka" id="no_rangka{{ $item->id }}"
                                        value="{{ isset($item->no_rangka ) ? $item->no_rangka : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('no_rangka{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="form-group row">
                                <label for="no_mesin" class="col-4 col-form-label">Nomor Mesin</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->no_mesin ) ? $item->no_mesin : '' }}
                                    @if (isset($item->no_mesin ) && $item->no_mesin != null)
                                    <input type="hidden" name="no_mesin" id="no_mesin{{ $item->id }}"
                                        value="{{ isset($item->no_mesin ) ? $item->no_mesin : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('no_mesin{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nopol" class="col-4 col-form-label">Nomor Polisi</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->nopol ) ? $item->nopol : '' }}
                                    <input type="hidden" name="nopol" id="nopol{{ $item->id }}"
                                        value="{{ isset($nopol->tipe ) ? $nopol->tipe : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('nopol{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pemilik_bpkb" class="col-4 col-form-label">Pemilik BPKB</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->pemilik_bpkb ) ? $item->pemilik_bpkb : '' }}
                                    <input type="hidden" name="pemilik_bpkb" id="pemilik_bpkb{{ $item->id }}"
                                        value="{{ isset($item->pemilik_bpkb ) ? $item->pemilik_bpkb : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('pemilik_bpkb{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nomor_bpkb" class="col-4 col-form-label">Nomor BPKB</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->nomor_bpkb ) ? $item->nomor_bpkb : '' }}
                                    <input type="hidden" name="nomor_bpkb" id="nomor_bpkb{{ $item->id }}"
                                        value="{{ isset($item->nomor_bpkb ) ? $item->nomor_bpkb : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('nomor_bpkb{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="customer_tipe" class="col-4 col-form-label">Customer Tipe</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->customer_tipe ) ? $item->customer_tipe : '' }}
                                    <input type="hidden" name="customer_tipe" id="customer_tipe{{ $item->id }}"
                                        value="{{ isset($item->customer_tipe ) ? $item->customer_tipe : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('customer_tipe{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_awal_tenor" class="col-4 col-form-label">Tanggal Awal Tenor</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->tgl_awal_tenor ) ? $item->tgl_awal_tenor : '' }}
                                    <input type="hidden" name="tgl_awal_tenor" id="tgl_awal_tenor{{ $item->id }}"
                                        value="{{ isset($item->tgl_awal_tenor ) ? $item->tgl_awal_tenor : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('tgl_awal_tenor{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_akhir_tenor" class="col-4 col-form-label">Tanggal Akhir
                                    Tenor</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->tgl_akhir_tenor ) ? $item->tgl_akhir_tenor : '' }}
                                    <input type="hidden" name="tgl_akhir_tenor" id="tgl_akhir_tenor{{ $item->id }}"
                                        value="{{ isset($item->tgl_akhir_tenor ) ? $item->tgl_akhir_tenor : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('tgl_akhir_tenor{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="type_produk" class="col-4 col-form-label">Tipe Produk</label>
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
                            <div class="form-group row">
                                <label for="cab" class="col-4 col-form-label">Cab</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->cab ) ? $item->cab : '' }}
                                    <input type="hidden" name="cab" id="cab{{ $item->id }}"
                                        value="{{ isset($item->cab ) ? $item->cab : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('cab{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rep" class="col-4 col-form-label">Rep</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->rep ) ? $item->rep : '' }}
                                    <input type="hidden" name="rep" id="rep{{ $item->id }}"
                                        value="{{ isset($item->rep ) ? $item->rep : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('rep{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="delegate_to" class="col-4 col-form-label">Delegate
                                    To</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->delegate_to) ? $item->delegate_to .' - '
                                    .$item->UserDelegate->nama_lengkap : '' }}
                                    <input type="hidden" name="delegate_to" id="delegate_to{{ $item->id }}"
                                        value="{{ isset($item->delegate_to) ? $item->delegate_to .' - ' .$item->UserDelegate->nama_lengkap : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('delegate_to{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="date_delegate" class="col-4 col-form-label">Date
                                    Delegate</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->date_delegate ) ? $item->date_delegate : '' }}
                                    <input type="hidden" name="date_delegate" id="date_delegate{{ $item->id }}"
                                        value="{{ isset($item->date_delegate ) ? $item->date_delegate : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('date_delegate{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="seq_numbers" class="col-4 col-form-label">No.
                                    Minuta</label>
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

                            {{-- FORM SELECT --}}
                            <div class="form-group row">
                                <label for="status_{{ $item->contract_number }}"
                                    class="col-4 col-form-label">Status</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> <span class="badge
                                    @if ($item->status == 'DRAFT')
                                    bg-warning
                                    @elseif ($item->status == 'REJECT')
                                    bg-danger
                                    @elseif ($item->status == 'APPROVE')
                                    bg-success
                                    @elseif ($item->status == 'ON PROCESS')
                                    bg-secondary
                                    @elseif ($item->status == 'COMPLETE')
                                    bg-secondary
                                    @elseif ($item->status == 'FINISHED')
                                    bg-dark
                                    @elseif ($item->status == 'DONE BY NOTARIS')
                                    bg-primary
                                    @endif
                                    " style="font-size:12px;">{{
                                        $item->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="remarks_verify" class="col-4 col-form-label">Remarks
                                    Verify</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->remarks_verify ) ? $item->remarks_verify : '' }}
                                    @if (isset($item->remarks_verify ) && $item->remarks_verify != null)
                                    <input type="hidden" name="remarks_verify" id="remarks_verify{{ $item->id }}"
                                        value="{{ isset($item->remarks_verify ) ? $item->remarks_verify : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('remarks_verify{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="billing_number_ahu{{ $item->contract_number }}"
                                    class="col-4 col-form-label">Billing
                                    Number AHU</label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->billing_number_ahu ) ? $item->billing_number_ahu : '' }}
                                    @if (isset($item->billing_number_ahu ) && $item->billing_number_ahu != null)
                                    <input type="hidden" name="billing_number_ahu" id="billing_number_ahu{{ $item->id }}"
                                        value="{{ isset($item->billing_number_ahu ) ? $item->billing_number_ahu : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('billing_number_ahu{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date_input_ahu" class="col-4 col-form-label">
                                    Date Input AHU
                                </label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->date_input_ahu ) ? $item->date_input_ahu : '' }}
                                    @if (isset($item->date_input_ahu ) && $item->date_input_ahu != null)
                                    <input type="hidden" name="date_input_ahu" id="date_input_ahu{{ $item->id }}"
                                        value="{{ isset($item->date_input_ahu ) ? $item->date_input_ahu : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('date_input_ahu{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name_pnbp" class="col-4 col-form-label">
                                    Nama PNPB
                                </label>
                                <div class="col-8 m-auto">
                                    <b>:</b> {{ isset($item->name_pnbp ) ? $item->name_pnbp : '' }}
                                    @if (isset($item->name_pnbp ) && $item->name_pnbp != null)
                                    <input type="hidden" name="name_pnbp" id="name_pnbp{{ $item->id }}"
                                        value="{{ isset($item->name_pnbp ) ? $item->name_pnbp : '' }}">
                                    <button class="btn btn-outline-success btn-sm float-end" type="button"
                                        onclick="RequestCertificateNotaris.copyToClipboard('name_pnbp{{ $item->id }}')">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sertificate_file" class="col-4 col-form-label">
                                    Sertificate File
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
                            <div class="form-group row">
                                <label for="warkah{{ $item->contract_number }}" class="col-4 col-form-label">Data
                                    Warkah
                                </label>
                                <div class="col-8">
                                    <ul>
                                        @foreach ($item->DataWarkah as $iw)
                                        <li>
                                            @if (isset($iw->warkah_file) && $iw->warkah_file != null)
                                            File : <a href="#"
                                                onclick="return RequestCertificateNotaris.confirmDownload('{{ $iw->warkah_file }}','{{ $iw->warkah_path . $iw->warkah_file }}')">
                                                {{ $iw->warkah_file }}</a>
                                            <br>
                                            Keterangan : {{ $iw->remarks }}
                                            @else
                                            File Not Found
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="minuta{{ $item->contract_number }}" class="col-md-2 col-form-label">Data
                                    Minuta
                                </label>
                                <div class="col-8">
                                    <ul>
                                        @foreach ($item->DataMinuta as $m)
                                        <li>
                                            @if (isset($m->sertificate_file) && $m->sertificate_file != null)
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
                </div>
                <br>
                <hr>
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="card-title mb-4">Timeline History</h4>
                        <div>
                            <ul class="verti-timeline list-unstyled">
                                @if (!empty($item->RequestContractStatusDailyReport))
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
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
