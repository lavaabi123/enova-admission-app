<?php

namespace App\Models;

use CodeIgniter\Model;

class BioDataModel extends Model
{
    protected $table      = 'bio_data';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id', 'dob', 'gender', 'address', 'city', 'state', 'pincode',
        'tenth_percentage', 'tenth_board', 'tenth_year',
        'twelfth_percentage', 'twelfth_board', 'twelfth_year', 'twelfth_stream',
        'cert_10th', 'cert_12th', 'photo',
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'dob'               => 'required|valid_date',
        'gender'            => 'required|in_list[male,female,other]',
        'address'           => 'required|min_length[10]',
        'city'              => 'required',
        'state'             => 'required',
        'pincode'           => 'required|regex_match[/^[0-9]{6}$/]',
        'tenth_percentage'  => 'required|decimal|greater_than[0]|less_than_equal_to[100]',
        'tenth_board'       => 'required',
        'tenth_year'        => 'required|integer|greater_than[1990]',
        'twelfth_percentage'=> 'required|decimal|greater_than[0]|less_than_equal_to[100]',
        'twelfth_board'     => 'required',
        'twelfth_year'      => 'required|integer|greater_than[1990]',
        'twelfth_stream'    => 'required|in_list[Science,Commerce,Arts]',
    ];

    public function getByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }
}
