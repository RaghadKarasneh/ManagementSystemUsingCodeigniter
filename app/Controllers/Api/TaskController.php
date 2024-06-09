<?php

namespace App\Controllers\Api;

use App\Models\Task;
use App\Validation\Tasks\TaskValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class TaskController extends ResourceController
{
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }
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
      //   ==================================================================
    //   ======================== Create Function =========================
    //   ==================================================================

    public function create()
    {
        try {
            // Collect requests data
            $created_data = [
                'title' => $this->request->getVar('title'),
                'description' => $this->request->getVar('description'),
                'status' => $this->request->getVar('status'),
                'due_date' => $this->request->getVar('due_date'),
            ];

            // Validate the request data
            $taskValidation = new TaskValidation($this->validation);
            $validationResponse = $taskValidation->validate($created_data);
           
            if ($validationResponse !== true) {  // To stop the create task process if any request is not match its rules
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON($validationResponse);
            }

            // Set default status to pending if its value isn't provided
            if (empty($created_data['status'])) {
                $created_data['status'] = 'pending';
            }

            $task = new Task();
            $task->insert($created_data);

            // Get the ID of the inserted task
            $id = $task->getInsertID();

            return $this->respondCreated([
                'id' => $id,
                'title' => $created_data['title'],
                'description' => $created_data['description'],
                'status' => $created_data['status'],
                'due_date' => $created_data['due_date'],
            ]);
        } catch (Exception $e) {
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
