<?php
$image = get_image();
$viewed = 0;
$cache_file = "cache/images/{$image}" . LYDS_DATA_FILEEXTENSION;

if( !file_exists( $cache_file ) )
{

    $file_name = get_map_filename($image);

    if (!file_exists($file_name))
        die("No files found...");
    else
        $images = read_data($file_name);

    if (!isset($images[$image]))
        die("Image not found...");

    $file = $images[$image];
    $rand = rand(0, count($images));
}
else
{

    $cache_file = read_data( $cache_file );
    $viewed = $cache_file["viewed"];
    $file = $cache_file["image"];

    if( isset( $stats ) && isset( $stats["image_count"] ) )
        $rand = rand(0, $stats["image_count"]);
    else
        $rand = rand(0,1000);
}

$image_location = "?hotlink&images={$image}";
?>
<script>
    document.onkeydown = checkKey;

    function checkKey(e) {

        e = e || window.event;

        if (e.keyCode == '82') {
            window.location.href = "<?=make_link("", ["images" => $rand])?>";
        } else if (e.keyCode == '37') {
            <?php
            if( $image > 0 )
            {
            ?>
            window.location.href = "<?=make_link("", ["images" => $image - 1])?>";
            <?php
            }
            ?>
        } else if (e.keyCode == '39') {
            <?php
            if( isset( $stats ) && isset($stats["image_count"]) )
            {
            if( $image < $stats["image_count"] - 1 )
            {
            ?>
            window.location.href = "<?=make_link("", ["images" => $image + 1])?>";
            <?php
            }
            }
            else
            {
            ?>
            window.location.href = "<?=make_link("", ["images" => $image + 1])?>";
            <?php
            }
            ?>
        }
    }
</script>
<fieldset>
    <h2>
        Lyd's <i class="pinktext">Sissy</i> Captions #<?= $image ?>: <?= $file["filename"] ?>
        <span><?= $file["extension"] ?></span>
        <span style="float: right;">
            <?php
            if ($image > 0)
            {
            ?>
            <a href="<?= make_link("", ["images" => $image - 1]) ?>">go back<a>
            or
            <?php
            }
            ?>
                    <?php
                    if (isset( $stats ) && isset($stats["image_count"])) {
                        if ($image < $stats["image_count"] - 1) {
                            ?>
                            <a href="<?= make_link("", ["images" => $image + 1]) ?>">go forward</a>
                            <?php
                        }
                    } else {
                        ?>
                        <a href="<?= make_link("", ["images" => $image + 1]) ?>">go forward</a>
                        <?php
                    }
                    ?>
            or
            <a href="<?= make_link("", ["images" => $rand]) ?>">go random</a>
            or
            <a href="<?= make_link("", ["list" => ""]) ?>">view list</a>
            or
            <a href="<?= make_link("", ["history" => ""]) ?>">view history</a>
        </span>
    </h2>
    <img class="mainimage"
         src="<?= $image_location ?>" alt="sissy image">
    <h4>
        <span style="font-size: 80%; color: hotpink;">This caption has been viewed <?=$viewed?> times.</span>
        You have viewed <i style="color: hotpink;"><?= @count(@$_SESSION["images"]) ?></i> captions.
        <span style="font-size: 100%; float: right;">
            autoplay: <a onclick="clittymode()" id="text" style="color: hotpink">clittymode (off)</a>
        </span>
    </h4>
</fieldset>
<script>
    function clittymode() {

        if (localStorage.getItem("clittymode") === null) {

            localStorage.setItem("clittymode", "false");
        } else if (localStorage.getItem("clittymode") === "true") {

            localStorage.setItem("clittymode", "false");
            window.location.reload();
        } else {
            localStorage.setItem("clittymode", "true");
            window.location.reload();
        }
    }

    if (localStorage.getItem("clittymode") === "true") {
        document.getElementById("text").innerText = "clittymode (on)";
    } else
        document.getElementById("text").innerText = "clittymode (off)";

    if (localStorage.getItem("clittymode") === "true") {

        setTimeout(function () {
            <?php
            if( isset( $stats ) && isset($stats["image_count"]) )
            {
            if( $image < $stats["image_count"] - 1 )
            {
            ?>
            window.location.href = "<?=make_link("", ["images" => $image + 1])?>";
            <?php
            }
            }
            else
            {
            ?>
            window.location.href = "<?=make_link("", ["images" => $image + 1])?>";
            <?php
            }
            ?>
        }, 4000);
    }
</script>
<?php
//update viewed images if activated
if (session_status() === PHP_SESSION_ACTIVE && LYDS_ENABLE_VIEWED) {

    if (!isset($_SESSION["images"])) {
        $_SESSION["images"] = [];
        $_SESSION["images"][$image] = ["image" => $file, "time" => time()];
    } else
        $_SESSION["images"][$image] = ["image" => $file, "time" => time()];
}
?>
