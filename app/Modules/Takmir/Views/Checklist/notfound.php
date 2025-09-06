<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>
<div class="container my-4">

    <div class="card border-warning shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-3">
                <i class="fas fa-exclamation-circle fa-3x text-warning"></i>
            </div>

            <h4 class="text-dark fw-bold mb-2">Data Checklist Tidak Tersedia</h4>

            <?php if (isset($uuid_ruangan)): ?>
                <p class="text-muted">
                    Tidak ditemukan data checklist untuk ruangan dengan UUID:<br>
                    <code><?= esc($uuid_ruangan) ?></code>
                </p>
            <?php endif; ?>

            <p class="text-muted">
                Kemungkinan penyebab:
            <ul class="list-unstyled mt-2 text-start d-inline-block">
                <li>• Checklist belum dipetakan ke ruangan ini</li>
                <li>• Petugas belum melakukan checklist</li>
                <li>• Atau UUID tidak sesuai</li>
            </ul>
            </p>

            <a href="<?= base_url('takmir') ?>" class="btn btn-sm btn-secondary mt-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Pilih Ruangan
            </a>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>