<?php if ($this['config']->get('article')=='tm-article-blog') : ?>
<article class="uk-article" <?php if ($permalink) echo 'data-permalink="'.$permalink.'"'; ?>>

	<div class="uk-grid">
		<div class="uk-width-1-1 uk-width-medium-1-4">
		<?php if ($image && $image_alignment == 'none') : ?>
			<?php if ($url) : ?>
				<a href="<?php echo $url; ?>" class="tm-article-image" style="background-image:url(<?php echo $image; ?>);"></a>
			<?php else : ?>
				<div class="tm-article-image" style="background-image:url(<?php echo $image; ?>);"></div>
			<?php endif; ?>

			<?php else : ?>

			<a href="<?php echo $url; ?>">
				<div class="tm-article-image tm-blog-image-placeholder"></div>
			</a>
		<?php endif; ?>
		</div>

		<div class="uk-width-1-1 uk-width-medium-3-4">

		<?php if ($title) : ?>
		<h1 class="uk-article-title">
			<?php if ($url && $title_link) : ?>
				<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
			<?php else : ?>
				<span><?php echo $title; ?></span>
			<?php endif; ?>
		</h1>
		<?php endif; ?>


	<?php echo $hook_aftertitle; ?>

	<?php if ($image && $image_alignment != 'none') : ?>
		<?php if ($url) : ?>
			<a class="uk-align-<?php echo $image_alignment; ?>" href="<?php echo $url; ?>" title="<?php echo $image_caption; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></a>
		<?php else : ?>
			<img class="uk-align-<?php echo $image_alignment; ?>" src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>">
		<?php endif; ?>
	<?php endif; ?>

	<?php echo $hook_beforearticle; ?>

	<?php if ($article) : ?>
	<div class="tm-article">
		<?php echo $article; ?>
	</div>
	<?php endif; ?>

	<?php if ($author || $category) : ?>
	<p class="uk-article-meta uk-width-1-1 uk-width-medium-2-3 uk-float-left uk-margin-top-remove">

		<?php

			$author   = ($author && $author_url) ? '<a href="'.$author_url.'">'.$author.'</a>' : $author;
			$date     = ($date) ? ($datetime ? '<time datetime="'.$datetime.'" pubdate>'.JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3')).'</time>' : JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3'))) : '';
			$category = ($category && $category_url) ? '<a href="'.$category_url.'">'.$category.'</a>' : $category;

			if($author && $date) {
				printf(JText::_('TPL_WARP_META_AUTHOR_DATE'), $author, $date);
			} elseif ($author) {
				printf(JText::_('TPL_WARP_META_AUTHOR'), $author);
			} elseif ($date) {
				printf(JText::_('TPL_WARP_META_DATE'), $date);
			}

			if ($category) {
				echo ' ';
				printf(JText::_('TPL_WARP_META_CATEGORY'), $category);
			}

		?>

	</p>
	<?php endif; ?>

	<?php if ($more) : ?>
		<a class="uk-margin-top uk-width-1-1 uk-width-medium-1-3" href="<?php echo $url; ?>" title="<?php echo $title; ?>"><button class="uk-button uk-button-small uk-float-right"><?php echo $more; ?></button></a>
	<?php endif; ?>

	<?php if ($tags) : ?>
	<p class="uk-float-left"><?php echo JText::_('TPL_WARP_TAGS').': '.$tags; ?></p>
	<?php endif; ?>

	<?php if ($edit) : ?>
	<p><?php echo $edit; ?></p>
	<?php endif; ?>

	<?php if ($previous || $next) : ?>
    <ul class="uk-pagination">
        <?php if ($previous) : ?>
        <li class="uk-pagination-previous">
            <a href="<?php echo $previous; ?>" title="<?php echo JText::_('JPREV') ?>">
                <i class="uk-icon-arrow-left"></i>
                <?php echo JText::_('JPREV') ?>
            </a>
        </li>
        <?php endif; ?>

        <?php if ($next) : ?>
        <li class="uk-pagination-next">
            <a href="<?php echo $next; ?>" title="<?php echo JText::_('JNEXT') ?>">
                <?php echo JText::_('JNEXT') ?>
                <i class="uk-icon-arrow-right"></i>
            </a>
        </li>
        <?php endif; ?>
    </ul>
    <?php endif; ?>

	<?php echo $hook_afterarticle; ?>

	</div>

</article>


<?php else : ?>




<article class="uk-article" <?php if ($permalink) echo 'data-permalink="'.$permalink.'"'; ?>>

	<?php if ($image && $image_alignment == 'none') : ?>
		<?php if ($url) : ?>
			<a href="<?php echo $url; ?>" title="<?php echo $image_caption; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></a>
		<?php else : ?>
			<img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>">
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($title) : ?>
	<h1 class="uk-article-title">
		<?php if ($url && $title_link) : ?>
			<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
		<?php else : ?>
			<?php echo $title; ?>
		<?php endif; ?>
	</h1>
	<?php endif; ?>

	<?php echo $hook_aftertitle; ?>

	<?php if ($author || $date || $category) : ?>
	<p class="uk-article-meta">

		<?php

			$author   = ($author && $author_url) ? '<a href="'.$author_url.'">'.$author.'</a>' : $author;
			$date     = ($date) ? ($datetime ? '<time datetime="'.$datetime.'" pubdate>'.JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3')).'</time>' : JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3'))) : '';
			$category = ($category && $category_url) ? '<a href="'.$category_url.'">'.$category.'</a>' : $category;

			if($author && $date) {
				printf(JText::_('TPL_WARP_META_AUTHOR_DATE'), $author, $date);
			} elseif ($author) {
				printf(JText::_('TPL_WARP_META_AUTHOR'), $author);
			} elseif ($date) {
				printf(JText::_('TPL_WARP_META_DATE'), $date);
			}

			if ($category) {
				echo ' ';
				printf(JText::_('TPL_WARP_META_CATEGORY'), $category);
			}

		?>

	</p>
	<?php endif; ?>

	<?php if ($image && $image_alignment != 'none') : ?>
		<?php if ($url) : ?>
			<a class="uk-align-<?php echo $image_alignment; ?>" href="<?php echo $url; ?>" title="<?php echo $image_caption; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></a>
		<?php else : ?>
			<img class="uk-align-<?php echo $image_alignment; ?>" src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>">
		<?php endif; ?>
	<?php endif; ?>

	<?php echo $hook_beforearticle; ?>

	<?php if ($article) : ?>
	<div>
		<?php echo $article; ?>
	</div>
	<?php endif; ?>

	<?php if ($tags) : ?>
	<p><?php echo JText::_('TPL_WARP_TAGS').': '.$tags; ?></p>
	<?php endif; ?>

	<?php if ($more) : ?>
	<p>
		<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $more; ?></a>
	</p>
	<?php endif; ?>

	<?php if ($edit) : ?>
	<p><?php echo $edit; ?></p>
	<?php endif; ?>

	<?php if ($previous || $next) : ?>
    <ul class="uk-pagination">
        <?php if ($previous) : ?>
        <li class="uk-pagination-previous">
            <a href="<?php echo $previous; ?>" title="<?php echo JText::_('JPREV') ?>">
                <i class="uk-icon-arrow-left"></i>
                <?php echo JText::_('JPREV') ?>
            </a>
        </li>
        <?php endif; ?>

        <?php if ($next) : ?>
        <li class="uk-pagination-next">
            <a href="<?php echo $next; ?>" title="<?php echo JText::_('JNEXT') ?>">
                <?php echo JText::_('JNEXT') ?>
                <i class="uk-icon-arrow-right"></i>
            </a>
        </li>
        <?php endif; ?>
    </ul>
    <?php endif; ?>

	<?php echo $hook_afterarticle; ?>

</article>



<?php endif; ?>