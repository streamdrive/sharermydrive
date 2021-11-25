<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/header.php'); check_login('admin'); ?>
<?php
changeTitle('Last Downloads');
$lastDls = $YuuClass->get_last_dls();
?>
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-md-3">
		<?php $menu_active = 2; include_once('menus.php'); ?>		
		</div>
		<div class="col-md-9">
			<div class="card text-muted bg-outline-primary" style="border-radius: 0;">
				<div class="card-body">
					<h4 class="card-title">Last 10 Download's</h4>
					<hr />
					<ul class="timeline" style="margin-left:-25px;">
						<?php foreach ($lastDls as $key => $last) : ?>
						<li>
							<a href="/<?= $last['id']; ?>"><span><?= substr($last['file_name'],0,80);?></span></a>
							<small href="javascript:void(0)"
								class="float-right"><?= date('d M, Y, H:i:s', strtotime($last['download_at'])); ?></small>
							<p>by <?= $last['user']; ?></p>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/footer.php'); ?>
