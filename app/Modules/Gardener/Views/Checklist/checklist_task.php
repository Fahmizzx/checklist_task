<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="<?= base_url() ?>">
    <meta charset="utf-8" />
    <title>Checklist Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Include CSS Metronic -->
    <link href="<?= base_url('assets/plugins/global/plugins.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/style.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/vendors/images/inti.svg" />
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="app-blank">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid">
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid p-10">
                    <!--begin::Main Content-->
                    <div class="container-fluid">
                        <div class="card card-flush">
                            <!-- Bagian Atas: Tombol dan Search -->
                            <div class="card-header align-items-center justify-content-between py-4">
                                <div class="d-flex gap-3 flex-wrap">
                                    <a href="#" class="btn btn-sm btn-primary">Kembali</a>
                                    <div class="d-flex align-items-center position-relative">
                                        <input type="text" data-kt-filter="search" class="form-control form-control-sm form-control-solid w-100px pe-10" placeholder="Cari..." />
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                            <i class="fas fa-search text-muted fs-6"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Umum Checklist -->
                            <div class="card-body pt-0">
                                <div class="mb-5">
                                    <div class="card shadow-sm">
                                        <div class="card-body px-4 py-3">
                                            <h4 class="fw-bold text-dark mb-3">Checklist Harian Zona Outdoor PT. INTI (Persero)</h4>
                                            <p class="text-gray-700 mb-2">
                                                <strong>Peraturan Checklist Harian Ruangan:</strong><br>
                                                1. Petugas Checklist adalah Gardener. Pengisian dengan cara dichecklist satu per satu sesuai dengan pertanyaan secara berurutan untuk semua Ruangan Kerja.<br>
                                                2. Checklist dilakukan setiap hari pada:<br>
                                                3. Apabila ada gangguan/masalah atau ada barang yang tidak tersedia maka wajib segera dilaporkan kepada GA Officer.
                                            </p>
                                            <p class="text-dark fw-bold mt-3 mb-0">HRGA Department</p>
                                        </div>
                                    </div>
                                </div>


                                <!-- Data Tidak Ditemukan -->
                                <div id="noDataMessage" class="alert text-center d-none bg-light border-0">
                                    <strong>Data tidak ditemukan.</strong>
                                </div>

                                <div class="card mb-5 shadow-sm">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <strong>Nama Ruangan:</strong> <?= esc($ruangan) ?>
                                        </div>
                                        <div>
                                            <strong>Role Petugas:</strong> Gardener
                                        </div>
                                    </div>
                                </div>


                                <!-- Form Checklist -->
                                <form>
                                    <?php $no = 1; ?>
                                    <?php foreach ($checklists as $task): ?>
                                        <?php
                                        $cleanText = strip_tags($task['aktivitas']);
                                        $id_checklist = $task['id_checklist_maintance'] ?? 0;
                                        ?>

                                        <div class="card mb-4 shadow-sm searchable-card" data-status="Belum">
                                            <div class="card-body">
                                                <div class="mb-2 fw-semibold">
                                                    <?= $no++ ?>. <span class="task-text"><?= esc($cleanText) ?></span> <span class="text-danger">*</span>
                                                </div>
                                                <div class="mt-4 text-end">
                                                    <button type="button" class="btn btn-secondary btn-sm toggleButton kosong"
                                                        data-id-checklist="<?= $id_checklist; ?>">
                                                        <i class="fas fa-check me-1"></i><span class="btn-text-sm">Selesai</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!--end::Main Content-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->

    <!-- JS Metronic -->
    <script src="<?= base_url('assets/plugins/global/plugins.bundle.js') ?>"></script>
    <script src="<?= base_url('assets/js/scripts.bundle.js') ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JS Custom -->
    <script>
        $(document).ready(function() {
            // Search
            $('[data-kt-filter="search"]').on('keyup', function() {
                var keyword = $(this).val().toLowerCase();
                var found = false;

                $('.searchable-card').each(function() {
                    var text = $(this).text().toLowerCase();
                    var match = text.indexOf(keyword) > -1;
                    $(this).toggle(match);
                    if (match) found = true;
                });

                if (!found) {
                    $('#noDataMessage').removeClass('d-none');
                } else {
                    $('#noDataMessage').addClass('d-none');
                }
            });

            // Fungsi pesan / alert (SweetAlert2)
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
                });
            }

            // ✅ Fungsi cek apakah semua checklist sudah selesai
            function checkIfAllCompleted() {
                let allDone = true;

                $('.searchable-card').each(function() {
                    const status = $(this).attr('data-status');
                    if (status !== 'Selesai') {
                        allDone = false;
                        return false;
                    }
                });

                if (allDone) {
                    setTimeout(function() {
                        window.location.href = "<?= base_url('cs/checklist_selesai') ?>";
                    }, 1500);
                }
            }

            // Toggle checklist
            $('.toggleButton').click(function() {
                const button = $(this);
                const card = button.closest('.card');
                const icon = button.find('i');
                const textSpan = button.find('.btn-text-sm');
                const id_checklist = button.data('id-checklist');
                const isKosong = button.hasClass('kosong');
                const status = isKosong ? 'Selesai' : 'Batal';

                const formData = new FormData();
                formData.append('id_checklist_maintance', id_checklist);
                formData.append('status', status);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                $.ajax({
                    url: '<?= base_url('cs/saveChecklist/' . $ruangan['uuid_ruangan']) ?>',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status === 'success') {
                            button.removeClass('btn-secondary btn-danger');

                            if (isKosong) {
                                button.removeClass('kosong').addClass('silang btn-danger');
                                icon.removeClass('fa-check').addClass('fa-times');
                                textSpan.text(' Batal');
                                card.appendTo(card.parent());
                                card.attr('data-status', 'Selesai');

                                alertMessage('Checklist berhasil disimpan.');
                            } else {
                                button.removeClass('silang').addClass('kosong btn-secondary');
                                icon.removeClass('fa-times').addClass('fa-check');
                                textSpan.text(' Selesai');
                                card.prependTo(card.parent());
                                card.attr('data-status', 'Belum');

                                alertMessage('Checklist dibatalkan.');
                            }

                            // ✅ Cek jika semua checklist sudah selesai
                            checkIfAllCompleted();
                        } else {
                            alertMessage(res.message || 'Gagal memproses.');
                        }
                    },
                    error: function() {
                        alertMessage('Terjadi kesalahan saat mengirim data.');
                    }
                });
            });
        });
    </script>
</body>
<!--end::Body-->

</html>