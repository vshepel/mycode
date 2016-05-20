	$(function() {
		$.extend($.fn.bootstrapSwitch.defaults, {
			onText: '<span class="glyphicon glyphicon-ok"></span>',
			offText: '<span class="glyphicon glyphicon-remove"></span>',

			onColor: 'success',
			offColor: 'danger'
		});

		$(':checkbox').bootstrapSwitch();
	})