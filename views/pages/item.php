<?php
define('ABS_PATH', dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))))) . '/');

require_once ABS_PATH . 'oc-load.php';

$dao = GzNewsDao::newInstance();

$item_id = (int) Params::getParam('i');

$item = $dao->findByPrimaryKey($item_id);

$images = GzNewsUtils::getImagesUrl($item_id);

$model = new GzNewsModel();

if ($item) {
	Params::setParam('themeCustomtitle', $item['gn_title']);
} else {
	$model->redirectTo(GzNewsUtils::getIndexUrl());
}
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
		/** Header that includes menu, logo, etc * */
		osc_current_web_theme_path('header.php');
		?>

		<div class="row">
			<div class="sixteen columns">
				<div class="page_heading">
					<h1><?php echo $item['gn_title']; ?></h1></div>
			</div>
		</div>


		<div class="row">
			<!-- Wide Column -->
			<div class="sixteen columns">

				<?php if (!empty($images)) { ?>
					<div class="pic">
						<a href="<?php echo $images['original']; ?>" rel="prettyPhoto" title=""><img src="<?php echo $images['original']; ?>"/><div class="img_overlay_zoom"></div></a>
					</div>
				<?php } ?>

				<script type="text/javascript" charset="utf-8">
					$(document).ready(function() {
						$(".pic a[rel^='prettyPhoto']").prettyPhoto({
							animation_speed: 'normal',
							overlay_gallery: false,
							social_tools: false
						});
					});
				</script>					

				<div class="h10"></div>
				<div class="h10 divider_bgr"></div>
				<div class="h10"></div>

				<p class="post_meta">
					<span class="data"><?php echo $item['gn_time_created']; ?></span>
					<?php if ($item['gn_tags']) { ?>
						<span class="tags"><?php echo $item['gn_tags']; ?></span>
					<?php } ?>
				</p>
				<div class="post_description">
					<?php echo $item['gn_description']; ?>
				</div>	
			</div>
		</div>

		<!--Comentarios-->
		<div class="sixteen columns">
			<div class="comments_section">
				<?php osc_current_web_theme_path('disqus-comments.php'); ?>
			</div>
		</div>
		<!--Comentarios::END-->
		<?php
		/** Latest news * */
		osc_current_web_theme_path('widgets/latest-news.php');
		?>

		<div class="clear"></div>

		<?php osc_current_web_theme_path('footer.php'); ?>
	</body>