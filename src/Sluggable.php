<?php

namespace Laraeast\LaravelSluggable;

use Illuminate\Support\Facades\Config;

trait Sluggable
{
    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        $slug = $this->generateSluggable();

        return $this->getAttribute($this->getRouteKeyName()).'-'.$slug;
    }

    /**
     * The sluggable fields for model.
     *
     * @return array
     */
    public abstract function sluggableFields();

    /**
     * Take the title of the entry.
     *
     * @return string
     */
    public function generateSluggable()
    {
        $separator = Config::get('sluggable.separator', '-');

        $input = [];

        foreach ($this->sluggableFields() as $key) {
            if ($this->{$key} instanceof Carbon) {
                $input[] = $this->{$key}->format('Y m d');
            } else {
                $input[] = $this->{$key};
            }
        }

        $input = strip_tags(
            collect($input)->join(' ')
        );

        $input = trim($input);

        // Minimize characters and remove spaces and punctuation.
        $return = trim(preg_replace('/ +/', ' ', preg_replace('/[^a-zA-Z\p{Arabic}0-9\s]/u', '', mb_strtolower($input))));

        // Replace space.
        return str_replace(' ', $separator, $return);
    }
}
