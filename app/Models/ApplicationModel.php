<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationModel extends Model
{
    protected $table      = 'applications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id', 'course_id', 'application_no',
        'status', 'remarks', 'applied_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'applied_at';
    protected $updatedField  = 'updated_at';

    const STATUS_PENDING  = 'pending';
    const STATUS_REVIEW   = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';


    public function getFullApplication(int $userId): ?array
    {
        return $this->select('applications.*, courses.name as course_name, courses.code as course_code, courses.duration_years, users.name as student_name, users.email')
                    ->join('courses', 'courses.id = applications.course_id')
                    ->join('users',   'users.id = applications.user_id')
                    ->where('applications.user_id', $userId)
                    ->orderBy('applications.applied_at', 'DESC')
                    ->first();
    }

    
	public function getAllWithDetails(int $perPage = 20): array
    {
        return $this->select('applications.*, courses.name as course_name, users.name as student_name, users.email')
                    ->join('courses', 'courses.id = applications.course_id')
                    ->join('users',   'users.id = applications.user_id')
                    ->orderBy('applications.applied_at', 'DESC')
                    ->paginate($perPage);
    }

    public function generateAppNo(): string
    {
        return 'APP-' . strtoupper(date('Y')) . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    public function hasApplied(int $userId): bool
    {
        return $this->where('user_id', $userId)->countAllResults() > 0;
    }

    public function updateStatus(int $id, string $status, string $remarks = ''): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->update($id, [
            'status'  => $status,
            'remarks' => $remarks,
        ]);

        $db->transComplete();

        return $db->transStatus();
    }
}
