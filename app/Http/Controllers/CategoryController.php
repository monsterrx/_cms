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
        $categories = Category::with('Article')
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        if($request->ajax())
        {
            foreach ($categories as $category)
            {
                if($category->description === '' || $category->description === null)
                {
                    $category->description = 'No Description Available';
                }

                $category->created_date = date('F d, Y', strtotime($category->created_at));

                $category->options =
                    '<a href="#edit-'.$category->id.'" class="btn btn-outline-dark" data-toggle="modal">
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
            'name' => 'required'
        ]);

        if($validator->passes()) {
            $category = new Category($request->all());

            $category->save();

            return response()->json(['status' => 'success', 'message' => 'A new category has been added'], 200);
        }

        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 403);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->passes()) {
            $category = Category::findOrfail($id);

            $category->update($request->all());

            Session::flash('success', 'A category has been successfully updated');
            return redirect()->route('categories.index');
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

    public function destroy($id)
    {
        $category = Category::findOrfail($id);

        $category->delete();

        return response()->json(['status' => 'warning', 'message' => 'A category has been deleted'], 200);
    }
}
