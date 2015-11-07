<?php

// no direct access
defined('_JEXEC') or die;

$iCatCount = count($this->items);
$this->numCatCols = ($iCatCount && $this->params->get('numCatCols') > $iCatCount) ? $iCatCount : $this->params->get('numCatCols');
$jCategories = JCategories::getInstance('Bixprintshop');
$this->numProdCols = $this->params->get('numProdCols');

?>
<div class="catoverzicht uk-form <?php echo $this->params->get('pageclass_sfx', ''); ?>">
	<?php if ($this->params->get('show_page_heading')): ?>
		<h1 class="uk-h3"><?php echo $this->page_heading; ?></h1>
	<?php endif; ?>
	<?php if ($this->params->get('catOrdering')) : ?>
		<div class="uk-grid" data-uk-grid-margin="">
			<?php foreach ($this->items as $catid => $items):
				$this->catItem = $jCategories->get($catid);
				$this->categorie = $this->catItem->get('title'); ?>
				<div class="uk-panel uk-width-medium-1-<?php echo $this->numCatCols; ?>">
					<fieldset class="categorieHolder">
						<?php echo $this->loadTemplate('categorie'); ?>
						<div class="uk-grid" data-uk-grid="{gutter: 10}" data-uk-scrollspy="{cls:'uk-animation-slide-left uk-invisible', target:'> div > .uk-panel', delay:300}">
							<?php
							$itemCount = count($items);
							//						$this->numProdCols = ($itemCount && $this->params->get('numProdCols') > $itemCount) ? $itemCount : $this->params->get('numProdCols');
							foreach ($items as $item):
								$this->item = $item; ?>
								<?php echo $this->loadTemplate('product'); ?>
							<?php endforeach; ?>
						</div>
					</fieldset>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<div class="uk-grid" data-uk-grid="{gutter: 10}">
			<?php foreach ($this->items as $item):
				$this->item = $item; ?>
				<?php echo $this->loadTemplate('product'); ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
