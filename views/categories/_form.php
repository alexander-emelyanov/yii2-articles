<?php

/**
 * @copyright Copyright &copy;2014 Giandomenico Olini
 * @company Gogodigital - Wide ICT Solutions 
 * @website http://www.gogodigital.it
 * @package yii2-articles
 * @github https://github.com/cinghie/yii2-articles
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 */

use yii\helpers\Html;
use cinghie\articles\assets\ArticlesAsset;

// Load Kartik Libraries
use kartik\widgets\ActiveForm;
use kartik\widgets\ActiveField;
use kartik\widgets\DateTimePicker;
use kartik\widgets\FileInput;
use kartik\widgets\InputWidget;
use kartik\widgets\Select2;

// Load Editors Libraries
use dosamigos\ckeditor\CKEditor;
use dosamigos\tinymce\TinyMce;
use kartik\markdown\MarkdownEditor;

// Load Articles Assets
ArticlesAsset::register($this);
$asset = $this->assetBundles['cinghie\articles\assets\ArticlesAsset'];

// Get info For the Select2 Categories 
if ($model->id) { $id = $_REQUEST['id']; } else { $id = 0; }
$select2categories = $model->getCategoriesSelect2($id);

// Get image from Categories
$imagecategories = $model->getCategoriesimage($id);

// Get info by Configuration
$editor    = Yii::$app->controller->module->editor;
$language  = substr(Yii::$app->language,0,2);
$languages = Yii::$app->controller->module->languages;
$imagetype = Yii::$app->controller->module->categoryimagetype;
$imageurl  = Yii::$app->homeUrl.Yii::$app->controller->module->categoryimagepath;

?>

<div class="categories-form">

    <?php $form = ActiveForm::begin([
		'options' => [
			'enctype'=>'multipart/form-data'
		],
	]); ?>
    
    <div class="row">
    
    	<div class="col-lg-12">
    
            <div class="bs-example bs-example-tabs">
            
                <ul class="nav nav-tabs" id="myTab">
                  <li class="active"><a data-toggle="tab" href="#item"><?= Yii::t('articles.message', 'Category') ?></a></li>
                  <li class=""><a data-toggle="tab" href="#image"><?= Yii::t('articles.message', 'Image') ?></a></li>
                  <li class=""><a data-toggle="tab" href="#seo">SEO</a></li>
                  <li class=""><a data-toggle="tab" href="#params"><?= Yii::t('articles.message', 'Options') ?></a></li>
                </ul>
                
                <div class="tab-content" id="myTabContent">
                	<div class="separator"></div>
                    
                    <div id="item" class="tab-pane fade active in">
                    
                        <div class="col-lg-8">
            
                            <?= $form->field($model, 'name', [
									'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-plus"></i>'
										]
									]
								])->textInput(['maxlength' => 255]) ?>
                                
                            <?php if ($editor=="ckeditor"){ ?>
                            	<?= $form->field($model, 'description')->widget(CKEditor::className(), 
									[
										'options' => ['rows' => 12],
										'preset' => 'advanced'
									]); ?>
                            <?php } else if ($editor=="tinymce") { ?>
                            	<?= $form->field($model, 'description')->widget(TinyMce::className(), [
										'options' => ['rows' => 12],
										'language' => $language,
										'clientOptions' => [
											'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
										]
									]); ?>
                            <?php } else if ($editor=="markdown") { ?>
                            	<?= $form->field($model, 'description')->widget(
										MarkdownEditor::classname(),
										['height' => 250, 'encodeLabels' => true]
									); ?>
                            <?php } else { ?>
                            	<?= $form->field($model, 'description')->textarea(['rows' => 12]); ?>
                            <?php } ?>
                
                        </div> <!-- col-lg-8 -->
            
                        <div class="col-lg-4">
                        
                            <?= $form->field($model, 'parent')->widget(Select2::classname(), [
								'data' => $select2categories,
								'pluginOptions' => [
									'allowClear' => true
								],
								'addon' => [
									'prepend' => [
										'content'=>'<i class="glyphicon glyphicon-folder-open"></i>'
									]
								],
							]); ?>
						
							<?= $form->field($model, 'published')->widget(Select2::classname(), [
                                    'data' => [
										1 => Yii::t('articles.message', 'Published'),
										0 => Yii::t('articles.message', 'Unpublished'),
									],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-question-sign"></i>'
										]
									],
                                ]); ?>
                                
                            <?= $form->field($model, 'access')->widget(Select2::classname(), [
                                    'data' => array_merge(["0" => Yii::t('articles.message', 'In Development') ]),
									'options' => [
										'disabled' => 'disabled'
									],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-log-in"></i>'
										]
									],
                                ]); ?>
                            
                            <?php if ($model->isNewRecord){ ?>
                            <?= $form->field($model, 'ordering')->widget(Select2::classname(), [
                                    'data' => array_merge([ "0" =>  Yii::t('articles.message', 'In Development') ]),
									'options' => [
										'disabled' => 'disabled'
									],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-sort"></i>'
										]
									],
                                ]); ?>
                            <?php } else { ?>
                            <?= $form->field($model, 'ordering')->widget(Select2::classname(), [
                                    'data' => array_merge([ "0" =>  Yii::t('articles.message', 'In Development') ]),
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-sort"></i>'
										]
									],
                                ]); ?>
                            <?php } ?>
                            
                            <?= $form->field($model, 'language')->widget(Select2::classname(), [
                                    'data' => array_merge($languages),
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-globe"></i>'
										]
									],
                                ]); ?>           
                            
                        </div> <!-- col-lg-4 -->
                        
                    </div> <!-- #item -->
                    
                    <div id="image" class="tab-pane fade">
                    
                    	<p class="bg-info"><?= Yii::t('articles.message', 'Allowed Extensions')?>: <?= $imagetype ?></p>
                    
                    	<div class="col-lg-6">
                    
                    		<?php if ($imagecategories==""){ ?>
                            
								<?= $form->field($model, 'image')->widget(FileInput::classname(), [
                                        'options' => [
                                            'accept' => 'image/'.$imagetype
                                        ],
                                        'pluginOptions' => [
                                            'previewFileType' => 'image',
                                            'showUpload' => false,
                                            'browseLabel' => Yii::t('articles.message', 'Browse &hellip;'),
                                        ],
                                    ]);?>
                             
                            <?php } else { 	?>		
                            
                            	<?= $form->field($model, 'image')->hiddenInput() ?>
                            
                            	<div class="thumbnail">                       	
                                    <img alt="200x200" class="img-thumbnail" data-src="holder.js/300x250" style="width: 300px;" src="<?= $imageurl.$model->image ?>">
                                    <div class="caption">
                                    	<p></p>
                                        <p>
                                        	<a class="btn btn-danger" href="deleteimage?id=<?= $model->id ?>">
												<?= Yii::t('articles.message', 'Delete Image') ?>
                                            </a> 
                                        </p>
                                    </div>
                                </div>
                            		
							<?php }  ?>
                        
                        </div> <!-- col-lg-6 -->
                        
                        <div class="col-lg-6">
		
							<?= $form->field($model, 'image_caption', [
									'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-picture"></i>'
										]
									 ]
								])->textarea(['rows' => 6]) ?>
                            
                            <?= $form->field($model, 'image_credits', [
									'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-barcode"></i>'
										]
									]
								])->textInput(['maxlength' => 255]) ?>
            
            			</div> <!-- col-lg-6 -->
                        
                            
                    </div> <!-- #image -->
                    
                    <div id="seo" class="tab-pane fade">
                    
                    	<div class="col-lg-5">
                        
                        	<?= $form->field($model, 'alias', ['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-bookmark"></i>']]] )->textInput(['maxlength' => 255]) ?>
							
                            <?= $form->field($model, 'robots')->widget(Select2::classname(), [
                                    'data' => array_merge(["index, follow" => "index, follow"],["no index, no follow" => "no index, no follow"],["no index, follow" => "no index, follow"],["index, no follow" => "index, no follow"]),
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']],
                                ]); ?>   
                            
							<?= $form->field($model, 'author', [
									'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-user"></i>'
										]
									]
								])->textInput(['maxlength' => 50]) ?>

   							<?= $form->field($model, 'copyright', [
									'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-ban-circle"></i>'
										]
									]
								])->textInput(['maxlength' => 50]) ?>
						
                        </div> <!-- col-lg-5 -->
                        
                        <div class="col-lg-7">

							<?= $form->field($model, 'metadesc', [
									'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-info-sign"></i>'
										]
									]
								])->textarea(['rows' => 4]) ?>
                            
                            <?= $form->field($model, 'metakey', [
									'addon' => [
										'prepend' => [
											'content'=>'<i class="glyphicon glyphicon-tags"></i>'
										]
									]
								])->textarea(['rows' => 4]) ?>
                        
                        </div> <!-- col-lg-7 -->
                        
                    </div> <!-- #seo -->
                    
                    <div id="params" class="tab-pane fade">
                        
                        <div class="row">
                            <div class="col-md-4">
                            	<h4><?= Yii::t('articles.message', 'Categories View')?></h4>
                                <?php								
									// Categories Image Width
									echo '<div class="form-group field-categories-categoriesImageWidth">';
									echo '<label class="control-label">'.Yii::t('articles.message', 'Image Width').'</label>';
									echo Select2::widget([
										'name' => 'categoriesImageWidth',
										'data' => [ 
											'small'  => Yii::t('articles.message', 'Small'), 
											'medium' => Yii::t('articles.message', 'Medium'), 
											'large'  => Yii::t('articles.message', 'Large'), 
											'extra'  => Yii::t('articles.message', 'Extra')
										],
									]);
									echo '</div>';
									
									// Show Categories Item Data
									echo '<div class="form-group field-categories-categoriesViewData">';
									echo '<label class="control-label">'.Yii::t('articles.message', 'Show Item Data').'</label>';
									echo Select2::widget([
										'name' => 'categoriesViewData',
										'data' => [ 
											1 => Yii::t('articles.message','Yes'), 
											0 => Yii::t('articles.message','No') 
										],
									]);
									echo '</div>';
								 ?>
                            </div>
                            <div class="col-md-4">
                            	<h4><?= Yii::t('articles.message', 'Category View')?></h4>
                                <?php 
									// Category Image Width
									echo '<div class="form-group field-categories-categoryImageWidth">';
									echo '<label class="control-label">'.Yii::t('articles.message', 'Image Width').'</label>';
									echo Select2::widget([
										'name' => 'categoryImageWidth',
										'data' => [ 
											'small'  => Yii::t('articles.message', 'Small'), 
											'medium' => Yii::t('articles.message', 'Medium'), 
											'large'  => Yii::t('articles.message', 'Large'), 
											'extra'  => Yii::t('articles.message', 'Extra')
										],
									]);
									echo '</div>';
									
									// Show Item Data
									echo '<div class="form-group field-categories-categoryViewData">';
									echo '<label class="control-label">'.Yii::t('articles.message', 'Show Item Data').'</label>';
									echo Select2::widget([
										'name' => 'categoryViewData',
										'data' => [ 
											1 => Yii::t('articles.message','Yes'), 
											0 => Yii::t('articles.message','No') 
										],
									]);
									echo '</div>';
								 ?>
                            </div>
                            <div class="col-md-4">
                            	<h4><?= Yii::t('articles.message', 'Item View')?></h4>
                                <?php 
									// Item Image Width
									echo '<div class="form-group field-categories-itemImageWidth">';
									echo '<label class="control-label">'.Yii::t('articles.message', 'Image Width').'</label>';
									echo Select2::widget([
										'name' => 'itemImageWidth',
										'data' => [ 
											'small'  => Yii::t('articles.message', 'Small'), 
											'medium' => Yii::t('articles.message', 'Medium'), 
											'large'  => Yii::t('articles.message', 'Large'), 
											'extra'  => Yii::t('articles.message', 'Extra')
										],
									]);
									echo '</div>';
									
									// Show Item Data
									echo '<div class="form-group field-categories-itemViewData">';
									echo '<label class="control-label">'.Yii::t('articles.message', 'Show Item Data').'</label>';
									echo Select2::widget([
										'name' => 'itemViewData',
										'data' => [ 
											1 => Yii::t('articles.message','Yes'), 
											0 => Yii::t('articles.message','No') 
										],
									]);
									echo '</div>';
								 ?>
                            </div>
                        </div>                         
                    </div> <!-- #params -->
                  
               </div> <!-- tab-content -->
               
            </div> <!-- bs-example -->
    
    	</div> <!-- col-lg-12 -->
        
    </div> <!-- row -->  

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ?  Yii::t('articles.message', 'Save & Exit') : Yii::t('articles.message', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
