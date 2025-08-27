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
                            <div class="mb-3">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <select class="form-control select2" id="provinsi_company"
                                    onchange="Saksi.fetchCities(this.value,'company')">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <small id="provinsi-error-text" class="form-text"
                                    style="color: red;"></small>
                                <input type="hidden" id="provinsi_name_company"
                                    name="provinsi_name_company" value="">
                            </div>

                            <div class="mb-3">
                                <label for="kota" class="form-label">Kota</label>
                                <select class="form-control select2" id="kota_company" disabled
                                    onchange="Saksi.fetchDistricts(this.value,'company')">
                                    <option value="">Pilih Kota</option>
                                </select>
                                <small id="kota-error-text" class="form-text"
                                    style="color: red;"></small>
                                <input type="hidden" id="kota_name_company" name="kota_name_company"
                                    value="">
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

                            <div class="mb-3">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <select class="form-control select2" id="kecamatan_company" disabled
                                    onchange="Saksi.fetchVillages(this.value,'company')">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <small id="kecamatan-error-text" class="form-text"
                                    style="color: red;"></small>
                                <input type="hidden" id="kecamatan_name_company"
                                    name="kecamatan_name_company" value="">
                            </div>

                            <div class="mb-3">
                                <label for="keldesa" class="form-label">Kelurahan/Desa</label>
                                <select class="form-control select2" id="keldesa_company" disabled>
                                    <option value="">Pilih Kelurahan/Desa</option>
                                </select>
                                <small id="keldesa-error-text" class="form-text"
                                    style="color: red;"></small>
                                <input type="hidden" id="keldesa_name_company"
                                    name="keldesa_name_company" value="">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select style="width: 200px;" {{ strtolower($akses) != 'superadmin' ? 'readonly' : '' }} class="required" error="Status" id="saksi">
                                    <option value="">PILIH SAKSI</option>
                                    @foreach ($data_status as $item)
                                        <option value="{{ $item }}" {{ isset($data->status) ? $data->status == $item ? 'selected' : ''  : ''}}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div>
                            <button type="submit" onclick="Saksi.submit(this, event)"
                                class="btn btn-primary waves-effect waves-light me-1">
                                Submit
                            </button>
                            <button type="reset" onclick="Saksi.cancel(this, event)"
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
