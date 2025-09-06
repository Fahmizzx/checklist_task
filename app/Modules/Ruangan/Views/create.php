<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="title mb-5">
                <h4 class="fw-bold">Form Tambah Ruangan</h4>
            </div>

            <div class="card shadow-sm card-flush border-0">
                <!-- Tombol Header -->
                <div class="card-header d-flex justify-content-between align-items-center py-4">
                    <div class="card-title">
                        <a href="<?= base_url('ruangan'); ?>" class="btn btn-sm btn-primary">
                            Data Ruangan
                        </a>
                    </div>
                </div>

                <!-- Body Form -->
                <div class="card-body">
                    <form action="<?= base_url('/ruangan/post') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Nama Ruangan</label>
                            <input type="text" name="ruangan" class="form-control" placeholder="Contoh: Ruang Rapat 1" required>
                        </div>

                        <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <select name="id_lokasi" class="form-select" required>
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($lokasi as $l): ?>
                                <option value="<?= $l['id_lokasi'] ?>"><?= esc($l['nama_lokasi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                      <div class="mb-3">
                            <label class="form-label">Role Akses</label>
                            <input type="hidden" name="id_role" value="<?= session('id_role') ?>">
                            <input type="text" class="form-control" value="<?= session('role') ?>" readonly>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">QR Code (Optional)</label>
                            <input type="text" name="qrcode" class="form-control" placeholder="Isi jika perlu">
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

<!-- CDN SweetAlert2 -->

<!-- 
<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '<?= session()->getFlashdata('success') ?>',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = "<?= base_url('/ruangan') ?>";
        });
    });
</script>


<?= $this->endSection(); ?>