<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:47:17
  from 'C:\wamp64\www\organization-management\site\templates\root\style.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e705e24436_39567925',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eabdc152ab6fc5e65e6553bbbdeb5b4b4de427e4' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\root\\style.html',
      1 => 1634821438,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b3e705e24436_39567925 (Smarty_Internal_Template $_smarty_tpl) {
?>  <!-- Custom fonts for this template-->
  <link href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/css/admin.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" /-->    
  <!-- Custom styles for this page -->
  <link href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('blog-new','blog','project-new','project','news-new','news-info','event-new','event','blogs','edit_blog','add_blog','newsletters','report-new','report','docupload-new','docupload','companydocs-new','companydoc','management_report','add_management_report','investment_webpage'))) {?>
  <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common/quilljs/quill.core.css">
  <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common/quilljs/quill.bubble.css">
  <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common/quilljs/quill.snow.css">
  <?php }?>

  <style>
      
    .nav-item{
        display: none;
    }
    #editcontent{
        background-color: white!important;
        height: fit-content!important;
    }
<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('chat'))) {?>
:root {
  --body-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  --msger-bg: #fff;
  --border: 2px solid #ddd;
  --left-msg-bg: #ececec;
  --right-msg-bg: #579ffb;
}

html {
  box-sizing: border-box;
}

*,
*:before,
*:after {
  margin: 0;
  padding: 0;
  box-sizing: inherit;
}

body {
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-image: var(--body-bg);
  font-family: Helvetica, sans-serif;
}
.msger_people {
  display: flex;
  flex-flow: column wrap;
  justify-content: space-between;
  width: 100%;
  margin: 25px 10px;
  border: var(--border);
  border-radius: 5px;
  background: var(--msger-bg);
  box-shadow: 0 15px 15px -5px rgba(0, 0, 0, 0.2);
}
.msger {
  display: flex;
  flex-flow: column wrap;
  justify-content: space-between;
  width: 100%;
  max-width: 867px;
  margin: 25px 10px;
  height: calc(100% - 50px);
  border: var(--border);
  border-radius: 5px;
  background: var(--msger-bg);
  box-shadow: 0 15px 15px -5px rgba(0, 0, 0, 0.2);
}

.msger-header {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  border-bottom: var(--border);
  background: #eee;
  color: #666;
}
.msger-people-list{
  max-height: 500px;
  flex: 1;
  overflow-y: auto;
  padding: 10px;
}
.msger-chat {
  max-height: 500px;
  flex: 1;
  overflow-y: auto;
  padding: 10px;
}
.msger-chat::-webkit-scrollbar {
  width: 6px;
}
.msger-chat::-webkit-scrollbar-track {
  background: #ddd;
}
.msger-chat::-webkit-scrollbar-thumb {
  background: #bdbdbd;
}
.msg {
  display: flex;
  align-items: flex-end;
  margin-bottom: 10px;
}
.msg:last-of-type {
  margin: 0;
}
.msg-img {
  width: 50px;
  height: 50px;
  margin-right: 10px;
  background: #ddd;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  border-radius: 50%;
}
.msg-bubble {
  max-width: 450px;
  padding: 15px;
  border-radius: 15px;
  background: var(--left-msg-bg);
}
.msg-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}
.msg-info-name {
  margin-right: 10px;
  font-weight: bold;
}
.msg-info-time {
  font-size: 0.85em;
}

.left-msg .msg-bubble {
  border-bottom-left-radius: 0;
}

.right-msg {
  flex-direction: row-reverse;
}
.right-msg .msg-bubble {
  background: var(--right-msg-bg);
  color: #fff;
  border-bottom-right-radius: 0;
}
.right-msg .msg-img {
  margin: 0 0 0 10px;
}

.msger-inputarea {
  display: flex;
  padding: 10px;
  border-top: var(--border);
  background: #eee;
}
.msger-inputarea * {
  padding: 10px;
  border: none;
  border-radius: 3px;
  font-size: 1em;
}
.msger-input {
  flex: 1;
  background: #ddd;
}
.msger-send-btn {
  margin-left: 10px;
  background: rgb(0, 196, 65);
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.23s;
}
.msger-send-btn:hover {
  background: rgb(0, 180, 50);
}

.msger-chat {
  background-color: #fcfcfe;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='260' height='260' viewBox='0 0 260 260'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23dddddd' fill-opacity='0.4'%3E%3Cpath d='M24.37 16c.2.65.39 1.32.54 2H21.17l1.17 2.34.45.9-.24.11V28a5 5 0 0 1-2.23 8.94l-.02.06a8 8 0 0 1-7.75 6h-20a8 8 0 0 1-7.74-6l-.02-.06A5 5 0 0 1-17.45 28v-6.76l-.79-1.58-.44-.9.9-.44.63-.32H-20a23.01 23.01 0 0 1 44.37-2zm-36.82 2a1 1 0 0 0-.44.1l-3.1 1.56.89 1.79 1.31-.66a3 3 0 0 1 2.69 0l2.2 1.1a1 1 0 0 0 .9 0l2.21-1.1a3 3 0 0 1 2.69 0l2.2 1.1a1 1 0 0 0 .9 0l2.21-1.1a3 3 0 0 1 2.69 0l2.2 1.1a1 1 0 0 0 .86.02l2.88-1.27a3 3 0 0 1 2.43 0l2.88 1.27a1 1 0 0 0 .85-.02l3.1-1.55-.89-1.79-1.42.71a3 3 0 0 1-2.56.06l-2.77-1.23a1 1 0 0 0-.4-.09h-.01a1 1 0 0 0-.4.09l-2.78 1.23a3 3 0 0 1-2.56-.06l-2.3-1.15a1 1 0 0 0-.45-.11h-.01a1 1 0 0 0-.44.1L.9 19.22a3 3 0 0 1-2.69 0l-2.2-1.1a1 1 0 0 0-.45-.11h-.01a1 1 0 0 0-.44.1l-2.21 1.11a3 3 0 0 1-2.69 0l-2.2-1.1a1 1 0 0 0-.45-.11h-.01zm0-2h-4.9a21.01 21.01 0 0 1 39.61 0h-2.09l-.06-.13-.26.13h-32.31zm30.35 7.68l1.36-.68h1.3v2h-36v-1.15l.34-.17 1.36-.68h2.59l1.36.68a3 3 0 0 0 2.69 0l1.36-.68h2.59l1.36.68a3 3 0 0 0 2.69 0L2.26 23h2.59l1.36.68a3 3 0 0 0 2.56.06l1.67-.74h3.23l1.67.74a3 3 0 0 0 2.56-.06zM-13.82 27l16.37 4.91L18.93 27h-32.75zm-.63 2h.34l16.66 5 16.67-5h.33a3 3 0 1 1 0 6h-34a3 3 0 1 1 0-6zm1.35 8a6 6 0 0 0 5.65 4h20a6 6 0 0 0 5.66-4H-13.1z'/%3E%3Cpath id='path6_fill-copy' d='M284.37 16c.2.65.39 1.32.54 2H281.17l1.17 2.34.45.9-.24.11V28a5 5 0 0 1-2.23 8.94l-.02.06a8 8 0 0 1-7.75 6h-20a8 8 0 0 1-7.74-6l-.02-.06a5 5 0 0 1-2.24-8.94v-6.76l-.79-1.58-.44-.9.9-.44.63-.32H240a23.01 23.01 0 0 1 44.37-2zm-36.82 2a1 1 0 0 0-.44.1l-3.1 1.56.89 1.79 1.31-.66a3 3 0 0 1 2.69 0l2.2 1.1a1 1 0 0 0 .9 0l2.21-1.1a3 3 0 0 1 2.69 0l2.2 1.1a1 1 0 0 0 .9 0l2.21-1.1a3 3 0 0 1 2.69 0l2.2 1.1a1 1 0 0 0 .86.02l2.88-1.27a3 3 0 0 1 2.43 0l2.88 1.27a1 1 0 0 0 .85-.02l3.1-1.55-.89-1.79-1.42.71a3 3 0 0 1-2.56.06l-2.77-1.23a1 1 0 0 0-.4-.09h-.01a1 1 0 0 0-.4.09l-2.78 1.23a3 3 0 0 1-2.56-.06l-2.3-1.15a1 1 0 0 0-.45-.11h-.01a1 1 0 0 0-.44.1l-2.21 1.11a3 3 0 0 1-2.69 0l-2.2-1.1a1 1 0 0 0-.45-.11h-.01a1 1 0 0 0-.44.1l-2.21 1.11a3 3 0 0 1-2.69 0l-2.2-1.1a1 1 0 0 0-.45-.11h-.01zm0-2h-4.9a21.01 21.01 0 0 1 39.61 0h-2.09l-.06-.13-.26.13h-32.31zm30.35 7.68l1.36-.68h1.3v2h-36v-1.15l.34-.17 1.36-.68h2.59l1.36.68a3 3 0 0 0 2.69 0l1.36-.68h2.59l1.36.68a3 3 0 0 0 2.69 0l1.36-.68h2.59l1.36.68a3 3 0 0 0 2.56.06l1.67-.74h3.23l1.67.74a3 3 0 0 0 2.56-.06zM246.18 27l16.37 4.91L278.93 27h-32.75zm-.63 2h.34l16.66 5 16.67-5h.33a3 3 0 1 1 0 6h-34a3 3 0 1 1 0-6zm1.35 8a6 6 0 0 0 5.65 4h20a6 6 0 0 0 5.66-4H246.9z'/%3E%3Cpath d='M159.5 21.02A9 9 0 0 0 151 15h-42a9 9 0 0 0-8.5 6.02 6 6 0 0 0 .02 11.96A8.99 8.99 0 0 0 109 45h42a9 9 0 0 0 8.48-12.02 6 6 0 0 0 .02-11.96zM151 17h-42a7 7 0 0 0-6.33 4h54.66a7 7 0 0 0-6.33-4zm-9.34 26a8.98 8.98 0 0 0 3.34-7h-2a7 7 0 0 1-7 7h-4.34a8.98 8.98 0 0 0 3.34-7h-2a7 7 0 0 1-7 7h-4.34a8.98 8.98 0 0 0 3.34-7h-2a7 7 0 0 1-7 7h-7a7 7 0 1 1 0-14h42a7 7 0 1 1 0 14h-9.34zM109 27a9 9 0 0 0-7.48 4H101a4 4 0 1 1 0-8h58a4 4 0 0 1 0 8h-.52a9 9 0 0 0-7.48-4h-42z'/%3E%3Cpath d='M39 115a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm6-8a6 6 0 1 1-12 0 6 6 0 0 1 12 0zm-3-29v-2h8v-6H40a4 4 0 0 0-4 4v10H22l-1.33 4-.67 2h2.19L26 130h26l3.81-40H58l-.67-2L56 84H42v-6zm-4-4v10h2V74h8v-2h-8a2 2 0 0 0-2 2zm2 12h14.56l.67 2H22.77l.67-2H40zm13.8 4H24.2l3.62 38h22.36l3.62-38z'/%3E%3Cpath d='M129 92h-6v4h-6v4h-6v14h-3l.24 2 3.76 32h36l3.76-32 .24-2h-3v-14h-6v-4h-6v-4h-8zm18 22v-12h-4v4h3v8h1zm-3 0v-6h-4v6h4zm-6 6v-16h-4v19.17c1.6-.7 2.97-1.8 4-3.17zm-6 3.8V100h-4v23.8a10.04 10.04 0 0 0 4 0zm-6-.63V104h-4v16a10.04 10.04 0 0 0 4 3.17zm-6-9.17v-6h-4v6h4zm-6 0v-8h3v-4h-4v12h1zm27-12v-4h-4v4h3v4h1v-4zm-6 0v-8h-4v4h3v4h1zm-6-4v-4h-4v8h1v-4h3zm-6 4v-4h-4v8h1v-4h3zm7 24a12 12 0 0 0 11.83-10h7.92l-3.53 30h-32.44l-3.53-30h7.92A12 12 0 0 0 130 126z'/%3E%3Cpath d='M212 86v2h-4v-2h4zm4 0h-2v2h2v-2zm-20 0v.1a5 5 0 0 0-.56 9.65l.06.25 1.12 4.48a2 2 0 0 0 1.94 1.52h.01l7.02 24.55a2 2 0 0 0 1.92 1.45h4.98a2 2 0 0 0 1.92-1.45l7.02-24.55a2 2 0 0 0 1.95-1.52L224.5 96l.06-.25a5 5 0 0 0-.56-9.65V86a14 14 0 0 0-28 0zm4 0h6v2h-9a3 3 0 1 0 0 6H223a3 3 0 1 0 0-6H220v-2h2a12 12 0 1 0-24 0h2zm-1.44 14l-1-4h24.88l-1 4h-22.88zm8.95 26l-6.86-24h18.7l-6.86 24h-4.98zM150 242a22 22 0 1 0 0-44 22 22 0 0 0 0 44zm24-22a24 24 0 1 1-48 0 24 24 0 0 1 48 0zm-28.38 17.73l2.04-.87a6 6 0 0 1 4.68 0l2.04.87a2 2 0 0 0 2.5-.82l1.14-1.9a6 6 0 0 1 3.79-2.75l2.15-.5a2 2 0 0 0 1.54-2.12l-.19-2.2a6 6 0 0 1 1.45-4.46l1.45-1.67a2 2 0 0 0 0-2.62l-1.45-1.67a6 6 0 0 1-1.45-4.46l.2-2.2a2 2 0 0 0-1.55-2.13l-2.15-.5a6 6 0 0 1-3.8-2.75l-1.13-1.9a2 2 0 0 0-2.5-.8l-2.04.86a6 6 0 0 1-4.68 0l-2.04-.87a2 2 0 0 0-2.5.82l-1.14 1.9a6 6 0 0 1-3.79 2.75l-2.15.5a2 2 0 0 0-1.54 2.12l.19 2.2a6 6 0 0 1-1.45 4.46l-1.45 1.67a2 2 0 0 0 0 2.62l1.45 1.67a6 6 0 0 1 1.45 4.46l-.2 2.2a2 2 0 0 0 1.55 2.13l2.15.5a6 6 0 0 1 3.8 2.75l1.13 1.9a2 2 0 0 0 2.5.8zm2.82.97a4 4 0 0 1 3.12 0l2.04.87a4 4 0 0 0 4.99-1.62l1.14-1.9a4 4 0 0 1 2.53-1.84l2.15-.5a4 4 0 0 0 3.09-4.24l-.2-2.2a4 4 0 0 1 .97-2.98l1.45-1.67a4 4 0 0 0 0-5.24l-1.45-1.67a4 4 0 0 1-.97-2.97l.2-2.2a4 4 0 0 0-3.09-4.25l-2.15-.5a4 4 0 0 1-2.53-1.84l-1.14-1.9a4 4 0 0 0-5-1.62l-2.03.87a4 4 0 0 1-3.12 0l-2.04-.87a4 4 0 0 0-4.99 1.62l-1.14 1.9a4 4 0 0 1-2.53 1.84l-2.15.5a4 4 0 0 0-3.09 4.24l.2 2.2a4 4 0 0 1-.97 2.98l-1.45 1.67a4 4 0 0 0 0 5.24l1.45 1.67a4 4 0 0 1 .97 2.97l-.2 2.2a4 4 0 0 0 3.09 4.25l2.15.5a4 4 0 0 1 2.53 1.84l1.14 1.9a4 4 0 0 0 5 1.62l2.03-.87zM152 207a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm6 2a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm-11 1a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm-6 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm3-5a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm-8 8a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm3 6a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm0 6a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm4 7a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm5-2a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm5 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm4-6a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm6-4a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm-4-3a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm4-3a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm-5-4a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm-24 6a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm16 5a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm7-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0zm86-29a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm19 9a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm-14 5a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm-25 1a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm5 4a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm9 0a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm15 1a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm12-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm-11-14a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm-19 0a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm6 5a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm-25 15c0-.47.01-.94.03-1.4a5 5 0 0 1-1.7-8 3.99 3.99 0 0 1 1.88-5.18 5 5 0 0 1 3.4-6.22 3 3 0 0 1 1.46-1.05 5 5 0 0 1 7.76-3.27A30.86 30.86 0 0 1 246 184c6.79 0 13.06 2.18 18.17 5.88a5 5 0 0 1 7.76 3.27 3 3 0 0 1 1.47 1.05 5 5 0 0 1 3.4 6.22 4 4 0 0 1 1.87 5.18 4.98 4.98 0 0 1-1.7 8c.02.46.03.93.03 1.4v1h-62v-1zm.83-7.17a30.9 30.9 0 0 0-.62 3.57 3 3 0 0 1-.61-4.2c.37.28.78.49 1.23.63zm1.49-4.61c-.36.87-.68 1.76-.96 2.68a2 2 0 0 1-.21-3.71c.33.4.73.75 1.17 1.03zm2.32-4.54c-.54.86-1.03 1.76-1.49 2.68a3 3 0 0 1-.07-4.67 3 3 0 0 0 1.56 1.99zm1.14-1.7c.35-.5.72-.98 1.1-1.46a1 1 0 1 0-1.1 1.45zm5.34-5.77c-1.03.86-2 1.79-2.9 2.77a3 3 0 0 0-1.11-.77 3 3 0 0 1 4-2zm42.66 2.77c-.9-.98-1.87-1.9-2.9-2.77a3 3 0 0 1 4.01 2 3 3 0 0 0-1.1.77zm1.34 1.54c.38.48.75.96 1.1 1.45a1 1 0 1 0-1.1-1.45zm3.73 5.84c-.46-.92-.95-1.82-1.5-2.68a3 3 0 0 0 1.57-1.99 3 3 0 0 1-.07 4.67zm1.8 4.53c-.29-.9-.6-1.8-.97-2.67.44-.28.84-.63 1.17-1.03a2 2 0 0 1-.2 3.7zm1.14 5.51c-.14-1.21-.35-2.4-.62-3.57.45-.14.86-.35 1.23-.63a2.99 2.99 0 0 1-.6 4.2zM275 214a29 29 0 0 0-57.97 0h57.96zM72.33 198.12c-.21-.32-.34-.7-.34-1.12v-12h-2v12a4.01 4.01 0 0 0 7.09 2.54c.57-.69.91-1.57.91-2.54v-12h-2v12a1.99 1.99 0 0 1-2 2 2 2 0 0 1-1.66-.88zM75 176c.38 0 .74-.04 1.1-.12a4 4 0 0 0 6.19 2.4A13.94 13.94 0 0 1 84 185v24a6 6 0 0 1-6 6h-3v9a5 5 0 1 1-10 0v-9h-3a6 6 0 0 1-6-6v-24a14 14 0 0 1 14-14 5 5 0 0 0 5 5zm-17 15v12a1.99 1.99 0 0 0 1.22 1.84 2 2 0 0 0 2.44-.72c.21-.32.34-.7.34-1.12v-12h2v12a3.98 3.98 0 0 1-5.35 3.77 3.98 3.98 0 0 1-.65-.3V209a4 4 0 0 0 4 4h16a4 4 0 0 0 4-4v-24c.01-1.53-.23-2.88-.72-4.17-.43.1-.87.16-1.28.17a6 6 0 0 1-5.2-3 7 7 0 0 1-6.47-4.88A12 12 0 0 0 58 185v6zm9 24v9a3 3 0 1 0 6 0v-9h-6z'/%3E%3Cpath d='M-17 191a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm19 9a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2H3a1 1 0 0 1-1-1zm-14 5a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm-25 1a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm5 4a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm9 0a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm15 1a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm12-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2H4zm-11-14a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm-19 0a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2h-2zm6 5a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm-25 15c0-.47.01-.94.03-1.4a5 5 0 0 1-1.7-8 3.99 3.99 0 0 1 1.88-5.18 5 5 0 0 1 3.4-6.22 3 3 0 0 1 1.46-1.05 5 5 0 0 1 7.76-3.27A30.86 30.86 0 0 1-14 184c6.79 0 13.06 2.18 18.17 5.88a5 5 0 0 1 7.76 3.27 3 3 0 0 1 1.47 1.05 5 5 0 0 1 3.4 6.22 4 4 0 0 1 1.87 5.18 4.98 4.98 0 0 1-1.7 8c.02.46.03.93.03 1.4v1h-62v-1zm.83-7.17a30.9 30.9 0 0 0-.62 3.57 3 3 0 0 1-.61-4.2c.37.28.78.49 1.23.63zm1.49-4.61c-.36.87-.68 1.76-.96 2.68a2 2 0 0 1-.21-3.71c.33.4.73.75 1.17 1.03zm2.32-4.54c-.54.86-1.03 1.76-1.49 2.68a3 3 0 0 1-.07-4.67 3 3 0 0 0 1.56 1.99zm1.14-1.7c.35-.5.72-.98 1.1-1.46a1 1 0 1 0-1.1 1.45zm5.34-5.77c-1.03.86-2 1.79-2.9 2.77a3 3 0 0 0-1.11-.77 3 3 0 0 1 4-2zm42.66 2.77c-.9-.98-1.87-1.9-2.9-2.77a3 3 0 0 1 4.01 2 3 3 0 0 0-1.1.77zm1.34 1.54c.38.48.75.96 1.1 1.45a1 1 0 1 0-1.1-1.45zm3.73 5.84c-.46-.92-.95-1.82-1.5-2.68a3 3 0 0 0 1.57-1.99 3 3 0 0 1-.07 4.67zm1.8 4.53c-.29-.9-.6-1.8-.97-2.67.44-.28.84-.63 1.17-1.03a2 2 0 0 1-.2 3.7zm1.14 5.51c-.14-1.21-.35-2.4-.62-3.57.45-.14.86-.35 1.23-.63a2.99 2.99 0 0 1-.6 4.2zM15 214a29 29 0 0 0-57.97 0h57.96z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('docs'))) {?>

        #myImg {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    }

    #myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (Image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image (Image Text) - Same Width as the Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation - Zoom in the Modal */
.modal-content, #caption {
  animation-name: zoom;
  animation-duration: 0.6s;
}
@keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
    <?php }?>
    <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('attendance_sheet'))) {?>
    .page-BgGray {
    background: #fff;
    }
    .page-TimeShifts {
        padding: 10px 0;
    }
    .TShifts__table {
        width: 100%;
    }
    .TShifts__table thead th.Month {
        width: 120px !important;
        display: inline-block;
    }
    .TShifts__table thead th {
        background: #19b182;
        padding: 10px 0;
        text-align: center;
        color: #fff;
        width: 4%;
    }
    .TShifts__table tbody tr td:first-child {
        width: 120px !important;
        padding: 10px 0;
        text-align: center;
        font-size: large;
        font-weight: bold;
    }
    .TShifts__table tbody tr td {
        min-width:40px;
        border-left: 1px solid #bec6c8;
        border-bottom: 1px solid #bec6c8;
        max-width: 4%;
        cursor: pointer;
    } 
    
    .workHours {
        background: #006DCB;
    }
    .vaction {
        background: #FFDC00;
    }
    .breakHours {
        background: greenyellow;
    }
    .weekend {
        background:#dfe3e4;
    }
    .other {
        background: #000;
    }
    .sick {
        background: red;
    }
    .maternity {
        background:#a31aaf;
    }

    <?php }?>

    <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('payslip','invoice'))) {?>
    
    #background{
        position:relative;
        z-index:0;
        background:white;
        display:block;
        min-height:50%; 
        min-width:50%;
        color:yellow;
    }

    #bg-text
    {
        color:lightgrey;
        font-size:50px;
        transform:rotate(300deg);
        -webkit-transform:rotate(300deg);
    }

    .invoice-box {
        background-color: white;
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2), .invoice-box table tr.total td:nth-child(4) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    <?php }?>

    .max-auto{
        width: 45px!important;
        max-width: 75px!important;
    }

    .clock {
    color: #4268d6;
    font-size: 10px;
    font-family:Geneva;
    letter-spacing: 2px;

   }
    .card.gradient-card .card-image .mask {
        -webkit-border-radius: .25rem;
        border-radius: .25rem;
    }
    .peach-gradient-rgba {
        background: linear-gradient(40deg,rgba(255,216,111,0.9),rgba(252,98,98,0.9)) !important;
    }
    .aqua-gradient-rgba {
    background: linear-gradient(40deg,rgba(32,150,255,0.9),rgba(5,255,163,0.9)) !important;
    }
    .purple-gradient-rgba {
    background: linear-gradient(40deg,rgba(255,110,196,0.9),rgba(120,115,245,0.9)) !important;
    }
    .blue-gradient-rgba {
    background: linear-gradient(40deg,rgba(69,202,252,0.9),rgba(48,63,159,0.9)) !important;
    }
    .warm-flame-gradient {
    background-image: linear-gradient(45deg,#ff9a9e 0,#fad0c4 99%,#fad0c4 100%);
    }
    .night-fade-gradient {
    background-image: linear-gradient(to top,#a18cd1 0,#fbc2eb 100%);
    }
    .rainy-ashville-gradient {
    background-image: linear-gradient(to top,#fbc2eb 0,#a6c1ee 100%);
    }
    .sunny-morning-gradient {
    background-image: linear-gradient(120deg,#f6d365 0,#fda085 100%);
    }
    .lady-lips-gradient {
    background-image: linear-gradient(to top,#ff9a9e 0,#fecfef 99%,#fecfef 100%);
    }
    .memo{
        background-color: honeydew;
        padding: 1px;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }
    .memo_client{
        
    }
    .memo_client > div {
    background-color: #f5f5f5;
    width: 100%;
    margin: 0 auto;
    padding: 0;
    max-height: 10em;
    overflow-y: auto;
    }
    .memo_client h4 {
    color: #cd0000;
    font-size: 42px;
    letter-spacing: -2px;
    text-align: left;
    }
    .memo_client .list {
    color: #555;
    font-size: 22px;
    padding: 0 !important;
    width: 500px;
    font-family: courier, monospace;
    border: 1px solid #dedede;
    }

    .memo_client .list li {
    list-style: none;
    border-bottom: 1px dotted #ccc;
    text-indent: 25px;
    height: auto;
    padding: 10px;
    text-transform: capitalize;
    }

    .memo_client .list li:hover {
    background-color: #f0f0f0;
    -webkit-transition: all 0.2s;
    -moz-transition:    all 0.2s;
    -ms-transition:     all 0.2s;
    -o-transition:      all 0.2s;
    }
    .select2-container{
        width: 75% !important;
    }

    /* Accessibility CSS */

        /* All */
        .showall{
            display: block!important;
        }
        /* All */
        .showOne{
            <?php if ((!in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level0')))) {?>
                display: none!important;
            <?php } else { ?>
            <?php }?>
        }
        /* Management */
        .management{
            <?php if ((in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level0','level1','level2')))) {?>
                display: block!important;
            <?php } else { ?>
            <?php }?>
        }

        .management-s{
            <?php if ((in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level0','level1','level2','level3')))) {?>
                display: block!important;
            <?php } else { ?>
            <?php }?>
        }

        .management-only{
            <?php if ((!in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level0','level1','level2')))) {?>
                display: none!important;
            <?php } else { ?>
            <?php }?>
        }
        /* End Management */

        /* Administrative */
        .admin-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Administrative'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .admin-m-only{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Administrative'))) {?>
            <?php } else { ?>
                display: none!important;
            <?php }?>
        }
        .admin-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Administrative'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .admin{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Administrative'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        /* End Administrative */
        
        /* Accounting */
        .account-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Accounting'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .account-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Accounting'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .account{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Accounting'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .account-only{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Accounting')) {?>
            <?php } else { ?>
                display: none!important;
            <?php }?>
        }
        /* End Accounting */

        /* Human Resources */
        .humanr-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Human Resources'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .humanr-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Human Resources'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        
        .humanr-m-only{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Human Resources'))) {?>
            <?php } else { ?>
                display: none!important;
            <?php }?>
        }
        .humanr-only{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Human Resources')) {?>
            <?php } else { ?>
                display: none!important;
            <?php }?>
        }
        .humanr{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Human Resources'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        /* End Human Resources */

        /* Marketing */
        .market-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Marketing'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .market-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Marketing'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .market{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Marketing'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .market-only{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Marketing')) {?>
            <?php } else { ?>
                display: none!important;
            <?php }?>
        }
        /* End Marketing */

        /* Facility */
        .facility-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Facility'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .facility-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Facility'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .facility{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Facility'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .facility-only{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Facility')) {?>
            <?php } else { ?>
                display: none!important;
            <?php }?>
        }
        /* End Facility */

        /* Corporate Office */
        .corporate-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Corporate Office'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .corporate-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Corporate Office'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .corporate{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Corporate Office'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        /* End Corporate Office */

        /* Information Technology */
        .information-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Information Technology'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .information-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Information Technology'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .information{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Information Technology'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        /* End Information Technology */

        /* Legal */
        .legal-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Legal'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .legal-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Legal'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .legal{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Legal'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        /* End Legal */

        /* Customer */
        .customer-m-s{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Customer Service'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .customer-m{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Customer Service'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .customer{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level1') || (in_array($_smarty_tpl->tpl_vars['userinfo']->value->userrole,array('level1','level2','level3','level4')) && ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Customer Service'))) {?>
            display: block!important;
            <?php } else { ?>
            <?php }?>
        }
        .customer-only{
            <?php if (($_smarty_tpl->tpl_vars['userinfo']->value->userrole == 'level0') || ($_smarty_tpl->tpl_vars['userinfo']->value->department == 'Customer Service')) {?>
            <?php } else { ?>
                display: none!important;
            <?php }?>
        }
        /* End Customer */

        .space{
            margin-bottom: 30px!important;
        }

        .triangle-bottomleft {
            z-index: 0 ;
            width: 0;
            height: 0;
            border-bottom: 200px solid red;
            border-right: 800px solid transparent;
        }
        .triangle-bottomright {
            z-index: 9;
            float: right;
            width: 0;
            border-bottom: 200px solid royalblue;
            border-left: 220px solid transparent;
        }
        .large{
            font-size: 3em;
        }

        .subletter{
            color: #000;
            font-size: 1.5em;
        }
        .address{
            color: #000;
            font-size: 1.2em;
        }
        #financetbl input,select{
            min-width: 100px;
        }

        #financetbl input[type=date]{
            min-width: 100px;
        }
        .sold_out {
            top: 2em;
            left: -4em;
            color: #fff;
            display: block;
            position:absolute;
            text-align: center;
            text-decoration: none;
            letter-spacing: .06em;
            background-color: #A00;
            padding: 0.5em 5em 0.4em 5em;
            text-shadow: 0 0 0.75em #444;
            box-shadow: 0 0 0.5em rgba(0,0,0,0.5);
            font: bold 16px/1.2em Arial, Sans-Serif;
            -webkit-text-shadow: 0 0 0.75em #444;
            -webkit-box-shadow: 0 0 0.5em rgba(0,0,0,0.5);
            -webkit-transform: rotate(-45deg) scale(0.75,1);
            z-index:10;
        }
        .sold_out:before {
            content: '';
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: absolute;
            margin: -0.3em -5em;
            transform: scale(0.7);
            -webkit-transform: scale(0.7);
            border: 2px rgba(255,255,255,0.7) dashed;
        }
        .flicker {
        animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
        .waitingForConnection {
        -webkit-animation-name: blinker;
        -webkit-animation-iteration-count: infinite;
        -webkit-animation-timing-function: cubic-bezier(.5, 0, 1, 1);
        -webkit-animation-duration: 1.7s;
        }
        .subtive{
            background-color: #e0e0e0;
        }

    .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: rgb(107, 107, 107);
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;

    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    }

    .toooltip:hover .tooltiptext {
        visibility: visible;
    }
    .plot{
        border-color: #19b182;
        border-width: 1px;
        border-style: solid;
        font-size: 10px!important;
        min-width: 5em;
        min-height: 3em;
    }
    .plot-holder{
        max-height: 690px;
        overflow-y: auto;
    }

    <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('tree','full_tree'))) {?>
    .tree,
.tree ul,
.tree li {
    list-style: none;
    margin: 0;
    padding: 0;
    position: relative;
}

.tree {
    margin: 0 0 1em;
    text-align: center;
}

.tree,
.tree ul {
    display: table;
}

.tree ul {
    width: 100%;
}

.tree li {
    display: table-cell;
    padding: .7em 0;
    vertical-align: top;
}

.tree li:before {
    outline: solid 1px #666;
    content: "";
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}

.tree li:first-child:before {
    left: 50%;
}

.tree li:last-child:before {
    right: 50%;
}

.tree code,
.tree span {
    font-size: 15px;
    border: solid .1em #666;
    border-radius: .2em;
    display: inline-block;
    margin: 0 .3em .7em;
    padding: .3em .5em;
    position: relative;
}

.tree ul:before,
.tree code:before,
.tree span:before {
    outline: solid 1px #666;
    content: "";
    height: .5em;
    left: 50%;
    position: absolute;
}

.tree ul:before {
    top: -.7em;
}

.tree code:before,
.tree span:before {
    top: -.55em;
}

.tree>li {
    margin-top: 0;
}

.tree>li:before,
.tree>li:after,
.tree>li>code:before,
.tree>li>span:before {
    outline: none;
}

        .tree li span:hover,
        .tree li span:hover+ul li span {
            background: #000 !important;
        color: #fff;
        border: $border-width solid #e9453f;
        }

        .tree li span:hover + ul li::after, 
        .tree li span:hover + ul li::before, 
        .tree li span:hover + ul::before, 
        .tree li span:hover + ul ul::before{
            border-color:  #000 !important;
        }
    
    <?php }?>

    <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('plot_view'))) {?>
    .table tbody tr:hover {
    background-color: rgb(124, 124, 124);
    color: #fff;
    }
    <?php }?>
  </style><?php }
}
