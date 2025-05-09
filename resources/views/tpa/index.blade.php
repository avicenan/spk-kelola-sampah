@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('title', 'SPKSerela | TPA')

@section('plugins.Datatables', true)

@php
    $kriteriasHeads = $kriterias
        ->map(function ($kriteria) {
            return $kriteria->label . ' (' . $kriteria->satuan_ukur . ')';
        })
        ->toArray();

    $heads = [
        ['label' => 'No', 'width' => 4],
        ['label' => 'Nama TPA', 'width' => 10],
        ['label' => 'Alamat', 'width' => 10],
        ['label' => 'Kontak', 'width' => 10],
        ['label' => 'Jenis Sampah', 'width' => 25],
        ['label' => 'Actions', 'no-export' => true, 'width' => 5],
    ];

    array_splice($heads, 5, 0, $kriteriasHeads);

    $config = [
        'data' => array_map('array_values', json_decode($tpas, true)),
        'order' => [[0, 'asc']],
        'columns' => [null, null, null, null, null],
        'columnDefs' => [
            [
                'targets' => [-1],
                'className' => 'text-center',
                'data' => null,
                'orderable' => false,
            ],
        ],
    ];

@endphp

@section('content')
    <div class="container-fluid">
        <div class="row py-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Tempat Pembuangan Akhir (TPA)</h1>
                <div class="mb-2">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createTPA"> <i
                            class="fa fa-plus mr-2"></i>
                        Tambah</button>
                </div>
            </div>

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

            <div class="col-12">
                <x-adminlte-datatable id="tpaTable" :heads="$heads" :config="$config" theme="light" striped hoverable
                    bordered class="border border-black rounded">
                    @foreach ($config['data'] as $item)
                        <tr>
                            @foreach ($item as $key => $value)
                                @if ($loop->first)
                                    <td>{{ $loop->parent->iteration }}</td>
                                @elseif($key >= 5)
                                    @foreach ($value as $krit)
                                        <td>{{ $krit['pivot']['nilai'] }}</td>
                                    @endforeach
                                @elseif($key === 4)
                                    <td>
                                        {{ implode(', ', array_column($value, 'nama')) }}
                                    </td>
                                @else
                                    <td>
                                        {{ $value }}
                                    </td>
                                @endif
                            @endforeach
                            <td>
                                <nobr>
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"
                                        data-toggle="modal" data-target="#editTPA"
                                        onclick="
                                            $('#editTPAForm').attr('action', '/tpa/{{ $item[0] }}');
                                            $('#editTPANama').val('{{ $item[1] }}');
                                            $('#editTPAAlamat').val(`{{ $item[2] }}`);
                                            $('#editTPAKontak').val('{{ $item[3] }}');
                                            // Populate jenisSampahContainer with readonly input for each item[5] and add delete button
                                            var jenisSampahHtml = '';
                                            @php $jenisSampahArr = $item[4]; @endphp
                                            @foreach ($jenisSampahArr as $idx => $js)
                                            jenisSampahHtml += `
                                                <div class='input-group mb-2'>
                                                    <input type='text' class='form-control' readonly value='{{ $js['nama'] }}'>
                                                    <input type='hidden' name='jenis_sampah[]' value='{{ $js['id'] }}'>
                                                    <div class='input-group-append'>
                                                        <button type='button' class='btn btn-danger btn-sm' onclick='$(this).closest(&quot;.input-group&quot;).remove();'>
                                                            <i class='fa fa-trash'></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            `; @endforeach
                                            // insert value to all kriteria
                                            var kriteriaList = {{ json_encode($item[5]) }};
                                            $.each(kriteriaList, function(key, value) {
                                                var kriteriaId = value['id'];
                                                var kriteriaNilai = value['pivot']['nilai'];
                                                $('#editTPA' + kriteriaId).val(kriteriaNilai);
                                            });
                                            $('#jenisSampahContainer').html(jenisSampahHtml);
                                        ">
                                        {{-- {{ dd($item[5], 'okee') }} --}}
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"
                                        data-toggle="modal" data-target="#deleteTPA"
                                        onclick="$('#deleteTPAForm').attr('action', '/tpa/{{ $item[0] }}'); $('#deleteTPANama').text('{{ $item[1] }}');">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </nobr>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </div>
        </div>

        {{-- Create TPA --}}
        <x-adminlte-modal id="createTPA" title="Tambah TPA" icon="fa fa-plus" v-centered scrollable>
            <form action="{{ route('tpa.store') }}" id="createTPAForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama TPA</label>
                    <input type="text" class="form-control" id="nama" name="nama">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="kontak">Kontak</label>
                    <input type="tel" class="form-control" id="kontak" name="kontak">
                </div>
                <div class="form-group">
                    <label for="jenis_sampah">Jenis Sampah</label>
                    <div id="createJenisSampahList">
                        <div class="input-group mb-2 jenis-sampah-row">
                            <select class="form-control" name="jenis_sampah[]" required>
                                <option selected disabled>-- Pilih Jenis Sampah --</option>
                                @foreach ($allJenisSampah as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-success add-jenis-sampah" type="button"><i
                                        class="fa fa-plus"></i></button>
                                <button class="btn btn-danger remove-jenis-sampah" type="button"><i
                                        class="fa fa-minus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        function getJenisSampahRowCreate() {
                            return `
                                    <div class="input-group mb-2 jenis-sampah-row">
                                        <select class="form-control" name="jenis_sampah[]" required>
                                            <option value="" disabled readonly>-- Pilih Jenis Sampah --</option>
                                            @foreach ($allJenisSampah as $jenis)
                                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-success add-jenis-sampah" type="button"><i class="fa fa-plus"></i></button>
                                            <button class="btn btn-danger remove-jenis-sampah" type="button"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                `;
                        }
                        document.getElementById('createJenisSampahList').addEventListener('click', function(e) {
                            if (e.target.closest('.add-jenis-sampah')) {
                                e.preventDefault();
                                this.insertAdjacentHTML('beforeend', getJenisSampahRowCreate());
                            }
                            if (e.target.closest('.remove-jenis-sampah')) {
                                e.preventDefault();
                                const rows = this.querySelectorAll('.jenis-sampah-row');
                                if (rows.length > 1) {
                                    e.target.closest('.jenis-sampah-row').remove();
                                }
                            }
                        });
                    });
                </script>
                @foreach ($kriterias as $krit)
                    <div class="form-group">
                        <label for="{{ $krit->id }}">{{ $krit->label . ' (' . $krit->satuan_ukur . ')' }}</label>
                        <input type="number" class="form-control" id="{{ $krit->id }}"
                            name="kriterias[{{ $krit->id }}]">
                    </div>
                @endforeach
                <x-slot name="footerSlot">
                    <button id="createTPAButton" type="button" class="btn btn-primary"
                        onclick="$('#createTPAForm').submit();">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </x-slot>
            </form>
        </x-adminlte-modal>

        {{-- Edit TPA --}}
        <x-adminlte-modal id="editTPA" title="Edit TPA" icon="fa fa-pen" v-centered scrollable>
            <form action="" id="editTPAForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama">Nama TPA</label>
                    <input type="text" class="form-control" id="editTPANama" name="nama">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="editTPAAlamat" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="kontak">Kontak</label>
                    <input type="tel" class="form-control" id="editTPAKontak" name="kontak">
                </div>
                <div class="form-group">
                    <label for="jenis_sampah">Jenis Sampah</label>
                    <div id="jenisSampahContainer"></div>
                    <div id="jenisSampahList">
                        <div class="input-group mb-2 jenis-sampah-row">
                            <button class="btn btn-outline-success add-jenis-sampah w-100" type="button"><i
                                    class="fa fa-plus"></i></button>
                            <div class="input-group-append">
                                {{-- <button class="btn btn-danger remove-jenis-sampah" type="button"><i
                                        class="fa fa-minus"></i></button> --}}
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            function getJenisSampahRow() {
                                return `
                                    <div class="input-group mb-2 jenis-sampah-row">
                                        <select class="form-control" name="jenis_sampah[]" required>
                                            <option value="" disabled readonly>-- Tambah Jenis Sampah --</option>
                                            @foreach ($allJenisSampah as $jenis)
                                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-success add-jenis-sampah" type="button"><i class="fa fa-plus"></i></button>
                                            <button class="btn btn-danger remove-jenis-sampah" type="button"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                `;
                            }

                            // Add new jenis sampah row
                            document.getElementById('jenisSampahList').addEventListener('click', function(e) {
                                if (e.target.closest('.add-jenis-sampah')) {
                                    e.preventDefault();
                                    this.insertAdjacentHTML('beforeend', getJenisSampahRow());
                                }
                                if (e.target.closest('.remove-jenis-sampah')) {
                                    e.preventDefault();
                                    const rows = this.querySelectorAll('.jenis-sampah-row');
                                    if (rows.length > 1) {
                                        e.target.closest('.jenis-sampah-row').remove();
                                    }
                                }
                            });
                        });
                    </script>
                </div>
                @foreach ($kriterias as $krit)
                    <div class="form-group">
                        <label for="{{ $krit->id }}">{{ $krit->label . ' (' . $krit->satuan_ukur . ')' }}</label>
                        <input type="number" class="form-control" id="{{ 'editTPA' . $krit->id }}"
                            name="kriterias[{{ $krit->id }}]">
                    </div>
                @endforeach
                <x-slot name="footerSlot">
                    <button id="editTPAButton" type="button" class="btn btn-primary"
                        onclick="$('#editTPAForm').submit();">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </x-slot>
            </form>
        </x-adminlte-modal>

        {{-- Delete TPA --}}
        <x-adminlte-modal id="deleteTPA" title="Hapus TPA" theme="danger" icon="fa fa-trash" v-centered>
            <p>Apakah Anda yakin ingin menghapus TPA ini?</p>
            <p class="font-weight-bold" id="deleteTPANama"></p>
            <form action="" id="deleteTPAForm" method="POST">
                @csrf
                @method('DELETE')
                <x-slot name="footerSlot">
                    <button id="deleteTPAButton" type="button" class="btn btn-danger"
                        onclick="$('#deleteTPAForm').submit();">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </x-slot>
            </form>
        </x-adminlte-modal>
    </div>
@endsection
