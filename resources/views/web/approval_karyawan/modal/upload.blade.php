<!-- First modal dialog -->
<div class="modal fade" id="konfirmasi-delete" aria-hidden="true" aria-labelledby="..." tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ $id }}</p>
            </div>
            <div class="modal-footer">
                <!-- Toogle to second dialog -->
                {{-- <button class="btn btn-primary" onclick="Karyawan.confirmDelete(this)"
                    data_id="{{ $id }}">Ya</button> --}}
                <button class="btn btn-info" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
