<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="title mb-5">
                <h4>Pilih Ruangan Checklist Mechanical</h4>
            </div>

            <div class="card shadow-sm card-flush border-0">
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="uuid" class="form-label">Pilih Ruangan</label>
                            <select class="form-select form-select-sm" data-control="select2" id="uuid" name="uuid" onchange="if(this.value) location.href='<?= base_url('mechanical/checklist_task') ?>/' + this.value;">
                                <option value="">-- Pilih Ruangan --</option>
                                <?php foreach ($ruangan as $r): ?>
                                    <option value="<?= esc($r['uuid_ruangan']) ?>">
                                        <?= esc($r['nama_lokasi']) ?> - <?= esc($r['nama_ruangan']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>