@extends('adminlte::page')

@section('title', 'Keputusan')

{{-- @section('plugins.DatatablesPlugin', true) --}}
@section('plugins.Datatables', true)

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
            </div>
            <div class="mb-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#createKriteria"> <i
                        class="fa fa-plus mr-2"></i>
                    Tambah</button>
                <h2 class="h5 mb-2 text-gray-800 font-weight-bold">Riwayat Keputusan</h2>
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
