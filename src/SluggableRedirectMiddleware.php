<?php

namespace Laraeast\LaravelSluggable;

use Closure;
use Illuminate\Database\Eloquent\Model;

class SluggableRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $newParameters = [];
        foreach ($request->route()->originalParameters() as $key => $value) {
            if (($model = $request->route()->parameter($key)) instanceof Model) {
                if (in_array(Sluggable::class, class_uses($model))) {
                    $slug = $model->generateSlug();
                    if ($value !== $slug) {
                        $newParameters[$key] = $slug;
                    }
                }
            }
        }
        if (!empty($newParameters)) {
            return redirect()->route(
                $request->route()->getName(),
                $newParameters
            );
        }

        return $next($request);
    }
}
