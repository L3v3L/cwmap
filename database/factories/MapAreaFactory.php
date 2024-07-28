<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MapArea>
 */
class MapAreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->bothify(fake()->country().' ??# ?#');
        $categories = \App\Models\MapAreaCategory::select('id')->inRandomOrder()->first();

        $startPoint = [
            fake()->randomFloat(2, -70, 0),
            fake()->randomFloat(2, -70, 0)
        ];

        $points = [
            $startPoint,
            [
                $startPoint[0] + fake()->randomFloat(2, 0, 70),
                $startPoint[1]
            ],
            [
                $startPoint[0] + fake()->randomFloat(2, 0, 70),
                $startPoint[1] + fake()->randomFloat(2, 0, 70)
            ],
            [
                $startPoint[0],
                $startPoint[1] + fake()->randomFloat(2, 0, 70)
            ],
            $startPoint
        ];


        // create date min 10 years ago
        $firstDate = fake()->dateTimeBetween('-5 years', 'now');
        if (fake()->boolean()) {
            $secondDate = fake()->dateTimeBetween($firstDate, '+10 year');
        }

        return [
            'name' => $name,
            'description' => fake()->sentence(),
            'map_area_categories_id' => $categories->id,
            'valid_from' => $firstDate,
            'valid_to' => $secondDate ?? null,
            'display_in_breaches' => fake()->boolean(),
            'geo_json' => [
                "type" => "FeatureCollection",
                "name" => $name,
                "crs" => [
                    "type" => "name",
                    "properties" => [
                        "name" => "urn:ogc:def:crs:OGC:1.3:CRS84"
                    ]
                ],
                "features" => [
                    [
                        "type" => "Feature",
                        "properties" => [
                            "Name" => "Gulf of Guinea + 12nm",
                            "description" => null,
                            "timestamp" => null,
                            "begin" => null,
                            "end" => null,
                            "altitudeMode" => null,
                            "tessellate" => -1,
                            "extrude" => 0,
                            "visibility" => -1,
                            "drawOrder" => null,
                            "icon" => null
                        ],
                        "geometry" => [
                            "type" => "Polygon",
                            "coordinates" => [
                                $points
                            ]
                        ]
                    ]
                ]
            ]

        ];
    }
}
