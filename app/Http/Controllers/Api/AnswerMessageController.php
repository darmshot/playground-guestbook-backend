<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageAnswered;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAnswerMessageRequest;
use App\Models\Message;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerMessageController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswerMessageRequest $request, string $id): JsonResource
    {
        $this->authorize(request()->route()->getName());

        Message::query()->where('id', $id)->update($request->validated());

        /** @var Message $message */
        $message = Message::query()->findOrFail($id);

        broadcast(new MessageAnswered($message))->toOthers();

        return JsonResource::make($message);
    }
}
