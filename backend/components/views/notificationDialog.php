<div class="modal fade" id="modalNotification" tabindex="-1" role="dialog">
    <div class="modal-dialog">                        
        <div class="box box-solid box-<?= $status ?>">
            <div class="box-header">
                <h3 class="box-title"><?= $message1 ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-<?= $status ?> btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <p>
                    <?= $message2 ?>
                </p>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>    
</div>

