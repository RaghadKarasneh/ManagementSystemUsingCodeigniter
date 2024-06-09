<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $task_data = [];
        
        $startDate = date('Y-m-d');
        for ($i=1; $i <= 5 ; $i++) { 
            $dueDate = date('Y-m-d', strtotime($startDate . ' + ' . (($i - 1) * 2) . ' days')); // To create different and dynamic due_date in each loop
            $task_data['title'] = 'task' . $i;
            $task_data['description'] = 'Task ' . $i . 'represents a simple task that the user must answer it to move to the next step';
            $task_data['status'] = 'pending';
            $task_data['due_date'] = $dueDate;
            $task_data['created_at'] = date('Y-m-d H:i:s'); // Set the current timestamp
            $task_data['updated_at'] = date('Y-m-d H:i:s'); 
            // To insert the records of the current loop in the database as a one row 
            $this->db->table('tasks')->insert($task_data);
        }
    }
}
