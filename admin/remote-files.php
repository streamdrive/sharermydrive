<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/header.php'); check_login('admin'); ?>
<?php
changeTitle('Remote Files');
$batas      = 50;
$page       = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$offset     = ($page > 1) ? ($page-1)*$batas : $page - 1;
$allFiles 	= $YuuClass->get_all_files($offset, $batas);
$next       = $page + 1;
$prev       = ($page !== 10) ? $page - 1 : 0;
$jumData    = $YuuClass->get_count('tb_file');
$jumHalaman = ceil($jumData / $batas);
$count_statistic = $YuuClass->get_file_count_statistic();
?>
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-md-3">
		<?php $menu_active = 1; include_once('menus.php'); ?>		
		</div>
		<div class="col-md-9">
			<div class="card text-muted bg-outline-primary" style="border-radius: 0;">
				<div class="card-body">
					<h4 class="card-title">Remote Files</h4>
					<hr />
					<div class="row">
						<div class="col-md-6">
								<div class="d-flex align-items-center p-3 text-dark bg-default rounded box-shadow">
									<div class="mr-3"><i class="fa fa-calendar fa-2x"></i></div>
									<div class="lh-100">
										<h6 class="mb-0 lh-100"><?= $count_statistic->count_today; ?></h6>
										<small> File uploaded today</small>
									</div>
								</div>
						</div>
						<div class="col-md-6">
								<div class="d-flex align-items-center p-3 text-dark bg-default rounded box-shadow">
									<div class="mr-3"><i class="fa fa-calendar fa-2x"></i></div>
									<div class="lh-100">
										<h6 class="mb-0 lh-100"><?= $count_statistic->count_yesterday ; ?></h6>
										<small> File uploaded yesterday</small>
									</div>
								</div>
						</div>
					</div>
					<div class="toolbar btn-group">
						<button class="btn btn-danger btn-sm" onclick="cekCheck()"><i class="fa fa-trash"></i></button>
					</div>
					<div class="table-responsive">
						<table class="table-bordered" data-toggle="table" data-search="true" data-search-align="right"
							data-toolbar-align="left" data-toolbar=".toolbar">
							<thead>
								<tr>
									<th class="text-center" data-width="10px"><input type="checkbox" id="checkAll"></th>
									<th>File Name</th>
									<th>Size</th>
									<th data-align="center">Dls</th>
									<th>Created</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($allFiles['files'] as $key => $file): ?>
								<?php $fileUrl = $file['id']."/".sanitize_filename($file['file_name']); ?>
								<?php
								if ($file['downloads'] >= 100 && $file['downloads'] < 500) {
									$trclass = 'table-secondary';
								} elseif ($file['downloads'] >= 500) {
									$trclass = 'table-primary';
								} else {
									$trclass = null;
								}
							?>
								<tr class="<?= $trclass; ?>">
									<td><input type="checkbox" name="chk" value="<?= $file['id'];?>"></td>
									<td title="<?= $file['file_owner_mail'];?>"><a
											href="/<?=$fileUrl; ?>"><?=substr($file['file_name'], 0,60);?></a>
									</td>
									<td><?= filesize_formatted($file['file_size']);?></td>
									<td><?= $file['downloads']; ?>x</td>
									<td><?= timeAgo($file['created_date']); ?></td>
								</tr>
								<?php endforeach;?>
							</tbody>
						</table>
					</div>
					<hr />
					<div class="text-center"><span class="text-muted"><?= $page .' of '. $jumHalaman; ?></span></div>
					<ul class="pagination float-right">
						<?php if($next > 2) : ?>
						<li class="page-item"><a href="/admin/remote-files?page=<?= $prev;?>" class="page-link">&larr;
								Back</a></li>
						<?php endif; ?>
						<?php if($jumHalaman != $page && $jumHalaman > 1) : ?>
						<li class="page-item active"><a href="/admin/remote-files?page=<?= $next;?>" class="page-link">More
								&rarr;</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function () {
		$("input#checkAll").click(function () {
			$('input:checkbox[name=chk]').not(this).prop('checked', this.checked);
		});
	});

	function cekCheck() {
		swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.value)
				$('input[name="chk"]:checked').each(function (i) {
					var id = $(this).attr('data-val');
					delFile($(this), true);
				});

		});
	}
</script>
<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/footer.php'); ?>
