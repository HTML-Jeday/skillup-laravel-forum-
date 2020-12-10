<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Requests\CreateTopicRequest;
use App\Http\Requests\DeleteTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Models\Topic;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Description of TopicController
 *
 * @author linux
 */
class TopicController extends Controller {

    public function index(Request $request) {

        $categoryTitle = $request->route()->id;

        $topic = Topic::query()->where('id', $categoryTitle)->first();

        if (!$topic) {
            return abort(404);
        }

        $userInfo = User::query()->where('id', $topic->author)->first();

        if (!$userInfo) {
            return abort(404);
        }

        $users = User::query()->get();

        $subcategory = Subcategory::query()->where('id', $topic->parent_id)->first();


        //dd($topic->messages);

        return view('topic', ['topic' => $topic,
            'users' => $users,
            'subcategory' => $subcategory]);
    }

    public function admin(Request $request) {
        $subcategories = Subcategory::query()->get();
        $topics = Topic::query()->get();
        $user = Auth::user();
        $users = User::query()->get();

        return view('admin.topic',
                [
                    'subcategories' => $subcategories,
                    'topics' => $topics,
                    'users' => $users
        ]);
    }

    public function create(CreateTopicRequest $request) {

        $validated = $request->validated();

        $topic = Topic::query()->where('title', $validated['title'])
                ->where('parent_id', $validated['parent_id'])
                ->first();


        if ($topic) {
            if ($topic->parent_id == $validated['parent_id']) {
                return back()->with(['error' => 'topic is already exist'], Response::HTTP_BAD_REQUEST);
            }
        }

        $subcategory = Subcategory::query()->where('id', $validated['parent_id'])->first();

        if (!$subcategory) {
            return back()->with('subcategory', ['error' => 'subcategory do not exist'], Response::HTTP_BAD_REQUEST);
        }

        $userId = Auth::user()->id ?? null;

        $user = User::query()->where('id', $userId)->first();

        if (!$user) {
            return back()->with('subcategory', ['error' => 'user do not exist'], Response::HTTP_BAD_REQUEST);
        }

        if ($validated['opened'] == 0) {
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
                return back()->with(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
            }
        }

        $topicItem = new Topic();

        $topicItem->title = $validated['title'];
        $topicItem->author = $validated['author'] ?? Auth::user()->id;
        $topicItem->parent_id = $validated['parent_id'];
        $topicItem->opened = $validated['opened'];
        $topicItem->text = $validated['text'];
        $topicItem->save();


        return back()->with(['success' => 'topic has been created'], Response::HTTP_CREATED);
    }

    public function delete(DeleteTopicRequest $request) {
        $validated = $request->validated();

        $topic = Topic::query()->where('id', $validated['id'])->first();

        if (!$topic) {
            return back()->with(['error' => 'topic do not exist'], Response::HTTP_NOT_FOUND);
        }

        $user = User::query()->where('id', $topic->author)->first();


        if (Auth::user()->role == 'user' || Auth::user()->role == 'moderator') {
            if ($topic->author !== Auth::user()->id) {
                if ($user->role == 'moderator' || $user->role == 'admin') {
                    return back()->with(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
                }
            }
        }


        $topic->delete();

        return back()->with(['success' => 'topic has been deleted'], Response::HTTP_ACCEPTED);
    }

    public function update(UpdateTopicRequest $request) {

        $validated = $request->validated();


        $topic = Topic::query()->where('id', $validated['id'])->first();

        if (!$topic) {
            return back()->with(['error' => 'topic do not exist'], Response::HTTP_NOT_FOUND);
        }


        if ($validated['opened'] == 0) {
            if (Auth::user()->role !== "admin" && Auth::user()->role !== 'moderator') {
                return back()->with(['error' => 'you do not have premission role'], Response::HTTP_FORBIDDEN);
            }
        }

        if ($topic->author !== Auth::user()->id) {
            return back()->with(['error' => 'you do not have premission user id'], Response::HTTP_FORBIDDEN);
        }


        $title = $validated['title'] ?? $topic->title;
        $parent_id = $validated['parent_id'] ?? $topic->parent_id;
        $text = $validated['text'] ?? $topic->text;
        $opened = $validated['opened'] ?? $topic->opened;

        $subcategory = Subcategory::query()->where('id', $parent_id);

        if (!$subcategory) {

            return back()->with(['error' => 'parent category do not exist'], Response::HTTP_NOT_FOUND);
        }

        if ($title == $topic->title && $parent_id == $topic->parent_id && $text == $topic->text && $opened == $topic->opened) {
            return back()->with(['ok' => 'nothing to update'], Response::HTTP_OK);
        }

        $uniqueTopic = Topic::query()->where('title', $validated['title'])->first();

        if ($uniqueTopic) {
            if ($uniqueTopic->id !== $topic->id) {
                return back()->with(['error' => 'topic is already exist'], Response::HTTP_BAD_REQUEST);
            }
        }

        $topic->title = $title;
        $topic->parent_id = $parent_id;
        $topic->opened = $validated['opened'];
        $topic->text = $text;
        $topic->save();

        return back()->with(['success' => 'topic has been updated'], Response::HTTP_ACCEPTED);
    }

}
