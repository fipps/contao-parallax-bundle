$(document).ready(function () {

    (function () {

        if (!('requestAnimationFrame' in window)) return;

        var backgrounds = [];

        $('.has-responsive-background-image').each(function () {
            var el = $(this);
            var bg = $('<div>');

            var src = el.find('img').prop('currentSrc');
            var noMobile = el.find('figure').data('nomobile');
            var hAlign = el.find('figure').data('align');

            bg.css({
                backgroundImage: 'url(' + src + ')',
                backgroundPositionX: hAlign
            });
            bg.addClass('bgImage');

            bg.appendTo(el);
            backgrounds.push(bg[0]);

            if (/Mobile|Android/.test(navigator.userAgent)  && noMobile == 1) {
                el.removeClass('parallax');
                return;
            }

        });

        if (!backgrounds.length) return;

        var visible = [];
        var scheduled;

        scroll();

        $(window).on('scroll resize', scroll);

        //Workaround to calculate correct position
        var pos = $(document).scrollTop();
        $(document).scrollTop(pos + 10);

        function scroll() {

            visible.length = 0;

            for (var i = 0; i < backgrounds.length; i++) {
                var bg = backgrounds[i];
                var parent = bg.parentNode;
                var img = $(parent).find('img');
                var hAlign = $(parent).find('figure').data('align');
                var w = img.prop('naturalWidth');
                var h = img.prop('naturalHeight');
                var src = img.prop('currentSrc');

                var scaleW = 1;
                var scaleH = 1;
                var scaleB = 1;
                $(bg).css("background-image", "url('" + src + "')");

                if ($(parent).hasClass('parallax')) {
                    var rect = parent.getBoundingClientRect();
                    if (rect.bottom > 0 && rect.top < window.innerHeight) {

                        scaleW = rect.width / w;
                        scaleH = rect.height / h;
                        scaleB = Math.min(scaleW, scaleH);
                        if (scaleH > 1 || scaleW > 1) {
                            scaleB = Math.max(scaleW, scaleH);
                        }

                        if (scaleB > 1) {
                            scale($(bg), hAlign, scaleB)
                        }

                        if (h * scaleB < rect.height) {
                            scaleB = rect.height / (h * scaleB);
                            scale($(bg), hAlign, scaleB)
                        }
                        if (h * scaleB > 2 * rect.height) {
                            scaleH = h * scaleB / (2 * rect.height);
                            scale($(bg), hAlign, scaleB, scaleH)
                        }

                        visible.push({
                            rect: rect,
                            node: bg
                        });
                    }
                }
            }

            cancelAnimationFrame(scheduled);

            if (visible.length) {
                scheduled = requestAnimationFrame(update);
            }
        }

        function update() {
            pos = $(window).scrollTop();

            for (var i = 0; i < visible.length; i++) {
                var rect = visible[i].rect;
                var node = visible[i].node;

                var quot = Math.max(rect.bottom, 0) / (window.innerHeight + rect.height);

                node.style.transform = 'translate3d(0, ' + (-50 * quot) + '%, 0)';
            }
        }

        function scale(el, hAlign, scaleW, scaleH = 0) {
            var left;
            switch (hAlign) {
                case 'left':
                    left = 0;
                case 'right':
                    left = -(scaleW - 1) * 100;
                default:
                    left = -(scaleW - 1) / 2 * 100;
            }

            el.css({
                width: scaleW * 100 + '%',
                left: left + '%'
            });

            if (scaleH > 0) {
                el.css({
                    height: scaleH * 200 + '%'
                });
            }

        };
    })();
});