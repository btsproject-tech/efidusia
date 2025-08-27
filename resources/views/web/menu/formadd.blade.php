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
                                <label class="form-label">Parent Menu</label>
                                <select class="form-control select2" id="parent_menu">
                                    <option value="">Daftar Menu</option>
                                    @foreach ($data_menu as $item)
                                        <option value="{{ $item['id'] }}" {{ isset($data->parent) ? $data->parent == $item['id'] ? 'selected' : ''  : ''}}>{{ $item['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Url Link</label>
                                <div>
                                    <input type="text" id="url" class="form-control required" error="Url Link"
                                        placeholder="Url Link" value="{{ isset($data->url) ? $data->url : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Nama Menu</label>
                                <div>
                                    <input type="text" id="nama" class="form-control required" error="Nama Menu"
                                        placeholder="Nama Menu" value="{{ isset($data->nama) ? $data->nama : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div>
                            <button type="submit" onclick="Menu.submit(this, event)"
                                class="btn btn-primary waves-effect waves-light me-1">
                                Submit
                            </button>
                            <button type="reset" onclick="Menu.cancel(this, event)"
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