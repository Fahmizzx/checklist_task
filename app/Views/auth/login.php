<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../">
    <title>Masuk - General Affairs Information System</title>
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
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(<?php echo base_url(); ?>assets/media/illustrations/sketchy-1/14.png)">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <!--begin::Logo-->
                <a href="<?= base_url(); ?>" class="mb-12">
                    <img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/logo2.svg" class="h-40px" />
                </a>
                <!--end::Logo-->
                <!--begin::Wrapper-->
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    <!--begin::Form-->
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="<?= base_url('auth/loginprocess') ?>" method="post">
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">Masuk ke Umprop</h1>
                            <div class="text-gray-400 fw-bold fs-4">Baru?
                                <a href="<?= base_url('auth/register') ?>" class="link-primary fw-bolder">Daftar di Sini</a>
                            </div>
                        </div>
                        <!--end::Heading-->

                        <!--begin::Flashdata Message-->
                        <?php if (session()->getFlashdata('success_msg')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success_msg') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error_msg')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error_msg') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <!--end::Flashdata Message-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-bolder text-dark">Username</label>
                            <input class="form-control form-control-lg" type="text" name="username" autocomplete="off" placeholder="Username" value="<?= old('username') ?>">
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                            <input class="form-control form-control-lg" type="password" id="password_input_login" name="password" autocomplete="off" placeholder="Password" />
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_password_login">
                                    <label class="form-check-label text-muted" for="show_password_login">
                                        Tampilkan password
                                    </label>
                                </div>
                                <a href="<?= base_url('auth/forgot-password') ?>" class="link-primary fs-6 fw-bolder">Lupa Password ?</a>
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Actions-->
                        <div class="text-center">
                            <button type="submit" id="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                <span class="indicator-label">Sign In</span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Main-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="<?php echo base_url(); ?>assets/js/custom/authentication/sign-in/general.js"></script>
    <script>
        // Custom JavaScript for show/hide password checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password_input_login');
            const showPasswordCheckbox = document.getElementById('show_password_login');

            if (passwordInput && showPasswordCheckbox) {
                showPasswordCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        passwordInput.type = 'text';
                    } else {
                        passwordInput.type = 'password';
                    }
                });
            }
        });
    </script>
    <!--end::Javascript-->
</body>
<!--end::Body-->
</html>
