<!DOCTYPE html>
<html lang="en">
<head>
	<base href="">
	<meta charset="utf-8" />
	<title>Login | Sistem Monitoring</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.bundle.css" />
	<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body id="kt_body" class="app-blank">
	<!--begin::Root-->
	<div class="d-flex flex-column flex-root" id="kt_app_root">
		<?= $this->renderSection('content') ?>
	</div>
<!--begin::Javascript-->
<!-- Global Javascript Bundle (wajib) -->
<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const passwordToggle = document.querySelector('[data-kt-password-meter-control="visibility"]');

        if (passwordToggle) {
            passwordToggle.addEventListener("click", function () {
                const input = passwordToggle.parentElement.querySelector("input");
                const eye = passwordToggle.querySelector(".bi-eye");
                const eyeSlash = passwordToggle.querySelector(".bi-eye-slash");

                if (input.type === "password") {
                    input.type = "text";
                    eye.classList.remove("d-none");
                    eyeSlash.classList.add("d-none");
                } else {
                    input.type = "password";
                    eye.classList.add("d-none");
                    eyeSlash.classList.remove("d-none");
                }
            });
        }
    });
</script> 
</body>
</html>
