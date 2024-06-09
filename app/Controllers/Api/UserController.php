<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\User;
use App\Validation\Users\LoginUserValidation;
use App\Validation\Users\RegisterUserValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Firebase\JWT\JWT;

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
    //   ==================================================================
//   ======================== Login Function ==========================
//   ==================================================================
public function login(){
    try {
        // Collect request data
        $created_data = [
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password'),
        ];

        // Validate the login data
        $loginValidation = new LoginUserValidation($this->validation);
        $validationResponse = $loginValidation->validate($created_data);

        // To stop the login process if any request is not match its rules 
        if ($validationResponse !== true) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON($validationResponse);
        }

        // Retrieve user by username
        $user = (new User())->where('username', $created_data['username'])->first();

        // Check if user exists and password matches
        if ($user && password_verify($created_data['password'], $user['password'])) {
            
            $key = getenv('JWT_SECRET'); 
            $iat = time(); // Issued at time => Unix timestamp
            $payload = [
                'iat' => $iat,
                'exp' => $iat + 3600, // Expiration time => Unix timestamp
                'uid' => $user['id'],
            ];
            $token = JWT::encode($payload, $key, 'HS256'); // Generate JWT token

            $response = [
                'status'   => 200,
                'token'    => $token
            ];
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_OK)
                ->setJSON($response);
        } else {
            // Handle invalid username or password
            $response = [
                'status'  => ResponseInterface::HTTP_UNAUTHORIZED,
                'messages' => 'Invalid username or password',
            ];

            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON($response);
        }
    } catch (Exception $e) {
        // Handle exceptions
        $errorResponse = [
            'status'  => ResponseInterface::HTTP_BAD_REQUEST,
            'message' => 'Login failed!',
            'error'   => $e->getMessage(),
        ];
        return $this->response
            ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
            ->setJSON($errorResponse);
    }
}
}
