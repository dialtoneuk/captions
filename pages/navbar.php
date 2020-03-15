<h2>
<?php   /** @noinspection ALL */
        if( !isset( $title ) )
            $title = "default";

        if( !isset( $file ) )
            $file = [];

        if( !isset( $stats ) )
            $stats = [];

        if( !isset( $current_page ) )
            $current_page = "list";

        switch( $title )
        {
            case "default":
                echo(sprintf('<i class="pinktext">Lyds Captions</i> : <u style="font-size: 1.25vw;">%s.%s</u>', @$file["filename"], $file["extension"]));
                break;
            case "page":
                echo(sprintf('Page <i class="pinktext">%d</i>: showing %s elements', @$page, LYDS_PAGE_MAX ));
        }
    ?>
    <span style="float: right; font-size: 1vw; line-height: 1vw" class="navbar">
        <?php
        switch( $title )
        {
            case "default":
                {
                    if( $image > 0 )
                        echo( sprintf("<a href='%s'>go back</a> or ", make_link("", ["images" => $image - 1]) ) );

                    echo(sprintf("<a href='%s'>go forward</a>", make_link("", ["images" => $image + 1]) ) );

                    if( isset( $rand ) )
                        echo(sprintf(" or <a href='%s'>random</a>", make_link("", ["images" => $rand ]) ) );

                    echo(sprintf(" | <a href='%s'>all</a>", make_link("", ["list" => ""]) ) );
                    echo(sprintf(" or <a href='%s'>your history</a>", make_link("", ["history" => "" ] ) ) );
                };
                break;
            case "page":
                {
                    if( $page > 0 )
                        echo( sprintf("<a href='%s'>go back</a> or ", make_link("", [$current_page => "", "page" => $page - 1]) ) );

                    echo(sprintf("<a href='%s'>go forward</a>", make_link("", [$current_page => "", "page" => $page + 1]) ) );

                    if( isset( $rand ) )
                        echo(sprintf("or <a href='%s'>random</a>", make_link("", [$current_page => "", "page" => $rand ]) ) );

                    echo(sprintf(" | <a href='%s'>home</a>", make_link("", ["" => ""]) ) );
                };
                break;
            default:
                echo("Invalid title: " . $title );
        }
        ?>
    </span>
</h2>