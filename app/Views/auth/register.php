<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="">
    <title>Buat Akun - General Affairs Information System</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/vendors/images/inti.svg" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="bg-body">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Sign-up -->
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(<?= base_url('assets/media/illustrations/sketchy-1/14.png') ?>)">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <!--begin::Logo-->
                <a href="<?= base_url() ?>" class="mb-12">
                    <img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/logo2.svg" class="h-40px" />
                </a>
                <!--end::Logo-->
                <!--begin::Wrapper-->
                <div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" action="<?= base_url('auth/registerprocess') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-10 text-center">
                            <h1 class="text-dark mb-3">Buat Akun</h1>
                            <div class="text-gray-400 fw-bold fs-4">
                                Sudah punya akun?
                                <a href="<?= base_url('auth/login') ?>" class="link-primary fw-bolder">Masuk Disini</a>
                            </div>
                        </div>

                        <?php if (isset($validation) && $validation->getErrors()): ?>
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0286 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0286 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8188 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
                                        <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80523 10.0065 8.51414 10.3018C8.22305 10.5971 8.22305 11.0737 8.51414 11.3689L10.4699 13.3442C10.6102 13.4875 10.79 13.559 10.9699 13.559C11.1498 13.559 11.33 13.4875 11.4699 13.3442L15.4699 9.29743C15.761 9.00215 15.761 8.5256 15.4699 8.23031C15.1788 7.93503 14.7023 7.93503 14.4112 8.23031L11.0246 11.6562L10.5606 11.3042Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger">Gagal Mendaftar!</h4>
                                    <span><?= $validation->listErrors() ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="fv-row mb-7">
                            <label class="form-label fw-bolder text-dark fs-6">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="nama" placeholder="Masukkan nama lengkap" value="<?= old('nama') ?>" required />
                        </div>

                        <div class="fv-row mb-7">
                            <label class="form-label fw-bolder text-dark fs-6">Username</label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="username" placeholder="Masukkan username" value="<?= old('username') ?>" required />
                        </div>

                        <div class="fv-row mb-7">
                            <label class="form-label fw-bolder text-dark fs-6">Email</label>
                            <input type="email" class="form-control form-control-lg form-control-solid" name="email" placeholder="Masukkan alamat email" value="<?= old('email') ?>" required />
                        </div>

                        <!-- Password Input -->
                        <div class="mb-10 fv-row" data-kt-password-meter="true">
                            <div class="mb-1">
                                <label class="form-label fw-bolder text-dark fs-6">Password</label>
                                <div class="position-relative mb-3">
                                    <input class="form-control form-control-lg form-control-solid" type="password" placeholder="Password" name="password" autocomplete="off" />
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                        <i class="bi bi-eye-slash fs-2"></i>
                                        <i class="bi bi-eye fs-2 d-none"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Konfirmasi Password Input -->
                        <div class="fv-row mb-7" data-kt-password-meter="true">
                            <label class="form-label fw-bolder text-dark fs-6">Konfirmasi Password</label>
                            <div class="position-relative mb-3">
                                <input class="form-control form-control-lg form-control-solid" type="password" placeholder="Konfirmasi password Anda" name="confirm_password" autocomplete="off" />
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                    <i class="bi bi-eye-slash fs-2"></i>
                                    <i class="bi bi-eye fs-2 d-none"></i>
                                </span>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" id="kt_sign_up_submit" class="btn btn-lg btn-primary">
                                <span class="indicator-label">Daftar</span>
                                <span class="indicator-progress">Mohon tunggu...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
    <?php echo base_url(); ?>
</body>
</html>
