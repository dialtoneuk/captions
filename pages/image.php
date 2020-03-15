<?php
$image = get_image();
$viewed = 0;
$cache_file = "cache/images/{$image}" . LYDS_DATA_FILEEXTENSION;

if (!file_exists($cache_file)) {

    $file_name = get_map_filename($image);

    if (!file_exists($file_name))
        die("No files found...");
    else
        $images = read_data($file_name);

    if (!isset($images[$image]))
        die("Image not found...");

    $file = $images[$image];
    $rand = rand(0, count($images));
} else {

    $cache_file = read_data($cache_file);
    $viewed = $cache_file["viewed"];
    $file = $cache_file["image"];

    if (isset($stats) && isset($stats["image_count"]))
        $rand = rand(0, $stats["image_count"]);
    else
        $rand = rand(0, 1000);
}

$image_location = "?hotlink&images={$image}";
?>
<script>
</script>
<fieldset>
    <?php
        include "navbar.php";
    ?>
    <p style="text-align: center; color: goldenrod; font-size: 75%; padding-bottom: 10px; margin: 0;">We will be moving to our own server soon! Stay put while we transfer things over. Your continued donations are appreciated!</p>
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
    var image_count = <?php if (isset($stats) && isset($stats["image_count"])) echo $stats["image_count"]; ?>;
    var elem = document.getElementById("touchsurface");

    function checkKey(e) {

        e = e || window.event;
        if (e.keyCode == '82') {
            window.location.href = "<?=make_link("", ["images" => $rand])?>";
        } else if (e.keyCode == '37') {
            if (image > 0)
                window.location.href = "<?=make_link("", ["images" => $image - 1])?>";
        } else if (e.keyCode == '39') {
            if (image < image_count) {
                window.location.href = "<?=make_link("", ["images" => $image + 1])?>";
            }
        }
    }

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

    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) { /* Firefox */
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE/Edge */
            elem.msRequestFullscreen();
        }
    }


    document.onreadystatechange = function () {

        document.onkeydown = checkKey;

        function swipedetect(el, callback) {

            var touchsurface = el,
                swipedir,
                startX,
                startY,
                distX,
                distY,
                threshold = 150, //required min distance traveled to be considered swipe
                restraint = 100, // maximum distance allowed at the same time in perpendicular direction
                allowedTime = 300, // maximum time allowed to travel that distance
                elapsedTime,
                startTime,
                handleswipe = callback || function (swipedir) {
                };

            touchsurface.addEventListener('touchstart', function (e) {
                var touchobj = e.changedTouches[0];
                swipedir = 'none';
                dist = 0;
                startX = touchobj.pageX;
                startY = touchobj.pageY;
                startTime = new Date().getTime(); // record time when finger first makes contact with surface
                e.preventDefault()
            }, false);

            touchsurface.addEventListener('touchmove', function (e) {
                e.preventDefault() // prevent scrolling when inside DIV
            }, false);

            touchsurface.addEventListener('touchend', function (e) {
                var touchobj = e.changedTouches[0];
                distX = touchobj.pageX - startX; // get horizontal dist traveled by finger while in contact with surface
                distY = touchobj.pageY - startY; // get vertical dist traveled by finger while in contact with surface
                elapsedTime = new Date().getTime() - startTime; // get time elapsed
                if (elapsedTime <= allowedTime) { // first condition for awipe met
                    if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint) { // 2nd condition for horizontal swipe met
                        swipedir = (distX < 0) ? 'left' : 'right' // if dist traveled is negative, it indicates left swipe
                    } else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint) { // 2nd condition for vertical swipe met
                        swipedir = (distY < 0) ? 'up' : 'down' // if dist traveled is negative, it indicates up swipe
                    }
                }
                handleswipe(swipedir);
                e.preventDefault()
            }, false)
        }

        if (localStorage.getItem("clittymode") === "true") {
            document.getElementById("text").innerText = "(on) 10s";
        } else
            document.getElementById("text").innerText = "(off)";

        if (localStorage.getItem("clittymode") === "true") {

            setTimeout(function () {
                if (image < image_count)
                    window.location.href = "<?=make_link("", ["images" => $image + 1])?>";
            }, 10000);
        }

        swipedetect(elem, function (swipedir) {
            if (swipedir == 'left')
                if (image > 0)
                    window.location.href = "<?=make_link("", ["images" => $image - 1])?>";

            if (swipedir == 'right')
                if (image < image_count)
                    window.location.href = "<?=make_link("", ["images" => $image + 1])?>";
        });
    }
</script>
<script>

</script>
<script>

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
