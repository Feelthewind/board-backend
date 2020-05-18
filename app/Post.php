<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $fillable = [
    'title',
    'description',
    'user_id',
    'image',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
