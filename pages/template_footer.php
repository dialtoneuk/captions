<footer style="text-align: center">
    <p>
        I have a total of <?php if( isset( $stats ) ) echo @$stats["image_count"] ?> captions. A total of <?php if( isset( $stats ) ) echo @$stats["confirmation_count"] ?>
        sissies have visited this site.
        I did not create any of the captions on this site. Programming by
        <a href="http://www.instagram.com/lydsartandmusic">lyds</a> <?= date("Y", time()) ?>. You can
        use the <i class="pinktext">left</i> arrow key to go back an image, <i class="pinktext">right</i>
        arrow key to go forward. The <i class="pinktext">R</i> key to go random.
        <br>
        <i>Stats were last updated on <?php if( isset( $stats ) ) echo date("d/m/Y H:i:s", @$stats["last_generated"]) ?></i>
    </p>
    <h2>
        <a href="http://www.paypal.me/<?= LYDS_PAYPAL_PREFIX ?>">please donate</a>
    </h2>
</footer>