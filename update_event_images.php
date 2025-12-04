<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Event;

$events = Event::where('image', 'like', 'storage/%')->get();

foreach ($events as $event) {
    $event->image = str_replace('storage/', '', $event->image);
    $event->save();
}

echo "Updated " . $events->count() . " event images.\n";
