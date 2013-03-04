<?php
if (osc_is_admin_user_logged_in() == FALSE) {
	die;
}

GzNewsForm::initForm();

$back_url = GzNewsUtils::getAdminIndexUrl();

$item = FALSE;
$item_id = Params::getParam('gn_id');

if ($item_id) {
	$dao = GzNewsDao::newInstance();
	$item = $dao->findByPrimaryKey($item_id);
}

$title = NULL;
$description = NULL;
$tags = NULL;
$lang = NULL;

if ($item) {
	$title = $item['gn_title'];
	$description = $item['gn_description'];
	$tags = $item['gn_tags'];
	$lang = $item['gn_lang'];
}

$images = GzNewsUtils::getImagesUrl($item_id);
?>
<h2 class="render-title">
	<?php _e('Add New', 'gz_news'); ?>
    <a class="btn btn-mini" href="<?php echo $back_url; ?>"><?php _e('Back to listing', 'gz_news') ?></a>
</h2>

<div class="newsFormWrapper"> 
    <form name="news_item_form" class="news_form" action="<?php echo GzNewsUtils::getActionAddNewUrl($item_id) ?>" method="POST" enctype="multipart/form-data">
		<fieldset>
			<div class="form-horizontal">
				<div class="form-row">
					<div class="form-label"><?php _e('Title', 'gz_news'); ?> *</div>
					<div class="form-controls"><?php GzNewsForm::title_input($title); ?></div>
				</div>
				<div class="form-row">
					<div class="form-label"><?php _e('Description', 'gz_news'); ?> *</div>
					<div class="form-controls input-description-wide"><?php GzNewsForm::description_input($description); ?></div>
				</div>
				<div class="form-row">
					<div class="form-label"><?php _e('Tags', 'gz_news'); ?></div>
					<div class="form-controls"><?php GzNewsForm::tags_input($tags); ?></div>
				</div>  
				<div class="form-row">
					<div class="form-label"><?php _e('Language', 'gz_news'); ?></div>
					<div class="form-controls"><?php GzNewsForm::language_selector($lang); ?></div>
				</div> 
				<div class="form-row">
					<div class="form-label"><?php _e('Image', 'gz_news'); ?></div>
					<div class="form-controls">
						<input type="file" name="image" value="" />
					</div>
					<?php if (!empty($images)) { ?>
						<div class="form-controls">
							<img class="preview_new_thumb" src="<?php echo $images['thumb']; ?>">
							<div class="imageActionLinks">
								<a href="<?php echo GzNewsUtils::getActionDeleteImageUrl($item_id) ?>" class="btn btn-mini btn-orange" onclick="return confirm('<?php _e('Do you really want to clear the image?', 'gz_news') ?>');"><?php _e('Clean', 'gz_news') ?></a>
							</div>
						</div>

					<?php } ?>
				</div> 
				<div class="form-actions">
					<?php if ($item_id) { ?>
						<a href="<?php echo GzNewsUtils::getActionDeleteUrl($item_id) ?>&from_form=1" class="btn btn-red newsDeleteBtn" onclick="return confirm('<?php _e('Do you really want to delete this new?', 'gz_news') ?>');"><?php _e('Delete', 'gz_news') ?></a>
					<?php } ?>
					<input type="submit" class="btn btn-submit newsSubmitBtn" value="<?php _e('Publish', 'gz_news') ?>">
				</div> 
			</div>
		</fieldset>
		<div class="clear"></div>
	</form>

</div>

<?php GzNewsForm::destroyForm(); ?>