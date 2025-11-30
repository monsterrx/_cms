<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $directory = '_assets/categories';

        if($request->ajax())
        {
            foreach ($categories as $category)
            {
                $icon = $this->verifyPhoto($category->icon, $directory);
                $dark_mode_icon = $this->verifyPhoto($category->dark_mode_icon, $directory);

                $category->icon = 
                    '<div class="row g-0">
                        <div class="col-2 px-1">
                            <img src="'.$icon.'" class="img-fluid">
                        </div>
                    </div>';

                $category->dark_mode_icon = 
                    '<div class="row g-0">
                        <div class="col-2 px-1">
                            <img src="'.$dark_mode_icon.'" class="img-fluid">
                        </div>
                    </div>';

                $category->created_date = 
                    ($category->created_at && $category->created_at != '0000-00-00 00:00:00')
                        ? $category->created_at->format('F d, Y')
                        : '';

                $category->options =
                    '<a href="#category-modal" data-action="edit" data-id="'.$category->id.'" data-url="'. route('categories.update', $category->id) .'" class="btn btn-outline-dark" data-toggle="modal">
                        <i class="fas fa-search"></i>
                     </a>';
            }

            return response()->json($categories);
        }

        $level = Auth::user()->Employee->Designation->level;

        if ($level === 1 || $level === 2 || $level === 3)
        {
            return view('_cms.system-views.digital.category.index',compact('categories'));
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'theme' => 'required',
        ]);

        if($validator->fails()) {
            return response()
            ->json([
                'status' => 'error', 
                'message' => $validator->errors()->all()
            ], 403);
        }

        $theme = $request['theme'];

        $path = 'images/_assets/categories';
        $directory = '_assets/categories';
        // Store the uploaded image and return the stored path or filename
        $iconPath = $this->storePhoto($request, $path, $directory);

        // Use a ternary operator for clarity, and explicitly set default behavior
        $iconKey = ($theme === 'dark') ? 'dark_mode_icon' : 'icon';

        // Assign dynamically to the request safely
        $request->merge([$iconKey => $iconPath]);

        $category = new Category($request->all());

        $category->save();

        return response()
            ->json([
                'status' => 'success', 
                'message' => 'A new category has been added'
            ], 200);
    }

    public function show($id, Request $request) {
        if ($request->ajax()) {
            $category = Category::query()->findOrFail($id);

            $directory = '_assets/categories';

            $category->icon = $this->verifyPhoto($category->icon, $directory);
            
            $category->dark_mode_icon = $this->verifyPhoto($category->dark_mode_icon, $directory);

            $category->description = 'No description available';
            
            $category->created_date = 
                ($category->created_at && $category->created_at != '0000-00-00 00:00:00')
                    ? $category->created_at->format('F d, Y')
                    : '';
            
            return response()->json([
                'category' => $category
            ]);
        }

        return redirect()->back()->withErrors('No direct script access!');
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()) {
            return redirect()
            ->back()
            ->withErrors($validator->errors()->all());
        }

        $theme = $request['theme'];

        $path = 'images/_assets/categories';
        $directory = '_assets/categories';
        // Store the uploaded image and return the stored path or filename
        $iconPath = $this->storePhoto($request, $path, $directory);

        // Use a ternary operator for clarity, and explicitly set default behavior
        $iconKey = ($theme === 'dark') ? 'dark_mode_icon' : 'icon';

        // Assign dynamically to the request safely
        $request->merge([$iconKey => $iconPath]);
        
        $category = Category::findOrfail($id);

        $category->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Category has been updated!'
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrfail($id);

        $category->delete();

        return response()->json(['status' => 'warning', 'message' => 'A category has been deleted'], 200);
    }
}
