<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    use ApiResponseHelpers;

    public function  __construct(){
        $this->setDefaultSuccessResponse(['success' => true]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():JsonResponse
    {
        $resource = TaskResource::collection(Task::all());
        return  $this->respondWithSuccess($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request):JsonResponse
    {
        try{
            $task = new Task();
            $task->fill($request->only(array_keys($request->rules())));
            $task->save();

            $resource = new TaskResource($task);
            return $this->respondCreated($resource);

        }catch (\ErrorException $errorException){
            return $this->respondForbidden('Forbidden');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id):JsonResponse
    {
        try {
            $task = Task::find($id);
            if (!$task) return $this->respondNotFound('Not Found');

            $resource = new TaskResource($task);
            return $this->respondWithSuccess($resource);
        }catch (\ErrorException $errorException){
            return $this->respondNotFound('Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id):JsonResponse
    {
        try {
            $task = Task::find($id);
            if (!$task) return $this->respondNotFound('Not Found');

            $task->fill($request->only(array_keys($request->rules()) ));
            $task->save();

            $resource = new TaskResource($task);

            return $this->respondWithSuccess($resource);
        }catch (\ErrorException $errorException){
            return $this->respondForbidden('Forbidden');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id):JsonResponse
    {
        try {
            $task = Task::find($id);
            if (!$task) return $this->respondNotFound('Not Found');

            $task->delete();
            return $this->respondNoContent();
        }catch (\ErrorException $errorException){
            return $this->respondForbidden('Forbidden');
        }
    }
}
