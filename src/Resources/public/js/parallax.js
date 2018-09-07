class BackgroundImage {

    constructor(object) {
        this.objBackground = $(object);
        let listOfImages = this.objBackground.find('img');
        if (listOfImages.length == 0) {
            return null;
        }
        this.img = listOfImages[0];
        this.src = '';
        this.currentBackgroundImage = this;
        $(this.img).load(this.update());

    }

    waitForImageLoaded(object) {
        if (!object.img.complete) {
            setTimeout(function () {
                object.waitForImageLoaded(object)
            }, 100)
        } else {
            return;
        }

    }

    update() {

        this.waitForImageLoaded(this.currentBackgroundImage);
        let src = this.img.src;
        if (this.img.currentSrc != '') {
            src = this.img.currentSrc;
        }

        if (this.src !== src) {
            this.src = src;
            this.objBackground.css("background-image", 'url(' + this.src + ')');
        }

        this.parallax();
    }

    parallax() {
        // Only if parallax is activated by class 'parallax-image'
        if (this.objBackground.hasClass('parallax-image')) {
            let windowHeight = window.innerHeight || document.documentElement.clientHeight;
            let position, height;

            // Get all dimensions from the browser before applying styles for better performance
            let parent = this.objBackground.parent();
            // Use array for "getBoundingClientRect()" in jQuery
            let parentCoords = parent[0].getBoundingClientRect();
            if (!('height' in parentCoords) || !('top' in parentCoords) || !('bottom' in parentCoords)) {
                return;
            }

            // Skip Element out of canvas
            if (parentCoords.bottom < 0 || parentCoords.top > windowHeight) {
                return;
            }

            // Calculate position
            height = Math.round(Math.max(0, Math.min(parentCoords.height, windowHeight) * 1.1));
            this.objBackground.css('bottom', 'auto');
            this.objBackground.css('height', height + 'px');
            if (height < windowHeight || (windowHeight < parentCoords.height && height < parentCoords.height)) {
                position = parentCoords.top / (windowHeight - parentCoords.height) * -(height - parentCoords.height);
            } else {
                position = (windowHeight - parentCoords.top) / (windowHeight + parentCoords.height) * -(height - parentCoords.height);
            }

            // Set position
            this.objBackground.css('-webkit-transform', 'translate3d(0, ' + Math.round(position) + 'px, 0)');
            this.objBackground.css('transform', 'translate3d(0, ' + Math.round(position) + 'px, 0)');
        }
    }

}

$(window).load(function () {
    let backgroundElements = $('.responsive-background-image');
    backgroundElements.each(function () {
            let backgroundImage = new BackgroundImage(this);
            $(window).scroll(function () {
                backgroundImage.parallax();
            });
            $(window).resize(function () {
                backgroundImage.update();
            });

        }
    )
});