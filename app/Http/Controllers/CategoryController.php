<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Response;

/**
 * Description of CategoryController
 *
 * @author linux
 */
class CategoryController extends Controller {

    public function index() {

        $categories = Category::query()->get();

        return view('category', ['categories' => $categories]);
    }

    public function create(CreateCategoryRequest $request) {

        $validated = $request->validated();

        $category = Category::query()->where('title', $validated['title'])->first();

        if ($category) {
            return response()->json(['error' => 'category already exist'], Response::HTTP_BAD_REQUEST);
        }


        $categoryItem = new Category();

        $categoryItem->title = $validated['title'];
        $categoryItem->save();


        return response()->json(['sucess' => 'category has been created'], Response::HTTP_CREATED);
    }

    public function delete(DeleteCategoryRequest $request) {

        $validated = $request->validated();


        $category = Category::query()->where('id', $validated['id'])->firstOrFail();

        if (!$category) {
            return response()->json(['error' => 'category do not exist!'], Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return response()->json(['success' => 'category has been deleted!'], Response::HTTP_ACCEPTED);
    }

    public function update(UpdateCategoryRequest $request) {

        $validated = $request->validated();


        $category = Category::query()->where('id', $validated['id'])->first();

        if (!$category) {
            return response()->json(['error' => 'category do not exist!'], Response::HTTP_NOT_FOUND);
        }

        $uniqueCategory = Category::query()->where('title', $validated['title'])->first();

        if ($uniqueCategory) {
            return response()->json(['error' => 'category already exist'], Response::HTTP_BAD_REQUEST);
        }



        if ($category->title == $validated['title']) {
            return response()->json(['ok' => 'nothing to update!'], Response::HTTP_OK);
        }


        $category->title = $validated['title'];
        $category->save();

        return response()->json(['success' => 'category has been updated!'], Response::HTTP_ACCEPTED);
    }

}
