<footer style="text-align: right">
    <p style="font-size: 75%;">
        Total Captions: <?php if( isset( $stats ) ) echo @$stats["image_count"] ?> <br>
        Total Sissies: <?php if( isset( $stats ) ) echo 0 + @$stats["confirmation_count"] . ""; else echo "0"?><br>
        Programming by <a href="http://www.instagram.com/lydsartandmusic">llydia lancaster</a> <?= date("Y", time()) ?>. <br>
        Your <span style="color: hotpink; font-size: 100%;">arrow keys</span> can be used to navigate this website.<br>
        You can also swipe left/right if you are viewing on a phone.        <br>
        <i>Stats were last updated on <?php if( isset( $stats ) ) echo date("d/m/Y H:i:s", @$stats["last_generated"]) ?></i>
    </p>
    <h2>
        <a href="http://www.paypal.me/<?= LYDS_PAYPAL_PREFIX ?>">please donate</a>
    </h2>
</footer>