# Eloquent-Sluggable
<p align="center">
<a href="https://github.styleci.io/repos/190690591"><img src="https://github.styleci.io/repos/190690591/shield?branch=master" alt="StyleCI"></a>
	<a href="https://travis-ci.org/laraeast/laravel-sluggable">
		<img src="https://travis-ci.org/laraeast/laravel-sluggable.svg?branch=master" alt="Travis Build Status">
	</a>
	<a href="https://circleci.com/gh/laraeast/laravel-sluggable">
		<img src="https://circleci.com/gh/laraeast/laravel-sluggable.png?style=shield" alt="Circleci Build Status">
	</a>
	<a href="https://packagist.org/packages/laraeast/laravel-sluggable">
		<img src="https://poser.pugx.org/laraeast/laravel-sluggable/d/total.svg" alt="Total Downloads">
	</a>
	<a href="https://packagist.org/packages/laraeast/laravel-sluggable">
		<img src="https://poser.pugx.org/laraeast/laravel-sluggable/v/stable.svg" alt="Latest Stable Version">
	</a>
	<a href="https://packagist.org/packages/laraeast/laravel-sluggable">
		<img src="https://poser.pugx.org/laraeast/laravel-sluggable/license.svg" alt="License">
	</a>
</p>
Easy creation of slugs for your Eloquent models in Laravel.

## Background: What is a slug?

A slug is a simplified version of a string, typically URL-friendly. The act of "slugging" 
a string usually involves converting it to one case, and removing any non-URL-friendly 
characters (spaces, accented letters, ampersands, etc.). The resulting string can 
then be used as an identifier for a particular resource.

For example, if you have a blog with posts, you could refer to each post via the ID:

    http://example.com/post/1
    http://example.com/post/2

... but that's not particularly friendly (especially for 
[SEO](http://en.wikipedia.org/wiki/Search_engine_optimization)). You probably would 
prefer to use the post's title in the URL, if your post 
is titled "My Dinner With Ahmed & Omar", the URL will be:
```
http://example.com/post/1-my-dinner-with-ahmed-omar
```

## Installation

* Install the package via Composer:
    - For Laravel 5.2 >= 6.x
        ```bash
        $ composer require laraeast/laravel-sluggable:^1.0
        ```
    - For Laravel 7.x
        ```bash
        $ composer require laraeast/laravel-sluggable:^2.0
        ```

    The package will automatically register its service provider.


## Middleware
You should add `SluggableRedirectMiddleware` to `web` middileware to redirect to latest updated slug.
`app/Http/Kernel.php` file :
```php
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            ...
            \Laraeast\LaravelSluggable\SluggableRedirectMiddleware::class,
        ],
        ...
    ];
```
## Updating your Eloquent Models

Your models should use the Sluggable trait, which has an abstract method `sluggableFields()`
that you need to define.  This is where any model-specific configuration is set 

```php
use Laraeast\LaravelSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    /**
     * The sluggable fields for model.
     *
     * @return array
     */
    public function sluggableFields()
    {
        return ['name'];
    }
}
```
