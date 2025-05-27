@extends('adminlte::page')

@section('plugins.Sweetalert2', true)

@section('title', 'SPKSerela | Kriteria')

@section('plugins.Datatables', true)

@php
    $heads = [
        ['label' => 'No', 'width' => 4],
        'Nama',
        'Sifat',
        'Bobot',
        'Satuan Ukur',
        ['label' => 'Actions', 'no-export' => true, 'width' => 5],
    ];

    $config = [
        'data' => array_map('array_values', json_decode($kriterias, true)),
        'order' => [[0, 'asc']],
        'columns' => [null, null, null, null, null],
    ];
@endphp

@section('content')
    <div class="container-fluid">
        <div class="row py-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Kriteria Keputusan</h1>
                <div class="mb-2 d-flex">
                    @if (Auth::user()->role === 'kepala_divisi')
                        <button class="btn btn-primary mr-2 items-baseline" data-toggle="modal" data-target="#createKriteria">
                            <i class="fa fa-plus mr-2"></i>
                            Tambah</button>
                    @endif
                    <div class="bg-white border p-2 rounded-sm">Total Bobot :
                        <span>{{ $totalBobot * 100 }}%</span>
                    </div>
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
                    bordered with-buttons class="border border-black rounded">
                    @foreach ($config['data'] as $item)
                        <tr>
                            @foreach ($item as $key => $value)
                                @if ($loop->first)
                                    <td>{{ $loop->parent->iteration }}</td>
                                @elseif($key === 2)
                                    @if ($value == 'cost')
                                        <td>
                                            <h5>
                                                <span class="badge badge-pill badge-warning">{{ ucfirst($value) }}</span>
                                            </h5>
                                        </td>
                                    @else
                                        <td>
                                            <h5>
                                                <span class="badge badge-pill badge-success">{{ ucfirst($value) }}</span>
                                            </h5>
                                        </td>
                                    @endif
                                @elseif ($key === 5)
                                    @if (Auth::user()->role === 'kepala_divisi')
                                        <td>
                                            <nobr>
                                                <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                    title="Edit" data-toggle="modal" data-target="#editKriteria"
                                                    onclick="$('#editKriteriaForm').attr('action', '/kriteria/{{ $item[0] }}'); $('#editKriteriaLabel').val('{{ $item[1] }}'); $('#editKriteriaSifat').val('{{ $item[2] }}'); $('#editKriteriaBobot').val('{{ $item[3] }}'); $('#editKriteriaSatuanUkur').val('{{ $item[4] }}')">
                                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                                </button>
                                                @if ($value == true)
                                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                        title="Delete" data-toggle="modal" data-target="#deleteKriteria"
                                                        onclick="$('#deleteKriteriaForm').attr('action', '/kriteria/{{ $item[0] }}'); $('#deleteKriteriaLabel').text('{{ $item[1] }}');">
                                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-xs btn-default text-secondary mx-1 shadow"
                                                        title="Lock" disabled>
                                                        <i class="fa fa-lg fa-fw fa-lock"></i>
                                                    </button>
                                                @endif
                                            </nobr>
                                        </td>
                                    @else
                                        <td>
                                            <nobr>
                                                <button class="btn btn-xs btn-default text-secondary mx-1 shadow"
                                                    title="Lock" disabled>
                                                    <i class="fa fa-lg fa-fw fa-lock"></i>
                                                </button>
                                            </nobr>
                                        </td>
                                    @endif
                                @else
                                    <td>{{ $value }}</td>
                                @endif
                            @endforeach

                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </div>
        </div>

        {{-- Create Kriteria --}}
        @if (Auth::user()->role === 'kepala_divisi')
            <x-adminlte-modal id="createKriteria" title="Tambah Kriteria" theme="primary" icon="fa fa-plus" v-centered>
                <form action="{{ route('kriteria.store') }}" id="createKriteriaForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="label">Nama</label>
                        <input type="text" class="form-control" id="label" name="label">
                    </div>
                    <div class="form-group">
                        <label for="sifat">Sifat</label>
                        <select name="sifat" id="sifat" class="form-control">
                            <option selected disabled>--- Pilih Sifat ---</option>
                            <option value="cost">Cost</option>
                            <option value="benefit">Benefit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bobot">Bobot</label>
                        <input type="number" name="bobot" id="bobot" class="form-control"></input>
                    </div>
                    <div class="form-group">
                        <label for="satuan_ukur">Satuan Ukur</label>
                        <input type="text" name="satuan_ukur" id="satuan_ukur" class="form-control"></input>
                    </div>
                    <x-slot name="footerSlot">
                        <button id="createKriteria" type="button" class="btn btn-primary"
                            onclick="$('#createKriteriaForm').submit();">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </x-slot>
                </form>
            </x-adminlte-modal>
        @endif

        {{-- Edit Kriteria --}}
        <x-adminlte-modal id="editKriteria" title="Edit Kriteria" theme="primary" icon="fa fa-pen" v-centered>
            <form action="" id="editKriteriaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="editKriteriaLabel">Nama</label>
                    <input type="text" class="form-control" id="editKriteriaLabel" name="label">
                </div>
                <div class="form-group">
                    <label for="sifat">Sifat</label>
                    <select name="sifat" id="editKriteriaSifat" class="form-control">
                        <option value="cost">Cost</option>
                        <option value="benefit">Benefit</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bobot">Bobot</label>
                    <input type="number" name="bobot" id="editKriteriaBobot" class="form-control"></input>
                </div>
                <div class="form-group">
                    <label for="satuan_ukur">Satuan Ukur</label>
                    <input type="text" name="satuan_ukur" id="editKriteriaSatuanUkur" class="form-control"></input>
                </div>
                <x-slot name="footerSlot">
                    <button id="editKriteria" type="button" class="btn btn-primary"
                        onclick="$('#editKriteriaForm').submit();">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </x-slot>
            </form>
        </x-adminlte-modal>

        {{-- Delete Kriteria --}}
        <x-adminlte-modal id="deleteKriteria" title="Hapus Kriteria" theme="danger" icon="fa fa-trash" v-centered>
            <p>Apakah Anda yakin ingin menghapus kriteria ini?</p>
            <p class="font-weight-bold" id="deleteKriteriaLabel"></p>
            <form action="" id="deleteKriteriaForm" method="POST">
                @csrf
                @method('DELETE')
                <x-slot name="footerSlot">
                    <button id="deleteKriteriaButton" type="button" class="btn btn-danger"
                        onclick="$('#deleteKriteriaForm').submit();">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </x-slot>
            </form>
        </x-adminlte-modal>
    </div>
@endsection
