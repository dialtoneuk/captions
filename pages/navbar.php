<?php
//stops direct access to this file through http browser :)
if( !defined("LYDS_ENABLE_GENERATION") )
{
    http_response_code(404);
    exit;
}
?>
<h2>
<?php   /** @noinspection ALL */
        if( !isset( $current_page ) )
            $current_page = "image";

        if( !isset( $file ) )
            $file = [];

        if( !isset( $stats ) )
            $stats = [];

        switch( $current_page )
        {
            case "image":
                echo(sprintf('<i class="pinktext">Lyds Captions</i> : <u style="font-size: 1.25vw;">%s.%s</u>', @$file["filename"], $file["extension"]));
                break;
            case "list":
                echo(sprintf('Page <i class="pinktext">%d</i>: showing %s elements', @$page, LYDS_PAGE_MAX ));
                break;
            case "history":
                echo(sprintf('Page <i class="pinktext">%d</i>: showing %s elements', @$page, LYDS_PAGE_MAX ));
                break;
            case "images":
                echo(sprintf('<i class="pinktext">Lyds Captions</i> : <u style="font-size: 1.25vw;">%s.%s</u>', @$file["filename"], $file["extension"]));
                break;
        }
    ?>
    <span class="navbar" style="font-size: 70%;">
        <?php
        switch( $current_page )
        {
            case "image":
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
            case "images":
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
            case "list":
                {
                    if( $page > 0 )
                        echo( sprintf("<a href='%s'>go back</a> or ", make_link("", [$current_page => "", "page" => $page - 1]) ) );

                    echo(sprintf("<a href='%s'>go forward</a>", make_link("", [$current_page => "", "page" => $page + 1]) ) );

                    if( isset( $rand ) )
                        echo(sprintf("or <a href='%s'>random</a>", make_link("", [$current_page => "", "page" => $rand ]) ) );

                    echo(sprintf(" | <a href='%s'>home</a>", make_link("", ["" => ""]) ) );
                };
                break;
            case "history":
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
                echo("undefined page type: " . $current_page );
        }
        ?>
    </span>
</h2>