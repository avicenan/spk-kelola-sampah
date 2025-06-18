@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('title', 'SPKSerela | Jenis Sampah')

@section('plugins.Datatables', true)

@php

    $heads = ['No', 'Nama Jenis', 'Sumber', 'Contoh', ['label' => 'Actions', 'no-export' => true, 'width' => 5]];

    $config = [
        'data' => array_map(
            function ($v, $index) {
                if (Auth::user()->role === 'staff') {
                    $v['actions'] =
                        '<nobr>
                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" 
                        data-toggle="modal" data-target="#editJenisSampah"
                        onclick="$(\'#editJenisSampahForm\').attr(\'action\', \'/jenis-sampah/' .
                        $v['id'] .
                        '\'); $(\'#editJenisSampahNama\').val(\'' .
                        $v['nama'] .
                        '\'); $(\'#editJenisSampahSumber\').val(\'' .
                        $v['sumber_sampah'] .
                        '\'); $(\'#editJenisSampahContoh\').val(\'' .
                        $v['contoh_sampah'] .
                        '\');">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </button>
                    <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"
                        data-toggle="modal" data-target="#deleteJenisSampah" 
                        onclick="$(\'#deleteJenisSampahForm\').attr(\'action\', \'/jenis-sampah/' .
                        $v['id'] .
                        '\'); $(\'#deleteJenisSampahNama\').text(\'' .
                        $v['nama'] .
                        '\');">
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                    </button>
                </nobr>';
                } else {
                    $v['actions'] = ' ';
                }

                // Replace ID with index + 1 for numbering
                $v['id'] = $index + 1;

                return array_values($v);
            },
            json_decode($jenisSampah, true),
            array_keys(json_decode($jenisSampah, true)),
        ),
        'order' => [[0, 'asc']],
        'columns' => [null, null, null, null, null],
    ];
@endphp

@section('content')
    <div class="container-fluid">
        <div class="row py-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Jenis Sampah Hotel</h1>
            </div>
            <div class="mb-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#createJenisSampah"> <i
                        class="fa fa-plus mr-2"></i>
                    Tambah</button>
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
                <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable
                    bordered with-buttons class="border border-black rounded">
                </x-adminlte-datatable>
                {{-- <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable
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
                                @if (Auth::user()->role === 'staff')
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
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </x-adminlte-datatable> --}}
            </div>
        </div>

        {{-- Create Jenis Sampah --}}
        <x-adminlte-modal id="createJenisSampah" title="Tambah Jenis Sampah" icon="fa fa-plus" v-centered>
            <form action="{{ route('jenis-sampah.store') }}" id="createJenisSampahForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Jenis</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                        placeholder="Masukkan nama jenis sampah.">
                </div>
                <div class="form-group">
                    <label for="sumber_sampah">Sumber</label>
                    <textarea name="sumber_sampah" id="sumber_sampah" class="form-control"
                        placeholder="Masukkan tempat darimana sampah berasal."></textarea>
                </div>
                <div class="form-group">
                    <label for="contoh_sampah">Contoh</label>
                    <textarea name="contoh_sampah" id="createJenisSampahContoh" class="form-control" placeholder="Masukkan contoh sampah."></textarea>
                </div>
                <x-slot name="footerSlot">
                    <button id="createJenisSampahButton" type="button" class="btn btn-primary"
                        onclick="$('#createJenisSampahForm').submit();">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </x-slot>
            </form>
        </x-adminlte-modal>

        {{-- Edit Jenis Sampah --}}
        <x-adminlte-modal id="editJenisSampah" title="Edit Jenis Sampah" icon="fa fa-pen" v-centered>
            <form action="" id="editJenisSampahForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama">Nama Jenis</label>
                    <input type="text" class="form-control" id="editJenisSampahNama" name="nama"
                        placeholder="Masukkan nama jenis sampah.">
                </div>
                <div class="form-group">
                    <label for="sumber_sampah">Sumber</label>
                    <textarea name="sumber_sampah" id="editJenisSampahSumber" class="form-control"
                        placeholder="Masukkan tempat darimana sampah berasal."></textarea>
                </div>
                <div class="form-group">
                    <label for="contoh_sampah">Contoh</label>
                    <textarea name="contoh_sampah" id="editJenisSampahContoh" class="form-control" placeholder="Masukkan contoh sampah."></textarea>
                </div>
                <x-slot name="footerSlot">
                    <button id="editJenisSampahButton" type="button" class="btn btn-primary"
                        onclick="$('#editJenisSampahForm').submit();">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </x-slot>
            </form>
        </x-adminlte-modal>

        {{-- Delete Jenis Sampah --}}
        <x-adminlte-modal id="deleteJenisSampah" title="Hapus Jenis Sampah" theme="danger" icon="fa fa-trash" v-centered>
            <p>Apakah Anda yakin ingin menghapus jenis sampah ini?</p>
            <p class="font-weight-bold" id="deleteJenisSampahNama"></p>
            <form action="" id="deleteJenisSampahForm" method="POST">
                @csrf
                @method('DELETE')
                <x-slot name="footerSlot">
                    <button id="deleteJenisSampahButton" type="button" class="btn btn-danger"
                        onclick="$('#deleteJenisSampahForm').submit();">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </x-slot>
            </form>
        </x-adminlte-modal>
    </div>
@endsection
