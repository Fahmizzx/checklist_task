<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="<?= base_url() ?>">
    <meta charset="utf-8" />
    <title>Checklist Selesai</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Include CSS Metronic -->
    <link href="<?= base_url('assets/plugins/global/plugins.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/style.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="<?= base_url('assets/vendors/images/inti.svg') ?>" />
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
                    <div class="container-fluid">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center py-20">
                                <div class="mb-5">
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                </div>
                                <h2 class="text-gray-800 mb-3">Terima Kasih</h2>
                                <p class="text-muted">
                                    Anda telah menyelesaikan tugas checklist harian.<br>
                                    Tetap semangat dan jaga kualitas kebersihan serta keamanan ruangan.
                                </p>
                                <a href="<?= base_url('mechanical/scan') ?>" class="btn btn-sm btn-primary mt-4">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Pemindaian
                                </a>
                            </div>
                        </div>
                    </div>
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
</body>
<!--end::Body-->

</html>