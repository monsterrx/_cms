<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Validator;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4',
            'image' => 'image|file|max:2048|required'
        ]);

        if ($validator->fails()) {
            session()->flash('errors', $validator->errors());
            return redirect()->back();
        }

        $article_id = $request->article_id;
        $show_id = $request->show_id;
        $jock_id = $request->jock_id;
        $batch_id = $request->batch_id;
        $student_jock_id = $request->student_jock_id;

        if ($batch_id) {
            $request['file'] = $this->storePhoto($request, 'images/scholarBatch', 'batch');
            $request['name'] = $this->IdGenerator(4);

            $photo = new Photo($request->all());
            $photo->save();

            session()->flash('success', 'Added the batch\'s photo!');
            return redirect()->route('articles.show', $article_id);
        } elseif ($show_id) {
            $request['file'] = $this->storePhoto($request, 'images/shows', 'shows', true);
            $request['name'] = $this->IdGenerator(4);

            $photo = new Photo($request->all());
            $photo->save();

            session()->flash('success', 'Added a show\'s photo!');
            return redirect()->route('shows.show', $show_id);
        } elseif ($jock_id) {
            $request['file'] = $this->storePhoto($request, 'images/jocks', 'jocks', true);
            $request['name'] = $this->IdGenerator(4);

            $photo = new Photo($request->all());
            $photo->save();

            session()->flash('success', 'Added a jock\'s photo!');
            return redirect()->route('jocks.show', $jock_id);
        } elseif ($student_jock_id) {
            $request['file'] = $this->storePhoto($request, 'images/studentJocks', 'studentJocks', true);
            $request['name'] = $this->IdGenerator(4);

            $photo = new Photo($request->all());
            $photo->save();

            session()->flash('success', 'Added a student jock\'s photo!');
            return redirect()->route('radioOne.jocks.show', $student_jock_id);
        } elseif ($article_id) {
            $request['file'] = $this->storePhoto($request, 'images/articles', 'articles');
            $request['name'] = $this->IdGenerator(4);

            $photo = new Photo($request->all());
            $photo->save();

            session()->flash('success', 'Added an article\'s image!');
            return redirect()->route('articles.show', $article_id);
        }

        session()->flash('error', 'Id not found nor registered, please contact your it developer');
        return redirect()->back();
    }

    public function update($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            session()->flash('errors', $validator->errors());
            return redirect()->back();
        }

        $photo = Photo::with('Show', 'Jock', 'Batch', 'Article')->findOrFail($id);
        $photo->update($request->all());

        session()->flash('success', 'Image data has been updated.');
        return redirect()->back();
    }

    public function destroy($id) {
        $photo = Photo::with('Show', 'Jock', 'Batch', 'Article')->findOrFail($id);

        $photo->delete();

        session()->flash('success', 'Image has been deleted');
        return redirect()->back();
    }
}
