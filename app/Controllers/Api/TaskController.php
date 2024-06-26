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
                'message' => 'Task creation failed!',
                'error'   => $e->getMessage(),
            ];
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON($errorResponse);
        }
    }

    //   ==================================================================
    //   ======================== Show Function ===========================
    //   ==================================================================

    public function show($id = null)
    { 
        try {
            $task = new Task();
            $task = $task->find($id);
            
            if ($task === null) { // Check if the task exists
                return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['error' => 'Task not found']);
            }

            return $this->respond($task);
        } catch (Exception $e) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)->setJSON(['error' => 'An error occurred while retrieving the task']);
        }
    }
    //   ==================================================================
    //   ======================= Update Function ==========================
    //   ==================================================================
    public function update($id = null)
    {
        try {
            // Collect request data
            $updated_data = [
                'title' => $this->request->getVar('title'),
                'description' => $this->request->getVar('description'),
                'status' => $this->request->getVar('status'),
                'due_date' => $this->request->getVar('due_date'),
            ];

            // Validate the request data
            $taskValidation = new TaskValidation($this->validation);
            $validationResponse = $taskValidation->validate($updated_data);
            
            if ($validationResponse !== true) {// Stop the update task process if any request does not match its rules
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                    ->setJSON($validationResponse);
            }

            $task = new Task();
            $existingTask = $task->find($id);
            
            if (!$existingTask) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                    ->setJSON(['message' => 'Task not found']);
            }

            $task->update($id, $updated_data);

            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_OK)
                ->setJSON([
                    'id' => $id,
                    'title' => $updated_data['title'],
                    'description' => $updated_data['description'],
                    'status' => $updated_data['status'],
                    'due_date' => $updated_data['due_date'],
                ]);
        } catch (Exception $e) {
            $errorResponse = [
                'status'  => ResponseInterface::HTTP_BAD_REQUEST,
                'message' => 'Update failed!',
                'error'   => $e->getMessage(),
            ];
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON($errorResponse);
        }
    }
     //   ==================================================================
    //   ======================= Delete Function ==========================
    //   ==================================================================
    public function delete($id = null)
    {
        try {
            $task = new Task();
            $existingTask = $task->find($id);

            if (!$existingTask) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                    ->setJSON(['message' => 'Task not found']);
            }

            $task->delete($id);

            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            // Handle exceptions
            $errorResponse = [
                'status'  => ResponseInterface::HTTP_BAD_REQUEST,
                'message' => 'Delete failed!',
                'error'   => $e->getMessage(),
            ];
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON($errorResponse);
        }
    }
}
