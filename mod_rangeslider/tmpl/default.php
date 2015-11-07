<?php

// no direct access
defined('_JEXEC') or die;
/**
 * @var $params
 */
$start = intval($params->get('start', 1));
?>
<h3 class="uk-panel-title"><?php echo JText::_('MOD_RANGESLIDER_TITLE'); ?></h3>
<div class="uk-width-1-1 uk-contrast uk-text-center">
	<i class="uk-icon-users uk-margin-small-right"></i>
	<input type="range" id="rangeslider" class="uk-width-7-10"
		   value="<?php echo $start; ?>"
		   min="<?php echo $params->get('min', 0); ?>"
		   max="<?php echo $params->get('max', 1000); ?>"
		   step="<?php echo $params->get('step', 1); ?>">
	<span class="rangeslider-value uk-margin-small-left"></span>

	<h3 class="uk-h1 uk-margin">€ <span class="rangeslider-output"></span>,-</h3>
	<em class="uk-text-muted uk-text-small"><?php echo JText::_('MOD_RANGESLIDER_DISCLAIMER'); ?></em>
</div>
<script>
	jQuery(function ($) {

		var factor = <?php echo intval($params->get('factor', 1)); ?>,
			valueEl = $('.rangeslider-value'),
			outputEl = $('.rangeslider-output');

		$('#rangeslider').on('input', function () {
			valueEl.html($(this).val());
			outputEl.html(($(this).val() * factor));
		});
		valueEl.html(<?php echo $start; ?>);
		outputEl.html((<?php echo $start; ?> * factor));
	});
</script>
