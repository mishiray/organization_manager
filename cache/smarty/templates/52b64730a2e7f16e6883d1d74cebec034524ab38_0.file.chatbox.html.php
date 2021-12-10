<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:47:20
  from 'C:\wamp64\www\organization-management\site\templates\base\chatbox.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e70856b9e4_64962158',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '52b64730a2e7f16e6883d1d74cebec034524ab38' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\base\\chatbox.html',
      1 => 1633966361,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b3e70856b9e4_64962158 (Smarty_Internal_Template $_smarty_tpl) {
?>
<span class="chat-careButton"><i class="fa fa-headphones fa-2x"></i></span>
<div class="chatApp invisible">
	<div class="card p1">
	  	<div class="card-heading chatHeader">
		    <h3 class="card-title chatTitle">Atobe CC - How can we help you today?</h3>
		    <span class="chatClose pull-right">x</span>
	  	</div>
	  	<div class="card-body chatBody" id="newChat">
	  		<div class="card-title chatFr2"></div>
	  		<div class="form-group chatFr">
	  			<label class="form-label">Name</label>
	  			<input name="messageName" id="chatName" class="form-control messageName"/>
	  		</div>
	  		<div class="form-group chatFr">
	  			<label class="form-label">Email</label>
	  			<input name="messageEmail" id="chatEmail" class="form-control messageEmail"/>
	  		</div>
	  		<div class="form-group chatFr2 chatListing">
	  		</div>
	  		<div class="form-group">
	  			<label class="form-label chatMsgLabel">Describe your message here...</label>
	  			<textarea name="messageTitle" id="chatMsg" class="form-control messageTitle" rows="2"></textarea>
	  		</div>
	  	</div>
	    <div class="card-footer py-1 chatFooter">
	    	<button class="btn btn-primary  chatSave chatStart">Start</button>
	    	<button class="btn btn-danger  chatEnd">Close</button>&nbsp;
	    </div>
	</div>
</div>
<?php }
}
