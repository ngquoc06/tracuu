<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Cập nhật Bài Viết</h3>
        <?php
        $role = Yii::app()->user->role;
        if (in_array($role, array('administrator', 'moderator', 'publisher'))) {
            $post_status = Yii::app()->params['post_status'];
        } else {
            $post_status = array('Updating' => 'Updating', 'Pending' => 'Pending');
        }
        $post_tag = CHtml::listData(Tag::model()->findAll(), 'tag_id', 'tag_name');
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'post-form',
            'enableAjaxValidation' => false,
        ));
        ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="formSep">
            <div class="row-fluid">
                <div class="span9">
                    <?php echo $form->labelEx($model, 'post_title'); ?>
                    <?php echo $form->textField($model, 'post_title', array('class' => 'textField span12')); ?>
                    <?php echo $form->error($model, 'post_title'); ?>
                    <div class="row-fluid">
                        <?php echo $form->labelEx($model, 'post_content_head'); ?>
                        <?php
                        $this->widget("application.extensions.ckeditor.CKEditor", array("model" => $model,
                            "attribute" => "post_content_head",
                        ));
                        ?>
                        <?php echo $form->error($model, 'post_content_head'); ?>
                    </div>
                    <div class="row-fluid">
                        <?php echo $form->labelEx($model, 'post_content_body'); ?>
                        <?php
                        $this->widget("application.extensions.ckeditor.CKEditor", array("model" => $model,
                            "attribute" => "post_content_body",
                        ));
                        ?>
                        <?php echo $form->error($model, 'post_content_body'); ?>
                    </div>
                    <div class="row-fluid">
                        <?php $this->widget('application.widgets.tinymce.TinyMCE', array('model'=>$model, 'field'=>'post_content_head', 'options'=> array('height'=>600))); ?>
                    </div>
                    <div class="row-fluid">
                        <?php echo $form->labelEx($model, 'post_content_foot'); ?>
                        <?php
                        $this->widget("application.extensions.ckeditor.CKEditor", array("model" => $model,
                            "attribute" => "post_content_foot",
                        ));
                        ?>
                        <?php echo $form->error($model, 'post_content_foot'); ?>
                    </div>
                    <div class="row-fluid">
                        <div id="w_sort05" class="w-box">    
                            <div class="w-box-header">
                                Danh sách Comments
                                <?php
                                $role = Yii::app()->user->role;
                                if (in_array($role, array('administrator', 'moderator', 'publisher'))) {
                                    ?>
                                    <div class="pull-right">
                                        <a id="add_comments" alt="Thêm Nhận xét" href="<?php echo Yii::app()->createUrl('/admin/comments/create', array('post_id' => $model->post_id)) ?>">
                                            <i class="splashy-document_letter_upload"> + </i>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="w-box-content cnt_a" id="comment_list">
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="span3">
                    <div class="row-fluid">
                        <div id="w_sort05" class="w-box">    
                            <div class="w-box-header">
                                Quản lý
                            </div>
                            <div class="w-box-content cnt_a">
                                <div class="sepH_b">
                                    <label for="Post_post_author"><span>Tác giả:</span> <?php echo Common::getValueAttributesByKey('User', 'username', $model->post_author); ?></label>
                                </div>
                                <div class="sepH_b">
                                    <label for="Post_post_category"><span>Chuyên mục:</span> <?php echo Common::getValueAttributesByKey('Category', 'category_name', $model->post_category); ?></label>
                                </div>
                                <div class="sepH_b">
                                    <label for="Post_post_date"><span>Ngày tạo:</span> <?php echo date('F j, Y, g:i a', strtotime($model->post_date)); ?></label>
                                </div>
                                <div class="sepH_b">
                                    <label for="Post_post_approved_user"><span>Người duyệt:</span> <?php echo!empty($model->post_approved_user) ? Common::getValueAttributesByKey('User', 'username', $model->post_approved_user) : 'Chưa Duyệt'; ?></label>
                                </div>
                                <div class="formSep">
                                    <label for="Post_post_status"><span>Trạng thái:</span> <?php echo $form->dropDownList($model, 'post_status', $post_status, array('class' => 'dropDownList span8', 'style' => 'margin-bottom:0;')); ?></label>
                                </div>
                                <div class="clearfix">
                                    <?php echo CHtml::submitButton('Cập nhật', array('class' => 'btn btn-gebo pull-left')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div id="w_sort05" class="w-box">    
                            <div class="w-box-header">
                                Thẻ
                            </div>
                            <div class="w-box-content cnt_a">
                                <?php
                                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                    // $codeList contains key=>label pairs, typically from CHtml::listData
                                    'name' => 'post_tag_auto',
                                    'htmlOptions' => array(
//                                'disabled' => 'disabled'
                                    ),
                                    'options' => array(
                                        'minLength' => '2',
                                        'select' => 'js:function(event, ui) {
                                        $(".tagchecklist").append(\'<span><input type="hidden" name="Post[post_tag][]" value="\'+ui.item.value+\'"><a tabindex="0" class="ntdelbutton" onclick="removetag(this)">X</a>&nbsp;<span>\'+ui.item.label+\'</span></span>\');
                                        this.value = "";
                                        return false;
                                    }',
                                        'focus' => 'js:function( event, ui ) {return false;}'
                                    ),
                                    'source' => array_map(function($key, $value) {
                                        return array('label' => $value, 'value' => $key);
                                    }, array_keys($post_tag), $post_tag),
                                ));
                                ?>
                                <script>
                                    function removetag(tthis) {
                                        $(tthis).parent('span').remove();
                                        return false;
                                    }
                                </script>
                                <div class="tagchecklist">
                                    <?php
                                    if (!empty($model->post_tag)) {
                                        foreach ($model->post_tag as $value) {
                                            ?>
                                            <span>
                                                <input type="hidden" name="Post[post_tag][]" value="<?php echo $value; ?>">
                                                <a tabindex="0" class="ntdelbutton" onclick="removetag(this)">X</a>
                                                &nbsp;<span><?php echo $post_tag[$value]; ?></span>
                                            </span>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div><!-- form -->

        <?php $this->endWidget(); ?>

    </div>
</div>
<script>
    $(document).ready(function() {
        $("#comment_list").load("<?php echo Yii::app()->createUrl('/admin/comments/index', array('post_id' => $model->post_id)) ?>");
        $("#add_comments").colorbox({
            'width': '60%'
            , 'maxHeight': '70%'
            , 'fixed': true
            , 'title': "Thêm nhận xét"
        });
    })
    function edit_comments(comment_id) {
        $.colorbox({
            href: "<?php echo Yii::app()->createUrl('/admin/comments/update'); ?>" + "/id/" + comment_id
            , 'width': '60%'
            , 'maxHeight': '70%'
            , 'fixed': true
            , 'title': "Sửa nhận xét"
        });
        return false;
    }
    function delete_comments(comment_id) {
        $.colorbox({
            href: "<?php echo Yii::app()->createUrl('/admin/comments/delete'); ?>" + "/id/" + comment_id
            , 'width': '60%'
            , 'maxHeight': '70%'
            , 'fixed': true
            , 'title': "Xóa nhận xét"
        });
        return false;
    }
</script>