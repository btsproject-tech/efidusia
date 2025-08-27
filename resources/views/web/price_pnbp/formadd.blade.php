<input type="hidden" id="id" value="{{ isset($id) ? $id : '' }}">

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-custom mb-4">
                    <li class="nav-item">
                        <a class="nav-link fw-bold p-3 active" href="#">
                            {{ $title }}</a>
                    </li>
                </ul>

                <form>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Batas Bawah</label>
                                <div>
                                    <input type="number" id="batas_bawah" class="form-control required" error="Batas Bawah"
                                        placeholder="Batas Bawah"
                                        value="{{ isset($data->batas_bawah) ? $data->batas_bawah : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Batas Atas</label>
                                <div>
                                    <input type="number" id="batas_atas" class="form-control required" error="Batas Atas"
                                        placeholder="Batas Atas"
                                        value="{{ isset($data->batas_atas) ? $data->batas_atas : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Biaya</label>
                                <div>
                                    <input type="number" id="biaya" class="form-control required" error="Biaya"
                                        placeholder="Biaya"
                                        value="{{ isset($data->biaya) ? $data->biaya : '' }}">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mb-0">
                        <div>
                            <button type="submit" onclick="PricePnbp.submit(this, event)"
                                class="btn btn-primary waves-effect waves-light me-1">
                                Submit
                            </button>
                            <button type="reset" onclick="PricePnbp.cancel(this, event)"
                                class="btn btn-secondary waves-effect">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <!-- end select2 -->

    </div>


</div>
<!-- end row -->
