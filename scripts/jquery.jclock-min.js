(function($) {
    $.fn.jclock = function(options) {
        return this.each(function() {
            var $this = $(this);
            setInterval(function() {
                var d = new Date();
                $this.text(d.toLocaleTimeString());
            }, 1000);
        });
    };
})(jQuery);
