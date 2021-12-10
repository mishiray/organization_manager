<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:47:46
  from 'C:\wamp64\www\organization-management\site\templates\root\profile.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e7222d84f0_33510078',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '847ec30e7613c1034ee763ff05f1ea0374c4a04e' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\root\\profile.html',
      1 => 1616577299,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b3e7222d84f0_33510078 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_77884546961b3e7220f6970_39021215', "breadcrumb");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_157208452161b3e7221036a0_85128578', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "root.html");
}
/* {block "breadcrumb"} */
class Block_77884546961b3e7220f6970_39021215 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'breadcrumb' => 
  array (
    0 => 'Block_77884546961b3e7220f6970_39021215',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<section class="breadcrumb"><strong class="uppercase">Dasboard |</strong>
	&nbsp;<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/dashboard/my-profile/profile">Account&nbsp;<i class="fa fa-vcard"></i></a>&nbsp;| Profile&nbsp;<i class="fa fa-user"></i>
</section>
<?php
}
}
/* {/block "breadcrumb"} */
/* {block "content"} */
class Block_157208452161b3e7221036a0_85128578 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_157208452161b3e7221036a0_85128578',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\wamp64\\www\\organization-management\\lib\\Smarty\\plugins\\modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),));
?>

<section class="row justify-content-center">
	<div class="col-md-10">
		<div class="card card-heading pt-3 pb-3">
			<div class="text-center justify-content-center">
				<form method="post" enctype="multipart/form-data">
					<div class="img-scope col-4">
						<label for="img-upload">
							<img class="img img-fluid img-profile  rounded-circle" id="dataImg" src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/<?php if (!empty($_smarty_tpl->tpl_vars['userinfo']->value->userimg)) {
echo $_smarty_tpl->tpl_vars['userinfo']->value->userimg;
} else { ?>site/media/i/admin-user.png<?php }?>"> <br>
							<data class="text-center jsonly">Update Photo</data>
						</label>
						<input type="file" accept="image/*" id="img-upload" onchange="this.form.submit()" name="img_upload"/>
						<noscript><button class="text-center" id="ppUpload" name="ppUpload" type="button">Upload Photo</button></noscript>
					</div>
				</form>
			</div>
		</div>
		<div class="card card-body row justify-content-center mt-3 mb-3">
			<div class="col-md-12">
				<?php if (!empty($_smarty_tpl->tpl_vars['fail']->value)) {?>
					<?php echo $_smarty_tpl->tpl_vars['fail']->value;?>

				<?php }?>
			</div>
			<form class="form" method="post" style="display: inherit;">
				<div class="col-md-6">
					<fieldset>
						<legend>Personal Information</legend>
						<div class="form-group">
							<label for="empid">Employee ID</label>
							<input type="text" id="empid" readonly value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->employeeid;?>
" class="form-control input-block"/>
						</div>
						<div class="form-group">
							<label for="reflink">Referral link</label>
							<input type="text" id="reflink" readonly value="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/signup?id=<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->employeeid;?>
" class="form-control input-block"/>
							<span class="text-primary" id="msg"></span>
							<button type="button" class=" m-2 btn btn-info" onclick="copyToClipboardMsg('reflink','msg')">Copy Link</button>
						</div>
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" id="username" name="username" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->username;?>
" class="form-control input-block" required/>
						</div>
						<div class="form-group">
							<label for="firstName" class="label">First Name</label>
							<input type="text" id="firstName" name="firstname" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->firstname;?>
" class="form-control input-block" required />
						</div>
						<div class="form-group">
							<label for="lastName">Last Name</label>
							<input type="text" id="lastName" name="lastname" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->lastname;?>
" class="form-control input-block" required/>
						</div>
						<div class="form-group">
							<label for="lastName">Other Names</label>
							<input type="text" id="otherName" name="othername" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->middlename;?>
" class="form-control input-block"/>
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" id="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->email;?>
" readonly class="form-control input-block" disabled/>
						</div>
						<div class="form-group">
							<label for="phone">Phone Number</label>
							<input type="tel" id="phone" name="phone" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->phone;?>
" class="form-control input-block" required/>
						</div>
						<div class="form-group">
							<label for="gender">Gender</label>
							<select name="gender" class="form-control" id="gender" required>
								<option value="female" <?php if ($_smarty_tpl->tpl_vars['userinfo']->value->gender == 'female') {?>selected<?php }?>>Female</option>
								<option value="male" <?php if ($_smarty_tpl->tpl_vars['userinfo']->value->gender == 'male') {?>selected<?php }?>>Male</option>
							</select>
						</div>
					</fieldset>
				</div>
				<div class="col-md-6">
					<fieldset>
						<legend>Address Information</legend>
						<div class="form-group">
							<label for="address1">Address</label>
							<textarea id="address1" name="address1" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->address1;?>
" rows="3" class="form-control" required><?php echo $_smarty_tpl->tpl_vars['userinfo']->value->address1;?>
</textarea>
						</div>
						<div class="form-group">
							<label for="address2">Address</label>
							<textarea id="address2" name="address2" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->address2;?>
" rows="3" class="form-control"><?php echo $_smarty_tpl->tpl_vars['userinfo']->value->address2;?>
</textarea>
						</div>
						<div class="form-group">
							<label for="country">Nationality</label>
							<select id="country" name="country" required class="form-control signup-input">
								<?php if (!empty($_smarty_tpl->tpl_vars['countries']->value)) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['countries']->value, 'country');
$_smarty_tpl->tpl_vars['country']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['country']->value) {
$_smarty_tpl->tpl_vars['country']->do_else = false;
?>
									<option value="<?php echo $_smarty_tpl->tpl_vars['country']->value->name;?>
" <?php if ($_smarty_tpl->tpl_vars['userinfo']->value->country == $_smarty_tpl->tpl_vars['country']->value->name) {?>selected<?php }?>><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['country']->value->name);?>
</option>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</select>
						</div>
						<div class="form-group">
							<label for="state">State of Origin</label>
							<select id="state" name="state" required class="form-control signup-input">
								<?php if (!empty($_smarty_tpl->tpl_vars['states']->value)) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['states']->value, 'state');
$_smarty_tpl->tpl_vars['state']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['state']->value) {
$_smarty_tpl->tpl_vars['state']->do_else = false;
?>
									<option value="<?php echo $_smarty_tpl->tpl_vars['state']->value->name;?>
" <?php if ($_smarty_tpl->tpl_vars['userinfo']->value->state == $_smarty_tpl->tpl_vars['state']->value->name) {?>selected<?php }?>><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['state']->value->name);?>
</option>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</select>
						</div>
						<div class="form-group">
							<label for="citi">City | Local Govt Area</label>
							<input type="text" name="city" value="<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->city;?>
" id="citi" class="d-none form-control signup-input" list="city">
							<select id="city" name="city" class="form-control signup-input"> 
								<?php if (!empty($_smarty_tpl->tpl_vars['cities']->value)) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['cities']->value, 'city');
$_smarty_tpl->tpl_vars['city']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['city']->value) {
$_smarty_tpl->tpl_vars['city']->do_else = false;
?>
									<option value="<?php echo $_smarty_tpl->tpl_vars['city']->value->name;?>
" <?php if ($_smarty_tpl->tpl_vars['userinfo']->value->city == $_smarty_tpl->tpl_vars['city']->value->name) {?>selected<?php }?>><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['city']->value->name);?>
</option>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</select>
						</div>
						<div class="form-group">
							<button class="btn btn-success btn-block" type="submit" name="personal">Update Changes</button>
						</div>
					</fieldset>
				</div>
			</form>
		</div>
	</div>
</section>
<?php
}
}
/* {/block "content"} */
}
