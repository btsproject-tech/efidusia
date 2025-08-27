{{-- {{ dd($data) }} --}}
<h4 class="card-title">Contract Data Certificate</h4>
<p class="card-title-desc">Fill all information below</p>
@if ($data == null)
<div class="row">
    <div class="col-6">
        <div class="mb-4">
            <label>File Kontrak .xlsx</label>
            <div class="input-group">
                <button class="btn btn-outline-primary" type="button" id="button-addon1"
                    onclick="RequestCertificate.ambilData(this, event)"><i class="bx bx-import"></i> Import
                    Data</button>
                <input id="import-file" type="text" class="form-control" readonly placeholder="File Kontrak .xlsx"
                    aria-label="File Kontrak .xlsx" aria-describedby="button-addon1" value="">
            </div>
        </div>
    </div>
    <div class="col-6 m-auto">
        <button class="btn btn-secondary" type="button" id="button-addon1"
            onclick="RequestCertificate.DownloadTemplate('Template1', 'assets/doc/template/Template1.xlsx')"><i
                class="bx bx-import"></i> Download
            Template
        </button>
    </div>
</div>
@endif
<div class="table-responsive">
    <table id="table-rate" class="table align-middle mb-0 table-nowrap">
        <thead class="table-light">
            <tr>
                <th scope="col">Remarks</th>
                <th scope="col">Nomor Kontrak</th>
                <th scope="col">Job Kontrak</th>
                <th scope="col">Debitur</th>
                <th scope="col">Alamat Debitur</th>
                <th scope="col">Debitur Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if ($data == null)
            <tr class="input" data_id="">
                <td>
                    <input type="text" class="form-control required" id="remarks_contract" placeholder="Remarks"
                        error="Remarks" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="contract_number" placeholder="Nomor Kontrak"
                        error="Nomor Kontrak" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="contract_job" placeholder="Job Kontrak "
                        error="Job Kontrak" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="debitur" placeholder="Debitur" error="Debitur"
                        value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="debitur_address" placeholder="Alamat Debitur"
                        error="Alamat Debitur" value="">
                </td>
                <td>
                    <input type="text" maxlength="12" class="form-control required" id="debitur_price"
                        placeholder="Debitur Price" error="Debitur Price" value=""
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </td>
                <td id="action">

                </td>
            </tr>
            @else
            @if (!empty($data))
            @foreach ($data->RequestContract as $key => $item)
            @if ($item->status == 'DRAFT')
            <tr class="input" data_id="">
                <td>
                    <input type="text" class="form-control required" id="remarks_contract" placeholder="Remarks" error="Remarks"
                        value="{{ isset($item->remarks ) ? $item->remarks : '' }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="contract_number" placeholder="Nomor Kontrak"
                        error="Nomor Kontrak" value="{{ isset($item->contract_number ) ? $item->contract_number : '' }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="contract_job" placeholder="Job Kontrak "
                        error="Job Kontrak" value="{{ isset($item->contract_job) ? $item->contract_job  : '' }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="debitur" placeholder="Debitur" error="Debitur"
                        value="{{ isset($item->debitur) ? $item->debitur : '' }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="debitur_address" placeholder="Alamat Debitur"
                        error="Alamat Debitur" value="{{ isset($item->debitur_address) ? $item->debitur_address : '' }}">
                </td>
                <td>
                    <input type="number" class="form-control required" id="debitur_price" placeholder="Debitur Price"
                        error="Debitur Price" value="{{ isset($item->debitur_price) ? $item->debitur_price : '' }}">
                </td>
                <td id="action">
                    @if ($key > 0)
                    <button type="button" onclick="RequestCertificate.deleteItem(this, event)"
                        class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i
                            class="bx bx-trash-alt"></i></button>
                    @endif
                </td>
            </tr>
            @endif

            @endforeach
            @endif
            @endif
            <tr id="add-item-row">
                <td colspan="6" class="text-start">
                    <a href="" onclick="RequestCertificate.addItem(this, event)">Tambah Item</a>
                </td>
            </tr>

        </tbody>
    </table>
</div>
