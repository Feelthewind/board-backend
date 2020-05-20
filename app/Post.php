<?php

namespace App;

use App\User;
use App\PostImage;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $fillable = [
    'title',
    'description',
    'user_id',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function post_images()
  {
    return $this->hasMany(PostImage::class);
  }
}
