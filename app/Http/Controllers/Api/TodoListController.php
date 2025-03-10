<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TodoListResource;
use App\Models\TodoList;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\TodoList\StoreTodoListRequest;
use App\Http\Requests\Api\TodoList\UpdateTodoListRequest;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    /**
     * Display a listing of the user's todo lists.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $todoLists = TodoList::where('user_id', Auth::id())->get();

        return response()->json([
            'data' => $todoLists
        ]);
    }

    /**
     * Store a newly created todo list in storage.
     *
     * @param StoreTodoListRequest $request
     * @return JsonResponse
     */
    public function store(StoreTodoListRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $todoList = TodoList::create($validated);

        return response()->json([
            'message' => 'Todo list created successfully',
            'data' => new TodoListResource($todoList)
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTodoListRequest $request
     * @return JsonResponse
     */
    public function update(UpdateTodoListRequest $request, $id): JsonResponse
    {
        try {
            $todoList = TodoList::findOrFail($id);

            if ($todoList->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to todo list'
                ], 403);
            }

            $todoList->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Todo list updated successfully',
                'data' => new TodoListResource($todoList)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update todo list',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified todo list from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $todoList = TodoList::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$todoList) {
            return response()->json([
                'message' => 'Todo list not found or you are not authorized to delete it'
            ], 404);
        }

        $todoList->delete();

        return response()->json([
            'message' => 'Todo list deleted successfully'
        ], 200);
    }
}
