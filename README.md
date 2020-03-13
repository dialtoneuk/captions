# What is this

Captions is a really simple procedural based PHP web application which allows
users to view image sets in a chronological or random fashion. This is best
suited to websites wishing to display *ahem* pornography.

## Features

* Self contained and uses no MySQL. Perfect for hosting with limited access to sudo and other commands.
* Can handle **millions** of images at once.
* View counting and other statistics.
* Implements a custom caching system and isn't resource intensive
* Adult Disclaimer warning with confirmed sessions logged for legal reasons.
* Easy to edit for PHP Beginners.
* History functionality (images viewed stored in session).


## Installation 

Captions can be installed directly from github. Simply download this project into
your web servers root directory (this is your public_html folder on some installations).

Once installed, open **index.php** and change the following line to
your own unique password.

```php
define("LYDS_GENERATION_PASSWORD", "password"); //change this to your own password
```

Then, create a folder in the same directory of which you downloaded this project too create
a folder called ``/images/``. Everything inside this folder will be scraped automatically by
the script and be viewable publicly. 

You can have as many sub-folders as you want as this is recursive, only ``jpg, png and gif`` images will be targeted. Everything
else will be ignored.

Once you have filled the images folder with the various images you would like to show, and you
have set a new generation password. Go to where your server is located in your
browser and enter the following URL, filling in the information required.

``www.my-server-here.com/?generate&password=my-generation-password``

Please wait a moment while the script maps your content. With 100,000 images I've found
it takes roughly 3 minutes to create the required files. Once completed, the page will
update and you have finished installing captions.

Whenever you add new images you will need to go back to this link and regenerate the
image database.

## Settings

Captions comes with various declarations which are meant to be edited by a server administrator in the ``index.php``. At the
top of ``index.php`` is where you can find these decelerations. Below is a brief description of each deceleration.

* ``LYDS_ENABLE_GENERATION`` Enables/Disables generation page, set to false once you have finished installing.
* ``LYDS_ENABLE_VIEWED`` Will be changed in later version, turns off view counting on images.
* ``LYDS_ENABLE_STATS``  Enables/Disables stats. Stats are needed for random image mode.
* ``LYDS_GENERATION_PASSWORD`` Password needed to generate data. Change this from its default value.
* ``LYDS_IMAGE_FOLDER`` Location of images. Must have directory separator at end of string.
* ``LYDS_HOSTING_SUBFOLDER`` If your hosting is something like *site.com/mywebsite/index.php*, make this value *mywebsite/*
* ``LYDS_PAYPAL_PREFIX`` Your paypal.me prefix.
* ``LYDS_PAGE_MAX`` The maximum number of elements to display on a page. Higher doesnt mean its harder on the server since it caches anyway. Just annoying for end user.
* ``LYDS_STATS_REFRESHRATE`` The default value will collect new stats every 10 minutes.
* ``LYDS_LIST_REFRESHRATE`` The default value will re-cache a list page every 60 minutes.
* ``LYDS_MODULUS_NUMBER`` This number is used to break up your map into 5 smaller files. Changing this higher will mean your image set is broken up into more files. Theoretically meaning less memory used.

## Credits

Llydia Lancaster (Programming, Research and Algorithms) 2019