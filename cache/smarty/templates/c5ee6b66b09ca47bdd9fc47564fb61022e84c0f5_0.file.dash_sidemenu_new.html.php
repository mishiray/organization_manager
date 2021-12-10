<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:48:53
  from 'C:\wamp64\www\organization-management\site\templates\root\dash_sidemenu_new.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e765e76513_95863793',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c5ee6b66b09ca47bdd9fc47564fb61022e84c0f5' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\root\\dash_sidemenu_new.html',
      1 => 1639180131,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b3e765e76513_95863793 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Sidebar -->
<ul class="navbar-nav bg-green sidebar sidebar-dark accordion" id="accordionSidebar">

  
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/home">
    <div class="sidebar-brand-icon">
    <!--  <i class="fas fa-laugh-wink"></i> -->
    <img src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/site/media/images/logo.png" alt="" class="img-thumbnail">
    </div>
    <div class="sidebar-brand-text mx-3"><?php echo $_smarty_tpl->tpl_vars['userinfo']->value->department;?>
 </div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('home'))) {?> active <?php }?>">
    <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/home">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    My Preferences
  </div>

  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('profile'))) {?> active <?php }?>">
    <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/profile">
      <i class="fas fa-fw fa-user-alt"></i>
      <span>Profile</span></a>
  </li>

  <!-- Nav Item - Pages Collapse Menu TIMESHEET-->
  
  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('companydoc','companydocs-new','companydocs'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecompanydocs" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-file"></i>
      <span>Company Documents</span>
    </a>
    <div id="collapsecompanydocs" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Uploads</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/companydocs">View Uploaded Docs</a>
        <a class="collapse-item management-only" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/companydocs-new">Add Document</a>
      </div>
    </div>
  </li>

  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('timesheet','timesheet_history'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTimesheet" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-clock"></i>
      <span>Timesheet Attendance</span>
    </a>
    <div id="collapseTimesheet" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Timesheet</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/timesheet">Log Timesheet</a>
        <a class="collapse-item " href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/timesheet_history">View Timesheet Log</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu CRM -->
  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_memo','memos'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMemo" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-bell"></i>
      <span>Announcements</span>
    </a>
    <div id="collapseMemo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Announcements</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/send_memo">Send Announcement</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/memos">Announcements</a>
      </div>
    </div>
  </li>

  <li class="nav-item management-s<?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('employees_assigned'))) {?> active <?php }?>">
    <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/employees_assigned">
      <i class="fas fa-fw fa-users"></i>
      <span>Employees Assigned</span></a>
  </li>

  <li class="nav-item showall<?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('tree','full_tree'))) {?> active <?php }?>">
    <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/full_tree">
      <i class="fas fa-fw fa-users"></i>
      <span>Organization Tree</span></a>
  </li>

  <!-- Nav Item - Pages Collapse Menu CRM -->
  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('emergency_contact','add_guarantor','guarantors'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePdata" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-address-book"></i>
      <span>Personal Affairs</span>
    </a>
    <div id="collapsePdata" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Contacts</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/emergency_contact">Emergency Contacts</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_guarantor">Add Guarantors Contact </a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/guarantors">Guarantors Contacts</a>
        <h6 class="collapse-header">Job Detail</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/view_job_details?id=<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->employeeid;?>
">Job Detail</a>
        <h6 class="collapse-header">Appraisal Records</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/appraisals?id=<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->employeeid;?>
">Appraisal Record</a>
        <h6 class="collapse-header">Disciplinary Records</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/disciplinaries?id=<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->employeeid;?>
">Disciplinary Record</a>
      </div>
    </div>
  </li>

   <!-- Nav Item - Pages Collapse Menu TIMESHEET-->
  
   <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('daily_tasks','add_daily_task'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsetask" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-tasks"></i>
      <span>Activity Report</span>
    </a>
    <div id="collapsetask" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Activity Manager</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_daily_task">Add Activity Report</a>
        <a class="collapse-item " href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/daily_tasks">View Activity Reports</a>
      </div>
    </div>
  </li>


  <!-- Nav Item - Pages Collapse Menu TIMESHEET-->
  
  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('attendance_sheet','attendance'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAttendance" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-calendar"></i>
      <span> Planner</span>
    </a>
    <div id="collapseAttendance" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header"> Planner</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/attendance_sheet"> Planner Sheet</a>
        <a class="collapse-item " href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/attendance">View Plans</a>
      </div>
    </div>
  </li>

  <div class="sidebar-heading">
    My Payslips
  </div>
  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('docs'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDocs" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-file-word"></i>
      <span>Documents Manager</span>
    </a>
    <div id="collapseDocs" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Payslips</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/docs?type=payslip">Saved Payslips</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/payslips">My Payslips</a>
      </div>
    </div>
  </li>
  <div class="sidebar-heading">
    Menu
  </div>
  

  <!-- Nav Item - Pages Collapse Menu CRM -->
  <li class="nav-item customer admin-m legal <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('clients','defaulters','transactions','upcoming_birthday','compose_crm','inbox_crm','messages_crm','memos_crm','send_memo_crm'))) {?> active <?php }?> ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCrm" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-users"></i>
      <span>Customer Relationship Management</span>
    </a>
    <div id="collapseCrm" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Clients</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/clients">View Clients </a>
        <h6 class="collapse-header">Upcoming Clients</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/defaulters">Upcoming Defaulters </a>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/upcoming_birthday">Upcoming Birthdays </a>
         <h6 class="collapse-header">Live Chat</h6>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/chat_care">Customer Care</a>
         <h6 class="collapse-header">Client Messages</h6>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/compose_crm">Compose</a>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/messages_crm">Sent Messages</a>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/inbox_crm">Inbox</a>
         <h6 class="collapse-header">Announcements</h6>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/send_memo_crm">Send Announcement</a>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/memos_crm">Announcements</a>
         <h6 class="collapse-header">Client Transactions</h6>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/transactions">Transactions</a>
      </div>
    </div>
  </li>

  
  <!-- Nav Item - Pages Collapse Menu CORPORATE OFFICE-->
  <li class="nav-item corporate-m <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_video','videos','event-new','events','event','blogs','blog-new','blog','news','news-new','news-info','add_newsletter_subscriber','newsletters','gallery','add_gallery'))) {?> active <?php }?> ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseoffice" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-building"></i>
      <span>Corporate Office</span>
    </a>
    <div id="collapseoffice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Reviews</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/client_reviews">Client Reviews</a>
        <h6 class="collapse-header">Marketing Report</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_corporate_market">New Marketing</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/corporate_marketing">View Marketing</a>
        <h6 class="collapse-header">CSR/Events</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/event-new">Add Events</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/events">View Events</a>
        <h6 class="collapse-header">Corporporate News</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/news-new">Add News</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/news">View News</a>
        <h6 class="collapse-header">Corporate Blogs</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/blog-new">Add Blog</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/blogs">View Blogs</a>
        <h6 class="collapse-header">Gallery</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_gallery">New Image</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/gallery">View Gallery</a>
        <h6 class="collapse-header">Videos</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_video">New Video</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/videos">View Videos</a>
        <h6 class="collapse-header">Newsletters</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_newsletter_subscriber">Add New Subscriber</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/newsletters">Send NewsLetter</a>
        </div>
    </div>
  </li>
  
  <!-- Nav Item - Pages Collapse Menu ACCOUNTING-->
  <li class="nav-item account <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('balance_sheet','cash_flow','aged_vendor_payables','defaulters','profit-and-loss','products_services','bills','add_bill','vendors','estimates','accounting_charts','payments','sales_report','commission_reports','management_commission','marketer_commission','add_payment','manage_finances','finance_reports','view_finance_detail','finance_manager'))) {?> active <?php }?> ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAccounting" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-coins"></i>
      <span>Accounting</span>
    </a>
    <div id="collapseAccounting" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Sales</h6>
         <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'payments') {?> subtive <?php }?> " href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/payments">Payments from Sales</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'add_payment') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_payment">Add Manual Payment</a>
        <h6 class="collapse-header">Payroll</h6>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'payrolls') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/payrolls">View Payroll</a>
        <h6 class="collapse-header">Estimates</h6>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'estimates') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/estimates">Estimates</a>
       
        <h6 class="collapse-header">Purchases</h6>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'bills') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/bills">Bills</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'vendors') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/vendors">Vendors</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'products_services') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/products_services">Products & Services</a>

        <h6 class="collapse-header">Accounting</h6>
        <a class="collapse-item d-none" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/finances">Finances</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'finance_manager') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/finance_manager">Transactions</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'finance_reports') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/finance_reports">View Transactions</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'accounting_charts') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/accounting_charts">Chart Of Accounts</a>

        <h6 class="collapse-header">Reports</h6>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'sales_report') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/sales_report">Sales Report</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'profit-and-loss') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/profit-and-loss">Profit & Loss</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'balance_sheet') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/balance_sheet">Balance Sheet</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'cash_flow') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/cash_flow">Cash Flow</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'defaulters') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/defaulters">Customer Aged Receivables</a>
        <a class="collapse-item <?php if ($_smarty_tpl->tpl_vars['sitePage']->value == 'aged_vendor_payables') {?> subtive <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/aged_vendor_payables"> Vendor Aged Payables</a>
        
      </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu INVENTORY-->
  <li class="nav-item facility information-m <?php if (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level0','level1','level2')) && in_array($_smarty_tpl->tpl_vars['userinfo']->value->department,array('Administrative'))) {?> <?php } else { ?>d-none <?php }?> <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('inventories','add_inventory','inventory'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventory" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-warehouse"></i>
      <span>Inventory</span>
    </a>
    <div id="collapseInventory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Inventory</h6>
        <a class="collapse-item" href="add_inventory">New</a>
        <a class="collapse-item" href="inventories">View</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu MANAGEMENT-->
  <li class="nav-item facility information-m <?php if (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level0','level1','level2')) || in_array($_smarty_tpl->tpl_vars['userinfo']->value->department,array('Facility'))) {?> <?php } else { ?>d-none <?php }?> ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFacility" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-boxes"></i>
      <span>Facility Management</span>
    </a>
    <div id="collapseFacility" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Management</h6>
        <a class="collapse-item" href="add_facility_report">Add Report</a>
        <a class="collapse-item" href="facility_reports">View Reports</a>
        </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu HRIS-->
  <li class="nav-item humanr <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_job_detail','job_details','view_job_details','employees','add_employee','disciplinaries','add_disciplinary','daily_tasks','add_daily_task','add_appraisal','appraisals','add_salary_record','salary_records','payrolls','payroll','add_payroll','payslips','payslip','job-new','jobs'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHR" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-user-tie"></i>
      <span>HRIS</span>
    </a>
    <div id="collapseHR" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Employee Database</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/upcoming_birthday_staff">Upcoming Anniversaries </a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/employees">Employee Details</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_employee">Add Employee</a>
        <h6 class="collapse-header">Employee Job Details</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_job_detail">Add Job Details</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/job_details">View Job Details</a>
        <h6 class="collapse-header">Emergency Contacts</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_emergency">Add Emergency Contact</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/emergencies">View Emergency Contacts</a>
        <h6 class="collapse-header">Guarantors</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_guarantor">Add Guarantors Contact</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/guarantors">View Guarantors Contact</a>
        <h6 class="collapse-header">Salary Records</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_salary_record">Add Salary Records</a>
        <a class="collapse-item humanr-m" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/salary_records">View Salary Records</a>
        <h6 class="collapse-header">Timesheet</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/timesheet_history">View Timesheet Log</a>
        <h6 class="collapse-header humanr-m-only">Financials</h6>
        <a class="collapse-item humanr-m-only" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_payroll">Add Payroll</a>
        <a class="collapse-item humanr-m-only" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/payrolls">View Payroll</a>
        <a class="collapse-item humanr-m-only" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/payslips">Payslips</a>
        <h6 class="collapse-header">Recruitment and Onboarding</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/job-new">Add Job</a>
        <a class="collapse-item humanr-m" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/jobs">View Jobs</a>
        <h6 class="collapse-header humanr-m-only">Performance and Evaluation</h6>
        <a class="collapse-item humanr-m-only" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_appraisal">Add Appraisals</a>
        <a class="collapse-item humanr-m-only" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/appraisals">View Appraisals</a>
        <h6 class="collapse-header humanr-m-only">Training and Development Tools</h6>
        <a class="collapse-item humanr-m-only" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_disciplinary">Add Disciplinary</a>
        <a class="collapse-item humanr-m-only" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/disciplinaries">View Disciplinary</a>
      </div>
    </div>
  </li> 
  
  <!-- Nav Item - Pages Collapse Menu HRIS-->
  <li class="nav-item humanr-m <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_disciplinary_warning','disciplinary_warnings','add_disciplinary_action','disciplinary_actions'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHRtools" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-cogs"></i>
      <span>HRIS TOOLS</span>
    </a>
    <div id="collapseHRtools" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Disciplinary Warnings</h6>
          <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_disciplinary_warning">Add Warning Types</a>
          <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/disciplinary_warnings">View Warning Types</a>
        <h6 class="collapse-header">Disciplinary Actions</h6>
          <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_disciplinary_action">Add Actions Types</a>
          <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/disciplinary_actions">View Actions Types</a>
        </div>
    </div>
  </li>
  

  <!-- Nav Item - Pages Collapse Menu MARKETING-->
  <li class="nav-item market <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_report','reports','add_weekly_report','add_summary_report','weekly_reports','summary_reports'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSales" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-calculator"></i>
      <span>Sales/Marketing</span>
    </a>
    <div id="collapseSales" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Marketing Logs</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_report">Add Marketing Log</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/reports">View Marketing Logs</a>
        <h6 class="collapse-header">Weekly Marketing Reports</h6>
        <a class="collapse-item " href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_weekly_report">Add Weekly/Monthly Report</a>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/weekly_reports">View Weekly/Monthly Reports</a>
         <h6 class="collapse-header">Marketing Summary Reports</h6>
         <a class="collapse-item  " href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_summary_report">Add Summary Report</a>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/summary_reports">View Summary Reports</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu ADMIN USERS-->
  <li class="nav-item admin-m <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('users'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStaff" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-user"></i>
      <span>Admin Users</span>
    </a>
    <div id="collapseStaff" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Admin Users</h6>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/officials">All Users</a>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_official">New User</a>
      </div>
    </div>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Nav Item - Pages Collapse Menu MESSAGES-->
  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('compose','messages','inbox','chat'))) {?> active <?php }?> ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMessaging" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-comments"></i>
      <span> Prop Communicator</span>
    </a>
    <div id="collapseMessaging" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/chat">Instant Chat</a>
        <h6 class="collapse-header">Messaging Box</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/compose">Compose</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/messages">Sent Messages</a>
         <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/inbox">Inbox</a>
      
      </div>
    </div>
  </li>
  
  <!-- Nav Item - Pages Collapse Menu MANAGEMENT CENTRE-->
  <li class="nav-item management <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('daily_sales_report','weekly_sales_report','monthly_sales_report','yearly_sales_report','docupload','docuploads','docupload-new'))) {?> active <?php }?> ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsemanagement" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-user-cog"></i>
      <span>Management Centre</span>
    </a>
    <div id="collapsemanagement" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Minutes</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/docuploads?category=minute"">All Meeting Minutes</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/docupload-new">Add Meeting Minutes</a>
        <h6 class="collapse-header">Milestones</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/docuploads?category=milestone"">All Milestones Uploaded</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/docupload-new">Add Milestone</a>
        <h6 class="collapse-header">Management Reporting</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/daily_sales_report">Daily Sales Reports</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/weekly_sales_report">Weekly Sales Reports</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/monthly_sales_report">Monthly Sales Reports</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/yearly_sales_report">Yearly Sales Reports</a>
      </div>
    </div>
  </li>
  
  
  <!-- Nav Item - Pages Collapse Menu ADMIN TOOLBOX-->
  <li class="nav-item admin-m <?php if (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level0'))) {?> <?php } else { ?> d-none <?php }?> <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_branch','branches','add_department','departments','toolbox'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsetoolbox" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-user-cog"></i>
      <span>Admin Toolbox</span>
    </a>
    <div id="collapsetoolbox" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Branches</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_branch">Add Branch</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/branches">View Branches</a>
        <h6 class="collapse-header">Departments</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_department">Add Department</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/departments">View Departments</a>
        <h6 class="collapse-header">Settings</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/settings">Settings</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/logger">Activity Logs</a>
      </div>
    </div>
  </li>
  


<!-- Nav Item - Pages Collapse Menu CRM -->
  <li class="nav-item showall <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('variance_reports','add_variance_report'))) {?> active <?php }?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTicket" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-ticket-alt"></i>
      <span>Tickets</span>
    </a>
    <div id="collapseTicket" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Log Tickets</h6>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/variance_reports">View Tickets</a>
        <a class="collapse-item" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/add_variance_report">Create New Ticket </a>
      </div>
    </div>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar --><?php }
}
