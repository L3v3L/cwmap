<?php

namespace App\Livewire\Areas;

use App\Models\MapArea;
use App\Models\MapAreaCategory;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Url]
    public string $searchName = '';
    #[Url]
    public string $searchDescription = '';
    #[Url]
    public ?int $searchCategory = null;
    #[Url]
    public ?string $searchValidFrom = null;
    #[Url]
    public ?string $searchValidTo = null;
    #[Url]
    public bool $searchDisplayInBreaches = false;

    public function render()
    {
        $areas = MapArea::query()
            ->with('category')
            ->when($this->searchName, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->when($this->searchDescription, function ($query, $search) {
                return $query->where('description', 'like', '%' . $search . '%');
            })->when($this->searchCategory, function ($query, $search) {
                return $query->whereHas('category', function ($query) use ($search) {
                    return $query->where('id', $search);
                });
            })->when($this->searchValidFrom, function ($query, $search) {
                return $query->where('valid_from', '>=', $search);
            })->when($this->searchValidTo, function ($query, $search) {
                return $query->where('valid_to', '<=', $search);
            })->when($this->searchDisplayInBreaches, function ($query, $search) {
                return $query->where('display_in_breaches', $search);
            })->paginate(10);

        return view('livewire.areas.index', [
            'areas' => $areas,
            'categories' =>  MapAreaCategory::all(),
        ]);
    }

    public function clear()
    {
        $this->reset();
    }

    public function deleteArea($id)
    {
        MapArea::destroy($id);
    }
}
