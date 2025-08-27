@php
$data_status = ['APPROVE', 'REJECT'];
@endphp

{{-- DARI REQUESTOR --}}
<div>
    <h4 class="card-title">DETAIL BY REQUESTOR</h4>
    <p class="card-title-desc">all information below</p>
    <form>
        <div class="form-group row mb-1">
            <label for="creator" class="col-md-2 col-form-label">Requestor</label>
            <div class="col-md-10 m-auto">
                <b>:</b> {{ isset($data->Creator->name ) ? $data->Creator->name : '' }}
            </div>
        </div>
        <div class="form-group row mb-1">
            <label for="remarks" class="col-md-2 col-form-label">Remarks Requestor</label>
            <div class="col-md-10 m-auto">
                <b>:</b> {{ isset($data->RequestContract->remarks ) ? $data->RequestContract->remarks : '' }}
            </div>
        </div>
        <div class="form-group row mb-1">
            <label for="contract_number" class="col-md-2 col-form-label">Nomor Kontrak</label>
            <div class="col-md-10 m-auto">
                <b>:</b> {{ isset($data->RequestContract->contract_number ) ? $data->RequestContract->contract_number :
                '' }}
            </div>
        </div>
        <div class="form-group row mb-1">
            <label for="contract_job" class="col-md-2 col-form-label">Job Kontrak</label>
            <div class="col-md-10 m-auto">
                <b>:</b> {{ isset($data->RequestContract->contract_job ) ? $data->RequestContract->contract_job : '' }}
            </div>
        </div>
        <div class="form-group row mb-1">
            <label for="debitur" class="col-md-2 col-form-label">Debitur Finance</label>
            <div class="col-md-10 m-auto">
                <b>:</b> {{ isset($data->RequestContract->debitur ) ? $data->RequestContract->debitur : '' }}
            </div>
        </div>
        <div class="form-group row mb-1">
            <label for="debitur_address" class="col-md-2 col-form-label">Alamat Debitur</label>
            <div class="col-md-10 m-auto">
                <b>:</b> {{ isset($data->RequestContract->debitur_address ) ? $data->RequestContract->debitur_address :
                '' }}
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="debitur_price" class="col-md-2 col-form-label">Price Debitur</label>
            <div class="col-md-10 m-auto">
                <b>:</b> Rp. {{ isset($data->RequestContract->debitur_price ) ? $data->RequestContract->debitur_price :
                '' }}
            </div>
        </div>
    </form>
</div>
<hr>

{{-- VENDOR --}}
<div>
    <h4 class="card-title">Contract Data Certificate</h4>
    <p class="card-title-desc">Fill all information below</p>
    <div class="table-responsive mb-4">
        <table id="table-rate" class="table align-middle mb-0 table-nowrap">
            <thead class="table-light">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Remarks</th>
                    <th scope="col">Nomor Kontrak</th>
                    <th scope="col">Job Kontrak</th>
                    <th scope="col">Debitur</th>
                    <th scope="col">Alamat Debitur</th>
                    <th scope="col">Debitur Price</th>
                    <th scope="col">Billing Number AHU</th>
                    <th scope="col">Date Input AHU</th>
                    <th scope="col">Nama PNPB</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->RequestContract as $item)
                <tr class="input" data_id="">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ isset($item->remarks ) ? $item->remarks : '' }}
                    </td>
                    <td>
                        {{ isset($item->contract_number ) ? $item->contract_number : '' }}
                    </td>
                    <td>
                        {{ isset($item->contract_job) ? $item->contract_job : '' }}
                    </td>
                    <td>
                        {{ isset($item->debitur ) ? $item->debitur : '' }}
                    </td>
                    <td>
                        {{ isset($item->debitur_address ) ? $item->debitur_address : '' }}
                    </td>
                    <td>
                        {{ isset($item->debitur_price ) ? $item->debitur_price : '' }}
                    </td>
                    <td>
                        {{ isset($item->billing_number_ahu ) ? $item->billing_number_ahu : '' }}
                    </td>
                    <td>
                        {{ isset($item->date_input_ahu ) ? $item->date_input_ahu : '' }}
                    </td>
                    <td>
                        {{ isset($item->name_pnbp) ? $item->name_pnbp : '' }}
                    </td>
                    @endforeach
            </tbody>
        </table>
    </div>
    <form>
        <div class="form-group row mb-4">
            <label for="delegate_to" class="col-md-2 col-form-label">Delegate To</label>
            <div class="col-md-10">
                <div class="input-group">
                    <button class="btn btn-outline-primary" type="button" id="button-addon1"
                        onclick="VerifikasiCertificate.showDataUserNotaris(this)">Pilih</button>
                    <input readonly id="delegate_to" type="text" class="form-control required" error="Pilih Delegate to"
                        placeholder="Pilih Delegate to" aria-label="Pilih Delegate to" aria-describedby="button-addon1"
                        value="{{ isset($data->RequestContract->delegate_to) ? $data->RequestContract->delegate_to .' - ' .$data->RequestContract->UserDelegate->nama_lengkap : '' }}">
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="date_delegate" class="col-md-2 col-form-label">Date Delegate</label>
            <div class="col-md-10">
                <input type="text" class="form-control  data-date" id="date_delegate" placeholder="Date Delegate"
                    value="{{ isset($data->RequestContract->date_delegate ) ? $data->RequestContract->date_delegate  : '' }}">
                <input type="hidden" class="form-control " id="updater" placeholder="updater"
                    value="{{ isset($data->RequestContract->updater) ? $data->RequestContract->updater : session('user_id') }}"
                    readonly>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="seq_numbers" class="col-md-2 col-form-label">Seq Number</label>
            <div class="col-md-10">
                <input type="text" class="form-control" id="seq_numbers" placeholder="Seq Number"
                    value="{{ isset($data->RequestContract->seq_numbers ) ? $data->RequestContract->seq_numbers  : '' }}">

            </div>
        </div>

        {{-- FORM SELECT --}}
        <div class="form-group row mb-4">
            <label for="status" class="col-md-2 col-form-label">status</label>
            <div class="col-md-10">
                <select onchange="VerifikasiCertificate.changeStatus(this, event)" class="form-select select2"
                    id="status">
                    <option>Select</option>
                    @foreach ($data_status as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="content_remarks_verify"></div>
    </form>
</div>
<hr>

{{-- AHU --}}
<div>
    <h4 class="card-title">AHU FORM</h4>
    <p class="card-title-desc">Fill all information below</p>
    <form>
        <div class="form-group row mb-4">
            <label for="billing_number_ahu" class="col-md-2 col-form-label">Billing Number AHU</label>
            <div class="col-md-10">
                <input type="text" class="form-control  data-date" id="billing_number_ahu" placeholder="Date Delegate"
                    value="{{ isset($data->RequestContract->billing_number_ahu ) ? $data->RequestContract->billing_number_ahu  : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="date_input_ahu" class="col-md-2 col-form-label">Date Input AHU</label>
            <div class="col-md-10">
                <input type="text" class="form-control data-date" id="date_input_ahu" placeholder="Date Input AHU"
                    value="{{ isset($data->RequestContract->date_input_ahu ) ? $data->RequestContract->date_input_ahu : '' }}">
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="name_pnbp" class="col-md-2 col-form-label">Nama PNPB </label>
            <div class="col-md-10">
                <input type="text" class="form-control" id="name_pnbp" placeholder="Nama PNPB"
                    value="{{ isset($data->RequestContract->name_pnbp ) ? $data->RequestContract->name_pnbp : '' }}">
            </div>
        </div>
    </form>
</div>
