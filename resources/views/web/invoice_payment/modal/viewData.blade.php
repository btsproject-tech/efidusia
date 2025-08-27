 <!-- First modal dialog -->
 <div class="modal bs-example-modal-lg fade" id="data-modal-view-data" aria-hidden="true" aria-labelledby="..."
     tabindex="-1">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">View Data</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <iframe src="{{ asset(isset($file_path) ? $file_path . $nama_file : '') }}" width="100%"
                     height="600px"></iframe>
             </div>
             <div class="modal-footer">
                 <!-- Toogle to second dialog -->
             </div>
         </div>
     </div>
 </div>
