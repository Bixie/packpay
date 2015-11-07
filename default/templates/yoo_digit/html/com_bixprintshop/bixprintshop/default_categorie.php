<?php
// no direct access
defined('_JEXEC') or die;
?>

<legend><?php echo $this->categorie; ?></legend>

<?php if ($this->params->get('show_categorieDescr') || $this->params->get('show_categorieImage')): ?>
	<div class="uk-clearfix uk-margin-bottom">
		<?php if ($this->params->get('show_categorieImage')):
			$catimgSrc = '/' . $this->catItem->getParams()->get('image', BIX_MEDIA . 'system/noimage-64.png');
			?>
			<div class="uk-<?php echo $this->params->get('categorieImageAlign'); ?>">
				<img src="<?php echo $catimgSrc; ?>" alt="<?php echo $this->categorie; ?>"/>
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_categorieDescr')): ?>
			<p class="uk-margin-remove">
				<?php echo strip_tags($this->catItem->get('description'), '<h3><h4><h5><h6><p><br><strong><a><em><u><ul><li>'); ?>
			</p>
		<?php endif; ?>
	</div>
<?php endif; ?>
