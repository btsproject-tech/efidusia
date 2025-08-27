<input type="hidden" value="{{ $data->id }}" id="id">
<input type="hidden" value="{{ $data->no_payment }}" id="no_invoice">

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div id="print-content">
                    <div class="invoice-title">
                        <div class="mb-4">
                            {{-- <img src="{{ asset('assets/images/logo_panjang.png') }}" alt="logo" height="150" /> --}}
                            <img src="{{ asset('assets/images/kop_header_mas.jpeg') }}" alt="logo" height="200"
                                width="100%" />
                        </div>
                        <div class="mb-4">
                            <h4 style="text-align: right; font-size:30px;">
                                <label style="font-weight: 700;">Invoice Payment</label><br />
                                {{-- <label for="" style="font-weight: 400; display:no;">{!! $company->alamat !!}</label> --}}
                            </h4>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tr>
                                    <td><label class="font-size-14" for="" style="font-weight: 700;">Customer
                                            Name</label></td>
                                </tr>
                                <tr>
                                    <td><label class="font-size-15" for=""
                                            style="padding:
                                            0px;font-weight: 500;">{{ strtoupper($data->nama_company) }}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;"><label class="font-size-15" for=""
                                            style="padding: 0px;font-weight: 500;">
                                            <p>{!! strtoupper($data->alamat) !!}</p>
                                        </label></td>
                                </tr>
                                <tr>
                                    <td><label class="font-size-15" for=""
                                            style="padding:
                                            0px;font-weight: 500;">{{ strtoupper($data->no_hp) }}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="font-size-15" for=""
                                            style="padding:
                                            0px;font-weight: 500;"><b>NPWP:</b>
                                            {{ $data->npwp == '' ? '-' : $data->npwp }}</label></td>
                                </tr>
                            </table>
                        </div>
                        {{-- <div class="col-md-6">
                            <table style="border: 1px solid #000;width: 100%;">
                                <tr>
                                    <td style="padding: 6px;" colspan="3"><label class="font-size-14" for=""
                                            style="font-weight: 700;">LUMISHIP BANK
                                            ACCOUNT</label></td>
                                </tr>
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">&nbsp;&nbsp;Bank Name</label>
                                    </td>
                                    <td style=""><label class="font-size-14" for=""
                                            style="font-weight: 500;">:</label>
                                    </td>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">Mandiri</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">&nbsp;&nbsp;Account
                                            Name</label></td>
                                    <td style=""><label class="font-size-14" for=""
                                            style="font-weight: 500;">:</label>
                                    </td>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">{{ strtoupper($company->nama_company) }}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">&nbsp;&nbsp;Account
                                            Number</label></td>
                                    <td style=""><label class="font-size-14" for=""
                                            style="font-weight: 500;">:</label>
                                    </td>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">14000 2018
                                            9503</label></td>
                                </tr>
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">&nbsp;&nbsp;Currency</label>
                                    </td>
                                    <td style=""><label class="font-size-14" for=""
                                            style="font-weight: 500;">:</label>
                                    </td>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">IDR
                                            (Rupiah)</label></td>
                                </tr>
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">&nbsp;&nbsp;Branch</label></td>
                                    <td style=""><label class="font-size-14" for=""
                                            style="font-weight: 500;">:</label>
                                    </td>
                                    <td><label class="font-size-14" for=""
                                            style="padding: 0px;font-weight: 500;">KCP Sby
                                            Tanjungperak</label></td>
                                </tr>
                            </table>
                        </div> --}}
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <table style="width: 100%;">
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="font-weight: 700; padding: 8px; margin-right: 10px">PAYMENT</label>
                                    </td>
                                    <td style="text-align: center;"><label class="font-size-14" for=""
                                            style="font-weight: 700;padding: 8px;">{{ $data->no_payment }}</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table style="width: 100%;">
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="font-weight: 700;padding: 8px;">Payment Date : </label></td>
                                    <td style="text-align: right;"><label class="font-size-14" for=""
                                            style="font-weight: 700;padding: 8px;">{{ $data->payment_date == '' ? '' : date('d F Y', strtotime($data->payment_date)) }}</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table style="width: 100%;">
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="font-weight: 700;padding: 8px;">NO INVOICE</label></td>
                                    <td style="text-align: center;"><label class="font-size-14" for=""
                                            style="font-weight: 700;padding: 8px;">{{ $data->no_invoice }}</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table style="width: 100%;">
                                <tr>
                                    <td><label class="font-size-14" for=""
                                            style="font-weight: 700;padding: 8px;">Invoice Date : </label></td>
                                    <td style="text-align: right;"><label class="font-size-14" for=""
                                            style="font-weight: 700;padding: 8px;">{{ $data->invoice_date == '' ? '' : date('d F Y', strtotime($data->invoice_date)) }}</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width: 100%;">
                                <tr>
                                    <table class="table mb-0" style="font-size: 15px">
                                        <tr>
                                            <td style=""><label class="" for=""
                                                    style="font-weight: 700;padding: 8px;">No</label>
                                            </td>
                                            <td style=""><label class="" for=""
                                                    style="font-weight: 700;padding: 8px;">Item</label></td>
                                            {{-- <td style=""><label class="" for=""
                                                    style="font-weight: 700;padding: 8px;">Charge</label></td> --}}
                                            <td style=""><label class="" for=""
                                                    style="font-weight: 700;padding: 8px;">Unit</label></td>
                                            <td style=""><label class="" for=""
                                                    style="font-weight: 700;padding: 8px;">Quantity</label></td>
                                            <td style=""><label class="" for=""
                                                    style="font-weight: 700;padding: 8px;">Currency</label></td>
                                            <td style=""><label class="" for=""
                                                    style="font-weight: 700;padding: 8px;">Sub Total</label></td>
                                        </tr>
                                        @if (!empty($rate_akhir))
                                            <tr>
                                                <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">1</label>
                                                </td>
                                                <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">{{ $rate_akhir['subject'] }}</label>
                                                </td>
                                                {{-- <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">-</label>
                                                </td> --}}
                                                <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">{{ $rate_akhir['unit'] }}</label>
                                                </td>
                                                <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">{{ $rate_akhir['qty'] }}</label>
                                                </td>
                                                <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">{{ $rate_akhir['currency'] }}</label>
                                                </td>
                                                <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">{{ number_format($rate_akhir['rate'], 0, ',', '.') }}</label>
                                                </td>
                                            </tr>
                                        @endif
                                        @php
                                            $total = 0;
                                            $no = 2;
                                        @endphp
                                        @if (!empty($data_item))
                                            @foreach ($data_item as $item)
                                                @php
                                                    $amount = $item['rate'];
                                                    $qty = $item['qty'];
                                                    $total += $item['qty'] * $amount;
                                                    $totalTax = (($total / (1 + $data->tax / 100)) * $data->tax) / 100;
                                                    $hasil = $qty * $amount - $totalTax;
                                                    $dpp = $qty * $amount - $totalTax;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <label class="" for=""
                                                            style="font-weight: 400;padding: 8px;">{{ $no++ }}</label>
                                                    </td>
                                                    <td>
                                                        <label class="" for=""
                                                            style="font-weight: 400;padding: 8px;">{{ $item['subject'] }}</label>
                                                    </td>
                                                    {{-- <td>
                                                        <label class="" for=""
                                                            style="font-weight: 400;padding: 8px;">{{ number_format($item['rate'], 0, ',', '.') }}</label>
                                                    </td> --}}
                                                    <td>
                                                        <label class="" for=""
                                                            style="font-weight: 400;padding: 8px;">{{ $item['unit'] }}</label>
                                                    </td>
                                                    <td>
                                                        <label class="" for=""
                                                            style="font-weight: 400;padding: 8px;">{{ $item['qty'] }}</label>
                                                    </td>
                                                    <td>
                                                        <label class="" for=""
                                                            style="font-weight: 400;padding: 8px;">{{ $item['currency'] }}</label>
                                                    </td>
                                                    <td>
                                                        <label class="" for=""
                                                            style="font-weight: 400;padding: 8px;">{{ number_format($hasil, 0, ',', '.') }}</label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3"></td>
                                                <td colspan="1">
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">DPP</label>
                                                </td>
                                                <td colspan="1">
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;"></label>
                                                </td>
                                                <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">{{ number_format($dpp, 0, ',', '.') }}</label>
                                                </td>
                                            </tr>
                                            @php
                                                // $totalTax = (($total / (1 + $data->tax / 100)) * $data->tax) / 100;
                                            @endphp
                                            <tr>
                                                <td colspan="3"></td>
                                                <td colspan="1">
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">PPN</label>
                                                </td>
                                                <td colspan="1">
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">{{ $data->tax }}
                                                        %</label>
                                                </td>
                                                <td>
                                                    <label class="" for=""
                                                        style="font-weight: 400;padding: 8px;">{{ number_format($totalTax, 0, ',', '.') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td colspan="1">
                                                    <label class="font-size-14" for=""
                                                        style="font-weight: 700;padding: 8px;">TOTAL</label>
                                                </td>
                                                <td colspan="1">
                                                    <label class="font-size-14" for=""
                                                        style="font-weight: 700;padding: 8px;">IDR</label>
                                                </td>
                                                <td>
                                                    <label class="font-size-14" for=""
                                                        style="font-weight: 700;padding: 8px;">{{ number_format($data->grand_total, 0, ',', '.') }}</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td colspan="1">
                                                    <label class="font-size-13" for=""
                                                        style="font-weight: 700;padding: 8px;">TOTAL DIBAYARKAN</label>
                                                </td>
                                                <td colspan="1">
                                                    <label class="font-size-14" for=""
                                                        style="font-weight: 700;padding: 8px;">IDR</label>
                                                </td>
                                                <td>
                                                    <label class="font-size-14" for=""
                                                        style="font-weight: 700;padding: 8px;">{{ number_format($data->grand_total, 0, ',', '.') }}</label>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </tr>
                                <div class="mt-4" style="font-size: 15px">
                                    <tr>
                                        <td colspan="" style="padding-left: 8px;">
                                            <label class="" for=""
                                                style="font-weight: 700;"><b>Terbilang</b>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="" style="padding-left: 8px;">
                                            <label class="" for="" style="font-weight: 700;"><b>:</b>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="" style="padding-left: 8px;">
                                            <label class="" for="" style="font-style: italic">
                                                {{ $terbilang }}
                                            </label>
                                        </td>
                                    </tr>
                                </div>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <table>
                                <tr style="font-size: 20px">
                                    <td>
                                        <label><b><u>Bank account info:</u></b></label>
                                    </td>
                                </tr>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label><b>Bank Mandiri</b></label>
                                    </td>
                                </tr>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label>Acc No : 167 000 500 8619</label>
                                    </td>
                                </tr>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label>A/N : PT Maju Anugerah Solusindo</label>
                                    </td>
                                </tr>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label><b>BNI</b></label>
                                    </td>
                                </tr>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label>Acc No : 1727480290</label>
                                    </td>
                                </tr>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label>A/N : PT Maju Anugerah Solusindo</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <table>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label>Hormat Kami :</label>
                                    </td>
                                </tr>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label>PT Maju Anugerah Solusindo</label>
                                    </td>
                                </tr>
                                <tr style="font-size: 15px; height: 100px;">
                                    <td></td>
                                </tr>
                                <tr style="font-size: 15px">
                                    <td>
                                        <label>{{ strtoupper($data->nama) }}</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr style="height: 4px;background-color: #000;">
                    <center>
                        <div style="font-size: 20px">
                            <label style="font-weight: 700;">PT. MAJU ANUGERAH SOLUSINDO</label> <br />
                            <label for="" style="font-weight: 400;">Ruko I-01 No.36 Perumahan CitraGrand
                                Cibubur. Jatikarya, Jatisampurna, Bekasi. Jawa Barat</label>
                        </div>
                    </center>
                    <div class="row" style="padding-left: 8px;">
                    </div>
                </div>
                <div class="d-print-none">
                    <div class="float-end">
                        <a href="" filename="{{ $data->no_payment }}-{{ 'invoice_payment' }}"
                            onclick="InvoicePayment.print(this, event)"
                            class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
                        <a href="" onclick="InvoicePayment.cancel(this, event)"
                            class="btn btn-info w-md waves-effect waves-light">Kembali</a>
                        @if ($data->status == 'DRAFT')
                            <a href="javascript: void(0);" onclick="InvoicePayment.confirm(this, event)"
                                class="btn btn-primary w-md waves-effect waves-light">Confirm</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
