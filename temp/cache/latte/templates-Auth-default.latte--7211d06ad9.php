<?php
// source: C:\xampp\htdocs\vykazuj\app\presenters/templates/Auth/default.latte

use Latte\Runtime as LR;

class Template7211d06ad9 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
?>

<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<body>
<p>
                    
<?php
		if (Nette\Config\Configurator::detectDebugMode()) {
			?>	<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiPresenter->link("Auth:callback", ['strategy' => 'fake'])) ?>">Fake login</a><br>
<?php
		}
		?><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiPresenter->link("Auth:auth", ['Google'])) ?>">Sign-in with Google</a><br>
<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiPresenter->link("Auth:auth", ['Facebook'])) ?>">Sign-in with Facebook</a><br>
<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiPresenter->link("Auth:auth", ['Twitter'])) ?>">Sign-in with Twitter</a><br>
<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiPresenter->link("Auth:auth", ['LinkedIn'])) ?>">Sign-in with LinkedIn</a><br>
                    
                </p>
</body>
<?php
	}

}
