<?php
$page = get_page();
$counter = 0;
$counter_two = 0;
$start = LYDS_PAGE_MAX * $page;
?>
<fieldset>
    <?php
    $title = "page";
    include "navbar.php";
    ?>
    <?php
    if (!isset($_SESSION["images"]) || empty($_SESSION["images"]))
        echo("<p>You haven't viewed anything yet..");
    else
        foreach ($_SESSION["images"] as $image => $file) {

            $time = $file["time"];
            $file = $file["image"];

            if ($start !== 0 && $counter < $start) {

                $counter++;
                continue;
            } else if ($counter_two >= LYDS_PAGE_MAX)
                break;

            $image_location = "?hotlink&images={$image}"
            ?>
            <div style="text-align: right;">
                <p style="line-height: 1em; border-bottom: 1px solid deeppink; text-align: left;">
                    #<?= $image . " " . $file["filename"] . "." . $file["extension"] ?>
                    viewed at <span style="color: hotpink; font-size: 100%;"><?= date("H:i:s", $time) ?></span>
                    <a href='<?= make_link("", ["images" => $image]) ?>' style="color: hotpink; float: right;">view
                        caption again</a>
                </p>
                <img class="smallimage"
                     src="<?= $image_location ?>" alt="sissy image">
            </div>
            <?php
            $counter_two++;
        }

    if ($counter_two == 0)
        echo("Page not found...");
    ?>
</fieldset>
