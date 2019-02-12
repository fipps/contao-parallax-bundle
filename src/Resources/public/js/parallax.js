$(document).ready(function(){

    (function(){

        if(!('requestAnimationFrame' in window)) return;
        if(/Mobile|Android/.test(navigator.userAgent)) return;

        var backgrounds = [];

        $('.has-responsive-background-image').each(function(){
            var el = $(this);
            var bg = $('<div>');

            var src = el.find('img').prop('currentSrc');
            var hAlign = el.find('figure').data('align');

            bg.css({
                backgroundImage: 'url(' + src + ')',
                backgroundPositionX: hAlign
            });
            bg.addClass('bgImage');

            bg.appendTo(el);
            backgrounds.push(bg[0]);

        });

        if(!backgrounds.length) return;

        var visible = [];
        var scheduled;

        $(window).on('scroll resize', scroll);

        scroll();

        function scroll(){

            visible.length = 0;

            for(var i = 0; i < backgrounds.length; i++){
                var parent = backgrounds[i].parentNode;
                var src = $(parent).find('img').prop('currentSrc');
                $(backgrounds[i]).css("background-image", "url('" + src + "')");

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

            if(visible.length){
                scheduled = requestAnimationFrame(update);
            }

        }

        function update(){

            for(var i = 0; i < visible.length; i++){
                var rect = visible[i].rect;
                var node = visible[i].node;

                var quot = Math.max(rect.bottom, 0) / (window.innerHeight + rect.height);

                node.style.transform = 'translate3d(0, '+(-50*quot)+'%, 0)';
            }

        }

    })();
});