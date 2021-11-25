<?php include(realpath($_SERVER['DOCUMENT_ROOT']).'/template/header.php'); check_login('admin'); ?>
<?php
changeTitle('Top Users');
$order_by = isset($_GET['orderby']) ? $_GET['orderby'] : 'total_file';
$data = $YuuClass->get_top_users($order_by);
?>
<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-3">
        <?php $menu_active = 5; include_once('menus.php'); ?>        
        </div>
        <div class="col-md-9">
            <div class="card text-muted bg-outline-primary" style="border-radius: 0;">
                <div class="card-body">
                    <h4 class="card-title">Top User's</h4>
                    <hr />
                    <select class="form-control col-md-4 col-sm-6" onchange="window.location=this.value">
                        <option selected disabled>-- Order by --</option>
                        <option value="?orderby=total_file">Total file</option>
                        <option value="?orderby=total_download">Total download</option>
                        <option value="?orderby=total_storage">Total storage</option>
                        <option value="?orderby=DATE(join_date)">Join date</option>
                    </select>
                    <div class="table-responsive">
                        <table data-toggle="table" class="table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Total File</th>
                                    <th>Total Downloads</th>
                                    <th>Total Size Shared</th>
                                    <th data-align="center">Join Date</th>
                                </tr>
                            </thead>
                            <tbody>
<?php foreach ($data as $key => $list) : ?>
                                <tr>
                                    <td><?=++$key;?></td>
                                    <td><?= $list->name;?></td>
                                    <td><?= $list->email; ?></td>
                                    <td><?= number_format($list->total_file); ?></td>
                                    <td><?= number_format($list->total_download); ?></td>
                                    <td><?= filesize_formatted($list->total_storage); ?></td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($list->join_date)); ?></td>
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
