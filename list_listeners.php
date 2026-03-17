<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Verified;

$listeners = Event::getListeners(Verified::class);
echo "Count: " . count($listeners) . "\n";
foreach ($listeners as $l) {
    if (is_string($l)) {
        echo "String Listener: $l\n";
    } elseif (is_array($l)) {
        echo "Array Listener: " . get_class($l[0]) . "@" . $l[1] . "\n";
    } else {
        echo "Closure or other: " . gettype($l) . "\n";
    }
}
