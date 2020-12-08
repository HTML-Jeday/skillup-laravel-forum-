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

        $user = Auth::user();

        $userInfo = User::query()->where('id', $topic->author)->first();

        if (!$userInfo) {
            return abort(404);
        }

        $users = User::query()->get();

        $subcategory = Subcategory::query()->where('id', $topic->parent_id)->first();

        return view('topic', ['topic' => $topic,
            'userName' => $user->name ?? null,
            'userRole' => $user->role,
            'users' => $users,
            'subcategory' => $subcategory]);
    }

    public function create(CreateTopicRequest $request) {


        $validated = $request->validated();

        $topic = Topic::query()->where('title', $validated['title'])
                ->where('parent_id', $validated['parent_id'])
                ->first();


        if ($topic) {
            if ($topic->parent_id == $validated['parent_id']) {
                return response()->json(['error' => 'topic is already exist'], Response::HTTP_BAD_REQUEST);
            }
        }

        $subcategory = Subcategory::query()->where('id', $validated['parent_id'])->first();

        if (!$subcategory) {
            return response()->json(['error' => 'subcategory do not exist'], Response::HTTP_BAD_REQUEST);
        }

        $userId = Auth::user()->id ?? null;

        $user = User::query()->where('id', $userId)->first();

        if (!$user) {
            return response()->json(['error' => 'user do not exist'], Response::HTTP_BAD_REQUEST);
        }

        if ($validated['opened'] == 0) {
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
                return response()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
            }
        }

        $topicItem = new Topic();

        $topicItem->title = $validated['title'];
        $topicItem->author = $validated['author'] ?? Auth::user()->id;
        $topicItem->parent_id = $validated['parent_id'];
        $topicItem->opened = $validated['opened'];
        $topicItem->text = $validated['text'];
        $topicItem->save();


        return response()->json(['success' => 'topic has been created'], Response::HTTP_CREATED);
    }

    public function delete(DeleteTopicRequest $request) {
        $validated = $request->validated();

        $topic = Topic::query()->where('id', $validated['id'])->first();

        if (!$topic) {
            return response()->json(['error' => 'topic do not exist'], Response::HTTP_NOT_FOUND);
        }

        $user = User::query()->where('id', $topic->author)->first();


        if (Auth::user()->role == 'user' || Auth::user()->role == 'moderator') {
            if ($topic->author !== Auth::user()->id) {
                if ($user->role == 'moderator' || $user->role == 'admin') {
                    return response()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
                }
            }
        }


        $topic->delete();

        return response()->json(['success' => 'topic has been deleted'], Response::HTTP_ACCEPTED);
    }

    public function update(UpdateTopicRequest $request) {

        $validated = $request->validated();


        $topic = Topic::query()->where('id', $validated['id'])->first();

        if (!$topic) {
            return response()->json(['error' => 'topic do not exist'], Response::HTTP_NOT_FOUND);
        }


        if ($validated['opened'] == 0) {
            if (Auth::user()->role !== "admin" && Auth::user()->role !== 'moderator') {
                return response()->json(['error' => 'you do not have premission role'], Response::HTTP_FORBIDDEN);
            }
        }

        if ($topic->author !== Auth::user()->id) {
            return response()->json(['error' => 'you do not have premission user id'], Response::HTTP_FORBIDDEN);
        }


        $title = $validated['title'] ?? $topic->title;
        $parent_id = $validated['parent_id'] ?? $topic->parent_id;
        $text = $validated['text'] ?? $topic->text;



        $subcategory = Subcategory::query()->where('id', $parent_id);

        if (!$subcategory) {
            return response()->json(['error' => 'parent category do not exist'], Response::HTTP_NOT_FOUND);
        }

        if ($title == $topic->title && $parent_id == $topic->parent_id && $text == $topic->text) {
            return response()->json(['ok' => 'nothing to update'], Response::HTTP_OK);
        }

        $uniqueTopic = Topic::query()->where('title', $validated['title'])->first();

        if ($uniqueTopic) {
            if ($uniqueTopic->parent_id == $validated['parent_id']) {
                return response()->json(['error' => 'topic is already exist'], Response::HTTP_BAD_REQUEST);
            }
        }

        $topic->title = $title;
        $topic->parent_id = $parent_id;
        $topic->opened = $validated['opened'];
        $topic->text = $text;
        $topic->save();

        return response()->json(['success' => 'topic has been updated'], Response::HTTP_ACCEPTED);
    }

}
