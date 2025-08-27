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
                                placeholder="Pilih Data File" aria-label="Pilih Data File" src="" error="Data File"
                                aria-describedby="button-addon1" value="">
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="no_sk{{ $item->id }}" class="col-md-2 col-form-label">Nomor SK</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control required" id="no_sk{{ $item->id }}" placeholder="No SK"
                            error="No SK" value="{{ isset($item->no_sk ) ? $item->no_sk  : '' }}" readonly>
                        <input type="hidden" name="id_item" id="id_item{{ $item->id }}" value="{{ $item->id }}">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="tanggal_sertifikat{{ $item->id }}" class="col-md-2 col-form-label">Tanggal
                        Sertifikat</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control required" id="tanggal_sertifikat{{ $item->id }}"
                            placeholder="Tanggal Sertifikat" error="Tanggal Sertifikat"
                            value="{{ isset($item->tanggal_sertifikat ) ? $item->tanggal_sertifikat  : '' }}" readonly>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="waktu_sk{{ $item->id }}" class="col-md-2 col-form-label">Tanggal
                        Sertifikat</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control required" id="waktu_sk{{ $item->id }}"
                            placeholder="Waktu SK" error="Waktu SK"
                            value="{{ isset($item->waktu_sk ) ? $item->waktu_sk  : '' }}" readonly>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="biaya_pnbp{{ $item->contract_number }}" class="col-md-2 col-form-label">
                        Biaya PNBP (Rp.)
                    </label>
                    <div class="col-md-10">
                        <input type="text" class="form-control required" id="biaya_pnbp{{ $item->contract_number }}"
                            placeholder="Biaya PNPB" error="Biaya PNPB"
                            value="{{ isset($item->hutang_barang ) ? format_rp(cari_biaya_barang($item->hutang_barang)) : '' }}"
                            readonly>
                    </div>
                </div>
                {{-- END UPLOAD SK --}}

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
