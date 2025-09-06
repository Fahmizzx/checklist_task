<?php date_default_timezone_set('Asia/Jakarta');
?>

<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="title mb-5">
                <h4>Pemetaan Checklist Maintenance</h4>
            </div>
            <div class="card card-flush">
                <div class="card-header align-items-center py-4 gap-2 gap-md-5 justify-content-between">
                    <div class="card-title">
                        <a href="<?= base_url('pemetaan/create'); ?>" class="btn btn-sm btn-primary ms-4">
                            Buat Pemetaan
                        </a>
                    </div>

                    <div class="card-toolbar me-4">
                        <div class="d-flex align-items-center position-relative my-1">
                            <input type="text" data-kt-filter="search" class="form-control form-control-sm form-control-solid w-180px ps-10 pe-30" placeholder="Cari..." />
                            <span class="svg-icon svg-icon-1 position-absolute end-0 top-50 translate-middle-y me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-rounded border gy-5 gs-5" id="table_search">
                            <thead class="bg-light">
                                <tr class="fw-bold text-center">
                                    <th>No</th>
                                    <th>Lokasi</th>
                                    <th>Created at</th>
                                    <th>Ruangan</th>
                                    <th>Petugas</th>
                                    <th>Periodik</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center" id="data-pemetaan">
                                <?php $no = 1; ?>
                                <?php foreach ($tb_checklist_maintance as $maintance) : ?>
                                    <tr id="<?= $maintance->id_checklist_maintance; ?>">
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $maintance->nama_lokasi; ?></td>
                                        <td><?= date('d-M-Y H.i', strtotime($maintance->created_at)) . ' WIB' ?></td>
                                        <td><?= $maintance->ruangan; ?></td>
                                        <td><?= $maintance->role; ?></td>
                                        <td><?= $maintance->periodik; ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                <a href="<?= base_url('pemetaan/detail/' . $maintance->id_checklist_maintance . '/' . $maintance->id_ruangan); ?>" type="button" class="btn btn-sm btn-success">Detail</a>
                                                <button type="button" class="btn btn-sm btn-warning editPemetaan" data-bs-toggle="modal" data-bs-target="#edit_pemetaan"
                                                    data-id_checklist_maintance="<?= $maintance->id_checklist_maintance; ?>"
                                                    data-id_lokasi="<?= $maintance->id_lokasi; ?>"
                                                    data-lokasi="<?= $maintance->nama_lokasi; ?>"
                                                    data-ruangan="<?= $maintance->id_ruangan; ?>"
                                                    data-id_petugas="<?= $maintance->id_role; ?>"
                                                    data-petugas="<?= $maintance->role; ?>"
                                                    data-id_periodik="<?= $maintance->id_periodik; ?>"
                                                    data-periodik="<?= $maintance->periodik; ?>">Edit</button>
                                                <button type="button" class="btn btn-sm btn-danger deletePemetaan" data-id_checklist_maintance="<?= $maintance->id_checklist_maintance; ?>">Hapus</button>
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
</div>

<div class="modal fade" tabindex="-1" id="edit_pemetaan">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-editPemetaan" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Pemetaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_checklist_maintance" id="id_checklist_maintance" class="form-control">

                    <div class="mb-5">
                        <label class="form-label fw-bold">Lokasi <span class="text-danger">*</span></label>
                        <select id="lokasiEdit2" class="form-select form-select-sm" data-control="select2"
                            data-dropdown-parent="#edit_pemetaan" name="lokasi" onchange="updateRuangan(this.value)">
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($tb_lokasi as $lokasi) : ?>
                                <option value="<?= $lokasi['id_lokasi']; ?>"><?= $lokasi['nama_lokasi']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Ruangan <span class="text-danger">*</span></label>
                        <select id="ruanganEdit2" class="form-select form-select-sm" data-control="select2"
                            data-dropdown-parent="#edit_pemetaan" name="ruangan">
                            <option value="">Pilih Ruangan</option>
                            </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Petugas <span class="text-danger">*</span></label>
                        <select id="petugasEdit2" class="form-select form-select-sm" data-control="select2"
                            data-dropdown-parent="#edit_pemetaan" name="petugas">
                            <option value="">Pilih Petugas</option>
                            <?php foreach ($tb_role as $role) : ?>
                                <option value="<?= $role['id_role']; ?>"><?= $role['role']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Periodik <span class="text-danger">*</span></label>
                        <select id="periodikEdit2" class="form-select form-select-sm" data-control="select2"
                            data-dropdown-parent="#edit_pemetaan" name="periodik">
                            <option value="">Pilih Periodik</option>
                            <?php foreach ($tb_periodik as $periodik) : ?>
                                <option value="<?= $periodik['id_periodik']; ?>"><?= $periodik['periodik']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" onclick="updateData()" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                <input type="hidden" name="id_checklist_maintance" id="id_delete" class="form-control">
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

    // Class definition
    var KTDatatablesButtons = function() {
        var table;
        var datatable;

        var initDatatable = function() {
            const tableRows = table.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                const dateRow = row.querySelectorAll('td');
                const realDate = moment(dateRow[3].innerHTML, "DD MMM YYYY, LT").format();
                dateRow[3].setAttribute('data-order', realDate);
            });

            // javascript cari/search data
            datatable = $(table).DataTable({
                "info": false,
                'order': [],
                'responsive': true,
                'pageLength': 10,
                "language": {
                    "zeroRecords": "Data yang dicari tidak ditemukan",
                    "info": "_START_ sampai _END_ dari _TOTAL_ entri",
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "loadingRecords": "Sedang memuat...",
                    "processing": "Sedang memproses..."
                }
            });
        }


        // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
        var handleSearchDatatable = () => {
            const filterSearch = document.querySelector('[data-kt-filter="search"]');
            filterSearch.addEventListener('keyup', function(e) {
                datatable.search(e.target.value).draw();
            });
        }

        // Public methods
        return {
            init: function() {
                table = document.querySelector('#table_search');

                if (!table) {
                    return;
                }

                initDatatable();
                // exportButtons();
                handleSearchDatatable();
            }
        };
    }();

    // On document ready
    KTUtil.onDOMContentLoaded(function() {
        KTDatatablesButtons.init();
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



    // Edit Aktivitas
    $('#data-pemetaan').on('click', '.editPemetaan', function() {
        $('#edit_pemetaan').modal('show');

        var id = $(this).data('id_checklist_maintance');
        var id_lokasi = $(this).data('id_lokasi');
        var nama_lokasi = $(this).data('nama_lokasi');
        var id_ruangan = $(this).data('ruangan');
        var id_petugas = $(this).data('id_petugas');
        var petugas = $(this).data('petugas');
        var id_periodik = $(this).data('id_periodik');
        var periodik = $(this).data('periodik');

        $('#id_checklist_maintance').val(id);
        $('#lokasiEdit2').val(id_lokasi).trigger('change');
        $('#petugasEdit2').val(id_petugas).trigger('change');
        $('#periodikEdit2').val(id_periodik).trigger('change');

        $("#select2-lokasiEdit2-container").text(nama_lokasi);
        $("#select2-petugasEdit2-container").text(petugas);
        $("#select2-periodikEdit2-container").text(periodik);

        // Update ruangan dan set selected
        updateRuangan(id_lokasi, id_ruangan);
    });


    // Fungsi untuk mendapatkan ruangan berdasarkan lokasi
    function updateRuangan(id_lokasi, selectedRuangan = null) {
        fetch('<?= base_url('pemetaan/getRuanganByLokasi/'); ?>' + id_lokasi)
            .then(response => response.json())
            .then(data => {
                let ruanganSelect = document.getElementById('ruanganEdit2');
                ruanganSelect.innerHTML = '<option value="">Pilih Ruangan</option>'; // Hapus optgroup biar tidak ribet

                data.forEach(ruangan => {
                    let option = document.createElement('option');
                    option.value = ruangan.id_ruangan;
                    option.textContent = ruangan.ruangan;
                    ruanganSelect.appendChild(option);
                });

                // Setelah opsi selesai dimuat, baru set value dan trigger select2
                if (selectedRuangan) {
                    ruanganSelect.value = selectedRuangan;
                    if (typeof $ !== 'undefined' && $.fn.select2) {
                        $('#ruanganEdit2').val(selectedRuangan).trigger('change.select2');
                    }
                }
            });
    }

    // fungsi update data
    function updateData() {
        const form = $('#form-editPemetaan');
        const formData = form.serialize();
        const id = $('#id_checklist_maintance').val();

        // Bersihkan validasi sebelumnya
        form.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        form.find('.invalid-feedback').remove();

        $.ajax({
            type: 'POST',
            url: `/pemetaan/update/${id}`,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    setSelect2Validation('#lokasiEdit2', true);
                    setSelect2Validation('#ruanganEdit2', true);
                    setSelect2Validation('#petugasEdit2', true);
                    setSelect2Validation('#periodikEdit2', true);

                    alertMessage(response.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                if (res?.errors) {
                    const errors = res.errors;

                    if (errors.lokasi) {
                        setSelect2Validation('#lokasiEdit2', false, errors.lokasi);
                    }

                    if (errors.ruangan) {
                        setSelect2Validation('#ruanganEdit2', false, errors.ruangan);
                    }

                    if (errors.petugas) {
                        setSelect2Validation('#petugasEdit2', false, errors.petugas);
                    }

                    if (errors.periodik) {
                        setSelect2Validation('#periodikEdit2', false, errors.periodik);
                    }

                    // alertMessage('Periksa kembali input yang kosong.', 'error');
                }
            }
        });
    }


    function setSelect2Validation(selector, valid, message = '') {
        const $el = $(selector);
        const $container = $el.closest('.mb-5');

        $el.removeClass('is-invalid is-valid');
        $container.find('.invalid-feedback').remove();

        if (valid) {
            $el.addClass('is-valid');
        } else {
            $el.addClass('is-invalid');
            $el.after(`<div class="invalid-feedback">${message}</div>`);
        }

        // Select2 custom container untuk highlight border
        const $select2Container = $el.next('.select2-container');
        $select2Container.removeClass('select2-valid select2-invalid');

        if (valid) {
            $select2Container.addClass('select2-valid');
        } else {
            $select2Container.addClass('select2-invalid');
        }
    }


    $(document).ready(function() {
        $('#lokasiEdit2, #ruanganEdit2, #petugasEdit2, #periodikEdit2').on('change', function() {
            const $el = $(this);
            if ($el.val()) {
                setSelect2Validation(this, true);
            }
        });
    });



    // hapus data
    $('#data-pemetaan').on('click', '.deletePemetaan', function() {
        var id = $(this).data('id_checklist_maintance');
        $('#confirmation-modal').modal('show');
        $('#id_delete').val(id);
    });

    function deleteData() {
        var id = $('#id_delete').val();

        $.ajax({
            type: "POST",
            dataType: "html",
            url: `/pemetaan/delete/${id}`,
            data: {
                id
            },
            success: function(response) {
                $("#" + id).remove();
                $("#id_delete").val("");
                alertMessage('Berhasil menghapus data aktivitas!', 'success')

                setTimeout(function() {
                    window.location.reload();
                }, 1600);
            }
        });
        return false;
    };
</script>

<?= $this->endSection(); ?>