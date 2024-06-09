<?php

namespace App\Validation\Tasks;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Validation;

class TaskValidation 
{
    protected $validation;

    public function __construct(Validation $validation) {
        $this->validation = $validation;
    }
    
    // Validation Rules
    public $rules = [
        'title' => 'required|string|max_length[255]',
        'description' => 'permit_empty|string',
        'status' => 'permit_empty|in_list[pending,in-progress,completed]',
        'due_date' => 'permit_empty|valid_date[Y-m-d]'
    ];
    
    // Custom validation messages
    public $messages = [
        'title' => [
            'required' => 'The title field is required.',
            'string' => 'The title field is invalid.',
            'max_length' => 'The title long must be less than {param} characters.',
        ],
        'description' => [
            'description' => 'The description field is invalid.'
        ],
        'status' => [
            'in_list' => 'The status field is invalid, it must be one of: pending,in-progress,or completed values.'
        ],
        'due_date' => [
            'in_list' => 'The due date field is invalid.',
            'validFutureDate' => 'The due date must be today or any day after today.'
        ]
    ];

    public function validate($data)
    {
       
        $this->validation->setRules($this->rules, $this->messages);
       
        if (!$this->validation->run($data)) {
            return [
                'status'   => ResponseInterface::HTTP_BAD_REQUEST,
                'messages' => 'Validation failed',
                'errors'   => $this->validation->getErrors(),
            ];
        }

        return true; // Validation passed
    }

}