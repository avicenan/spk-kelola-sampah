<script>
    let hasils;

    function setHasils(data) {
        hasils = data;
    }

    // Show Result Modal on Result Detail Hidden
    $('#resultDetailModal').on('hidden.bs.modal', function() {
        $('#resultModal').modal('show');
    });

    // Trigger Kalkulasi Alternatif
    $('#calculate').on('click', function() {
        calculate();
    })

    $('#saveResult').on('click', function() {
        saveResult();
    })

    // Print result detailed data
    function printView() {
        const printContent = document.querySelector(`#resultDetailModalContainer`).innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
    }

    // Show result detail data in modal
    function showDetail(id) {
        const hasil = hasils[id];
        $('#resultDetailModalContainer').html(`
                                        <div class="text-center">
                                            <h4 class='font-weight-bold'> Hasil Keputusan</h4>
                                            <p>Serela Hotel Cihampelas</p>
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
                                                                <span class="badge ${hasil.view.tingkat_kemacetan >= 1 && hasil.view.tingkat_kemacetan <= 2 ? 'badge-primary' : hasil.view.tingkat_kemacetan >= 3 && hasil.view.tingkat_kemacetan <= 4 ? 'badge-warning' : 'badge-danger'}">${hasil.view.tingkat_kemacetan} - ${hasil.view.tingkat_kemacetan >= 1 && hasil.view.tingkat_kemacetan <= 2 ? 'Kurang' : hasil.view.tingkat_kemacetan >= 3 && hasil.view.tingkat_kemacetan <= 4 ? 'Sedang' : 'Banyak'}</span>
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

        $('#resultModal').modal('hide');
    }

    // show result data in modal
    function renderResult() {
        const saveButton = $('#saveResult');
        saveButton.prop('hidden', false);
        saveButton.prop('disabled', false);
        saveButton.html(`<i
                class="fa fa-lg fa-fw fa-save"></i>Simpan`);
        const resultHtml = '<table class="table table-bordered">' +
            '<thead>' +
            '<tr>' +
            '<th scope="col" class="text-center">Rank</th>' +
            '<th scope="col" class="text-center">TPA</th>' +
            '<th scope="col" class="text-center">Skor</th>' +
            '<th scope="col" class="text-center">View</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            hasils.map((data, index) => `
                                        <tr>
                                            <td class="text-center">#${index + 1}</td>
                                            <td class="text-center">${data.view.nama}</td>
                                            <td class="text-center">${data.skor}</td>
                                            <td class="text-center"><button type="button" class="btn btn-xs btn-default text-primary" data-toggle="modal" data-target="#resultDetailModal" onclick="showDetail(${index})"><i class="fa fa-lg fa-fw fa-receipt"></i></button></td>
                                        </tr>
                                    `).join('') +
            '</tbody>' +
            '</table>';
        $('#resultModalContainer').html(resultHtml);
    }

    // show error alert on result modal
    function renderResultError(error) {
        const errorHTML = `
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Terjadi Kesalahan</h4>
                <p>${error.responseJSON.message}</p>
                </div>
            `;
        $('#resultModalContainer').html(errorHTML);
    }

    // calculate data with SAW
    function calculate() {
        var formData = new FormData(event.target.form);
        $.ajax({
            url: `{{ route('keputusan.calculate') }}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                setHasils(data);
                renderResult();
            },
            error: function(error) {
                // console.error(error);
                renderResultError(error);
            }
        });
    }

    // Save result hasils
    function saveResult() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = new FormData();
        formData.append('data', JSON.stringify(hasils));
        $.ajax({
            url: `{{ route('keputusan.store') }}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                Toast.fire({
                    title: 'Success',
                    text: 'Data berhasil disimpan',
                    icon: 'success'
                });
                console.log(data)
                $('#saveResult').prop('disabled', true);
                $('#saveResult').text('Disimpan');
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
</script>
