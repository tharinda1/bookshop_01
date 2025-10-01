<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthorModel extends Model
{
    protected $table = 'authors';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true; // Important: Tells CI not to use auto-increment
    protected $allowedFields = ['id', 'name'];
    protected $returnType = 'array'; // Or 'array' based on your preference
    protected $useSoftDeletes   = true; // Enable soft deletes

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function saveData($data)
    {
        $db= \Config\Database::connect();
        $builder = $db->table('authors');

        $res = $builder->insert($data);
        if($res){
            return true;
        }
        else
        {
            return false;
        }
        
    }
}
