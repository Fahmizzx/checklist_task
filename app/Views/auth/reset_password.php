<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - PT INTI</title>
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f2f5; }
        .card { width: 400px; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .bgi-position-y-bottom {
            background-image: url(<?php echo base_url(); ?>assets/media/illustrations/sketchy-1/14.png);
            background-repeat: no-repeat;
            background-position-y: bottom;
            background-position-x: center;
            background-size: contain;
            background-attachment: fixed;
        }
    </style>
</head>
<body id="kt_body" class="bg-body">
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <a href="<?= base_url(); ?>" class="mb-12">
                    <img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/logo2.svg" class="h-40px" />
                </a>
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    <h2 class="text-center mb-4">Reset Password</h2>
                    <?php if (session()->getFlashdata('error_msg')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error_msg') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('auth/reset-password/update') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <!-- Struktur untuk toggle mata Metronic -->
                            <div class="position-relative" data-kt-password-meter="true">
                                <input type="password" class="form-control" id="password_input_reset" name="password" placeholder="Masukan Password Anda" required>
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                    data-kt-password-meter-control="visibility">
                                    <i class="bi bi-eye-slash fs-2"></i>
                                    <i class="bi bi-eye fs-2 d-none"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <div class="position-relative" data-kt-password-meter="true">
                                <input type="password" class="form-control" id="confirm_password_input_reset" name="confirm_password" placeholder="Masukan Password Anda kembali" required>
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                    data-kt-password-meter-control="visibility">
                                    <i class="bi bi-eye-slash fs-2"></i>
                                    <i class="bi bi-eye fs-2 d-none"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Javascript-->
    <script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
    <!--end::Javascript-->
</body>
</html>
