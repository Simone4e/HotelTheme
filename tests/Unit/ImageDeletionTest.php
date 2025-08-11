<?php

use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('removes file from storage when image is deleted', function () {
    Storage::fake('public');

    $image = Image::factory()->create(['path' => 'images/test.jpg']);

    $image->delete();

    Storage::disk('public')->assertMissing('images/test.jpg');
});
