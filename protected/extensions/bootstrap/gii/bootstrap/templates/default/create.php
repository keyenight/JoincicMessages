<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Create',
);\n";
?>

$this->menu=array(
array('label'=>Yii::t('<?php echo $this->modelClass; ?>','List <?php echo $this->modelClass; ?>'),'url'=>array('index')),
array('label'=>Yii::t('<?php echo $this->modelClass; ?>','Manage <?php echo $this->modelClass; ?>'),'url'=>array('admin')),
);
?>

<h1><?php echo "<?php"; ?> Yii::t('<?php echo $this->modelClass; ?>','Create <?php echo $this->modelClass; ?>'); ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
