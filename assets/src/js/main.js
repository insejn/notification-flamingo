(function($) {

	$('.notification-flamingo-acceptance-form').on('submit', function(e) {
		e.preventDefault();
		$(this).find('.accept').attr('disabled');
	});

})(jQuery);
