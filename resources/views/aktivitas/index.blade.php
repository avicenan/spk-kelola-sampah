@extends('adminlte::page')

@section('plugins.Sweetalert2', true)

@section('title', 'SPKSerela | Aktivitas')

@php

    $heads = ['id', 'Tanggal', 'Aktivitas', 'Jenis', ['label' => 'Actions', 'no-export' => true, 'width' => 5]];

    $config = [
        'data' => array_map(function ($v) {
            // $v['created_at'] = \Carbon\Carbon::parse($v['created_at'])->locale('id')->format('l, d F Y H:i:s');
            $v['created_at'] = \Carbon\Carbon::parse($v['created_at'])
                ->locale('id')
                ->translatedFormat('l, d F Y H:i:s');

            // $v['created_at'] = \Carbon\Carbon::parse($v['created_at'])
            //     ->locale('id')
            //     ->translatedFormat('l, d F Y H:i:s');
            if ($v['jenis'] == 'add_keputusan') {
                $v['actions'] =
                    '<button id="{{ $item[0] }}" class="showDetail btn btn-xs btn-default text-teal mx-1 shadow" title="Details" data-toggle="modal" data-target="#keputusanModal" data-aktivitas-id="' .
                    $v['id'] .
                    '">
                   <i class="fa fa-lg fa-fw fa-eye"></i>
               </button>';
            } else {
                $v['actions'] = ' ';
            }
            return array_values($v);
        }, json_decode($allAktivitas, true)),
        'order' => [[0, 'desc']],
        'columns' => [['visible' => false], null, null, ['visible' => false], null],
        'columnDefs' => [
            [
                'target' => [0],
                'visible' => false,
            ],
            [
                'targets' => [3],
                'visible' => false,
            ],
        ],
        'lengthMenu' => [10],
    ];
@endphp


@section('content')
    <div class="container-fluid">
        <div class="row py-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Aktivitas</h1>
            </div>
            <div class="col-12">
                <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable
                    bordered with-buttons class="border border-black rounded">
                </x-adminlte-datatable>
            </div>
        </div>
    </div>

@section('js')
    <script>
        let keputusan;
        $('#keputusanModal').on('shown.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var aktivitasId = button.data('aktivitas-id') || null;
            if (aktivitasId === null) {
                return;
            }
            console.log('buka modal');

            $.ajax({
                url: "{{ route('keputusan.getHasilKeputusan', ['aktivitasId' => ':aktivitasId']) }}"
                    .replace(
                        ':aktivitasId', aktivitasId),
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    keputusan = response;
                    if (response.length === 0) {
                        $('#keputusanModalContainer').html(`
                            <div class="text-center" style="margin: 40px 0;">
                                <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                                <p class="text-muted">Tidak ada data keputusan yang tersedia</p>
                            </div>
                        `);
                    } else {
                        $('#keputusanModalContainer').html(`
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Rank</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Skor</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="keputusanModalTableBody">
                                    ${response.map(item => `
                <tr>
                    <td class="text-center">#${item.rank}</td>
                    <td class="text-center">${item.nama}</td>
                    <td class="text-center">${item.skor}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-xs btn-default text-primary showDetail" data-toggle="modal" data-target="#resultDetailModal" data-hasil-keputusan-id="${item.id}" onclick="$('#keputusanModal').modal('hide')">
                            <i class="fa fa-lg fa-fw fa-receipt"></i>
                        </button>
                    </td>
                </tr>
            `).join('')}
                                </tbody>
                            </table>
                        `);
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });

            $('#aktivitasId').text(aktivitasId);
            $('#aktivitasDesc').text(aktivitasId);
        });

        $('#resultDetailModal').on('shown.bs.modal', function(e) {
            $('#keputusanModal').modal('hide');
            var button = $(e.relatedTarget);
            var hasilKeputusanId = button.data('hasil-keputusan-id') || null;
            if (hasilKeputusanId === null) {
                return;
            }
            let hasil = keputusan.find(item => item.id === hasilKeputusanId);
            console.log(hasil);
            $('#resultDetailModalContainer').html(`
                <div class="text-center">
                    <h4 class='font-weight-bold'> Hasil Keputusan</h4>
                    <p>${hasil.nama}</p>
                </div>

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
                                    <div>${hasil.nama}</div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Alamat:</div>
                                    <div>${hasil.alamat}</div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Kontak:</div>
                                    <div>${hasil.kontak}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Rank:</div>
                                    <div><span class="badge badge-primary">${hasil.rank}</span></div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Jarak:</div>
                                    <div>${hasil.jarak} km</div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Biaya:</div>
                                    <div>Rp ${parseFloat(hasil.biaya).toLocaleString('id-ID')}</div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Tingkat Kemacetan:</div>
                                    <div>
                                        <span class="badge ${hasil.tingkat_kemacetan >= 1 && hasil.tingkat_kemacetan <= 2 ? 'badge-primary' : hasil.tingkat_kemacetan >= 3 && hasil.tingkat_kemacetan <= 4 ? 'badge-warning' : 'badge-danger'}">${hasil.tingkat_kemacetan} - ${hasil.tingkat_kemacetan >= 1 && hasil.tingkat_kemacetan <= 2 ? 'Kurang' : hasil.tingkat_kemacetan >= 3 && hasil.tingkat_kemacetan <= 4 ? 'Sedang' : 'Banyak'}</span>
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
                                    <div>${hasil.jenis_sampah}</div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Sumber Sampah:</div>
                                    <div>${hasil.sumber_sampah}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Jumlah Sampah:</div>
                                    <div>${hasil.jumlah_sampah} kg</div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Periode:</div>
                                    <div>${hasil.from} - ${hasil.to}</div>
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
                                    <div>${hasil.nama_pengguna}</div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Email:</div>
                                    <div>${hasil.email_pengguna}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Jabatan:</div>
                                    <div><span class="badge badge-info">${hasil.role}</span></div>
                                </div>
                                <div class=" mb-2">
                                    <div class="font-weight-bold">Tanggal:</div>
                                    <div>${new Date(hasil.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric'  })}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        });

        $('#resultDetailModal').on('hidden.bs.modal', function() {
            $('#keputusanModal').modal('show');
        });
    </script>
@endsection

{{-- View Keputusan Modal --}}
<x-adminlte-modal id="keputusanModal" title="Detail Aktivitas" theme="primary" v-centered scrollable>
    <div id="keputusanModalContainer">
        <div class="text-center" style="margin: 40px 0;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>
    </div>

    <x-slot name="footerSlot">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </x-slot>
</x-adminlte-modal>

{{-- View Keputusan Detail Modal --}}
<x-adminlte-modal id="resultDetailModal" title="Detail Hasil Keputusan" v-centered scrollable>
    <div id="resultDetailModalContainer">
        <div class="text-center" style="margin: 40px 0;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>
    </div>
</x-adminlte-modal>

@endsection
