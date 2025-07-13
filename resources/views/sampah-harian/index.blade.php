@extends('adminlte::page')

@section('title', 'Data Harian Sampah')

@section('content_header')
    <h1>Data Harian Sampah</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Line Chart -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Grafik Volume Sampah Harian
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="lineChart"
                        style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Data Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-2"></i>
                        Tabel Data Harian Sampah
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                            <i class="fas fa-plus mr-1"></i>
                            Input Laporan Harian
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sampahHarianTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Tanggal Input</th>
                                    <th width="20%">Kategori Sampah</th>
                                    <th width="15%">Volume Sampah (kg)</th>
                                    <th width="20%">Sumber Sampah</th>
                                    <th width="15%">Input Oleh</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sampahHarian as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->tanggal_input->format('d/m/Y') }}</td>
                                        <td>{{ $data->jenisSampah->nama }}</td>
                                        <td>{{ number_format($data->volume_sampah, 2) }}</td>
                                        <td>{{ $data->sumber_sampah }}</td>
                                        <td>{{ $data->user->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info edit-btn"
                                                data-id="{{ $data->id }}"
                                                data-jenis-sampah-id="{{ $data->jenis_sampah_id }}"
                                                data-volume-sampah="{{ $data->volume_sampah }}"
                                                data-sumber-sampah="{{ $data->sumber_sampah }}"
                                                data-tanggal-input="{{ $data->tanggal_input->format('Y-m-d') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-id="{{ $data->id }}"
                                                data-jenis-sampah="{{ $data->jenisSampah->nama }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Input Laporan Sampah Harian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tanggal_input">Tanggal Input <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_input" name="tanggal_input"
                                value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Data Sampah Harian</label>
                            <div class="text-right mb-2">
                                <button type="button" class="btn btn-sm btn-success" id="addRowBtn">
                                    <i class="fas fa-plus mr-1"></i>Tambah Kategori
                                </button>
                            </div>
                            <div class="alert alert-info" id="summaryInfo" style="display: none;">
                                <strong>Ringkasan:</strong> <span id="totalCategories">0</span> kategori dengan total volume
                                <span id="totalVolume">0</span> kg
                            </div>
                            <div id="wasteItemsContainer">
                                <div class="waste-item border rounded p-3 mb-3" data-index="0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Kategori Sampah <span class="text-danger">*</span></label>
                                                <select class="form-control jenis-sampah-select"
                                                    name="bulk_data[0][jenis_sampah_id]" required>
                                                    <option value="">Pilih Kategori Sampah</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Volume (kg) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control"
                                                    name="bulk_data[0][volume_sampah]" step="0.01" min="0.01"
                                                    max="1000" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Sumber Sampah <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                    name="bulk_data[0][sumber_sampah]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-sm btn-danger remove-row"
                                                    style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Semua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Sampah Harian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_jenis_sampah_id">Kategori Sampah <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_jenis_sampah_id" name="jenis_sampah_id"
                                        required>
                                        <option value="">Pilih Kategori Sampah</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_volume_sampah">Volume Sampah (kg) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="edit_volume_sampah"
                                        name="volume_sampah" step="0.01" min="0.01" max="1000" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_sumber_sampah">Sumber Sampah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_sumber_sampah" name="sumber_sampah"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="edit_tanggal_input">Tanggal Input <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_tanggal_input" name="tanggal_input"
                                max="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-body canvas {
            width: 100% !important;
        }

        .waste-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6 !important;
        }

        .waste-item:hover {
            background-color: #e9ecef;
        }

        .remove-row {
            margin-top: 32px;
        }

        #addRowBtn {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/vendor/sweetalert2/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#sampahHarianTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#sampahHarianTable_wrapper .col-md-6:eq(0)');

            // Load jenis sampah for dropdowns
            loadJenisSampah();

            // Initialize line chart
            initializeChart();

            // Initialize remove buttons
            updateRemoveButtons();

            // Form submissions
            $('#addForm').on('submit', function(e) {
                e.preventDefault();
                addData();
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                updateData();
            });

            // Edit button click
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const jenisSampahId = $(this).data('jenis-sampah-id');
                const volumeSampah = $(this).data('volume-sampah');
                const sumberSampah = $(this).data('sumber-sampah');
                const tanggalInput = $(this).data('tanggal-input');

                $('#edit_id').val(id);
                $('#edit_jenis_sampah_id').val(jenisSampahId);
                $('#edit_volume_sampah').val(volumeSampah);
                $('#edit_sumber_sampah').val(sumberSampah);
                $('#edit_tanggal_input').val(tanggalInput);

                $('#editModal').modal('show');
            });

            // Delete button click
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                const jenisSampah = $(this).data('jenis-sampah');

                if (confirm(`Apakah Anda yakin ingin menghapus data sampah ${jenisSampah}?`)) {
                    deleteData(id);
                }
            });
        });

        function loadJenisSampah() {
            // Prevent multiple calls
            if ($('#jenis_sampah_id option').length > 1) {
                return;
            }

            $.get('{{ route('harian-sampah.jenis-sampah') }}')
                .done(function(data) {
                    let options = '<option value="">Pilih Kategori Sampah</option>';
                    data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    });
                    // Clear existing options first to prevent duplication
                    $('#jenis_sampah_id').empty().html(options);
                    $('#edit_jenis_sampah_id').empty().html(options);

                    // Load options for bulk form selects
                    $('.jenis-sampah-select').each(function() {
                        if ($(this).find('option').length <= 1) {
                            $(this).html(options);
                        }
                    });
                })
        }

        // Add row functionality for bulk form
        $('#addRowBtn').on('click', function() {
            addWasteRow();
        });

        function addWasteRow() {
            const container = $('#wasteItemsContainer');
            const newIndex = container.children().length;

            $.get('{{ route('harian-sampah.jenis-sampah') }}')
                .done(function(data) {
                    let options = '<option value="">Pilih Kategori Sampah</option>';
                    data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    });

                    const newRow = `
                        <div class="waste-item border rounded p-3 mb-3" data-index="${newIndex}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Kategori Sampah <span class="text-danger">*</span></label>
                                        <select class="form-control jenis-sampah-select" name="bulk_data[${newIndex}][jenis_sampah_id]" required>
                                            ${options}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Volume (kg) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="bulk_data[${newIndex}][volume_sampah]"
                                            step="0.01" min="0.01" max="1000" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sumber Sampah <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="bulk_data[${newIndex}][sumber_sampah]" required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    container.append(newRow);
                    updateRemoveButtons();
                })
        }

        function updateRemoveButtons() {
            const items = $('.waste-item');
            items.each(function(index) {
                const removeBtn = $(this).find('.remove-row');
                if (items.length === 1) {
                    removeBtn.hide();
                } else {
                    removeBtn.show();
                }
            });
        }

        // Remove row functionality
        $(document).on('click', '.remove-row', function() {
            $(this).closest('.waste-item').remove();
            reindexRows();
            updateRemoveButtons();
        });

        function reindexRows() {
            $('.waste-item').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('select').attr('name', `bulk_data[${index}][jenis_sampah_id]`);
                $(this).find('input[name*="volume_sampah"]').attr('name', `bulk_data[${index}][volume_sampah]`);
                $(this).find('input[name*="sumber_sampah"]').attr('name', `bulk_data[${index}][sumber_sampah]`);
            });
            updateSummary();
        }

        function updateSummary() {
            const items = $('.waste-item');
            let totalVolume = 0;
            let validCategories = 0;

            items.each(function() {
                const volume = parseFloat($(this).find('input[name*="volume_sampah"]').val()) || 0;
                const category = $(this).find('select').val();

                if (volume > 0 && category) {
                    totalVolume += volume;
                    validCategories++;
                }
            });

            if (validCategories > 0) {
                $('#totalCategories').text(validCategories);
                $('#totalVolume').text(totalVolume.toFixed(2));
                $('#summaryInfo').show();
            } else {
                $('#summaryInfo').hide();
            }
        }

        // Update summary when inputs change
        $(document).on('input', 'input[name*="volume_sampah"]', updateSummary);
        $(document).on('change', '.jenis-sampah-select', updateSummary);

        function initializeChart() {
            const chartData = @json($chartData);

            const labels = chartData.map(item => item.nama);
            const data = chartData.map(item => item.total_volume);

            const ctx = document.getElementById('lineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Volume Sampah (kg)',
                        data: data,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10,
                                max: Math.max(...data) > 150 ? 200 : 150
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }

        function addData() {
            const formData = new FormData($('#addForm')[0]);

            $.ajax({
                url: '{{ route('harian-sampah.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#addModal').modal('hide');
                        $('#addForm')[0].reset();

                        // Reset bulk form to single row
                        $('#wasteItemsContainer').html(`
                            <div class="waste-item border rounded p-3 mb-3" data-index="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kategori Sampah <span class="text-danger">*</span></label>
                                            <select class="form-control jenis-sampah-select" name="bulk_data[0][jenis_sampah_id]" required>
                                                <option value="">Pilih Kategori Sampah</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Volume (kg) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="bulk_data[0][volume_sampah]"
                                                step="0.01" min="0.01" max="1000" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Sumber Sampah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bulk_data[0][sumber_sampah]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-sm btn-danger remove-row" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);

                        // Reload jenis sampah options
                        loadJenisSampah();

                        // Show success message with details
                        if (response.total_items > 1) {
                            Swal.fire('Berhasil',
                                `Berhasil menambahkan ${response.total_items} data sampah harian dengan total volume ${response.total_volume} kg`,
                                'success');
                        } else {
                            Swal.fire('Berhasil', 'Data sampah harian berhasil ditambahkan', 'success');
                        }

                        location.reload();
                    } else {
                        Swal.fire('Gagal', 'Gagal menambahkan data: ' + response.message, 'error');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        Swal.fire('Gagal', 'Gagal menambahkan data: ' + response.message, 'error');
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menambahkan data', 'error');
                    }
                }
            });
        }

        function updateData() {
            const id = $('#edit_id').val();
            const formData = new FormData($('#editForm')[0]);

            $.ajax({
                url: `/harian-sampah/${id}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-HTTP-Method-Override': 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        $('#editModal').modal('hide');
                        Swal.fire('Berhasil', 'Data sampah harian berhasil diperbarui', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', 'Gagal memperbarui data: ' + response.message, 'error');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        Swal.fire('Gagal', 'Gagal memperbarui data: ' + response.message, 'error');
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui data', 'error');
                    }
                }
            });
        }

        function deleteData(id) {
            $.ajax({
                url: `/harian-sampah/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil', 'Data sampah harian berhasil dihapus', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', 'Gagal menghapus data: ' + response.message, 'error');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        Swal.fire('Gagal', 'Gagal menghapus data: ' + response.message, 'error');
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data', 'error');
                    }
                }
            });
        }
    </script>
@stop
