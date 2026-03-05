<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url', 'app']);
    }

    public function login(): string|RedirectResponse
    {
        if (session()->get('student_logged_in')) {
            return redirect()->to('/student/dashboard');
        }
        return view('auth/login', ['title' => 'Student Login']);
    }

    public function loginProcess(): RedirectResponse
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->verifyLogin($email, $password);

        if (! $user) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Set session
        session()->set([
            'student_logged_in' => true,
            'user_id'           => $user['id'],
            'user_name'         => $user['name'],
            'user_email'        => $user['email'],
        ]);

        return redirect()->to('/student/dashboard')->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    public function signup(): string|RedirectResponse
    {
        if (session()->get('student_logged_in')) {
            return redirect()->to('/student/dashboard');
        }
        return view('auth/signup', ['title' => 'Create Account']);
    }

    public function signupProcess(): RedirectResponse
    {
        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone'            => 'required|regex_match[/^[0-9]{10}$/]',
            'password'         => 'required|min_length[8]|regex_match[/^(?=.*[A-Za-z])(?=.*\d).+$/]',
            'confirm_password' => 'required|matches[password]',
        ];

        $messages = [
            'email'    => ['is_unique'    => 'This email is already registered.'],
            'password' => ['regex_match'  => 'Password must contain letters and numbers.'],
            'confirm_password' => ['matches' => 'Passwords do not match.'],
            'phone'    => ['regex_match'  => 'Phone must be exactly 10 digits.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),
            'password' => $this->request->getPost('password'),
        ]);

        if (! $userId) {
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }

        // Auto-login after signup
        $user = $this->userModel->find($userId);
        session()->set([
            'student_logged_in' => true,
            'user_id'           => $user['id'],
            'user_name'         => $user['name'],
            'user_email'        => $user['email'],
        ]);

        return redirect()->to('/student/biodata')->with('success', 'Account created! Please complete your profile.');
    }

    public function logout(): RedirectResponse
    {
        session()->remove(['student_logged_in', 'user_id', 'user_name', 'user_email']);
		//session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }
}
