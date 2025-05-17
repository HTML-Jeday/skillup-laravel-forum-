<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\DeleteMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Topic;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Message Controller
 * 
 * Handles all operations related to messages including listing, creating,
 * updating and deleting messages.
 */
class MessageController extends Controller
{
    /**
     * Display a listing of all messages for admin panel
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('admin', Message::class);
        
        // Paginate results to improve performance
        $topics = Topic::paginate(15);
        $messages = Message::with(['user', 'topic'])->paginate(15);
        $users = User::paginate(15);

        return view('admin.message', ['topics' => $topics, 'users' => $users, 'messages' => $messages]);
    }

    /**
     * Create a new message
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateMessageRequest $request)
    {
        $this->authorize('create', Message::class);
        
        $validated = $request->validated();
        $user = Auth::user();

        // Find the topic
        $topic = Topic::find($validated['parent_id']);

        if (!$topic) {
            return back()
                ->with('error', 'The selected topic does not exist')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // Check if topic is open for new messages
        if (!$topic->opened) {
            return back()
                ->with('error', 'This topic is closed and cannot receive new messages')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // Create the message
        Message::create([
            'author' => $user->id,
            'text' => $validated['text'],
            'parent_id' => $validated['parent_id']
        ]);

        return back()
            ->with('success', 'Message has been created successfully')
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Delete an existing message
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(DeleteMessageRequest $request)
    {
        $validated = $request->validated();

        // Find the message
        $message = Message::find($validated['id']);

        if (!$message) {
            return back()
                ->with('error', 'Message not found')
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        // Check authorization using policy
        $this->authorize('delete', $message);

        // Delete the message
        $message->delete();
        
        return back()
            ->with('success', 'Message has been deleted successfully')
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Update an existing message
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateMessageRequest $request)
    {
        $validated = $request->validated();

        // Find the message
        $message = Message::find($validated['id']);

        if (!$message) {
            return back()
                ->with('error', 'Message not found')
                ->withInput()
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        // Check authorization using policy
        $this->authorize('update', $message);

        $messageText = $validated['text'] ?? $message->text;

        // If no changes, return early
        if ($message->text === $messageText) {
            return back()
                ->with('info', 'No changes were made')
                ->setStatusCode(Response::HTTP_OK);
        }

        // Update the message
        $message->text = $messageText;
        $message->save();
        
        return back()
            ->with('success', 'Message has been updated successfully')
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
