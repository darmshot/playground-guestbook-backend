<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Services\Auth\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $collection = Message::query()->with(['author'])->orderByDesc('created_at')->paginate();

        return JsonResource::collection($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request): \Illuminate\Http\Response
    {
        $this->authorize(request()->route()->getName());

        $message = Message::query()->create([...$request->validated(), 'user_id' => $request->user()->id]);
        $message->load('author');

        MessageCreated::dispatch($message);

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResource
    {
        $this->authorize(request()->route()->getName());

        return JsonResource::make(Message::query()->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, string $id): \Illuminate\Http\Response
    {
        $this->authorize(request()->route()->getName());

        Message::query()->where('id', $id)->update($request->validated());

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\Response
    {
        $this->authorize(request()->route()->getName());

        Message::query()->where('id', $id)->delete();

        return response()->noContent();
    }
}
