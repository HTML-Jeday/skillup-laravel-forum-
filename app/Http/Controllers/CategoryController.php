<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Category Controller
 * 
 * Handles all operations related to categories including listing, creating,
 * updating and deleting categories.
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewAny', Category::class);
        
        $categories = Category::all();

        return view('admin.category', ['categories' => $categories]);
    }

    /**
     * Create a new category
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateCategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        
        $validated = $request->validated();

        // Check if category with same title already exists
        if (Category::where('title', $validated['title'])->exists()) {
            return back()
                ->with('error', 'Category already exists')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // Create the category
        Category::create([
            'title' => $validated['title']
        ]);

        return back()
            ->with('success', 'Category has been created successfully')
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Delete an existing category
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(DeleteCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::find($validated['id']);
        
        $this->authorize('delete', $category);
        
        if (!$category) {
            return back()
                ->with('error', 'Category not found')
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return back()
            ->with('success', 'Category has been deleted successfully')
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Update an existing category
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::find($validated['id']);
        
        $this->authorize('update', $category);

        if (!$category) {
            return back()
                ->with('error', 'Category not found')
                ->withInput()
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        // No changes needed if title is the same
        if ($category->title === $validated['title']) {
            return back()
                ->with('info', 'No changes were made')
                ->setStatusCode(Response::HTTP_OK);
        }

        // Check if another category with the same title exists
        $existingCategory = Category::where('title', $validated['title'])
            ->where('id', '!=', $category->id)
            ->first();
            
        if ($existingCategory) {
            return back()
                ->with('error', 'A category with this title already exists')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $category->title = $validated['title'];
        $category->save();

        return back()
            ->with('success', 'Category has been updated successfully')
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
