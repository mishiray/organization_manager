<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:47:17
  from 'C:\wamp64\www\organization-management\site\templates\root\home.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e70579c334_29338439',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a867c089f3cf3e40dcbd3c4c13c1323f3d4cee23' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\root\\home.html',
      1 => 1639145424,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b3e70579c334_29338439 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_122499223861b3e705753e57_73845975', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "root.html");
}
/* {block "content"} */
class Block_122499223861b3e705753e57_73845975 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_122499223861b3e705753e57_73845975',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<section class="jumbotron text-center">
	<!--img src="--><!--$Site.siteProtocol--><!--$Site.domainName--><!--/site/media/i/all/dashboard_model.jpg" style="" class="img-fluid rounded mx-auto"-->
	<div class="row  justify-content-center">

		<div class="col-md-3 account-only mb-4">
			<div class="card bg-gradient-primary text-white shadow">
				<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/finances">
					<div class=" text-white card-body">
						<i class="fa fa-calculator  text-white"></i>
						<div class="text-white">Accounting & Expenses</div>
					</div>
				</a>
			</div>
		</div>
		
		<div class="col-md-3 humanr-only mb-4">
			<div class="card bg-gradient-success text-white shadow">
				<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/employees">
					<div class="text-white card-body">
						<i class="fa fa-user-circle text-white"></i>
						<div class="text-white">Human Resource</div>
						
					</div>
				</a>
			</div>
		  </div>
		  <div class="col-md-3 market-only mb-4">
			<div class="card bg-gradient-info text-white shadow">
				<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/reports">
					<div class="text-white card-body">
						<i class="fa fa-credit-card text-white"></i>
					  <div class="text-white">Sales/Marketing</div>
					</div>
				</a>
			</div>
		  </div>
		  <div class="col-md-3 customer-only mb-4">
			<div class="card bg-gradient-primary text-white shadow">
				<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/inbox">
					<div class=" text-white card-body">
						<i class="fa fa-comment text-white"></i> 
						<div class="text-white">
							Customer Service
						</div>
					  </div>
				</a>
			</div>
		</div>
		<div class="col-md-3 mb-4">
			<div class="card bg-gradient-success text-white shadow">
				<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/technical">
					<div class="text-white card-body">
						<i class="fa fa-wrench text-white"></i>
						<div class="text-white">Technical Operation</div>
					</div>
				</a>
			</div>
		  </div>
		  <div class="col-md-3 facility-only mb-4">
			<div class="card bg-gradient-info text-white shadow">
				<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/inventories">
					<div class="text-white card-body">
						<i class="fa fa-archive text-white"></i>
						
					  <div class="text-white">Inventory</div>
					</div>
				</a>
			</div>
		  </div>
		  <div class="col-md-3 customer-only mb-4">
			<div class="card bg-gradient-primary text-white shadow">
				<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/clients">
					<div class=" text-white card-body">
						<i class="fa fa-headphones text-white"></i>
						
					  <div class="text-white">Clients</div>
					  </div>
				</a>
			</div>
		</div>
		<div class="col-md-3 mb-4">
			<div class="card bg-gradient-success text-white shadow">
				<a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_variance_report">
					<div class="text-white card-body">
						<i class="fa fa-question-circle text-white"></i>
						<div class="text-white">Help</div>
					</div>
				</a>
			</div>
		  </div>
	</div>
	
	<div class="row <?php if (!in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level0','level1','level2'))) {?> d-none <?php }?>">


		<!-- Area Chart -->
		  <div class="col-xl-8 col-lg-7">
			<div class="card shadow mb-4">
			  <!-- Card Header - Dropdown -->
			  <div class="card-header py-3 d-flex bg-gradient-danger flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-light">Earnings Overview</h6>
			</div>
			  <!-- Card Body -->
			  <div class="card-body">
				<div class="chart-area">
				  <canvas id="myAreaChart"></canvas>
				</div>
			  </div>
			</div>
		  </div>
	</div>
</section>
<?php
}
}
/* {/block "content"} */
}
