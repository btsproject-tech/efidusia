<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="modalMinuta" aria-hidden="true"
    id="modal-warkah">
    <div class="modal-dialog modal-xl">
        <div class="modal-content content-save-minuta">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMinuta"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-secondary d-flex" type="button" onclick="VerifikasiCertificate.confirmDownload('MINUTA_FIDUSIA_FINAL_20_Agustus_2024_Maya.doc','/assets/doc/template/MINUTA_FIDUSIA_FINAL_20_Agustus_2024_Maya.doc')">
                    <i class="mdi mdi-download"></i> Unduh Template Minuta
                </button>
                <div class="table-responsive">
                    <table id="table-minuta" class="table align-middle mb-0 table-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">File Minuta</th>
                                <th scope="col">Keterangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="input" data_id="">
                                <td>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="button-addon1"
                                            onclick="VerifikasiCertificate.addFileMinuta(this)">Choose File</button>
                                        <input id="file" type="text" readonly class="form-control required"
                                            placeholder="Pilih Data File" aria-label="Pilih Data File" src=""
                                            error="Data File" aria-describedby="button-addon1" value="">

                                    </div>
                                </td>
                                <td>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                        error="Keterangan" placeholder="Masukkan Keterangan..."></textarea>
                                </td>


                                <td id="action"></td>
                            </tr>
                            <tr id="add-item-row">
                                <td colspan="2" class="text-start">
                                    <a href="#" onclick="VerifikasiCertificate.addItemMinuta(this, event)">Tambah
                                        Item</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                    onclick="VerifikasiCertificate.saveMinuta(this,event)">Submit</button>
            </div>

        </div>
    </div>
</div>
