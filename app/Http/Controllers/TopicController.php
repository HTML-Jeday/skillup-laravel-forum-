<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTopicRequest;
use App\Http\Requests\DeleteTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use App\Enums\TopicStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Topic Controller
 * 
 * Handles all operations related to topics including viewing, creating,
 * updating and deleting topics.
 */
class TopicController extends Controller
{
    /**
     * Display a specific topic and its messages
     *
     * @param Topic $topic The topic model resolved by route model binding
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function index(Topic $topic)
    {
        // Eager load messages relationship to reduce queries
        $topic->load('messages');
        
        // Get the author of the topic
        $userInfo = User::find($topic->author);
        
        if (!$userInfo) {
            abort(404, 'Topic author not found');
        }
        
        // Get all users for the view
        $users = User::all();
        
        // Get the subcategory for the topic
        $subcategory = Subcategory::find($topic->parent_id);
        
        return view('topic', ['topic' => $topic, 'users' => $users, 'subcategory' => $subcategory]);
    }

    /**
     * Display admin panel for topics
     *
     * @return \Illuminate\View\View
     */
    public function admin(Request $request)
    {
        $this->authorize('admin', Topic::class);
        
        $subcategories = Subcategory::all();
        $topics = Topic::all();
        $users = User::all();

        return view('admin.topic', ['subcategories' => $subcategories, 'topics' => $topics, 'users' => $users]);
    }

    /**
     * Create a new topic
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateTopicRequest $request)
    {
        $this->authorize('create', Topic::class);
        
        $validated = $request->validated();

        // Check if topic with same title already exists in the same subcategory
        $existingTopic = Topic::where('title', $validated['title'])
            ->where('parent_id', $validated['parent_id'])
            ->first();

        if ($existingTopic) {
            return back()
                ->with('error', 'A topic with this title already exists in this subcategory')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // Verify subcategory exists
        $subcategory = Subcategory::find($validated['parent_id']);

        if (!$subcategory) {
            return back()
                ->with('error', 'The selected subcategory does not exist')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        Auth::user();
        
        // Only admin and moderator can create closed topics
        if (isset($validated['opened']) && $validated['opened'] == 0 && !Gate::allows('manage-topics')) {
            return back()
                ->with('error', 'You do not have permission to create closed topics')
                ->withInput()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        // Create the topic
        Topic::create([
            'title' => $validated['title'],
            'author' => $validated['author'] ?? Auth::id(),
            'parent_id' => $validated['parent_id'],
            'status' => isset($validated['opened']) && $validated['opened'] ? TopicStatus::OPENED : TopicStatus::CLOSED,
            'text' => $validated['text']
        ]);

        return back()
            ->with('success', 'Topic has been created successfully')
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Delete an existing topic
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(DeleteTopicRequest $request)
    {
        $validated = $request->validated();

        // Find the topic
        $topic = Topic::find($validated['id']);

        if (!$topic) {
            return back()
                ->with('error', 'Topic not found')
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        // Check authorization using policy
        $this->authorize('delete', $topic);

        // Delete the topic
        $topic->delete();

        return back()
            ->with('success', 'Topic has been deleted successfully')
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Update an existing topic
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTopicRequest $request)
    {
        $validated = $request->validated();

        // Find the topic
        $topic = Topic::find($validated['id']);

        if (!$topic) {
            return back()
                ->with('error', 'Topic not found')
                ->withInput()
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        // Check authorization using policy
        $this->authorize('update', $topic);
        
        // Get updated values or use existing ones
        $title = $validated['title'] ?? $topic->title;
        $parent_id = $validated['parent_id'] ?? $topic->parent_id;
        $text = $validated['text'] ?? $topic->text;
        $status = isset($validated['opened']) ? 
            ($validated['opened'] ? TopicStatus::OPENED->value : TopicStatus::CLOSED->value) : 
            $topic->status;
            
        // Verify subcategory exists if changing
        if ($parent_id != $topic->parent_id) {
            $subcategory = Subcategory::find($parent_id);
            
            if (!$subcategory) {
                return back()
                    ->with('error', 'The selected subcategory does not exist')
                    ->withInput()
                    ->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        }

        // If no changes, return early
        if ($title == $topic->title && 
            $parent_id == $topic->parent_id && 
            $text == $topic->text && 
            $status == $topic->status) {
            return back()
                ->with('info', 'No changes were made')
                ->setStatusCode(Response::HTTP_OK);
        }

        // Check if another topic with the same title exists in the same subcategory
        if ($title != $topic->title || $parent_id != $topic->parent_id) {
            $existingTopic = Topic::where('title', $title)
                ->where('parent_id', $parent_id)
                ->where('id', '!=', $topic->id)
                ->first();

            if ($existingTopic) {
                return back()
                    ->with('error', 'A topic with this title already exists in this subcategory')
                    ->withInput()
                    ->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
        }

        // Update the topic
        $topic->title = $title;
        $topic->parent_id = $parent_id;
        $topic->status = $status;
        $topic->text = $text;
        $topic->save();

        return back()
            ->with('success', 'Topic has been updated successfully')
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
