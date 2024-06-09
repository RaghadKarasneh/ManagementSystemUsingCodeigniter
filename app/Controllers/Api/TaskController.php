<?php

namespace App\Controllers\Api;

use App\Models\Task;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class TaskController extends ResourceController
{
    //   ==================================================================
    //   ======================== Index Function ==========================
    //   ==================================================================
    public function index()
    {
        try {
            $task = new Task();
            $tasks = $task->findAll();
            if (empty($tasks)) {
                $response = [
                    'status'  => ResponseInterface::HTTP_OK,
                    'message' => 'No tasks found!',
                    'data'    => $tasks
                ];
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_OK)
                    ->setJSON($response);
            }else{
                 return $this->respond($tasks);
            }
        } catch (\Exception $e) {
            $errorResponse = [
                'status'  => ResponseInterface::HTTP_BAD_REQUEST,
                'message' => 'Failed to retrieve tasks!',
                'error'   => $e->getMessage(),
            ];
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON($errorResponse);
        }
    }
}
