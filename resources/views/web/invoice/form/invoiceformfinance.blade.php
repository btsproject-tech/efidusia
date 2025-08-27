<div class="row">
    <h4 class="card-title">Invoice Data</h4>
    <p class="card-title-desc">Fill all information below</p>
    <form>
        <div class="form-group row mb-4">
            <label for="invoice_date" class="col-md-2 col-form-label">Invoice Date</label>
            <input type="hidden" id="finance" value="FINANCE">
            <input type="hidden" id="company" value="{{ isset($data->company) ? $data->company : '' }}">
            <input type="hidden" id="batch" value="{{ isset($batch[0]) ? $batch[0]['id'] : '' }}">
            <div class="col-md-10" style="width: 525px">
                <input type="text" readonly class="form-control data-date" error="Invoice Date" id="invoice_date_f"
                    placeholder="Invoice Date"
                    value="{{ isset($data->invoice_date) ? $data->invoice_date : date('Y-m-d') }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="customer_name_f" class="col-md-2 col-form-label">Data Batch</label>
            <div class="col-md-10" style="width: 525px">
                <div class="input-group">
                    <button class="btn btn-outline-primary" type="button" id="button-addon1"
                        onclick="Invoice.showDataBatch('finance')">Pilih</button>
                    <input readonly id="notaris_name" type="text" class="form-control " error="Data Invoice"
                        placeholder="Pilih Data Invoice" aria-label="Pilih Data Invoice"
                        aria-describedby="button-addon1"
                        value="{{ isset($batch[0]) ? $batch[0]['id'] . '-' . $batch[0]['user_notaris']['nama_lengkap'] : '' }}">
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="customer_name_f" class="col-md-2 col-form-label">Customer Name</label>
            <div class="col-md-10" style="width: 525px">
                <div class="input-group">
                    <input type="text" class="form-control" readonly error="Finance" id="financeCompany"
                        placeholder="Finance" value="{{ isset($company->nama_company) ? $company->nama_company : '' }}">
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="customer_name_f" class="col-md-2 col-form-label">Biaya Jasa</label>
            <div class="col-md-10" style="width: 525px">
                <div class="input-group">
                    <input type="text" class="form-control" readonly error="Roe" id="biayaJasa"
                        placeholder="Biaya Jasa" value="{{ isset($company->biaya_jasa) ? $company->biaya_jasa : '' }}">
                </div>
            </div>
        </div>
        <hr>
        <div class="m-2"><b>
                Biaya PNBP
            </b></div>
        <div class="form-group row mb-4">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table" id="table-rate_f2">
                        <thead class="table-light">
                            <tr>
                                <td>Item</td>
                                <td>Unit</td>
                                <td>Qty</td>
                                <td>Curr</td>
                                <td>Total</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $qty = 0;
                            @endphp
                            @if (isset($data_pnbp))
                                {{-- @foreach ($data_pnbp as $key => $item) --}}
                                <tr class="inputF2">
                                    <td>
                                        <input type="text" class="form-control subject_f required" id="subject_f"
                                            error="Subject" value="{{ $data_pnbp['subject'] }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control unit_f" readonly id="unit_f"
                                            error="Unit" value="{{ $data_pnbp['unit'] }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control qty_f" readonly id="qty_f"
                                            error="Quantity" value="{{ $data_pnbp['qty'] }}">
                                    </td>
                                    <td>
                                        <input type="text" id="currency_f" class="form-control currency_f"
                                            value="IDR" error="Currency" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control charge_f" pnbp error="Charge"
                                            id="rate_f" value="{{ $data_pnbp['rate'] }}" readonly
                                            onchange="Invoice.hitungGrandTotalF(this)">
                                    </td>
                                </tr>
                                {{-- @php
                                        $qty += $item['qty'];
                                    @endphp --}}
                                {{-- @endforeach --}}
                                {{-- @else
                                <tr class="inputF2">
                                    <td>
                                        <input type="text" class="form-control subject_f" id="subject_f"
                                            error="Subject" value="pnbp">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control unit_f" id="unit_f" error="Unit"
                                            value="berkas">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control qty_f" id="qty_f" error="Quantity"
                                            value="230">
                                    </td>
                                    <td>
                                        <input type="text" id="currency_f" class="form-control currency_f"
                                            value="IDR" error="Currency">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control charge_f" pnbp id="rate_f"
                                            error="rate" value="14400000">
                                    </td>
                                    <td id="action">
                                    </td>
                                </tr> --}}
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table" id="table-rate_f">
                        <thead class="table-light">
                            <tr>
                                <td>Item</td>
                                <td>Unit</td>
                                <td>Qty</td>
                                <td>Curr</td>
                                <td>Charge</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $qty = 0;
                            @endphp
                            @if (isset($data_rate))
                                @foreach ($data_rate as $key => $item)
                                    <tr class="inputF">
                                        <td>
                                            <input type="text" class="form-control required subject_f"
                                                id="subject_f" error="Subject" value="{{ $item['subject'] }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control required unit_f" id="unit_f"
                                                error="Unit" value="{{ $item['unit'] }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control required qty_f"
                                                onkeyup="Invoice.hitungGrandTotalF(this)" id="qty_f"
                                                error="Quantity" value="{{ $item['qty'] }}">
                                        </td>
                                        <td>
                                            <input type="text" id="currency_f" class="form-control currency_f"
                                                value="IDR" error="Currency" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control charge_f required"
                                                error="Charge" onkeyup="Invoice.hitungGrandTotalF(this)"
                                                id="rate_f" value="{{ $item['rate'] }}">
                                        </td>
                                        <td id="action">
                                            @if ($key > 0)
                                                <button type="button" data-per="finance"
                                                    onclick="Invoice.deleteItem(this, event)"
                                                    class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i
                                                        class="bx bx-trash-alt"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $qty += $item['qty'];
                                    @endphp
                                @endforeach
                            @else
                                <tr class="inputF">
                                    <td>
                                        <input type="text" class="form-control required subject_f" id="subject_f"
                                            error="Subject">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control required unit_f" id="unit_f"
                                            error="Unit">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control required qty_f"
                                            onkeyup="Invoice.hitungGrandTotalF(this)" id="qty_f"
                                            error="Quantity">
                                    </td>
                                    <td>
                                        <input type="text" id="currency_f" class="form-control currency_f"
                                            value="IDR" error="Currency" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control charge_f required"
                                            onkeyup="Invoice.hitungGrandTotalF(this)" id="rate_f" error="rate">
                                    </td>
                                    <td id="action">
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5">
                                    <a href="" onclick="Invoice.addItemInvoiceF(this, event)">Tambah Data</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="roe" class="col-md-2 col-form-label">Roe</label>
            <div class="col-md-10">
                <input type="text" class="form-control" readonly error="Roe" id="roe_f" placeholder="Roe"
                    value="{{ isset($data->roe) ? $data->roe : '1' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="term_of_payment" class="col-md-2 col-form-label">Amount</label>
            <div class="col-md-10">
                <input type="number" class="form-control" readonly error="Amount" id="amount_f"
                    placeholder="Amount" value="{{ isset($data->amount) ? $data->amount : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="roe" class="col-md-2 col-form-label">TAX</label>
            <div class="col-md-10">
                <input type="number" class="form-control" readonly error="TAX" id="tax_f" placeholder="TAX"
                    value="{{ isset($data->tax) ? $data->tax : '11' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="salesman" class="col-md-2 col-form-label">Grand Total</label>
            <div class="col-md-10">
                <input readonly type="text" class="form-control" error="Grand Total" id="grand_total_f"
                    placeholder="Grand Total" value="{{ isset($data->grand_total) ? $data->grand_total : '' }}">
            </div>
        </div>
        {{-- <div class="form-group row mb-4">
            <label for="salesman" class="col-md-2 col-form-label">Biaya Meterai</label>
            <div class="col-md-10">
                <input type="text" class="form-control required" onkeyup="Invoice.hitungGrandTotalF(this)"
                    error="Biaya Meterai" id="biaya_meterai_f" placeholder="Biaya Meterai"
                    value="{{ isset($data->materai) ? $data->materai : '' }}">
            </div>
        </div> --}}
    </form>
</div>
<div class="row mt-4">
    <div class="col-sm-6">
        <a href="" onclick="Invoice.cancel(this, event)"
            class="btn text-muted d-none d-sm-inline-block btn-link">
            <i class="mdi mdi-arrow-left me-1"></i> Kembali </a>
    </div> <!-- end col -->
    <div class="col-sm-6">
        <div class="text-end">
            <a href="" onclick="Invoice.submit('finance', this, event)" class="btn btn-success">
                <i class="mdi mdi-truck-fast me-1"></i> Submit </a>
        </div>
    </div>
    <!-- end col -->
</div> <!-- end row -->
