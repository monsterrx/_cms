<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Content;
use App\Models\Employee;
use App\Models\Photo;
use App\Models\Relevant;
use App\Models\Social;
use App\Models\User;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller {

	public function index(Request $request)
	{
	    if($request->ajax()) {
	        if($request['status'] === 'published') {
                $user_level = Auth::user()->Employee->Designation->level;

                if($user_level === 1 || $user_level === 2) {
                    $published = Article::whereNotNull('published_at')
                        ->where('location', $this->getStationCode())
                        ->whereNull('deleted_at')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(6);

                    foreach ($published as $article) {
                        $article->image = $this->verifyPhoto($article->image, 'articles');
                    }

                    $next = $published->nextPageUrl();
                    $previous = $published->previousPageUrl();

                    return view('_cms.system-views.digital.articles.published_articles', compact('published', 'next', 'previous'));
                }

                $published = Article::whereNotNull('published_at')
                    ->where('employee_id', Auth::user()->Employee->id)
                    ->where('location', $this->getStationCode())
                    ->whereNull('deleted_at')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(6);

                foreach ($published as $article) {
                    $article->image = $this->verifyPhoto($article->image, 'articles');
                }

                $next = $published->nextPageUrl();
                $previous = $published->previousPageUrl();

                return view('_cms.system-views.digital.articles.published_articles', compact('published', 'next', 'previous'));

            } elseif ($request['status'] === 'unpublished') {
                $user_level = Auth::user()->Employee->Designation->level;

                if($user_level === 1 || $user_level === 2) {
                    $unpublished = Article::whereNull('published_at')
                        ->where('location', $this->getStationCode())
                        ->whereNull('deleted_at')
                        ->orderBy('created_at', 'desc')
                        ->paginate(6);

                    foreach ($unpublished as $article) {
                        $article->image = $this->verifyPhoto($article->image, 'articles');
                    }

                    $next = $unpublished->nextPageUrl();
                    $previous = $unpublished->previousPageUrl();

                    return view('_cms.system-views.digital.articles.unpublished_articles', compact('unpublished', 'next', 'previous'));
                }

                $unpublished = Article::whereNull('published_at')
                    ->where('employee_id', Auth::user()->Employee->id)
                    ->where('location', $this->getStationCode())
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->paginate(6);

                foreach ($unpublished as $article) {
                    $article->image = $this->verifyPhoto($article->image, 'articles');
                }

                $next = $unpublished->nextPageUrl();
                $previous = $unpublished->previousPageUrl();

                return view('_cms.system-views.digital.articles.unpublished_articles', compact('unpublished', 'next', 'previous'));
            } else {
	            return view('components.errors.page-404');
            }
        }

        $category = Category::orderBy('name')->get();

		$level = Auth::user()->Employee->Designation->level;

		if ($level === 1 || $level === 2 || $level === 3) {
			return view('_cms.system-views.digital.index', compact('category'));
		}

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function store(Request $request)
	{
	    if($request->ajax()) {
	        // for uploading the cropped image and creating the file then sending it to the directory.
            if($request['croppedImage']) {
                $data = $request['croppedImage'];

                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);

                $data = base64_decode($data);
                $imageName = date('Ymd').'-'. mt_rand() . '.png';
                $path = 'images/articles/'. $imageName;

                file_put_contents($path, $data);

                return $imageName;
            }
        }

        // for uploading the cropped image in the database.
        if($request['croppedArticleImage']) {
            $article_id = $request['article_id'];
            $croppedImage = $request['croppedArticleImage'];

            try {
                $article = Article::with('Employee')->findOrFail($article_id);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error', $e], 404);
            }

            $file = 'images/articles/'.$croppedImage;
            Storage::disk('articles')->put($croppedImage, file_get_contents($file));

            $article['image'] = $croppedImage;
            $article->save();

            Session::flash('success', 'Article image has been successfully uploaded');
            return redirect()->route('articles.show', $article_id);
        }

		$validator = Validator::make($request->all(), [
            'title' => 'required',
            'heading' => 'required|min:20',
            'category_id' => 'required',
        ]);

		if($validator->passes()) {
            $img = $request->file('image');
            $path = 'images/articles';

            $article_guid = $this->IdGenerator(8);
            $request['unique_id'] = $article_guid;
            $request['location'] = $this->getStationCode();

            $new_article = new Article($request->all());

            if($img) {
                $imgname = date('Ymd') . '-' . mt_rand() . '.' .$img->getClientOriginalExtension();

                $img->move($path, $imgname);

                $file = 'images/articles/' . $imgname;
                Storage::disk('articles')->put($imgname, file_get_contents($file));

                $new_article['image'] = $this->storePhoto($request, $path, 'articles', false);
                $new_article['employee_id'] = Auth::user()->Employee->id;
                $new_article->save();

                $article = Article::where('unique_id', $article_guid)->first();

                Session::flash('success', 'An article draft has been successfully created');
                return redirect()->route('articles.show', $article['id']);
            } else {
                $new_article['image'] = 'default.png';
            }

            $new_article['employee_id'] = Auth::user()->Employee->id;
            $new_article->save();

            $article = Article::where('unique_id', $article_guid)->first();

            Session::flash('success', 'An article draft has been successfully created');
            return redirect()->route('articles.show', $article['id']);
        }

		return redirect()->back()->withErrors($validator->errors()->all());
	}

	public function show($id)
	{
        $level = Auth::user()->Employee->Designation->level;

        $article = Article::with('Category', 'Content', 'Image', 'Social', 'Relevant')
            ->findOrfail($id);

		$category = Category::orderBy('name')
            ->whereNull('deleted_at')->get();

        $articles = Article::with('Relevant')
            ->latest()
            ->where('id', '!=', $id)
            ->where('location', $this->getStationCode())
            ->whereNotNull('published_at')
            ->get();

        foreach($article->Image as $images)
        {
            $images['file'] = $this->verifyPhoto($images['file'], 'articles');
        }

        foreach ($article->Relevant as $relatedArticles) {
            $relatedArticles->RelatedArticle->image = $this->verifyPhoto($relatedArticles->RelatedArticle->image, 'articles');
        }

        $article['image'] = $this->verifyPhoto($article['image'], 'articles');

		if ($level === 1 || $level === 2 || $level === 3) {
			return view('_cms.system-views.digital.articles.show',compact('article', 'category', 'articles'));
		}

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
            'title' => 'required',
            'heading' => 'required|min:10',
            'category_id' => 'required',
        ]);

        if($validator->passes()) {
            $article = Article::findOrfail($id);

            $img = $request->file('image');
            $path = 'images/articles';

            if ($img) {
                $article['image'] =  $this->storePhoto($request, $path, 'articles', false);
                $article->save();

                Session::flash('success', 'An article has been successfully updated');
                return redirect()->route('articles.show', $article['id']);
            }

            $article->update($request->except('image'));

            Session::flash('success', 'An article has been successfully updated');
            return redirect()->route('articles.show', $article['id']);
        }

        return redirect()->back()->withErrors($validator->errors()->all());
    }

	public function destroy($id)
	{
		$article = Article::findOrfail($id);

		$article->delete();

		Session::flash('success', 'An article has been successfully deleted');
		return redirect()->route('articles.index');
	}

	public function delete(Request $request){
		try {

			$article = Article::findOrfail($request['id']);

		} catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$article->delete();

		Session::flash('success', 'An article has been successfully deleted');
		return redirect()->route('articles.index');

	}

	public function yearlyArticles(Request $request)
    {
        if($request->ajax()) {
            $year = date('Y');
            $article = Article::whereRaw('YEAR(created_at) = ?',[$year])
                ->whereNull('deleted_at')
                ->where('employee_id', Auth::user()->Employee->id)
                ->where('location', $this->getStationCode())
                ->orderBy('created_at', 'desc')
                ->get();

            return view('_cms.system-views.digital.articles.current_articles', compact('article'));
        }

        return redirect()->back()->withErrors('Restricted Access!');
    }

	public function addImage(Request $request)
    {
		$this->validate($request, [
			'file' => 'required'
        ]);

		$img = $request->file('file');

		if($img){

			foreach ($img as $images){
                $path = 'images/articles';
                $imgname = date('Ymd') . '-' . mt_rand() . '.' . $images->getClientOriginalExtension();

                $images->move($path, $imgname);

                $file = 'images/articles/' . $imgname;
                Storage::disk('articles')->put($imgname, file_get_contents($file));

                $request['file'] = $imgname;
                $request['name'] = $this->IdGenerator(4);

                $image = new Photo($request->all());

                $image->save();
            }

            Session::flash('success', 'Additional article image has been successfully added');
            return redirect()->back();
        }

        return redirect()->back()->withErrors(['Request Error', 'No File Found!']);
	}

	public function removeImage(Request $request){

		try {

			$image = Photo::findOrfail($request['id']);

		} catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$image->delete();

        Session::flash('success', 'Additional article image has been successfully deleted');
		return redirect()->back();
	}

	public function addLink(Request $request)
    {
		$this->validate($request, [
			'website' => 'required',
			'link_string' => 'required',
        ]);

		if ($request->website === 'Tweet_embeded') {

			if (!strpos($request['link_string'], 'ref_src')) {

				return redirect()->back()->withErrors(['Link is not embedded link']);
			}
		}

		if ($request->website === 'Youtube_embeded') {

			if (!strpos($request['link_string'], 'www.youtube.com/embed/')) {

				return redirect()->back()->withErrors(['Link is not embedded link']);
			}
		}

		$link = new Social($request->all());

		$link->save();

		Session::flash('success', 'An article link has been successfully added');
		return redirect()->back();
	}

	public function removeLink(Request $request){

		try {

			$link = Social::findOrfail($request['id']);

		} catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$link->delete();

		Session::flash('success', 'An article link has been successfully removed');
		return redirect()->back();

	}

	public function addRelated($article_id, Request $request)
    {
		$this->validate($request, [
			'related_article_id' => 'required'
        ]);

        $request['article_id'] = $article_id;

		$related = Relevant::with('Article')
            ->where('article_id', $article_id)
            ->where('related_article_id', $request['related_article_id'])
            ->whereNull('deleted_at')
            ->count();

		if ($related > 0) {
            return redirect()->back()->withErrors(['The article is already related to the content']);
        }

        $related_article = new Relevant($request->all());

		$related_article->save();

        Session::flash('success', 'The related article has been successfully added');
		return redirect()->back();
	}

	public function removeRelated($article_id, Request $request){
		try {

			$related = Relevant::with('Article')
                ->where('article_id', $article_id)
                ->where('related_article_id', $request['related_article_id'])
                ->first();

		} catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$related->delete();

		Session::flash('success', 'The related article has been successfully removed!');
		return redirect()->back();

	}

	public function publish(Request $request){

		try {

			$publish = Article::findOrfail($request['id']);

		} catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}

		$request['published_at'] = Carbon::now();

		$publish->update($request->all());

		Session::flash('success', 'The article has been successfully published');
		return redirect()->route('articles.index');
	}

	public function profile($employeeNumber)
    {
        //
    }

	public function profileUpdate($id, Request $request)
    {
		try {
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'contact_number' => 'required|min:11',
                'email' => 'required'
            ]);

			$employee = Employee::findOrfail($id);

            $employee->update($request->all());

            $user = User::findOrfail(Auth::user()->id);

            $user->update($request->all());

            Session::flash('success', 'Your information has been successfully updated');
            return redirect()->back();

		}  catch(ModelNotFoundException $e) {

			return redirect()->back()->withErrors(['Model Error','Data not Found!']);
		}
	}

	public function archive(Request $request)
    {
		if($request->ajax()) {
            $year = date("Y");
            $article = Article::whereRaw('YEAR(created_at) < ?',[$year])
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->orderBy('created_at', 'desc')
                ->get();

            $data = array('article' => $article);

            return view('_cms.system-views.digital.articles.archives', compact('data'));
        }

		return redirect()->withErrors('Restricted Access!');
	}

    public function preview($id) {
        $article = Article::with('Content', 'Relevant', 'Social')->findOrFail($id);

        $article->image = $this->verifyPhoto($article->image, 'articles');

        return view('_cms.system-views.digital.articles.preview', compact('article'));
    }
}
