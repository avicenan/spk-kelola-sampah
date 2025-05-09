<x-adminlte-modal id="resultModal" title="Hasil Keputusan" theme='primary' scrollable v-centered static-backdrop>
    <div id="resultModalContainer">
        <div class="text-center my-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <x-slot name="footerSlot">
        <button id="saveResult" type="button" class="btn btn-primary" hidden><i
                class="fa fa-lg fa-fw fa-save"></i>Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </x-slot>
</x-adminlte-modal>
