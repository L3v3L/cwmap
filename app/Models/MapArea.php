<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'map_area_categories_id',
        'valid_from',
        'valid_to',
        'display_in_breaches',
        'geo_json',
    ];

    protected $casts = [
        'geo_json' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(MapAreaCategory::class, 'map_area_categories_id');
    }

    public function getValidFromAttribute($value)
    {
        return $value?\Carbon\Carbon::create($value)->format("Y-m-d\TH:i:s"):null;
    }

    public function getValidToAttribute($value)
    {
        return $value?\Carbon\Carbon::create($value)->format("Y-m-d\TH:i:s"):null;
    }
}
