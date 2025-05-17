<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubcategoryRequest;
use App\Http\Requests\DeleteSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * Subcategory Controller
 * 
 * Handles all operations related to subcategories including viewing, creating,
 * updating and deleting subcategories.
 */
class SubcategoryController extends Controller
{
    /**
     * Display a specific subcategory and its topics
     *
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subcategoryId = $request->route()->id;

        // Find the subcategory with eager loading to reduce queries
        $subcategory = Subcategory::with(['topics', 'category'])->find($subcategoryId);
        
        if ($subcategory) {
            $this->authorize('view', $subcategory);
        }

        if (!$subcategory) {
            abort(404, 'Subcategory not found');
        }

        // Get unique users who have topics in this subcategory
        // This is much more efficient than the previous implementation
        $topicAuthors = Topic::where('parent_id', $subcategoryId)
            ->select('author')
            ->distinct()
            ->pluck('author')
            ->toArray();
            
        $users = User::whereIn('id', $topicAuthors)->get();

        return view('subcategory', ['subcategory' => $subcategory, 'users' => $users]);
    }

    /**
     * Display admin panel for subcategories
     *
     * @return \Illuminate\View\View
     */
    public function admin(Request $request)
    {
        $this->authorize('admin', Subcategory::class);
        
        $categories = Category::paginate(15);
        $subcategories = Subcategory::with('category')->paginate(15);

        return view('admin.subcategory', ['categories' => $categories, 'subcategories' => $subcategories]);
    }

    /**
     * Create a new subcategory
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateSubcategoryRequest $request)
    {
        $this->authorize('create', Subcategory::class);
        
        $validated = $request->validated();

        // Check if subcategory with same title already exists
        $existingSubcategory = Subcategory::where('title', $validated['title'])
            ->where('parent_id', $validated['parent_id'])
            ->first();
            
        if ($existingSubcategory) {
            return back()
                ->with('error', 'A subcategory with this title already exists in this category')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // Verify category exists
        $category = Category::find($validated['parent_id']);

        if (!$category) {
            return back()
                ->with('error', 'The selected category does not exist')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // Create the subcategory
        Subcategory::create([
            'title' => $validated['title'],
            'parent_id' => $validated['parent_id']
        ]);

        return back()
            ->with('success', 'Subcategory has been created successfully')
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Delete an existing subcategory
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(DeleteSubcategoryRequest $request)
    {
        $validated = $request->validated();

        $subcategory = Subcategory::find($validated['id']);
        
        if ($subcategory) {
            $this->authorize('delete', $subcategory);
        }

        if (!$subcategory) {
            return back()
                ->with('error', 'Subcategory not found')
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $subcategory->delete();

        return back()
            ->with('success', 'Subcategory has been deleted successfully')
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Update an existing subcategory
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSubcategoryRequest $request)
    {
        $validated = $request->validated();

        $subcategory = Subcategory::find($validated['id']);
        
        if ($subcategory) {
            $this->authorize('update', $subcategory);
        }

        if (!$subcategory) {
            return back()
                ->with('error', 'Subcategory not found')
                ->withInput()
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        // Get updated values or use existing ones
        $title = $validated['title'] ?? $subcategory->title;
        $parent_id = $validated['parent_id'] ?? $subcategory->parent_id;

        // Verify category exists if changing
        if ($parent_id != $subcategory->parent_id) {
            $category = Category::find($parent_id);

            if (!$category) {
                return back()
                    ->with('error', 'The selected category does not exist')
                    ->withInput()
                    ->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        }

        // If no changes, return early
        if ($title === $subcategory->title && $parent_id === $subcategory->parent_id) {
            return back()
                ->with('info', 'No changes were made')
                ->setStatusCode(Response::HTTP_OK);
        }

        // Check if another subcategory with the same title exists in the same category
        $existingSubcategory = Subcategory::where('title', $title)
            ->where('parent_id', $parent_id)
            ->where('id', '!=', $subcategory->id)
            ->first();
            
        if ($existingSubcategory) {
            return back()
                ->with('error', 'A subcategory with this title already exists in this category')
                ->withInput()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // Update the subcategory
        $subcategory->title = $title;
        $subcategory->parent_id = $parent_id;
        $subcategory->save();

        return back()
            ->with('success', 'Subcategory has been updated successfully')
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
