 <x-adminlte-modal id="resultDetailModal" v-centered scrollable>
     <div id="resultDetailModalContainer">
         <div class="spinner-border text-primary" role="status">
             <span class="sr-only">Loading...</span>
         </div>
     </div>

     <x-slot name="footerSlot">
         <button type="" class="btn btn-primary" onclick="printView()">
             <i class="fa fa-lg fa-fw fa-print"></i>Print</button>
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
     </x-slot>
 </x-adminlte-modal>
