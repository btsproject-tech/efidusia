@php
$notaris = [];
$data_status = [];

// pilih salah satu saja jika ada notaris lebih dari satu didalam array $nataris
foreach ($data->RequestContract as $item) {
$data_status[] = $item->status;
if ($item->request_sertificate_notaris != null) {
$notaris[] = [
'id' => $item->UserDelegate->CompanyKaryawan->id,
'name' => $item->UserDelegate->CompanyKaryawan->nama_company,
'nik' => $item->UserDelegate->nik,
'terkirim' => $item->flag_notif,
];
}
}
$notaris = collect($notaris)->unique('id')->values()->all();
// dd($notaris);
@endphp

<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="">
            <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                <strong>Managemen Notaris</strong>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="perjanjian_pembiayaan"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Pilih Notaris</label>
                            <br>
                            <select class="form-control select2" name="notaris" id="notaris"
                                onchange="VerifikasiCertificate.buttonHide(this)">
                                <option value="">Pilih Notaris</option>
                                @foreach ($notaris as $item)
                                <option value="{{ $item['nik'] }}" terikirim="{{ $item['terkirim'] }}">
                                    {{ $item['name'] }}
                                    {{ $item['terkirim'] == 'TERKIRIM' ? ' ✅' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            @if (session('akses') != 'admin minuta' && in_array('APPROVE', $data_status))
                            {{-- <button class="btn btn-info d-none" type="button" id="sendNotaris"
                                onclick="VerifikasiCertificate.sendNotifikasi(this,event)">
                                <i class="mdi mdi-send"></i> Tagihkan ke notaris
                            </button> --}}
                            <button class="btn btn-success" onclick="VerifikasiCertificate.exportXlsContract('xlsx')">
                                <i class="bx bx-export"></i>
                                Export Excel
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-4">
                            <label>File Kontrak .xlsx</label>
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-primary" type="button" id="button-addon1"
                                    onclick="VerifikasiCertificate.ambilData(this, event)"><i class="bx bx-import"></i>
                                    Import
                                    Data</button>
                                <input id="import-file" type="text" class="form-control" readonly
                                    placeholder="File Kontrak .xlsx" aria-label="File Kontrak .xlsx"
                                    aria-describedby="button-addon1" value="">
                                <button class="btn btn-primary disabled" id="btn-import" onclick="VerifikasiCertificate.submitImport()">
                                    <i class="mdi mdi-check"></i>
                                    Submit Data Import
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="table-rate" class="table align-middle mb-0 table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID Transaksi</th>
                                        <th scope="col">Nomor Kontrak</th>
                                        <th scope="col">Nomor Minuta</th>
                                        <th scope="col">Bill ID</th>
                                        <th scope="col">Biaya PNBP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="input" data_id="">
                                        <td>
                                            <input type="text" class="form-control required" id="id_kontrak"
                                                placeholder="ID Transaksi" error="ID Transaksi" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control required" id="contract_number"
                                                placeholder="Nomor Kontrak" error="Nomor Kontrak" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control required" id="no_minuta"
                                                placeholder="Nomor Minuta" error="Nomor Minuta" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control required" id="billId"
                                                placeholder="Bill ID" error="Bill ID" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control required" id="biaya_pnbp"
                                                placeholder="Biaya PNBP" error="Biaya PNBP" value="" readonly>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
