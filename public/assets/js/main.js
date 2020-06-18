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

            setTimeout(function () {
                scrolling = window.pageYOffset;
                parallaxeWallpaper(scrolling)
            }, 10);

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
    },2000)
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
    let imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                let image = entry.target;
                image.src = image.dataset.src;
                image.classList.remove('lazy');
                imageObserver.unobserve(image);
            }
        });
    });

    lazyloadImages.forEach(function(image) {
        imageObserver.observe(image);
    });
} else {
    let lazyloadThrottleTimeout;
    lazyloadImages = document.querySelectorAll(".entry article img");

    function lazyload () {
        if(lazyloadThrottleTimeout) {
            clearTimeout(lazyloadThrottleTimeout);
        }

        lazyloadThrottleTimeout = setTimeout(function() {
            let scrollTop = window.pageYOffset;
            lazyloadImages.forEach(function(img) {
                if(img.offsetTop < (window.innerHeight + scrollTop)) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                }
            });
            if(lazyloadImages.length === 0) {
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
