$(document).ready(function () {

    (function () {
        if (!('requestAnimationFrame' in window)) return;

        var backgrounds = [];
        var aVAlign = ['top', 'bottom'];

        $('.has-responsive-background-image').each(function () {
            var el = $(this);
            var bg = $('<div>');

            var src = el.find('img').prop('currentSrc');
            var noMobile = el.find('figure').data('nomobile');
            var hAlign = checkData(el, 'halign', 50);
            var vAlign = checkData(el, 'valign', 50);
            var scale = checkData(el, 'scale', 160);

            bg.css({
                backgroundImage: 'url(' + src + ')',
                backgroundPositionX: hAlign + '%',
                backgroundPositionY: vAlign + '%'
            });
            bg.addClass('bgImage');

            bg.appendTo(el);
            backgrounds.push(bg[0]);

            if (/Mobile|Android/.test(navigator.userAgent) && noMobile == 1) {
                el.removeClass('parallax');
                return;
            }

            if (el.hasClass('parallax')) {
                bg.css({
                    height: scale + '%'
                });
                setPosY(this, bg)
            }


        });

        if (!backgrounds.length) return;

        var visible = [];
        var scheduled;

        $(window).on('scroll', scroll);
        $(window).on('resize', resize);

        var pos = $(document).scrollTop();
        $(document).scrollTop(pos + 1);

        function resize() {
            $('.has-responsive-background-image').each(function () {
                var el = $(this);
                var src = el.find('img').prop('currentSrc');
                var bg = el.find('.bgImage');

                if (src != '') {
                    bg.css({
                        backgroundImage: 'url(' + src + ')'
                    });
                    if (el.hasClass('parallax')) {
                        setPosY(this, bg);
                        scroll();
                    }
                }
            });
        }

        function scroll() {
            visible.length = 0;
            for (var i = 0; i < backgrounds.length; i++) {
                var parent = backgrounds[i].parentNode;
                if ($(parent).hasClass('parallax')) {
                    var rect = parent.getBoundingClientRect();

                    if (rect.bottom > 0 && rect.top < window.innerHeight) {
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

        function checkData(el, data, def) {
            var val = el.find('figure').data(data);
            if (isNaN(val) || val=='') {
                val = def;
            }

            return val;
        }

        function setPosY(el, bg) {
            var rect = el.getBoundingClientRect();
            var vAlign = checkData($(el),'valign', 50);
            var faktor = (100 - vAlign) / 100;
            var quot = Math.max(rect.bottom, window.innerHeight) / (window.innerHeight + rect.height);
            var bgh = bg.height();
            var posY = quot / 2 * bgh * faktor;
            bg.css({
                backgroundPositionY: posY + 'px'
            })
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
