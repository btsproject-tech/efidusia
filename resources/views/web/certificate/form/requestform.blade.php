<div>
    <h4 class="card-title">Request Sertificate Data</h4>
    <p class="card-title-desc">Fill all information below</p>
    <form>
        <div class="form-group row mb-4">
            <label for="date_request" class="col-md-2 col-form-label">Tanggal Request</label>
            <div class="col-md-10 m-auto">

                <input type="text" class="form-control data-date required" id="date_request"
                    placeholder="Tanggal Reqeust" error="Tanggal Request"
                    value="{{ isset($data->date_request) ? $data->date_request : '' }}" readonly>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label for="remarks" class="col-md-2 col-form-label">Remarks</label>
            <div class="col-md-10 m-auto">
                <textarea name="remarks" id="remarks" class="form-control required"
                    error="remarks">{{ isset($data->remarks) ? $data->remarks : '' }}</textarea>
            </div>
        </div>
    </form>
</div>
