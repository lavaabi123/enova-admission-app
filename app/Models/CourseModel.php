<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table      = 'courses';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'name', 'code', 'stream', 'min_percentage',
        'duration_years', 'seats', 'description', 'is_active'
    ];

    public function getEligibleCourses(string $stream, float $percentage): array
    {
        return $this->where('is_active', 1)
                    ->groupStart()
                        ->where('stream', $stream)
                        ->orWhere('stream', 'All')
                    ->groupEnd()
                    ->where('min_percentage <=', $percentage)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getActiveCourses(): array
    {
        return $this->where('is_active', 1)->findAll();
    }
}
