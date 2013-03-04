<?php
if (osc_is_admin_user_logged_in() == FALSE) {
	die;
}

$add_url = GzNewsUtils::getAdminAddUrl();
$index_url = GzNewsUtils::getAdminIndexUrl();

$dao = GzNewsDao::newInstance();

$current_page = (int) Params::getParam('new_p');
$selected = 0;
if ($current_page > 0) {
	$selected = $current_page -1;
}

$total_items = $dao->count();
$total_per_page = 10;

$total_pages = ceil($total_items / $total_per_page);

$params = array(
	'total' => (int) $total_pages,
	'selected' => $selected,
	'url' => $index_url.'&new_p={PAGE}',
	'sides' => 5
);
// set pagination
$pagination = new Pagination($params);
$paginator_html = $pagination->doPagination();

$options = array(
	'page' => $current_page,
	'total_per_page' => $total_per_page,
);

$list = $dao->listItems($options);

?>

<h2 class="render-title">
	<?php _e('Listing News', 'gz_news') ?> <a class="btn btn-mini" href="<?php echo $add_url; ?>"><?php _e('Add', 'gz_news') ?></a>
</h2>


<?php if (is_array($list) && !empty($list)) {  ?>
<div class="">
	<table class="table" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th class="col-title"><?php _e('Title', 'gz_news') ?></th>
				<th class="col-lang"><?php _e('Language', 'gz_news') ?></th>
				<th class="col-tags"><?php _e('Tags', 'gz_news') ?></th>
				<th class="col-date"><?php _e('Date', 'gz_news') ?></th>
				<th class="col-actions"><?php _e('Actions', 'gz_news') ?></th>

			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $item) { ?>

				<tr class="">
					<td><?php echo $item['gn_title'] ?></td>
					<td><?php echo $item['gn_lang'] ?></td>
					<td><?php echo $item['gn_tags'] ?></td>
					<td><?php echo $item['gn_time_created'] ?></td>
					<td>
						<ul>
							<li>
								<a href="<?php echo GzNewsUtils::getItemUrl($item) ?>" target="_blank"><?php _e('View', 'gz_news') ?></a>
							</li>
							<li>
								<a href="<?php echo GzNewsUtils::getAdminEditUrl($item['gn_id']) ?>"><?php _e('Edit', 'gz_news') ?></a>
							</li>
							<li>
								<a href="<?php echo GzNewsUtils::getActionDeleteUrl($item['gn_id']) ?>" onclick="return confirm('<?php _e('Do you really want to delete this new?', 'gz_news')?>');"><?php _e('Delete', 'gz_news') ?></a>
							</li>
						</ul>
					</td>


				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<div class="has-pagination">
	<div class="links">
	<?php
		echo $paginator_html;
	?>
	</div>
	</div>
<?php } else { ?>
<p><?php _e("There are no news to display"); ?></p>

<?php } ?>

