<?php
$page = get_page();
$stats = [];

if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/"))
    @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache");

top:
if (file_exists("cache/{$page}.php") && time() < filemtime("cache/{$page}.php") + (60 * LYDS_LIST_REFRESHRATE))
    echo(file_get_contents("cache/{$page}.php"));
else {
    ob_start();
    $start = LYDS_PAGE_MAX * $page;
    $file_name = "data/list{$page}" . LYDS_DATA_FILEEXTENSION;

    if (file_exists($file_name) && time() < filemtime($file_name) + (60 * LYDS_LIST_REFRESHRATE))
    {

        $images = read_data($file_name);

        foreach( $images as $image=>$file )
        {

            $cache_file = "cache/images/{$image}" . LYDS_DATA_FILEEXTENSION;

            if (file_exists($cache_file))
            {

                $file = read_data($cache_file);
                $stats[$image] = $file["viewed"];
            }
        }
    }
    else {

        $opened_files = [];
        $images = [];

        //todo: could move this somewhere else
        for ($i = $start; $i < $start + LYDS_PAGE_MAX; $i++) {

            $cache_file = "cache/images/{$i}" . LYDS_DATA_FILEEXTENSION;

            if (file_exists($cache_file))
            {

                $file = read_data($cache_file);
                $images[$i] = $file["image"];
                $stats[$i] = $file["viewed"];
            }
            else
            {

                $file = get_map_filename($i);

                if (isset($opened_files[$file]))
                    $files = $opened_files[$file];
                else
                    $opened_files[$file] = read_data($file);

                if (isset($opened_files[$file][$i]))
                    $images[$i] = $opened_files[$file][$i];
            }
        }

        if (!empty($images))
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/{$file_name}", serialize($images));
    }
    ?>
    <script>
        document.onkeydown = checkKey;

        function checkKey(e) {

            e = e || window.event;

            if (e.keyCode == '82') {
                //nothing
            } else if (e.keyCode == '37') {
                <?php
                if( $page > 0 )
                {
                ?>
                window.location.href = "<?=make_link("", ["list" => "", "page" => $page - 1])?>";
                <?php
                }
                ?>
            } else if (e.keyCode == '39') {
                window.location.href = "<?=make_link("", ["list" => "", "page" => $page + 1])?>";
            }
        }
    </script>
    <fieldset>
        <?php
        $title = "page";
        include "navbar.php";
        ?>
        <?php

        if (empty($images))
            echo("<p>No images found...</p>");
        else
            foreach ($images as $image => $file) {
                $image_location = "?hotlink&images={$image}";
                ?>
                <div style="text-align: right;">
                    <p style="line-height: 1em; border-bottom: 1px solid deeppink; text-align: left;">
                        #<?= $image . " " . $file["filename"] . "." . $file["extension"] ?>
                        <a href='<?= make_link("", ["images" => $image]) ?>' style="color: hotpink; float: right;">view
                            caption</a>
                    </p>
                    <a href="<?= make_link("", ["images" => $image]) ?>">
                        <img class="smallimage"
                             src="<?= $image_location ?>" alt="sissy image">
                    </a>
                    <span style="font-size: 1vw;">
                           <?php
                           if( isset ($stats[ $image ] ) )
                               echo($stats[ $image ]);
                           ?>
                        Views
                    </span>
                </div>
                <?php
            }
        ?>
    </fieldset>
    <?php

    file_put_contents("cache/{$page}.php", ob_get_contents());
}
?>
