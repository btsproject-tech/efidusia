<div class="row notaris">
    <h4 class="card-title">Invoice Data</h4>
    <p class="card-title-desc">Fill all information below</p>
    <form id="form-notaris">
        <div class="form-group row mb-4">
            <label for="invoice_date" class="col-md-2 col-form-label">Invoice Date</label>
            <input type="hidden" id="notaris" value="NOTARIS">
            <div class="col-md-10" style="width: 525px">
                <input type="text" readonly class="form-control data-date" error="Invoice Date" id="invoice_date_n"
                    placeholder="Invoice Date"
                    value="{{ isset($data->invoice_date) ? $data->invoice_date : date('Y-m-d') }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="customer_name_n" class="col-md-2 col-form-label">Customer Name</label>
            <div class="col-md-10" style="width: 525px">
                {{-- <select id="customer_name_n" class="form-control required select2" error="Customer Notaris">
                    <option value="">Pilih Customer</option>
                    @if (!empty($data_notaris))
                        @foreach ($data_notaris as $item)
                            <option value="{{ $item['id'] }}"
                                {{ isset($data->company) ? ($data->company == $item['id'] ? 'selected' : '') : '' }}>
                                {{ $item['nama_company'] }}</option>
                        @endforeach
                    @endif
                </select> --}}
                <div class="input-group">
                    <button class="btn btn-outline-primary" type="button" id="button-addon1"
                        onclick="Invoice.showDataBatch('notaris')">Pilih</button>
                    <input readonly id="no_invoice" type="text" class="form-control required" error="Data Invoice"
                        placeholder="Pilih Data Invoice" aria-label="Pilih Data Invoice"
                        aria-describedby="button-addon1" {{-- data_id="{{ isset($data->no_invoice) ? $data->shipping_excecution : '' }}" --}}
                        {{-- value="{{ isset($data->no_invoice) ? $data->no_invoice : '' }}" --}}
                        >
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table" id="table-rate_n">
                        <thead class="table-light">
                            <tr>
                                <td>Charge Description</td>
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
                                    <tr class="input">
                                        <td>
                                            <input type="text" class="form-control required" error="Subject"
                                                id="subject_n" value="{{ $item['subject'] }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control required" error="Unit"
                                                id="unit_n" value="{{ $item['unit'] }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control required qty_n"
                                                onkeyup="Invoice.hitungGrandTotalN(this)" error="Quantity"
                                                id="qty_n" value="{{ $item['qty'] }}">
                                        </td>
                                        <td>
                                            <input type="text" id="currency_n" class="form-control" value="IDR"
                                                error="Currency" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control charge_n required" error="Charge"
                                                onkeyup="Invoice.hitungGrandTotalN(this)" id="rate_n"
                                                value="{{ $item['rate'] }}">
                                        </td>
                                        <td id="action">
                                            @if ($key > 0)
                                                <button type="button" data-per="notaris"
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
                                <tr class="input">
                                    <td>
                                        <input type="text" class="form-control required" id="subject_n"
                                            error="Subject">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control required" id="unit_n"
                                            error="Unit">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control required qty_n"
                                            onkeyup="Invoice.hitungGrandTotalN(this)" id="qty_n" error="Quantity">
                                    </td>
                                    <td>
                                        <input type="text" id="currency_n" class="form-control" value="IDR"
                                            error="Currency" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control charge_n required"
                                            onkeyup="Invoice.hitungGrandTotalN(this)" id="rate_n" error="Charge">
                                    </td>
                                    <td id="action">
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5">
                                    <a href="" onclick="Invoice.addItemInvoiceN(this, event)">Tambah Data</a>
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
                <input type="text" class="form-control" readonly error="Roe" id="roe_n" placeholder="Roe"
                    value="{{ isset($data->roe) ? $data->roe : '1' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="term_of_payment" class="col-md-2 col-form-label">Amount</label>
            <div class="col-md-10">
                <input type="number" class="form-control" readonly error="Amount" id="amount_n"
                    placeholder="Amount" value="{{ isset($data->amount) ? $data->amount : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="roe" class="col-md-2 col-form-label">TAX</label>
            <div class="col-md-10">
                <input type="number" readonly class="form-control" error="TAX" id="tax_n" placeholder="TAX"
                    value="{{ isset($data->tax) ? $data->tax : '11' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="salesman" class="col-md-2 col-form-label">Grand Total</label>
            <div class="col-md-10">
                <input readonly type="text" class="form-control" error="Grand Total" id="grand_total_n"
                    placeholder="Grand Total" value="{{ isset($data->grand_total) ? $data->grand_total : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="salesman" class="col-md-2 col-form-label">Biaya Meterai</label>
            <div class="col-md-10">
                <input type="text" class="form-control required" error="Biaya Meterai" id="biaya_meterai_n"
                    onkeyup="Invoice.hitungGrandTotalN(this)" placeholder="Biaya Meterai"
                    value="{{ isset($data->materai) ? $data->materai : '' }}" required>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6">
                <a href="" onclick="Invoice.cancel(this, event)"
                    class="btn text-muted d-none d-sm-inline-block btn-link">
                    <i class="mdi mdi-arrow-left me-1"></i> Kembali </a>
            </div> <!-- end col -->
            <div class="col-sm-6">
                <div class="text-end">
                    <button type="button" class="btn btn-success"
                        onclick="Invoice.submit('notaris', this, event)"><i class="mdi mdi-truck-fast me-1"></i>
                        Submit</a></button>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </form>
</div>
