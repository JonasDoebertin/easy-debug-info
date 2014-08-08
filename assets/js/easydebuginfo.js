/*------------------------------------*\
	$AJAX ACTIONS
\*------------------------------------*/
jQuery(function($)
{

	var generateReportAnchor = $('.js-easydebuginfo-generate-report'),
        reportHolder         = $('.js-easydebuginfo-report-holder'),
        reportAlert          = $('.js-easydebuginfo-old-report-alert');

	/**
	 * Generating a report
	 */
	generateReportAnchor.on('click', function(e) {

    	e.preventDefault();

		/* Show loading indicator */
		var icon = $(this).prev('i').addClass('easydebuginfo-icon-spinner');

		/* Build data */
		data = {
			action: EasyDebugInfo.action.generate,
			nonce:  EasyDebugInfo.nonce.generate
		};

		/* Send post request */
		$.post(ajaxurl, data, function(response) {

            /* Hide loading indicator */
			icon.removeClass('easydebuginfo-icon-spinner');

            /* Hide alert box */
            reportAlert.hide();

			/* Show message */
            reportHolder.hide().text(response).slideDown('slow');

			//alert(response);
		});

	});

});
