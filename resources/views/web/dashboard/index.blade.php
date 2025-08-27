 @if (isset($akses->dashboard))
     @if ($akses->dashboard->view == 1)
         <!-- start page title -->
         <div class="row">
             <div class="col-12">
                 <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                     {{-- <h4 class="mb-sm-0 font-size-18">Dashboard</h4> --}}
                 </div>
             </div>
         </div>
         <!-- end page title -->

         <button type="button" id="btn-show-modal" style="display: none;" data-bs-toggle="modal"
             data-bs-target="#data-modal-detail">button</button>
         <div id="content-modal-form"></div>

         <div class="row">
             <div class="col-xl-4">
                 <div class="card overflow-hidden">
                     <div class="bg-primary bg-soft">
                         <div class="row">
                             <div class="col-7">
                                 <div class="text-primary p-3">
                                     <h5 class="text-primary">Welcome Back !</h5>
                                     <p></p>
                                 </div>
                             </div>
                             <div class="col-5 align-self-end">
                                 <img src="{{ asset('skote/assets/images/profile-img.png') }}" alt=""
                                     class="img-fluid">
                             </div>
                         </div>
                     </div>
                     <div class="card-body pt-0">
                         <div class="row">
                             <div class="col-sm-4">
                                 <div class="avatar-md profile-user-wid mb-4">
                                     <img src="{{ asset('assets/images/user.png') }}" alt=""
                                         class="img-thumbnail rounded-circle">
                                 </div>
                                 <h5 class="font-size-15 text-truncate">{{ strtoupper(session('nama_lengkap')) }}</h5>
                                 <p class="text-muted mb-0 text-truncate">{{ strtoupper(session('akses')) }}</p>
                             </div>

                             <div class="col-sm-8">
                                 <div class="pt-4">

                                     <div class="row">
                                         <div class="col-6">
                                             <h5 class="font-size-15" id="allData"></h5>
                                             <p class="text-muted mb-0">Jumlah Data</p>
                                         </div>
                                         {{-- <div class="col-6">
                               <h5 class="font-size-15">{{ $total_si }}</h5>
                               <p class="text-muted mb-0">Shipping Instruction</p>
                           </div> --}}
                                     </div>
                                     {{-- <div class="mt-4">
                           <a href="javascript: void(0);"
                               class="btn btn-primary waves-effect waves-light btn-sm">View
                               Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                       </div> --}}
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-xl-8">
                 <div class="row">
                     <div class="col-md-4">
                         <div class="card mini-stats-wid" style="height: 7.5rem">
                             <div class="card-body">
                                 <div class="d-flex">
                                     <div class="flex-grow-1">
                                         <p class="text-muted fw-medium">SK DRAFT</p>
                                         <h4 class="mb-0" id="skDraft"></h4>
                                     </div>

                                     <div class="flex-shrink-0 align-self-center ">
                                         <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                                             <span class="avatar-title rounded-circle bg-warning">
                                                 <i class="bx bx-time font-size-24"></i>
                                             </span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="card mini-stats-wid" style="height: 7.5rem">
                             <div class="card-body">
                                 <div class="d-flex">
                                     <div class="flex-grow-1">
                                         <p class="text-muted fw-medium">SK APPROVE</p>
                                         <h4 class="mb-0" id="skApprove"></h4>
                                     </div>

                                     <div class="flex-shrink-0 align-self-center">
                                         <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                                             <span class="avatar-title rounded-circle bg-success">
                                                 <i class="bx bx-check-circle font-size-24"></i>
                                             </span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="card mini-stats-wid" style="height: 7.5rem">
                             <div class="card-body">
                                 <div class="d-flex">
                                     <div class="flex-grow-1">
                                         <p class="text-muted fw-medium">SK DONE</p>
                                         <h4 class="mb-0" id="skDone"></h4>
                                     </div>

                                     <div class="flex-shrink-0 align-self-center">
                                         <div class="avatar-sm rounded-circle bg-info mini-stat-icon">
                                             <span class="avatar-title rounded-circle bg-info">
                                                 <i class="bx bx-check-square font-size-24"></i>
                                             </span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="row ">
                     <div class="col-md-6">
                         <div class="card mini-stats-wid" style="height: 7.5rem">
                             <div class="card-body">
                                 <div class="d-flex">
                                     <div class="flex-grow-1">
                                         <p class="text-muted fw-medium">SK FINISHED</p>
                                         <h4 class="mb-0" id="skFinished"></h4>
                                     </div>

                                     <div class="flex-shrink-0 align-self-center">
                                         <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                             <span class="avatar-title rounded-circle bg-primary">
                                                 <i class="bx bx-check-circle font-size-24"></i>
                                             </span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="card mini-stats-wid" style="height: 7.5rem">
                             <div class="card-body">
                                 <div class="d-flex">
                                     <div class="flex-grow-1">
                                         <p class="text-muted fw-medium">SK COMPLETE</p>
                                         <h4 class="mb-0" id="skComplete"></h4>
                                     </div>

                                     <div class="flex-shrink-0 align-self-center">
                                         <div class="avatar-sm rounded-circle bg-secondary mini-stat-icon">
                                             <span class="avatar-title rounded-circle bg-secondary">
                                                 <i class="bx bx-check-double font-size-24"></i>
                                             </span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <div class="row">
             <div class="col-md-4">
                 <div class="card">
                     <div class="card-body">
                         <h4 class="card-title mb-4">Total Data</h4>
                         <div class="container-pie-chart" style="position: relative; width: 100%; height:100%">
                             <div id="loader" class="spinner-border text-secondary"
                                 style="display: none; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);"
                                 role="status">
                                 <span class="visually-hidden">Loading...</span>
                             </div>
                             <div id="pie_chart" class="apex-charts" dir="ltr"></div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-md-8">
                 <div class="card">
                     <div class="card-body">
                         <h4 class="card-title mb-4">Statistics Transactions</h4>
                         <div class="container-pie-chart" style="position: relative; width: 100%; height:100%">
                             <div id="loaderBar" class="spinner-border text-secondary"
                                 style="display: none; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);"
                                 role="status">
                                 <span class="visually-hidden">Loading...</span>
                             </div>
                             <canvas id="lineChartM" height="100%"></canvas>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <div class="row">
             {{-- <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Activity</h4>
                <ul class="verti-timeline list-unstyled" id="activity">
                    @if (!empty($data_activity))
                        @foreach ($data_activity as $key => $item)
                            <li class="event-list">
                                <div class="event-timeline-dot">
                                    <i class="bx bx-right-arrow-circle font-size-18"></i>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <h5 class="font-size-14">
                                            {{ date('d M', strtotime($item->created_at)) }}
                                            <i
                                                class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                        </h5>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div>
                                            <b>{{ strtoupper($item->karyawan_name) }}</b>
                                        </div>
                                        <div>
                                            {{ $item->action }}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul> --}}
             {{-- <div class="text-center mt-4"><a href="javascript: void(0);"
                       class="btn btn-primary waves-effect waves-light btn-sm">View More <i
                           class="mdi mdi-arrow-right ms-1"></i></a></div> --}}
             {{-- </div>
        </div>
    </div> --}}
             <div class="col-xl-12">
                 <div class="card">
                     <div class="card-body">
                         <h4 class="card-title mb-4">Latest Transaction</h4>
                         <div class="accordion mb-4" id="accordionExample">
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="headingTwo">
                                     <button class="accordion-button fw-medium collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                         aria-expanded="false" aria-controls="collapseTwo">
                                         Filter Data
                                     </button>
                                 </h2>
                                 <div id="collapseTwo" class="accordion-collapse collapse"
                                     aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                     <div class="accordion-body">
                                         <div class="text-muted">
                                             <div class="row">
                                                 <div class="col-md-7">
                                                     <div class="mb-4">
                                                         {{-- <label>Filter Date</label> --}}
                                                         <div class="input-daterange input-group" id="datepicker6"
                                                             data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                             data-provide="datepicker"
                                                             data-date-container='#datepicker6'>
                                                             <input type="text" class="form-control"
                                                                 name="start" placeholder="Start Date"
                                                                 id="tgl_awal"
                                                                 onchange="Dashboard.handelDate(this)" />
                                                             <input type="text" class="form-control"
                                                                 name="end" placeholder="End Date" id="tgl_akhir"
                                                                 onchange="Dashboard.handelDate(this)" />
                                                         </div>
                                                     </div>
                                                 </div>
                                                 {{-- <div class="col-md-3">
                                            <label style="opacity: 0;">Filter Date</label>
                                            <div class="mb-4 d-grid gap-2">
                                                <button class="btn btn-block btn-warning"
                                                    onclick="Dashboard.getData(this)">Filter</button>
                                            </div>
                                        </div> --}}
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="table-responsive">
                             <table class="table align-middle table-nowrap mb-0" id="table-data">
                                 <thead class="table-light">
                                     <tr>
                                         <th>No</th>
                                         <th>SK Number</th>
                                         <th>Contract Number</th>
                                         <th>Minuta Number</th>
                                         <th>Name Notaris</th>
                                         <th>Debitur</th>
                                         <th>Status</th>
                                         <th>Created</th>
                                         <th>Action</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                                     </tr>
                                 </tbody>
                             </table>
                         </div>
                         <!-- end table-responsive -->
                     </div>
                 </div>
                 <!-- end row -->
             </div>
         </div>
         <!-- end row -->
         <!-- end row -->


         <!-- end row -->
     @else
         @include('web.alert.message')
     @endif
 @else
     @include('web.alert.message')
 @endif
