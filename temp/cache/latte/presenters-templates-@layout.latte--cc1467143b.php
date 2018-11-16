<?php
// source: C:\xampp\htdocs\vykazuj\app\presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Templatecc1467143b extends Latte\Runtime\Template
{
	public $blocks = [
		'head' => 'blockHead',
		'scripts' => 'blockScripts',
		'body_start' => 'blockBody_start',
		'content' => 'blockContent',
		'body_end' => 'blockBody_end',
		'style' => 'blockStyle',
	];

	public $blockTypes = [
		'head' => 'html',
		'scripts' => 'html',
		'body_start' => 'html',
		'content' => 'html',
		'body_end' => 'html',
		'style' => 'html',
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
		$this->renderBlock('body_start', get_defined_vars());
?>

<?php
		$this->renderBlock('content', get_defined_vars());
?>

<?php
		$this->renderBlock('body_end', get_defined_vars());
?>

        
<?php
		$this->renderBlock('style', get_defined_vars());
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
        <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 29 */ ?>/js/init_scripts.js"></script>

<?php
	}


	function blockBody_start($_args)
	{
		extract($_args);
?>
<body>
<div class="row">
    <div class="col-12 col-lg-2 col-sm-12  col-xs-12 text-center panel-left">
        <h2 class="h1 mb-4 font-weight-semibold red">Vykazuj.cz</h2>
        <img src="
<?php
		if ((isset($userImage) && $userImage!='')) {
			?>                 <?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($userImage)) /* line 40 */ ?>

<?php
		}
		else {
			?>                <?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 42 */ ?>/images/honza.jpg
<?php
		}
?>
                " class="rounded-circle" alt="Cinque Terre" width="150px">
        <span class="full-name"><?php echo LR\Filters::escapeHtmlText($firstName) /* line 45 */ ?> <?php
		echo LR\Filters::escapeHtmlText($lastName) /* line 45 */ ?></span>
        <span class="job-title"><?php
		if ($lastName == 'Haase' || $lastName == 'Lamaj') {
			?>Slave<?php
		}
		else {
			?>Jednatel<?php
		}
?></span>
        <div class="list-group">
            <a class="list-group-item <?php
		if ($activePage=='charge') {
			?>active<?php
		}
		?>" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Charge:default")) ?>"><i class="far fa-clock"></i>Timesheety</a>
            <a class="list-group-item <?php
		if ($activePage=='clients') {
			?>active<?php
		}
		?>" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Clients:default")) ?>"><i class="fas fa-users"></i>Klienti</a>
            <a class="list-group-item <?php
		if ($activePage=='statistics') {
			?>active<?php
		}
		?>" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Statistics:default")) ?>"><i class="fas fa-chart-line"></i>Statistiky</a>
            <a class="list-group-item <?php
		if ($activePage=='settings') {
			?>active<?php
		}
		?>" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Settings:default")) ?>"><i class="fas fa-cog"></i>Nastavení</a>
            <a class="list-group-item <?php
		if ($activePage=='will_never_happen') {
			?>active<?php
		}
		?>" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:logout")) ?>"><i class="fas fa-sign-out-alt"></i>Odhlásit</a>
                        
        </div>
    </div>
        
<?php
	}


	function blockContent($_args)
	{
		
	}


	function blockBody_end($_args)
	{
?></div>
</body>
<?php
	}


	function blockStyle($_args)
	{
		extract($_args);
?>
<style>
    
.small-screen { display:none;}
        

select.client_not_name_label{
    font-weight: 600 !important;
    text-align: left !important;
    background-color: #ffffff;
    border-radius: 5px;
    border: 1px solid #ddd;
    padding-left: 10px;
}

.nopadding {
   padding: 0 !important;
   margin: 0 !important;
}

@media screen and (max-width: 992px) {
        body{ font-size: 14px;}
        .nav-pills > li { padding: 3px; font-size: 12px;}
        .panel-left { height: 100%;}
        .panel-left > h2.red{ font-size: 20px; display: none;}
        .panel-left > span.full-name{ font-size: 16px; display: none;}
        .panel-left > span.job-title{ font-size: 10px; display: none;}
        .panel-left > img { display: none;}
        .table-hover > tbody > tr > td:first-child { font-size: 16px;}
        .table th, .table td {  padding-left: 0rem; vertical-align: middle;}
        
        .list-group { margin-top: 0px; display: inline-flex; -ms-flex-direction: column; flex-direction: row;}
        .list-group-item {  margin-bottom: 0px; padding: 0.5rem 0.5rem; }
        input#client_name_label { font-size: 1.5rem; }
        .timetable-blank { margin-top: 0px; }
        .panel-mid { padding-top: 20px;}
        .big-screen { display:none;}
        .small-screen { display:block;}
}
</style>

<?php
	}

}
