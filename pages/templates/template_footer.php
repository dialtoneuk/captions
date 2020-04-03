<?php
//stops direct access to this file through http browser :)
if( !defined("LYDS_ENABLE_GENERATION") )
{
    http_response_code(404);
    exit;
}
?>
<footer style="text-align: right">
    <p style="font-size: 75%;">
        Total Captions: <?php if( isset( $stats ) && isset($stats["image_count"]) ) echo @$stats["image_count"]; else echo "0" ?> <br>
        Total Sissies: <?php if( isset( $stats ) && isset($stats["confirmation_count"]) ) echo @$stats["confirmation_count"] . ""; else echo "0"?><br>
        Total Pages: <?php if( isset( $stats ) && isset($stats["page_count"]) ) echo @$stats["page_count"] . ""; else echo "0"?><br>
        Programming by <a href="http://www.instagram.com/lydsartandmusic">llydia lancaster</a> <?= date("Y", time()) ?>. <br>
        Your <span style="color: hotpink; font-size: 100%;">arrow keys</span> can be used to navigate this website.<br>
        You can also swipe left/right if you are viewing on a phone. You can also swipe up to pick an image randomly.<br>
        <i>Stats were last updated on <?php if( isset( $stats ) ) echo date("d/m/Y H:i:s", @$stats["last_generated"]) ?></i>
    </p>
    <h2>
        <a href="http://www.paypal.me/<?= LYDS_PAYPAL_PREFIX ?>">donate here!</a>
    </h2>
</footer>