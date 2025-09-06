<?php date_default_timezone_set('Asia/Jakarta');
?>

<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>
<?php if (session()->getFlashdata('success')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success'); ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="title mb-5">
                <h4>Data Ruangan</h4>
            </div>

            <div class="card shadow-sm card-flush border-0">
                <div class="card-header d-flex justify-content-between align-items-center py-4">
                    <div class="card-title">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahRuangan">
                            Tambah Ruangan
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
                    <table id="datatable" class="table table-row-bordered table-rounded border gy-5 gs-5">
                        <thead class="bg-light">
                            <tr class="fw-bold text-center">
                                <th>No</th>
                                <th>Nama Ruangan</th>
                                <th>Lokasi</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center" id="data-ruangan">
                            <?php $no = 1;
                            foreach ($ruangan as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($row['ruangan']) ?></td>
                                    <td><?= esc($row['nama_lokasi']) ?></td>
                                    <td><?= esc($row['role']) ?></td>
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
                                            <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalQR<?= $row['id_ruangan']; ?>">Detail QRCode</a>
                                            <a href="<?= base_url('ruangan/edit/' . $row['id_ruangan']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <button type="button" class="btn btn-sm btn-danger deleteRuangan" data-id_ruangan="<?= $row['id_ruangan']; ?>">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                        <?php foreach ($ruangan as $row): ?>
                            <?php $qrContent = $row['qrcode']; ?>
                            <div class="modal fade" id="modalQR<?= $row['id_ruangan']; ?>" tabindex="-1" aria-labelledby="modalQRLabel<?= $row['id_ruangan']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title" id="modalQRLabel<?= $row['id_ruangan']; ?>">QR Code</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body text-center py-4">
                                            <img
                                                src="<?= base_url('assets/file/qrcode/' . $row['uuid_ruangan'] . '.png'); ?>" width="200" height="200" alt="QR Code">
                                            <p class="text-muted small">
                                            <pre><?= esc($row['qrcode']) ?></pre>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Ruangan -->
<div class="modal fade" id="modalTambahRuangan" tabindex="-1" aria-labelledby="modalTambahRuangan" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formTambahRuangan" action="<?= base_url('/ruangan/post') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <!-- Nama Ruangan -->
                    <div class="mb-3">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text"
                            name="ruangan"
                            class="form-control form-control-sm"
                            placeholder="Contoh: Ruang Rapat 1"
                            value="<?= old('ruangan'); ?>">
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Lokasi -->
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <select name="id_lokasi"
                            class="form-select form-select-sm"
                            data-dropdown-parent="#modalTambahRuangan"
                            data-control="select2">
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($lokasi as $l): ?>
                                <option value="<?= $l['id_lokasi'] ?>" <?= old('id_lokasi') == $l['id_lokasi'] ? 'selected' : '' ?>>
                                    <?= esc($l['nama_lokasi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Role Petugas -->
                    <div class="mb-3">
                        <label class="form-label">Role Petugas</label>
                        <select name="role"
                            class="form-select form-select-sm"
                            id="petugas"
                            data-dropdown-parent="#modalTambahRuangan"
                            data-control="select2">
                            <option value="">Pilih Petugas</option>
                            <?php foreach ($tb_role as $role): ?>
                                <option value="<?= $role['id_role']; ?>" <?= old('role') == $role['id_role'] ? 'selected' : '' ?>>
                                    <?= esc($role['role']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
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
                <input type="hidden" name="id_ruangan" id="id_delete" class="form-control">
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
        const table = $('#datatable').DataTable({
            responsive: true,
            pageLength: 10,
            ordering: true,
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


    // Fungsi modal tambah
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("formTambahRuangan");

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            // Reset semua is-invalid & feedback
            form.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
            form.querySelectorAll(".is-valid").forEach(el => el.classList.remove("is-valid"));
            form.querySelectorAll(".invalid-feedback").forEach(el => el.textContent = "");

            fetch("<?= base_url('/ruangan/post') ?>", {
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

                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahRuangan'));
                        modal.hide();
                        form.reset();

                        setTimeout(() => {
                            location.reload();
                        }, 2100);
                    } else if (result.status === 'error') {
                        const errors = result.errors;

                        for (const [field, message] of Object.entries(errors)) {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add("is-invalid");

                                // Untuk Select2 Metronic
                                if ($(input).hasClass("select2-hidden-accessible")) {
                                    $(input)
                                        .siblings('.select2-container')
                                        .find('.select2-selection')
                                        .addClass('is-invalid');
                                }

                                const feedback = input.closest(".mb-3").querySelector(".invalid-feedback");
                                if (feedback) {
                                    feedback.textContent = message;
                                }
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Terjadi kesalahan saat mengirim data.'
                    });
                });

            // Tambahan: reset error saat user mengetik atau memilih
            form.querySelectorAll("input, select, textarea").forEach(field => {
                if (field.tagName.toLowerCase() === "select" && $(field).hasClass("select2-hidden-accessible")) {
                    // Select2: gunakan event dari plugin
                    $(field).on("change", function() {
                        resetValidation(field);
                    });
                } else {
                    // Input biasa
                    field.addEventListener("input", () => resetValidation(field));
                    field.addEventListener("change", () => resetValidation(field));
                }
            });


            function resetValidation(field) {
                if (field.classList.contains("is-invalid")) {
                    field.classList.remove("is-invalid");
                    field.classList.add("is-valid");

                    if ($(field).hasClass("select2-hidden-accessible")) {
                        $(field)
                            .siblings('.select2-container')
                            .find('.select2-selection')
                            .removeClass('is-invalid')
                            .addClass('is-valid');
                    }

                    const feedback = field.closest(".mb-3")?.querySelector(".invalid-feedback");
                    if (feedback) feedback.textContent = "";
                }
            }
        });
    });


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

    // hapus data
    document.getElementById('data-ruangan').addEventListener('click', function (e) {
    if (e.target.classList.contains('deleteRuangan')) {
        let id = e.target.getAttribute('data-id_ruangan');

        let modal = new bootstrap.Modal(document.getElementById('confirmation-modal'));
        modal.show();

        document.getElementById('id_delete').value = id;
    }
    });

    function deleteData() {
        var id = $('#id_delete').val();

        $.ajax({
            type: "DELETE",
            dataType: "html",
            url: `/ruangan/delete/${id}`,
            success: function(response) {
                $("#" + id).remove();
                $("#id_delete").val("");
                alertMessage('Berhasil menghapus data ruangan!', 'success')

                setTimeout(function() {
                    window.location.reload();
                }, 1600);
            }
        });
        return false;
    };
</script>

<?= $this->endSection(); ?>