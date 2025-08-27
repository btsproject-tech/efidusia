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
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Roles</label>
                                <select onchange="Permission.showMenu(this)" class="form-control select2 required" error="Roles" id="roles">
                                    <option value="">Daftar Roles</option>
                                    @foreach ($data_roles as $item)
                                        <option value="{{ $item['id'] }}" {{ isset($data->users_group) ? $data->users_group == $item['id'] ? 'selected' : ''  : ''}}>{{ $item['group'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            {{-- <div class="mb-3">
                                <label class="form-label">Daftar Menu</label>
                                <select class="form-control select2" id="menu">
                                    <option value="">Daftar Roles</option>
                                    @foreach ($data_roles as $item)
                                        <option value="{{ $item['id'] }}" {{ isset($data->users_group) ? $data->users_group == $item['id'] ? 'selected' : ''  : ''}}>{{ $item['group'] }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            {!! $list_menu_view !!}
                        </div>
                    </div>

                    <div class="mb-0">
                        <div>
                            <button type="submit" onclick="Permission.submit(this, event)"
                                class="btn btn-primary waves-effect waves-light me-1">
                                Submit
                            </button>
                            <button type="reset" onclick="Permission.cancel(this, event)"
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