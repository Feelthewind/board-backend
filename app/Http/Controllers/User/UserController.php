<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
      ];

      $this->validate($request, $rules);

      $data = $request->all();
      $data['password'] = bcrypt($request->password);

      $user = User::create($data);

      // return response()->json(['data' => $user], 201);
      return $this->showOne($user, 201);
    }
}
