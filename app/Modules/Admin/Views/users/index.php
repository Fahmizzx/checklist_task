<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('pageTitle'); ?>
Manajemen Pengguna
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="card shadow-sm card-flush border-0">
    <div class="card-header pt-8">
        <div class="card-title">
            <h3 class="fw-bold">Manajemen Pengguna</h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex align-items-center position-relative my-1 me-5">
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                    </svg>
                </span>
                <input type="text" data-kt-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Cari Pengguna..." />
            </div>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                Tambah Pengguna
            </button>
        </div>
    </div>
    <div class="card-body">
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
        <?php $validation = \Config\Services::validation(); if ($validation->hasError('nama') || $validation->hasError('username') || $validation->hasError('email') || $validation->hasError('password')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Gagal Menambahkan Pengguna!</h4>
                <div class="alert-body">
                    <?= $validation->listErrors() ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="user-management-table" class="table table-row-bordered table-rounded border gy-5 gs-7">
                <thead class="bg-light">
                    <tr class="fw-bold fs-6 text-gray-800">
                        <th class="text-center w-50px">No</th>
                        <th>Nama</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php $no = 1;
                        foreach ($users as $user) : ?>
                            <?php
                            $status_badge = '';
                            $status_text = '';
                            if ($user['banned'] == 1) {
                                $status_badge = 'badge-light-danger';
                                $status_text = 'Diblokir';
                            } elseif ($user['aktivasi'] == 0) {
                                $status_badge = 'badge-light-warning';
                                $status_text = 'Menunggu Persetujuan';
                            } else {
                                $status_badge = 'badge-light-success';
                                $status_text = 'Aktif';
                            }
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 text-hover-primary mb-1 fw-bold"><?= esc($user['nama']) ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $status_badge ?>"><?= $status_text ?></span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#userActionModal" 
                                    data-id="<?= $user['id_users'] ?>" 
                                    data-nama="<?= esc($user['nama']) ?>" 
                                    data-username="<?= esc($user['username']) ?>" 
                                    data-email="<?= esc($user['email']) ?>" 
                                    data-role="<?= esc($user['role']) ?>" 
                                    data-aktivasi="<?= $user['aktivasi'] ?>" 
                                    data-banned="<?= $user['banned'] ?>">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-light-danger btn-delete" data-id="<?= $user['id_users'] ?>">
                                        Delete
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

<!-- Modal Edit Pengguna -->
<div class="modal fade" id="userActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Konten akan di-generate oleh JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('admin/users/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-solid" name="nama" value="<?= old('nama') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Username</label>
                        <input type="text" class="form-control form-control-solid" name="username" value="<?= old('username') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Email</label>
                        <input type="email" class="form-control form-control-solid" name="email" value="<?= old('email') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Password</label>
                        <input type="password" class="form-control form-control-solid" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Konfirmasi Password</label>
                        <input type="password" class="form-control form-control-solid" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PERBAIKAN UTAMA DI SINI: Template Modal -->
<template id="user-modal-template">
    <div class="mb-7">
        <div class="row mb-2">
            <div class="col-4 text-gray-500 fw-semibold">Nama:</div>
            <div class="col-8 fw-bold" id="modalNama"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4 text-gray-500 fw-semibold">Username:</div>
            <div class="col-8 fw-bold" id="modalUsername"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4 text-gray-500 fw-semibold">Email:</div>
            <div class="col-8 fw-bold" id="modalEmail"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4 text-gray-500 fw-semibold">Status:</div>
            <div class="col-8" id="modalStatus"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4 text-gray-500 fw-semibold">Peran:</div>
            <div class="col-8 fw-bold" id="modalPeran"></div>
        </div>
    </div>
    <div class="separator separator-dashed mb-7"></div>
    <div id="actionFormsContainer" class="d-flex flex-column gap-3">
        <form id="roleForm" method="post" action="##FORM_ACTION##" class="d-none">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update_role">
            <div class="mb-3">
                <label for="modalRole" class="form-label required">Tetapkan Peran Pengguna</label>
                <select id="modalRole" name="role" class="form-select form-select-solid" data-placeholder="Pilih peran..." required>
                    <option value="">Pilih Peran</option>
                    <?php if (!empty($roles)) : ?>
                        <?php foreach ($roles as $role_item) : ?>
                            <option value="<?= esc($role_item['role']) ?>"><?= esc($role_item['role']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <button id="roleSubmitButton" type="submit" class="btn btn-primary w-100">Setujui & Tetapkan Peran</button>
        </form>
        <div id="banUnbanContainer" class="row g-2 d-none">
            <div class="col">
                <form id="banForm" method="post" action="##FORM_ACTION##">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="ban">
                    <button id="banButton" type="button" class="btn btn-danger w-100">Blokir</button>
                </form>
            </div>
            <div class="col">
                 <form id="unbanForm" method="post" action="##FORM_ACTION##">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="unban">
                    <button id="unbanButton" type="button" class="btn btn-success w-100">Buka Blokir</button>
                </form>
            </div>
        </div>
    </div>
</template>

<!-- Form Hapus (tersembunyi) -->
<form id="formDeleteUser" action="<?= base_url('admin/users/delete') ?>" method="post" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" id="delete_id_users" name="id_users">
</form>

<?= $this->endSection(); ?>

<?= $this->section('custom_page_scripts'); ?>
<script>
    "use strict";
    document.addEventListener("DOMContentLoaded", function() {
        var KTUserManagement = function () {
            var table;
            var datatable;
            var modalEl = document.getElementById('userActionModal');

            var initDatatable = function () {
                if (typeof $ === 'undefined' || !$.fn.DataTable) { return; }
                datatable = $(table).DataTable({
                    "info": false, 'order': [], 'pageLength': 10,
                    'columnDefs': [{ orderable: false, targets: 3 }],
                    language: { zeroRecords: "Data pengguna tidak ditemukan", search: "", lengthMenu: "Tampilkan _MENU_" }
                });
            }

            var handleSearchDatatable = function () {
                const filterSearch = document.querySelector('[data-kt-filter="search"]');
                if (filterSearch) {
                    filterSearch.addEventListener('keyup', function (e) {
                        if (datatable) datatable.search(e.target.value).draw();
                    });
                }
            }

            var handleModal = function () {
                if (!modalEl) return;
                modalEl.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const modalBody = modalEl.querySelector('.modal-body');
                    let templateHTML = document.getElementById('user-modal-template').innerHTML;
                    
                    const id = button.getAttribute('data-id');
                    const updateUrl = "<?= base_url('admin/users/update/') ?>" + "/" + id;

                    templateHTML = templateHTML.replace(/##FORM_ACTION##/g, updateUrl);
                    modalBody.innerHTML = templateHTML;

                    const nama = button.getAttribute('data-nama');
                    const username = button.getAttribute('data-username');
                    const email = button.getAttribute('data-email');
                    const role = button.getAttribute('data-role');
                    const aktivasi = button.getAttribute('data-aktivasi');
                    const banned = button.getAttribute('data-banned');

                    modalEl.querySelector('#modalTitle').textContent = 'Detail Pengguna';
                    modalEl.querySelector('#modalNama').textContent = nama;
                    modalEl.querySelector('#modalUsername').textContent = username;
                    modalEl.querySelector('#modalEmail').textContent = email;
                    modalEl.querySelector('#modalPeran').textContent = role || 'Belum diatur';

                    const statusContainer = modalEl.querySelector('#modalStatus');
                    statusContainer.innerHTML = ''; // Kosongkan dulu
                    if (banned == '1') {
                        statusContainer.innerHTML = '<span class="badge badge-light-danger">Diblokir</span>';
                    } else if (aktivasi == '0') {
                        statusContainer.innerHTML = '<span class="badge badge-light-warning">Menunggu Persetujuan</span>';
                    } else {
                        statusContainer.innerHTML = '<span class="badge badge-light-success">Aktif</span>';
                    }

                    const roleForm = modalEl.querySelector('#roleForm');
                    const banForm = modalEl.querySelector('#banForm');
                    const unbanForm = modalEl.querySelector('#unbanForm');
                    const banUnbanContainer = modalEl.querySelector('#banUnbanContainer');
                    
                    if (banned == '1') {
                        banUnbanContainer.classList.remove('d-none');
                        unbanForm.classList.remove('d-none');
                        setupConfirmation(modalEl.querySelector('#unbanButton'), unbanForm, 'Aktifkan kembali pengguna ini?');
                    } else if (aktivasi == '0') {
                        roleForm.classList.remove('d-none');
                        const roleSelect = modalEl.querySelector('#modalRole');
                        roleSelect.value = role;
                        setupConfirmation(modalEl.querySelector('#roleSubmitButton'), roleForm, 'Anda yakin ingin menyetujui pengguna ini?');
                    } else if (aktivasi == '1') {
                        roleForm.classList.remove('d-none');
                        banUnbanContainer.classList.remove('d-none');
                        banForm.classList.remove('d-none');
                        const roleSelect = modalEl.querySelector('#modalRole');
                        roleSelect.value = role;
                        modalEl.querySelector('#roleSubmitButton').textContent = 'Perbarui Peran';
                        setupConfirmation(modalEl.querySelector('#banButton'), banForm, 'Blokir pengguna ini?');
                    }
                });
            }
            
            var handleDelete = function() {
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function() {
                        const userId = this.getAttribute('data-id');
                        Swal.fire({
                            text: "Anda yakin ingin menghapus pengguna ini secara permanen?",
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
                                document.getElementById('delete_id_users').value = userId;
                                document.getElementById('formDeleteUser').submit();
                            }
                        });
                    });
                });
            };

            var handleAddUserModal = function() {
                <?php $validation = \Config\Services::validation(); if ($validation->hasError('nama') || $validation->hasError('username') || $validation->hasError('email') || $validation->hasError('password')) : ?>
                    const addUserModal = new bootstrap.Modal(document.getElementById('modalTambahUser'));
                    addUserModal.show();
                <?php endif; ?>
            };

            var setupConfirmation = function(button, form, title) {
                if (!button || !form) return;
                var newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);

                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (typeof Swal === 'undefined') {
                        if(confirm(title)) form.submit();
                        return;
                    }
                    Swal.fire({
                        title: title, text: "Anda tidak akan dapat mengembalikan tindakan ini!",
                        icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal', customClass: { confirmButton: "btn btn-primary", cancelButton: "btn btn-light" }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            }

            return {
                init: function () {
                    table = document.querySelector('#user-management-table');
                    if (table) {
                        initDatatable();
                        handleSearchDatatable();
                        handleModal();
                        handleDelete();
                        handleAddUserModal();
                    }
                }
            };
        }();

        KTUserManagement.init();
    });
</script>
<?= $this->endSection(); ?>
