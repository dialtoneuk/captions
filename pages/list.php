<?php
$page = get_page();
$stats_images = [];

if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/"))
    @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache");

top:
if (LYDS_ENABLE_CACHE && file_exists("cache/{$page}.php") && time() < filemtime("cache/{$page}.php") + (60 * LYDS_LIST_REFRESHRATE))
    echo(file_get_contents("cache/{$page}.php"));
else {
    ob_start();
    $start = LYDS_PAGE_MAX * $page;
    $file_name = "data/list{$page}" . LYDS_DATA_FILEEXTENSION;

    if (LYDS_ENABLE_CACHE && file_exists($file_name) && time() < filemtime($file_name) + (60 * LYDS_LIST_REFRESHRATE)) {

        $images = read_data($file_name);

        foreach ($images as $image => $file) {

            $cache_file = "cache/images/{$image}" . LYDS_DATA_FILEEXTENSION;

            if (file_exists($cache_file)) {

                $file = read_data($cache_file);
                $stats_images[$image] = $file["viewed"];
            }
        }
    } else {

        $opened_files = [];
        $images = [];

        //todo: could move this somewhere else
        for ($i = $start; $i < $start + LYDS_PAGE_MAX; $i++) {

            $cache_file = "cache/images/{$i}" . LYDS_DATA_FILEEXTENSION;

            if (file_exists($cache_file)) {

                $file = read_data($cache_file);
                $images[$i] = $file["image"];
                $stats_images[$i] = $file["viewed"];
            } else {

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
        <p style="text-align: center;">
            <?php

            if (isset($stats) && isset($stats["page_count"]))
            {

                $counter = 0;
                for ($i = abs( $page - LYDS_PAGE_MAX); $i < $page; $i++) {

                    if( $counter > LYDS_PAGE_MAX )
                        break;

                    echo(sprintf("<a href='%s'>%d</a> ", make_link("", ["list" => "", "page" => $i]), $i));

                    $counter++;
                };

                $counter = 0;
                for ($i = 0; $i < $stats["page_count"]; $i++) {

                    if( $counter > LYDS_PAGE_MAX )
                        break;

                    if( $i < $page )
                        continue;

                    if( isset( $stats ) && isset( $stats["page_count"] ) )
                        if($i > $stats["page_count"] )
                            break;

                    if ($i == $page)
                        echo("<u style='padding-left:'>here</u> ");
                    else
                        echo(sprintf("<a href='%s'>%d</a> ", make_link("", ["list" => "", "page" => $i]), $i));

                    $counter++;
                };
            }

            ?>
        </p>
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
                    <span style="font-size: 2vw; float: right;">
                           <?php
                           if (isset ($stats_images[$image]))
                               echo($stats_images[$image]);
                           ?>
                        Views
                    </span>
                    <img class="smallimage" onclick="window.location.href = '<?= make_link("", ["images" => $image]) ?>'"
                         src="<?= $image_location ?>" alt="sissy image">
                </div>
                <?php
            }
        ?>
        <p>
        <span style="float: left; font-size: 150%;">
            <a href="<?=make_link("",["list"=>"","page" => abs( $page - 1 ) ] )?>"><--</a>
        </span>
        <span style="float: right; font-size: 150%;">
            <a href="<?=make_link("",["list"=>"","page" => abs( $page  + 1 ) ] )?>">--></a>
        </span>
        </p>
    </fieldset>
    <?php

    file_put_contents("cache/{$page}.php", ob_get_contents());
}
?>
