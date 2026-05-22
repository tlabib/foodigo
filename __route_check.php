<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$names = [
'admin.restaurants.show',
'admin.restaurants.update',
'admin.restaurants.destroy',
'admin.restaurants.menu-items.update',
'admin.restaurants.menu-items.destroy',
'admin.restaurants.menu-items.toggle-availability',
];
foreach($names as $n){ echo $n.': '.(Illuminate\Support\Facades\Route::has($n)?'yes':'no').PHP_EOL; }
