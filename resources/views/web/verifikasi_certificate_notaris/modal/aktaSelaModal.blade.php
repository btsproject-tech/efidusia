<div class="modal fade bs-example-modal-lg{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Add Akta Sela No. Minuta #{{ $item->seq_number }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="content-modal-save{{ $item->id }}">
                <div class="form-group row">
                    <label for="creator" class="col-md-2 col-form-label">Generate No. Minuta</label>
                    <div class="col-md-2 m-auto">
                        <input type="number" class="form-control required" name="new_number_generate{{ $item->id }}" id="new_number_generate{{ $item->id }}" error="no_notaris"
                            placeholder="(Notaris) Exp: 1" >
                        <input type="hidden" class="form-control required" name="no_notaris{{ $item->id }}" id="no_notaris{{ $item->id }}" error="no_notaris"
                            placeholder="(Notaris) Exp: 1" value="{{ $item->request_sertificate_notaris }}">
                        <input type="hidden" class="form-control required" name="no_minuta{{ $item->id  }}" id="no_minuta{{ $item->id  }}" error="id_kontrak"
                            placeholder="(Notaris) Exp: 1" value="{{ $item->seq_number }}">
                    </div>
                    <div class="col-md-2 m-auto">
                        <input type="time" class="form-control required" name="waktu{{ $item->id }}" id="waktu{{ $item->id }}" error="waktu">
                    </div>
                    <div class="col-md-2 m-auto">
                        <input type="number" class="form-control required" name="waktu_jeda{{ $item->id }}" id="waktu_jeda{{ $item->id }}" error="waktu_jeda"
                            placeholder="(Menit) Exp: 1">
                    </div>
                    <div class="col-md-4 m-auto">
                        @if ($item->seq_number != null)
                        <button class="btn btn-primary"
                            onclick="RequestCertificateNotaris.inputAktaSela('{{ $item->id }}')">Generate</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
