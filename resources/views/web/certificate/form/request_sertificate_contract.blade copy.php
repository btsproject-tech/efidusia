
{{-- {{ dd($data) }} --}}

<div class="row mb-4">
    <div class="col-sm-12 text-end">
        <a href="" onclick="RequestCertificate.cancel(this, event)" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Kembali </a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4 class="card-title">Request Sertificate Data</h4>
                    <form>
                        <div class="form-group row ">
                            <label for="no_request" class="col-md-2 col-form-label">No Request</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> {{ isset($data->no_request) ? $data->no_request : '' }}
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="date_request" class="col-md-2 col-form-label">Tanggal Request</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> {{ isset($data->date_request) ? $data->date_request : '' }}
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="remarks" class="col-md-2 col-form-label">Remarks</label>
                            <div class="col-md-10 m-auto">
                                <div>
                                    <b>:</b> {!! isset($data->remarks) ? $data->remarks : '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="creator" class="col-md-2 col-form-label">Requestor</label>
                            <div class="col-md-10 m-auto">
                                <b>:</b> {{ isset($data->Creator->name ) ? $data->Creator->name : '' }}
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="status" class="col-md-2 col-form-label">Status</label>
                            <div class="col-md-10 m-auto">
                                <span class="badge
                                @if ($data->status == 'DRAFT')
                                bg-warning
                                @elseif ($data->status == 'REJECT')
                                bg-danger
                                @elseif ($data->status == 'APPROVE')
                                bg-success
                                @elseif ($data->status == 'ON PROCESS')
                                bg-secondary
                                @elseif ($data->status == 'COMPLETE')
                                bg-secondary
                                @elseif ($data->status == 'DONE')
                                bg-primary
                                @endif
                                " style="font-size:12px;">{{
                                    $data->status }}
                                </span>
                            </div>
                        </div>

                    </form>
                </div>
                <hr>

                <div class="mb-4"></div>

                {{-- VENDOR --}}
                <div>
                    <h4 class="card-title">Contract Data Certificate</h4>
                    <div class="table-responsive">
                        <table id="detail_contract" class="table align-middle mb-0 table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Remarks</th>
                                    <th scope="col">Nomor Kontrak</th>
                                    <th scope="col">Job Kontrak</th>
                                    <th scope="col">Debitur</th>
                                    <th scope="col">Alamat Debitur</th>
                                    <th scope="col">Debitur Price</th>
                                    <th scope="col">Billing Number AHU</th>
                                    <th scope="col">Date Input AHU</th>
                                    <th scope="col">Nama PNPB</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($data->RequestContract as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge
                                        @if ($item->status == 'DRAFT')
                                        bg-warning
                                        @elseif ($item->status == 'REJECT')
                                        bg-danger
                                        @elseif ($item->status == 'APPROVE')
                                        bg-success
                                        @elseif ($item->status == 'DONE')
                                        bg-primary
                                        @elseif ($item->status == 'COMPLETE')
                                        bg-secondary
                                        @elseif ($item->status == 'FINISHED')
                                        bg-dark
                                        @endif
                                        " style="font-size:12px;">{{
                                            $item->status }}
                                        </span>

                                        @if ($item->status == 'DONE')
                                        <br>
                                        <button type="button" class="btn btn-outline-success btn-sm mt-1 waves-effect"
                                            onclick="RequestCertificate.showModalWarkah('{{ $item->id }}','{{ $item->contract_number }}')">
                                            <i class="mdi mdi-upload"></i> Upload Warkah
                                        </button>
                                        @elseif ($item->status == 'FINISHED')
                                        <br>
                                        <button type="button" class="btn btn-primary btn-sm mt-1 waves-effect"
                                            data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg{{ $item->id }}">
                                            <i class="mdi mdi-eye"></i> Lihat Detail
                                        </button>
                                        @endif
                                    </td>
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
                                        Rp. {{ isset($item->debitur_price ) ? $item->debitur_price : '' }}
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
                                </tr>
                                @include('web.certificate.modal.detailDataModal')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('web.certificate.modal.detailModal')
            </div>
        </div>
    </div>
</div>
<!-- end row -->

