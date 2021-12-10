<?php
/* Smarty version 3.1.39, created on 2021-12-10 15:17:50
  from 'C:\wamp64\www\organization-management\site\templates\root\default.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3618e441c65_71007527',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa85df4ac2eebb151de20d4f1b3cefe8237e9fdc' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\root\\default.html',
      1 => 1632475833,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:style.html' => 1,
    'file:dash_sidemenu_new.html' => 1,
    'file:navbar.html' => 1,
    'file:script.html' => 1,
  ),
),false)) {
function content_61b3618e441c65_71007527 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\wamp64\\www\\organization-management\\lib\\Smarty\\plugins\\modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),));
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <title>Admin @ <?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['Site']->value['companyName']);?>
 | <?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['sitePage']->value);?>
</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="robots" content="noindex">
      <link rel="apple-touch-icon" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/site/media/i/atobe_logo.png">
      <link rel="icon" type="image/x-icon" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/site/media/i/atobe_logo.png">
      <?php $_smarty_tpl->_subTemplateRender('file:style.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!--div class="overlay-menu"></div-->
		<!Dashboard Side Menu>
		<?php $_smarty_tpl->_subTemplateRender('file:dash_sidemenu_new.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>    
    
    <!Dashboard Content Wrapper>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar --> 
          <?php $_smarty_tpl->_subTemplateRender('file:navbar.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div id="memo_messages" class="row">
          </div>
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_55214976461b3618e4187c2_73437196', "content");
?>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; <strong><?php echo $_smarty_tpl->tpl_vars['Site']->value['companyName'];?>
</strong>. All Rights Reserved.</span>
            <div class="credits"> Powered by <a class="author-link" href="https://hoffenheimtechnologies.com/">Hoffenheim Technologies</a>
            </div>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/logout">Logout</a>
        </div>
      </div>
    </div>
  </div>
</body>
<?php $_smarty_tpl->_subTemplateRender('file:script.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</html>
<?php }
/* {block "content"} */
class Block_55214976461b3618e4187c2_73437196 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_55214976461b3618e4187c2_73437196',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

						<section class="jumbotron text-center">
							<h4>Dashboard Error 404: Page not found</h4>
						</section>
						<?php
}
}
/* {/block "content"} */
}
