<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ContentController extends Controller
{
    public function index()
    {
        return redirect()->back();
    }

    public function create($article_id)
    {
        $data = array('article_id' => $article_id );
        return view('_cms.system-views.digital.articles.sub_content.create', compact('data'));
    }

    public function store($article_id ,Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
            'article_id' => 'required'
        ]);

        Content::create($request->all());

        Session::flash('success', 'Sub content has been successfully added');
        return redirect()->route('articles.show', $article_id);
    }

    public function show($article_id, $subcontent_id)
    {
        $subcontent = Content::findOrfail($subcontent_id);
        $article = Article::findOrfail($article_id);

        $data = array('content' => $subcontent, 'article' => $article);

        $level = Auth::user()->Employee->Designation->level;
        if ($level === 1 || $level === 2 || $level === 3) {
            return view('_cms.system-views.digital.articles.sub_content.index',compact('data'));
        }

        return redirect()->back()->withErrors('Restricted Access');
    }

    public function edit($id)
    {
        //
    }

    public function update($article_id, $subcontent_id, Request $request)
    {
        $this->validate($request, [
            'content' => 'required|min:50'
        ]);

        $subcontent = Content::findOrfail($subcontent_id);

        $subcontent->update($request->all());

        Session::flash('success', 'Sub content has been successfully updated');
        return redirect()->route('articles.show', $article_id);
    }

    public function destroy($article_id, $subcontent_id)
    {
        $subcontent = Content::findOrfail($subcontent_id);

        $subcontent->delete();

        Session::flash('success', 'Sub content has been successfully deleted');
        return redirect()->route('articles.show', $article_id);
    }
}
