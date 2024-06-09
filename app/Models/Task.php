<?php

namespace App\Models;

use CodeIgniter\Model;

class Task extends Model
{
    protected $table            = 'tasks';
    protected $primaryKey       = 'id';
    protected $allowedFields = [
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat = 'datetime';
}
