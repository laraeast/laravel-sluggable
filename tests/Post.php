<?php

namespace Laraeast\LaravelSluggable\Tests;

use Illuminate\Database\Eloquent\Model;
use Laraeast\LaravelSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
    ];

    /**
     * The sluggable fields for model.
     *
     * @return array
     */
    public function sluggableFields()
    {
        return ['title'];
    }
}
