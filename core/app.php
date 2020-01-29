<?php
// az útvonal lekérdezése
$uri = $_SERVER["REQUEST_URI"] ?? '/';
$cleaned = explode("?", $uri)[0];

// dispatch() fv. meghívása, ami kiválasztja az adott útvonalhoz tartozó controllert
list($view, $data) = dispatch($cleaned,'notFoundController');
extract($data);

