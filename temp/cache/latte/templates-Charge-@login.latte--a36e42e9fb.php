<?php
// source: C:\xampp\htdocs\vykazuj\app\presenters\templates\Charge\@login.latte

use Latte\Runtime as LR;

class Templatea36e42e9fb extends Latte\Runtime\Template
{
	public $blocks = [
		'head' => 'blockHead',
		'scripts' => 'blockScripts',
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'head' => 'html',
		'scripts' => 'html',
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title><?php
		if (isset($this->blockQueue["title"])) {
			$this->renderBlock('title', $this->params, function ($s, $type) {
				$_fi = new LR\FilterInfo($type);
				return LR\Filters::convertTo($_fi, 'html', $this->filters->filterContent('striphtml', $_fi, $s));
			});
			?> | <?php
		}
?>Nette Sandbox</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 13 */ ?>/css/style.css">
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 14 */ ?>/css/bootstrap.css" rel="stylesheet" media="screen">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:600" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:700" rel="stylesheet">
	<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('head', get_defined_vars());
?>
</head>


<?php
		$this->renderBlock('scripts', get_defined_vars());
?>

<?php
		$this->renderBlock('content', get_defined_vars());
?>


</body>
</html>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockHead($_args)
	{
		
	}


	function blockScripts($_args)
	{
		extract($_args);
?>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://nette.github.io/resources/js/netteForms.min.js"></script>
        <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 25 */ ?>/js/jquery-3.3.1.min.js"></script>        
        <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 26 */ ?>/js/bootstrap.min.js"></script>
	<script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 27 */ ?>/js/Chart.js"></script>
        <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 28 */ ?>/js/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
<?php
	}


	function blockContent($_args)
	{
		
	}

}
