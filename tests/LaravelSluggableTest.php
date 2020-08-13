<?php

namespace Laraeast\LaravelSluggable\Tests;

use Illuminate\Support\Facades\Route;
use Laraeast\LaravelSluggable\ServiceProvider;
use Laraeast\LaravelSluggable\SluggableRedirectMiddleware;
use Orchestra\Testbench\TestCase;

class LaravelSluggableTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * Load package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('app.key', 'base64:DqoQnm5jcBekszwTnhkgn30vCbCeXKecqMmFNftB1TU=');
    }

    /** @test */
    public function it_returns_post_slug_url_when_use_route_method()
    {
        Route::get('posts/{post}', function (Post $post) {
            return $post->title;
        })->name('posts.show');

        $post = Post::create([
            'title' => 'dummy title',
            'body'  => 'dummy body',
        ]);

        $this->assertEquals(route('posts.show', $post), url("posts/{$post->id}-dummy-title"));

        $this->app->make('config')->set(['sluggable.separator' => '_']);

        $this->assertEquals(route('posts.show', $post), url("posts/{$post->id}_dummy_title"));
    }

    /** @test */
    public function it_redirect_to_latest_updated_slug()
    {
        Route::middleware(['web', SluggableRedirectMiddleware::class])->get('posts/{post}', function (Post $post) {
            return request()->fullUrl();
        })->name('posts.show');

        $post = Post::create([
            'title' => 'dummy title',
            'body'  => 'dummy body',
        ]);

        $this->get(url("posts/{$post->id}"))
            ->assertRedirect(url("posts/{$post->id}-dummy-title"));

        $this->get(route('posts.show', $post))
            ->assertSuccessful()->assertSee(url("posts/{$post->id}-dummy-title"));

        $post->update(['title' => 'new title']);
        $this->assertEquals(route('posts.show', $post), url("posts/{$post->id}-new-title"));

        $this->get(url("posts/{$post->id}-dummy-title"))
            ->assertRedirect(url("posts/{$post->id}-new-title"));
    }
}
