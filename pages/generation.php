<?php
if( !defined("LYDS_ENABLE_GENERATION") )
{
    http_response_code(404);
    exit;
}

if (!LYDS_ENABLE_GENERATION)
    die("Generation is disabled...");

if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/data/"))
    @mkdir($_SERVER["DOCUMENT_ROOT"] . "/data/");

if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/"))
    @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache/");

if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/images/"))
    @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache/images/");

?>
<html>
    <?php
        include_once "templates/template_header.php";
    ?>
    <body>
        <?php
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
            echo("Generating hotlinks... <br>");
            generate_hotlinks($stats["image_count"]);
            echo("Done.. <br><a href='" . make_link("?", []) . "'>go home</a>");

            set_time_limit(30);
        }
        ?>
    </body>
    <?php
        include_once "templates/template_footer.php";
    ?>
</html>