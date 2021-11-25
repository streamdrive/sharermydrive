<?php include(realpath($_SERVER['DOCUMENT_ROOT']) . '/template/header.php'); ?>
<?php changeTitle('About - YuuDrive'); ?>
<div class="container" style="margin-top: 30px;">
    <div class="card">
        <strong class="card-header bg-primary text-white text-center"><i class="fa fa-info-circle"></i> About us</strong>
        <div class="card-body text-muted">
          <strong><?= $app['description']; ?>.</strong>
        </div>
    </div>
</div>
<?php include(realpath($_SERVER['DOCUMENT_ROOT']) . '/template/footer.php'); ?>