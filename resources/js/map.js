import Map from 'ol/Map.js';
import View from 'ol/View.js';
import { Draw, Modify, Snap } from 'ol/interaction.js';
import { Vector as VectorSource, TileWMS } from 'ol/source.js';
import { createXYZ } from 'ol/tilegrid.js';
import { Tile as TileLayer, Vector as VectorLayer } from 'ol/layer.js';
import { get, fromLonLat } from 'ol/proj.js';
import GeoJSON from 'ol/format/GeoJSON.js';

let draw;
let snap;
let modify;
let defaultGeoJson = {
    'type': 'FeatureCollection',
    "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
    'features': []
};
let dropArea = document.getElementById("drop-area");


document.addEventListener('livewire:initialized', () => {
    function showToast(message , type = 'success') {
        let toastElement = document.getElementById('toast');
        toastElement.innerHTML = message;
        if (type === 'success') {
            toastElement.classList.remove('custom-toast-error');
            toastElement.classList.add('custom-toast-success');
        } else {
            toastElement.classList.remove('custom-toast-success');
            toastElement.classList.add('custom-toast-error');
        }
        toastElement.classList.remove('hidden');
        setTimeout(function () {
            toastElement.classList.add('hidden');
        }, 3000);
    }



    // Toast event
    Livewire.on('toast', (options) => {
        showToast(options[0].message);
    });


    let geojsonObject = document.getElementById('geo_json').value ? document.getElementById('encoded_geo_json').value : defaultGeoJson;


    // Loading GeoJSON features with a Vector source
    const vector = new VectorLayer({
        source: new VectorSource({
            features: new GeoJSON().readFeatures(geojsonObject),
        }),
        style: {
            'fill-color': 'rgba(0, 0, 0, 0.5)',
            'stroke-color': '#ffcc33',
            'stroke-width': 2,
            'circle-radius': 7,
            'circle-fill-color': '#ffcc33',
        },
    });

    // Limit multi-world panning to one world east and west of the real world.
    // Geometry coordinates have to be within that range.
    const extent = get('EPSG:4326').getExtent().slice();
    extent[0] += extent[0];
    extent[2] += extent[2];

    const map = new Map({
        layers: [
            new TileLayer({
                source: new TileWMS({
                    url: 'https://ahocevar.com/geoserver/gwc/service/wms',
                    crossOrigin: '',
                    params: {
                        'LAYERS': 'ne:NE1_HR_LC_SR_W_DR',
                        'TILED': true,
                        'VERSION': '1.1.1',
                    },
                    projection: 'EPSG:4326',
                    // Source tile grid (before reprojection)
                    tileGrid: createXYZ({
                        extent: [-180, -90, 180, 90],
                        maxResolution: 360 / 512,
                        maxZoom: 10,
                    }),
                    // Accept a reprojection error of 2 pixels
                    reprojectionErrorThreshold: 2,
                }),
            }),
            vector
        ],
        target: 'map',
        view: new View({
            projection: 'EPSG:4326',
            center: fromLonLat([
                0,
                0
            ]),
            zoom: 3,
            extent,
        }),
    });

    createNewVectorSource(geojsonObject);

    // Save the area button
    document.getElementById('save_area').addEventListener('click', () => {
        const format = new GeoJSON();
        const features = vector.getSource().getFeatures();
        Livewire.first().set('geo_json', JSON.parse(format.writeFeatures(features)));
        Livewire.dispatch('save-area')
    });

    // Clear the map button
    document.getElementById('clear_map').addEventListener('click', () => createNewVectorSource(defaultGeoJson));

    function createNewVectorSource(json) {
        vector.setSource(new VectorSource({
            features: new GeoJSON().readFeatures(json),
        }));

        if (draw) {
            map.removeInteraction(draw);
        }
        if (snap) {
            map.removeInteraction(snap);
        }
        if (modify) {
            map.removeInteraction(modify);
        }

        modify = new Modify({ source: vector.getSource() });
        map.addInteraction(modify);

        draw = new Draw({
            source: vector.getSource(),
            type: 'Polygon',
        });
        map.addInteraction(draw);

        snap = new Snap({ source: vector.getSource() })
        map.addInteraction(snap);
    }

    // ************************ Drag and drop ***************** //
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop', 'change'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Handle dropped files
    dropArea.addEventListener('drop', handleFileDrop, false);
    dropArea.addEventListener('change', handleFileSelect, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleFileDrop(e) {
        loadFile(e.dataTransfer.files[0]);
    }

    function handleFileSelect(e) {
        loadFile(e.target.files[0]);
    }

    function loadFile(file) {
        let reader = new FileReader();
        reader.readAsText(file);
        reader.onloadend = () => {
            try {
                let result = reader.result;
                result = JSON.parse(result);
                Livewire.first().set('name', result.name);
                createNewVectorSource(result);
            } catch (e) {
                showToast('Invalid file format', 'error');
                return;
            }
        };
    }
});