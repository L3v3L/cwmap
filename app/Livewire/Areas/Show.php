<?php

namespace App\Livewire\Areas;

use App\Models\MapArea;
use App\Models\MapAreaCategory;
use Livewire\Component;
use Livewire\Attributes\On;

class Show extends Component
{
    public ?int $areaId;
    public ?string $name;
    public ?string $description;
    public ?int $map_area_categories_id;
    public ?string $valid_from;
    public ?string $valid_to;
    public ?bool $display_in_breaches;
    public ?array $geo_json;
    public ?string $encoded_geo_json;

    public $categories = [];

    public function render()
    {
        $this->categories = MapAreaCategory::all();
        return view('livewire.areas.show');
    }

    public function mount(MapArea $area)
    {
        if ($area->id) {
            $this->areaId = $area->id;
            $this->name = $area->name;
            $this->description = $area->description;
            $this->map_area_categories_id = $area->map_area_categories_id;
            $this->valid_from = $area->valid_from;
            $this->valid_to = $area->valid_to;
            $this->display_in_breaches = $area->display_in_breaches;
            $this->geo_json = $area->geo_json;
            $this->encoded_geo_json = json_encode($area->geo_json);
        }
    }

    #[On('save-area')]
    public function saveArea() {
        $this->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'map_area_categories_id' => 'required|exists:map_area_categories,id',
            'valid_from' => 'required|date',
            'valid_to' => 'nullable|date',
            'display_in_breaches' => 'nullable|boolean',
            'geo_json' => 'required|array',
        ]);

        if($this->areaId??null) {
            $area = MapArea::findOrNew($this->areaId);
            $flashMessage = 'Area updated';
        } else {
            $area = new MapArea();
            $flashMessage = 'Area created';
        }

        $area->name = $this->name;
        $area->description = $this->description??null;
        $area->map_area_categories_id = $this->map_area_categories_id;
        $area->valid_from = $this->valid_from;
        $area->valid_to = $this->valid_to??null;
        $area->display_in_breaches = $this->display_in_breaches??false;
        // add meta data to the geo_json
        $this->geo_json['name'] = $this->name;
        $this->geo_json['crs'] = [
            'type' => 'name',
            'properties' => [
                'name' => 'urn:ogc:def:crs:OGC:1.3:CRS84'
            ]
        ];
        $area->geo_json = $this->geo_json;
        $area->save();

        // update the id
        $this->areaId = $area->id;
        $this->dispatch('toast', ['message' => $flashMessage]);

    }
}
