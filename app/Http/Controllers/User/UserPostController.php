<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use App\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserPostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
      $posts = $user->posts;

      return $this->showAll($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
      $rules = [
        'title' => 'required',
        'description' => 'required',
        'image' => 'image',
      ];

      $this->validate($request, $rules);

      $data = $request->all();
      $data['image'] = '1.jpg';
      $data['user_id'] = $user->id;

      $post = Post::create($data);

      return $this->showOne($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Post $post)
    {
      $rules = [
        'image' => 'image',
      ];

      $this->validate($request, $rules);

      $this->checkAuthor($user, $post);

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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Post $post)
    {
      $this->checkAuthor($user, $post);

      $post->delete();

      return $this->showOne($post);
    }

    protected function checkAuthor(User $user, Post $post)
    {
      if ($user->id != $post->user_id) {
        throw new HttpException(422, 'The specified user is not the actual user of the post');
      }
    }
}
