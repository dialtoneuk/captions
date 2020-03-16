<?php
/**
 *  Written by Llydia Lancaster in 2020.
 *  Version 2.0 of Lyds Sissy Captions <3
 */

/**
 * TODO: albums? logins?
 * TODO: move "viewed" to stats maybe for efficiency
 * TODO: Better stats which display the total size of all captions
 * TODO: A tagging system, users tag an image with a keyphrase which is then stored in a file similar to the maps.
 * TODO: Bake image data into lists page so doesn't hit hotlink (will need ffpmeg tho to build thumbnails)
 * TODO: gallery mode (fullscreen mobile)
 */

/**
 * Settings
 */

define("LYDS_ENABLE_GENERATION", true); //todo: remove this and add login features so admins can generate new maps
define("LYDS_ENABLE_VIEWED", true); //Enables view counting
define("LYDS_ENABLE_STATS", true); //Enables stats
define("LYDS_ENABLE_CACHE", false); //Enables Cache
define("LYDS_GENERATION_PASSWORD", "password");//todo: remove this and add login features so admins can generate new maps
define("LYDS_IMAGE_FOLDER", "images/");
define("LYDS_HOSTING_SUBFOLDER", ""); //used when you have hosting which is alike www.mywebsite.com/*subfolder*/
define("LYDS_PAYPAL_PREFIX", "lyds299");
define("LYDS_PAGE_MAX", 16);
define("LYDS_MAP_FILENAME", "data/map");
define("LYDS_STATS_REFRESHRATE", 10); //each number is a minute
define("LYDS_LIST_REFRESHRATE", 60); //each number is a minute
define("LYDS_DATA_FILEEXTENSION", ".data");
define("LYDS_MODULUS_NUMBER", 3); //don't ask me to explain this :)

/**
 * Clients true IP Address
 */

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $client_ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $client_ip = $_SERVER['REMOTE_ADDR'];
}

define('LYDS_CLIENT_ADDRESS', $client_ip );

/**
 * Script Start
 */

include_once "includes/functions.php";

//start session
if (session_status() === PHP_SESSION_NONE)
    session_start();

//process request
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    //Generate stats
    if (LYDS_ENABLE_STATS)
    {

        $_ = "stats" . LYDS_DATA_FILEEXTENSION;

        if (file_exists( $_ )) {

            //generate stats every 10 minutes
            if (time() > filemtime( $_ ) + (60 * LYDS_STATS_REFRESHRATE))
                generate_stats();

            $stats = read_data( $_ );
        } else {

            generate_stats();
            $stats = read_data( $_ );
        }
    }

    if (!isset($_SESSION["confirmed"]) || empty($_SESSION["confirmed"]) || !$_SESSION["confirmed"])
        include_once "pages/confirmation.php";
    else {

        get:
        if( empty( $_GET ) || !isset( $_GET ) )
            $current_page = "image";
        else
            $current_page = array_keys( $_GET )[0];

        if( is_numeric( $current_page ) )
            $current_page = "image";
        else
            $current_page = strtolower( $current_page );

        switch( $current_page )
        {

            case "list":
                $page = get_page();

                if( LYDS_ENABLE_CACHE )
                {
                    $_ = "cache/{$page}.php";

                    if( file_exists( $_ ) && (time() < filemtime($_) + (60*LYDS_LIST_REFRESHRATE )))
                    {
                        echo( file_get_contents( $_ ) );
                        break;
                    }
                    elseif( (time() < filemtime($_) + (60*LYDS_LIST_REFRESHRATE )) && isset( $stats["page_count"] ) )
                        generate_lists( $stats["page_count"] );

                }
                include_once "pages/list.php";
                break;
            case "history":
                include_once "pages/history.php";
                break;
            case "hotlink":
                include_once "pages/hotlink.php";
                break;
            case "generate":
                if( !LYDS_ENABLE_GENERATION )
                {
                    http_response_code(404);
                    die();
                }
                else
                    include_once "pages/generation.php";
                break;
            case "image":
                include_once "pages/image.php";
                if (session_status() === PHP_SESSION_ACTIVE && LYDS_ENABLE_VIEWED)
                    if( isset( $file ) )
                        if (!isset($_SESSION["images"])) {
                            $_SESSION["images"] = [];
                            $_SESSION["images"][$image] = ["image" => $file, "time" => time()];
                        } else
                            $_SESSION["images"][$image] = ["image" => $file, "time" => time()];
                break;
            case "images":
                include_once "pages/image.php";
                if (session_status() === PHP_SESSION_ACTIVE && LYDS_ENABLE_VIEWED)
                    if( isset( $file ) )
                        if (!isset($_SESSION["images"])) {
                            $_SESSION["images"] = [];
                            $_SESSION["images"][$image] = ["image" => $file, "time" => time()];
                        } else
                            $_SESSION["images"][$image] = ["image" => $file, "time" => time()];
                break;
            default:
                http_response_code(404);
                die();
                break;
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["action"]) || is_numeric($_POST["action"]))
        die("Invalid post request");
    else {

        $action = $_POST["action"];

        switch ($action) {

            case "confirm":
                $_SESSION["confirmed"] = true;

                if (file_exists("confirmations" . LYDS_DATA_FILEEXTENSION))
                    $data = read_data("confirmations" . LYDS_DATA_FILEEXTENSION);
                else
                    $data = [];

                $data[] = [
                    "confirmed" => true,
                    "ip" => $client_ip,
                    "time" => time()
                ];

                file_put_contents("confirmations" . LYDS_DATA_FILEEXTENSION, serialize($data));
                break;
            default:
                die("Invalid post request");
        }
    }

    goto get;
}