<input type="hidden" id="id" value="{{ isset($id) ? $id : '' }}">

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">{{ $title }}</h4>
                <hr>

                <form>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Perusahaan</label>
                                <select {{ strtolower($akses) != 'superadmin' ? 'readonly' : '' }} class="form-control select2 required" error="Perusahaan" id="company">
                                    @foreach ($data_company as $item)
                                        <option value="{{ $item['id'] }}" {{ isset($data->company) ? $data->company == $item['id'] ? 'selected' : ''  : ''}}>{{ $item['nama_company'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Nama Karyawan</label>
                                <div>
                                    <input type="text" id="nama" class="form-control required" error="Nama"
                                        placeholder="Nama" value="{{ isset($data->nama_lengkap) ? $data->nama_lengkap : '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Contact</label>
                                <div>
                                    <input type="text" id="contact" class="form-control required" error="Contact"
                                        placeholder="Contact" value="{{ isset($data->contact) ? $data->contact : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>NIK Karyawan</label>
                                <div>
                                    <input type="text" id="nik" class="form-control required" error="Nik"
                                        placeholder="Nik" value="{{ isset($data->nik) ? $data->nik : '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Jabatan</label>
                                <div>
                                    <input type="text" id="jabatan" class="form-control required" error="Jabatan"
                                        placeholder="Jabatan" value="{{ isset($data->jabatan) ? $data->jabatan : '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <div>
                                    <input type="text" id="email" class="form-control required" error="Email"
                                        placeholder="Email" value="{{ isset($data->email) ? $data->email : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div>
                            <button type="submit" onclick="Karyawan.submit(this, event)"
                                class="btn btn-primary waves-effect waves-light me-1">
                                Submit
                            </button>
                            <button type="reset" onclick="Karyawan.cancel(this, event)"
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
