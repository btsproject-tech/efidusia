<div>
    <h4 class="card-title">Request Sertificate Data</h4>
    <p class="card-title-desc">Fill all information below</p>
    <form>
        <div class="form-group row ">
            <label for="no_request" class="col-md-2 col-form-label">No Request</label>
            <div class="col-md-10">
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
        <div class="form-group row ">
            <label for="status" class="col-md-2 col-form-label">status</label>
            <div class="col-md-10 m-auto">
                <div>
                    <b>:</b> <span class="badge
                    @if ($data->status == 'DRAFT')
                    bg-warning
                    @elseif ($data->status == 'REJECT')
                    bg-danger
                    @elseif ($data->status == 'APPROVE')
                    bg-success
                    @endif
                    " style="font-size:12px;">{{
                        $data->status }}
                    </span>
                </div>
            </div>
        </div>

    </form>
</div>
