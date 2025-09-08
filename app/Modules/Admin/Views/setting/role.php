<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('pageTitle'); ?>
Setting - Manajemen Role
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="card card-flush shadow-sm">
        <div class="card-header pt-8">
            <div class="card-title">
                <h3 class="fw-bold">Manajemen Peran Pengguna</h3>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1 me-5">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                        </svg>
                    </span>
                    <input type="text" data-kt-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Cari Peran..." />
                </div>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahRole">
                    Tambah Peran Baru
                </button>
            </div>
        </div>
        <div class="card-body py-5">
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

            <div class="table-responsive">
                <!-- PERBAIKAN: Mengganti ID tabel agar sesuai dengan JavaScript -->
                <table id="kt_datatable_roles" class="table table-row-bordered table-rounded border gy-5 gs-7">
                    <thead class="bg-light">
                        <!-- PERBAIKAN: Menambahkan text-center ke tr untuk header -->
                        <tr class="fw-bold fs-6 text-gray-800 text-center">
                            <th class="min-w-125px">Nama Peran (Singkat)</th>
                            <th class="min-w-250px">Nama Lengkap Peran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        <?php if (!empty($roles)) : ?>
                            <?php foreach ($roles as $role) : ?>
                                <tr>
                                    <!-- PERBAIKAN: Menambahkan text-center ke td -->
                                    <td class="text-center"><?= esc($role['role']); ?></td>
                                    <td class="text-center"><?= esc($role['full']); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light-warning btn-edit" 
                                            data-role-id="<?= $role['id_role'] ?>" 
                                            data-role-name="<?= esc($role['role']) ?>"
                                            data-role-full="<?= esc($role['full']) ?>">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-light-danger btn-delete" data-role-id="<?= $role['id_role'] ?>">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Role -->
<div class="modal fade" id="modalTambahRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('admin/setting/role/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Peran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Peran (Singkat)</label>
                        <input type="text" class="form-control form-control-solid" name="role" placeholder="Contoh: CS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Peran</label>
                        <input type="text" class="form-control form-control-solid" name="full" placeholder="Contoh: Cleaning Service">
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

<!-- Modal Edit Role -->
<div class="modal fade" id="modalEditRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditRole" action="<?= base_url('admin/setting/role/update') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_id_role" name="id_role">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Peran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Peran (Singkat)</label>
                        <input type="text" class="form-control form-control-solid" id="edit_nama_role" name="role" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Peran</label>
                        <input type="text" class="form-control form-control-solid" id="edit_full_role" name="full">
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

<!-- Form Hapus (tersembunyi) -->
<form id="formDeleteRole" action="<?= base_url('admin/setting/role/delete') ?>" method="post" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" id="delete_id_role" name="id_role">
</form>

<?= $this->endSection(); ?>

<?= $this->section('custom_page_scripts'); ?>
<script>
    "use strict";
    document.addEventListener("DOMContentLoaded", function() {
        var table = document.querySelector('#kt_datatable_roles');
        var datatable;

        if (table) {
            datatable = $(table).DataTable({
                "info": false,
                'order': [[0, 'asc']],
                'pageLength': 10,
                'columnDefs': [{ orderable: false, targets: 2 }]
            });
        }

        const filterSearch = document.querySelector('[data-kt-filter="search"]');
        if(filterSearch) {
            filterSearch.addEventListener('keyup', function(e) {
                if(datatable) {
                    datatable.search(e.target.value).draw();
                }
            });
        }

        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const roleId = this.getAttribute('data-role-id');
                const roleName = this.getAttribute('data-role-name');
                const roleFull = this.getAttribute('data-role-full');

                document.getElementById('edit_id_role').value = roleId;
                document.getElementById('edit_nama_role').value = roleName;
                document.getElementById('edit_full_role').value = roleFull;

                new bootstrap.Modal(document.getElementById('modalEditRole')).show();
            });
        });

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const roleId = this.getAttribute('data-role-id');
                Swal.fire({
                    text: "Anda yakin ingin menghapus peran ini?",
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
                        document.getElementById('delete_id_role').value = roleId;
                        document.getElementById('formDeleteRole').submit();
                    }
                });
            });
        });
    });
</script>
<?= $this->endSection(); ?>
