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
                                <label>Nama Perusahaan</label>
                                <div>
                                    <input type="text" class="form-control" name="nama_company" id="nama_company"
                                        placeholder="Enter company name">
                                    <small id="nama_company-error-text" class="form-text" style="color: red;"></small>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Perusahaan</label>
                                <select {{ strtolower($akses) !='superadmin' ? 'readonly' : '' }}
                                    class="form-control select2 required" error="Jenis Perusahaan" id="type">
                                    @foreach ($data_jenis as $item)
                                    <option value="{{ $item }}" {{ isset($data->type) ? ($data->type == $item ?
                                        'selected' : '') : '' }}>
                                        {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Email</label>
                                <div>
                                    <input type="email" id="email" class="form-control required" error="Email"
                                        placeholder="Email" value="{{ isset($data->email) ? $data->email : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>No. HP</label>
                                <div>
                                    <input type="number" id="no_hp" class="form-control required" error="No. HP"
                                        placeholder="No. HP" value="{{ isset($data->no_hp) ? $data->no_hp : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Alamat</label>
                                <div>
                                    <textarea name="" id="alamat" class="required form-control"
                                        error="Alamat">{{ isset($data->alamat) ? $data->alamat : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div>
                            <button type="submit" onclick="Company.submit(this, event)"
                                class="btn btn-primary waves-effect waves-light me-1">
                                Submit
                            </button>
                            <button type="reset" onclick="Company.cancel(this, event)"
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
