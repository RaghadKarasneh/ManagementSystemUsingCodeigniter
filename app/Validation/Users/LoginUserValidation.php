<?php

namespace App\Validation\Users;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Validation;

class LoginUserValidation 
{
    protected $validation;

    public function __construct(Validation $validation) {
        $this->validation = $validation;
    }
    
    // Validation Rules
    public $rules = [
        'username' => 'required|min_length[5]|max_length[20]',
        'password' => 'required|min_length[8]',
    ];
    // Custom validation messages
    public $messages = [
        'username' => [
            'required' => 'The username field is required.',
            'min_length' => 'The username long must be at least {param} characters.',
            'max_length' => 'The username long must be less than {param} characters.',
        ],
        'password' => [
            'required' => 'The password field is required.',
            'min_length' => 'The password long must be at least {param} characters.'
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