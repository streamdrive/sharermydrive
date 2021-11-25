<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/header.php'); check_login('admin'); ?>
<?php
changeTitle('Last Downloads');
$data = $YuuClass->get_most_downloads();
?>
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-md-3">
		<?php $menu_active = 3; include_once('menus.php'); ?>		
		</div>
		<div class="col-md-9">
			<div class="card text-muted bg-outline-primary" style="border-radius: 0;">
				<div class="card-body">
					<h4 class="card-title">Top Download's</h4>
					<hr />
					<div class="table-responsive">
						<table data-toggle="table" class="table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>File Name</th>
									<th>User</th>
									<th>Count</th>
									<th data-align="center">Last Download</th>
									<th data-align="center">Created at</th>
								</tr>
							</thead>
							<tbody>
<?php foreach ($data as $key => $list) : ?>
								<tr>
									<td><?=++$key;?></td>
									<td><a href="/<?= $list['id']; ?>"><?= $list['file_name'];?></a></td>
									<td><?= $list['file_owner_mail']; ?></td>
									<td><?= number_format($list['downloads']); ?></td>
									<td><?= isset($list['download_at']) ? date('d/m/Y, H:i:s', strtotime($list['download_at'])) : ''; ?></td>
									<td><?= date('d/m/Y, H:i:s', strtotime($list['created_date'])); ?></td>
								</tr>
<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/footer.php'); ?>
