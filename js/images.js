if (typeof rand === 'undefined')
    var rand = 0;

if (typeof image === 'undefined')
    var image = 0;

if (typeof image_count === 'undefined')
    var image_count = BigInt;

var elem = document.getElementById("touchsurface");

function checkKey(e) {

    e = e || window.event;
    if (e.keyCode == '82') {
        window.location.href = "?images=" + Math.abs(rand);
    } else if (e.keyCode == '37') {
        if (image > 0)
            window.location.href = "?images=" + Math.abs(image - 1);
    } else if (e.keyCode == '39') {
        if (image < image_count) {
            window.location.href = "?images=" + Math.abs(image + 1);
        }
    }
}

function clittymode() {

    if (localStorage.getItem("clittymode") === null) {

        localStorage.setItem("clittymode", "false");
    } else if (localStorage.getItem("clittymode") === "true") {

        localStorage.setItem("clittymode", "false");
        window.location.reload();
    } else {
        localStorage.setItem("clittymode", "true");
        window.location.reload();
    }
}

function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) { /* Firefox */
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE/Edge */
        elem.msRequestFullscreen();
    }
}

function swipedetect(el, callback) {

    var touchsurface = el,
        swipedir,
        startX,
        startY,
        distX,
        distY,
        threshold = 150, //required min distance traveled to be considered swipe
        restraint = 100, // maximum distance allowed at the same time in perpendicular direction
        allowedTime = 300, // maximum time allowed to travel that distance
        elapsedTime,
        startTime,
        handleswipe = callback || function (swipedir) {
        };

    touchsurface.addEventListener('touchstart', function (e) {
        var touchobj = e.changedTouches[0];
        swipedir = 'none';
        dist = 0;
        startX = touchobj.pageX;
        startY = touchobj.pageY;
        startTime = new Date().getTime(); // record time when finger first makes contact with surface
        e.preventDefault()
    }, false);

    touchsurface.addEventListener('touchmove', function (e) {
        e.preventDefault() // prevent scrolling when inside DIV
    }, false);

    touchsurface.addEventListener('touchend', function (e) {
        var touchobj = e.changedTouches[0];
        distX = touchobj.pageX - startX; // get horizontal dist traveled by finger while in contact with surface
        distY = touchobj.pageY - startY; // get vertical dist traveled by finger while in contact with surface
        elapsedTime = new Date().getTime() - startTime; // get time elapsed
        if (elapsedTime <= allowedTime) { // first condition for awipe met
            if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint) { // 2nd condition for horizontal swipe met
                swipedir = (distX < 0) ? 'left' : 'right' // if dist traveled is negative, it indicates left swipe
            } else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint) { // 2nd condition for vertical swipe met
                swipedir = (distY < 0) ? 'up' : 'down' // if dist traveled is negative, it indicates up swipe
            }
        }
        handleswipe(swipedir);
        e.preventDefault()
    }, false)
}