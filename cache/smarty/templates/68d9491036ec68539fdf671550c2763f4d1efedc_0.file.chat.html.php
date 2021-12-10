<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:49:01
  from 'C:\wamp64\www\organization-management\site\templates\root\chat.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e76d755435_41010291',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '68d9491036ec68539fdf671550c2763f4d1efedc' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\root\\chat.html',
      1 => 1632317025,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b3e76d755435_41010291 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_146347733061b3e76d715c81_79716039', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "root.html");
}
/* {block "content"} */
class Block_146347733061b3e76d715c81_79716039 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_146347733061b3e76d715c81_79716039',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <div class="card-header py-3">
    <h3 class="m-0 font-weight-bold text-dark text-center"> <i class="text-primary fa fa-comments"></i> <?php echo $_smarty_tpl->tpl_vars['Site']->value['companyName'];?>
 Chat</h3>
  </div>
  <div class="row" justify-content-center">
    <div class="col-lg-3">
        <div class="msger_people">
            <header class="msger-header bg-danger text-light">
              <div class="msger-header-title"   data-toggle="collapse" data-target="#peopleslist" aria-expanded="true" aria-controls="peopleslist" id="peoplist">
                <i class="fas fa-user"></i> Chats <i class="fa fa-arrow-down">
              </i>
              </div>
              <div class="msger-header-options">
                <span data-toggle="modal" data-target="#new_chatModal"><i class="fas fa-plus"></i></span>
              </div>
            </header>
            
            <div id="peopleslist" class="mt-2 collapse row justify-content-center" aria-labelledby="peopleslist">
              <main class="msger-people-list">
                  <table class="table table-bordered peoples" id="people" width="100%" style="border-collapse:separate; ">
                      <tbody id="recent_people">
                        <!-- Script Ways -->
                      </tbody>
                  </table>
              </main>
            </div>
            <input type="hidden" name="" id="setUser" value="null">
        </div>
    </div>
    <div class="col-md-8 col-lg-9" >
    <section class="msger">
        <header class="msger-header bg-success text-light">
          <div class="msger-header-title">
            <i class="fas fa-comment-alt"></i> <span id="msg_title">Messages</span>
          </div>
        </header>
      
        <main class="msger-chat">
          <!-- Script Ways -->
        </main>
      
        <form  id="send-area" class="msger-inputarea">
            <input type="text" class="msger-input" placeholder="Enter your message...">
            <button type="submit" class="msger-send-btn">Send</button>
        </form>
      </section>
      </div>
  </div>
</div>
<!-- add person Modal-->
<div class="modal fade" id="new_chatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Text Person</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <?php if (!empty($_smarty_tpl->tpl_vars['peoples']->value)) {?>
            <table class="table peoples" id="dataTable" width="100%">
                <thead>
                  <th>Names</th>
                </thead>
                <tbody class="new_chat">
                    <?php $_smarty_tpl->_assignInScope('numCount', 1);?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['peoples']->value, 'per');
$_smarty_tpl->tpl_vars['per']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['per']->value) {
$_smarty_tpl->tpl_vars['per']->do_else = false;
?>
                        <?php if ($_smarty_tpl->tpl_vars['per']->value->email != $_smarty_tpl->tpl_vars['userinfo']->value->email) {?>
                        <tr class="" i = "<?php echo $_smarty_tpl->tpl_vars['numCount']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['per']->value->email;?>
" fullname="<?php echo ucfirst($_smarty_tpl->tpl_vars['per']->value->lastname);?>
 <?php echo ucfirst($_smarty_tpl->tpl_vars['per']->value->firstname);?>
">
                            <td>
                                <a class="text-decoration-none btn" data-dismiss="modal" rel="noopener noreferrer"> <?php echo ucfirst($_smarty_tpl->tpl_vars['per']->value->lastname);?>
 <?php echo ucfirst($_smarty_tpl->tpl_vars['per']->value->firstname);?>
 </a>
                            </td>
                        </tr>
                        <?php }?>
                        <?php $_smarty_tpl->_assignInScope('numCount', $_smarty_tpl->tpl_vars['numCount']->value+1);?>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </tbody>
            </table>
             <?php }?>
        </div>
      </div>
    </div>
<?php
}
}
/* {/block "content"} */
}
