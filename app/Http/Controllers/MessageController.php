<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\DeleteMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\User;
use App\Models\Topic;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

/**
 * Description of MessageController
 *
 * @author linux
 */
class MessageController extends Controller {

    public function index() {

        $topics = Topic::query()->get();
        $messages = Message::query()->get();

        return view('message', ['topics' => $topics, 'messages' => $messages]);
    }

    public function create(CreateMessageRequest $request) {

        $validated = $request->validated();

        $authUserId = Auth::user()->id ?? null;

        $user = User::query()->where('id', $authUserId)->first();

        if (!$user) {
            return response()->json(['error' => 'user do not exist'], Response::HTTP_BAD_REQUEST);
        }

        $topic = Topic::query()->where('id', $validated['parent_id'])->first();

        if (!$topic) {
            return response()->json(['error' => 'topic do not exist'], Response::HTTP_BAD_REQUEST);
        }

        if (!$topic->opened) {
            return response()->json(['error' => 'topic is closed noone can add message'], Response::HTTP_BAD_REQUEST);
        }

        $message = new Message();

        $message->author = $user->id;
        $message->text = $validated['text'];
        $message->parent_id = $validated['parent_id'];
        $message->save();

        return response()->json(['success' => 'message has been created'], Response::HTTP_CREATED);
    }

    public function delete(DeleteMessageRequest $request) {
        $validated = $request->validated();

        $message = Message::query()->where('id', $validated['id'])->first();

        if (!$message) {
            return response()->json(['error' => 'message do not exist'], Response::HTTP_NOT_FOUND);
        }

        $user = User::query()->where('id', $message->author)->first();

        if (Auth::user()->role == 'user' || Auth::user()->role == 'moderator') {
            if ($message->author !== Auth::user()->id) {
                if ($user->role == 'moderator' || $user->role == 'admin') {
                    return response()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
                }
            }
        }

        $message->delete();
        return response()->json(['error' => 'message has been deleted'], Response::HTTP_ACCEPTED);
    }

    public function update(UpdateMessageRequest $request) {
        $validated = $request->validated();

        $message = Message::query()->where('id', $validated['id'])->first();


        if (!$message) {
            return response()->json(['error' => 'message do not exist'], Response::HTTP_NOT_FOUND);
        }

        if ($message->author !== Auth::user()->id) {
            return response()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
        }

        $messageText = $validated['text'] ?? $message->text;

        if ($message->text == $messageText) {
            return response()->json(['ok' => 'nothing to update'], Response::HTTP_OK);
        }

        $message->text = $messageText;
        $message->save();
        return response()->json(['success' => 'message has been updated'], Response::HTTP_ACCEPTED);
    }

}
