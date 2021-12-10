<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:47:19
  from 'C:\wamp64\www\organization-management\site\templates\base\default.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e707edd8e2_06976654',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1c22d2a1d2b70d1e50f26fd48821b4cde269514c' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\base\\default.html',
      1 => 1639140064,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:style.html' => 1,
    'file:header.html' => 1,
    'file:chatbox.html' => 1,
    'file:scripts.html' => 1,
  ),
),false)) {
function content_61b3e707edd8e2_06976654 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo ucwords($_smarty_tpl->tpl_vars['Site']->value['companyName']);?>
</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,600,700,800,900&display=swap" rel="stylesheet">

    <link rel="apple-touch-icon" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/site/media/i/atobe_logo.png">
    <link rel="icon" type="image/x-icon" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/site/media/i/atobe_logo.png">
    
    <?php $_smarty_tpl->_subTemplateRender('file:style.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
   
  </head>
  <body>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_68260010761b3e707e9c234_44969276', "header");
?>

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_113634710961b3e707eab976_36281999', "content");
?>

      
  
    <!-- loader -->

  <!---Book Site Inspection Modal PopUp-->
  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_157045318361b3e707ec79a2_93944328', "chat_box");
?>

  <?php $_smarty_tpl->_subTemplateRender('file:scripts.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  </body>
</html><?php }
/* {block "header"} */
class Block_68260010761b3e707e9c234_44969276 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_68260010761b3e707e9c234_44969276',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:header.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block "header"} */
/* {block "main-slider"} */
class Block_165715977861b3e707eb3902_55462444 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php
}
}
/* {/block "main-slider"} */
/* {block "main_content"} */
class Block_169909228961b3e707ebc035_07732569 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section class="ftco-section contact-section">
        <div class="container">
          
        </div>
      </section>
      <section class="ftco-section contact-section">
        <div class="container">
          
        </div>
      </section>

      <?php
}
}
/* {/block "main_content"} */
/* {block "content"} */
class Block_113634710961b3e707eab976_36281999 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_113634710961b3e707eab976_36281999',
  ),
  'main-slider' => 
  array (
    0 => 'Block_165715977861b3e707eb3902_55462444',
  ),
  'main_content' => 
  array (
    0 => 'Block_169909228961b3e707ebc035_07732569',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_165715977861b3e707eb3902_55462444', "main-slider", $this->tplIndex);
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_169909228961b3e707ebc035_07732569', "main_content", $this->tplIndex);
?>

    <?php
}
}
/* {/block "content"} */
/* {block "chat_box"} */
class Block_157045318361b3e707ec79a2_93944328 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'chat_box' => 
  array (
    0 => 'Block_157045318361b3e707ec79a2_93944328',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	  <?php $_smarty_tpl->_subTemplateRender("file:chatbox.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  <?php
}
}
/* {/block "chat_box"} */
}
