<?= $this->extend('layout/dashboard') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-xl-12 col-lg-12 col-md-12">
    <div class="title mb-5">
        <h4>Edit Data Lokasi</h4>
    </div>

    <div class="card shadow-sm card-flush border-0">
    <div class="card-body">
        <form action="<?= base_url('/lokasi/update/' . $lokasi['id_lokasi']) ?>" method="post">
            <div class="mb-3">
                <label for="lokasi">Nama Lokasi</label>
                <input type="text" name="nama_lokasi" class="form-control" value="<?= $lokasi['nama_lokasi'] ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?= base_url('/lokasi') ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    </div>
</div>
</div>
</div>


<!-- <?php if (isset($success) && $success === true) : ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data berhasil diubah',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = "<?= base_url('/lokasi') ?>";
        });
    });
</script>
<?php endif; ?> -->
<?= $this->endSection() ?>