<div class="flex flex-col border p-4 h-fit w-full bg-gray-100">
    <div class="flex flex-row m-2 p-2 w-full justify-between items-center">
        <div class="custom-input grow">
            <label>Name</label>
            <input type="text" name="name" id="name" placeholder="Area Name" required wire:model.live="searchName">
        </div>
        <div class="custom-input grow">
            <label>Description</label>
            <input type="text" name="description" id="description" placeholder="Area Description" required wire:model.live="searchDescription">
        </div>
        <div class="custom-input grow">
            <label>Category</label>
            <select name="category" id="category" required wire:model.live="searchCategory">
                <option value="">All</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="custom-input">
            <label>Valid From</label>
            <input type="datetime-local" name="valid_from" id="valid_from" required wire:model.live="searchValidFrom">
        </div>
        <div class="custom-input">
            <label>Valid To</label>
            <input type="datetime-local" name="valid_to" id="valid_to" wire:model.live="searchValidTo">
        </div>
        <div class="flex flex-row items-center mx-2">
            <input type="checkbox" name="display_in_breaches_list" id="display_in_breaches_list" wire:model.live="searchDisplayInBreaches">
            <label class="ml-2" for="display_in_breaches_list">Breaches</label>
        </div>
        <div class="flex flex-row items-center">
            <button wire:click="clear" class="custom-button bg-yellow-500 hover:bg-yellow-700 mr-2" title="Clear Filters">
                <i class="fa-solid fa-eraser"></i>
            </button>
            <a href="{{ route('areas.create') }}" class="custom-button bg-green-600 hover:bg-green-700" title="Create New Area">
                <i class="fa-solid fa-file-circle-plus"></i></a>
        </div>
    </div>


    <table class="w-full text-sm text-left rtl:text-right text-gray-500 my-2">
        <thead class="text-xs text-gray-700 uppercase bg-gray-300">
            <tr>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Description</th>
                <th class="px-6 py-3">Category</th>
                <th class="px-6 py-3">Valid From</th>
                <th class="px-6 py-3">Valid To</th>
                <th class="px-6 py-3">Display in Breaches List</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($areas as $area)
            <tr class="bg-white border-b">
                <td class="custom-table-row">
                    {{ $area->name }}
                </td>
                <td class="custom-table-row">
                    {{ Str::limit($area->description) }}
                </td>
                <td class="custom-table-row">
                    {{ $area->category->name }}
                </td>
                <td class="custom-table-row">
                    {{ Carbon\Carbon::parse($area->valid_from)->format('j M Y H:i') }}
                </td>
                @if($area->valid_to)
                <td class="custom-table-row">
                    {{ Carbon\Carbon::parse($area->valid_to)->format('j M Y H:i') }}
                </td>
                @else
                <td class="custom-table-row">
                    <i class="fa-solid fa-infinity"></i>
                </td>
                @endif
                @if($area->display_in_breaches)
                <td class="custom-table-row">
                    <i class="fa-solid fa-check text-green-500"></i>
                </td>
                @else
                <td class="custom-table-row">
                    <i class="fa-solid fa-times text-red-500"></i>
                </td>
                @endif
                <td class="custom-table-row">
                    <div class="flex flex-row">
                        <a href="{{ route('areas.show', $area) }}" class="custom-button bg-blue-500 hover:bg-blue-700 mr-2"
                        title="View Area"
                        ><i class="fa-solid fa-eye"></i></a>
                        <button wire:click="deleteArea({{ $area->id }})" class="custom-button bg-red-500 hover:bg-red-700"
                        title="Delete Area"
                        ><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $areas->links() }}
    </div>
</div>