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
    $page = get_page();

    if( LYDS_ENABLE_CACHE )
        ob_start();

    if( has_list_cache( $page ) )
        $images = get_list_cache( $page );
    else
        $images = [];

    include_once "templates/template_header.php";
    ?>
    <body>
    <fieldset>
        <?php
            include "navbar.php";
        ?>
        <div style="display: block; width: 100%; margin-top: 12px; height: 50px; float: left; border: 1px solid hotpink;">
            <p style="text-align: center;">
                <?php
                if( $page > 0 )
                    echo(sprintf("<a href='%s'><<<<</a> ", make_link("", ["list" => "", "page" => $page - 1])));

                if (isset($stats) && isset($stats["page_count"]))
                {

                    $counter = 0;
                    $last_i = $page - LYDS_PAGE_MAX;

                    if( $page - LYDS_PAGE_MAX < 0 )
                        $last_i = 0;

                    for ($i = $last_i; $i < $page + LYDS_PAGE_MAX; $i++) {

                        if( $last_i < 0 )
                            continue;

                        if( $i > $page )
                            continue;

                        if( $i == $page )
                            continue;

                        if( $counter < LYDS_PAGE_MAX  )
                            echo(sprintf("<a href='%s'>%d</a> ", make_link("", ["list" => "", "page" => $i ]), $i));
                        else
                            continue;

                        $last_i = $i;

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

                if( isset( $stats ) && isset( $stats["page_count"] ) && $stats["page_count"] > $page )
                    echo(sprintf("<a href='%s'>>>>></a> ", make_link("", ["list" => "", "page" => $page + 1])));
                ?>
            </p>
        </div>
        <div>
        <?php

        if (empty($images))
            echo("<p>No images found...</p>");
        else
            foreach ($images as $image => $file) {

                if( !isset( $file["viewed"] ) )
                    $view_count = 0;
                else
                    $view_count = @$file["viewed"];

                $file = $file["image"];
                $image_location = "?hotlink&images={$image}";
                ?>
                <div style="text-align: right; width: 200px; margin-left: 12px; margin-top: 12px; height:270px; float: left; border: 1px solid gray;">
                    <p style="line-height: 1em; margin-left: 12px; border-bottom: 1px solid deeppink; text-align: left; font-size: 50%;">
                        #<?= $image . " " . $file["filename"] . "." . $file["extension"] ?>
                    </p>
                    <img class="smallimage" style="width: auto; cursor: pointer; height: 200px; margin-left: auto; margin-right: auto;" onclick="window.location.href = '<?= make_link("", ["images" => $image]) ?>'"
                         src="<?= $image_location ?>" alt="sissy image">
                    <p style="font-size: 1vw; text-align: center;"><?=$view_count?> Views</p>
                </div>
                <?php
            }
        ?>
            <div style="display: block; width: 100%; margin-top: 12px; height: 50px; float: left; border: 1px solid hotpink;">
                <p style="text-align: center;">
                    <?php
                    if( $page > 0 )
                        echo(sprintf("<a href='%s'><<<<</a> ", make_link("", ["list" => "", "page" => $page - 1])));

                    if (isset($stats) && isset($stats["page_count"]))
                    {

                        $counter = 0;
                        $last_i = $page - LYDS_PAGE_MAX;

                        if( $page - LYDS_PAGE_MAX < 0 )
                            $last_i = 0;

                        for ($i = $last_i; $i < $page + LYDS_PAGE_MAX; $i++) {

                            if( $last_i < 0 )
                                continue;

                            if( $i > $page )
                                continue;

                            if( $i == $page )
                                continue;

                            if( $counter < LYDS_PAGE_MAX  )
                                echo(sprintf("<a href='%s'>%d</a> ", make_link("", ["list" => "", "page" => $i ]), $i));
                            else
                                continue;

                            $last_i = $i;

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

                    if( isset( $stats ) && isset( $stats["page_count"] ) && $stats["page_count"] > $page )
                        echo(sprintf("<a href='%s'>>>>></a> ", make_link("", ["list" => "", "page" => $page + 1])));
                    ?>
                </p>
            </div>
        </div>


    </fieldset>
    </body>
    <?php
        include_once "templates/template_footer.php";

        if( LYDS_ENABLE_CACHE )
            file_put_contents("cache/{$page}.php", ob_get_contents());
    ?>
</html>