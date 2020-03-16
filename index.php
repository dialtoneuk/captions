<?php
/**
 *  Written by Llydia Lancaster in 2019.
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

//Script Specific Declarations
define("LYDS_ENABLE_GENERATION", true);
define("LYDS_ENABLE_VIEWED", true);
define("LYDS_ENABLE_STATS", true);
define("LYDS_ENABLE_CACHE", false);
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

//Script Specific Includes
foreach (glob("includes/*.php") as $file)
    include_once($file);

//Generate stats
if (LYDS_ENABLE_STATS)
    if (file_exists("stats" . LYDS_DATA_FILEEXTENSION)) {

        //generate stats every 10 minutes
        if (time() > filemtime("stats" . LYDS_DATA_FILEEXTENSION) + (60 * LYDS_STATS_REFRESHRATE))
            generate_stats();

        $stats = read_data("stats" . LYDS_DATA_FILEEXTENSION);
    } else {

        generate_stats();
        $stats = read_data("stats" . LYDS_DATA_FILEEXTENSION);
    }

//start session
if (session_status() === PHP_SESSION_NONE)
    session_start();

//process request
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    if (isset($_GET["hotlink"])) {
        include_once "pages/hotlink.php";
        die();
    } else
        echo("<html lang=\"en\">");

    get:
    include_once "pages/template_header.php";

    if (!isset($_SESSION["confirmed"]) || empty($_SESSION["confirmed"]) || !$_SESSION["confirmed"])
        include_once "pages/confirmation.php";
    else {
        ?>
        <body>
        <?php
        if (isset($_GET["list"]))
        {
            $current_page = "list";
            include_once "pages/list.php";
        }
        elseif (isset($_GET["history"]))
        {
            $current_page = "history";
            include_once "pages/history.php";
        }
        elseif (isset($_GET["generate"]) && isset($_GET["password"]) && $_GET["password"] == LYDS_GENERATION_PASSWORD) {

            if (!LYDS_ENABLE_GENERATION)
                die("Generation is disabled...");

            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/data/"))
                @mkdir($_SERVER["DOCUMENT_ROOT"] . "/data/");

            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/"))
                @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache/");

            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/images/"))
                @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache/images/");

            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/images/"))
                echo("Cannot generate image set as no images found in:" . $_SERVER["DOCUMENT_ROOT"] . "/images/");
            else {

                set_time_limit(600);

                echo("Generating map files... <br>");
                generate_map_files();
                echo("Generating stats... <br>");
                generate_stats();
                echo("Generating pages... <br>");
                $stats = read_data("stats" . LYDS_DATA_FILEEXTENSION);
                generate_lists($stats["image_count"]);
                echo("Generating hotlinks...");
                generate_hotlinks($stats["image_count"]);
                echo("Done.. <a href='" . make_link("?", []) . "'>Go home..</a>");

                set_time_limit(30);
            }
        } else
            include_once "pages/image.php";

        ?>
        </body>
        <?php
        include_once "pages/template_footer.php";
        echo("</html>");
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


                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }

                $data[] = [
                    "confirmed" => true,
                    "ip" => $ip,
                    "time" => time()
                ];

                file_put_contents("confirmations" . LYDS_DATA_FILEEXTENSION, serialize($data));
                break;
            default:
                die("Invalid post request");
        }

        goto get;
    }
}
?>