<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\{BioDataModel, CourseModel, ApplicationModel, UserModel};
use CodeIgniter\HTTP\RedirectResponse;

class StudentController extends BaseController
{
    protected BioDataModel    $bioModel;
    protected CourseModel     $courseModel;
    protected ApplicationModel $appModel;

    public function __construct()
    {
        $this->bioModel   = new BioDataModel();
        $this->courseModel = new CourseModel();
        $this->appModel   = new ApplicationModel();
        helper(['form', 'url', 'upload', 'app']);
    }

    private function userId(): int
    {
        return (int) session()->get('user_id');
    }

    public function dashboard(): string
    {
        $bioData = $this->bioModel->getByUserId($this->userId());
        $application = $this->appModel->getFullApplication($this->userId());

        return view('student/dashboard', [
            'title'       => 'My Dashboard',
            'bioData'     => $bioData,
            'application' => $application,
        ]);
    }

    public function biodata(): string
    {
        $existing  = $this->bioModel->getByUserId($this->userId());
        $hasApplied = $this->appModel->hasApplied($this->userId());

        return view('student/biodata', [
            'title'      => $existing ? 'Edit Your Profile' : 'Complete Your Profile',
            'existing'   => $existing,
            'hasApplied' => $hasApplied,  
        ]);
    }

    public function biodataProcess(): RedirectResponse
    {
        $rules = [
            'dob'                => 'required|valid_date',
            'gender'             => 'required|in_list[male,female,other]',
            'address'            => 'required|min_length[10]',
            'city'               => 'required|alpha_space',
            'state'              => 'required|alpha_space',
            'pincode'            => 'required|regex_match[/^[0-9]{6}$/]',
            'tenth_percentage'   => 'required|decimal|greater_than[0]|less_than_equal_to[100]',
            'tenth_board'        => 'required|max_length[100]',
            'tenth_year'         => 'required|integer|greater_than[1990]|less_than_equal_to[' . date('Y') . ']',
            'twelfth_percentage' => 'required|decimal|greater_than[0]|less_than_equal_to[100]',
            'twelfth_board'      => 'required|max_length[100]',
            'twelfth_year'       => 'required|integer|greater_than[1990]|less_than_equal_to[' . date('Y') . ']',
            'twelfth_stream'     => 'required|in_list[Science,Commerce,Arts]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id'            => $this->userId(),
            'dob'                => $this->request->getPost('dob'),
            'gender'             => $this->request->getPost('gender'),
            'address'            => $this->request->getPost('address'),
            'city'               => $this->request->getPost('city'),
            'state'              => $this->request->getPost('state'),
            'pincode'            => $this->request->getPost('pincode'),
            'tenth_percentage'   => $this->request->getPost('tenth_percentage'),
            'tenth_board'        => $this->request->getPost('tenth_board'),
            'tenth_year'         => $this->request->getPost('tenth_year'),
            'twelfth_percentage' => $this->request->getPost('twelfth_percentage'),
            'twelfth_board'      => $this->request->getPost('twelfth_board'),
            'twelfth_year'       => $this->request->getPost('twelfth_year'),
            'twelfth_stream'     => $this->request->getPost('twelfth_stream'),
        ];

        // image uploads
        $cert10 = upload_certificate('cert_10th');
        $cert12 = upload_certificate('cert_12th');
        $photo  = upload_photo('photo');

        $existing = $this->bioModel->getByUserId($this->userId());

        // Only require uploads if no existing record
        if (! $existing) {
            if (! $cert10['success']) {
                return redirect()->back()->withInput()->with('error', '10th Certificate: ' . $cert10['error']);
            }
            if (! $cert12['success']) {
                return redirect()->back()->withInput()->with('error', '12th Certificate: ' . $cert12['error']);
            }
            if (! $photo['success']) {
                return redirect()->back()->withInput()->with('error', 'Photo: ' . $photo['error']);
            }
        }

        if ($cert10['success']) $data['cert_10th'] = $cert10['filename'];
        if ($cert12['success']) $data['cert_12th'] = $cert12['filename'];
        if ($photo['success'])  $data['photo']     = $photo['filename'];

        if ($existing) {
            $this->bioModel->update($existing['id'], $data);
            $redirectTo = $this->appModel->hasApplied($this->userId())
                ? '/student/dashboard'
                : '/student/courses';
            return redirect()->to($redirectTo)->with('success', 'Profile updated successfully.');
        } else {
            $this->bioModel->insert($data);
            return redirect()->to('/student/courses')->with('success', 'Profile saved! Select your course below.');
        }
    }

    public function courses(): string|RedirectResponse
    {
        $bio = $this->bioModel->getByUserId($this->userId());

        if (! $bio) {
            return redirect()->to('/student/biodata')->with('error', 'Please complete your profile first.');
        }

        if ($this->appModel->hasApplied($this->userId())) {
            return redirect()->to('/student/dashboard');
        }

        $courses = $this->courseModel->getEligibleCourses(
            $bio['twelfth_stream'],
            (float) $bio['twelfth_percentage']
        );

        return view('student/courses', [
            'title'   => 'Available Courses',
            'courses' => $courses,
            'bio'     => $bio,
        ]);
    }
    
    public function apply(): RedirectResponse
    {
        if ($this->appModel->hasApplied($this->userId())) {
            return redirect()->to('/student/dashboard')->with('info', 'You have already applied.');
        }

        $courseId = (int) $this->request->getPost('course_id');
        if (! $courseId) {
            return redirect()->back()->with('error', 'Please select a course.');
        }

        // Verify course exists
        $course = $this->courseModel->find($courseId);
        if (! $course) {
            return redirect()->back()->with('error', 'Invalid course selected.');
        }

        $this->appModel->insert([
            'user_id'        => $this->userId(),
            'course_id'      => $courseId,
            'application_no' => $this->appModel->generateAppNo(),
            'status'         => ApplicationModel::STATUS_PENDING,
        ]);

        return redirect()->to('/student/dashboard')->with('success', 'Application submitted successfully!');
    }
    
    public function statusCheck(): \CodeIgniter\HTTP\ResponseInterface
    {
        $application = $this->appModel->getFullApplication($this->userId());

        return $this->response->setJSON([
            'status'         => $application['status']          ?? null,
            'remarks'        => $application['remarks']         ?? '',
            'application_no' => $application['application_no']  ?? '',
            'updated_at'     => $application['updated_at']      ?? '',
        ]);
    }
}
