<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('pageTitle'); ?>
Setting - Manajemen Perizinan
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Flashdata Messages -->
    <?php if (session()->getFlashdata('success_msg')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success_msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error_msg')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error_msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->get('errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Gagal Menyimpan!</h4>
            <div class="alert-body">
                <?php foreach (session()->get('errors') as $error) : ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach ?>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Card untuk CRUD Halaman -->
    <div class="card card-flush shadow-sm mb-10">
        <div class="card-header pt-8">
            <div class="card-title">
                <h3 class="fw-bold">Kelola Halaman</h3>
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPage">
                    Tambah Halaman Baru
                </button>
            </div>
        </div>
        <div class="card-body py-5">
            <div class="table-responsive">
                <table class="table table-row-bordered table-rounded border gy-5 gs-7">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th>Nama Halaman</th>
                            <th>Kunci Halaman</th>
                            <th>URL</th>
                            <th>Induk Menu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page) : ?>
                            <tr>
                                <td><?= esc($page['page_name']); ?></td>
                                <td><code><?= esc($page['page_key']); ?></code></td>
                                <td><?= esc($page['url'] ?? '-'); ?></td>
                                <td><code><?= esc($page['parent_key'] ?? 'Menu Utama'); ?></code></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light-warning btn-edit-page"
                                        data-page-id="<?= $page['id'] ?>"
                                        data-page-key="<?= esc($page['page_key']) ?>"
                                        data-page-name="<?= esc($page['page_name']) ?>"
                                        data-page-parent="<?= esc($page['parent_key']) ?>"
                                        data-page-url="<?= esc($page['url']) ?>"
                                        data-page-icon="<?= esc($page['icon']) ?>"
                                        data-page-order="<?= esc($page['menu_order']) ?>">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-light-danger btn-delete-page" data-page-id="<?= $page['id'] ?>">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card untuk Matriks Perizinan -->
    <div class="card card-flush shadow-sm">
        <!-- ... (konten matriks perizinan tetap sama) ... -->
    </div>
</div>

<!-- Modal Tambah Halaman -->
<div class="modal fade" id="modalTambahPage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('admin/setting/page/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Halaman Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Halaman</label>
                        <input type="text" class="form-control form-control-solid" name="page_name" value="<?= old('page_name') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Kunci Halaman</label>
                        <input type="text" class="form-control form-control-solid" name="page_key" value="<?= old('page_key') ?>" required>
                        <div class="form-text">Gunakan format `alpha_dash` (contoh: `halaman_baru`). Ini akan digunakan di sistem.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Induk Menu</label>
                        <select class="form-select form-select-solid" name="parent_key">
                            <option value="">-- Menu Utama (Tanpa Induk) --</option>
                            <?php foreach($pages as $p): ?>
                                <option value="<?= esc($p['page_key']) ?>"><?= esc($p['page_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Pilih di bawah menu mana halaman ini akan muncul.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control form-control-solid" name="url" value="<?= old('url') ?>">
                        <div class="form-text">Contoh: `dashboard`, `admin/users`. Kosongkan jika ini adalah menu dropdown.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ikon</label>
                        <input type="text" class="form-control form-control-solid" name="icon" value="<?= old('icon') ?>">
                        <div class="form-text">Contoh: `fonticon-house`, `bi-gear`. Kosongkan jika ini adalah sub-menu.</div>
                    </div>
                     <div class="mb-3">
                        <label class="form-label">Urutan Menu</label>
                        <input type="number" class="form-control form-control-solid" name="menu_order" value="<?= old('menu_order', 0) ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Halaman -->
<div class="modal fade" id="modalEditPage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditPage" action="<?= base_url('admin/setting/page/update') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_id_page" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Halaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Halaman</label>
                        <input type="text" class="form-control form-control-solid" id="edit_page_name" name="page_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Kunci Halaman</label>
                        <input type="text" class="form-control form-control-solid" id="edit_page_key" name="page_key" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Induk Menu</label>
                        <select class="form-select form-select-solid" id="edit_parent_key" name="parent_key">
                            <option value="">-- Menu Utama (Tanpa Induk) --</option>
                            <?php foreach($pages as $p): ?>
                                <option value="<?= esc($p['page_key']) ?>"><?= esc($p['page_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control form-control-solid" id="edit_page_url" name="url">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ikon</label>
                        <input type="text" class="form-control form-control-solid" id="edit_page_icon" name="icon">
                    </div>
                     <div class="mb-3">
                        <label class="form-label">Urutan Menu</label>
                        <input type="number" class="form-control form-control-solid" id="edit_menu_order" name="menu_order">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Hapus Halaman (tersembunyi) -->
<form id="formDeletePage" action="<?= base_url('admin/setting/page/delete') ?>" method="post" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" id="delete_id_page" name="id">
</form>

<?= $this->endSection(); ?>

<?= $this->section('custom_page_scripts'); ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Handle klik tombol edit halaman
        document.querySelectorAll('.btn-edit-page').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit_id_page').value = this.getAttribute('data-page-id');
                document.getElementById('edit_page_key').value = this.getAttribute('data-page-key');
                document.getElementById('edit_page_name').value = this.getAttribute('data-page-name');
                document.getElementById('edit_parent_key').value = this.getAttribute('data-page-parent');
                document.getElementById('edit_page_url').value = this.getAttribute('data-page-url');
                document.getElementById('edit_page_icon').value = this.getAttribute('data-page-icon');
                document.getElementById('edit_menu_order').value = this.getAttribute('data-page-order');
                new bootstrap.Modal(document.getElementById('modalEditPage')).show();
            });
        });

        // Handle klik tombol delete halaman
        document.querySelectorAll('.btn-delete-page').forEach(button => {
            button.addEventListener('click', function() {
                const pageId = this.getAttribute('data-page-id');
                Swal.fire({
                    text: "Anda yakin ingin menghapus halaman ini dari sistem?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Tidak, batalkan",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then(function(result) {
                    if (result.isConfirmed) {
                        document.getElementById('delete_id_page').value = pageId;
                        document.getElementById('formDeletePage').submit();
                    }
                });
            });
        });
    });
</script>
<?= $this->endSection(); ?>
