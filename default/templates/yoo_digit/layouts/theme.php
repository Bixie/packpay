<?php
/**
* @package   yoo_digit
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>"  data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>

<head>
<?php echo $this['template']->render('head'); ?>
</head>

<body class="<?php echo $this['config']->get('body_classes'); ?>">


	<?php if ($this['widgets']->count('toolbar-l + toolbar-r')) : ?>

		<div class="tm-toolbar uk-clearfix">

			<div class="uk-container uk-container-center">

				<?php if ($this['widgets']->count('toolbar-l')) : ?>
				<div class="uk-float-left"><?php echo $this['widgets']->render('toolbar-l'); ?></div>
				<?php endif; ?>

				<?php if ($this['widgets']->count('toolbar-r')) : ?>
				<div class="uk-float-right"><?php echo $this['widgets']->render('toolbar-r'); ?></div>
				<?php endif; ?>

			</div>

		</div>

	<?php endif; ?>

	<?php if ($this['widgets']->count('logo + headerbar + menu + search')) : ?>

		<div class="tm-headerbar<?php if (!$this['widgets']->count('top-teaser')) echo ' tm-headerbar-plain' ?> uk-clearfix">

			<div class="uk-container uk-container-center">

				<?php if ($this['widgets']->count('logo')) : ?>
				<a class="uk-navbar-brand uk-hidden-small" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo'); ?></a>
				<?php endif; ?>

				<?php echo $this['widgets']->render('headerbar'); ?>

				<?php if ($this['widgets']->count('search')) : ?>

				<div class="uk-navbar-flip">
					<div class="uk-navbar-content uk-visible-large"><?php echo $this['widgets']->render('search'); ?></div>
				</div>
				<?php endif; ?>

				<?php if ($this['widgets']->count('menu')) : ?>
				<div class="uk-navbar-nav uk-navbar-flip uk-hidden-small">
					<?php echo $this['widgets']->render('menu'); ?>
				</div>
				<?php endif; ?>

				<?php if ($this['widgets']->count('offcanvas')) : ?>
				<a href="#offcanvas" class="uk-navbar-toggle uk-navbar-flip uk-visible-small" data-uk-offcanvas></a>
				<?php endif; ?>

				<?php if ($this['widgets']->count('logo-small')) : ?>
				<a class="tm-logo-small uk-navbar-brand uk-visible-small" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo-small'); ?></a>

				<?php endif; ?>

			</div>

		</div>

	<?php endif; ?>

	<?php if ($this['widgets']->count('top-teaser')) : ?>
	<div class="tm-teaser">
		<div class="uk-container uk-container-center uk-height-1-1">
			<?php echo $this['widgets']->render('top-teaser'); ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="tm-wrapper">

	<?php if ($this['widgets']->count('top-a')) : ?>
	<div class="tm-block <?php echo $block_classes['top-a']; echo $display_classes['top-a']; ?>">
		<div class="<?php echo $width_classes['top-a']; ?> uk-container uk-container-center">
			<section class="<?php echo $grid_classes['top-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?></section>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($this['widgets']->count('top-b')) : ?>
	<div class="tm-block <?php echo $block_classes['top-b']; echo $display_classes['top-b']; ?> ">
		<div class="<?php echo $width_classes['top-b']; ?> uk-container uk-container-center">
			<section class="<?php echo $grid_classes['top-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?></section>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
	<div class="tm-block <?php echo $block_classes['main']; ?> ">
		<div class="<?php echo $width_classes['main']; ?> uk-container uk-container-center">

			<?php if ($this['config']->get('system_output', true)) : ?>
				<?php if ($this['widgets']->count('breadcrumbs')) : ?>
					<div class="tm-breadcrumb">
					<?php echo $this['widgets']->render('breadcrumbs'); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<div class="tm-middle uk-grid" data-uk-grid-match data-uk-grid-margin>

				<?php if ($this['widgets']->count('main-top + main-bottom') || $this['config']->get('system_output', true)) : ?>
				<div class="<?php echo $columns['main']['class'] ?>">

					<?php if ($this['widgets']->count('main-top')) : ?>
					<section class="<?php echo $grid_classes['main-top']; echo $display_classes['main-top']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?></section>
					<?php endif; ?>

					<?php if ($this['config']->get('system_output', true)) : ?>
					<main class="tm-content">

						<?php echo $this['template']->render('content'); ?>

					</main>
					<?php endif; ?>

					<?php if ($this['widgets']->count('main-bottom')) : ?>
					<section class="<?php echo $grid_classes['main-bottom']; echo $display_classes['main-bottom']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?></section>
					<?php endif; ?>

				</div>
				<?php endif; ?>

	            <?php foreach($columns as $name => &$column) : ?>
	            <?php if ($name != 'main' && $this['widgets']->count($name)) : ?>
	            <aside class="<?php echo $column['class'] ?>"><?php echo $this['widgets']->render($name) ?></aside>
	            <?php endif ?>
	            <?php endforeach ?>

			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($this['widgets']->count('bottom-a')) : ?>
	<div class="tm-block <?php echo $block_classes['bottom-a']; echo $display_classes['bottom-a']; ?> ">
		<div class="<?php echo $width_classes['bottom-a']; ?> uk-container uk-container-center">
			<section class="<?php echo $grid_classes['bottom-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?></section>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($this['widgets']->count('bottom-b')) : ?>
	<div class="tm-block <?php echo $block_classes['bottom-b']; echo $display_classes['bottom-b']; ?> ">
		<div class="<?php echo $width_classes['bottom-b']; ?> uk-container uk-container-center">
			<section class="<?php echo $grid_classes['bottom-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
		</div>
	</div>
	<?php endif; ?>
	<?php if ($this['config']->get('totop_scroller', true)) : ?>
		<a class="tm-totop-scroller <?php if ($this['widgets']->count('bottom-c')) echo $this['config']->get('block.bottom-c.block-bg'); else echo $config->get('block.footer.block-bg'); ?>" data-uk-smooth-scroll href="#"></a>
	<?php endif; ?>

	<?php if ($this['widgets']->count('bottom-c')) : ?>
	<div class="tm-block <?php echo $block_classes['bottom-c']; echo $display_classes['bottom-c']; ?> ">
		<div class="uk-container uk-container-center <?php echo $width_classes['bottom-c']; ?>">
			<section class="<?php echo $grid_classes['bottom-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-c', array('layout'=>$this['config']->get('grid.bottom-c.layout'))); ?></section>
		</div>
	</div>
	<?php endif; ?>

	</div>

	<?php if ($this['widgets']->count('footer + debug') || $this['config']->get('warp_branding', true) || $this['config']->get('totop_scroller', true)) : ?>
	<div class="<?php echo $block_classes['footer']; ?>">
		<div class="<?php echo $width_classes['footer']; ?> uk-container uk-container-center">
			<footer class="tm-footer uk-text-center uk-text-small">

				<?php
					echo $this['widgets']->render('footer');
					$this->output('warp_branding');
					echo $this['widgets']->render('debug');
				?>

			</footer>
		</div>
	</div>
	<?php endif; ?>

	<?php echo $this->render('footer'); ?>

	<?php if ($this['widgets']->count('offcanvas')) : ?>
	<div id="offcanvas" class="uk-offcanvas">
		<div class="uk-offcanvas-bar uk-offcanvas-bar-flip"><?php echo $this['widgets']->render('offcanvas'); ?></div>
	</div>
	<?php endif; ?>

</body>
</html>