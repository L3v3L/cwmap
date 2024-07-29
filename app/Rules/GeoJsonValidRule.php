<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GeoJsonValidRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // check if the value is an array
        if (!is_array($value)) {
            $fail("Unvalid map data.");
            return;
        }

        // check if the value has a type key with the value FeatureCollection
        if (!isset($value['type']) || $value['type'] !== 'FeatureCollection') {
            $fail("Unvalid map data.");
            return;
        }

        if (!is_array($value['features'][0]['geometry']['coordinates'][0]??null)) {
            $fail("Map must have contain atleast one shape.");
        }
    }
}
