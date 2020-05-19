<?php

namespace App\Http\Controllers\Post;

use Storage;
use File;
use App\Post;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $posts = Post::orderBy('created_at','DESC')->paginate(3);

      return response()->json([$posts], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $rules = [
        'title' => 'required|string',
        'description' => 'required|string',
      ];

      $this->validate($request, $rules);

      $data = $request->all();
      $data['user_id'] = auth()->user()->id;

      $post = Post::create($data);

      return $this->showOne($post, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $this->showOne($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
      // $rules = [
      //   'image' => 'image',
      // ];

      // $this->validate($request, $rules);

      $this->checkAuthor($post);

      $post->fill($request->only([
        'title',
        'description',
        'image',
      ]));

      if ($post->isClean()) {
        return $this->errorResponse('You need to specify a different value', 422);
      }

      $post->save();

      return $this->showOne($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
      $this->checkAuthor($post);

      $post->delete();

      return $this->showOne($post);
    }

    /**
     * Upload post image
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadimage(Request $request)
    {
      $imagePath = Storage::disk('uploads')->put('', $request->image);
            return response()->json(["url" => '/uploads/' . $imagePath]);
      // if ($request->hasFile('image'))
      // {
      //       $file      = $request->file('image');
      //       $filename  = $file->getClientOriginalName();
      //       $extension = $file->getClientOriginalExtension();
      //       $picture   = date('His').'-'.$filename;
      //       $file->move(public_path('img'), $picture);
      //       return response()->json(["url" => public_path('img')]);
      // }
    }

    public function deleteimage(Request $request)
    {
      // File::delete('uploads/iDalWz0scKSNHGIKkuIYtodjGG0LbSt6G6Ff9vOp.jpeg');
      
      // dd($request->image);
      if(File::exists($request->image)) {
        File::delete($request->image);
      }
    }

    protected function checkAuthor(Post $post)
    {
      if (auth()->user()->id != $post->user_id) {
        throw new HttpException(422, 'The specified user is not the actual user of the post');
      }
    }
}
