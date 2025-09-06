<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="title mb-5">
        <h4><a href="<?php echo base_url('/pemetaan'); ?>" class="text-dark">Pemetaan Checklist Maintenance</a></h4>
    </div>

    <div class="card card-flush">
        <div class="card-body">
            <!-- Lokasi dan Petugas -->
            <div class="mb-7">
                <div class="mb-3">
                    <table style="border-collapse: collapse; width: 35%;">
                        <?php if (!empty($tb_checklist_maintance)): ?>
                            <?php foreach ($tb_checklist_maintance as $item): ?>
                                <tr>
                                    <td style="padding-right: 5px;"><strong>Lokasi</strong></td>
                                    <td style="padding-right: 5px;">:</td>
                                    <td class="nama_lokasi"><?= esc($item->nama_lokasi); ?></td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 5px;"><strong>Petugas</strong></td>
                                    <td style="padding-right: 5px;">:</td>
                                    <td class="role"><?= esc($item->role); ?></td>
                                </tr>
                                <?php break; // tampilkan hanya satu kali 
                                ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">Data tidak tersedia</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>

                <!-- Tombol Cari, Cetak, Hapus -->
                <div class="d-flex justify-content-end gap-2 mt-10">
                    <div class="d-flex align-items-center position-relative my-1">
                        <input type="text" data-kt-filter="search" class="form-control form-control-sm form-control-solid w-130px ps-10 pe-30" placeholder="Cari..." />
                        <span class="svg-icon svg-icon-1 position-absolute end-0 top-50 translate-middle-y me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="btnExportReport">Cetak PDF</button>
                    <button type="button" class="btn btn-sm btn-danger delete-Pemetaan"
                        data-id_checklist="<?= $first_maintance->id_checklist_maintance ?? '' ?>"
                        data-id_ruangan="<?= $first_maintance->id_ruangan ?? '' ?>">Hapus Pemetaan</button>
                </div>
            </div>

            <hr class="my-5" />

            <div class="table-responsive">
                <table class="table table-row-bordered table-rounded border gy-5 gs-5" id="table_search">
                    <thead class="bg-light">
                        <tr class="fw-bold text-center">
                            <th>No</th>
                            <th>Ruangan</th>
                            <th>Aktivitas</th>
                            <th width="10%">Periodik</th>
                            <th>Waktu</th>
                            <th width="5%">Standar</th>
                            <th width="5%" class="no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="detail-pemetaan">
                        <?php foreach ($tb_checklist_maintance as $maintance): ?>
                            <tr>
                                <td class="text-center"><?= $maintance->id_checklist_maintance; ?></td>
                                <td><?= esc($maintance->ruangan); ?></td>
                                <td><?= $maintance->aktivitas; ?></td>
                                <td><?= esc($maintance->periodik); ?></td>
                                <td>
                                    <?php
                                    $id = $maintance->id_checklist_maintance;
                                    $waktuList = $list_waktu[$id] ?? [];

                                    if (!empty($waktuList)) {
                                        $output = [];
                                        foreach ($waktuList as $item) {
                                            $output[] = $maintance->id_periodik == 1 ? date('H:i', strtotime($item)) : $item;
                                        }
                                        echo esc(implode(', ', $output));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?= esc($maintance->standar); ?></td>
                                <td class="no-print">
                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-sm btn-warning edit-detailPemetaan"
                                            data-bs-toggle="modal" data-bs-target="#edit_aktivitas"
                                            data-id_checklist_maintance="<?= $maintance->id_checklist_maintance; ?>"
                                            data-ruangan="<?= esc($maintance->ruangan); ?>"
                                            data-id_ruangan="<?= $maintance->id_ruangan; ?>"
                                            data-aktivitas="<?= esc($maintance->aktivitas); ?>"
                                            data-id_periodik="<?= $maintance->id_periodik; ?>"
                                            data-periodik="<?= esc($maintance->periodik); ?>"
                                            data-list_waktu='<?= json_encode($list_waktu[$maintance->id_checklist_maintance] ?? []); ?>'
                                            data-standar="<?= esc($maintance->standar); ?>">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-Aktivitas"
                                            data-id_checklist_maintance="<?= $maintance->id_checklist_maintance; ?>">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tombol Tambah Aktivitas -->
    <div class="text-center mt-5">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_aktivitas">
            <i class="ki-outline ki-plus fs-3 me-1"></i>Tambah Aktivitas
        </button>
    </div>

    <!-- MODAL TAMBAH AKTIVITAS -->
    <div class="modal fade" tabindex="-1" id="tambah_aktivitas">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-tambahAktivitas" method="post">
                    <?= csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Aktivitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_checklist_maintance" id="id_checklist_maintance" value="<?= esc($first_maintance->id_checklist_maintance ?? ''); ?>">
                        <input type="hidden" name="id_ruangan" id="id_ruangan_hidden" value="<?= esc($first_maintance->id_ruangan ?? ''); ?>">
                        <input type="hidden" id="nama_lokasi_hidden" value="<?= esc($first_maintance->nama_lokasi ?? ''); ?>">

                        <!-- Ruangan -->
                        <div class="mb-5">
                            <label class="form-label fw-bold">Ruangan</label>
                            <select class="form-select form-select-sm" data-control="select2" data-dropdown-parent="#tambah_aktivitas" id="ruangan" name="ruangan">
                                <option value=""></option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Aktivitas -->
                        <div class="mb-5">
                            <label class="form-label">Aktivitas</label>
                            <textarea class="form-control form-control-sm" name="aktivitas" rows="5" id="aktivitas" placeholder="Masukkan Aktivitas"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Periodik -->
                        <div class="mb-5">
                            <label class="form-label fw-bold">Periodik</label>
                            <select class="form-select form-select-sm" name="periodik" id="periodikTambah" data-control="select2" data-dropdown-parent="#tambah_aktivitas">
                                <option value="">Pilih Periodik</option>
                                <?php foreach ($tb_periodik as $periodik): ?>
                                    <option value="<?= $periodik['id_periodik']; ?>"><?= esc($periodik['periodik']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Waktu -->
                        <div class="mb-5">
                            <div data-repeater-list="list_waktu">
                                <div data-repeater-item>
                                    <label class="form-label">Waktu / Hari <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm waktu-wrapper">
                                        <input type="text" name="waktu" class="form-control form-control-sm waktu" placeholder="Pilih Waktu">
                                        <button class="btn btn-sm btn-light" data-repeater-create type="button">
                                            <span class="svg-icon svg-icon-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 28 28">
                                                    <path d="M5 12h14M12 5v14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </button>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <!-- Input hari -->
                                    <div class="input-group input-group-sm hari-wrapper">
                                        <select class="form-select form-select-sm hari-select" name="hari" style="flex-grow: 1; width: auto;" data-control="select2" data-dropdown-parent="#tambah_aktivitas">
                                            <option value="">Pilih Hari</option>
                                            <option>Senin</option>
                                            <option>Selasa</option>
                                            <option>Rabu</option>
                                            <option>Kamis</option>
                                            <option>Jumat</option>
                                            <option>Sabtu</option>
                                            <option>Minggu</option>
                                        </select>
                                        <button class="btn btn-sm btn-light" data-repeater-create type="button">+</button>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <button class="btn btn-sm btn-danger mt-3" data-repeater-delete type="button">
                                        <i class="la la-trash-o fs-3"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Standar -->
                        <div class="mb-5">
                            <label class="form-label">Standar</label>
                            <input type="text" class="form-control form-control-sm" name="standar" placeholder="Masukkan Standar">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" onclick="addData()" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT AKTIVITAS -->
    <div class="modal fade" tabindex="-1" id="edit_aktivitas">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-editAktivitas" method="post">
                        <?= csrf_field(); ?>
                        <!-- Ruangan -->
                        <div class="mb-5">
                            <input type="hidden" name="id_checklist_maintance" id="id_checklist_maintance">
                            <input type="hidden" name="id_ruangan" id="id_ruangan">
                            <input type="hidden" id="nama_lokasi" value="<?= esc($first_maintance->nama_lokasi ?? ''); ?>">
                            <label class="form-label fw-bold">Ruangan</label>
                            <select id="ruanganEdit" class="form-select form-select-sm" data-control="select2" data-dropdown-parent="#edit_aktivitas" name="ruangan" data-selected="<?= $maintance->id_ruangan; ?>">
                                <option value=""></option>
                            </select>
                        </div>

                        <!-- Aktivitas -->
                        <div class="mb-5">
                            <label class="form-label">Aktivitas</label>
                            <textarea name="aktivitas" id="aktivitasEdit" class="form-control form-control-sm" rows="5"></textarea>
                        </div>

                        <!-- Periodik -->
                        <div class="mb-5">
                            <label class="form-label fw-bold">Periodik</label>
                            <select id="periodikEdit" class="form-select form-select-sm" name="periodik" data-control="select2" data-dropdown-parent="#edit_aktivitas">
                                <optgroup label="Pilih Periodik">
                                    <?php foreach ($tb_periodik as $periodik): ?>
                                        <option value="<?= $periodik['id_periodik']; ?>"><?= esc($periodik['periodik']); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Waktu -->
                        <div class="mb-5">
                            <label class="form-label fw-bold">Waktu / Hari</label>
                            <div data-repeater-list="list_waktu" class="mb-5" id="repeater-edit-waktu">
                            </div>
                            <div class="text-end mt-2">
                                <button type="button" class="btn btn-sm btn-light mt-2" id="btn-add-edit-waktu">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Standar -->
                        <div class="mb-5">
                            <label class="form-label">Standar</label>
                            <input type="text" class="form-control form-control-sm" name="standar" id="standarEdit" placeholder="Masukkan Standar">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" onclick="updateData()" class="btn btn-sm btn-primary">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Hapus Aktivitas/Konfirmasi (Hapus aktivitas di tabel) -->
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
                    <h6>Apakah Anda yakin akan menghapus data form pemetaan ini?</h6>
                    <p class="text-sm">Data yang dihapus tidak dapat dikembalikan.</p>
                    <input type="hidden" name="id_checklist_maintance" id="id_delete" class="form-control">
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-dark" onclick="deleteAktivitas()">Ya</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Hapus Pemetaan/Konfirmasi 2 (Hapus Pemetaan) -->
    <div class="modal fade" id="confirmation-modal2" tabindex="-1" aria-labelledby="confirmation-modal-label" aria-hidden="true">
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
                    <h6>Apakah Anda yakin akan menghapus seluruh data pemetaan ini?</h6>
                    <p class="text-sm">Data yang dihapus tidak dapat dikembalikan.</p>
                    <input type="hidden" name="id_checklist" id="id_delete" class="form-control">
                    <input type="hidden" id="id_ruangan_delete" name="id_ruangan">
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-dark" onclick="deletePemetaan()">Ya</button>
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

            document.getElementById('btnExportReport').addEventListener('click', function() {
                const originalContent = document.body.innerHTML;

                // Ambil konten yang ingin dicetak
                const printContent = `
            <div>
                <h3 class="text-center">Laporan Checklist Maintenance</h3>
                <table style="margin-bottom: 20px; margin-top: 15px; border-collapse: collapse; width: 35%;">
                    <tr>
                        <td style="padding-right: 5px;"><strong>Lokasi</strong></td>
                        <td style="padding-right: 5px;">:</td>
                        <td>${document.querySelector('.nama_lokasi').innerText}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 5px;"><strong>Petugas</strong></td>
                        <td style="padding-right: 5px;">:</td>
                        <td>${document.querySelector('.role').innerText}</td>
                    </tr>
                </table>

                <div class="dropdown-divider"></div>
                ${document.getElementById('table_search').outerHTML}
            </div>
        `;

                // Ganti isi body sementara dan cetak
                document.body.innerHTML = printContent;
                window.print();

                // Kembalikan isi body seperti semula setelah print
                document.body.innerHTML = originalContent;
                window.location.reload(); // reload agar semua JS dan event aktif kembali
            });

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


        // fungsi untuk waktu ( tanggal dan jam)
        function initializeFlatpickr() {
            $("input[name*='[waktu]']").each(function() {
                if (!$(this).hasClass('flatpickr-initialized')) {
                    flatpickr(this, {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        time_24hr: true,
                    });
                    $(this).addClass('flatpickr-initialized');
                }
            });
        }


        $(document).ready(function() {
            initializeFlatpickr();

            $('select[data-control="select2"]').select2();


            // Handle jika tombol tambah repeater diklik
            $('[data-repeater-list]').each(function() {
                const repeaterList = $(this);

                repeaterList.closest('form').on('click', '[data-repeater-create]', function() {
                    setTimeout(function() {
                        repeaterList.find('[data-repeater-item]').each(function() {});
                        initializeFlatpickr();
                    }, 100);
                });
            });
        });


        $('#tambah_aktivitas').on('shown.bs.modal', function() {
            $(this).find('select[data-control="select2"]').each(function() {
                $(this).select2({
                    dropdownParent: $('#tambah_aktivitas'),
                    width: '100%'
                });
            });
        });



        // repeater untuk waktu di form tambah
        $('#tambah_aktivitas').repeater({
            initEmpty: false,
            isFirstItemUndeletable: true,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function() {
                $(this).slideDown();
                initializeFlatpickr();

                // Inisialisasi ulang semua Select2 di dalam item baru
                $(this).find('select[data-control="select2"]').select2({
                    dropdownParent: $(this)
                });
            },

            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
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


        function handlePeriodikChangeTambah() {
            $('#periodikTambah').on('change', function() {
                const id_periodik = $(this).val();

                $('[data-repeater-list="list_waktu"] [data-repeater-item]').each(function() {
                    const waktuWrapper = $(this).find('.waktu-wrapper');
                    const hariWrapper = $(this).find('.hari-wrapper');

                    if (id_periodik == 1) {
                        // Harian → Tampilkan input waktu, sembunyikan hari
                        waktuWrapper.show();
                        hariWrapper.hide();
                        $(this).find('.hari-select').val('');
                    } else if (id_periodik == 2) {
                        // Mingguan → Tampilkan input hari, sembunyikan waktu
                        hariWrapper.show();
                        waktuWrapper.hide();
                        $(this).find('.waktu').val('');
                    } else {
                        // Default: sembunyikan semua
                        waktuWrapper.hide();
                        hariWrapper.hide();
                        $(this).find('.waktu').val('');
                        $(this).find('.hari-select').val('');
                    }
                });
            });
        }


        // Ketika modal tambah aktivitas ditampilkan
        $('#tambah_aktivitas').on('show.bs.modal', function() {
            // Ambil nama_lokasi dari elemen yang menampilkan nama lokasi
            var nama_lokasi = encodeURIComponent($('.nama_lokasi').text().trim());
            updateRuanganTambahByNamaLokasi(nama_lokasi);

            handlePeriodikChangeTambah();
            $('#periodikTambah').trigger('change');
        });


        // Fungsi untuk update dropdown ruangan berdasarkan nama lokasi (di modal tambah)
        function updateRuanganTambahByNamaLokasi(nama_lokasi, selectedId = null) {
            fetch('<?= base_url('pemetaan/getRuanganByNamaLokasi/'); ?>' + encodeURIComponent(nama_lokasi))
                .then(response => response.json())
                .then(data => {
                    let ruanganSelect = document.getElementById('ruangan');
                    let ruanganOptions = '<optgroup label="Pilih Ruangan"><option value="">Pilih Ruangan</option>';
                    data.forEach(ruangan => {
                        let selected = (ruangan.id_ruangan == selectedId) ? 'selected' : '';
                        ruanganOptions += `<option value="${ruangan.id_ruangan}" ${selected}>${ruangan.ruangan}</option>`;
                    });
                    ruanganOptions += '</optgroup>';
                    ruanganSelect.innerHTML = ruanganOptions;

                    // Render ulang jika pakai Select2
                    if (typeof $ !== 'undefined' && $.fn.select2) {
                        $(ruanganSelect).trigger('change.select2');
                    }
                })
                .catch(error => console.error('Error fetching ruangan (modal tambah):', error));
        }


        // fungsi untuk menyimpan tambah aktivitas (modal tambah)
        function addData() {
            const form = $('#form-tambahAktivitas');
            const formData = form.serialize();

            form.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
            form.find('.invalid-feedback').remove();
            form.find('.select2-container').removeClass('select2-invalid select2-valid');

            const id = $('#id_checklist_maintance').val();
            const id_ruangan = $('#id_ruangan_hidden').val();

            $.ajax({
                type: "POST",
                url: `<?= base_url('pemetaan/storeAktivitas') ?>/${id}/${id_ruangan}`,
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        markAllInputsValid('#form-tambahAktivitas');
                        alertMessage(response.message, 'success');

                        const ruanganId = response.id_ruangan;
                        console.log('Data berhasil disimpan untuk ID Ruangan:', ruanganId);

                        $('#tambah_aktivitas').modal('hide');
                        setTimeout(() => location.reload(), 1500);
                    }
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    if (!res || !res.errors) {
                        alertMessage('Terjadi kesalahan tidak terduga.', 'error');
                        return;
                    }

                    const errors = res.errors;
                    Object.keys(errors).forEach(key => {
                        const msg = errors[key];
                        const nameFormatted = key.replace(/\.(\d+)/g, '[$1]').replace(/\.(\w+)/g, '[$1]');
                        const input = form.find(`[name="${nameFormatted}"]`);

                        if (input.length) {
                            if (input.is('select')) {
                                const container = input.next('.select2');
                                input.addClass('is-invalid');
                                container.addClass('select2-invalid');
                                if (input.parent().find('.invalid-feedback').length === 0) {
                                    input.parent().append(`<div class="invalid-feedback">${msg}</div>`);
                                }
                            } else {
                                input.addClass('is-invalid');
                                if (input.next('.invalid-feedback').length === 0) {
                                    input.after(`<div class="invalid-feedback">${msg}</div>`);
                                }
                            }
                        }
                    });

                    // alertMessage('Periksa kembali input yang salah.', 'error');
                }
            });
        }

        // Hijaukan field valid
        function markAllInputsValid(selector) {
            const form = $(selector);
            form.find('input, textarea, select').each(function() {
                const el = $(this);
                if (el.val()) {
                    el.removeClass('is-invalid').addClass('is-valid');
                }
            });

            form.find('select').each(function() {
                const el = $(this);
                const container = el.next('.select2');
                if (el.val()) {
                    container.removeClass('select2-invalid').addClass('select2-valid');
                }
            });
        }

        // Validasi realtime
        $(document).on('input change', '#form-tambahAktivitas input, #form-tambahAktivitas textarea', function() {
            const el = $(this);
            if (el.val()) {
                el.removeClass('is-invalid').addClass('is-valid');
                el.siblings('.invalid-feedback').remove();
            }
        });

        $(document).on('change', '#form-tambahAktivitas select', function() {
            const el = $(this);
            const container = el.next('.select2');
            if (el.val()) {
                el.removeClass('is-invalid').addClass('is-valid');
                container.removeClass('select2-invalid').addClass('select2-valid');
                el.siblings('.invalid-feedback').remove();
            }
        });



        // function initializeFlatpickrEdit() {
        //     $(".waktu").flatpickr({
        //         enableTime: true,
        //         // noCalendar: true,
        //         dateFormat: "Y-m-d H:i",
        //         time_24hr: true
        //     });
        // }

        // // Repeater untuk waktu pada form edit
        // function initEditRepeater() {
        //     $('#container-repeater-edit').repeater({
        //         initEmpty: false,
        //         isFirstItemUndeletable: false,

        //         show: function() {
        //             $(this).slideDown();
        //             initializeFlatpickrEdit(); // agar datepicker aktif
        //         },

        //         hide: function(deleteElement) {
        //             $(this).slideUp(deleteElement);
        //         }
        //     });
        // }


        // Edit Aktivitas
        //     $('#detail-pemetaan').on('click', '.edit-detailPemetaan', function() {
        //         $('#edit_aktivitas').modal('show');

        //         var id = $(this).data('id_checklist_maintance');
        //         var id_lokasi = $(this).data('id_lokasi');
        //         // var nama_lokasi = $(this).data('nama_lokasi');
        //         var id_ruangan = $(this).data('id_ruangan');
        //         var ruangan = $(this).data('ruangan');
        //         var aktivitas = $(this).data('aktivitas');
        //         var id_periodik = $(this).data('id_periodik');
        //         var periodik = $(this).data('periodik');
        //         var listWaktu = $(this).data('list_waktu');
        //         var standar = $(this).data('standar');

        //         $('#id_checklist_maintance').val(id);
        //         $('#id_ruangan').val(id_ruangan);
        //         $('#aktivitasEdit').val(aktivitas);
        //         $('#periodikEdit').val(id_periodik).trigger('change');
        //         $('#standarEdit').val(standar);


        //         // Repeater Waktu
        //         let repeaterContainer = $('[data-repeater-list="list_waktu"]');
        //         repeaterContainer.empty();

        //         if (Array.isArray(listWaktu)) {
        //             listWaktu.forEach(function(item) {
        //                 let waktuValue = item.waktu || '';
        //                 let hariValue = item.hari || '';
        //                 let idWaktu = item.id_waktu || '';

        //                 let repeaterItem = $(`
        //     <div data-repeater-item>
        //         <input type="hidden" name="list_waktu[][id_waktu]" value="${item.id_waktu ?? ''}">
        //         <label class="form-label">Waktu / Hari</label>

        //         <!-- Input waktu (periodik harian) -->
        //         <div class="input-group input-group-sm waktu-wrapper" style="display: ${id_periodik == 1 ? 'flex' : 'none'};">
        //             <input type="text" name="list_waktu[][waktu]" class="form-control form-control-sm waktu" value="${item.waktu ?? ''}" placeholder="Pilih Waktu">
        //             <button class="btn btn-sm btn-light" data-repeater-create type="button">+</button>
        //         </div>

        //         <!-- Select hari (periodik mingguan) -->
        //         <div class="input-group input-group-sm hari-wrapper mt-2" style="display: ${id_periodik == 2 ? 'flex' : 'none'}; align-items: center; gap: 4px;">
        //             <select class="form-select form-select-sm hari-select" name="list_waktu[][hari]">
        //                 <option value="">Pilih Hari</option>
        //                 ${['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'].map(hari => `
        //                     <option value="${hari}" ${item.hari === hari ? 'selected' : ''}>${hari}</option>
        //                 `).join('')}
        //             </select>
        //             <button class="btn btn-light btn-sm" data-repeater-create type="button">+</button>
        //         </div>

        //         <button class="btn btn-sm btn-danger mt-3" data-repeater-delete type="button">
        //             <i class="la la-trash-o fs-3"></i> Delete
        //         </button>
        //     </div>
        // `);

        //                 repeaterContainer.append(repeaterItem);
        //             });
        //             initializeFlatpickrEdit(); // Jika pakai datepicker
        //         }

        //         var nama_lokasi = $('#nama_lokasi').val();
        //         updateRuanganByNamaLokasi(nama_lokasi, id_ruangan);
        //     });

        // Fungsi untuk buat item repeater waktu
        function getWaktuItemEdit(id_periodik, item = {}, index = 0) {
            const waktu = item.waktu ?? '';
            const hari = item.hari ?? '';
            const id_waktu = item.id_waktu ?? '';

            return `
        <div data-repeater-item class="mb-3">
            <input type="hidden" name="list_waktu[${index}][id_waktu]" value="${id_waktu}">
            <div class="input-group input-group-sm waktu-wrapper" style="display: ${id_periodik == 1 ? 'flex' : 'none'};">
                <input type="text" name="list_waktu[${index}][waktu]" class="form-control waktu" placeholder="Pilih Waktu" value="${waktu}" readonly="readonly">
                <button class="btn btn-danger btn-sm" type="button" onclick="$(this).closest('[data-repeater-item]').remove();">
                    <i class="la la-trash-o fs-4"></i>
                </button>
            </div>

            <div class="input-group input-group-sm hari-wrapper" style="display: ${id_periodik == 2 ? 'flex' : 'none'};">
                <select class="form-select form-select-sm hari-select" name="list_waktu[${index}][hari]" data-control="select2" data-dropdown-parent="#edit_aktivitas">
                    <option value="">Pilih Hari</option>
                    ${['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'].map(h => `
                        <option value="${h}" ${hari === h ? 'selected' : ''}>${h}</option>
                    `).join('')}
                </select>
                <button class="btn btn-danger btn-sm" type="button" onclick="$(this).closest('[data-repeater-item]').remove();">
                    <i class="la la-trash-o fs-4"></i>
                </button>
            </div>
        </div>
    `;
        }


        function initializeSelect2EditHari() {
            $('#edit_aktivitas .hari-select').select2({
                dropdownParent: $('#edit_aktivitas'),
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        }


        // Tampilkan field waktu/hari saat edit
        function renderEditWaktuFields(id_periodik, listWaktu = []) {
            const container = $('#repeater-edit-waktu');
            container.empty();

            if (listWaktu.length === 0) {
                listWaktu.push({
                    waktu: '',
                    hari: '',
                    id_waktu: ''
                });
            }

            listWaktu.forEach((item, index) => {
                container.append(getWaktuItemEdit(id_periodik, item, index));
            });

            initializeFlatpickrEdit();
            initializeSelect2EditHari();
        }

        // Inisialisasi flatpickr di input waktu
        function initializeFlatpickrEdit() {
            $(".waktu").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
        }

        // Tambah item baru saat tombol tambah diklik
        $('#btn-add-edit-waktu').on('click', function() {
            const id_periodik = $('#periodikEdit').val();
            $('#repeater-edit-waktu').append(getWaktuItemEdit(id_periodik));
            initializeFlatpickrEdit();
            initializeSelect2EditHari();
        });

        // Toggle tampilan waktu/hari saat periodik diganti
        $('#periodikEdit').on('change', function() {
            const newPeriodik = $(this).val();
            $('#repeater-edit-waktu').children('[data-repeater-item]').each(function() {
                if (newPeriodik == '1') {
                    $(this).find('.waktu-wrapper').show();
                    $(this).find('.hari-wrapper').hide();
                } else if (newPeriodik == '2') {
                    $(this).find('.waktu-wrapper').hide();
                    $(this).find('.hari-wrapper').show();
                }
            });
        });

        // Event klik tombol edit (buka modal + isi data)
        $('#detail-pemetaan').on('click', '.edit-detailPemetaan', function() {
            $('#edit_aktivitas').modal('show');

            var id = $(this).data('id_checklist_maintance');
            var id_lokasi = $(this).data('id_lokasi');
            // var nama_lokasi = $(this).data('nama_lokasi');
            var id_ruangan = $(this).data('id_ruangan');
            var ruangan = $(this).data('ruangan');
            var aktivitas = $(this).data('aktivitas');
            var id_periodik = $(this).data('id_periodik');
            var periodik = $(this).data('periodik');
            var listWaktu = $(this).data('list_waktu');
            var standar = $(this).data('standar');

            $('#id_checklist_maintance').val(id);
            $('#id_ruangan').val(id_ruangan);
            $('#aktivitasEdit').val(aktivitas);
            $('#periodikEdit').val(id_periodik).trigger('change');
            $('#standarEdit').val(standar);

            renderEditWaktuFields(id_periodik, listWaktu);

            // Update ruangan
            var nama_lokasi = $('#nama_lokasi').val();
            updateRuanganByNamaLokasi(nama_lokasi, id_ruangan);
        });


        $('#edit_aktivitas').on('show.bs.modal', function() {
            var nama_lokasi = $('#nama_lokasi').val(); // Ambil dari hidden input
            var selectedRuangan = $('#ruanganEdit').data('selected'); // Ambil nilai ruangan terpilih
            updateRuanganByNamaLokasi(nama_lokasi, selectedRuangan);
        });


        // Fungsi untuk update dropdown ruangan berdasarkan nama lokasi (di modal edit)
        function updateRuanganByNamaLokasi(nama_lokasi, selectedId = null) {
            fetch('<?= base_url('pemetaan/getRuanganByNamaLokasi/'); ?>' + encodeURIComponent(nama_lokasi))
                .then(response => response.json())
                .then(data => {
                    let ruanganSelect = document.getElementById('ruanganEdit');
                    let ruanganOptions = '<optgroup label="Pilih Ruangan"><option value="">Pilih Ruangan</option>';
                    data.forEach(ruangan => {
                        let selected = (ruangan.id_ruangan == selectedId) ? 'selected' : '';
                        ruanganOptions += `<option value="${ruangan.id_ruangan}" ${selected}>${ruangan.ruangan}</option>`;
                    });
                    ruanganOptions += '</optgroup>';
                    ruanganSelect.innerHTML = ruanganOptions;

                    // Render ulang jika pakai Select2
                    if (typeof $ !== 'undefined' && $.fn.select2) {
                        $(ruanganSelect).trigger('change.select2');
                    }
                })
                .catch(error => console.error('Error fetching ruangan:', error));
        }



        function updateData() {
            var id = $('#id_checklist_maintance').val();
            var id_ruangan = $('#id_ruangan').val();
            // var validation = validateEdit();

            // if (validation !== 'sukses') {
            //     alertMessage(`Silahkan lengkapi data ${validation}`, 'error', false);
            //     return;
            // }

            // Sinkronisasi data dari CKEditor 5 ke textarea (aktivitasEdit)
            // if (typeof aktivitasEditorEdit !== 'undefined') {
            //     $('#aktivitasEdit').val(aktivitasEditorEdit.getData());
            // }

            var formData = $("#form-editAktivitas").serialize();

            $.ajax({
                type: "POST",
                url: `/pemetaan/updateAktivitas/${id}/${id_ruangan}`,
                data: formData,
                success: function(response) {
                    $("#form-editAktivitas")[0].reset();
                    $("#edit_aktivitas").modal('hide');
                    alertMessage('Berhasil memperbarui data aktivitas!', 'success', true);

                    setTimeout(function() {
                        window.location.reload();
                    }, 1600);
                },
                error: function(err) {
                    let errorMsg = "Terjadi kesalahan saat mengirim data.";
                    if (err.responseJSON && err.responseJSON.errors) {
                        console.log(err.responseJSON.errors);
                        errorMsg = Object.values(err.responseJSON.errors).join(', ');
                    }
                    alertMessage(errorMsg, 'error', true);
                }
            });
        }



        // hapus data keseluruhan
        $(document).on('click', '.delete-Pemetaan', function() {
            var id = $(this).data('id_checklist');
            var id_ruangan = $(this).data('id_ruangan');

            $('#confirmation-modal2').modal('show');
            $('#id_delete').val(id);
            $('#id_ruangan_delete').val(id_ruangan);

        });

        function deletePemetaan() {
            var id = $('#id_delete').val();
            var id_ruangan = $('#id_ruangan_delete').val();

            $.ajax({
                type: "POST",
                dataType: "html",
                url: `/pemetaan/deletePemetaan/${id}/${id_ruangan}`,
                data: {
                    id,
                    id_ruangan
                },
                success: function(response) {
                    $("#" + id).remove();
                    $("#" + id_ruangan).remove();
                    $("#id_delete").val("");
                    $("#id_ruangan_delete").val("");
                    alertMessage('Berhasil menghapus data aktivitas!', 'success')

                    setTimeout(function() {
                        window.location.href = '/pemetaan';
                    }, 1600);
                }
            });
            return false;
        };



        $('#detail-pemetaan').on('click', '.delete-Aktivitas', function() {
            var id = $(this).data('id_checklist_maintance');

            // Isi input hidden modal sesuai data atribut tombol
            $('#id_delete').val(id);

            $('#confirmation-modal').modal('show');
        });


        // Fungsi hapus data tabel di halaman detail
        function deleteAktivitas() {
            var id = $('#id_delete').val();

            $.ajax({
                type: "POST",
                url: `/pemetaan/deleteAktivitas/${id}`,
                data: {
                    id
                },
                success: function(response) {
                    $("#" + id).remove();
                    $('#id_delete').val("");
                    // Tampilkan pesan sukses
                    alertMessage('Berhasil menghapus data aktivitas!', 'success');

                    // Reload halaman / update data tabel sesuai kebutuhan
                    setTimeout(function() {
                        location.reload();
                    }, 1600);
                },
                error: function() {
                    alertMessage('Gagal menghapus data!', 'error');
                }
            });

            return false;
        }
    </script>
    <!-- </div> -->

    <?= $this->endSection(); ?>