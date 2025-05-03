@extends('adminlte::page')

@section('title', 'SPKSerela | Keputusan')

{{-- @section('plugins.DatatablesPlugin', true) --}}
@section('plugins.Datatables', true)
@section('plugins.TempusDominusBs4', true)

@php
    $heads = [
        ['label' => 'No', 'width' => 4],
        'Judul',
        'Isi',
        ['label' => 'Actions', 'no-export' => true, 'width' => 5],
    ];

    $config = [
        'data' => array_map('array_values', json_decode($keputusans, true)),
        'order' => [[0, 'asc']],
        'columns' => [null, null, null],
    ];
@endphp


@section('content')
    <div class="container-fluid">
        <div class="row py-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Keputusan</h1>
                <h2 class="h5 mb-2 text-gray-800 font-weight-bold">Form Keputusan</h2>
                <form method="POST" class="p-2 bg-white border mb-4">
                    @csrf
                    <div class="col-8">
                        {{-- Jenis Sampah --}}
                        <div class="form-group">
                            <label for="jenis_sampah_id">Jenis Sampah</label>
                            <select name="jenis_sampah_id" id="" class="form-control">
                                <option selected disabled>--- Pilih Jenis Sampah ---</option>
                                @foreach ($jenisSampahs as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Periode Sampah --}}
                        <div class="form-group">
                            <label>Periode Sampah</label>
                            <div class="row m-0">
                                @php
                                    $inputDateConfig = ['format' => 'DD-MM-YYYY'];
                                @endphp
                                <x-adminlte-input-date name="from" :config="$inputDateConfig"
                                    placeholder="Choose a start date...">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-secondary">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                                <span class="m-1 fs-3 fw-bold">-</span>
                                <x-adminlte-input-date name="to" :config="$inputDateConfig" placeholder="Choose a end date...">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-secondary">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                            </div>

                        </div>

                        {{-- Jumlah Sampah --}}
                        <div class="form-group">
                            <label for="jumlah_sampah">Jumlah Sampah <span class="text-muted font-weight-normal">(dalam
                                    kg)</span></label>
                            <input type="number" class="form-control" id="jumlah_sampah" name="jumlah_sampah"
                                placeholder="Masukkan jumlah sampah dalam kg">
                        </div>

                        {{-- Biaya --}}
                        <div class="form-group">
                            <label for="biaya">Biaya <span class="text-muted font-weight-normal">(dalam
                                    rupiah)</span></label>
                            <input type="number" class="form-control" id="biaya" name="biaya"
                                placeholder="Masukkan biaya pembuangan dan pengangkutan sampah">
                        </div>

                        {{-- Tingkat Kemacetan --}}
                        <div class="form-group">
                            <label for="tingkat_kemacetan">Tingkat Kemacetan <span
                                    class="text-muted font-weight-normal">(dari 1 hingga 5)</span></label>
                            <input type="number" class="form-control" id="tingkat_kemacetan" name="tingkat_kemacetan"
                                placeholder="Masukkan tingkat kemacetan" min="1" max="5">
                        </div>
                    </div>
                @section('js')
                    <script>
                        function printView() {
                            const printContent = document.querySelector(`#resultDetailModalContainer`).innerHTML;
                            const originalContent = document.body.innerHTML;

                            document.body.innerHTML = printContent;
                            window.print();
                            document.body.innerHTML = originalContent;
                        }

                        function calculate() {
                            var formData = new FormData(event.target.form);
                            $.ajax({
                                url: `{{ route('keputusan.calculate') }}`,
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(data) {
                                    const hasils = data;
                                    const resultModalContainer = document.querySelector('#resultModalContainer');
                                    const resultHtml = `
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-center">Rank</th>
                                                        <th scope="col" class="text-center">TPA</th>
                                                        <th scope="col" class="text-center">Skor</th>
                                                        <th scope="col" class="text-center">View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${hasils.map((data, index) => `
                                                        <tr>
                                                        <td class="text-center">#${index + 1}</td>
                                                        <td class="text-center">${data.view.nama}</td>
                                                        <td class="text-center">${data.skor}</td>
                                                        <td class="text-center"><button type="button" id="${index}" class="resultDetailModal btn btn-xs btn-default text-primary" data-toggle="modal" data-target="#resultDetailModal"><i class="fa fa-lg fa-fw fa-receipt"></i></button></td>
                                                        </tr>
                                                        `).join('')}
                                                </tbody>
                                            </table>
                                        `;
                                    resultModalContainer.innerHTML = resultHtml;

                                    $('.resultDetailModal').on('click', function() {

                                        $('#resultModal').modal('hide');
                                        const hasil = hasils[this.id];

                                        $('#resultDetailModalContainer').html(`

                                                <div class="text-center"><h4 class='font-weight-bold'> Hasil Keputusan</h4><p>Serela Hotel Cihampelas</p></div>
                                                <!-- TPA Information Section -->
                                                <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                <h6 class="mb-0 font-weight-bold">Informasi TPA</h6>
                                                </div>
                                                <div class="card-body">
                                                <div class="row">
                                                <div class="col-md-6">
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Nama TPA:</div>
                                                        <div>${hasil.view.nama}</div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Alamat:</div>
                                                        <div>${hasil.view.alamat}</div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Kontak:</div>
                                                        <div>${hasil.view.kontak}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Rank:</div>
                                                        <div><span class="badge badge-primary">${hasil.view.rank}</span></div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Jarak:</div>
                                                        <div>${hasil.view.jarak} km</div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Biaya:</div>
                                                        <div>Rp ${parseFloat(hasil.view.biaya).toLocaleString('id-ID')}</div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Tingkat Kemacetan:</div>
                                                        <div>
                                                            <span class="badge ${hasil.view.tingkat_kemacetan >= 1 && hasil.view.tingkat_kemacetan <= 2 ? 'badge-success' : hasil.view.tingkat_kemacetan >= 3 && hasil.view.tingkat_kemacetan <= 4 ? 'badge-warning' : 'badge-danger'}">${hasil.view.tingkat_kemacetan}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>

                                                <!-- Waste Information Section -->
                                                <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                <h6 class="mb-0 font-weight-bold">Informasi Sampah</h6>
                                                </div>
                                                <div class="card-body">
                                                <div class="row">
                                                <div class="col-md-6">
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Jenis Sampah:</div>
                                                        <div>${hasil.view.jenis_sampah}</div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Sumber Sampah:</div>
                                                        <div>${hasil.view.sumber_sampah}</div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Jumlah Sampah:</div>
                                                        <div>${hasil.view.jumlah_sampah} kg</div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Periode:</div>
                                                        <div>${hasil.view.from} - ${hasil.view.to}</div>
                                                    </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>

                                                <!-- User Information Section -->
                                                <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                <h6 class="mb-0 font-weight-bold">Informasi Pengguna</h6>
                                                </div>
                                                <div class="card-body">
                                                <div class="row">
                                                <div class="col-md-6">
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Nama:</div>
                                                        <div>${hasil.view.nama_pengguna}</div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Email:</div>
                                                        <div>${hasil.view.email_pengguna}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Jabatan:</div>
                                                        <div><span class="badge badge-info">${hasil.view.role}</span></div>
                                                    </div>
                                                    <div class=" mb-2">
                                                        <div class="font-weight-bold">Tanggal:</div>
                                                        <div>${new Date(hasil.view.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric'  })}</div>
                                                    </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                            `);
                                    });

                                },
                                error: function(error) {
                                    console.error(error);
                                    const errorHTML = `
                                            <div class="alert alert-danger" role="alert">
                                                <h4 class="alert-heading">Terjadi Kesalahan</h4>
                                                <p>${error.responseJSON.message}</p>
                                            </div>
                                        `;
                                    const resultModalContainer = document.querySelector('#resultModalContainer');
                                    resultModalContainer.innerHTML = errorHTML;
                                    return error;
                                }
                            });
                        }

                        $('#resultDetailModal').on('hidden.bs.modal', function() {
                            $('#resultModal').modal('show');
                        });
                    </script>
                @endsection
                {{-- Submit Button --}}
                <div class="form-group">
                    <button type="button" data-toggle="modal" data-target="#resultModal" onclick="calculate();"
                        class="btn btn-primary">Kalkulasi</button>
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
                bordered with-buttons class="border border-black rounded">
                @foreach ($config['data'] as $item)
                    <tr>
                        @foreach ($item as $key => $value)
                            @if ($loop->first)
                                <td>{{ $loop->parent->iteration }}</td>
                            @else
                                <td>{{ $value }}</td>
                            @endif
                        @endforeach
                        <td>
                            <nobr>
                                <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"
                                    data-toggle="modal" data-target="#editJenisSampah"
                                    onclick="$('#editJenisSampahForm').attr('action', '/jenis-sampah/{{ $item[0] }}'); $('#editJenisSampahNama').val('{{ $item[1] }}'); $('#editJenisSampahSumber').val('{{ $item[2] }}');">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>

                                <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"
                                    data-toggle="modal" data-target="#deleteJenisSampah"
                                    onclick="$('#deleteJenisSampahForm').attr('action', '/jenis-sampah/{{ $item[0] }}'); $('#deleteJenisSampahNama').text('{{ $item[1] }}');">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </nobr>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
</div>
@endsection
