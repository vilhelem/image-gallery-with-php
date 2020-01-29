<?php
//--------------------------------------------------------------------------------------------------
//Függvények
//--------------------------------------------------------------------------------------------------

/**
 * Lapozást megvalósító függvény
 *
 * @param [type] $total Összes képszám
 * @param [type] $currentPage Aktuális oldal száma
 * @param [type] $size Lapméret
 * @return [string] A lapozósáv listaelemeinek a markup-ját
 */
function paginate($total, $currentPage, $size){
    $page = 0;
    $markup =""; // ebben fogjuk a visszaadandó html markup-ot összefűzni

    // Előző oldal
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $markup .=
            "<li class=\"page-item\">
                    <a class=\"page-link\" href=\"?size=$size&page=$prevPage\">Prev</a>
                </li>";
    }
    else {
        $markup .=
            "<li class=\"page-item disabled\">
                <a class=\"page-link\" href=\"#\">Prev</a>
            </li>";
    }

    for ($i = 0; $i < $total; $i += $size) {
        $page++;
        $activeClass = $currentPage == $page ? "active" : "";
        $markup .= 
            "<li class=\"page-item $activeClass\">
                <a class=\"page-link\" href=\"?size=$size&page=$page\">$page</a>
            </li>";
    }


    //Következő oldal
        if ($currentPage < $page) {
            $nextPage = $currentPage + 1;
            $markup .=
                "<li class=\"page-item\">
                        <a class=\"page-link\" href=\"?size=$size&page=$nextPage\">Next</a>
                    </li>";
        }
        else {
            $markup .=
                "<li class=\"page-item disabled\">
                    <a class=\"page-link\" href=\"#\">Next</a>
                </li>";
        }

    return $markup;
}


/**
 * Hiba esetén megjeleníti a templates/error.php oldalt
 *
 * @return void
 */
function errorPage() {
    include "templates/error.php";
    die();
}

/**
 * Kiír egy hibát a logfájlba (logs/application.log)
 *
 * @param [string] $level Hiba szint.
 * @param [string] $message Hibaüzenet szövege.
 * @return void
 */
function logMessage($level, $message) {
    $file = fopen("logs/application.log", "a"); //"a" mint append
    fwrite($file, "[$level] " . date("Y-m-d H:i:s") . " $message" . PHP_EOL); //EOL ez egy beépített konstans
    fclose($file);
}

/**
 * Adatbázis kapcsolat
 * 
 * @return $connection 
 */
function getConnection(){
    global $config;
    $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
        if (!$connection) {
            logMessage("ERROR", 'Failed to connect to MySQL: ' . mysqli_connect_error());
            errorPage();
        }
            return $connection;

}

/**
 * getTotal() a képek számának meghatározása
 *
 * @param [type] $connection MySQL kapcsolat
 * @return [int] A képek száma
 */
function getTotal($connection) {
    $query = "SELECT count(*) AS count FROM photos";
    if ($result = mysqli_query($connection, $query)) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

/**
 * Egy oldalnyi képet ad vissza az adatbázisból, a lapméret és az eltolás alapján.
 *
 * @param [type] $connection MySQL kapcsolat
 * @param [int] $size Lapméret
 * @param [int] $offset Eltolás
 * @return void MYSQLI_ASSOC
 */
function getPhotosPaginated($connection, $size, $offset) {
    $query = "SELECT * FROM photos LIMIT ?, ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "ii", $offset, $size);
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }   
}


/**
 * Egy képet ad vissza, azonosító alapján
 *
 * @param [type] $connection
 * @param [type] $id
 * @return void
 */
function getImageById($connection, $id) {
    $query = "SELECT * FROM photos WHERE id = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "i", $id);
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        return mysqli_fetch_assoc($result);
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

/**
 * Egy route (útvonal) és a hozzá tartozó kezelő függvény hozzáadása a $routes assoc tömbhöz.
 *
 * @global
 * @param	mixed 	$route   	Útvonal
 * @param	mixed 	$callable	Handler function (Controller)
 * @param	string	$method  	Default: "GET"
 * @return	void
 */
function route($route, $callable, $method = "GET") {
    global $routes;
   //$route = "%^$route$%";
    $routes[strtoupper($method)][$route] = $callable;
}

/**
 * Az aktuális útvonalhoz tartozó kezelő függvény (Controller) kikeresése, meghívása
 *
 * @param [string] $actualRoute Az aktuális útvonal
 * @return [bool] true - találat esetén | false egyébként
 */
function dispatch($actualRoute, $notFound) {
    global $routes;
    $method = $_SERVER["REQUEST_METHOD"];   // POST GET PATH DELETE
    if (key_exists($method, $routes)) {
        foreach ($routes[$method] as $route => $callable) {
            $route = "%^$route$%";
            if (preg_match($route, $actualRoute, $matches)) {
                return $callable($matches);
            }
        }
    }
    return $notFound();
}