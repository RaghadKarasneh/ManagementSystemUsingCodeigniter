<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\User;
use App\Validation\Users\RegisterUserValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class UserController extends BaseController
{
    protected $validation;

    public function __construct()
    {
        // To initialize the validation service
        $this->validation = \Config\Services::validation();
    }
    //   ==================================================================
    //   ====================== Register Function =========================
    //   ==================================================================
    public function register()
    {
        try {
            // Collect requests
            $created_data = [
                'username' => $this->request->getVar('username'),
                'password' => $this->request->getVar('password'),
            ];
            // Start validate the requests
            $userValidation = new RegisterUserValidation($this->validation);
            $validationResponse = $userValidation->validate($created_data);

            if ($validationResponse !== true) { // To stop the register process if any request is not match its rules (validation failed)
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON($validationResponse);
            }
            // End validate the requests, all requests are correct, the registration process will continue

            $user = new User();
            $created_data['password'] = password_hash($created_data['password'], PASSWORD_DEFAULT);
          
            
            if ($user->insert($created_data)) { // Create new user
                $response = [
                    'status'   => 200,
                    'messages' => 'User registered successfully',
                ];
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_OK)
                    ->setJSON($response);
            } else {
                $response = [
                    'status'  => ResponseInterface::HTTP_BAD_REQUEST,
                    'messages' => 'Failed to register user',
                    'errors'   => $user->errors(),
                ];

                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON($response);
            }
        } catch (Exception $e) {
            $errorResponse = [
                'status'  => ResponseInterface::HTTP_BAD_REQUEST,
                'message' => 'Register failed!',
                'error'   => $e->getMessage(),
            ];
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON($errorResponse);
        }
    }
}
