<?php

namespace App\Http\Controllers\API;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditTaskItemRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    //

    public function addTask(TaskRequest $request)
    {
        $authUser = Auth::user();
        if (!$authUser) {
            return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
        }

        $taskData = [
            'task_name' => $request->task_name,
            'task' => $request->task,
            'user_id' => $authUser->id
        ];

        if ($request->has('task_status')) {
            $taskData['task_status'] = $request->task_status;
        }

        try {
            // create Task
            Task::create($taskData);
            return   ResponseHelper::success(message: 'Task Created', statusCode: 200);
        } catch (Exception $e) {
            Log::error('Unable to create task', [$e->getMessage() . 'Line no' . $e->getLine()]);
            return   ResponseHelper::error(message: 'Something went wrong, please try again' . $e->getMessage(), statusCode: 500);
        }
    }

    public function updateTaskStatus(UpdateTaskRequest $request)
    {

        $authUser = Auth::user();

        if (!$authUser) {
            return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
        }

        try {
            $task = $authUser->tasks()->where('id', $request->id)->first();
            $task['task_status'] = $request->task_status;
            $task->update();
            return   ResponseHelper::success(message: 'Task Status Updated!', statusCode: 200);
        } catch (Exception $e) {
            Log::error('Unable to update task  status', [$e->getMessage() . 'Line no' . $e->getLine()]);
            return   ResponseHelper::error(message: 'Something went wrong, please try again' . $e->getMessage(), statusCode: 500);
        }
    }

    public function editTaskItem(EditTaskItemRequest $request)
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
        }

        try {
            $task = $authUser->tasks()->where('id', $request->id)->first();
            $task['task'] = $request->task;
            $task->update();
            return   ResponseHelper::success(message: 'Task Updated!', statusCode: 200);
        } catch (Exception $e) {
            Log::error('Unable to update task  status', [$e->getMessage() . 'Line no' . $e->getLine()]);
            return   ResponseHelper::error(message: 'Something went wrong, please try again' . $e->getMessage(), statusCode: 500);
        }
    }

    public function allTask()
    {
        $authUser = Auth::user();
        if (!$authUser) {
            return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
        }

        try {
            $tasks = $authUser->tasks;
            return   ResponseHelper::success(message: 'Task Status Updated!', data: $tasks, statusCode: 200);
        } catch (Exception $e) {
            Log::error('Unable to fetch task', [$e->getMessage() . 'Line no' . $e->getLine()]);
            return   ResponseHelper::error(message: 'Something went wrong, please try again' . $e->getMessage(), statusCode: 500);
        }
    }
}
