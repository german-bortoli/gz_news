<?php
define('ABS_PATH', dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))))) . '/');

require_once ABS_PATH . 'oc-load.php';


$dao = GzNewsDao::newInstance();

$index_url = GzNewsUtils::getIndexUrl();

$current_page = (int) Params::getParam('new_p');
$selected = 0;
if ($current_page > 0) {
	$selected = $current_page - 1;
}

$total_items = $dao->count();
$total_per_page = GzNewsUtils::getMaxItemsPerPage();

$total_pages = ceil($total_items / $total_per_page);

$params = array(
	'total' => (int) $total_pages,
	'selected' => $selected,
	'url' => $index_url . '?new_p={PAGE}',
	'sides' => 5
);
// set pagination
$pagination = new Pagination($params);
$paginator_html = $pagination->doPagination();

$options = array(
	'page' => $current_page,
	'total_per_page' => $total_per_page,
	'language' => osc_current_user_locale(),
);

$list = $dao->listItems($options);
Params::setParam('themeCustomtitle', __('Noticias', 'gz_news'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
    <head>
        <?php osc_current_web_theme_path('head.php'); ?>
        <meta name="robots" content="index, follow" />
        <meta name="googlebot" content="index, follow" />
    </head>
    <body>
        
        <?php
        /** Header that includes menu, logo, etc **/
        osc_current_web_theme_path('header.php');
        
		?>
		<div class="row">
			<div class="sixteen columns">
				<div class="page_heading">
					<h1><?php _e('Novedades', 'gz_news') ?></h1>
				</div>
			</div>
		</div>

		<div class="row">
			<!-- Wide Column -->
			<div class="sixteen columns">
				<?php if (is_array($list) && !empty($list)) { ?>

					<?php foreach ($list as $item) { ?>
						<!--Novedades-->
						<div class="post_item">

							<?php $images = GzNewsUtils::getImagesUrl($item['gn_id']); ?>
							<?php if ($images && isset($images['original'])) { ?>
								<div class="pic">
									<a href="<?php echo GzNewsUtils::getItemUrl($item) ?>"><img src="<?php echo $images['original'] ?>"/><div class="img_overlay"></div></a>
								</div>
							<?php } ?>
							<h3 class="post_title"><a href="<?php echo GzNewsUtils::getItemUrl($item) ?>"><?php echo $item['gn_title'] ?></a></h3>
							<p class="post_meta">

								<span class="data"><?php echo $item['gn_time_created'] ?></span>
								<span class="tags"><?php echo $item['gn_tags'] ?> </span>
							</p>
							<p class="post_description"><?php echo GzNewsUtils::getItemExcerpt($item); ?></p>					
						</div>
						<!--Novedades::END-->
					<?php } ?>
				

				<div class="pagination clearfix">		
					<?php
					echo $paginator_html;
					?>		
				</div>
						
				<?php } else {?>
						<div><h3><?php _e('No hay noticias disponibles', 'gn_new')?></h3></div>
				<?php }?>

			</div>
			<!-- Wide Column::END -->


			<!-- Side Column --><!-- Side Column::END -->

		</div>	
		<?php osc_current_web_theme_path('footer.php'); ?>

	</body>