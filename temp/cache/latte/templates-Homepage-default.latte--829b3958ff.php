<?php
// source: C:\xampp\htdocs\vykazuj\app\presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template829b3958ff extends Latte\Runtime\Template
{
	public $blocks = [
		'scripts' => 'blockScripts',
		'content' => 'blockContent',
		'head' => 'blockHead',
	];

	public $blockTypes = [
		'scripts' => 'html',
		'content' => 'html',
		'head' => 'html',
	];


	function main()
	{
		extract($this->params);
?>


<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('scripts', get_defined_vars());
?>

<?php
		$this->renderBlock('content', get_defined_vars());
		$this->renderBlock('head', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		$this->parentName = "@login.latte";
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockScripts($_args)
	{
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
  <body class="text-center">
      <div class="container">
        <div class="panel panel-body">
            <form class="form-signin" action="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 11 */ ?>/charge/">
              <img src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 12 */ ?>/images/granton_logo.png">  
              <h1 class="h1 mb-4 font-weight-semibold">Vykazuj.cz</h1>
              <label for="inputEmail" class="sr-only">Email address</label>
              <input type="text" id="mail" class="form-control" placeholder="Email address" required autofocus>
              <label for="inputPassword" class="sr-only">Password</label>
              <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit">Přihlásit se</button>
            </form>
        </div>
      </div>
  </body>
  
<?php
	}


	function blockHead($_args)
	{
		extract($_args);
?>
<style>
	body {     
    font-family: 'Titillium Web', sans-serif;
    font-weight: 600;
    font-style: normal; 
    background: #f8f9fa;
    display: -ms-flexbox;
    -ms-flex-align: center;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    font-size:15pt;
}

/* Change the white to any color ;) */
input:-webkit-autofill, input:-webkit-autofill:focus,  input:-webkit-autofill:visited, input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px white inset;
}
 
.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="text"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
<?php
	}

}
