<div style="width: 100%; height: 100vh">

    <div class="rounded-b  px-4 py-3 shadow-md w-full absolute z-10 hidden" id="toast" wire:ignore onclick="this.classList.add('hidden')">
    </div>

    <div class="text-xl mb-2">
    <strong><a href="{{ route('areas.index') }}" class="text-blue-600">Home</a> / Area</strong>
    </div>

    <div class="flex flex-row">
        <div style="height: 90vh; width: 70%; background-color: gray;" wire:ignore>
            <div id="map" class="w-full h-full"></div>
            <button class="custom-button bg-red-500 hover:bg-red-700 my-2" id="clear_map">Clear Map</button>
        </div>

        <div class="flex flex-col border p-4 m-4 h-fit bg-gray-100">

            <input type="hidden" name="area_id" id="area_id" wire:model="areaId">
            <input type="hidden" name="geo_json" id="geo_json" wire:model="geo_json">
            <input type="hidden" name="encoded_geo_json" id="encoded_geo_json" wire:model="encoded_geo_json">

            <div class="flex flex-col border border-gray-300 rounded-lg bg-white relative">
                <input type="file" name="file" id="drop-area" accept=".geojson" class="absolute left-0 top-0 opacity-0 h-full">
                <div class="text-xl font-bold bg-gray-200 border p-2 border-gray-300">New Area</div>
                <div class="p-2">
                    Click here to select file to upload<br>
                    <i>(Or drag & drop files)</i>
                    <div class="text-center">
                        <i class="fa-solid fa-folder-plus text-9xl" id="file-upload-icon"></i>
                    </div>
                </div>
            </div>
            <div class="custom-input">
                <input type="text" name="area_name" id="area_name" placeholder="Area Name" required wire:model="name">
            </div>
            @error('name') <span class="custom-error">{{ $message }}</span> @enderror

            <div class="custom-input">
                <textarea name="area_description" id="area_description" placeholder="Area Description (optional)" wire:model="description"></textarea>
            </div>
            @error('description') <span class="custom-error">{{ $message }}</span> @enderror

            <div class="custom-input">
                <label>Category</label>
                <select name="category" id="category" required wire:model="map_area_categories_id">
                    <option value="">Choose a category</option>
                    @if ($categories)
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            @error('map_area_categories_id') <span class="custom-error">{{ $message }}</span> @enderror

            <div class="custom-input">
                <label>Valid From</label>
                <input type="datetime-local" name="valid_from" id="valid_from" required wire:model="valid_from">
            </div>
            @error('valid_from') <span class="custom-error">{{ $message }}</span> @enderror

            <div class="custom-input">
                <label>Valid To (optional)</label>
                <input type="datetime-local" name="valid_to" id="valid_to" wire:model="valid_to">
            </div>
            @error('valid_to') <span class="custom-error">{{ $message }}</span> @enderror

            <div class="flex flex-row m-2">
                <input type="checkbox" name="display_in_breaches_list" id="display_in_breaches_list" wire:model="display_in_breaches" class="mr-2">
                Display in breaches list
            </div>
            @error('display_in_breaches') <span class="custom-error">{{ $message }}</span> @enderror

            <button class="custom-button bg-blue-500 hover:bg-blue-700 my-2" id="save_area">
                @if ($areaId)
                Update Area
                @else
                Save Area
                @endif
            </button>
            @error('geo_json') <span class="custom-error">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

@vite(['resources/js/map.js'])