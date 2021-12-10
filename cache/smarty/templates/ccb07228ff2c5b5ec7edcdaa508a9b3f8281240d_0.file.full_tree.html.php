<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:47:59
  from 'C:\wamp64\www\organization-management\site\templates\root\full_tree.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e72f2906f1_57108734',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ccb07228ff2c5b5ec7edcdaa508a9b3f8281240d' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\root\\full_tree.html',
      1 => 1632914750,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b3e72f2906f1_57108734 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7884704361b3e72f245513_80225603', "content");
?>

  <?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "root.html");
}
/* {block "content"} */
class Block_7884704361b3e72f245513_80225603 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_7884704361b3e72f245513_80225603',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<!-- Page Heading -->
<h1 style="text-transform: uppercase;" class="h3 mb-2 text-gray-800">AMG Consultant Tree</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Consultant Tree</h6>
    </div>
    <div class="form-group">
      <?php if (!empty($_smarty_tpl->tpl_vars['fail']->value)) {?>
        <?php echo $_smarty_tpl->tpl_vars['fail']->value;?>

      <?php }?>
    </div>
    <div class="row">
      <div class="col-md-4 bg-danger pt-2 m-4">
        <form action="" class="d-flex" method="post">
        <div class="form-group w-100">
          <label class="text-light" for="department" class="form-control-label">Select Consultant</label>
          <select onchange=" location = this.value;" class="form-control w-100" id="department">
          <?php if (!empty($_smarty_tpl->tpl_vars['consultants']->value)) {?>
              <option selected disabled>Select Consultant</option>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['consultants']->value, 'consultant');
$_smarty_tpl->tpl_vars['consultant']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['consultant']->value) {
$_smarty_tpl->tpl_vars['consultant']->do_else = false;
?>
            <option <?php if (!empty($_smarty_tpl->tpl_vars['filter']->value->consultant)) {?> <?php if ($_smarty_tpl->tpl_vars['consultant']->value->email == $_smarty_tpl->tpl_vars['filter']->value->consultant) {?>selected <?php }?> <?php }?>  value="?id=<?php echo base64_encode($_smarty_tpl->tpl_vars['consultant']->value->email);?>
"><?php echo $_smarty_tpl->tpl_vars['consultant']->value->lastname;?>
 <?php echo $_smarty_tpl->tpl_vars['consultant']->value->firstname;?>
 - <?php echo $_smarty_tpl->tpl_vars['consultant']->value->userid;?>
</option>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          <?php } else { ?>
            <option value="null">none</option>
          <?php }?>
          </select> 
        </div>  
       </form>
      </div>
    </div>
    <?php if (!empty($_smarty_tpl->tpl_vars['main']->value)) {?>
    <div class="card-body">
      <div class="table-responsive">
        <div class="row w-100 justify-content-center">
            <div class="col-md-12 text-center p-4">
                <ul class="tree w-100">
                    <?php if (!empty($_smarty_tpl->tpl_vars['main']->value)) {?>
                    <li id="tree_top" class="subs"> 
                        <span class="bg-danger text-light">
                          <?php echo $_smarty_tpl->tpl_vars['main']->value->firstname;?>
 <?php echo $_smarty_tpl->tpl_vars['main']->value->lastname;?>
 <br> <?php echo $_smarty_tpl->tpl_vars['main']->value->userid;?>

                        </span>
                        <!-- js things -->
                    </li>
                    <?php }?>
                </ul>
            </div>
        </div>
      </div>
    </div>
    <?php }?>
  </div>
<?php
}
}
/* {/block "content"} */
}
