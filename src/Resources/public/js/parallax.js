$(document).ready(function () {

    (function () {

        if (!('requestAnimationFrame' in window)) return;

        var backgrounds = [];
        var aHAlign = ['left','right'];
        var aVAlign = ['top','bottom'];

        $('.has-responsive-background-image').each(function () {
            var el = $(this);
            var bg = $('<div>');

            var src = el.find('img').prop('currentSrc');
            var noMobile = el.find('figure').data('nomobile');
            var hAlign = checkAlign(el.find('figure').data('halign'),aHAlign);
            var vAlign = checkAlign(el.find('figure').data('valign'), aVAlign);

            bg.css({
                backgroundImage: 'url(' + src + ')',
                backgroundPositionX: hAlign,
                backgroundPositionY: vAlign
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
                var hAlign = checkAlign($(parent).find('figure').data('halign'),aHAlign);
                var vAlign = checkAlign($(parent).find('figure').data('valign'), aVAlign);
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
                            scale($(bg), hAlign, vAlign, h, scaleB, scaleH)
                        }
                        if (h * scaleB < rect.height) {
                            scaleB = rect.height / (h * scaleB);
                            scale($(bg), hAlign, vAlign, h, scaleB, scaleH)
                        }
                        if (h * scaleB > 2 * rect.height) {
                            scaleH = h * scaleB / (2 * rect.height);
                            scale($(bg), hAlign, vAlign, h, scaleB, scaleH)
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

        function checkAlign(align, array) {
            if (array.indexOf(align) == -1) {
                return 'center';
            }
            return align;
        }

        function scale(el, hAlign, vAlign, height, scaleB, scaleH) {
            var left;
            var top;
            var h;
            switch (hAlign) {
                case 'left':
                    left = 0;
                    break;
                case 'right':
                    left = -(scaleB - 1) * 100;
                    break;
                default:
                    left = -(scaleB - 1) / 2 * 100;
            }
            switch (vAlign) {
                case 'top':
                    h = scaleH * height * 2;
                    top = (h - height) / 2;
                    top = top + 'px';
                    break;
                case 'bottom':
                    h = scaleH * height * 2;
                    top = (h - height) / 2 + height;
                    top = top + 'px';
                    break;
                default:
                    top = '50%';
            }

            el.css({
                width: scaleB * 100 + '%',
                left: left + '%',
                backgroundPositionY: top
            });

            if (scaleH > 1) {
                el.css({
                    height: scaleH * 200 + '%'
                });
            }

        };
    })();
});