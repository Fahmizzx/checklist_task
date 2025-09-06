<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="title mb-5">
                <h4>Rincian Tugas Checklist <?= esc($ruangan['role']) ?> - <?= esc($ruangan['ruangan']) ?> (<?= esc($ruangan['nama_lokasi']) ?>)</h4>
            </div>
            <div class="card shadow-sm card-flush border-0 mb-5">
                <div class="card-header align-items-center py-4 gap-2 gap-md-5 justify-content-between">
                    <!-- Tombol Create Pemetaan -->
                    <div class="card-title">
                        <a href="<?= base_url('/mechanical'); ?>" class="btn btn-sm btn-primary ms-4">
                            Kembali
                        </a>
                    </div>

                    <!-- Tombol Cari -->
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
                        <table class="table table-row-bordered table-rounded border gy-5 gs-5 mt-20" id="datatable">
                            <thead class="bg-light">
                                <tr class="fw-bold text-center">
                                    <th>Nama Petugas</th>
                                    <th>Aktivitas</th>
                                    <th>Standar</th>
                                    <th>Periodik</th>
                                    <th>Waktu Target</th>
                                    <th>Waktu Dikerjakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td><?= esc($task['nama']) ?></td>
                                        <td><?= esc($task['aktivitas']) ?></td>
                                        <td><?= esc($task['standar']) ?></td>
                                        <td><?= esc($task['periodik']) ?></td>
                                        <td>
                                            <?php
                                            $id = $task['id_checklist_maintance'];
                                            $waktuList = $list_waktu[$id] ?? [];

                                            if (!empty($waktuList)) {
                                                $output = [];
                                                foreach ($waktuList as $item) {
                                                    $output[] = $task['id_periodik'] == 1 ? date('H:i', strtotime($item)) : $item;
                                                }
                                                echo esc(implode(', ', $output));
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td><?= esc($task['created_at']) ?></td>
                                        <td>
                                            <?php if ($task['status'] == 'Selesai'): ?>
                                                <span class="badge badge-light-success fw-semibold"><?= esc($task['status']) ?></span>
                                            <?php elseif ($task['status'] == 'Belum'): ?>
                                                <span class="badge badge-light-danger fw-semibold"><?= esc($task['status']) ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-light-warning fw-semibold"><?= esc($task['status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                table = document.querySelector('#datatable');

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
</script>


<?= $this->endSection(); ?>