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
        include_once "templates/template_header.php";
    ?>
    <body style="text-align: center;">
    <form method="post">
        <h1 class="bounce animated">
            [lyds captions]
        </h1>
        <h3>
            Adult Content Disclaimer
        </h3>
        <h4>
            [this website has lots of d**** and is not for kids]
        </h4>
        <p>
            Warning this website has porn on it, specifically, <a href="https://www.reddit.com/r/Sissyperfection/">Sissy/Sissy
                Hypnotherapy</a> porn. This is porn meant to trick the mind. Continuing could mean that you are hypnotised. Like,
            for real. So you have to be over 18 to continue. By clicking the button below you confirm you
            are over 18.
        </p>
        <input type="submit" style="margin-left: auto; margin-right: auto; font-size: 4vh; width: 90vw; height: 20vh;" value="I am an adult">
        <input type="hidden" name="action" value="confirm">
    </form>
    </body>
    <?php
        include_once "templates/template_footer.php";
    ?>
</html>