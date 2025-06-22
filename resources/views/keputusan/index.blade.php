@extends('adminlte::page')

@section('title', 'SPKSerela | Keputusan')
@section('plugins.Sweetalert2', true)

@section('plugins.Datatables', true)
@section('plugins.TempusDominusBs4', true)

@php
    $heads = ['id', 'Tanggal', 'Aktifitas', 'Ket'];

    $config = [
        'data' => array_map(function ($v) {
            $v['created_at'] = \Carbon\Carbon::parse($v['created_at'])
                ->locale('id')
                ->translatedFormat('l, d F Y H:i:s');
            return array_values($v);
        }, json_decode($keputusans, true)),
        'order' => [[0, 'desc']],
        'columns' => [['visible' => false], null, null, null],
        'columnDefs' => [
            [
                'targets' => [0],
                'visible' => false,
                'searchable' => false,
            ],
        ],
        'lengthMenu' => [5],
    ];
@endphp

@section('js')
    @include('keputusan.script')
    <script>
        $(document).ready(function() {
            // Update 'to' date minimum when 'from' date changes
            // $('input[name="from"]').on('change.datetimepicker', function(e) {
            //     $('input[name="to"]').data("datetimepicker").minDate(e.date);
            // });

            $('#calculate').click(function() {
                // Get form data
                var formData = new FormData($('#keputusanForm')[0]);

                // Add TPA criteria values
                $('input[name^="tpa_kriteria"]').each(function() {
                    formData.append($(this).attr('name'), $(this).val());
                });

                // Send AJAX request
                $.ajax({
                    url: $('#keputusanForm').attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle success response
                        if (response.length > 0) {
                            // Update result modal with response data
                            var resultHtml = '';
                            response.forEach(function(item) {
                                resultHtml += `
                                    <tr>
                                        <td>${item.view.rank}</td>
                                        <td>${item.view.nama}</td>
                                        <td>${item.view.alamat}</td>
                                        <td>${item.view.jenis_sampah}</td>
                                        <td>${item.view.jumlah_sampah} kg</td>
                                        <td>${item.skor}%</td>
                                    </tr>
                                `;
                            });
                            $('#resultTableBody').html(resultHtml);
                            $('#resultModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        // Handle error response
                        var errorMessage = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menghitung keputusan';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[name="from"]').on('change.datetimepicker', function(e) {
                $('input[name="to"]').data("datetimepicker").minDate(e.date);
            });

            // Disable all TPA inputs by default when page loads
            $('.tpa-input').prop('disabled', true);
        });
    </script>
    <script>
        function getTpaByJenisSampah(jenisSampahId) {
            // If no jenis sampah is selected, disable all inputs
            if (!jenisSampahId) {
                $('.tpa-input').prop('disabled', true);
                return;
            }

            $.ajax({
                url: '{{ route('keputusan.getTpaByJenisSampah') }}',
                type: 'GET',
                data: {
                    jenis_sampah_id: jenisSampahId
                },
                success: function(response) {

                    // First disable all TPA inputs
                    $('.tpa-input').prop('disabled', true);

                    // Then enable only the TPA inputs from response
                    response.forEach(function(tpa) {
                        $(`#tpa_${tpa.id}`).find('input').prop('disabled', false);
                    });

                },
                error: function(xhr) {
                    console.error('Error fetching TPA data:', xhr);
                    // If there's an error, disable all inputs
                    $('.tpa-input').prop('disabled', true);
                }
            });
        }
    </script>
@endsection

@section('content')
    @if (session('success'))
        @section('js')
            <script>
                $(document).ready(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({
                        title: 'Success',
                        text: '{{ session('success') }}',
                        icon: 'success'
                    });
                });
            </script>
        @endsection
    @endif
    @if (session('error'))
        @section('js')
            <script>
                $(document).ready(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({
                        title: 'Error',
                        text: '{{ session('error') }}',
                        icon: 'error'
                    });
                });
            </script>
        @endsection
    @endif
    @if ($errors->any())
        @section('js')
            <script>
                $(document).ready(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({
                        title: 'Error',
                        text: '{{ $errors->first() }}',
                        icon: 'error'
                    });
                });
            </script>
        @endsection
    @endif

    <div class="container-fluid">
        <div class="row py-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Keputusan</h1>
                <h2 class="h5 mb-2 text-gray-800 font-weight-bold">Form Keputusan</h2>
                <form method="POST" action="{{ route('keputusan.calculate') }}" class="p-2 bg-white border mb-4"
                    id="keputusanForm">
                    @csrf
                    <div class="col-12">
                        <div class="row m-0">
                            {{-- Jenis Sampah --}}
                            <div class="form-group col-6 p-0">
                                <label for="jenis_sampah_id">Jenis Sampah</label>
                                <select name="jenis_sampah_id" id="jenis_sampah_id" class="form-control"
                                    onchange="getTpaByJenisSampah(this.value)">
                                    <option selected disabled>--- Pilih Jenis Sampah ---</option>
                                    @foreach ($jenisSampahs as $jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jumlah Sampah --}}
                            <div class="form-group col-6">
                                <label for="jumlah_sampah">Jumlah Sampah <span class="text-muted font-weight-normal">(dalam
                                        kg)</span></label>
                                <input type="number" class="form-control" id="jumlah_sampah" name="jumlah_sampah"
                                    placeholder="Masukkan jumlah sampah dalam kg">
                            </div>
                        </div>

                        {{-- Periode Sampah --}}
                        <div class="form-group">
                            <label>Periode Sampah</label>
                            <div class="row m-0">
                                @php
                                    $inputDateConfig = [
                                        'format' => 'DD-MM-YYYY',
                                    ];
                                @endphp
                                <x-adminlte-input-date id="from" name="from" :config="$inputDateConfig"
                                    placeholder="Pilih tanggal awal...">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-secondary">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                                <span class="m-1 fs-3 fw-bold">-</span>
                                <x-adminlte-input-date name="to" :config="$inputDateConfig"
                                    placeholder="Pilih tanggal akhir...">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-secondary">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                            </div>
                        </div>

                        {{-- TPA Table --}}
                        <div class="form-group">
                            <label>Daftar TPA dan Kriteria</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tpaTable">
                                    <thead>
                                        <tr>
                                            <th>Nama TPA</th>
                                            @foreach ($kriterias as $kriteria)
                                                <th>{{ $kriteria->label }} ({{ $kriteria->satuan_ukur }})</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="tpaTableBody">
                                        @foreach ($tpas as $tpa)
                                            <tr id="tpa_{{ $tpa->id }}">
                                                <td>{{ $tpa->nama }}</td>
                                                @foreach ($kriterias as $kriteria)
                                                    <td>
                                                        <input id="tpa_kriteria_{{ $tpa->id }}_{{ $kriteria->id }}"
                                                            type="number" class="form-control form-control-sm tpa-input"
                                                            name="tpa_kriteria[{{ $tpa->id }}][{{ $kriteria->id }}]"
                                                            value="{{ $tpa->kriterias->where('id', $kriteria->id)->first()->pivot->nilai ?? 0 }}"
                                                            step="0.01" min="0" disabled>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Biaya --}}
                        {{-- <div class="form-group">
                            <label for="biaya">Biaya <span class="text-muted font-weight-normal">(dalam
                                    rupiah)</span></label>
                            <input type="number" class="form-control" id="biaya" name="biaya"
                                placeholder="Masukkan biaya pembuangan dan pengangkutan sampah">
                        </div> --}}

                        {{-- Tingkat Kemacetan --}}
                        {{-- <div class="form-group">
                            <label for="tingkat_kemacetan">Tingkat Kemacetan <span
                                    class="text-muted font-weight-normal">(dari 1 hingga 5)</span></label>
                            <select class="form-control" id="tingkat_kemacetan" name="tingkat_kemacetan">
                                <option value="" disabled selected>Pilih tingkat kemacetan</option>
                                <option value="1">1 - Lancar</option>
                                <option value="2">2 - Lancar</option>
                                <option value="3">3 - Sedang</option>
                                <option value="4">4 - Sedang</option>
                                <option value="5">5 - Macet</option>
                            </select>
                        </div> --}}

                        {{-- Submit Button --}}
                        <div class="form-group">
                            <button type="button" id="calculate" data-toggle="modal" data-target="#resultModal"
                                class="btn btn-primary">Kalkulasi</button>
                        </div>
                    </div>

                    {{-- Result Modal --}}
                    @include('keputusan.result-modal')

                    {{-- Result Detail Modal --}}
                    @include('keputusan.result-detail-modal')

                </form>
                <h2 class="h5 mb-2 text-gray-800 font-weight-bold">Riwayat</h2>
            </div>

            <div class="col-12">
                <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable
                    bordered class="border border-black rounded">
                </x-adminlte-datatable>
            </div>
        </div>
    </div>
@endsection
