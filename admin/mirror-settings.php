<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/header.php'); check_login('admin'); ?>
<?php
changeTitle('Last Downloads');
// $data = $YuuClass->get_most_downloads();
$multiup_user = $YuuClass->get_option('multiup_user');
$multiup_password = $YuuClass->get_option('multiup_password');
$success = false;
if(isset($_POST['multiup_user']) && isset($_POST['multiup_pass'])) {
    $YuuClass->update_option('multiup_user', $_POST['multiup_user']);
    $YuuClass->update_option('multiup_password', $_POST['multiup_pass']);
    $success = true;
}
?>
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-md-3">
		<?php $menu_active = 6; include_once('menus.php'); ?>		
		</div>
		<div class="col-md-9">
			<div class="card text-muted bg-outline-primary" style="border-radius: 0;">
				<form method="POST" class="card-body">
					<h4 class="card-title">Mirror's Settings</h4>
					<hr />
                    <?php if($success) : ?>
                        <div class="alert alert-primary">Successfully update option</div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="">Multiup Account</label>
                        <input type="text" class="form-control" name="multiup_user" placeholder="username" value="<?= $multiup_user; ?>" autocomplete="OFF"/>
                        <input type="text" class="form-control" name="multiup_pass" placeholder="password" value="<?= $multiup_password; ?>" autocomplete="OFF"/>
                    <small class="form-text text-muted">Leave blank to disabled</small>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Submit</button>
                    </div>
				</form>
			</div>
		</div>
	</div>
	<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/footer.php'); ?>
