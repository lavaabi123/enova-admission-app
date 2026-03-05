<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\{ApplicationModel, UserModel, CourseModel, AdminModel};
use CodeIgniter\HTTP\RedirectResponse;

class AdminController extends BaseController
{
    protected ApplicationModel $appModel;
    protected UserModel        $userModel;
    protected CourseModel      $courseModel;
    protected AdminModel       $adminModel;

    public function __construct()
    {
        $this->appModel    = new ApplicationModel();
        $this->userModel   = new UserModel();
        $this->courseModel = new CourseModel();
        $this->adminModel  = new AdminModel();
        helper(['form', 'url', 'app']);
    }

    public function login(): string|RedirectResponse
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login', ['title' => 'Admin Login']);
    }

    public function loginProcess(): RedirectResponse
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (! $email || ! $password) {
            return redirect()->back()->with('error', 'Email and password are required.');
        }

        $admin = $this->adminModel->verifyLogin($email, $password);

        if (! $admin) {
            return redirect()->back()->with('error', 'Invalid admin credentials.');
        }

        session()->set([
            'admin_logged_in' => true,
            'admin_id'        => $admin['id'],
            'admin_name'      => $admin['name'],
            'admin_email'     => $admin['email'],
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function logout(): RedirectResponse
    {
        session()->remove(['admin_logged_in', 'admin_name', 'admin_id', 'admin_email']);
        return redirect()->to('/admin/login');
    }

    public function dashboard(): string
    {
        $stats = [
            'total'       => $this->appModel->countAll(),
            'pending'     => $this->appModel->where('status', 'pending')->countAllResults(),
            'under_review'=> $this->appModel->where('status', 'under_review')->countAllResults(),
            'approved'    => $this->appModel->where('status', 'approved')->countAllResults(),
            'rejected'    => $this->appModel->where('status', 'rejected')->countAllResults(),
            'students'    => $this->userModel->countAll(),
        ];

        $recent = $this->appModel->getAllWithDetails(5);

        return view('admin/dashboard', [
            'title'  => 'Admin Dashboard',
            'stats'  => $stats,
            'recent' => $recent,
        ]);
    }

    public function applications(): string
    {
        $filter = $this->request->getGet('status') ?? '';
        $model  = $this->appModel->select('applications.*, courses.name as course_name, users.name as student_name, users.email')
                                  ->join('courses', 'courses.id = applications.course_id')
                                  ->join('users',   'users.id = applications.user_id')
                                  ->orderBy('applications.applied_at', 'DESC');

        if ($filter) {
            $model->where('applications.status', $filter);
        }

        $applications = $model->paginate(15);

        return view('admin/applications', [
            'title'        => 'All Applications',
            'applications' => $applications,
            'pager'        => $this->appModel->pager,
            'filter'       => $filter,
        ]);
    }

    public function updateStatus(int $id): RedirectResponse
    {
        $status  = $this->request->getPost('status');
        $remarks = $this->request->getPost('remarks') ?? '';

        $allowed = ['pending', 'under_review', 'approved', 'rejected'];
        if (! in_array($status, $allowed)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $result = $this->appModel->updateStatus($id, $status, $remarks);

        if ($result) {
            return redirect()->back()->with('success', 'Application status updated.');
        }

        return redirect()->back()->with('error', 'Failed to update status.');
    }

    public function students(): string
    {
        $students = $this->userModel->paginate(20);

        return view('admin/students', [
            'title'    => 'Registered Students',
            'students' => $students,
            'pager'    => $this->userModel->pager,
        ]);
    }
}
