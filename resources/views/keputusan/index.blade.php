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
                <form action="{{ route('keputusan.calculate') }}" id="createKeputusanForm" method="POST"
                    class="p-2 bg-white border mb-4">
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
                                    $inputDateConfig = ['format' => 'L'];
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

                        {{-- Submit Button --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Kalkulasi</button>
                        </div>

                    </div>


                </form>
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
