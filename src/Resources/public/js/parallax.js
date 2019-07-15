$(document).ready(function () {

    (function () {

        if (!('requestAnimationFrame' in window)) return;
        //if(/Mobile|Android/.test(navigator.userAgent)) return;

        var backgrounds = [];
        var hAlign;

        $('.has-responsive-background-image').each(function () {
            var el = $(this);
            var bg = $('<div>');

            var src = el.find('img').prop('currentSrc');
            hAlign = el.find('figure').data('align');

            bg.css({
                backgroundImage: 'url(' + src + ')',
                backgroundPositionX: hAlign
            });
            bg.addClass('bgImage');

            bg.appendTo(el);
            backgrounds.push(bg[0]);

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
                var w = img.prop('naturalWidth');
                var h = img.prop('naturalHeight');
                var src = img.prop('currentSrc');

                var scaleW = 1;
                var scaleH = 1;
                var scale = 1;
                $(bg).css("background-image", "url('" + src + "')");

                if ($(parent).hasClass('parallax')) {
                    var rect = parent.getBoundingClientRect();
                    if (rect.bottom > 0 && rect.top < window.innerHeight) {

                        scaleW = rect.width / w;
                        scaleH = rect.height / h;
                        scale = Math.min(scaleW, scaleH);

                        if (h * scale < rect.height) {
                            scale = rect.height / (h * scale);
                            var left;
                            switch (hAlign) {
                                case 'left':
                                    left = 0;
                                case 'right':
                                    left = -(scale - 1) * 100;
                                default:
                                    left = -(scale - 1) / 2 * 100;
                            }

                            $(bg).css({
                                width: scale * 100 + '%',
                                left: left + '%'
                            });
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
    })();
});