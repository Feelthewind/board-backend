<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
  protected $fillable = [
    'post_id',
    'post_image_path',
  ];
}
