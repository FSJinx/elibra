<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Model
 *
 * @property array $attributes
 * @property array $formatter
 */
trait AutoFormatter
{
    /**
     * Boot the trait and hook into Eloquent's saving event.
     */
    protected static function bootAutoFormatter(): void
    {
        static::saving(function ($model) {
            /** @var Model $model */
            if (! property_exists($model, 'formatter') || ! is_array($model->formatter)) {
                return;
            }

            // Gamitin ang method_exists para hindi mag-error ang Intelephense sa getFillable()
            $fillable = method_exists($model, 'getFillable') ? $model->getFillable() : [];

            foreach ($model->formatter as $attribute => $rule) {

                // Security Check: Ililimit lang sa mga fields na nasa $fillable
                if (! in_array($attribute, $fillable, true)) {
                    continue;
                }

                // Basahin ang attribute value (gagamitin ang getAttribute para makuha ang casted array)
                $value = $model->getAttribute($attribute);

                if (is_null($value) || $value === '' || $value === []) {
                    continue;
                }

                // Perform Formatting Transformation (suportado ang string at array/JSON columns)
                $formattedValue = static::applyFormatRule($value, $rule);

                // Gamitin ang setAttribute para ligtas at walang $attributes property warning
                $model->setAttribute($attribute, $formattedValue);
            }
        });
    }

    /**
     * Apply the formatting rule to a scalar value or recursively to an array.
     */
    protected static function applyFormatRule(mixed $value, mixed $rule): mixed
    {
        // Kung array ang value (hal. JSON/Casted array tulad ng 'keywords'), i-apply sa bawat element
        if (is_array($value)) {
            return array_map(fn ($item) => static::applyFormatRule($item, $rule), $value);
        }

        // Siguraduhing string o scalar lang ang ipapasa sa string functions
        if (! is_string($value)) {
            return $value;
        }

        return match ($rule) {
            'uppercase' => Str::upper($value),
            'lowercase' => Str::lower($value),
            'titlecase' => Str::title(Str::lower($value)), // "mark angelo" -> "Mark Angelo"
            'ucfirst' => Str::ucfirst(Str::lower($value)),
            'trim' => trim($value),
            'slug' => Str::slug($value),
            'digits' => preg_replace('/[^0-9]/', '', $value),
            default => is_callable($rule) ? $rule($value) : $value,
        };
    }
}
