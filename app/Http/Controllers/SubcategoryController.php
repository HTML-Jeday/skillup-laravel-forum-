<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubcategoryRequest;
use App\Http\Requests\DeleteSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use \Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\User;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Description of SubcategoryController
 *
 * @author linux
 */
class SubcategoryController extends Controller {

    public function index(Request $request) {

        $categoryTitle = $request->route()->id;


        $user = Auth::user();

        $users = User::query()->get();

        $subcategory = Subcategory::query()->where('id', $categoryTitle)->first();
        if (!$subcategory) {
            return abort(404);
        }


        return view('subcategory', ['subcategory' => $subcategory,
            'userName' => $user->name ?? null,
            'users' => $users]);
    }

    public function create(CreateSubcategoryRequest $request) {

        $validated = $request->validated();

        $subcategory = Subcategory::query()->where('title', $validated['title'])->first();

        if ($subcategory) {
            return response()->json(['error' => 'subcategory exist'], Response::HTTP_BAD_REQUEST);
        }


        $category = Category::query()->where('id', $validated['parent_id'])->first();

        if (!$category) {
            return response()->json(['error' => 'category do not exist'], Response::HTTP_BAD_REQUEST);
        }

        $subcategoryItem = new Subcategory();
        $subcategoryItem->title = $validated['title'];
        $subcategoryItem->parent_id = $validated['parent_id'];

        $subcategoryItem->save();

        return response()->json(['success' => 'subcategory has been created'], Response::HTTP_CREATED);
    }

    public function delete(DeleteSubcategoryRequest $request) {
        $validated = $request->validated();

        $subcategory = Subcategory::query()->where('id', $validated['id'])->first();

        if (!$subcategory) {
            return response()->json(['error' => 'subcategory do not exist'], Response::HTTP_NOT_FOUND);
        }

        $subcategory->delete();

        return response()->json(['success' => 'subcategory has been deleted'], Response::HTTP_ACCEPTED);
    }

    public function update(UpdateSubcategoryRequest $request) {
        $validated = $request->validated();


        $subcategory = Subcategory::query()->where('id', $validated['id'])->first();

        if (!$subcategory) {
            return response()->json(['error' => 'subcategory do not exist'], Response::HTTP_NOT_FOUND);
        }


        $title = $validated['title'] ?? $subcategory->title;
        $parent_id = $validated['parent_id'] ?? $subcategory->parent_id;


        $category = Category::query()->where('id', $parent_id)->first();

        if (!$category) {
            return response()->json(['error' => 'category do not exist'], Response::HTTP_NOT_FOUND);
        }

        if ($title == $subcategory->title && $parent_id == $subcategory->parent_id) {
            return response()->json(['ok' => 'nothing to update'], Response::HTTP_OK);
        }


        $uniqueSubcategory = Subcategory::query()->where('title', $validated['title'])->first();

        if ($uniqueSubcategory) {
            return response()->json(['error' => 'subcategory must be unique'], Response::HTTP_BAD_REQUEST);
        }

        $subcategory->title = $title;
        $subcategory->parent_id = $parent_id;

        $subcategory->save();

        return response()->json(['success' => 'subcategory has been updated'], Response::HTTP_ACCEPTED);
    }

}
