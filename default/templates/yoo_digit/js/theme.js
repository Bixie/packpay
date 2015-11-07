/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

jQuery(function($) {

	var config = $('html').data('config') || {};

	// Social buttons
	$('article[data-permalink]').socialButtons(config);

    // Fixed headerbar

    var doc     = $(document),
        header  = $('.tm-headerbar');

    if ($('body').hasClass('tm-navbar-fixed')) {

        doc.on('scroll', function(){

            if(doc.scrollTop() > 100){
                header.addClass('tm-headerbar-small');
            } else{
                header.removeClass('tm-headerbar-small');
            }

        });

    }

});