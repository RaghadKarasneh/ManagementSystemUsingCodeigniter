<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;

class AuthFilter implements FilterInterface
{
    // To ensure the user is authenticated
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');

        if (!$header) { // Authorization header is missing
            return Services::response()
                ->setJSON(['error' => 'Authorization header required'])
                ->setStatusCode(401);
        }

        $token = explode(' ', $header)[1]; // To get the bearer token from the header

        try {
            $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256')); // Decode the token with the secret key
            $request->user = $decoded->uid; // Set the user ID in the request object
        } catch (\Exception $e) {
            return Services::response()
                ->setJSON(['error' => 'Invalid token'])
                ->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {}
}
