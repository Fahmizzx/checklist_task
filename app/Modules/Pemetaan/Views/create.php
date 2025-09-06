<?php
date_default_timezone_set('Asia/Jakarta');

use Carbon\Carbon;
?>

<?= $this->extend('layout/dashboard'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="title mb-5">
        <h4><a href="<?php echo base_url('/pemetaan'); ?>" class="text-dark">Pemetaan Checklist Maintenance</a></h4>
    </div>

    <div class="card card-flush py-3 px-5">
        <div class="card-body">
            <form id="form-tambahPemetaan" class="form" method="post">
                <?= csrf_field() ?>
                <!-- Lokasi -->
                <div class="mb-5">
                    <label class="form-label fw-bold">Lokasi <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm 100 w-md-25 <?= isset($errors['lokasi']) ? 'is-invalid' : '' ?>" data-control="select2" name="lokasi" id="lokasi" onchange="updateRuangan(this.value)">
                        <option label="">Pilih Lokasi</option>
                        <?php foreach ($tb_lokasi as $lokasi) { ?>
                            <option value="<?= esc($lokasi['id_lokasi']); ?>" <?= old('lokasi') == $lokasi['id_lokasi'] ? 'selected' : '' ?>>
                                <?= esc($lokasi['nama_lokasi']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $errors['lokasi'] ?? '' ?>
                    </div>
                </div>

                <!-- Petugas -->
                <div class="mb-5">
                    <label class="form-label fw-bold">Petugas <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm 100 w-md-25 <?= isset($errors['petugas']) ? 'is-invalid' : '' ?>" data-control="select2" name="petugas" id="petugas">
                        <option label="">Pilih Petugas</option>
                        <?php foreach ($tb_role as $role) { ?>
                            <option value="<?= esc($role['id_role']); ?>" <?= old('petugas') == $role['id_role'] ? 'selected' : '' ?>>
                                <?= esc($role['role']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $errors['petugas'] ?? '' ?>
                    </div>
                </div>

                <!-- Repeater -->
                <div class="form-group" id="aktivitasRepeater">
                    <div class="d-flex justify-content-end mb-5">
                        <a href="javascript:;" data-repeater-create class="btn btn-sm btn-primary">
                            Tambah aktivitas
                        </a>
                    </div>

                    <hr class="my-5" />

                    <?php $list_aktivitas = old('list_aktivitas') ?? [[]]; ?>
                    <div data-repeater-list="list_aktivitas">
                        <?php foreach ($list_aktivitas as $index => $aktivitas) : ?>
                            <div data-repeater-item>
                                <div class="row mb-5">
                                    <!-- Ruangan -->
                                    <div class="col-md-5 mt-5">
                                        <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                                        <select name="ruangan" class="form-select form-select-sm ruangan-select <?= isset($errors["list_aktivitas.$index.ruangan"]) ? 'is-invalid' : '' ?>" data-control="select2">
                                            <option value="">Pilih Ruangan</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            <?= $errors["list_aktivitas.$index.ruangan"] ?? '' ?>
                                        </div>
                                    </div>

                                    <!-- Aktivitas -->
                                    <div class="col-md-5 mt-5">
                                        <label class="form-label">Aktivitas <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-control-sm <?= isset($errors["list_aktivitas.$index.aktivitas"]) ? 'is-invalid' : '' ?>" placeholder="Masukan Aktivitas" rows="10" name="aktivitas"><?= esc($aktivitas['aktivitas'] ?? '') ?></textarea>
                                        <div class="invalid-feedback">
                                            <?= $errors["list_aktivitas.$index.aktivitas"] ?? '' ?>
                                        </div>
                                    </div>

                                    <!-- Periodik -->
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label">Periodik <span class="text-danger">*</span></label>
                                        <select name="periodik" class="form-select form-select-sm <?= isset($errors["list_aktivitas.$index.periodik"]) ? 'is-invalid' : '' ?>" data-control="select2">
                                            <optgroup label="Pilih Periodik">
                                                <?php foreach ($tb_periodik as $periodik) { ?>
                                                    <option value="<?= esc($periodik['id_periodik']); ?>"
                                                        <?= (isset($aktivitas['periodik']) && $aktivitas['periodik'] == $periodik['id_periodik']) ? 'selected' : '' ?>>
                                                        <?= esc($periodik['periodik']); ?>
                                                    </option>
                                                <?php } ?>
                                            </optgroup>
                                        </select>
                                        <div class="invalid-feedback">
                                            <?= $errors["list_aktivitas.$index.periodik"] ?? '' ?>
                                        </div>
                                    </div>

                                    <!-- Nested Repeater (Waktu) -->
                                    <div class="col-md-4 mt-5">
                                        <div class="inner-repeater">
                                            <div data-repeater-list="list_waktu" class="mb-5">
                                                <!-- Item repeater default -->
                                                <?php $waktus = $aktivitas['list_waktu'] ?? [[]]; ?>
                                                <?php foreach ($waktus as $i => $waktu): ?>
                                                    <div data-repeater-item>
                                                        <label class="form-label">Waktu / Hari <span class="text-danger">*</span></label>

                                                        <!-- Input waktu (periodik harian) -->
                                                        <div class="input-group input-group-sm waktu-wrapper">
                                                            <input type="text" name="waktu" class="form-control form-control-sm waktu <?= isset($errors["list_aktivitas.$index.list_waktu.$i.waktu"]) ? 'is-invalid' : '' ?>" placeholder="Pilih Waktu" value="<?= esc($waktu['waktu'] ?? '') ?>">
                                                            <button class="btn btn-sm btn-light" data-repeater-create type="button">
                                                                <span class="svg-icon svg-icon-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 28 28">
                                                                        <path d="M5 12h14M12 5v14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </div>
                                                        <?php if (isset($errors["list_aktivitas.$index.list_waktu.$i.waktu"])): ?>
                                                            <div class="invalid-feedback"><?= $errors["list_aktivitas.$index.list_waktu.$i.waktu"] ?></div>
                                                        <?php endif; ?>

                                                        <!-- Select hari (periodik mingguan) -->
                                                        <div class="input-group input-group-sm hari-wrapper mt-2">
                                                            <select class="form-select form-select-sm hari-select <?= isset($errors["list_aktivitas.$index.list_waktu.$i.hari"]) ? 'is-invalid' : '' ?>" data-control="select2" name="hari" style="flex-grow: 1; width: auto;">
                                                                <option value="">Pilih Hari</option>
                                                                <option value="Senin" <?= ($waktu['hari'] ?? '') == 'Senin' ? 'selected' : '' ?>>Senin</option>
                                                                <option value="Selasa" <?= ($waktu['hari'] ?? '') == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                                                                <option value="Rabu" <?= ($waktu['hari'] ?? '') == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                                                                <option value="Kamis" <?= ($waktu['hari'] ?? '') == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                                                                <option value="Jumat" <?= ($waktu['hari'] ?? '') == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                                                                <option value="Sabtu" <?= ($waktu['hari'] ?? '') == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
                                                                <option value="Minggu" <?= ($waktu['hari'] ?? '') == 'Minggu' ? 'selected' : '' ?>>Minggu</option>
                                                            </select>
                                                            <button class="btn btn-sm btn-light" data-repeater-create type="button">
                                                                <span class="svg-icon svg-icon-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 28 28">
                                                                        <path d="M5 12h14M12 5v14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </div>
                                                        <?php if (isset($errors["list_aktivitas.$index.list_waktu.$i.hari"])): ?>
                                                            <div class="invalid-feedback"><?= $errors["list_aktivitas.$index.list_waktu.$i.hari"] ?></div>
                                                        <?php endif; ?>

                                                        <!-- tombol hapus repeater -->
                                                        <button data-repeater-delete type="button" class="btn btn-danger btn-sm mt-2">Delete</button>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Standar -->
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label">Standar <span class="text-danger">*</span></label>
                                        <input type="text" name="standar" class="form-control form-control-sm <?= isset($errors["list_aktivitas.$index.standar"]) ? 'is-invalid' : '' ?>" placeholder="Contoh: Ruangan Bersih" value="<?= esc($aktivitas['standar'] ?? '') ?>">
                                        <div class="invalid-feedback">
                                            <?= $errors["list_aktivitas.$index.standar"] ?? '' ?>
                                        </div>
                                    </div>

                                    <!-- Tombol Delete -->
                                    <div class="col-md-3 mt-3">
                                        <button type="button" data-repeater-delete class="btn btn-sm btn-danger mt-5">
                                            <i class="la la-trash-o"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="d-flex justify-content-center mt-10">
                    <button type="button" onclick="saveData()" class="btn btn-sm btn-primary me-3">Simpan</button>
                    <button type="reset" class="btn btn-sm btn-danger">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Ambil id_lokasi dari form utama
        const id_lokasi = $('#lokasi').val();
        updateRuangan(id_lokasi);
    });


    function updateRuangan() {
        var lokasiSelect = $('#lokasi');
        var id_lokasi = lokasiSelect.val();

        $('[data-repeater-item]').each(function() {
            var container = $(this);
            var ruanganSelect = container.find('.ruangan-select');

            var currentVal = ruanganSelect.val();
            if (currentVal && currentVal !== "") {
                return; // skip ruangan yang sudah diisi
            }

            $.ajax({
                url: '/pemetaan/getRuanganByLokasi/' + id_lokasi,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    try {
                        ruanganSelect.select2('destroy'); // coba destroy, jika belum diinisialisasi bakal error, ditangkap try-catch
                    } catch (e) {
                        // do nothing, karena memang belum diinisialisasi Select2
                    }

                    ruanganSelect.empty();
                    ruanganSelect.append('<option value="">Pilih Ruangan</option>');

                    $.each(response, function(i, item) {
                        ruanganSelect.append(
                            $('<option>', {
                                value: item.id_ruangan,
                                text: item.ruangan
                            })
                        );
                    });

                    ruanganSelect.select2({
                        dropdownParent: $('#form-tambahPemetaan')
                    });
                },
                error: function() {
                    alert('Gagal memuat data ruangan.');
                }
            });
        });
    }

    $('#lokasi').on('change', function() {
        updateRuangan();
    });


    // fungsi untuk waktu ( tanggal dan jam)
    function initializeFlatpickr() {
        $("input[name*='[waktu]']").each(function() {
            if (!$(this).hasClass('flatpickr-initialized')) {
                flatpickr(this, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                });
                $(this).addClass('flatpickr-initialized');
            }
        });
    }

    // fungsi/inisialisasi repeater aktivitas
    $('#aktivitasRepeater').repeater({
        repeaters: [{
            selector: '.inner-repeater',
            nestedName: 'list_waktu',
            initEmpty: false,
            isFirstItemUndeletable: true,
            show: function() {
                $(this).slideDown();
                initializeFlatpickr();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        }],
        isFirstItemUndeletable: true,
        show: function() {
            $(this).slideDown();


            initializeFlatpickr();
            updateDeleteButtons();

            handlePeriodikChange($(this));

            const id_lokasi = $('#lokasi').val();
            updateRuangan(id_lokasi);

            // Inisialisasi ulang semua Select2 di dalam item baru
            $(this).find('select[data-control="select2"]').select2({
                dropdownParent: $(this)
            });

            // Set default value periodik (pastikan option value="1" sudah ada)
            let selectPeriodik = $(this).find('select[name*="periodik"]');
            if (!selectPeriodik.find('option:selected').val()) {
                selectPeriodik.val("1").trigger("change");
            }


        },
        hide: function(deleteElement) {
            $(this).slideUp(deleteElement, function() {
                updateDeleteButtons();
            });
        }
    });

    // Inisialisasi awal saat halaman pertama kali load
    $(document).ready(function() {
        const id_lokasi = $('#lokasi').val();
        updateRuangan(id_lokasi); // muat ruangan di semua select awal

        // Jika id_lokasi berubah
        $('#lokasi').on('change', function() {
            updateRuangan($(this).val());
        });

        $('select[data-control="select2"]').select2();

        initializeFlatpickr();

        // Jalankan untuk semua select periodik (termasuk form utama)
        handlePeriodikChange($('body'));

        // Handle jika tombol tambah repeater diklik
        $('[data-repeater-list]').each(function() {
            const repeaterList = $(this);

            repeaterList.closest('form').on('click', '[data-repeater-create]', function() {
                setTimeout(function() {
                    repeaterList.find('[data-repeater-item]').each(function() {
                        handlePeriodikChange($(this));
                    });
                    initializeFlatpickr();
                }, 100);
            });
        });

    });

    // fungsi tombol delete pada repeater
    function updateDeleteButtons() {
        const repeaterItems = $('[data-repeater-list="list_aktivitas"] > [data-repeater-item]');

        repeaterItems.each(function(index, element) {
            const deleteButton = $(element).find('[data-repeater-delete]');
            if (index === 0) {
                deleteButton.hide();
            } else {
                deleteButton.show();
            }
        });
    }


    // fungsi pesan / alert (SweetAlert2)
    function alertMessage(message, type = 'success') {
        Swal.fire({
            text: message,
            icon: type,
            buttonsStyling: false,
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
            customClass: {
                confirmButton: "btn btn-sm btn-light-" + (type === 'success' ? "success" : "danger")
            },
        });
    }


    // fungsi simpan data
    // function saveData() {
    //     const form = $("#form-tambahPemetaan");
    //     const formData = form.serializeArray();

    //     $.ajax({
    //         type: "POST",
    //         dataType: "html",
    //         url: "<?= base_url('pemetaan/store') ?>",
    //         data: formData,
    //         success: function(response) {
    //             form[0].reset();
    //             alertMessage('Berhasil menambahkan data pemetaan aktivitas!', 'success');

    //             setTimeout(function() {
    //                 window.location.href = '/pemetaan';
    //             }, 1700);
    //         },
    //         error: function(err) {
    //             const errorData = err.responseJSON?.errors || err.responseText;
    //             console.error(errorData);
    //             alertMessage('Terjadi kesalahan saat menyimpan data.', 'error');
    //         }
    //     });
    // }

    // function saveData() {
    //     const form = $('#form-tambahPemetaan');
    //     const formData = form.serialize();

    //     // Bersihkan semua pesan error sebelumnya
    //     form.find('.is-invalid').removeClass('is-invalid');
    //     form.find('.invalid-feedback').remove();

    //     $.ajax({
    //         type: "POST",
    //         url: "<?= base_url('pemetaan/store') ?>",
    //         data: formData,
    //         dataType: "json",
    //         success: function(response) {
    //             if (response.status === 'success') {
    //                 alertMessage(response.message || 'Data berhasil disimpan!', 'success');

    //                 setTimeout(() => {
    //                     window.location.href = '/pemetaan';
    //                 }, 1600);
    //             }
    //         },
    //         error: function(xhr) {
    //             const res = xhr.responseJSON;
    //             if (!res || !res.errors) {
    //                 alertMessage('Terjadi kesalahan tidak terduga.', 'error');
    //                 return;
    //             }

    //             const errors = res.errors;

    //             // Validasi utama
    //             if (errors.lokasi) {
    //                 $('#lokasi').addClass('is-invalid')
    //                     .after(`<div class="invalid-feedback">${errors.lokasi}</div>`);
    //             }
    //             if (errors.petugas) {
    //                 $('#petugas').addClass('is-invalid')
    //                     .after(`<div class="invalid-feedback">${errors.petugas}</div>`);
    //             }

    //             // Validasi per aktivitas
    //             if (Array.isArray(errors.list_aktivitas)) {
    //                 errors.list_aktivitas.forEach((errAktivitas, indexAktivitas) => {
    //                     const aktivitasElement = $('[data-repeater-list="list_aktivitas"] > [data-repeater-item]').eq(indexAktivitas);

    //                     if (errAktivitas.ruangan) {
    //                         aktivitasElement.find('[name="ruangan"]').addClass('is-invalid')
    //                             .after(`<div class="invalid-feedback">${errAktivitas.ruangan}</div>`);
    //                     }

    //                     if (errAktivitas.aktivitas) {
    //                         aktivitasElement.find('[name="aktivitas"]').addClass('is-invalid')
    //                             .after(`<div class="invalid-feedback">${errAktivitas.aktivitas}</div>`);
    //                     }

    //                     if (errAktivitas.periodik) {
    //                         aktivitasElement.find('[name="periodik"]').addClass('is-invalid')
    //                             .after(`<div class="invalid-feedback">${errAktivitas.periodik}</div>`);
    //                     }

    //                     if (errAktivitas.standar) {
    //                         aktivitasElement.find('[name="standar"]').addClass('is-invalid')
    //                             .after(`<div class="invalid-feedback">${errAktivitas.standar}</div>`);
    //                     }

    //                     // Nested repeater waktu
    //                     if (Array.isArray(errAktivitas.list_waktu)) {
    //                         errAktivitas.list_waktu.forEach((errWaktu, indexWaktu) => {
    //                             const waktuItem = aktivitasElement.find('[data-repeater-list="list_waktu"] > [data-repeater-item]').eq(indexWaktu);

    //                             if (errWaktu.waktu) {
    //                                 waktuItem.find('[name="waktu"]').addClass('is-invalid')
    //                                     .after(`<div class="invalid-feedback">${errWaktu.waktu}</div>`);
    //                             }

    //                             if (errWaktu.hari) {
    //                                 waktuItem.find('[name="hari"]').addClass('is-invalid')
    //                                     .after(`<div class="invalid-feedback">${errWaktu.hari}</div>`);
    //                             }
    //                         });
    //                     }
    //                 });
    //             }

    //             alertMessage('Periksa kembali input yang masih salah.', 'error');
    //         }
    //     });
    // }

    // function saveData() {
    //     const form = $('#form-tambahPemetaan');
    //     const formData = form.serialize();

    //     // Hapus error sebelumnya
    //     form.find('.is-invalid').removeClass('is-invalid');
    //     form.find('.invalid-feedback').remove();

    //     $.ajax({
    //         type: "POST",
    //         url: "<?= base_url('pemetaan/store') ?>",
    //         data: formData,
    //         dataType: "json",
    //         success: function(response) {
    //             if (response.status === 'success') {
    //                 alertMessage(response.message || 'Data berhasil disimpan!', 'success');
    //                 setTimeout(() => window.location.href = '/pemetaan', 1600);
    //             }
    //         },
    //         error: function(xhr) {
    //             const res = xhr.responseJSON;
    //             if (!res || !res.errors) {
    //                 alertMessage('Terjadi kesalahan tidak terduga.', 'error');
    //                 return;
    //             }

    //             const errors = res.errors;

    //             // Loop seluruh error key yang dikembalikan dari server
    //             for (const key in errors) {
    //                 if (!errors.hasOwnProperty(key)) continue;

    //                 const errorMessage = errors[key];

    //                 // Ubah key titik menjadi format array name, misal: list_aktivitas.0.ruangan => list_aktivitas[0][ruangan]
    //                 const nameFormatted = key
    //                     .replace(/\.(\d+)/g, '[$1]')
    //                     .replace(/\.(\w+)/g, '[$1]');

    //                 // Temukan input dengan name tersebut
    //                 const input = form.find(`[name="${nameFormatted}"]`);

    //                 // Tandai field yang error
    //                 if (input.length) {
    //                     input.addClass('is-invalid');

    //                     // Tambahkan invalid feedback di bawahnya
    //                     if (input.next('.invalid-feedback').length === 0) {
    //                         input.after(`<div class="invalid-feedback">${errorMessage}</div>`);
    //                     }
    //                 }
    //             }

    //             alertMessage('Periksa kembali input yang masih salah.', 'error');
    //         }
    //     });
    // }

    // function setSelect2Validation(selectElement, valid = false, message = '') {
    //     const $select = $(selectElement);
    //     const $container = $select.next('.select2-container');

    //     // Hapus class sebelumnya
    //     $select.removeClass('is-invalid is-valid');
    //     $container.removeClass('select2-invalid select2-valid');
    //     $select.nextAll('.invalid-feedback').remove();

    //     // Tambahkan class & feedback baru
    //     if (valid === false) {
    //         $select.addClass('is-invalid');
    //         $container.addClass('select2-invalid');

    //         if (message) {
    //             $select.after(`<div class="invalid-feedback">${message}</div>`);
    //         }
    //     } else if (valid === true) {
    //         $select.addClass('is-valid');
    //         $container.addClass('select2-valid');
    //     }
    // }

    // function saveData() {
    //     const form = $('#form-tambahPemetaan');
    //     const formData = form.serialize();

    //     // Bersihkan error sebelumnya
    //     form.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
    //     form.find('.invalid-feedback').remove();
    //     form.find('.select2-container').removeClass('select2-invalid select2-valid');

    //     $.ajax({
    //         type: "POST",
    //         url: "<?= base_url('pemetaan/store') ?>",
    //         data: formData,
    //         dataType: "json",
    //         success: function(response) {
    //             if (response.status === 'success') {
    //                 // Validasi sukses, tandai field valid
    //                 setSelect2Validation('#lokasi', true);
    //                 setSelect2Validation('#petugas', true);

    //                 alertMessage(response.message || 'Data berhasil disimpan!', 'success');
    //                 setTimeout(() => {
    //                     window.location.href = '/pemetaan';
    //                 }, 1600);
    //             }
    //         },
    //         error: function(xhr) {
    //             const res = xhr.responseJSON;
    //             if (!res || !res.errors) {
    //                 alertMessage('Terjadi kesalahan tidak terduga.', 'error');
    //                 return;
    //             }

    //             const errors = res.errors;

    //             // Lokasi & Petugas
    //             if (errors.lokasi) {
    //                 setSelect2Validation('#lokasi', false, errors.lokasi);
    //             }

    //             if (errors.petugas) {
    //                 setSelect2Validation('#petugas', false, errors.petugas);
    //             }

    //             // Aktivitas (Repeater)
    //             Object.keys(errors).forEach(key => {
    //                 if (!key.startsWith('list_aktivitas')) return;

    //                 const parts = key.split('.');
    //                 const indexAktivitas = parseInt(parts[1]);
    //                 const field = parts[2];
    //                 const indexWaktu = parts[3];
    //                 const subfield = parts[4];
    //                 const message = errors[key];

    //                 const aktivitasItem = $('[data-repeater-list="list_aktivitas"] > [data-repeater-item]').eq(indexAktivitas);

    //                 if (field === 'ruangan') {
    //                     setSelect2Validation(aktivitasItem.find('[name="ruangan"]'), false, message);
    //                 }

    //                 if (field === 'aktivitas') {
    //                     const input = aktivitasItem.find('[name="aktivitas"]');
    //                     input.addClass('is-invalid');
    //                     input.after(`<div class="invalid-feedback">${message}</div>`);
    //                 }

    //                 if (field === 'periodik') {
    //                     setSelect2Validation(aktivitasItem.find('[name="periodik"]'), false, message);
    //                 }

    //                 if (field === 'standar') {
    //                     const input = aktivitasItem.find('[name="standar"]');
    //                     input.addClass('is-invalid');
    //                     input.after(`<div class="invalid-feedback">${message}</div>`);
    //                 }

    //                 // list_waktu.$index.field
    //                 if (field === 'list_waktu' && (subfield === 'waktu' || subfield === 'hari')) {
    //                     const waktuItem = aktivitasItem.find('[data-repeater-list="list_waktu"] > [data-repeater-item]').eq(indexWaktu);
    //                     const input = waktuItem.find(`[name="${subfield}"]`);
    //                     input.addClass('is-invalid');
    //                     input.after(`<div class="invalid-feedback">${message}</div>`);
    //                 }
    //             });

    //             alertMessage('Periksa kembali input yang masih kosong.', 'error');
    //         }
    //     });
    // }


    // $('#lokasi, #petugas').on('change', function() {
    //     if ($(this).val()) {
    //         setSelect2Validation(this, true);
    //     }
    // });


    // // Hapus error saat user mulai mengetik atau memilih ulang
    // $(document).on('input change', '.is-invalid', function() {
    //     $(this).removeClass('is-invalid');
    //     $(this).next('.invalid-feedback').remove();
    // });

    function saveData() {
        const form = $('#form-tambahPemetaan');
        const formData = form.serialize();

        // Bersihkan semua status validasi sebelumnya
        form.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        form.find('.invalid-feedback').remove();
        form.find('.select2-container').removeClass('select2-invalid select2-valid');

        $.ajax({
            type: "POST",
            url: "<?= base_url('pemetaan/store') ?>",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    markAllInputsValid(); // tandai hijau semua field valid
                    alertMessage(response.message || 'Data berhasil disimpan!', 'success');
                    setTimeout(() => {
                        window.location.href = '/pemetaan';
                    }, 1600);
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                if (!res || !res.errors) {
                    alertMessage('Terjadi kesalahan tidak terduga.', 'error');
                    return;
                }

                const errors = res.errors;

                // Tampilkan error dari server
                Object.keys(errors).forEach(key => {
                    const message = errors[key];

                    // Ubah ke format name HTML
                    const nameFormatted = key
                        .replace(/\.(\d+)/g, '[$1]')
                        .replace(/\.(\w+)/g, '[$1]');

                    const input = form.find(`[name="${nameFormatted}"]`);

                    if (input.length) {
                        // Untuk select2
                        if (input.is('select')) {
                            const container = input.next('.select2');
                            input.addClass('is-invalid').removeClass('is-valid');
                            container.addClass('select2-invalid').removeClass('select2-valid');

                            // Hindari duplikat invalid-feedback
                            if (input.parent().find('.invalid-feedback').length === 0) {
                                input.parent().append(`<div class="invalid-feedback">${message}</div>`);
                            }
                        } else {
                            input.addClass('is-invalid').removeClass('is-valid');
                            if (input.next('.invalid-feedback').length === 0) {
                                input.after(`<div class="invalid-feedback">${message}</div>`);
                            }
                        }
                    }
                });

                alertMessage('Periksa kembali input yang masih salah.', 'error');
            }
        });
    }

    // Tandai semua input dan select yang valid (hijau + checklist)
    function markAllInputsValid() {
        const form = $('#form-tambahPemetaan');

        form.find('input, textarea, select').each(function() {
            const el = $(this);
            if (el.val()) {
                el.removeClass('is-invalid').addClass('is-valid');
            }
        });

        // Select2
        form.find('select').each(function() {
            const el = $(this);
            const container = el.next('.select2');
            if (el.val()) {
                container.removeClass('select2-invalid').addClass('select2-valid');
            }
        });
    }

    // Validasi langsung saat user mengetik atau memilih
    $(document).on('input change', '#form-tambahPemetaan input, #form-tambahPemetaan textarea', function() {
        const el = $(this);
        if (el.val()) {
            el.removeClass('is-invalid');
            if (!el.hasClass('is-valid')) {
                el.addClass('is-valid');
            }
            el.siblings('.invalid-feedback').remove();
        }
    });

    // Select & select2
    $(document).on('change', '#form-tambahPemetaan select', function() {
        const el = $(this);
        const container = el.next('.select2');

        if (el.val()) {
            el.removeClass('is-invalid');
            if (!el.hasClass('is-valid')) {
                el.addClass('is-valid');
            }

            container.removeClass('select2-invalid');
            if (!container.hasClass('select2-valid')) {
                container.addClass('select2-valid');
            }

            el.siblings('.invalid-feedback').remove();
        }
    });




    function validate() {
        let validate = 'sukses';
        if ($("#lokasi").val() === '') {
            return 'Lokasi';
        } else if ($("#petugas").val() === '') {
            return 'Petugas';
        }
        return validate;
    }

    function validateAktivitas() {
        let validate = 'sukses';

        $('div[data-repeater-list="list_aktivitas"] > div[data-repeater-item]:visible').each(function(indexAktivitas, element) {
            const ruanganSelect = $(element).find('select[name*="[ruangan]"]');

            // Debug log untuk cek nilai ruangan
            console.log(`Aktivitas ke-${indexAktivitas + 1} - Ruangan value:`, ruanganSelect.val());

            const ruangan = ruanganSelect.val();

            const aktivitasName = $(element).find('textarea[name*="[aktivitas]"]').attr('name');
            const aktivitasEditor = ckeditors[aktivitasName];
            const aktivitasValue = aktivitasEditor ? aktivitasEditor.getData().trim() : '';

            const periodik = $(element).find('select[name*="[periodik]"]').val();
            const standar = $(element).find('input[name*="[standar]"]').val();

            const isEmptyItem = (!ruangan && !aktivitasValue && !periodik && (!standar || standar.trim() === ''));

            if (isEmptyItem) {
                return true;
            }

            if (!ruangan) {
                validate = `Ruangan belum dipilih pada aktivitas ke-${indexAktivitas + 1}`;
                return false;
            }

            if (!aktivitasValue || aktivitasValue === '<p></p>' || aktivitasValue === '') {
                validate = `Aktivitas kosong pada aktivitas ke-${indexAktivitas + 1}`;
                return false;
            }

            if (!periodik) {
                validate = `Periodik belum dipilih pada aktivitas ke-${indexAktivitas + 1}`;
                return false;
            }

            if (!standar || standar.trim() === '') {
                validate = `Standar belum diisi pada aktivitas ke-${indexAktivitas + 1}`;
                return false;
            }

            // const waktuItems = $(element).find('div[data-repeater-list="list_waktu"] > div[data-repeater-item]:visible');
            // if (waktuItems.length === 0) {
            //     validate = `Minimal 1 waktu harus diisi pada aktivitas ke-${indexAktivitas + 1}`;
            //     return false;
            // }

            // let waktuKosong = false;
            // waktuItems.each(function(indexWaktu, waktuElement) {
            //     const waktu = $(waktuElement).find('input[name*="[waktu]"]').val();
            //     if (!waktu || waktu.trim() === '') {
            //         validate = `Waktu ke-${indexWaktu + 1} kosong pada aktivitas ke-${indexAktivitas + 1}`;
            //         waktuKosong = true;
            //         return false;
            //     }
            // });

            // if (waktuKosong) return false;
        });

        return validate;
    }


    function handlePeriodikChange(item) {
        const periodikSelect = item.find('select[name*="[periodik]"]');
        const waktuList = item.find('[data-repeater-list="list_waktu"]');

        periodikSelect.on('change', function() {
            const val = $(this).val();

            waktuList.find('[data-repeater-item]').each(function() {
                const waktuWrapper = $(this).find('.waktu-wrapper');
                const waktuInput = waktuWrapper.find('.waktu');
                const hariWrapper = $(this).find('.hari-wrapper');
                const hariSelect = hariWrapper.find('.hari-select');

                if (val === "1") { // Harian
                    waktuWrapper.show();
                    initializeFlatpickr(waktuInput);

                    hariWrapper.hide();
                    hariSelect.val('').trigger('change'); // kosongkan dan reset select2
                } else if (val === "2") { // Mingguan
                    waktuWrapper.hide();
                    waktuInput.val('');

                    hariWrapper.show();

                    $('.hari-select').select2();
                }
            });
        });

        periodikSelect.trigger('change');
    }
</script>


<?= $this->endSection(); ?>