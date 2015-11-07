<?php

// no direct access
defined('_JEXEC') or die;

$boxClass = $this->params->get('prodboxclass', '');

if ($this->params->get('show_productImage')) {
	$iconSize = $this->params->get('productType') == 'Link' ? 32 : $this->params->get('productImageSize', 64);
	$imageIcons = BixHelper::getImageIcons($this->item->productImage);

}
// $this->params->get('show_productLink') || 
?>

<?php if ($this->params->get('productType') == 'Button'): ?>
	<div class="uk-width-medium-1-<?php echo $this->numProdCols; ?>">

		<div class="uk-panel uk-invisible <?php echo $boxClass ?>">
			<div class="uk-panel-teaser">
				<figure class="uk-overlay uk-overlay-hover">
					<?php if ($this->params->get('show_productImage')): ?>
						<img src="<?php echo $imageIcons['image' . $iconSize]; ?>"
							 alt="<?php echo $this->item->productNaam; ?>"/>
					<?php endif; ?>
					<div class="uk-overlay-panel uk-overlay-background uk-overlay-fade uk-text-center uk-flex uk-flex-center uk-flex-middle">
						<div>
							<h4><?php echo $this->item->productNaam; ?></h4>

							<p>
								<?php if ($this->item->params->get('subtitle', '')): ?>
									<em><?php echo nl2br($this->item->params->get('subtitle', '')); ?></em>
								<?php endif; ?>
							</p>
						</div>
					</div>
					<a class="uk-position-cover" href="<?php echo $this->item->prodLink; ?>"></a>
				</figure>
			</div>
		</div>
	</div>

<?php endif; ?>
<?php if ($this->params->get('productType') == 'Link'): ?>
	<div class="uk-width-medium-1-<?php echo $this->numProdCols; ?>">
		<div class="uk-panel uk-invisible">
			<a class="product<?php echo $this->params->get('productType'); ?>" href="<?php echo $this->item->prodLink; ?>">
				<?php if ($this->params->get('show_productImage')): ?>
					<img src="<?php echo $imageIcons['image' . $iconSize]; ?>"
						 alt="<?php echo $this->item->productNaam; ?>"/>
				<?php endif; ?>
				<span
					title="<?php echo $this->item->params->get('subtitle', ''); ?>"><?php echo $this->item->productNaam; ?></span>
			</a>
		</div>
	</div>
<?php endif; ?>
