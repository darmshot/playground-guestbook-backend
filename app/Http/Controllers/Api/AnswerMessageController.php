<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageAnswered;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAnswerMessageRequest;
use App\Models\Message;

class AnswerMessageController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswerMessageRequest $request, string $id): \Illuminate\Http\Response
    {
        $this->authorize(request()->route()->getName());

        $messageId = Message::query()->where('id', $id)->update($request->validated());

        MessageAnswered::dispatch(Message::query()->find($messageId));

        return response()->noContent();
    }
}
