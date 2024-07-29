<p align="center">CWmap</p>

## About CWmap

CWmap is a proof of concept Laravel app that allows the user to create polygon features on a map and save them to a database with meta data.

## Requirements
- PHP ^8.2
- Composer
- Node.js
- npm

## Installation
```bash
git clone https://github.com/L3v3L/cwmap.git
cd cwmap
composer install
npm install
# when asked if command should create sqlite database, choose yes
php artisan migrate --seed
npm run build
```

## Map Featues
The map allow to create a polygon by pressing on the map and creating points, after completing the polygon the user may hold and drag on any perimeter of the polygon to edit the shape.
Holding shift allows the user the free draw the polygon.
There is a clear map button that will remove all polygons from the map.

GeoJson files may be dragged and dropped onto the folder icon to load the polygons onto the map.

Map Projection is set to PSG:4326

## Showcases
* Laravel main components
* Livewire main components
* Validation
* Handling files
* Drag and drop
* Seeding
* Use of Javascript 3rd party libraries
* use of TailwindCSS
* Eloquent
* Search filters
* Pagination
* Styling to requirements

## Made with
 - https://laravel.com/
 - https://livewire.laravel.com/
 - https://tailwindcss.com/
 - https://openlayers.org/
