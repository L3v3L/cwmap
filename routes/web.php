<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Areas\Index;
use App\Livewire\Areas\Show;

Route::get('/', Index::class)->name('areas.index');

Route::group([
    'prefix' => 'areas'
], function () {
    Route::get('create', Show::class)->name('areas.create');
    Route::get('show/{area}', Show::class)->name('areas.show');
});

require __DIR__.'/auth.php';
