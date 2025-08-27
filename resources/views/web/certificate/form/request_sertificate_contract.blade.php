<style>
    table th,
    table td {
        min-width: 100px;
        max-width: 300px;

    }
</style>
<h4 class="card-title">Contract Data Certificate</h4>
<p class="card-title-desc">Fill all information below</p>
@if ($data == null)
<div class="row">
    <div class="col-6">
        <div class="mb-4">
            <label>File Kontrak .xlsx</label>
            <div class="input-group">
                <button class="btn btn-outline-primary" type="button" id="button-addon1"
                    onclick="RequestCertificate.ambilData(this, event)"><i class="bx bx-import"></i> Import
                    Data</button>
                <input id="import-file" type="text" class="form-control" readonly
                    placeholder="File Kontrak .xlsx" aria-label="File Kontrak .xlsx" aria-describedby="button-addon1"
                    value="">
            </div>
        </div>
    </div>
    <div class="col-6 m-auto">
        <button class="btn btn-secondary" type="button" id="button-addon1"
            onclick="RequestCertificate.DownloadTemplate('MAS-20240819-110-308', 'assets/doc/template/MAS-20240819-110-308.xls')"><i
                class="bx bx-import"></i> Download
            Template
        </button>
    </div>
</div>
@endif
<div class="table-responsive">
    <table id="table-rate" class="table align-middle mb-0 table-nowrap">
        <thead class="table-light">
            <tr>
                <th scope="col">Pemberi Fidusia</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Tempat Lahir</th>
                <th scope="col">Tanggal Lahir</th>
                <th scope="col">Pekerjaan</th>
                <th scope="col">Alamat</th>
                <th scope="col">RT</th>
                <th scope="col">RW</th>
                <th scope="col">Kelurahan</th>
                <th scope="col">Kecamatan</th>
                <th scope="col">Kabupaten</th>
                <th scope="col">Provinsi</th>
                <th scope="col">Kode POS</th>
                <th scope="col">KTP</th>
                <th scope="col">NPWP</th>
                <th scope="col">No Telp</th>
                <th scope="col">Status Perkawinan</th>
                <th scope="col">Nama Pasangan</th>
                <th scope="col">Tanggal Kuasa</th>
                <th scope="col">Nomor Kontrak</th>
                <th scope="col">Nomor Perjanjian Kontrak</th>
                <th scope="col">Nama Debitur</th>
                <th scope="col">Hutang Pokok</th>
                <th scope="col">Nilai Jaminan</th>
                <th scope="col">Biaya OTR</th>
                <th scope="col">Merk</th>
                <th scope="col">Tipe</th>
                <th scope="col">Tahun</th>
                <th scope="col">Warna</th>
                <th scope="col">No Rangka</th>
                <th scope="col">No Mesin</th>
                <th scope="col">NOPOL</th>
                <th scope="col">Pemilik BPKB</th>
                <th scope="col">Nomor BPKB</th>
                <th scope="col">Customer Tipe</th>
                <th scope="col">Tanggal Awal Tenor</th>
                <th scope="col">Tanggal Akhir Tenor</th>
                <th scope="col">Tipe Produk</th>
                <th scope="col">Cab</th>
                <th scope="col">Rep</th>
                <th scope="col">Kodisi</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if ($data == null)
            <tr class="input" data_id="">
                <td>
                    <input type="text" class="form-control required" id="pemberi_fidusia" placeholder="Pemberi Fidusia"
                        error="Pemberi Fidusia" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="jenis_kelamin" placeholder="Jenis Kelamin"
                        error="Jenis Kelamin" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="tempat_lahir" placeholder="Tempat Lahir"
                        error="Tempat Lahir" value="">
                </td>
                <td>
                    <input type="date" class="form-control required" id="tanggal_lahir" placeholder="Tanggal Lahir"
                        error="Tanggal Lahir" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="pekerjaan" placeholder="Pekerjaan"
                        error="Pekerjaan" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="alamat" placeholder="Alamat" error="Alamat"
                        value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="rt" placeholder="RT" error="RT" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </td>
                <td>
                    <input type="text" class="form-control required" id="rw" placeholder="RW" error="RW" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kelurahan" placeholder="Kelurahan"
                        error="Kelurahan" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kecamatan" placeholder="Kecamatan"
                        error="Kecamatan" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kabupaten" placeholder="Kabupaten"
                        error="Kabupaten" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="provinsi" placeholder="Provinsi"
                        error="Provinsi" value="">
                </td>
                <td>
                    <input type="number" class="form-control required" id="kode_pos" placeholder="Kode POS"
                        error="Kode POS" value="">
                </td>
                <td>
                    <input type="number" class="form-control required" id="ktp" placeholder="KTP" error="KTP" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="npwp" placeholder="NPWP" error="NPWP" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="no_telp" placeholder="No Telp" error="No Telp"
                        value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="status_perkawinan"
                        placeholder="Status Perkawinan" error="Status Perkawinan" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="nama_pasangan" placeholder="Nama Pasangan"
                        error="Nama Pasangan" value="">
                </td>
                <td>
                    <input type="date" class="form-control required" id="tanggal_kuasa" placeholder="Tanggal Kuasa"
                        error="Tanggal Kuasa" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="contract_number"
                        placeholder="Nomor Kontrak" error="Nomor Kontrak" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="no_perjanjian_kontrak"
                        placeholder="Nomor Perjanjian Kontrak" error="Nomor Perjanjian Kontrak" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="debitur" placeholder="Nama Debitur"
                        error="Nama Debitur" value="">
                </td>
                <td>
                    <input type="number" class="form-control required" id="hutang_pokok" placeholder="Hutang Pokok"
                        error="Hutang Pokok" value="">
                </td>
                <td>
                    <input type="number" class="form-control required" id="nilai_jaminan" placeholder="Nilai Jaminan"
                        error="Nilai Jaminan" value="">
                </td>
                <td>
                    <input type="number" class="form-control required" id="hutang_barang" placeholder="Biaya OTR"
                        error="Biaya OTR" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="merk" placeholder="Merk" error="Merk" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="tipe" placeholder="Tipe" error="Tipe" value="">
                </td>
                <td>
                    <input type="number" class="form-control required" id="tahun" placeholder="Tahun" error="Tahun"
                        value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="warna" placeholder="Warna" error="Warna"
                        value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="no_rangka" placeholder="No Angka"
                        error="No Angka" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="no_mesin" placeholder="No Mesin"
                        error="No Mesin" value="">
                </td>
                <td>
                    <input type="text" class="form-control " id="nopol" placeholder="NOPOL" error="NOPOL" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="pemilik_bpkb" placeholder="Pemilik BPKB"
                        error="Pemilik BPKB" value="">
                </td>
                <td>
                    <input type="text" class="form-control " id="nomor_bpkb" placeholder="Nomor BPKB" error="Nomor BPKB"
                        value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="customer_tipe" placeholder="Customer Tipe"
                        error="Customer Tipe" value="">
                </td>
                <td>
                    <input type="date" class="form-control required" id="tgl_awal_tenor"
                        placeholder="Tanggal Awal Tenor" error="Tanggal Awal Tenor" value="">
                </td>
                <td>
                    <input type="date" class="form-control required" id="tgl_akhir_tenor"
                        placeholder="Tanggal Akhir Tenor" error="Tanggal Akhir Tenor" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="type_produk" placeholder="Tipe Produk"
                        error="Tipe Produk" value="">
                </td>
                <td>
                    <input type="number" class="form-control required" id="cab" placeholder="Cab" error="Cab" value="">
                </td>
                <td>
                    <input type="number" class="form-control required" id="rep" placeholder="Rep" error="Rep" value="">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kondisi" placeholder="Kondisi" error="Kondisi"
                        value="">
                </td>
                <td id="action"></td>
            </tr>
            @else
            @if (!empty($data))
            @foreach ($data->RequestContract as $key => $item)
            @if ($item->status == 'DRAFT')
            <tr class="input" data_id="">
                <td>
                    <input type="text" class="form-control required" id="pemberi_fidusia" placeholder="Pemberi Fidusia"
                        error="Pemberi Fidusia" value="{{ $item->pemberi_fidusia }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="jenis_kelamin" placeholder="Jenis Kelamin"
                        error="Jenis Kelamin" value="{{ $item->jenis_kelamin }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="tempat_lahir" placeholder="Tempat Lahir"
                        error="Tempat Lahir" value="{{ $item->tempat_lahir }}">
                </td>
                <td>
                    <input type="date" class="form-control required" id="tanggal_lahir" placeholder="Tanggal Lahir"
                        error="Tanggal Lahir" value="{{ $item->tanggal_lahir }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="pekerjaan" placeholder="Pekerjaan" error="Pekerjaan"
                        value="{{ $item->pekerjaan }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="alamat" placeholder="Alamat" error="Alamat"
                        value="{{ $item->alamat }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="rt" placeholder="RT" error="RT"
                        value="{{ $item->rt }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="rw" placeholder="RW" error="RW"
                        value="{{ $item->rw }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kelurahan" placeholder="Kelurahan"
                        error="Kelurahan" value="{{ $item->kelurahan }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kecamatan" placeholder="Kecamatan"
                        error="Kecamatan" value="{{ $item->kecamatan }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kabupaten" placeholder="Kabupaten"
                        error="Kabupaten" value="{{ $item->kabupaten }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="provinsi" placeholder="Provinsi"
                        error="Provinsi" value="{{ $item->provinsi }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kode_pos" placeholder="Kode POS"
                        error="Kode POS" value="{{ $item->kode_pos }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="ktp" placeholder="KTP" error="KTP"
                        value="{{ $item->ktp }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="npwp" placeholder="NPWP" error="NPWP"
                        value="{{ $item->npwp }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="no_telp" placeholder="No Telp" error="No Telp"
                        value="{{ $item->no_telp }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="status_perkawinan"
                        placeholder="Status Perkawinan" error="Status Perkawinan"
                        value="{{ $item->status_perkawinan }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="nama_pasangan" placeholder="Nama Pasangan"
                        error="Nama Pasangan" value="{{ $item->nama_pasangan }}">
                </td>
                <td>
                    <input type="date" class="form-control required" id="tanggal_kuasa" placeholder="Tanggal Kuasa"
                        error="Tanggal Kuasa" value="{{ $item->tanggal_kuasa }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="contract_number"
                        placeholder="Nomor Kontrak" error="Nomor Kontrak"
                        value="{{ $item->contract_number }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="no_perjanjian_kontrak"
                        placeholder="Nomor Perjanjian Kontrak" error="Nomor Perjanjian Kontrak"
                        value="{{ $item->no_perjanjian_kontrak }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="debitur" placeholder="Nama Debitur"
                        error="Nama Debitur" value="{{ $item->debitur }}">
                </td>
                <td>
                    <input type="number" class="form-control required" id="hutang_pokok" placeholder="Hutang Pokok"
                        error="Hutang Pokok" value="{{ $item->hutang_pokok }}">
                </td>
                <td>
                    <input type="number" class="form-control required" id="nilai_jaminan" placeholder="Nilai Jaminan"
                        error="Nilai Jaminan" value="{{ $item->nilai_jaminan }}">
                </td>
                <td>
                    <input type="number" class="form-control required" id="hutang_barang" placeholder="Biaya OTR"
                        error="Biaya OTR" value="{{ $item->hutang_barang }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="merk" placeholder="Merk" error="Merk"
                        value="{{ $item->merk }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="tipe" placeholder="Tipe" error="Tipe"
                        value="{{ $item->tipe }}">
                </td>
                <td>
                    <input type="number" class="form-control required" id="tahun" placeholder="Tahun" error="Tahun"
                        value="{{ $item->tahun }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="warna" placeholder="Warna" error="Warna"
                        value="{{ $item->warna }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="no_rangka" placeholder="No Angka"
                        error="No Angka" value="{{ $item->no_rangka }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="no_mesin" placeholder="No Mesin"
                        error="No Mesin" value="{{ $item->no_mesin }}">
                </td>
                <td>
                    <input type="text" class="form-control required " id="nopol" placeholder="NOPOL" error="NOPOL"
                        value="{{ $item->nopol }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="pemilik_bpkb" placeholder="Pemilik BPKB"
                        error="Pemilik BPKB" value="{{ $item->pemilik_bpkb }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="nomor_bpkb" placeholder="Nomor BPKB" error="Nomor BPKB"
                        value="{{ $item->nomor_bpkb }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="customer_tipe" placeholder="Customer Tipe"
                        error="Customer Tipe" value="{{ $item->customer_tipe }}">
                </td>
                <td>
                    <input type="date" class="form-control required" id="tgl_awal_tenor"
                        placeholder="Tanggal Awal Tenor" error="Tanggal Awal Tenor" value="{{ $item->tgl_awal_tenor }}">
                </td>
                <td>
                    <input type="date" class="form-control required" id="tgl_akhir_tenor"
                        placeholder="Tanggal Akhir Tenor" error="Tanggal Akhir Tenor"
                        value="{{ $item->tgl_akhir_tenor }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="type_produk" placeholder="Tipe Produk"
                        error="Tipe Produk" value="{{ $item->type_produk }}">
                </td>
                <td>
                    <input type="number" class="form-control required" id="cab" placeholder="Cab" error="Cab"
                        value="{{ $item->cab }}">
                </td>
                <td>
                    <input type="number" class="form-control required" id="rep" placeholder="Rep" error="Rep"
                        value="{{ $item->rep }}">
                </td>
                <td>
                    <input type="text" class="form-control required" id="kondisi" placeholder="Kondisi" error="Kondisi"
                        value="{{ $item->kondisi }}">
                </td>
                <td id="action">
                    <button type="button" onclick="RequestCertificate.deleteItem(this, event)"
                        class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i
                            class="bx bx-trash-alt"></i></button>
                </td>
            </tr>
            @endif
            @endforeach
            @endif
            @endif
            <tr id="add-item-row">
                <td colspan="6" class="text-start">
                    <a href="" onclick="RequestCertificate.addItem(this, event)">Tambah Item</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
