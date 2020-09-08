let beacon = document.querySelectorAll('.trigger');
for (let i = 0; i < beacon.length; i++) {
    beacon[i].classList.remove("triggered")
}

//animations de chargement
let loader = document.getElementById("loader");
setTimeout(function () {
    loader.classList.add('explode')
}, 10);

setTimeout(function () {
    if (document.readyState !== "complete")
        loader.classList.add('vortex')
}, 4100);
// Popup de confirmation pour suppression et validation -------------------------------------------------------------------

const sup = document.getElementById('suppression');
const val = document.getElementById('validation');

if (sup) {
    sup.addEventListener('click', function (e) {
        alert("Confirmer la suppression ?")
    })
}


if (val) {
    val.addEventListener('click', function (e) {
        alert("Confirmer la validation ?")
    })
}

window.addEventListener('load', function () {


    //Révélation de la page

    pageReveal();

    //Effet de parallaxe des éléments de background de la page------------------------------------------------------

    const body = document.getElementsByTagName("body")[0];

    let scrolling = window.pageYOffset;

    function parallaxeWallpaper(scrolling) {


        body.style.backgroundPosition =
            "0 " + scrolling * .4 + "px, " +
            "90% " + (80 + scrolling * .5) + "px," +
            "5% " + (480 + scrolling * .3) + "px," +
            "10% " + (480 + scrolling * .6) + "px," +
            "65% " + (600 + scrolling * .8) + "px";

    }

    if (window.innerWidth > 768) {


        parallaxeWallpaper(scrolling); //Actualisation des positions au chargement de la page

        //Ecouteur d'events pour tous les effets au scroll

        document.addEventListener('scroll', (e) => {
            window.requestAnimationFrame(function () {
                scrolling = window.pageYOffset;
                parallaxeWallpaper(scrolling)
            });
        });

    }

    //Effet de perspective au hover

    /*
    let counter = 0;
    let updateRate = 20;

    function isTimeToUpdate() {
        return counter++ % updateRate === 0;
    }

    let containers = document.getElementsByClassName('index_item');
    let inners = document.getElementsByClassName('index_img');

    let mouse = {
        _x: 0,
        _y: 0,
        x: 0,
        y: 0,
        updatePosition: function(event) {
            let e = event || window.event;
            this.x = e.clientX - this._x;
            this.y = (e.clientY - this._y) * -1;
        },
        setOrigin: function(e) {
            this._x = e.offsetLeft + Math.floor(e.offsetWidth/2);
            this._y = e.offsetTop + Math.floor(e.offsetHeight/2);
        },
        show: function() { return '(' + this.x + ', ' + this.y + ')'; }
    };

    for (let i = 0; i < containers.length; i++) {

        let container = containers[i];
        let inner = inners[i];

        let update = function(event) {
            mouse.updatePosition(event);
            updateTransformStyle(
                (mouse.y / inner.offsetHeight/2).toFixed(2) *15,
                (mouse.x / inner.offsetWidth/2).toFixed(2) *15
            );
        };

        let updateTransformStyle = function(x, y) {
            let style = "rotateX(" + x + "deg) rotateY(" + y + "deg)";
            inner.style.transform = style;
        };

        let onMouseEnterHandler = function (event) {
            update(event);
        };
        let onMouseLeaveHandler = function () {
            inner.style = "";
        };
        let onMouseMoveHandler = function (event) {
            if (isTimeToUpdate()) {
                update(event);
            }
        };
        mouse.setOrigin(container);
        container.onmouseenter = onMouseEnterHandler;
        container.onmouseleave = onMouseLeaveHandler;
        container.onmousemove = onMouseMoveHandler;
    }

     */

});

//Transitions de pages

let allLinks = document.querySelectorAll("a");
let glassWall = document.getElementById("glass_wall");
let shards = document.getElementsByClassName("eclat");

//Le loader apparaît pendant que la page finit de charger
loader.style.opacity = "1";

let pageReveal = function () {
    let x = 0;
    let y = 0;
    let d = 0;

    for (let i = 0; i < shards.length; i++) {
        x = Math.floor(Math.random() * (200 - -200 + 1)) + -200;
        y = Math.floor(Math.random() * (200 - -200 + 1)) + -200;
        d = Math.floor(Math.random() * (10 - -10 + 1)) + -10;

        shards[i].style.transform = "translate3d(" + y + "px," + x + "px,0px) rotateY(" + d + "deg)";
        shards[i].style.opacity = "0";
        glassWall.style.transitionDelay = "1.5s";
        glassWall.style.zIndex = "-100";
        loader.style.opacity = "0";
        loader.style.zIndex = "-100";
    }

    setTimeout(function () {
        loader.classList.remove('explode');
        loader.classList.remove('vortex');
    }, 2000)
};

let pageHide = function () {
    let x = 0;
    let y = 0;
    let d = 0;

    for (let i = 0; i < shards.length; i++) {
        shards[i].style.transform = "translate3d(" + y + "px," + x + "px,0px) rotateY(" + d + "deg)";
        shards[i].style.opacity = "1";
        glassWall.style.transitionDelay = "0s";
        glassWall.style.zIndex = "101";
    }
};

for (let i = 0; i < allLinks.length; i++) {
    allLinks[i].addEventListener("click", function (e) {
        if (this.target !== "_blank" && !this.classList.contains('anchor')) {
            e.preventDefault();
            let href = this.href;
            pageHide();
            setTimeout(function () {
                window.location = href;
            }, 1500);
        }
    })
}

//fonctions d'apparition des éléments au scroll

let section = document.querySelectorAll('section');

let progression = function (elem) {
    let scrolling = window.scrollY;
    let position = scrolling + elem.getBoundingClientRect().top;

    return (
        scrolling >= position + window.innerHeight * -.75
    );
};


//Immédiatement au chargement de la page, dans le cas où l'utilisateur ne se trouve pas en haut
for (let i = 0; i < beacon.length; i++) {
    if (progression(beacon[i])) {
        beacon[i].classList.add("triggered")
    }

    document.addEventListener('scroll', function () {
        if (progression(beacon[i])) {
            beacon[i].classList.add("triggered")
        } /* else if (beacon[i].classList.contains("triggered")) {
                beacon[i].classList.remove("triggered")
            } */
    });
}

//lazyloading des images des articles (utilisation de l'API Intersection Observer
//et méthode alternative si non supportée

let lazyloadImages;

if ("IntersectionObserver" in window) {
    lazyloadImages = document.querySelectorAll(".lazy, .entry article img");
    let imageObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                let image = entry.target;
                image.src = image.dataset.src;
                image.complete = image.classList.remove('lazy');
                imageObserver.unobserve(image);
            }
        });
    });

    lazyloadImages.forEach(function (image) {
        imageObserver.observe(image);
    });
} else {
    let lazyloadThrottleTimeout;
    lazyloadImages = document.querySelectorAll(".entry article img");

    function lazyload() {
        if (lazyloadThrottleTimeout) {
            clearTimeout(lazyloadThrottleTimeout);
        }

        lazyloadThrottleTimeout = setTimeout(function () {
            let scrollTop = window.pageYOffset;
            lazyloadImages.forEach(function (img) {
                if (img.offsetTop < (window.innerHeight + scrollTop)) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                }
            });
            if (lazyloadImages.length === 0) {
                document.removeEventListener("scroll", lazyload);
                window.removeEventListener("resize", lazyload);
                window.removeEventListener("orientationChange", lazyload);
            }
        }, 20);
    }

    document.addEventListener("scroll", lazyload);
    window.addEventListener("resize", lazyload);
    window.addEventListener("orientationChange", lazyload);
}

//Rédaction de nouvelles entrées du journal

let mce = document.getElementsByClassName("tinymce");

//Le required généré crée des erreurs au moment de soumettre le formulaire:
// On l'enlève avant l'initiation.
if (mce[0]) {
    mce[0].removeAttribute("required");
    tinymce.init({
        selector: '.tinymce',
        convert_urls: false,
        statusbar: false,
        plugins: "image link code",
        images_upload_url: '/attachment',
        // override default upload handler to simulate successful upload
        images_upload_handler: function (blobInfo, success, failure) {
            let xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '/attachment');

            xhr.onload = function () {
                let json;

                if (xhr.status !== 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                console.log(xhr.responseText);

                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        },
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },

    });
}

//Effet de loupe au hover (script inspiré de la ressource : https://www.w3schools.com/howto/howto_js_image_magnifier_glass.asp)
function magnify(imgID, zoom) {
    let img, glass, w, h, bw;
    img = document.getElementById(imgID);

    /* Create magnifier glass: */
    glass = document.createElement("DIV");
    glass.setAttribute("class", "img_magnifier_glass lazy");

    /* Insert magnifier glass: */
    img.parentElement.insertBefore(glass, img);

    /* Set background properties for the magnifier glass: */
    glass.style.backgroundImage = "url('" + img.dataset.src + "')";
    glass.style.backgroundRepeat = "no-repeat";
    glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
    console.log(img.width, img.height);
    bw = 3;
    w = glass.offsetWidth / 2;
    h = glass.offsetHeight / 2;

    /* Execute a function when someone moves the magnifier glass over the image: */
    glass.addEventListener("mousemove", moveMagnifier);
    img.addEventListener("mousemove", moveMagnifier);

    /*and also for touch screens:*/
    glass.addEventListener("touchmove", moveMagnifier);
    img.addEventListener("touchmove", moveMagnifier);

    function moveMagnifier(e) {
        let pos, x, y;
        /* Prevent any other actions that may occur when moving over the image */
        e.preventDefault();
        /* Get the cursor's x and y positions: */
        pos = getCursorPos(e);
        x = pos.x;
        y = pos.y;
        /* Prevent the magnifier glass from being positioned outside the image: */
        if (x > img.width - (w / zoom)) {
            x = img.width - (w / zoom);
        }
        if (x < w / zoom) {
            x = w / zoom;
        }
        if (y > img.height - (h / zoom)) {
            y = img.height - (h / zoom);
        }
        if (y < h / zoom) {
            y = h / zoom;
        }
        /* Set the position of the magnifier glass: */
        glass.style.left = (x - w) + "px";
        glass.style.top = (y - h) + "px";
        /* Display what the magnifier glass "sees": */
        glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
    }

    function getCursorPos(e) {
        let a, x = 0, y = 0;
        e = e || window.event;
        /* Get the x and y positions of the image: */
        a = img.getBoundingClientRect();
        /* Calculate the cursor's x and y coordinates, relative to the image: */
        x = e.pageX - a.left;
        y = e.pageY - a.top;
        /* Consider any page scrolling: */
        x = x - window.pageXOffset;
        y = y - window.pageYOffset;
        return {x: x, y: y};
    }
}

if (document.getElementsByClassName("franklin")[0]) {
    magnify("the_note", 2);
    magnify("king_william_map", 2);
    magnify("jr_note", 2);
}

//Affichage d'une image en grand

if (window.innerWidth > 750) {

    let wide_gallery = Array.prototype.slice.call(document.getElementsByClassName("expandable_picture"));
    let fullscreen_container = document.getElementById("fullscreen_container");
    let fullscreen_image = document.getElementById("fullscreen_image");
    let target = null

    function display_fullscreen(e) {
        target = e.currentTarget;
        fullscreen_image.src = target.src;
        fullscreen_container.classList.remove("inactive");
        fullscreen_container.classList.add("active");
        document.addEventListener('keydown', gallerySwitch)
    }

    function playGalleryTransition() {
        fullscreen_image.classList.add('gallery_transition')
        setTimeout(function () {
            fullscreen_image.classList.remove('gallery_transition')
        }, 800)
    }

    function close_fullscreen() {
        fullscreen_container.classList.add("inactive");
        fullscreen_container.classList.remove("active");
        document.removeEventListener('keydown', gallerySwitch)
    }

    fullscreen_container.addEventListener("click", close_fullscreen);

    for (let i = 0; i < wide_gallery.length; i++) {
        wide_gallery[i].addEventListener("click", display_fullscreen);
    }

    function gallerySwitch (e) {
        let targetIndex = wide_gallery.indexOf(target);
        switch (e.key) {
            case ('ArrowLeft') :
                if (targetIndex !== 0) {
                    target = wide_gallery[targetIndex - 1]
                    //playGalleryTransition();
                        fullscreen_image.src = target.dataset.src;

                }
                break;
            case('ArrowRight'):
                if (targetIndex !== wide_gallery.length - 1) {
                    target = wide_gallery[targetIndex + 1]
                    //playGalleryTransition()
                        fullscreen_image.src = target.dataset.src;
                }
                break;
        }
    }
}
