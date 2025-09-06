<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="title mb-5">
                <h4 class="fw-bold">Form Tambah Lokasi</h4>
            </div>

            <div class="card shadow-sm card-flush border-0">
                <!-- Tombol Header -->
                <div class="card-header d-flex justify-content-between align-items-center py-4">
                    <div class="card-title">
                        <a href="<?= base_url('lokasi'); ?>" class="btn btn-sm btn-primary">
                            Data Lokasi
                        </a>
                    </div>
                </div>

                <!-- Body Form -->
                <div class="card-body">
                    <form action="<?= base_url('/lokasi/post') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Nama Lokasi</label>
                            <input type="text" name="nama_lokasi" class="form-control" placeholder="Contoh: Gedung 1" required>
                        </div>

                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress d-none">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>



<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.ckeditor-classic').forEach(function(element) {
            ClassicEditor
                .create(element)
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script> -->


<?= $this->endSection(); ?>