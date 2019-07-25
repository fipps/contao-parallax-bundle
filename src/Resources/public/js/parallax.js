$(document).ready(function () {

    (function () {

        if (!('requestAnimationFrame' in window)) return;

        var backgrounds = [];
        var aHAlign = ['left', 'right'];
        var aVAlign = ['top', 'bottom'];

        $('.has-responsive-background-image').each(function () {
            var el = $(this);
            var bg = $('<div>');

            var src = el.find('img').prop('currentSrc');
            var noMobile = el.find('figure').data('nomobile');
            var hAlign = checkAlign(el.find('figure').data('halign'), aHAlign);
            var vAlign = checkAlign(el.find('figure').data('valign'), aVAlign);


            bg.css({
                backgroundImage: 'url(' + src + ')',
                backgroundPositionX: hAlign,
                backgroundPositionY: vAlign
            });
            bg.addClass('bgImage');

            bg.appendTo(el);
            backgrounds.push(bg[0]);

            if (/Mobile|Android/.test(navigator.userAgent) && noMobile == 1) {
                el.removeClass('parallax');
                return;
            }


        });

        if (!backgrounds.length) return;

        var visible = [];
        var scheduled;

        $(window).on('scroll resize', scroll);

        scroll();
        //Workaround to calculate correct position
        var pos = $(document).scrollTop();
        $(document).scrollTop(pos + 10);

        function scroll() {

            visible.length = 0;

            for (var i = 0; i < backgrounds.length; i++) {
                var parent = backgrounds[i].parentNode;
                var src = $(parent).find('img').prop('currentSrc');

                $(backgrounds[i]).css("background-image", "url('" + src + "')");



                if ($(parent).hasClass('parallax')) {
                    var rect = parent.getBoundingClientRect();
                    var img = $(parent).find('img');
                    var vAlign = checkAlign($(parent).find('figure').data('valign'), aVAlign);
                    var h = img.prop('naturalHeight');

                    if (rect.bottom > 0 && rect.top < window.innerHeight) {
                        var positionY;
                        switch (vAlign) {
                            case 'top':
                                positionY = 1.4*rect.height - h;
                                $(backgrounds[i]).css({
                                    backgroundPositionY: positionY + 'px'
                                });
                                break;
                            case 'bottom':
                                positionY = 1.4*rect.height - h;
                                $(backgrounds[i]).css({
                                    backgroundPositionY: -positionY + 'px'
                                });
                                break;
                        }

                        visible.push({
                            rect: rect,
                            node: backgrounds[i]
                        });
                    }
                }

            }

            cancelAnimationFrame(scheduled);

            if (visible.length) {
                scheduled = requestAnimationFrame(update);
            }

        }

        function checkAlign(align, array) {
            if (array.indexOf(align) == -1) {
                return 'center';
            }
            return align;
        }

        function update() {

            for (var i = 0; i < visible.length; i++) {
                var rect = visible[i].rect;
                var node = visible[i].node;

                var quot = Math.max(rect.bottom, 0) / (window.innerHeight + rect.height);

                node.style.transform = 'translate3d(0, ' + (-50 * quot) + '%, 0)';
            }

        }

    })();
});