<?= $this->extend('layout/dashboard') ?>
<?= $this->section('content') ?>
<?php if (session()->getFlashdata('success')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success'); ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
<?php endif; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="title mb-5">
                <h4>Edit Data Ruangan</h4>
            </div>

            <div class="card shadow-sm card-flush border-0">
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/ruangan/update/' . $ruangan['id_ruangan']) ?>" method="post" class="w-75" id="formEditRuangan">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="ruangan">Nama Ruangan</label>
                            <input type="text" name="ruangan" id="ruangan"
                                class="form-control form-control-sm <?= isset($validation) && $validation->hasError('ruangan') ? 'is-invalid' : ''; ?>"
                                value="<?= old('ruangan', $ruangan['ruangan']) ?>">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('ruangan') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="id_lokasi">Lokasi</label>
                            <select name="id_lokasi" id="id_lokasi"
                                class="form-select form-select-sm <?= isset($validation) && $validation->hasError('id_lokasi') ? 'is-invalid' : ''; ?>"
                                data-control="select2">
                                <option value="">Pilih Lokasi</option>
                                <?php foreach ($lokasi as $l): ?>
                                    <option value="<?= $l['id_lokasi'] ?>"
                                        <?= old('id_lokasi', $ruangan['id_lokasi']) == $l['id_lokasi'] ? 'selected' : '' ?>>
                                        <?= esc($l['nama_lokasi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('id_lokasi') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role">Role Petugas</label>
                            <select name="role" id="role"
                                class="form-select form-select-sm <?= isset($validation) && $validation->hasError('role') ? 'is-invalid' : ''; ?>"
                                data-control="select2">
                                <option value="">Pilih Petugas</option>
                                <?php foreach ($tb_role as $role): ?>
                                    <option value="<?= $role['id_role'] ?>"
                                        <?= old('role', $ruangan['id_role']) == $role['id_role'] ? 'selected' : '' ?>>
                                        <?= esc($role['role']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('role') : '' ?>
                            </div>
                        </div>

                        <a href="<?= base_url('/ruangan') ?>" class="btn btn-sm btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fields = ['#ruangan', '#id_lokasi', '#role'];

        fields.forEach(function(selector) {
            const element = document.querySelector(selector);
            if (!element) return;

            element.addEventListener('change', function() {
                if (element.classList.contains('is-invalid')) {
                    element.classList.remove('is-invalid');
                    const feedback = element.parentElement.querySelector('.invalid-feedback');
                    if (feedback) feedback.textContent = '';
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>