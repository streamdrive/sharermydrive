<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/header.php'); check_login('admin'); ?>
<?php
changeTitle('Last User Registered');
$data = $YuuClass->get_last_registered();
$count_registered = $YuuClass->get_last_registered_count();
?>
<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-3">
        <?php $menu_active = 4; include_once('menus.php'); ?>        
        </div>
        <div class="col-md-9">
            <div class="card text-muted bg-outline-primary" style="border-radius: 0;">
                <div class="card-body">
                    <h4 class="card-title">Last User Registered</h4>
                    <hr />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 text-dark bg-default rounded box-shadow">
                                <div class="mr-3"><i class="fa fa-calendar fa-2x"></i></div>
                                <div class="lh-100">
                                    <h6 class="mb-0 lh-100"><?= $count_registered->count_today; ?></h6>
                                    <small> Registered user today </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 text-dark bg-default rounded box-shadow">
                                <div class="mr-3"><i class="fa fa-calendar fa-2x"></i></div>
                                <div class="lh-100">
                                    <h6 class="mb-0 lh-100"><?= $count_registered->count_yesterday ; ?></h6>
                                    <small> Registered user yesterday</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table data-toggle="table" class="table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th data-align="center">Join Date</th>
                                </tr>
                            </thead>
                            <tbody>
<?php foreach ($data as $key => $last) : ?>
                                <tr>
                                    <td><?=++$key;?></td>
                                    <td><?= $last['name'];?></td>
                                    <td><?= $last['email']; ?></td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($last['join_date'])); ?></td>
                                </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/footer.php'); ?>
