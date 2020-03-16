<?php
//stops direct access to this file through http browser :)
if( !defined("LYDS_ENABLE_GENERATION") )
{
    http_response_code(404);
    exit;
}
?>
<html lang="en">
    <?php
    include_once "templates/template_header.php";
    ?>
    <body>
        <?php
        $image = get_image();
        $viewed = 0;
        $rand = 0;
        $_ = "cache/images/{$image}" . LYDS_DATA_FILEEXTENSION;

        if (!file_exists( $_ )) {

            $file_name = get_map_filename($image);

            if (!file_exists($file_name))
                die("No files found...");
            else
                $images = read_data($file_name);

            if (!isset($images[$image]))
                die("Image not found...");

            $file = $images[$image];
            $rand = rand(0, count($images));
            unset( $images );
        } else {

            $cache_file = read_data( $_ );
            $file = $cache_file["image"];
            $viewed = $cache_file["viewed"];
            unset( $cache_file );

            if (isset($stats) && isset($stats["image_count"]))
                $rand = rand(0, $stats["image_count"]);
        }

        $image_location = "?hotlink&images={$image}";
        ?>
        <fieldset>
            <?php
                include "navbar.php";
            ?>
            <p style="text-align: center; color: goldenrod; font-size: 75%; padding-bottom: 10px; margin: 0;">We will be moving
                to our own server soon! Stay put while we transfer things over. Your continued donations are appreciated!</p>
            <img class="mainimage" id="touchsurface"
                 src="<?= $image_location ?>" alt="sissy image">
            <div style="margin-top: 12px; text-align: left;">
                <span style="font-size: 80%; color: hotpink;">This caption has been viewed <?= $viewed ?> times.</span>
                You have viewed <i style="color: hotpink;"><?= @count(@$_SESSION["images"]) ?></i> captions.
                <span style="font-size: 100%; float: right;">
                autoplay: <a onclick="clittymode()" id="text" style="color: hotpink">on (10s)</a>
                & <a onclick="openFullscreen()" id="text_two" style="color: hotpink">larger</a>
                </span>
            </div>
        </fieldset>
        <script>
            var image = <?=$image?>;
            var rand = <?=$rand?>;
            var image_count = <?=@$stats["image_count"]?>;
        </script>
        <script src="<?=LYDS_HOSTING_SUBFOLDER?>js/images.js"></script>
        <script>

            if (localStorage.getItem("clittymode") === "true") {
                document.getElementById("text").innerText = "on 10s";
            } else
                document.getElementById("text").innerText = "off";

            if (localStorage.getItem("clittymode") === "true") {

                setTimeout(function () {
                    if (image < image_count)
                        window.location.href = "?images=" + Math.abs(image + 1);
                }, 10000);
            }

            swipedetect(elem, function (swipedir) {
                if (swipedir == 'left')
                    if (image > 0)
                        window.location.href = "?images=" + Math.abs(image - 1);

                if (swipedir == 'right')
                    if (image < image_count)
                        window.location.href = "?images=" + Math.abs(image + 1);
            });

            document.onkeydown = checkKey;
        </script>
    </body>
    <?php
        include_once "templates/template_footer.php";
    ?>
</html>