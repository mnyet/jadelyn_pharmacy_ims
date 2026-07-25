<?php

namespace App\Controllers;

use App\Models\Auth\AuthModel;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    public function index(): string
    {
        return view('Login');
    }

    public function loginVerify()
    {
        $params = $this->request->getPost();

        $lockoutTime = $this->session->get('lockout_time');
        if ($lockoutTime) {
            if (time() < $lockoutTime) {
                $remaining = $lockoutTime - time();
                $this->session->setFlashdata('error', "Too many failed attempts. Please wait for " . ceil($remaining/60) . " minute(s).");
                return redirect()->to('/login');
            } else {
                $this->session->remove(['lockout_time', 'login_attempts']);
            }
        }

        $response = $this->authModel->verifyLogin($params);

        if ($response) {
            $this->session->remove(['login_attempts', 'lockout_time']);
            $this->session->set([
                'isLoggedIn' => true,
                'username' => $response->username,
                'userRoleId' => $response->role_id,
                'userId' => $response->id
            ]);
            return redirect()->to('/');
        } else {
            $attempts = ($this->session->get('login_attempts') ?? 0) + 1;
            $this->session->set('login_attempts', $attempts);

            if ($attempts >= 5) {
                $penalty = time() + (15 * 60);
                $this->session->set('lockout_time', $penalty);
                $this->session->setFlashdata('error', 'Too many failed attempts. Please wait for 15 minutes.');
            } else {
                $this->session->setFlashdata('error', 'Invalid username or password. Attempt ' . $attempts . ' of 5.');
            }

            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

}
