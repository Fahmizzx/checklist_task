<?php namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController; 
use App\Modules\Auth\Models\UserModel;
use App\Libraries\EmailService;
use CodeIgniter\Controller; 
use CodeIgniter\API\ResponseTrait;

class ForgotPasswordController extends BaseController 
{
    use ResponseTrait;

    protected $userModel;
    protected $emailService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->emailService = new EmailService();
    }

    public function index()
    {
        return view('auth/forgot_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');

        $user = $this->userModel->where('email', $email)->first();

        if (empty($user) || !isset($user['id_users'])) {
            session()->setFlashdata('error_msg', 'Email tidak ditemukan atau akun tidak valid.');
            return redirect()->back()->withInput();
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->userModel->update($user['id_users'], [
            'reset_token' => $token,
            'reset_token_expires_at' => $expires,
        ]);

        $resetLink = base_url('auth/reset-password?token=' . $token);

        $subject = 'Reset Password Anda untuk Aplikasi Monitoring PT INTI';
        $message = "Halo " . $user['username'] . ",<br><br>"
                 . "Anda telah meminta reset password. Silakan klik link berikut untuk mereset password Anda:<br>"
                 . "<a href='" . $resetLink . "'>" . $resetLink . "</a><br><br>"
                 . "Link ini akan kedaluwarsa dalam 1 jam.<br>"
                 . "Jika Anda tidak meminta reset password ini, abaikan email ini.<br><br>"
                 . "Terima kasih,<br>"
                 . "Tim PT INTI";

        if ($this->emailService->sendEmail($user['email'], $subject, $message)) {
            session()->setFlashdata('success_msg', 'Link reset password telah dikirim ke email Anda. Silakan cek kotak masuk.');
        } else {
            session()->setFlashdata('error_msg', 'Gagal mengirim email reset password. Silakan coba lagi.');
        }

        return redirect()->back();
    }

    public function resetPassword()
    {
        $token = $this->request->getGet('token');
        $user = $this->userModel->where('reset_token', $token)
                                 ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
                                 ->first();

        if (empty($user) || !isset($user['id_users'])) {
            session()->setFlashdata('error_msg', 'Token reset password tidak valid atau sudah kedaluwarsa.');
            return redirect()->to(base_url('auth/forgot-password'));
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if ($password !== $confirmPassword) {
            session()->setFlashdata('error_msg', 'Konfirmasi password tidak cocok.');
            return redirect()->back()->withInput();
        }

        $user = $this->userModel->where('reset_token', $token)
                                 ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
                                 ->first();

        if (empty($user) || !isset($user['id_users'])) {
            session()->setFlashdata('error_msg', 'Token reset password tidak valid atau sudah kedaluwarsa.');
            return redirect()->to(base_url('auth/forgot-password'));
        }

        log_message('debug', 'Reset Password: User ID: ' . $user['id_users'] . ', Username: ' . $user['username']);
        log_message('debug', 'Reset Password: Plain new password: ' . $password);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        log_message('debug', 'Reset Password: Hashed new password (before update): ' . $hashedPassword);

        $this->userModel->update($user['id_users'], [
            'password' => $hashedPassword,
            'reset_token' => null,
            'reset_token_expires_at' => null,
        ]);

        session()->setFlashdata('success_msg', 'Password Anda berhasil direset. Silakan login dengan password baru.');
        return redirect()->to(base_url('auth/login'));
    }
}
