<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="title mb-5">
                <h4>Data Lokasi</h4>
            </div>

            <div class="card shadow-sm card-flush border-0">
                <div class="card-header d-flex justify-content-between align-items-center py-4">
                    <!-- Tombol Create (Sekarang memanggil modal) -->
                    <div class="card-title">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahLokasi">
                            Tambah Lokasi
                        </button>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="card-toolbar me-4">
                        <div class="d-flex align-items-center position-relative my-1">
                            <input type="text" data-kt-filter="search" class="form-control form-control-sm form-control-solid w-200px ps-10 pe-30" placeholder="Cari..." />
                            <span class="svg-icon svg-icon-1 position-absolute end-0 top-50 translate-middle-y me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- TABEL DATA -->
                <div class="card-body">
                    <table id="datatables" class="table table-row-bordered gy-5 gs-7 border rounded">
                        <thead class="bg-light">
                            <tr class="fw-bold text-center">
                                <th>No</th>
                                <th>Nama Lokasi</th>
                                <th>Created At</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center" id="data-lokasi">
                            <?php $no = 1;
                            foreach ($lokasi as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($row['nama_lokasi']) ?></td>
                                    <td>
                                        <?php
                                        $bulan = [
                                            '01' => 'JANUARI',
                                            '02' => 'FEBRUARI',
                                            '03' => 'MARET',
                                            '04' => 'APRIL',
                                            '05' => 'MEI',
                                            '06' => 'JUNI',
                                            '07' => 'JULI',
                                            '08' => 'AGUSTUS',
                                            '09' => 'SEPTEMBER',
                                            '10' => 'OKTOBER',
                                            '11' => 'NOVEMBER',
                                            '12' => 'DESEMBER'
                                        ];
                                        $tanggal = date('d', strtotime($row['created_at']));
                                        $bulanNum = date('m', strtotime($row['created_at']));
                                        $tahun = date('Y', strtotime($row['created_at']));
                                        echo $tanggal . ' ' . $bulan[$bulanNum] . ' ' . $tahun;
                                        ?>
                                    </td>

                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn btn-sm btn-warning btn-edit"
                                                data-id="<?= $row['id_lokasi'] ?>"
                                                data-nama="<?= esc($row['nama_lokasi']) ?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger deleteLokasi" data-id_lokasi="<?= $row['id_lokasi']; ?>">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Lokasi -->
<div class="modal fade" id="modalTambahLokasi" tabindex="-1" aria-labelledby="modalTambahLokasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLokasiLabel">Tambah Lokasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahLokasi" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
                        <input type="text" class="form-control form-control-sm" id="nama_lokasi" name="nama_lokasi">
                        <div class="invalid-feedback" id="error-nama_lokasi"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" onclick="saveData()" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Edit Lokasi -->
<div class="modal fade" id="modalEditLokasi" tabindex="-1" aria-labelledby="modalEditLokasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLokasiLabel">Edit Lokasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditLokasi" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_id_lokasi" name="id_lokasi">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_lokasi" class="form-label">Nama Lokasi</label>
                        <input type="text" class="form-control form-control-sm" id="edit_nama_lokasi" name="nama_lokasi">
                        <div class="invalid-feedback" id="edit-error-nama_lokasi"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Hapus/Konfirmasi -->
<div class="modal fade" id="confirmation-modal" tabindex="-1" aria-labelledby="confirmation-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-warning">
            <div class="modal-header border-0 position-relative">
                <div class="w-100 text-center">
                    <h5 class="modal-title" id="confirmation-modal-label">
                        <i class="fa fa-exclamation-triangle text-dark fs-2 me-2"></i> Peringatan
                    </h5>
                </div>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center">
                <h6>Apakah Anda yakin akan menghapus data ini?</h6>
                <p class="text-sm">Data yang dihapus tidak dapat dikembalikan.</p>
                <input type="hidden" name="id_lokasi" id="id_delete" class="form-control">
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-dark" onclick="deleteData()">Ya</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tidak</button>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    document.addEventListener("DOMContentLoaded", function() {
        const table = $('#datatables').DataTable({
            responsive: true,
            pageLength: 10,
            order: [],
            language: {
                zeroRecords: "Data yang dicari tidak ditemukan",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(disaring dari _MAX_ total data)"
            }
        });

        // Fungsi pencarian dari input
        const filterSearch = document.querySelector('[data-kt-filter="search"]');
        if (filterSearch) {
            filterSearch.addEventListener('keyup', function(e) {
                table.search(e.target.value).draw();
            });
        }
    });


    // Fungsi pesan alert dengan timeout
    function alertMessage(message, type = 'success') {
        Swal.fire({
            text: message,
            icon: type,
            buttonsStyling: false,
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
            customClass: {
                confirmButton: "btn btn-sm btn-light-" + (type === 'success' ? "success" : "danger")
            },
            didClose: () => {
                $('#confirmation-modal').modal('hide');
            }
        });
    }


    // fungsi simpan data (modal tambah)
    function saveData() {
        const form = $('#formTambahLokasi');
        const formData = form.serialize();

        // Reset error states
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        $.ajax({
            type: "POST",
            url: "<?= base_url('lokasi/post') ?>",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#modalTambahLokasi').modal('hide');
                    form[0].reset();

                    setTimeout(function() {
                        location.reload();
                    }, 1600);
                } else if (response.status === 'error') {
                    // Tampilkan error di field
                    $.each(response.errors, function(field, message) {
                        $('#' + field).addClass('is-invalid');
                        $('#error-' + field).text(message);
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    // Real-time hilangkan is-invalid saat diketik
    // $('#nama_lokasi').on('input', function() {
    //     if ($(this).val().length > 0) {
    //         $(this).removeClass('is-invalid').addClass('is-valid');
    //         $('#error-nama_lokasi').text('');
    //     } else {
    //         $(this).removeClass('is-valid');
    //     }
    // });


    const formEditLokasi = document.getElementById("formEditLokasi");

    // Reset error jika field diubah
    document.getElementById('edit_nama_lokasi').addEventListener('input', function() {
        if (this.value.length > 0) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            document.getElementById('edit-error-nama_lokasi').innerText = '';
        } else {
            this.classList.remove('is-valid');
        }
    });

    // Handle klik tombol edit
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');

            document.getElementById('edit_id_lokasi').value = id;
            document.getElementById('edit_nama_lokasi').value = nama;

            // Reset status validasi
            document.getElementById('edit_nama_lokasi').classList.remove('is-invalid', 'is-valid');
            document.getElementById('edit-error-nama_lokasi').innerText = '';

            const modal = new bootstrap.Modal(document.getElementById('modalEditLokasi'));
            modal.show();
        });
    });

    // Handle submit form edit
    formEditLokasi.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(formEditLokasi);
        const idLokasi = document.getElementById("edit_id_lokasi").value;

        // Reset error state
        const input = document.getElementById("edit_nama_lokasi");
        const errorDiv = document.getElementById("edit-error-nama_lokasi");
        input.classList.remove('is-invalid');
        input.classList.remove('is-valid');
        errorDiv.innerText = '';

        fetch(`<?= base_url('/lokasi/update') ?>/${idLokasi}`, {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditLokasi'));
                    modal.hide();

                    setTimeout(() => {
                        location.reload();
                    }, 2100);
                } else if (result.status === 'error' && result.errors) {
                    if (result.errors.nama_lokasi) {
                        input.classList.add('is-invalid');
                        errorDiv.innerText = result.errors.nama_lokasi;
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.message || 'Terjadi kesalahan.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan, silakan coba lagi.',
                });
            });
    });



    // hapus data
    // $('#data-lokasi').on('click', '.deleteLokasi', function() {
    //     var id = $(this).data('id_lokasi');
    //     console.log(id);
    //     $('#confirmation-modal').modal('show');
    //     $('#id_delete').val(id);
    // });

    document.getElementById('data-lokasi').addEventListener('click', function (e) {
    if (e.target.classList.contains('deleteLokasi')) {
        let id = e.target.getAttribute('data-id_lokasi');
        console.log(id);

        // show modal
        let modal = new bootstrap.Modal(document.getElementById('confirmation-modal'));
        modal.show();

        // set input hidden value
        document.getElementById('id_delete').value = id;
    }
    });

    function deleteData() {
        var id = $('#id_delete').val();

        $.ajax({
            type: "DELETE",
            dataType: "html",
            url: `/lokasi/delete/${id}`,
            success: function(response) {
                $("#" + id).remove();
                $("#id_delete").val("");
                alertMessage('Berhasil menghapus data lokasi!', 'success')

                setTimeout(function() {
                    window.location.reload();
                }, 1600);
            }
        });
        return false;
    };
</script>

<?= $this->endSection(); ?>