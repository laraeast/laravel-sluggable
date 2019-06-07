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
        return $this->generateSlug();
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        $value = explode($this->getSeparator(), $value)[0];

        return $this->where($this->getRouteKeyName(), $value)->first();
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
    public function generateSlug()
    {
        $separator = $this->getSeparator();

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
        $slug = str_replace(' ', $separator, $return);

        return $this->getKey().$separator.$slug;
    }

    /**
     * Get the sluggable separator
     * @return string
     */
    protected function getSeparator()
    {
        return Config::get('sluggable.separator', '-');
    }
}
