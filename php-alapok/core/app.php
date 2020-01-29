<?php 
route('/', 'homeController');
route('/about', 'aboutController');
route('/image/(?<id>[\d]+)', 'singleImageController');
route('/image/(?<id>[\d]+)/edit', 'singleImageEditController', 'POST');
route('/image/(?<id>[\d]+)/delete', 'singleImageDeleteController', 'POST');

$uri = $_SERVER["REQUEST_URI"];
$cleaned = explode("?", $uri)[0];

$size = $_GET["size"] ?? 10;

$page = $_GET["page"] ?? 1;

$connection = mysqli_connect($config['db_host'] ,$config['db_user'],$config['db_pass'] ,$config['db_name']);
if (mysqli_connect_errno($connection)) {
    die (mysqli_connect_error());
}

$offset = ($page - 1) * $size;



$query = "SELECT count(*) as count FROM photos";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);
$total =  $row ['count'];


// last page- az utolso oldal sorszáma//

$lastPage = $total % $size == 0 ? intdiv($total, $size) : intdiv($total,$size) + 1;

//függvények
/**
 * Undocumented function
 *
 * @param [int] $total Az össze képszám
 * @param [type] $currentPage Aktuális oldal száma
 * @param [type] $size Lapméret
 * @return [string]
 */
