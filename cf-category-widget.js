(function($) {
	$(function() {
		$('body').on('click', '.widget-top, .savewidget', function(e) {
			$(this).parent('div').find('.cfcw-select').chosen({ width: '100%' });
			$('div[id*="cf_category_selection"]').css('overflow', 'visible');
		});
	});
})(jQuery);