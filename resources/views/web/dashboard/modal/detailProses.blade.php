 <!-- First modal dialog -->
 <div class="modal fade" id="data-modal-detail" aria-hidden="true" aria-labelledby="..." tabindex="-1">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Detail Proses</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <h4 class="card-title mb-4">Timeline History</h4>
                 <div>
                     <ul class="verti-timeline list-unstyled">
                         @foreach ($data as $i)
                             {{-- @if ($i->status == 'DRAFT')
                                 @continue
                             @else --}}
                             <li class="event-list {{ $i->status == $last_status->status ? 'active' : '' }}">
                                 <div class="event-timeline-dot">
                                     <i
                                         class="bx bx-right-arrow-circle {{ $i->status == $last_status->status ? 'bx-fade-right' : '' }}"></i>
                                 </div>
                                 <div class="d-flex">
                                     <div class="flex-shrink-0 me-3">
                                         <span
                                             class="badge
                                                        @if ($i->status == 'APPROVE') bg-success
                                                        @elseif ($i->status == 'COMPLETE')
                                                        bg-secondary
                                                        @elseif ($i->status == 'FINISHED')
                                                        bg-dark
                                                        @elseif ($i->status == 'VERIFIED')
                                                        bg-success
                                                        @elseif ($i->status == 'DONE')
                                                        bg-primary @endif
                                                        "
                                             style="font-size:12px;">
                                             {{ $i->status }}
                                         </span>
                                         <i
                                             class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                     </div>
                                     <div class="flex-grow-1">
                                         <h5>{{ $i->created_at }}</h5>
                                         <p class="text-muted">{{ $i->remarks }}</p>
                                     </div>
                                 </div>
                             </li>
                             {{-- @endif --}}
                         @endforeach
                         @foreach (getListDashboard($i->status) as $j)
                             {!! $j !!}
                         @endforeach
                     </ul>
                 </div>
             </div>
             <div class="modal-footer">
                 <!-- Toogle to second dialog -->
             </div>
         </div>
     </div>
 </div>
