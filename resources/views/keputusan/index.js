function calculate() {
    var formData = new FormData(event.target.form);
    $.ajax({
        url: `{{ route('keputusan.calculate') }}`,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            const hasils = data;
            const resultModalContainer = document.querySelector(
                "#resultModalContainer"
            );
            const resultHtml = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Rank</th>
                            <th scope="col">TPA</th>
                            <th scope="col">Skor</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${hasils
                            .map(
                                (data, index) => `
                                <tr>
                                <td>${index + 1}</td>
                                <td>${data.view.nama}</td>
                                <td>${data.skor}</td>
                                <td><button type="button" id="${index}" class="resultDetailModal btn btn-xs btn-default text-primary" data-toggle="modal" data-target="#resultDetailModal"><i class="fa fa-lg fa-fw fa-info-circle"></i></button></td>
                                </tr>
                                `
                            )
                            .join("")}
                    </tbody>
                </table>
            `;
            resultModalContainer.innerHTML = resultHtml;

            $(".resultDetailModal").on("click", function () {
                $("#resultModal").modal("hide");
                const hasil = hasils[this.id];

                $("#resultDetailModalContainer").html(`
                <!-- TPA Information Section -->
                <div class="card mb-3">
                <div class="card-header bg-light">
                <h6 class="mb-0 font-weight-bold">Informasi TPA</h6>
                </div>
                <div class="card-body">
                <div class="row">
                <div class="col-md-6">
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Nama TPA:</div>
                        <div>${hasil.view.nama}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Alamat:</div>
                        <div>${hasil.view.alamat}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Kontak:</div>
                        <div>${hasil.view.kontak}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Rank:</div>
                        <div><span class="badge badge-primary">${
                            hasil.view.rank
                        }</span></div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Jarak:</div>
                        <div>${hasil.view.jarak} km</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Biaya:</div>
                        <div>Rp${hasil.view.biaya}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Tingkat Kemacetan:</div>
                        <div>
                            <span class="badge ${
                                hasil.view.tingkat_kemacetan === "Rendah"
                                    ? "badge-success"
                                    : hasil.view.tingkat_kemacetan === "Sedang"
                                    ? "badge-warning"
                                    : "badge-danger"
                            }">${hasil.view.tingkat_kemacetan}</span>
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
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Jenis Sampah:</div>
                        <div>${hasil.view.jenis_sampah}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Sumber Sampah:</div>
                        <div>${hasil.view.sumber_sampah}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Jumlah Sampah:</div>
                        <div>${hasil.view.jumlah_sampah} kg</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex mb-2">
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
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Nama:</div>
                        <div>${hasil.view.nama_pengguna}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Email:</div>
                        <div>${hasil.view.email_pengguna}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Jabatan:</div>
                        <div><span class="badge badge-info">${
                            hasil.view.role
                        }</span></div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="font-weight-bold">Tanggal:</div>
                        <div>${new Date(
                            hasil.view.created_at
                        ).toLocaleString()}</div>
                    </div>
                </div>
                </div>
                </div>
                </div>
            `);
            });
        },
        error: function (error) {
            console.error(error);
            const errorHTML = `
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Terjadi Kesalahan</h4>
                    <p>${error.responseJSON.message}</p>
                </div>
            `;
            const resultModalContainer = document.querySelector(
                "#resultModalContainer"
            );
            resultModalContainer.innerHTML = errorHTML;
            return error;
        },
    });
}

$("#resultDetailModal").on("hidden.bs.modal", function () {
    $("#resultModal").modal("show");
});
