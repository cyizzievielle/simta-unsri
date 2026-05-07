<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->getByEmail($email);

        if (! $user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak ditemukan.');
        }

        if ((int) $user['is_active'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'Akun tidak aktif.');
        }

        if (! password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah.');
        }

        session()->set([
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'foto'       => $user['foto'] ?? null,
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/dashboard');
    }
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

public function sendResetLink()
{
    $email = trim((string) $this->request->getPost('email'));

    if ($email === '') {
        return redirect()->back()->with('error', 'Email wajib diisi.');
    }

    $db = \Config\Database::connect();

    $user = $db->table('users')
        ->where('email', $email)
        ->get()
        ->getRowArray();

    if (! $user) {
        return redirect()->back()->with('error', 'Email tidak ditemukan.');
    }

    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    $db->table('users')
        ->where('id', $user['id'])
        ->update([
            'reset_token'   => $token,
            'reset_expires' => $expires,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

    $resetLink = base_url('reset-password/' . $token);

    $emailService = \Config\Services::email();

    $emailService->setTo($user['email']);
    $emailService->setFrom('simtami.unsri@gmail.com', 'Sistem TA MI UNSRI');

    $emailService->setSubject('Reset Password - Sistem TA');

    $emailService->setMessage("
        <div style='font-family:Inter,sans-serif;padding:20px'>
            <h2 style='margin-bottom:10px;color:#0f172a'>
                Reset Password
            </h2>

            <p style='color:#475569;line-height:1.7'>
                Halo <strong>{$user['name']}</strong>,
            </p>

            <p style='color:#475569;line-height:1.7'>
                Klik tombol di bawah untuk mengganti password akun Sistem TA kamu.
            </p>

            <a href='{$resetLink}'
            style='
                    display:inline-block;
                    margin-top:12px;
                    padding:14px 22px;
                    background:linear-gradient(135deg,#2563eb,#7c3aed);
                    color:#fff;
                    text-decoration:none;
                    border-radius:12px;
                    font-weight:700;
            '>
                Reset Password
            </a>

            <p style='margin-top:22px;color:#64748b;font-size:13px'>
                Link ini berlaku selama 15 menit.
            </p>
        </div>
    ");

    if ($emailService->send()) {
        return redirect()->to('/forgot-password')
            ->with('success', 'Link reset password berhasil dikirim ke email.');
    }

    return redirect()->back()
        ->with('error', 'Gagal mengirim email.');
}

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }
}