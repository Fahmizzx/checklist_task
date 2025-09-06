<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="card shadow-sm">
        <div class="pd-ltr-20">
            <div class="card-box pd-20 height-100-p mb-30">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <img src="assets/media/illustrations/dozzy-1/4.png" alt="" style="width: 350px; height: 350px;">
                    </div>
                    <div class="col-md-8">
                        <h4 class="font-20 weight-500 mb-10 text-capitalize">
                            Selamat Datang <div class="weight-600 font-50" style="color:rgb(9, 72, 166);"><?= session()->get('nama') ?>!</div>
                        </h4>
                        <p class="font-18 max-width-600">Sistem ini digunakan untuk memantau kinerja karyawan melalui scan QR code
                            sebagai bagian dari proses checklist maintenance.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->endSection(); ?>