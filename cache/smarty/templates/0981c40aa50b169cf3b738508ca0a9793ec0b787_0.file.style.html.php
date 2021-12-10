<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:47:20
  from 'C:\wamp64\www\organization-management\site\templates\base\style.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e7082b8e82_63394654',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0981c40aa50b169cf3b738508ca0a9793ec0b787' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\base\\style.html',
      1 => 1635413734,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_61b3e7082b8e82_63394654 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('signup','login','admin'))) {?>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/login/css/main.css">
<!--===============================================================================================-->
<?php }?> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="lib/common/css-new/animate.css">
    <link rel="stylesheet" href="lib/common/css-new/owl.carousel.min.css">
    <link rel="stylesheet" href="lib/common/css-new/owl.theme.default.min.css">
    <link rel="stylesheet" href="lib/common/css-new/magnific-popup.css">
    
    <link rel="stylesheet" href="lib/common/css-new/flaticon.css">
    <link rel="stylesheet" href="lib/common/css-new/style.css">
    	<style>
          #myVideo {
          position: absolute;
          right: 0;
          bottom: 0;
          min-width: 100%;
          min-height: 100%;
          
      }
	</style>
<style id="pagelayer-global-styles" type="text/css">

#content img {
max-width: 100%;
    margin-bottom: -9px height:auto;
    border-bottom: 6px solid #1fa762;
    vertical-align: middle;
}

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('events'))) {?>
    /*!
 * FullCalendar v1.6.4 Stylesheet
 * Docs & License: http://arshaw.com/fullcalendar/
 * (c) 2013 Adam Shaw
 */
 @import url('https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i');
td.fc-day {

background:#FFF !important;
font-family: 'Roboto', sans-serif;

}
td.fc-today {
	background:#FFF !important;
	position: relative;


}

.fc-first th{
	font-family: 'Roboto', sans-serif;
    background:#9675ce !important;
	color:#FFF;
	font-size:14px !important;
	font-weight:500 !important;

	}
.fc-event-inner {
	font-family: 'Roboto', sans-serif;
    background-color: #006DCB;
    color: #FFF!important;
    font-size: 12px!important;
    font-weight: 500!important;
    padding: 5px 0px!important;
}

.fc {
	direction: ltr;
	text-align: left;
	}
	
.fc table {
	border-collapse: collapse;
	border-spacing: 0;
	}
	
html .fc,
.fc table {
	font-size: 1em;
	font-family: "Helvetica Neue",Helvetica;

	}
	
.fc td,
.fc th {
	padding: 0;
	vertical-align: top;
	}



/* Header
------------------------------------------------------------------------*/

.fc-header td {
	white-space: nowrap;
	padding: 15px 10px 0px;
}

.fc-header-left {
	width: 25%;
	text-align: left;
}
	
.fc-header-center {
	text-align: center;
	}
	
.fc-header-right {
	width: 25%;
	text-align: right;
	}
	
.fc-header-title {
	display: inline-block;
	vertical-align: top;
	margin-top: -5px;
}
	
.fc-header-title h2 {
  color: #1a1e25;
	margin-top: 0;
	white-space: nowrap;
	font-size: 32px;
    font-weight: 100;
    margin-bottom: 10px;
		font-family: 'Roboto', sans-serif;
}
	span.fc-button {
    font-family: 'Roboto', sans-serif;
    border-color: #9675ce;
	color: #9675ce;
}
.fc-state-down, .fc-state-active {
    background-color: #9675ce !important;
	color: #FFF !important;
}
.fc .fc-header-space {
	padding-left: 10px;
	}
	
.fc-header .fc-button {
	margin-bottom: 1em;
	vertical-align: top;
	}
	
/* buttons edges butting together */

.fc-header .fc-button {
	margin-right: -1px;
	}
	
.fc-header .fc-corner-right,  /* non-theme */
.fc-header .ui-corner-right { /* theme */
	margin-right: 0; /* back to normal */
	}
	
/* button layering (for border precedence) */
	
.fc-header .fc-state-hover,
.fc-header .ui-state-hover {
	z-index: 2;
	}
	
.fc-header .fc-state-down {
	z-index: 3;
	}

.fc-header .fc-state-active,
.fc-header .ui-state-active {
	z-index: 4;
	}
	
	
	
/* Content
------------------------------------------------------------------------*/
	
.fc-content {
	clear: both;
	zoom: 1; /* for IE7, gives accurate coordinates for [un]freezeContentHeight */
	}
	
.fc-view {
	width: 100%;
	overflow: hidden;
	}
	
	

/* Cell Styles
------------------------------------------------------------------------*/

    /* <th>, usually */
.fc-widget-content { 
	border: 1px solid #e5e5e5;
	}
.fc-widget-header{
    border-bottom: 1px solid #EEE; 
}	
.fc-state-highlight { /* <td> today cell */ /* TODO: add .fc-today to <th> */
	/* background: #fcf8e3; */
}

.fc-state-highlight > div > div.fc-day-number{
    background-color: #ff3b30;
    color: #FFFFFF;
    border-radius: 50%;
    margin: 4px;
}
	
.fc-cell-overlay { /* semi-transparent rectangle while dragging */
	background: #bce8f1;
	opacity: .3;
	filter: alpha(opacity=30); 
}
	

.fc-button {
	position: relative;
	display: inline-block;
	padding: 0 .6em;
	overflow: hidden;
	height: 1.9em;
	line-height: 1.9em;
	white-space: nowrap;
	cursor: pointer;
	}
	
.fc-state-default { /* non-theme */
	border: 1px solid;
	}

.fc-state-default.fc-corner-left { /* non-theme */
	border-top-left-radius: 4px;
	border-bottom-left-radius: 4px;
	}

.fc-state-default.fc-corner-right { /* non-theme */
	border-top-right-radius: 4px;
	border-bottom-right-radius: 4px;
	}

.fc-text-arrow {
	margin: 0 .4em;
	font-size: 2em;
	line-height: 23px;
	vertical-align: baseline; /* for IE7 */
	}

.fc-button-prev .fc-text-arrow,
.fc-button-next .fc-text-arrow { /* for ‹ › */
	font-weight: bold;
	}
	
/* icon (for jquery ui) */
	
.fc-button .fc-icon-wrap {
	position: relative;
	float: left;
	top: 50%;
	}
	
.fc-button .ui-icon {
	position: relative;
	float: left;
	margin-top: -50%;
	
	*margin-top: 0;
	*top: -50%;
	}


.fc-state-default {
	border-color: #ff3b30;
	color: #ff3b30;	
}
.fc-button-month.fc-state-default, .fc-button-agendaWeek.fc-state-default, .fc-button-agendaDay.fc-state-default{
    min-width: 67px;
	text-align: center;
	transition: all 0.2s;
	-webkit-transition: all 0.2s;
}
.fc-state-hover,
.fc-state-down,
.fc-state-active,
.fc-state-disabled {
	color: #333333;
	background-color: #FFE3E3;
	}

.fc-state-hover {
	color: #ff3b30;
	text-decoration: none;
	background-position: 0 -15px;
	-webkit-transition: background-position 0.1s linear;
	   -moz-transition: background-position 0.1s linear;
	     -o-transition: background-position 0.1s linear;
	        transition: background-position 0.1s linear;
	}

.fc-state-down,
.fc-state-active {
	background-color: #ff3b30;
	background-image: none;
	outline: 0;
	color: #FFFFFF;
}

.fc-state-disabled {
	cursor: default;
	background-image: none;
	background-color: #FFE3E3;
	filter: alpha(opacity=65);
	box-shadow: none;
	border:1px solid #FFE3E3;
	color: #ff3b30;
	}

	

/* Global Event Styles
------------------------------------------------------------------------*/

.fc-event-container > * {
	z-index: 8;
	}

.fc-event-container > .ui-draggable-dragging,
.fc-event-container > .ui-resizable-resizing {
	z-index: 9;
	}
	 
.fc-event {
	border: 1px solid #FFF; /* default BORDER color */
	background-color: #FFF; /* default BACKGROUND color */
	color: #919191;               /* default TEXT color */
	font-size: 12px;
	cursor: default;
}
.fc-event.chill{
    background-color: #df7ff5 !important;
}
.fc-event.info{
    background-color: #00aaff !important;
}
.fc-event.important{
    background-color: #fc6767 !important;
}
.fc-event.success{
    background-color: #6cfd6f !important;
}
.fc-event:hover{
    opacity: 0.7;
}
a.fc-event {
	text-decoration: none;
	}
	
a.fc-event,
.fc-event-draggable {
	cursor: pointer;
	}
	
.fc-rtl .fc-event {
	text-align: right;
	}

.fc-event-inner {
	width: 100%;
	height: 100%;
	overflow: hidden;
	line-height: 15px;
	}
	
.fc-event-time,
.fc-event-title {
	padding: 0 1px;
	}
	
.fc .ui-resizable-handle {
	display: block;
	position: absolute;
	z-index: 99999;
	overflow: hidden; /* hacky spaces (IE6/7) */
	font-size: 300%;  /* */
	line-height: 50%; /* */
	}
	
	
	
/* Horizontal Events
------------------------------------------------------------------------*/

.fc-event-hori {
	border-width: 1px 0;
	margin-bottom: 1px;
	}

.fc-ltr .fc-event-hori.fc-event-start,
.fc-rtl .fc-event-hori.fc-event-end {
	border-left-width: 1px;
	/*
border-top-left-radius: 3px;
	border-bottom-left-radius: 3px;
*/
	}

.fc-ltr .fc-event-hori.fc-event-end,
.fc-rtl .fc-event-hori.fc-event-start {
	border-right-width: 1px;
	}
	
	
.fc-event-hori .ui-resizable-e {
	top: 0           !important; 
	right: -3px      !important;
	width: 7px       !important;
	height: 100%     !important;
	cursor: e-resize;
	}
	
.fc-event-hori .ui-resizable-w {
	top: 0           !important;
	left: -3px       !important;
	width: 7px       !important;
	height: 100%     !important;
	cursor: w-resize;
	}
	
.fc-event-hori .ui-resizable-handle {
	_padding-bottom: 14px; 
	}
	
	
	
table.fc-border-separate {
	border-collapse: separate;
	}
	
.fc-border-separate th,
.fc-border-separate td {
	border-width: 1px 0 0 1px;
	}
	
.fc-border-separate th.fc-last,
.fc-border-separate td.fc-last {
	border-right-width: 1px;
	}
	

.fc-border-separate tr.fc-last td {
	
}
.fc-border-separate .fc-week .fc-first{
    border-left: 0;
}
.fc-border-separate .fc-week .fc-last{
    border-right: 0;
}
.fc-border-separate tr.fc-last th{
    border-bottom-width: 1px;
    border-color: #cdcdcd;
    font-size: 16px;
    font-weight: 300;
	line-height: 30px;
}
.fc-border-separate tbody tr.fc-first td,
.fc-border-separate tbody tr.fc-first th {
	border-top-width: 0;
	}
	

.fc-grid th {
	text-align: center;
	}

.fc .fc-week-number {
	width: 22px;
	text-align: center;
	}

.fc .fc-week-number div {
	padding: 0 2px;
	}
	
.fc-grid .fc-day-number {
	float: right;
	padding: 0 2px;
	}
	
.fc-grid .fc-other-month .fc-day-number {
	opacity: 0.3;
	filter: alpha(opacity=30); 
	}
	
.fc-grid .fc-day-content {
	clear: both;
	padding: 2px 2px 1px;
	}
	
/* event styles */
	
.fc-grid .fc-event-time {
	font-weight: bold;
	}
	
/* right-to-left */
	
.fc-rtl .fc-grid .fc-day-number {
	float: left;
	}
	
.fc-rtl .fc-grid .fc-event-time {
	float: right;
	}
	
	

/* Agenda Week View, Agenda Day View
------------------------------------------------------------------------*/

.fc-agenda table {
	border-collapse: separate;
	}
	
.fc-agenda-days th {
	text-align: center;
	}
	
.fc-agenda .fc-agenda-axis {
    color: #FFF;
	width: 50px;
	padding: 0 4px;
	vertical-align: middle;
	text-align: right;
	white-space: nowrap;
	font-weight: normal;
	}

.fc-agenda .fc-week-number {
	font-weight: bold;
	}
	
.fc-agenda .fc-day-content {
	padding: 2px 2px 1px;
	}
	
/* make axis border take precedence */
	
.fc-agenda-days .fc-agenda-axis {
	border-right-width: 1px;
	}
	
.fc-agenda-days .fc-col0 {
	border-left-width: 0;
	}
	
/* all-day area */
	
.fc-agenda-allday th {
	border-width: 0 1px;
	}
	
.fc-agenda-allday .fc-day-content {
	min-height: 34px; /* TODO: doesnt work well in quirksmode */
	_height: 34px;
	}
	
/* divider (between all-day and slots) */
	
.fc-agenda-divider-inner {
	height: 2px;
	overflow: hidden;
	}
	
.fc-widget-header .fc-agenda-divider-inner {
	background: #eee;
	}
	
/* slot rows */
	
.fc-agenda-slots th {
	border-width: 1px 1px 0;
	}
	
.fc-agenda-slots td {
	border-width: 1px 0 0;
	background: none;
	}
	
.fc-agenda-slots td div {
	height: 20px;
	}
	
.fc-agenda-slots tr.fc-slot0 th,
.fc-agenda-slots tr.fc-slot0 td {
	border-top-width: 0;
	}
	
.fc-agenda-slots tr.fc-minor th.ui-widget-header {
	*border-top-style: solid; 
	}
	


.fc-event-vert {
	border-width: 0 1px;
	}

.fc-event-vert.fc-event-start {
	border-top-width: 1px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	}

.fc-event-vert.fc-event-end {
	border-bottom-width: 1px;
	border-bottom-left-radius: 3px;
	border-bottom-right-radius: 3px;
	}
	
.fc-event-vert .fc-event-time {
	white-space: nowrap;
	font-size: 10px;
	}

.fc-event-vert .fc-event-inner {
	position: relative;
	z-index: 2;
	}
	
.fc-event-vert .fc-event-bg { /* makes the event lighter w/ a semi-transparent overlay  */
	position: absolute;
	z-index: 1;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: #fff;
	opacity: .25;
	filter: alpha(opacity=25);
	}
	
.fc .ui-draggable-dragging .fc-event-bg, /* TODO: something nicer like .fc-opacity */
.fc-select-helper .fc-event-bg {
	display: none; 
	}
	
.fc-event-vert .ui-resizable-s {
	bottom: 0        !important; /* importants override pre jquery ui 1.7 styles */
	width: 100%      !important;
	height: 8px      !important;
	overflow: hidden !important;
	line-height: 8px !important;
	font-size: 11px  !important;
	font-family: monospace;
	text-align: center;
	cursor: s-resize;
	}
	
.fc-agenda .ui-resizable-resizing { /* TODO: better selector */
	_overflow: hidden;
	}
	
thead tr.fc-first{
    background-color: #f7f7f7;
}
table.fc-header{
    background-color: #FFFFFF;
    border-radius: 6px 6px 0 0;
}

.fc-week .fc-day > div .fc-day-number{
    font-size: 15px;
    margin: 2px;
    min-width: 19px;
    padding: 6px;
    text-align: center;
       width: 30px;
    height: 30px;
}
.fc-sun, .fc-sat{
    color: #b8b8b8;
}

.fc-week .fc-day:hover .fc-day-number{
    background-color: #B8B8B8;
    border-radius: 50%;
    color: #FFFFFF;
    transition: background-color 0.2s;
}
.fc-week .fc-day.fc-state-highlight:hover .fc-day-number{
    background-color:  #ff3b30;
}
.fc-button-today{
    border: 1px solid rgba(255,255,255,.0);
}
.fc-view-agendaDay thead tr.fc-first .fc-widget-header{
    text-align: right;
    padding-right: 10px;
}

.fc-event {
	background: #fff !important;
	color: #000 !important;
	}
	
.fc-event-bg {
	display: none !important;
	}
	
.fc-event .ui-resizable-handle {
	display: none !important;
	}
	
	#wrap {
		width: 1100px;
		margin: 0 auto;
		}
		
	#external-events {
		float: left;
		width: 150px;
		padding: 0 10px;
		text-align: left;
		}
		
	#external-events h4 {
		font-size: 16px;
		margin-top: 0;
		padding-top: 1em;
		}
		
	.external-event { /* try to mimick the look of a real event */
		margin: 10px 0;
		padding: 2px 4px;
		background: #3366CC;
		color: #fff;
		font-size: .85em;
		cursor: pointer;
		}
		
	#external-events p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
		}
		
	#external-events p input {
		margin: 0;
		vertical-align: middle;
		}

	#calendar {
        margin: 0 auto;
		width: 900px;
		background-color: #FFFFFF;
		  border-radius: 6px;
        box-shadow: 0 1px 2px #C3C3C3;
                -webkit-box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
        -moz-box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
        box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
		}


    <?php }?>    

/*chat styles*/


.chat-careButton{
	border-radius: 50px;
    position: fixed;
    z-index: 99999999;
    bottom: 100px;
    right: 16px;
    padding: 16px;
    margin-bottom: 0;
    text-align: center;
    vertical-align: middle;
    color: #fff !important;
    background-color: #24A148 !important;
    cursor: pointer;
}
.chatApp{
    z-index: 99999999;
    position: fixed;
    bottom: 55px;
    right: 16px;
    border-color: #24A148;
    background-color: #ffffff !important;
}
.chatHeader{
  background-color: #24A148;
	padding: 15px;
  display: flex;
}
.chatTitle{
	font-weight: 400;
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  font-size: 1.5rem;
	margin-right: 15px;
  white-space: pre-wrap;
  color: #fff;
}
.chatApp .chatClose{
	  background: #fff;
    padding: 3px 6px;
    border-radius: 50pc 50pc 50pc 50pc;
    cursor: pointer;
}
.chatBody{
    border: 1px solid #e4e4e4;
    border-radius: 3px;
    padding: 16px 15px 0;
}
.chatFooter{
  text-align: center;
}
.chatFooter button{
  min-width: 100px;
  margin: 10px;
}
.chatApp .chatListing{
    overflow-y: auto;
    max-height: 230px;
    padding: 1rem;
}
.chatListMsg{
    padding: 0.5rem;
}
.chatApp .meChat{
  /*
    color: #000000;
  */
    background-color: #c0c0c0;
  
  text-align: right;
    border-radius: 1px 10px 10px 10px;
    margin-bottom: 1rem;
}
.chatApp .youChat{
    /*color: #c0c0c0;
    */
    text-align: left;
    background-color: #f3f3f3;
    
    border-radius: 10px 1px 10px 10px;
    margin-bottom: 1rem;
}
</style>
<style type="text/css">
      .overflow-hidden{
        overflow: hidden !important;
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
        
	<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('gallery'))) {?>
			
			.caption-1 figcaption {
				position: absolute;
				bottom: 0;
				right: 0;
			}
			.imageSource {
				border-radius: 5px;
				cursor: pointer;
				transition: 0.3s;
			}

			.imageSource:hover {opacity: 0.7;}

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
</style><?php }
}
