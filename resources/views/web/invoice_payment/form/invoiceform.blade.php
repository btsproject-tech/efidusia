<div>
    <h4 class="card-title">Invoice Payment Data</h4>
    <p class="card-title-desc">Fill all information below</p>
    <form>
        <div class="form-group row mb-4">
            <label for="payment_date" class="col-md-2 col-form-label">Tanggal Pengajuan</label>
            <div class="col-md-10">
                <input type="text" readonly class="form-control data-date" error="Invoice Date" id="payment_date"
                    placeholder="Invoice Date"
                    value="{{ isset($data->payment_date) ? $data->payment_date : date('Y-m-d') }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="shipping_line" class="col-md-2 col-form-label">Data Invoice</label>
            <div class="col-md-10">
                <div class="input-group">
                    <button class="btn btn-outline-primary" type="button" id="button-addon1"
                        onclick="InvoicePayment.showDataInvoice(this, 'origin')">Pilih</button>
                    <input readonly id="no_invoice" type="text" class="form-control required" error="Data Invoice"
                        placeholder="Pilih Data Invoice" aria-label="Pilih Data Invoice"
                        aria-describedby="button-addon1" {{-- data_id="{{ isset($data->no_invoice) ? $data->shipping_excecution : '' }}" --}}
                        value="{{ isset($data->no_invoice) ? $data->no_invoice : '' }}">
                </div>
            </div>
        </div>

        <div class="form-group row mb-4">
            <label for="bukti_transfer" class="col-md-2 col-form-label">Bukti Transfer</label>
            <div class="col-md-10">
                <div class="input-group">
                    @if (isset($data->file_payment))
                        <button class="btn btn-outline-primary bx bx-detail" data_file="{{ $data->path_file }}"
                            data_name="{{ $data->file_payment }}" type="button" id="button-addon1"
                            onclick="InvoicePayment.viewData(this)"></button>
                    @endif
                    <button class="btn btn-outline-secondary bx bx-upload" type="button" id="button-addon1"
                        onclick="InvoicePayment.addFile(this)"></button>
                    <input id="file" type="text" readonly class="form-control required"
                        placeholder="Pilih Data File" aria-label="Pilih Data File"
                        tipe="{{ isset($file_tipe) ? $file_tipe : '' }}"
                        src="{{ isset($data->path_file) ? $data->path_file : '' }}" error="Data File"
                        aria-describedby="button-addon1"
                        value="{{ isset($data->file_payment) ? $data->file_payment : '' }}">
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="remarks" class="col-md-2 col-form-label">Remarks</label>
            <div class="col-md-10">
                <input type="text" class="form-control" error="Remarks" id="remarks" placeholder="Remarks"
                    value="{{ isset($data->remarks) ? $data->remarks : '' }}">
            </div>
        </div>
        <input type="hidden" class="form-control" id="data_invoice" value="{{ isset($data->id) ? $data->id : '' }}">
        <div class="form-group row mb-4">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table" id="table-rate">
                        <thead class="table-light">
                            <tr>
                                <td>Charge Description</td>
                                <td>Unit</td>
                                <td>Qty</td>
                                <td>Curr</td>
                                <td>Charge</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $qty = 0;
                            @endphp
                            @if (isset($data_item))
                                @foreach ($data_item as $key => $item)
                                    <tr class="input">
                                        <td>
                                            <input type="text" class="form-control" readonly id="subject"
                                                value="{{ $item->subject }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" readonly id="unit"
                                                value="{{ $item->unit }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" readonly id="qty"
                                                value="{{ $item->qty }}">
                                        </td>
                                        <td>
                                            <input type="" class="form-control" id="currency" readonly
                                                error="Currency" value="{{ $item->currency }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" readonly id="rate"
                                                value="{{ $item->rate }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="input">
                                    <td class="text-center" colspan="8">
                                        Tidak ada data ditemukan
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="roe" class="col-md-2 col-form-label">Roe</label>
            <div class="col-md-10">
                <input type="text" class="form-control" readonly error="Roe" id="roe" placeholder="Roe"
                    value="{{ isset($data->roe) ? $data->roe : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="number_of_bl" class="col-md-2 col-form-label">Total Quantity</label>
            <div class="col-md-10">
                <input type="number" readonly class="form-control" error="Quantity" id="quantity"
                    placeholder="Quantity" value="{{ isset($total_data) ? $total_data : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="term_of_payment" class="col-md-2 col-form-label">Amount</label>
            <div class="col-md-10">
                <input type="number" class="form-control" readonly error="Amount" id="amount"
                    placeholder="Amount" value="{{ isset($data->amount) ? $data->amount : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="roe" class="col-md-2 col-form-label">TAX</label>
            <div class="col-md-10">
                <input type="number" readonly class="form-control" error="TAX" id="tax" placeholder="TAX"
                    value="{{ isset($data->tax) ? $data->tax : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="salesman" class="col-md-2 col-form-label">Grand Total</label>
            <div class="col-md-10">
                <input readonly type="text" class="form-control" error="Grand Total" id="grand_total"
                    placeholder="Grand Total" value="{{ isset($data->grand_total) ? $data->grand_total : '' }}">
            </div>
        </div>
    </form>
</div>
