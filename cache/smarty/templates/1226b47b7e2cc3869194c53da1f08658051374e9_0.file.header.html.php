<?php
/* Smarty version 3.1.39, created on 2021-12-10 15:17:52
  from 'C:\wamp64\www\organization-management\site\templates\base\header.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b361901d5062_96538501',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1226b47b7e2cc3869194c53da1f08658051374e9' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\base\\header.html',
      1 => 1639140183,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b361901d5062_96538501 (Smarty_Internal_Template $_smarty_tpl) {
?>
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-dark p-0" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/default">OG-Manager</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
	        		 <a class="nav-link " data-toggle="dropdown" id="login1" href="#" role="button" aria-expanded="false" aria-haspopup="true"> Login
                     </a>
                 <div class="dropdown-menu" aria-labelledby="login1">
                 <ul class="list-group list-group-flush">
                  <li class="list-group-item"><a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/login">Client's Login</a></li>
                <li class="list-group-item"><a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/admin">Staff Login</a></li>
                    </div>
          </li>
	          <li class="nav-item"><a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/contact" class="nav-link">Contact</a></li>
        </ul>
	      </div>
	    </div>
	  </nav>
	  <?php }
}
