<?php
/* Smarty version 3.1.39, created on 2021-12-11 00:47:19
  from 'C:\wamp64\www\organization-management\site\templates\root\script.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_61b3e7075f03c2_79826015',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9888254b4abc72ce58cf8ec33ae448519db397e5' => 
    array (
      0 => 'C:\\wamp64\\www\\organization-management\\site\\templates\\root\\script.html',
      1 => 1639176219,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:style.html' => 1,
  ),
),false)) {
function content_61b3e7075f03c2_79826015 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\wamp64\\www\\organization-management\\lib\\Smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
 <!-- Bootstrap core JavaScript-->
 <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/vendor/jquery/jquery.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.5.0/d3.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/js/html2canvas.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"><?php echo '</script'; ?>
>
			
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/vendor/bootstrap/js/bootstrap.bundle.min.js"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"><?php echo '</script'; ?>
> 

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('blog-new','blog','project-new','project','news-new','newsletters','news-info','event-new','event','docupload-new','docupload','companydocs-new','companydoc','management_report','add_management_report','investment_webpage'))) {?>
  <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common/quilljs/quill.min.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="https://cdn.rawgit.com/kensnyder/quill-image-resize-module/3411c9a7/image-resize.min.js"><?php echo '</script'; ?>
>
<?php }?>


<!-- Core plugin JavaScript-->
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/vendor/jquery-easing/jquery.easing.min.js"><?php echo '</script'; ?>
>

<!-- Custom scripts for all pages-->
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/js/admin.min.js"><?php echo '</script'; ?>
>

<!-- Page level plugins -->
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/vendor/chart.js/Chart.min.js"><?php echo '</script'; ?>
>

<!-- Page level custom scripts -->
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/js/demo/chart-area-demo.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/js/demo/chart-pie-demo.js"><?php echo '</script'; ?>
>

 
<!-- Page level plugins -->
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/vendor/datatables/jquery.dataTables.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/vendor/datatables/dataTables.bootstrap4.min.js"><?php echo '</script'; ?>
>

<!-- Page level custom scripts -->
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/js/demo/datatables-demo.js"><?php echo '</script'; ?>
>

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('plot_view'))) {
echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common_new/js/jquery.imagemapster.min.js"><?php echo '</script'; ?>
>
<?php }?>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/lib/common/js/script.js"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
  const refreshNotifications = ()=>{
    let arguement = {
			send: true,
			sendMode: 'getFresh', 
		};
		$.post('<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/refresh', arguement, function(resp) {
      //console.log(resp)
      let chatResp = JSON.parse(resp);
      let memo = chatResp.memo;
      var memo_value = '';
      let alertBar = chatResp.alertBar;
      var alertBar_value = '';
      let messages = chatResp.messages;
      var messages_value = '';
      let count = chatResp.count;
      var count_value = '';
      let counter = chatResp.counter;
      var counter_value = '';

      if(memo != null){
        for (mem of memo) {
          memo_value += `<div id="" class="col-sm-12 col-lg-4">                    
                    <a href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/memos?id=${mem.token}" class="text-decoration-none">
                      <div style="z-index: 99999;" class="alert text-center alert-danger flicker alert-dismissible" role="alert">
                        <strong>${mem.sendername}: </strong> <br>${mem.content}
                      </div>
                    </a>
                  </div>
                `;
        }
      }

      if(messages != null){
        for (mess of messages) {
        messages_value += `
            <a class="dropdown-item d-flex align-items-center" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/inbox?id=${mess.messageid}">
              <div class="dropdown-list-image mr-3">
                <img class="rounded-circle" src="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/${mess.senderimg}" alt="">
                <div class="status-indicator bg-success"></div>
              </div>
              <div class="font-weight-bold">
                <div class="text-truncate">${mess.content}</div>
                <div class="small text-gray-500">${mess.sendername} <br> ${mess.datesent}</div>
              </div>
            </a>
          `;
        }
      }else{
        messages_value = `<p class="dropdown-item text-center">No new Messages</p>`
      }
      if(alertBar != null){
        for (alert of alertBar) {
          if(alert.page == ""){
            alertBar_value += `
                <a class="dropdown-item d-flex align-items-center" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/<?php echo $_smarty_tpl->tpl_vars['sitePage']->value;?>
?alertid=${alert.token}">
                  <div class="mr-3">
                    <div class="icon-circle ${alert.severity==0 ? 'bg-primary' : ' ' } ${alert.severity==1 ? 'bg-warning' : ' ' } ${alert.severity==2 ? 'bg-danger' : ' ' }">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">${alert.dateadded}</div>
                      ${alert.content}
                    </div>
                </a>`;
            }else{
              alertBar_value += `
                  <a class="dropdown-item d-flex align-items-center" href="<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/${alert.page}&alertid=${alert.token}">
                    <div class="mr-3">
                      <div class="icon-circle ${alert.severity==0 ? 'bg-primary' : ' ' } ${alert.severity==1 ? 'bg-warning' : ' ' } ${alert.severity==2 ? 'bg-danger' : ' ' }">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">${alert.dateadded}</div>
                        ${alert.content}
                      </div>
                  </a>`;
            }
          }
        }
        else{
          alertBar_value = `<p class="dropdown-item text-center">No Alerts</p>`
        }

			$('#memo_messages').html(memo_value);
			$('#message_messages').html(messages_value);
			$('#alert_messages').html(alertBar_value);

      counter_value = counter > 0 ? " badge-danger flicker " : " badge-success ";
      count_value = count > 0 ? " badge-danger flicker " : " badge-success ";
      $('#counter').html(counter);
      $('#counter').addClass(counter_value);
      $('#count').html(count);
      $('#count').addClass(count_value);


		});
	}

  $(document).ready(function () {
    
    setInterval(refreshNotifications, 500);
    
    <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && !in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('real-estate','bill','add_bill','vendors','manage_finances','view_finance','view_finance_detail','blog-new','blog','news','news-new','news-info','newsletters','investment_webpage','finance_manager','event-new','event','docupload-new','docupload'))) {?>
      $('.select2-container').addClass("w-75");
      $('select').select2();
    <?php }?>

  });

  <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('chat'))) {?>
  
  
    const refreshPeople = ()=>{
      let arguement = {
        send: true,
        sendMode: 'getFresh', 
      };
        $.post('<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/chat_people', arguement, function(resp) {
          //console.log(resp);
          let chatResp = JSON.parse(resp);
          let peoples_array = chatResp;
          var peoples_value = '';
          if(peoples_array != null){
                for (peoples of peoples_array) {
                  if(!jQuery.isEmptyObject( peoples )){
                    peoples_value +=  `<tr class="" id="${peoples.email}" fullname="${peoples.fullname}" >
                        <td class="pe-auto">
                          <a class="text-decoration-none btn" rel="noopener noreferrer">${peoples.fullname}</a> ${peoples.hasread == 0 ? ' <span class="float-right text-success"><i class="fa fa-circle"></i></span>' : ''}  
                        </td>
                    </tr>`;
                  }
                }
          }else{
            peoples_value = `<p class="text-center">Begin a new chat</p>`
          }
          $('#recent_people').html(peoples_value);
        });
    }

    
  function myScroll(some) {
  }

    $('.peoples tr').click(function () {
        $('.peoples tr').removeClass('bg-success text-light');
        $(this).addClass('bg-success text-light');
        $('#setUser').val(this.id);
        $("#msg_title").html($(this).attr('fullname'));
        $('#send-area').removeClass('d-none');
        refreshChat();
        setTimeout(function(){
        var mydiv = $(".msger-chat");
        mydiv.scrollTop(mydiv.prop("scrollHeight"));
        },500);
    });

    $('#people').on('click','tr', function() {
      $('.peoples tr').removeClass('bg-success text-light');
        $(this).addClass('bg-success text-light');
        $('#setUser').val(this.id);
        $("#msg_title").html($(this).attr('fullname'));
        $('#send-area').removeClass('d-none');
        refreshChat();
        setTimeout(function(){
        var mydiv = $(".msger-chat");
        mydiv.scrollTop(mydiv.prop("scrollHeight"));
        },500);
    });

    const msgerForm = get(".msger-inputarea");
    const msgerInput = get(".msger-input");
    const msgerChat = get(".msger-chat");

    // Icons made by Freepik from www.flaticon.com
    const BOT_IMG = "https://image.flaticon.com/icons/svg/327/327779.svg";
    const PERSON_IMG = "https://image.flaticon.com/icons/svg/145/145867.svg";
    const PERSON_NAME = '<?php echo ucfirst($_smarty_tpl->tpl_vars['userinfo']->value->lastname);?>
 <?php echo ucfirst($_smarty_tpl->tpl_vars['userinfo']->value->firstname);?>
';
    
    const  refreshChat = ()=>{
        if($('#setUser').val() != 'null'){
            let arguement = {
            send: true,
            sendMode: 'getFresh', 
            receiver: $('#setUser').val(),
          };
          $.post('<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/chat_refresh', arguement, function(resp) {
            //console.log(resp);
            let chatResp = JSON.parse(resp);
            var sender_value = '';
            if(chatResp != null){
              let sender = chatResp.sender;
              let receiver = chatResp.receiver;
              var receiver_value = '';
                for (responses of chatResp) {
                  if(responses.sender == '<?php echo $_smarty_tpl->tpl_vars['userinfo']->value->email;?>
'){
                    sender_value +=  appendMessage(PERSON_NAME, PERSON_IMG, `${responses.datesent}`, "right", `${responses.content}`);
                  }else{
                    sender_value += appendMessage(`${responses.receiver}`, BOT_IMG, `${responses.datesent}`, "left", `${responses.content}`);
                  }
                }
            }else{
                sender_value = '';
            }
            $(".msger-chat").html(sender_value);
          });
        }else{
          $('#send-area').addClass('d-none');
        }
    };


    msgerForm.addEventListener("submit", event => {
      event.preventDefault();

      const msgText = msgerInput.value;
      if (!msgText) return;

      //real_appendMessage(PERSON_NAME, PERSON_IMG, "left", msgText);
      
      let arguement = {
        send: true,
        content: msgText, 
        receiver: $('#setUser').val(),
      };

      $.post('<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/chat_send', arguement, function(resp) {
        //console.log(resp);
        let chatResp = JSON.parse(resp);
          if(chatResp.status == 1){
            refreshChat();
            refreshPeople();
          }
      });
      
      msgerInput.value = "";
    });
    
    function appendMessage(name, img, time, side, text) {
      //   Simple solution for small apps
      const msgHTML = `
        <div class="msg ${side}-msg">
          <div class="msg-img" style="background-image: url(${img})"></div>

          <div class="msg-bubble">
            <div class="msg-info">
              <div class="msg-info-name">${name}</div>
              <div class="msg-info-time">${time}</div>
            </div>

            <div class="msg-text">${text}</div>
          </div>
        </div>
      `;
      return msgHTML;
    }

    function real_appendMessage(name, img, time, side, text) {
      //   Simple solution for small apps
      const msgHTML = `
        <div class="msg ${side}-msg">
          <div class="msg-img" style="background-image: url(${img})"></div>

          <div class="msg-bubble">
            <div class="msg-info">
              <div class="msg-info-name">${name}</div>
              <div class="msg-info-time">${time}</div>
            </div>

            <div class="msg-text">${text}</div>
          </div>
        </div>
      `;

      msgerChat.insertAdjacentHTML("beforeend", msgHTML);
      msgerChat.scrollTop += 500;
    }

    // Utils
    function get(selector, root = document) {
      return root.querySelector(selector);
    }


    function random(min, max) {
      return Math.floor(Math.random() * (max - min) + min);
    }

    
    refreshPeople();
    setInterval(refreshChat, 500);

  <?php }?>

  function deleteItem() {
    if (confirm("Are you sure you want to delete?")) {
        return true;
    }
    return false;
  }
  
function exportExcel(tableId){
    $(tableId).table2excel({
      // exclude CSS class
      exclude:".noExl",
      name:"download <?php echo smarty_modifier_date_format(time());?>
",
      filename:"download <?php echo smarty_modifier_date_format(time());?>
",//do not include extension
      fileext:".xls" // file extension
   });
}

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('home','index'))) {?>

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
      label: "Earnings",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: [<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Jan;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Feb;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Mar;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Apr;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->May;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Jun;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Jul;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Aug;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Sep;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Oct;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Nov;?>
,<?php echo $_smarty_tpl->tpl_vars['earnings']->value->Dec;?>
],
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return '???' + number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': ???' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});

<?php }?>


<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_employee'))) {?>
  $('#location').change(function(){
    var prefix = $(this).children("option:selected").attr("i");
    $("#employeeId").val(prefix+"<?php echo $_smarty_tpl->tpl_vars['newEmpId']->value;?>
");
  }); 
<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_inventory'))) {?>
var main = 'ACC/'; 
var serId = '';
  $('#format').change(function(){
    var prefix = $(this).children("option:selected").attr("value");
    serId = main+prefix+'/';
    $("#serialId").val(serId);
    $('#location').val("");
  });  
  $('#location').change(function(){
      var prefix = $(this).children("option:selected").attr("i");
      str = prefix.substring(3);
      finalId = serId+str+'/'+"<?php echo $_smarty_tpl->tpl_vars['newSerId']->value;?>
";
      $("#serialId").val(finalId);
  }); 
<?php }?>
function copyToClipboardMsg(elem, msgElem) {
    elemem = document.getElementById(elem);
	  var succeed = copyToClipboard(elemem);
    var msg;
    if (!succeed) {
        msg = "Copy not supported or blocked.  Press Ctrl+c to copy."
    } else {
        msg = "Text copied to the clipboard."
    }
    if (typeof msgElem === "string") {
        msgElem = document.getElementById(msgElem);
    }
    msgElem.innerHTML = msg;
    setTimeout(function() {
        msgElem.innerHTML = "";
    }, 2000);
}

function copyToClipboard(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}
<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('gen_payslip'))) {?>
    screenshot();    
<?php }?>  

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('invoice'))) {?>
    $(document).on('click','#printNow',function(){
      printDiv('printable','Invoice To <?php echo ucfirst($_smarty_tpl->tpl_vars['subscription']->value->surname);?>
 <?php echo ucfirst($_smarty_tpl->tpl_vars['subscription']->value->middlename);?>
 <?php echo ucfirst($_smarty_tpl->tpl_vars['subscription']->value->firstname);?>
');    
    })
<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('balance_sheet'))) {?>
    $(document).on('click','#printNow',function(){
      printDiv('printable','Balance Sheet Report');    
    })
<?php }
if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('cash_flow'))) {?>
    $(document).on('click','#printNow',function(){
      printDiv('printable','Cash Flow Report');    
    })
<?php }
if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('view_estimate'))) {?>
    $(document).on('click','#printNow',function(){
      printDiv('printable','Estimate To <?php echo ucwords($_smarty_tpl->tpl_vars['estimate']->value->customer);?>
');    
    })
<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('payslips','payslip'))) {?>   
    $(document).on('click','#acknowledge',function(){
      document.getElementById("removewatermark1").classList.add("d-none");
      document.getElementById("removewatermark").classList.add("d-none");
      this.classList.add("d-none");
      setTimeout(() => {  screenshot(); }, 3000);
    })
    $(document).on('click','#saveNow',function(){
      screenshot();
    })
    $(document).on('click','#printNow',function(){
      //screenshot();
      var doc = new jsPDF();
      printDiv('printable','<?php echo $_smarty_tpl->tpl_vars['payslip']->value->accname;?>
_payslip_<?php echo $_smarty_tpl->tpl_vars['payslip']->value->month;?>
_<?php echo $_smarty_tpl->tpl_vars['payslip']->value->year;?>
');
    })

    function screenshot(){
      window.scrollTo(0,0);
      html2canvas(document.getElementById('printable')).then(function(canvas) {
       //document.body.appendChild(canvas);
      //console.log(document.getElementById('printable'));
      // Get base64URL
      //var base64URL = canvas.toDataURL('image/png');
      var base64URL = canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
      
      //console.log(base64URL);
      // AJAX request
        $.ajax({
          url: '<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/upload_payslip',
          type: 'post',
          data: {image: base64URL,id:'<?php echo $_smarty_tpl->tpl_vars['payslip']->value->id;?>
'},
          success: function(data){
              alert("Saved to <?php echo $_smarty_tpl->tpl_vars['Site']->value['companyName'];?>
 Docs");
          }
        });
      });  
    }
    
<?php }?>

function printDiv(divId, title) {
  
  var doc = new jsPDF();
  let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');
  
  mywindow.document.write(`<html><head><title>${title}</title> <?php $_smarty_tpl->_subTemplateRender("file:style.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> `);
  mywindow.document.write('</head><body>');
  mywindow.document.write(document.getElementById(divId).innerHTML);
  mywindow.document.write('</body></html>');
  //doc.fromHTML(mywindow);
  //doc.save('div.pdf');
  mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/

  mywindow.print();
  mywindow.close();

  return true;
}

    function showTime(){
    var date = new Date();
    var h = date.getHours(); // 0 - 23
    var m = date.getMinutes(); // 0 - 59
    var s = date.getSeconds(); // 0 - 59
    var session = "AM";
    
    if(h == 0){
        h = 12;
    }
    
    if(h > 12){
        h = h - 12;
        session = "PM";
    }
    
    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;
    
    var time = h + ":" + m + ":" + s + " " + session;
    document.getElementById("MyClockDisplay").innerText = time;
    document.getElementById("MyClockDisplay").textContent = time;
    
    setTimeout(showTime, 1000);
    
}

    showTime();
    //console.log(document.getElementById("dataTable").rows[2].cells[6].innerHTML);
  
  <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('attendance_sheet'))) {?>

  var is_weekend =  function(date1){
    var dt = new Date(date1);
     
    if(dt.getDay() == 6 || dt.getDay() == 0)
       {
        return true;
      } else{
        return false;
      }
  }

  function fixTable(){
    var d = new Date();
    var n = d.getFullYear();
    var table = document.getElementById("attendance_sheet");
    var table = document.getElementById("attendance_sheet");
    for (var i = 1, row; row = table.rows[i]; i++) {
      //iterate through rows
      //rows would be accessed using the "row" variable assigned in the for loop
      for (var j = 1, col; col = row.cells[j]; j++) {
        var day = document.getElementById("attendance_sheet").rows[i].cells[j];
        //console.log(day);
        var date = i + '/' + j+ '/' +n;
        if(is_weekend(date)){
          $(day).addClass("weekend");
        }else{
          $(day).addClass("workHours");
        }
        //console.log(i + '/' + j+ '/' +n);
        //iterate through columns
        //columns would be accessed using the "col" variable assigned in the for loop
      }  
    }
  }

  $(document).ready(function() {
    fixTable();
    ///////////////////////////////////
            /*App Variables*/
    ///////////////////////////////////
        
    var workHoursBtn = document.getElementById('workHoursBtn');
    var vactionBtn = document.getElementById('vactionBtn');
    var breakHoursBtn = document.getElementById('breakHoursBtn');
    var sickBtn = document.getElementById('sickBtn');
    var maternityBtn = document.getElementById('maternityBtn');
    var otherBtn = document.getElementById('otherBtn');
    var workHours = document.getElementsByClassName('workHours');
    var vaction = document.getElementsByClassName('vaction');
    var breakHours = document.getElementsByClassName('breakHours');
    var drawWorkTimeTableBody = document.getElementById('drawWorkTimeTableBody');
    var x = 0;
    var tableDayes = {
        0: '.january',
        1: '.february',
        2: '.march',
        3: '.april',
        4: '.may',
        5: '.june',
        6: '.july',
        7: '.august',
        8: '.september',
        9: '.october',
        10: '.november',
        11: '.december'
    };
    var dayes = {
        0: 'january',
        1: 'february',
        2: 'march',
        3: 'april',
        4: 'may',
        5: 'june',
        6: 'july',
        7: 'august',
        8: 'september',
        9: 'october',
        10: 'november',
        11: 'december'
    };
        
    function changeColor1() {
        x = 1;
    }
    function changeColor2() {
        x = 2;
    }
    function changeColor3() {
        x = 0;
    }
    function changeColor4() {
        x = 4;
    }
    function changeColor5() {
        x = 5;
    }
    function changeColor6() {
        x = 6;
    }

    workHoursBtn.onclick = changeColor3;
    vactionBtn.onclick = changeColor1;
    breakHoursBtn.onclick = changeColor2;
    sickBtn.onclick = changeColor4;
    maternityBtn.onclick = changeColor5;
    otherBtn.onclick = changeColor6;
        
///////////////////////////////////
        /*Change Cell Color*/
///////////////////////////////////
        
    function changeCellColor() {
        if (x == 0) {
            $(this).toggleClass('workHours');
            $(this).removeClass('vaction breakHours other sick maternity');
        }
        if (x == 1) {
            $(this).toggleClass('vaction');
            $(this).removeClass('workHours breakHours other sick maternity');
        }
        if (x == 2) {
            $(this).toggleClass('breakHours');
            $(this).removeClass('workHours vaction other sick maternity');
        }
        if (x == 4) {
            $(this).toggleClass('sick');
            $(this).removeClass('workHours vaction breakHours other maternity');
        }
        if (x == 5) {
            $(this).toggleClass('maternity');
            $(this).removeClass('workHours vaction breakHours other sick');
        }
        if (x == 6) {
            $(this).toggleClass('other');
            $(this).removeClass('workHours vaction breakHours sick maternity');
        }
    };
        
        
        
$(function dragChangeCellColor() {
  var isMouseDown = false;
  $(".js-TimeShifts table tbody tr td")
    .mousedown(function () {
      isMouseDown = true;
      var d = new Date();
      var n = d.getFullYear();
      if (x == 0) {
            $(this).toggleClass('workHours');
            $(this).removeClass('vaction breakHours other sick maternity');
        }
        if (x == 1) {
            $(this).toggleClass('vaction');
            $(this).removeClass('workHours breakHours other sick maternity');
        }
        if (x == 2) {
            $(this).toggleClass('breakHours');
            $(this).removeClass('workHours vaction other sick maternity');
        }
        if (x == 4) {
            $(this).toggleClass('sick');
            $(this).removeClass('workHours vaction breakHours other maternity');
        }
        if (x == 5) {
            $(this).toggleClass('maternity');
            $(this).removeClass('workHours vaction breakHours other sick');
        }
        if (x == 6) {
            $(this).toggleClass('other');
            $(this).removeClass('workHours vaction breakHours sick maternity');
        }
          var col = $(this).parent().children().index($(this));
          var row = $(this).parent().parent().children().index($(this).parent());
          row++;
          var date = row + '/' + col+'/'+n;
          var data = new Date(date);
          $('#putDate').val(data.toDateString());
          //console.log(row + '/' + col+'/'+n);
      return false; // prevent text selection
    })
    .mouseover(function () {
      if (isMouseDown) {
        if (x == 0) {
            $(this).toggleClass('workHours');
            $(this).removeClass('vaction breakHours other sick maternity');
        }
        if (x == 1) {
            $(this).toggleClass('vaction');
            $(this).removeClass('workHours breakHours other sick maternity');
        }
        if (x == 2) {
            $(this).toggleClass('breakHours');
            $(this).removeClass('workHours vaction other sick maternity');
        }
        if (x == 4) {
            $(this).toggleClass('sick');
            $(this).removeClass('workHours vaction breakHours other maternity');
        }
        if (x == 5) {
            $(this).toggleClass('maternity');
            $(this).removeClass('workHours vaction breakHours other sick');
        }
        if (x == 6) {
            $(this).toggleClass('other');
            $(this).removeClass('workHours vaction breakHours sick maternity');
        }
      }
    });
  $(document)
    .mouseup(function () {
      isMouseDown = false;
    });
});

        
///////////////////////////////////
        /*Change Row Color*/
///////////////////////////////////
        
    function changeRowColor() {
      if (x == 0) {
            $(this).siblings().toggleClass('workHours');
            
            $(this).removeClass('workHours');
            $(this).siblings().removeClass('vaction breakHours other sick maternity');
        }
        if (x == 1) {
            $(this).siblings().toggleClass('vaction');
            $(this).removeClass('vaction');
            $(this).siblings().removeClass('workHours breakHours other sick maternity');
        }
        if (x == 2) {
            $(this).siblings().toggleClass('breakHours');
            $(this).removeClass('breakHours');
            $(this).siblings().removeClass('workHours vaction other sick maternity');
        }
        if (x == 4) {
            $(this).siblings().toggleClass('sick');
            
            $(this).removeClass('sick');
            $(this).siblings().removeClass('workHours vaction breakHours other maternity');
        }
        if (x == 5) {
            $(this).siblings().toggleClass('maternity');
            $(this).removeClass('maternity');
            $(this).siblings().removeClass('workHours vaction breakHours other sick');
        }
        if (x == 6) {
            $(this).siblings().toggleClass('other');
            $(this).removeClass('other');
            $(this).siblings().removeClass('workHours vaction breakHours sick maternity');
        }
        
    };
    $('.js-TimeShifts table tbody tr td:nth-of-type(1)').click(changeRowColor);
        
    ///////////////////////////////////////////
            /*Get Work Time Table Data*/
    ///////////////////////////////////////////
    let mainArray = [];
    function getWorkTimeTableData() {
        var array = [];
        for (i = 0; i < $('.js-TimeShifts table tbody').children('tr').length; i++) {
            var workHours = [];
            var workDays = [];
            var vactionHours = [];
            var sickDays = [];
            var maternityDays = [];
            var otherDays = [];
            var vactionDays = [];
            var breakHours = [];
            var emptyHours = [];
            var emptyDays = [];
            var vars = {};
            Array.prototype.max = function() {
                return Math.max.apply(Math, this);
            };
            Array.prototype.min = function() {
                return Math.min.apply(Math, this);
            };
            totalWorkDays = $(".row" + i).children('.workHours').length ;
            totalVactionDays = $(".row" + i).children('.vaction').length ;
            totalBreakDays = $(".row" + i).children('.breakHours').length ;
            totalSickDays = $(".row" + i).children('.sick').length ;
            totalMaternityDays = $(".row" + i).children('.maternity').length ;
            totalOtherDays = $(".row" + i).children('.other').length ;
            totalEmptyDays = ($(".row" + i).children().not(".workHours, .vaction, .breakHours, .sick, .maternity, .other").length - 1) ;
            $(".row" + i + " .workHours").each(function() {
                var getWorkHours = $(this).index() ;
                workHours.push(getWorkHours);
            });
            var firstWorkDay = Math.min.apply(Math, workHours),
                lastWorkDay = Math.max.apply(Math, workHours);
            $(".row" + i + " .vaction").each(function() {
                var getVactionHours = $(this).index() ;
                vactionHours.push(getVactionHours);
            });
            var firstVactionDay = Math.min.apply(Math, vactionHours),
                lastVactionDay = Math.max.apply(Math, vactionHours);
            $(".row" + i + " .breakHours").each(function() {
                var getBreakHours = $(this).index() ;
                breakHours.push(getBreakHours);
            });
            $(".row" + i + " .sick").each(function() {
                var getSickDays = $(this).index() ;
                sickDays.push(getSickDays);
            });
            $(".row" + i + " .maternity").each(function() {
                var getMaternityDays = $(this).index() ;
                maternityDays.push(getMaternityDays);
            });
            $(".row" + i + " .other").each(function() {
                var getOtherDays = $(this).index() ;
                otherDays.push(getOtherDays);
            });
            $(".row" + i).children().not(".workHours, .vaction, .breakHours, td:nth-of-type(1)").each(function() {
                var getEmptyHours = $(this).index() ;
                emptyHours.push(getEmptyHours);
            });
            var firstBreakDay = Math.min.apply(Math, breakHours),
                lastBreakDay = Math.max.apply(Math, breakHours);
            var getDayName = $(".js-TimeShifts table tbody tr td:nth-of-type(1)");
            
            vars['finalReport' + dayes[i]] = {
                dayName: $(getDayName[i]).text(),
                workDays: workHours,
                vactionDays: vactionHours,
                breakHours: breakHours,
                sickDays: sickDays,
                maternityDays: maternityDays,
                otherDays: otherDays,
                totalWorkDays: totalWorkDays,
                totalVactionDays: totalVactionDays,
                totalBreakDays: totalBreakDays,
                totalSickDays: totalSickDays,
                totalMaternityDays: totalMaternityDays,
                totalOtherDays: totalOtherDays
                /*firstWorkDay: firstWorkDay,
                lastWorkDay: lastWorkDay,
                firstVactionDay: firstVactionDay,
                lastVactionDay: lastVactionDay,
                firstBreakDay: firstBreakDay,
                emptyDays: emptyHours,
                lastBreakDay: lastBreakDay,
                totalEmptyDays: totalEmptyDays*/
            };
            array.push(vars['finalReport' + dayes[i]]);
        };

        var allData = JSON.stringify(array);
        $("#allData").html(allData);
        //console.log($( "#allData" ).html());
        //$( "#target" ).submit();
        
        ///////////////////////////////////////////
                    /*Get JSon File*/
        ///////////////////////////////////////////
        $("#response").html("Attendance sheet has been saved. You can submit now");
        var json = JSON.stringify(array);
        var blob = new Blob([json], {
            type: "application/json"
        });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.download = "backup.json";
        a.href = url;
        a.textContent = "Download backup.json";
        document.getElementById('linkcontent').appendChild(a);
        $("#save").toggle();
        $("#submitBtn").toggle();
        
    };

    $('#save').click(getWorkTimeTableData);
        
    //////////////////////////////////////////////
            /*Draw Table Using Datepicker*/
    //////////////////////////////////////////////
        
    $(function() {
        $("input[data-datepicker='True']").datepicker({
            format: "mm-yyyy",
            viewMode: 'years',
            changeMonth: true,
            changeYear: true,
            onSelect: function(date) {
                var getSelectedDate = document.getElementById('getDate');

                function getDaysInMonth(month, year) {
                    // Since no month has fewer than 28 days
                    var date = new Date(year, month, 1);
                    var days = [];
                    while (date.getMonth() === month) {
                        days.push(new Date(date));
                        date.setDate(date.getDate() + 1);
                    }
                    return days;
                };
                var selectedDate = getSelectedDate.value;
                var strMonth = parseInt(selectedDate.slice(0, 2)) - 1;
                var strYear = parseInt(selectedDate.slice(6, 10));
                var drawWorkTimeTableRows = [];
                for (i = 0; i < getDaysInMonth(strMonth, strYear).length; i++) {
                    drawWorkTimeTableRows.push('<tr class="row' + i + '"><td>' + dayes[getDaysInMonth(strMonth, strYear)[i].getDay()] + '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
                };
                drawWorkTimeTableBody.innerHTML = drawWorkTimeTableRows.join("").toString();

                $(function dragChangeCellColor() {
                  var isMouseDown = false;
                  $(".js-TimeShifts table tbody tr td")
                    .mousedown(function () {
                      isMouseDown = true;
                              if (x == 0) {
                            $(this).toggleClass('workHours');
                            $(this).removeClass('vaction breakHours');
                        }
                        if (x == 1) {
                            $(this).toggleClass('vaction');
                            $(this).removeClass('workHours breakHours');
                        }
                        if (x == 2) {
                            $(this).toggleClass('breakHours');
                            $(this).removeClass('workHours vaction');
                        }
                      return false; // prevent text selection
                    })
                    .mouseover(function () {
                      if (isMouseDown) {
                                if (x == 0) {
                            $(this).toggleClass('workHours');
                            $(this).removeClass('vaction breakHours');
                        }
                        if (x == 1) {
                            $(this).toggleClass('vaction');
                            $(this).removeClass('workHours breakHours');
                        }
                        if (x == 2) {
                            $(this).toggleClass('breakHours');
                            $(this).removeClass('workHours vaction');
                        }
                      }
                    });
                  $(document)
                    .mouseup(function () {
                      isMouseDown = false;
                    });
                });
                $('.js-TimeShifts table tbody tr td:nth-of-type(1)').click(changeRowColor);
                $('#save').click(getWorkTimeTableData);
            },
        });
    });
});
  <?php }?>

  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('finance'))) {?>

    const budget=Number(<?php echo $_smarty_tpl->tpl_vars['finance']->value->amount;?>
);

    $(document).on('change keyup', ".creditInput", function() {
      // console.log($(".creditInput").length);
      let creditVal= Number($(this).val()), crIdx=Number($(this).attr('i')),
       totalCredit=0.0, allCredits=$(".creditInput"),
       totalBal=0.0, allBalance=$(".balanceInput"), pointedBal=budget;
      if (crIdx<=0) {
        $(`#balance0`).attr('value', `${creditVal+budget}`);
        $(`#balance0`).val(`${creditVal+budget}`);
      }else{
        for (let idDx=(crIdx-1); idDx>=0; idDx--) {
          // console.log($(allBalance[idDx]));
          // pointedBal=(($(`#balance${crIdx-1}`).val() && $(`#balance${crIdx-1}`).val()>0)? Number($(`#balance${crIdx-1}`).val()): budget);
          if ($(allBalance[idDx]) && $(allBalance[idDx]).val() && $(allBalance[idDx]).val()>0) {
            pointedBal=Number($(allBalance[idDx]).val());
            break;
          }
        }
        $(`#balance${crIdx}`).attr('value', `${creditVal+pointedBal}`);
        $(`#balance${crIdx}`).val(`${creditVal+pointedBal}`);
      }
      if(creditVal>0){
        $(`#debit${crIdx}`).val('0');
        $(`#debit${crIdx}`).attr('value', '0');
      }
      if(allCredits && allCredits.length>0){
        for (let cred of allCredits) {
          totalCredit+=($(cred).val()? Number($(cred).val()): 0);
        }
      }
      $('.creditTotal').val(totalCredit);
      $(`.creditTotal`).attr('value', `${totalCredit}`);
      /*-------------------------------*/ 
      if(allBalance && allBalance.length>0){
        for (let bals of allBalance) {
          $('.balanceTotal').attr('value', `${(($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val())}`);
          $('.balanceTotal').val((($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val()));
        }
      }
    });

    $(document).on('change keyup', ".debitInput", function() {
      let debitVal= Number($(this).val()), crIdx=Number($(this).attr('i')), 
       totalDebit=0.0, allDebit=$(".debitInput"),
       totalBal=0.0, allBalance=$(".balanceInput"), pointedBal=budget;
      if (crIdx<=0) {
        $(`#balance0`).attr('value', `${budget-debitVal}`);
        $(`#balance0`).val(`${budget-debitVal}`);
      }else{
        for (let idDx=(crIdx-1); idDx>=0; idDx--) {
          if ($(allBalance[idDx]) && $(allBalance[idDx]).val() && $(allBalance[idDx]).val()>0) {
            pointedBal=Number($(allBalance[idDx]).val());
            break;
          }
        }
        $(`#balance${crIdx}`).attr('value', `${pointedBal-debitVal}`);
        $(`#balance${crIdx}`).val(`${pointedBal-debitVal}`);
      }
      if(debitVal>0){
        $(`#credit${crIdx}`).attr('value', `0`);
        $(`#credit${crIdx}`).val('0');
      }
      if(allDebit && allDebit.length>0){
        for (let debt of allDebit) {
          totalDebit+=($(debt).val()? Number($(debt).val()): 0);
        }
      }
      $('.debitTotal').val(totalDebit);
      $(`.debitTotal`).attr('value', `${totalDebit}`);
      /*-------------------------------*/ 
      if(allBalance && allBalance.length>0){
        for (let bals of allBalance) {
          $('.balanceTotal').attr('value', `${(($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val())}`);
          $('.balanceTotal').val((($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val()));
        }
      }
    });

    $(document).on('change keyup', ".balanceInput", function() {
      let totalBal=0.0, allBalance=$(".balanceInput");
      if(allBalance && allBalance.length>0){
        for (let bals of allBalance) {
          $('.balanceTotal').attr('value', `${(($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val())}`);
          $('.balanceTotal').val((($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val()));
        }
      }
    });

  <?php }?>

  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('manage_finances','view_finance'))) {?>
    
    //add row
    $(document).on('click', ".add-record", function() {
     var content = $('.sample-row'),
     size = $('#financetbl >tbody >tr').length,
     element = null,    
     element = content.clone();
     element.attr('id', 'rec-'+size);
     element.attr('class', 'row-'+size);
     element.removeClass('d-none');
     element.find('.delete-record').attr('data-id', size);
     element.find('#credits').attr('i', size);
     element.find('#credits').attr('id', 'credit'+size);
     element.find('#debits').attr('i', size);
     element.find('#debits').attr('id', 'debit'+size);
     element.find('#balances').attr('i', size);
     element.find('#balances').attr('id', 'balance'+size);
     element.appendTo('#financebody');
     element.find('.sn').html(size+1);
    });

    //delete row
    $(document).on('click', '.delete-record', function() {
      var didConfirm = confirm("Are you sure You want to delete");
      if (didConfirm == true) {
        var id = $(this).attr('data-id');
        var targetDiv = $(this).attr('targetDiv');
        jQuery('#rec-' + id).remove();
        //regnerate index number on table
        $('#financebody tr').each(function(index) {
          $(this).find('span.sn').html(index+1);
        });
      updateFun(id);
      return true;
      } else {
        return false;
      }
    });

    //setInterval(function() {  updateFun(); }, 1000);

    function updateFun(index){

      //$('.creditInput').change();
      //$('.debitInput').change();
      $('.balanceInput').change();

    }
    

    $(document).on('change keyup', ".creditInput", function() {
      let creditVal= Number($(this).val()), crIdx=Number($(this).attr('i')),
       totalCredit=0.0, allCredits=$(".creditInput"),
       totalBal=0.0, allBalance=$(".balanceInput"), pointedBal=0;
      if (crIdx<=0) {
        $(`#balance0`).attr('value', `${creditVal}`);
        $(`#balance0`).val(`${creditVal}`);
      }else{
        for (let idDx=(crIdx-1); idDx>=0; idDx--) {
          if ($(allBalance[idDx]) && $(allBalance[idDx]).val() && $(allBalance[idDx]).val()>0) {
            pointedBal=Number($(allBalance[idDx]).val());
            break;
          }
        }
        $(`#balance${crIdx}`).attr('value', `${creditVal+pointedBal}`);
        $(`#balance${crIdx}`).val(`${creditVal+pointedBal}`);
      }
      if(creditVal>0){
        $(`#debit${crIdx}`).val('0');
        $(`#debit${crIdx}`).attr('value', '0');
      }
      if(allCredits && allCredits.length>0){
        for (let cred of allCredits) {
          totalCredit+=($(cred).val()? Number($(cred).val()): 0);
        }
      }
      $('.creditTotal').val(totalCredit);
      $(`.creditTotal`).attr('value', `${totalCredit}`);
      /*-------------------------------*/ 
      if(allBalance && allBalance.length>0){
        for (let bals of allBalance) {
          //console.log(bals);
          $('.balanceTotal').attr('value', `${(($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val())}`);
          $('.balanceTotal').val((($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val()));
        }
      }
      /*
      let creditVal= Number($(this).val()), crIdx=Number($(this).attr('i')),
      totalCredit=0.0, allCredits=$(".creditInput"),
      totalBal=0.0, allBalance=$(".balanceInput"), pointedBal=0.0;
      //console.log(creditVal);
      if (crIdx<=0) {
        var deb = $(`#debit`).val();
        var cred = $(`#credit`).val();
        var bal = cred - deb;
        bal = (bal >= 0) ? bal : 0;  

        $(`#balance`).attr('value', `${bal}`);
        $(`#balance`).val(`${bal}`);

      }else{

        crIdx = crIdx + 1;
        var deb = $(`#debit${crIdx}`).val();
        var cred = $(`#credit${crIdx}`).val();
        var bal = cred - deb;
        bal = (bal >= 0) ? bal : 0;  
        //console.log(cred+" - "+deb+" = "+bal); 
         
        $(`#balance${crIdx}`).attr('value', `${bal}`);
        $(`#balance${crIdx}`).val(`${bal}`);
      
      }

      if(allCredits && allCredits.length>0){
        for (let cred of allCredits) {
          totalCredit+=($(cred).val()? Number($(cred).val()): 0);
        }
      }
      $('.creditTotal').val(totalCredit);
      $(`.creditTotal`).attr('value', `${totalCredit}`);
    
      if(allBalance && allBalance.length>0){
        for (let bals of allBalance) {
          totalBal+=($(bals).val()? Number($(bals).val()): 0);
        }
      }

      $('.balanceTotal').val(totalBal);
      $(`.balanceTotal`).attr('value', `${totalBal}`);
      */
    }); 

    $(document).on('change keyup', ".debitInput", function() {
      let debitVal= Number($(this).val()), crIdx=Number($(this).attr('i')), 
       totalDebit=0.0, allDebit=$(".debitInput"),
       totalBal=0.0, allBalance=$(".balanceInput"), pointedBal=0;
       //console.log(allBalance);
      if (crIdx<=0) {
        $(`#balance0`).attr('value', `${debitVal}`);
        $(`#balance0`).val(`${debitVal}`);
      }else{
        for (let idDx=(crIdx-1); idDx>=0; idDx--) {
          if ($(allBalance[idDx]) && $(allBalance[idDx]).val() && $(allBalance[idDx]).val()>0) {
            pointedBal=Number($(allBalance[idDx]).val());
            break;
          }
        }
        $(`#balance${crIdx}`).attr('value', `${pointedBal-debitVal}`);
        $(`#balance${crIdx}`).val(`${pointedBal-debitVal}`);
      }
      if(debitVal>0){
        $(`#credit${crIdx}`).attr('value', `0`);
        $(`#credit${crIdx}`).val('0');
      }
      if(allDebit && allDebit.length>0){
        for (let debt of allDebit) {
          totalDebit+=($(debt).val()? Number($(debt).val()): 0);
        }
      }
      $('.debitTotal').val(totalDebit);
      $(`.debitTotal`).attr('value', `${totalDebit}`);
      /*-------------------------------*/ 
      if(allBalance && allBalance.length>0){
        for (let bals of allBalance) {
          $('.balanceTotal').attr('value', `${(($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val())}`);
          $('.balanceTotal').val((($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val()));
        }
      }
      /*
      let debitVal= Number($(this).val()), crIdx=Number($(this).attr('i')), 
       totalDebit=0.0, allDebit=$(".debitInput"),
       totalBal=0.0, allBalance=$(".balanceInput"), pointedBal=0.0;
      if (crIdx<=0) {

        var deb = $(`#debit`).val();
        var cred = $(`#credit`).val();
        var bal = cred - deb;
        bal = (bal >= 0) ? bal : 0; 

        $(`#balance`).attr('value', `${bal}`);
        $(`#balance`).val(`${bal}`);

      }else{

        crIdx = crIdx + 1;
        var deb = $(`#debit${crIdx}`).val();
        var cred = $(`#credit${crIdx}`).val();
        var bal = cred - deb;
        bal = (bal >= 0) ? bal : 0;  

        //console.log(cred+" - "+deb+" = "+bal); 
         
        $(`#balance${crIdx}`).attr('value', `${bal}`);
        $(`#balance${crIdx}`).val(`${bal}`);

      }

      if(allDebit && allDebit.length>0){
        for (let debt of allDebit) {
          totalDebit+=($(debt).val()? Number($(debt).val()): 0);
        }
      }
      $('.debitTotal').val(totalDebit);
      $(`.debitTotal`).attr('value', `${totalDebit}`);
    
      if(allBalance && allBalance.length>0){
        for (let bals of allBalance) {
          totalBal+=($(bals).val()? Number($(bals).val()): 0);
        }
      }

      $('.balanceTotal').val(totalBal);
      $(`.balanceTotal`).attr('value', `${totalBal}`);
      */
    });
    
    $(document).on('change keyup', ".balanceInput", function() {
      let totalBal=0.0, allBalance=$(".balanceInput");
      //console.log(allBalance);
      if(allBalance && allBalance.length>0){
        for (let bals of allBalance) {
          $('.balanceTotal').attr('value', `${(($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val())}`);
          $('.balanceTotal').val((($(bals).val() && $(bals).val()>0)? Number($(bals).val()): $('.balanceTotal').val()));
        }
      }
    });
  
  <?php }?>

  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('estimates'))) {?>
    $(document).on('change keyup', ".product", function() {
        var amount = $(this).children("option:selected").attr("i");
        $('#price').val(amount);
        var quantity = 0;
        var price = 0;
        var tax = 0;
        var total = 0;
        quantity = $('#quantity').val();
        price = $('#price').val();
        tax = $('#tax').val();
        
        total = (price * quantity) - tax;

        $(`#amount`).val(total);
    });

    $(document).on('click', ".editestimate", function() {
      let estimateDetail=JSON.parse($(this).attr('i').trim());
      //console.log($(this));
        if(estimateDetail && $.isEmptyObject(estimateDetail)==false ){
          $('#token').val(estimateDetail.token);
          $("#editcustomer").val(`${estimateDetail.customer}`);
          $('#editdate').val(`${estimateDetail.date}`);
          $('#editexpdate').val(estimateDetail.expiry);
          $('#editdescription').val(`${estimateDetail.description}`);
          $('#editquantity').val(estimateDetail.quantity);
          $('#edittax').val(`${estimateDetail.tax}`);
          $('#editprice').val(`${estimateDetail.price}`);
          $('#editamount').val(`${estimateDetail.price}`);
          $(`#editproduct option[value='${estimateDetail.product}']`).attr("selected", 'selected');
        }
		});

    $(document).on('change keyup', ".money", function() {
      var amount = 0;
      var quantity = 0;
      var price = 0;
      var tax = 0;
      var total = 0;

      var equantity = 0;
      var eprice = 0;
      var etax = 0;
      var etotal = 0;

      quantity = $('#quantity').val();
      price = $('#price').val();
      tax = $('#tax').val();
      total = (price * quantity) - tax;

      equantity = $('#editquantity').val();
      eprice = $('#editprice').val();
      etax = $('#edittax').val();
      etotal = (eprice * equantity) - etax;

			$(`#amount`).val(total);
			$(`#editamount`).val(etotal);
		});
  <?php }?>


  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('vendors'))) {?>
  $(document).on('click', ".editvendor", function() {
      let vendorDetail=JSON.parse($(this).attr('i').trim());
      //console.log($(this));
        if(vendorDetail && $.isEmptyObject(vendorDetail)==false ){
          $('#token').val(vendorDetail.token);
          $("#edit-vendor_name").val(`${vendorDetail.vendor_name}`);
          $("#edit-email").val(`${vendorDetail.email}`);
          $('#edit-firstname').val(`${vendorDetail.firstname}`);
          $('#edit-lastname').val(vendorDetail.lastname);
          $('#edit-phone').val(`${vendorDetail.phone}`);
          $('#edit-address2').val(`${vendorDetail.address}`);
          $('#edit-city').val(vendorDetail.city);
          $(`#country option[value='${vendorDetail.country}']`).attr("selected", 'selected');
          $(`#state option[value='${vendorDetail.state}']`).attr("selected", 'selected');
        }
		});
    <?php }?>

    <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('products_services'))) {?>
$(document).on('click', ".editProduct", function() {
    let productDetail=JSON.parse($(this).attr('i').trim());
    //console.log($(this));
      if(productDetail && $.isEmptyObject(productDetail)==false ){
        $('#token').val(productDetail.token);
        $("#product_name").val(`${productDetail.product_name}`);
        $("#price").val(`${productDetail.price}`);
        $('#description').val(`${productDetail.description}`);
        $('#tax').val(productDetail.tax);
        $(`#expense_category option[value='${productDetail.expense_category}']`).attr("selected", 'selected');
      }
  });
  <?php }?>
  
<?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('bills'))) {?>
$(document).on('click', ".recordPayment", function() {
    let billDetail=JSON.parse($(this).attr('i').trim());
    //console.log($(this));
      if(billDetail && $.isEmptyObject(billDetail)==false ){
        $('#token').val(billDetail.token);
        $("#amount").val(`${billDetail.total_amount}`);
      }
  });
  <?php }?>

  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('branches'))) {?>
  $(document).on('click', ".editbranch", function() {
      let branchDetail=JSON.parse($(this).attr('i').trim());
      //console.log($(this));
        if(branchDetail && $.isEmptyObject(branchDetail)==false ){
          $('#id').val(branchDetail.id);
          $('#prefix').val(branchDetail.prefix);
          $("#name").val(`${branchDetail.zone}`);
          $("#address").val(`${branchDetail.address}`);
        }
		});
<?php }?>

  
<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('full_tree'))) {?>
    let tree = '<?php echo $_smarty_tpl->tpl_vars['head']->value;?>
';
    var colors = ['bg-primary','bg-success','bg-info','bg-warning','bg-dark'];
    tree = JSON.parse(tree);
    function printTree(nodes){
      var list = '';
      if(nodes != null) {
        list += '<ul class="inn_line w-100">';
          for(let nod of nodes){
            list += '<li class="subs">'; 
            list += '<span class="'+ colors[Math.floor(Math.random()*colors.length)] +' text-light">';
            list +=  nod.firstname + ' ' + nod.lastname + '<br>' + nod.userid;
            list += '</span>';
            if(nod.next != null){
              list += printTree(nod.next);
            }
            list += '</li>';
          }
        list += '</ul>'
      }
      return list;
    }

    var tree_branches = printTree(tree);
    //console.log(tree_branches);
    $('#tree_top').append(tree_branches);
<?php }?>


  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('view_finance_detail'))) {?>
							$(document).on('change keyup', ".category", function() {
								var category = $(this).val();
								//console.log(category);
								if(category == 'income'){
								$(`#incomedetail`).removeClass('d-none');
								$(`#expensedetail`).addClass('d-none');
								}else{
								//console.log(category);
								$(`#expensedetail`).removeClass('d-none');
								$(`#incomedetail`).addClass('d-none');
								}
								$(`#catfinal`).val(category);

							});

        $(document).on('change keyup', ".expensedetail", function() {
            var category = $(this).val();
        var sub_category = $(this).children("option:selected").attr("i");
            $('#sub_category').val(sub_category);
            $(`#detfinal`).val(category);
        });

        $(document).on('change keyup', ".incomedetail", function() {
            var category = $(this).val();
        var sub_category = $(this).children("option:selected").attr("i");
            $('#sub_category').val(sub_category);
            $(`#detfinal`).val(category);
        });
  <?php }?>

  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('finance_manager'))) {?>
    
    //add row
    $(document).on('click', ".add-record", function() {
     var content = $('.sample-row'),
     size = $('#financetbl >tbody >tr').length,
     element = null,    
     element = content.clone();
     element.attr('id', 'rec-'+size);
     element.attr('class', 'row-'+size);
     element.removeClass('d-none');
     element.find('.delete-record').attr('data-id', size);

     //select tags
     element.find('#credits').attr('i', size);
     element.find('#credits').attr('id', 'incomedetail'+size);
     element.find('#debits').attr('i', size);
     element.find('#debits').attr('id', 'expensedetail'+size);

     //input and label
     element.find('#income').attr('i', size);
     element.find('#income').attr('name', 'category'+size);
     element.find('#income').attr('id', 'income'+size);
     element.find('#labelIncome').attr('for', 'income'+size);

     element.find('#detfinal').attr('id', 'detfinal'+size);
     element.find('#catfinal').attr('id', 'catfinal'+size);
     

     element.find('#expense').attr('i', size);
     element.find('#expense').attr('name', 'category'+size);
     element.find('#expense').attr('id', 'expense'+size);
     element.find('#labelExpense').attr('for', 'expense'+size);



     element.appendTo('#financebody');
     element.find('.sn').html(size+1);
    });

    //delete row
    $(document).on('click', '.delete-record', function() {
      var didConfirm = confirm("Are you sure You want to delete");
      if (didConfirm == true) {
        var id = $(this).attr('data-id');
        var targetDiv = $(this).attr('targetDiv');
        jQuery('#rec-' + id).remove();
        //regnerate index number on table
        $('#financebody tr').each(function(index) {
          $(this).find('span.sn').html(index+1);
        });
      updateFun(id);
      return true;
      } else {
        return false;
      }
    });

    $(document).on('change keyup', ".category", function() {
        let crIdx=Number($(this).attr('i'));
        var category = $(this).val();
        //console.log(category);
        if(category == 'income'){
          //console.log( $(`#income${crIdx}`));
          $(`#incomedetail${crIdx}`).removeClass('d-none');
          $(`#expensedetail${crIdx}`).addClass('d-none');
        }else{
          //console.log(category);
          $(`#expensedetail${crIdx}`).removeClass('d-none');
          $(`#incomedetail${crIdx}`).addClass('d-none');
        }
        $(`#catfinal${crIdx}`).val(category);
    });

    $(document).on('change keyup', ".expensedetail", function() {
        let crIdx=Number($(this).attr('i'));
        var sub_category = $(this).children("option:selected").attr("i");
        var category = $(this).val();
        $(`#detfinal${crIdx}`).val(category);
        $(`#detfinal${crIdx}`).attr('v',sub_category);
    });

    $(document).on('change keyup', ".incomedetail", function() {
        let crIdx=Number($(this).attr('i'));
        var sub_category = $(this).children("option:selected").attr("i");
        var category = $(this).val();
        $(`#detfinal${crIdx}`).val(category);
        $(`#detfinal${crIdx}`).attr('v',sub_category);
    });

    setInterval(function() {  updateFun(); }, 1000);
    setInterval(function() {  updateData(); }, 1000);

    var updateFun = function(){
      var table = document.getElementById("financetbl");
      //console.log(table);
      var totalBalance = 0;
      var totalIncome = 0;
      var totalExpense = 0;
      var length = $('#financetbl tr').length;
      for (var i = 1, row; row = table.rows[i] && i < length-1; i++) {

        var row_index = i;
        
        //CR SIDE
        var amount = document.getElementById("financetbl").rows[row_index].cells[8].children['0'].value;
        if(amount<0||Number.isNaN(parseFloat(amount))){
          document.getElementById("financetbl").rows[row_index].cells[8].children['0'].value = 0;
        }
        //console.log(amount);
        var cat1 = document.getElementById("financetbl").rows[row_index].cells[5].children['0'].children['0'].children['0'];
        var cat2 = document.getElementById("financetbl").rows[row_index].cells[5].children['0'].children['1'].children['0'];
        //console.log(cat1);

        if($(cat1).is(":checked")){
          totalIncome += +amount;
        }else{
          totalExpense += +amount;
        }
        
      }

      totalBalance = totalIncome - totalExpense;
      /*
      console.log("income = " + totalIncome );
      console.log("expense = " + totalExpense );
      console.log("balance = " + totalBalance );
      */
      $('.creditTotal').val(totalIncome);
      $(`.creditTotal`).attr('value', `${totalIncome}`);
      $('.debitTotal').val(totalExpense);
      $(`.debitTotal`).attr('value', `${totalExpense}`);
      $('.balanceTotal').val(totalBalance);
      $(`.balanceTotal`).attr('value', `${totalBalance}`);
      //document.getElementById("financetbl").rows[row_index].cells[].innerHTML = drTotal;

    }
    
    var updateData = function(){

      var TableData = new Array();
      
      //var amount = document.getElementById("financetbl").rows[row_index].cells[8].children['0'].value;
       //document.getElementById("financetbl").rows[row_index].cells[8].children['0'].value;
        //var cat1 = document.getElementById("financetbl").rows[row_index].cells[5].children['0'].children['0'].children['0'];
        //var cat2 = document.getElementById("financetbl").rows[row_index].cells[5].children['0'].children['1'].children['0'];
      /*
      let crIdx=Number($(this).attr('i'));
        var category = $(this).val();
        //console.log(category);
        if(category == 'income'){
          $(`#incomedetail${crIdx}`).removeClass('d-none');
          $(`#expensedetail${crIdx}`).addClass('d-none');
        }else{
          $(`#expensedetail${crIdx}`).removeClass('d-none');
          $(`#incomedetail${crIdx}`).addClass('d-none');
        }
      */
      var table = document.getElementById("financetbl");
      var length = $('#financetbl tr').length;
      var len = 0;
      for (var i = 1, row; row = table.rows[i] && i < length-1; i++) {

        var row_index = i;
        
        //CR SIDE
        var amount = document.getElementById("financetbl").rows[row_index].cells[8].children['0'].value;
        var description = document.getElementById("financetbl").rows[row_index].cells[7].children['0'].value;
        var details = document.getElementById("financetbl").rows[row_index].cells[6].children['0'].value;
        var id_class = document.getElementById("financetbl").rows[row_index].cells[6].children['0'].id;
        var sub_category = $(`#${id_class}`).attr('v');
        var category = document.getElementById("financetbl").rows[row_index].cells[5].children['1'].value;
        var beneficiary = document.getElementById("financetbl").rows[row_index].cells[4].children['0'].value;
        var payment = document.getElementById("financetbl").rows[row_index].cells[3].children['0'].value;
        var bankaccount = document.getElementById("financetbl").rows[row_index].cells[2].children['0'].value;
        var date = document.getElementById("financetbl").rows[row_index].cells[1].children['0'].value;

        TableData[len]={
              "date" : date
              , "bankaccount" : bankaccount
              , "payment" : payment
              , "beneficiary" : beneficiary
              , "category" : category
              , "sub_category" : sub_category
              , "details" : details
              , "description" : description
              , "amount" : amount
        }
        len++;
      }
      //TableData.shift();
      TableData = JSON.stringify(TableData);
      $('#allData').val(TableData);
    
    }


  <?php }?>

  
  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_bill','bill'))) {?>
    //add row
    $(document).on('click', ".add-record", function() {
     var content = $('.sample-row'),
     size = $('#financetbl >tbody >tr').length,
     element = null,    
     element = content.clone();
     element.attr('id', 'rec-'+size);
     element.attr('class', 'row-'+size);
     
     //select tags
     element.find('#products').attr('i', size);
     element.find('#products').attr('id', 'products'+size);

     element.find('#expenses').attr('i', size);

     element.find('#amount').attr('id', 'amount'+size);
     element.find('#description').attr('id', 'description'+size);
     element.find('#price').attr('id', 'price'+size);
     element.find('#quantity').attr('id', 'quantity'+size);
     element.find('#tax').attr('id', 'tax'+size);

     element.removeClass('d-none');
     element.find('.delete-record').attr('data-id', size);

     element.appendTo('#financebody');
     element.find('.sn').html(size+1);
    });

    //delete row
    $(document).on('click', '.delete-record', function() {
      var didConfirm = confirm("Are you sure You want to delete");
      if (didConfirm == true) {
        var id = $(this).attr('data-id');
        var targetDiv = $(this).attr('targetDiv');
        jQuery('#rec-' + id).remove();
        //regnerate index number on table
        $('#financebody tr').each(function(index) {
          $(this).find('span.sn').html(index+1);
        });
      return true;
      } else {
        return false;
      }
    });

    $(document).on('change keyup', "#vendors", function() {
      var details = '';
      var vendorDetail = $(this).children("option:selected").attr("i");
      vendorDetail=JSON.parse(vendorDetail.trim());
      details = `
        ${vendorDetail.firstname} ${vendorDetail.lastname} <br>
        ${vendorDetail.address} <br>
        ${vendorDetail.country} <br>
        ${vendorDetail.email}   
      `;
      //console.log(details);
        $('#vendor_address').html(details);
    });

    $(document).on('change keyup', ".product", function() {
        let crIdx=Number($(this).attr('i'));
        var productDetail = $(this).children("option:selected").attr("i");
        //console.log(productDetail);
        productDetail=JSON.parse(productDetail.trim());
        $(`#price${crIdx}`).val(productDetail.price);
        $(`#tax${crIdx}`).val(productDetail.tax);
        $(`#amount${crIdx}`).val(productDetail.amount);
        $(`#expenses0${crIdx} option[value='${productDetail.expense_category}']`).attr("selected", 'selected');
    });
  
    var updateFun = function(){
      var table = document.getElementById("financetbl");
      //var totalAmount = 0;
      var totalIncome = 0;
      var totalExpense = 0;
      var totalAmount = 0;

      var length = $('#financetbl tr').length;

      for (var i = 1, row; row = table.rows[i] && i < length-1; i++) {

        var row_index = i;
        
        //CR SIDE
        var price = document.getElementById("financetbl").rows[row_index].cells[5].children['0'].value;
        var quantity = document.getElementById("financetbl").rows[row_index].cells[4].children['0'].value;
        var tax = document.getElementById("financetbl").rows[row_index].cells[6].children['0'].value;
        var amount = 0;

        if(price<0||Number.isNaN(parseFloat(price))){
          document.getElementById("financetbl").rows[row_index].cells[5].children['0'].value = 0;
        }
        
        if(quantity <= 0||Number.isNaN(parseFloat(quantity))){
          document.getElementById("financetbl").rows[row_index].cells[4].children['0'].value = 1;
        }

        if(tax<0||Number.isNaN(parseFloat(tax))){
          document.getElementById("financetbl").rows[row_index].cells[6].children['0'].value = 0;
        }

        amount = (quantity * price) - tax;
        totalAmount += amount;

        document.getElementById("financetbl").rows[row_index].cells[7].children['0'].value = amount;
      }
      document.getElementById("creditTotal").value = totalAmount;

    }
    
    var updateData = function(){

      var TableData = new Array();
      var table = document.getElementById("financetbl");
      var length = $('#financetbl tr').length;
      var len = 0;

      for (var i = 1, row; row = table.rows[i] && i < length-1; i++) {
        var row_index = i;
        
        //CR SIDE
        var amount = document.getElementById("financetbl").rows[row_index].cells[7].children['0'].value;
        var tax = document.getElementById("financetbl").rows[row_index].cells[6].children['0'].value;
        var price = document.getElementById("financetbl").rows[row_index].cells[5].children['0'].value;
        var quantity = document.getElementById("financetbl").rows[row_index].cells[4].children['0'].value;
        var description = document.getElementById("financetbl").rows[row_index].cells[3].children['0'].value;
        var expense_category = document.getElementById("financetbl").rows[row_index].cells[2].children['0'].value;
        var item = document.getElementById("financetbl").rows[row_index].cells[1].children['0'].value;

        TableData[len]={
              "item" : item
              , "expense_category" : expense_category
              , "description" : description
              , "quantity" : quantity
              , "price" : price
              , "tax" : tax
              , "amount" : amount
        }
        len++;
      }
      TableData = JSON.stringify(TableData);
      $('#allData').val(TableData);
      
    }


    setInterval(function() {  updateFun(); }, 1000);
    setInterval(function() {  updateData(); }, 1000);

  <?php }?>

  

	<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('timesheet'))) {?>
        $(document).ready(function(){
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(showLocation);
            }else{ 
                $('#location').html('Geolocation is not supported by this browser.');
            }
        });
    
    function showLocation(position){
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
         $("#latitude").val(latitude);
         $("#longitude").val(longitude);
        /*console.log(longitude);
        $.ajax({
            type:'POST',
            url:'<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/root/timesheet',
            data:'latitude='+latitude+'&longitude='+longitude,
            success:function(msg){
                if(msg){
                // $("#location").html(msg);
                }else{
                // $("#location").html('Not Available');
                }
            }
        });
        */
    }
    <?php }?>

    
	 <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('change_password'))) {?>
      /*  $(document).ready(function(){

          $( "#salary" ).keyup(function() {
            var salary = $( "#salary" ).val();
            var tax = 0.01*salary;
            $("#tax").val(tax);
          });
            
        });
      */$("input[type=password]").keyup(function(){
    var ucase = new RegExp("[A-Z]+");
    var lcase = new RegExp("[a-z]+");
    var num = new RegExp("[0-9]+");
	
	if($("#password1").val().length >= 8){
		$("#8char").removeClass("fa-times");
		$("#8char").addClass("fa-check");
		$("#8char").css("color","#00A41E");
	}else{
		$("#8char").removeClass("fa-check");
		$("#8char").addClass("fa-times");
		$("#8char").css("color","#FF0004");
	}
	
	if(ucase.test($("#password1").val())){
		$("#ucase").removeClass("fa-times");
		$("#ucase").addClass("fa-check");
		$("#ucase").css("color","#00A41E");
	}else{
		$("#ucase").removeClass("fa-check");
		$("#ucase").addClass("fa-times");
		$("#ucase").css("color","#FF0004");
	}
	
	if(lcase.test($("#password1").val())){
		$("#lcase").removeClass("fa-times");
		$("#lcase").addClass("fa-check");
		$("#lcase").css("color","#00A41E");
	}else{
		$("#lcase").removeClass("fa-check");
		$("#lcase").addClass("fa-times");
		$("#lcase").css("color","#FF0004");
	}
	
	if(num.test($("#password1").val())){
		$("#num").removeClass("fa-times");
		$("#num").addClass("fa-check");
		$("#num").css("color","#00A41E");
	}else{
		$("#num").removeClass("fa-check");
		$("#num").addClass("fa-times");
		$("#num").css("color","#FF0004");
	}
	
	if($("#password1").val() == $("#password2").val()){
		$("#pwmatch").removeClass("fa-times");
		$("#pwmatch").addClass("fa-check");
		$("#pwmatch").css("color","#00A41E");
	}else{
		$("#pwmatch").removeClass("fa-check");
		$("#pwmatch").addClass("fa-times");
		$("#pwmatch").css("color","#FF0004");
	}
});
    <?php }?>
  
    
	<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('commission_payroll'))) {?>
    $(document).ready(function () {
              //console.log("i am changed");

              var updateFun = function(){
                var table = document.getElementById("dataTable");
                for (var i = 2, row; row = table.rows[i]; i++) {
                  var row_index = i;
                  var nairInc = 0;

                  //CR SIDE
                  var commission =  document.getElementById("dataTable").rows[row_index].cells[6].innerHTML;
                  var amount =  document.getElementById("dataTable").rows[row_index].cells[8].innerHTML;

                  /*
                  
                  if(commission<0||Number.isNaN(parseFloat(commission))){
                    document.getElementById("dataTable").rows[row_index].cells[6].innerHTML = 0;
                  }
                  if(amount<0||Number.isNaN(parseFloat(amount))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[8].innerHTML = 0;
                  }

                  document.getElementById("dataTable").rows[row_index].cells[8].innerHTML = amount;

                  */

                  var grossTotal = amount;

                  //DR SIDE
                  var loan = document.getElementById("dataTable").rows[row_index].cells[9].innerHTML;
                  var payee =  document.getElementById("dataTable").rows[row_index].cells[10].innerHTML;
                  var pension =  document.getElementById("dataTable").rows[row_index].cells[11].innerHTML;
                  var hmo =  document.getElementById("dataTable").rows[row_index].cells[12].innerHTML;
                  var fines =  document.getElementById("dataTable").rows[row_index].cells[13].innerHTML;
                  var welfare =  document.getElementById("dataTable").rows[row_index].cells[14].innerHTML;
                  var others =  document.getElementById("dataTable").rows[row_index].cells[15].innerHTML;
                  var tax =  document.getElementById("dataTable").rows[row_index].cells[16].innerHTML;

                  if(loan<0||Number.isNaN(parseFloat(loan))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[9].innerHTML = 0;
                  }
                  if(payee<0||Number.isNaN(parseFloat(payee))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[10].innerHTML = 0;
                  }
                  if(pension<0||Number.isNaN(parseFloat(pension))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[11].innerHTML = 0;
                  }
                  if(hmo<0||Number.isNaN(parseFloat(hmo))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[12].innerHTML = 0;
                  }
                  if(fines<0||Number.isNaN(parseFloat(fines))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[13].innerHTML = 0;
                  }
                  if(welfare<0||Number.isNaN(parseFloat(welfare))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[14].innerHTML = 0;
                  }

                  if(others<0||Number.isNaN(parseFloat(others))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[15].innerHTML = 0;
                  }

                  if(tax<0||Number.isNaN(parseFloat(tax))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[16].innerHTML = 0;
                  }

                  var drTotal = +loan + +payee + +pension + +hmo + +fines + +welfare + +others + +tax;
                  document.getElementById("dataTable").rows[row_index].cells[17].innerHTML = drTotal;


                  var netTotal = +grossTotal - +drTotal
                  if(netTotal<0){
                    alert("check values\n Total can't be zero");
                    netTotal = 0;
                  }else{
                    document.getElementById("dataTable").rows[row_index].cells[18].innerHTML = netTotal;
                    document.getElementById("net_pay"+(row_index-1)).value = netTotal;
                    document.getElementById("debtotal"+(row_index-1)).value = drTotal;
                    document.getElementById("loan"+(row_index-1)).value = loan;
                    document.getElementById("payee"+(row_index-1)).value = payee;
                    document.getElementById("pension"+(row_index-1)).value = pension;
                    document.getElementById("hmo"+(row_index-1)).value = hmo;
                    document.getElementById("fines"+(row_index-1)).value = fines;
                    document.getElementById("welfare"+(row_index-1)).value = welfare;
                    document.getElementById("others"+(row_index-1)).value = others;
                    document.getElementById("tax"+(row_index-1)).value = tax;
                  }
                }                
              }

              $('#dataTable tr td').bind("DOMSubtreeModified", function(){
                var row_index = $(this).parent().index();
                row_index = row_index+2
                var col_index = $(this).index();
                  //console.log(document.getElementById("dataTable").rows[row_index].cells[col_index].innerHTML);
              });
              setInterval(function() {  updateFun(); }, 1000);
              //setInterval(function() {  updateData(); }, 1000);
                //document.getElementById("dataTable").rows[row_index].cells[col_index].innerHTML = 4000;
              
              $("#exportbtn").click(function(){
                // console.log("clicked");
                $("#dataTable").table2excel({
                // exclude CSS class
                exclude:".noExl",
                name:"Commission PayRoll <?php echo smarty_modifier_date_format(time());?>
",
                filename:"Commission PayRoll <?php echo smarty_modifier_date_format(time());?>
",//do not include extension
                fileext:".xls" // file extension
                });
              });
                  
    });
  <?php }?> 

	<?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_payroll','payroll'))) {?>
    $(document).ready(function () {
              //console.log("i am changed");

              var updateFun = function(){
                var table = document.getElementById("dataTable");
                for (var i = 2, row; row = table.rows[i]; i++) {
                  var row_index = i;
                  var nairInc = 0;

                  //CR SIDE
                  var salary = document.getElementById("dataTable").rows[row_index].cells[6].innerHTML;
                  var perc =  document.getElementById("dataTable").rows[row_index].cells[7].innerHTML;
                  var transport =  document.getElementById("dataTable").rows[row_index].cells[9].innerHTML;
                  var housing =  document.getElementById("dataTable").rows[row_index].cells[10].innerHTML;
                  var lunch =  document.getElementById("dataTable").rows[row_index].cells[11].innerHTML;
                  var commission =  document.getElementById("dataTable").rows[row_index].cells[13].innerHTML;

                  if(salary<0||Number.isNaN(parseFloat(salary))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[6].innerHTML = 0;
                  }

                  if(perc<0||Number.isNaN(parseFloat(perc))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[7].innerHTML = 0;
                  }
                  if(perc<0||Number.isNaN(parseFloat(transport))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[9].innerHTML = 0;
                  }
                  if(perc<0||Number.isNaN(parseFloat(housing))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[10].innerHTML = 0;
                  }
                  if(perc<0||Number.isNaN(parseFloat(lunch))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[11].innerHTML = 0;
                  }

                  if(commission<0||Number.isNaN(parseFloat(commission))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[13].innerHTML = 0;
                  }

                  try {
                    var nairInc = ((perc/100)*salary) + +salary;      
                  } catch (error) {
                    alert("check values");
                  }
                  document.getElementById("dataTable").rows[row_index].cells[8].innerHTML = nairInc;
                  var grossTotal = nairInc + +transport + +housing + +lunch + +commission;

                  //total
                  document.getElementById("dataTable").rows[row_index].cells[14].innerHTML = grossTotal;


                  //DR SIDE
                  var loan = document.getElementById("dataTable").rows[row_index].cells[15].innerHTML;
                  var payee =  document.getElementById("dataTable").rows[row_index].cells[16].innerHTML;
                  var pension =  document.getElementById("dataTable").rows[row_index].cells[17].innerHTML;
                  var hmo =  document.getElementById("dataTable").rows[row_index].cells[18].innerHTML;
                  var fines =  document.getElementById("dataTable").rows[row_index].cells[19].innerHTML;
                  var welfare =  document.getElementById("dataTable").rows[row_index].cells[20].innerHTML;
                  var others =  document.getElementById("dataTable").rows[row_index].cells[21].innerHTML;
                  var tax =  document.getElementById("dataTable").rows[row_index].cells[22].innerHTML;

                  if(loan<0||Number.isNaN(parseFloat(loan))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[15].innerHTML = 0;
                  }
                  if(payee<0||Number.isNaN(parseFloat(payee))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[16].innerHTML = 0;
                  }
                  if(pension<0||Number.isNaN(parseFloat(pension))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[17].innerHTML = 0;
                  }
                  if(hmo<0||Number.isNaN(parseFloat(hmo))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[18].innerHTML = 0;
                  }
                  if(fines<0||Number.isNaN(parseFloat(fines))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[19].innerHTML = 0;
                  }
                  if(welfare<0||Number.isNaN(parseFloat(welfare))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[20].innerHTML = 0;
                  }

                  if(others<0||Number.isNaN(parseFloat(others))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[21].innerHTML = 0;
                  }

                  if(tax<0||Number.isNaN(parseFloat(tax))){
                    //alert("check value");
                    document.getElementById("dataTable").rows[row_index].cells[22].innerHTML = 0;
                  }

                  var drTotal = +loan + +payee + +pension + +hmo + +fines + +welfare + +others + +tax;
                  document.getElementById("dataTable").rows[row_index].cells[23].innerHTML = drTotal;


                  var netTotal = +grossTotal - +drTotal
                  if(netTotal<0){
                    alert("check values\n Total can't be zero");
                    netTotal = 0;
                  }else{
                    document.getElementById("dataTable").rows[row_index].cells[24].innerHTML = netTotal;

                  }

                }
                
                
                var total_salary= 0;
                var total_gross= 0;
                var total_loan= 0;
                var total_tax= 0;
                var total_deb= 0;
                var total_pay = 0;
                for (var i = 2, row; row = table.rows[i]; i++) {
                  var row_index = i;
                  //CR SIDE
                  total_salary += (Number.isNaN(parseFloat(document.getElementById("dataTable").rows[row_index].cells[6].innerHTML))) ? 0 : parseFloat(document.getElementById("dataTable").rows[row_index].cells[6].innerHTML);
                  total_gross += (Number.isNaN(parseFloat(document.getElementById("dataTable").rows[row_index].cells[14].innerHTML))) ? 0 : parseFloat(document.getElementById("dataTable").rows[row_index].cells[14].innerHTML);
                  total_loan += (Number.isNaN(parseFloat(document.getElementById("dataTable").rows[row_index].cells[15].innerHTML))) ? 0 : parseFloat(document.getElementById("dataTable").rows[row_index].cells[15].innerHTML);
                  total_tax +=  (Number.isNaN(parseFloat(document.getElementById("dataTable").rows[row_index].cells[22].innerHTML))) ? 0 : parseFloat(document.getElementById("dataTable").rows[row_index].cells[22].innerHTML);
                  total_deb +=  (Number.isNaN(parseFloat(document.getElementById("dataTable").rows[row_index].cells[23].innerHTML))) ? 0 : parseFloat(document.getElementById("dataTable").rows[row_index].cells[23].innerHTML);
                  total_pay += (Number.isNaN(parseFloat(document.getElementById("dataTable").rows[row_index].cells[24].innerHTML))) ? 0 : parseFloat(document.getElementById("dataTable").rows[row_index].cells[24].innerHTML);
                 
                }
                document.getElementById("total_salary").value = total_salary;
                document.getElementById("total_gross").value = total_gross;
                document.getElementById("total_loan").value = total_loan;
                document.getElementById("total_tax").value = total_tax;
                document.getElementById("total_deb").value = total_deb;
                document.getElementById("total_pay").value = total_pay;
                
              }

              $('#dataTable tr td').bind("DOMSubtreeModified", function(){
                var row_index = $(this).parent().index();
                row_index = row_index+2
                var col_index = $(this).index();
                  //console.log(document.getElementById("dataTable").rows[row_index].cells[col_index].innerHTML);
              });
              setInterval(function() {  updateFun(); }, 1000);
              setInterval(function() {  updateData(); }, 1000);
                //document.getElementById("dataTable").rows[row_index].cells[col_index].innerHTML = 4000;
              
              $("#exportbtn").click(function(){
                  //console.log("clicked");
                $("#dataTable").table2excel({
                  // exclude CSS class
                  exclude:".noExl",
                  name:"PayRoll <?php echo smarty_modifier_date_format(time());?>
",
                  filename:"PayRoll <?php echo smarty_modifier_date_format(time());?>
",//do not include extension
                  fileext:".xls" // file extension
                });
              });

              var updateData = function(){

                var TableData = new Array();
    
                $('#dataTable tr').each(function(row, tr){
                    TableData[row]={
                        "empId" : $(tr).find('td:eq(1)').text().trim()
                        , "name" :$(tr).find('td:eq(2)').text().trim()
                        , "bankname" : $(tr).find('td:eq(3)').text().trim()
                        , "accnum" : $(tr).find('td:eq(4)').text().trim()
                        , "position" : $(tr).find('td:eq(5)').text().trim()
                        , "salary" : $(tr).find('td:eq(6)').text().trim()
                        , "per_inc" :$(tr).find('td:eq(7)').text().trim()
                        , "naira_inc" : $(tr).find('td:eq(8)').text().trim()
                        , "transport" : $(tr).find('td:eq(9)').text().trim()
                        , "housing" : $(tr).find('td:eq(10)').text().trim()
                        , "lunch" : $(tr).find('td:eq(11)').text().trim()
                        , "reason" : $(tr).find('td:eq(12)').text().trim()
                        , "commission" :$(tr).find('td:eq(13)').text().trim()
                        , "gross_total" : $(tr).find('td:eq(14)').text().trim()
                        , "loan" : $(tr).find('td:eq(15)').text().trim()
                        , "payee" : $(tr).find('td:eq(16)').text().trim()
                        , "pension" : $(tr).find('td:eq(17)').text().trim()
                        , "hmo" : $(tr).find('td:eq(18)').text().trim()
                        , "fines" : $(tr).find('td:eq(19)').text().trim()
                        , "welfare" : $(tr).find('td:eq(20)').text().trim()
                        , "others" :$(tr).find('td:eq(21)').text().trim()
                        , "tax" : $(tr).find('td:eq(22)').text().trim()
                        , "drtotal" : $(tr).find('td:eq(23)').text().trim()
                        , "net_total" :$(tr).find('td:eq(24)').text().trim()
                    }
                }); 
                TableData.shift();
                TableData.shift();
                TableData = JSON.stringify(TableData);
                $('#allData').val(TableData);
              }
              /*
              $("#savebtn").click(function(e){
                  console.log("save clicked");
                    

                    document.getElementById("mypayform").submit();

              });
              */
                  
    });
  <?php }?> 

  function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function htmlReverseEntities(str) {
      return String(str).replace(/&quot;/g, '"').replace(/&gt;/g, '>').replace(/&lt;/g, '<').replace(/&amp;/g, '&');
      // return String(str).replace('&amp;', /&/g).replace('&lt;', '<');
  }
  /*Changing of Country State City*/
  <?php if (in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('profile','vendors','add_employee','edit_employee','add_client','client','add_admin','add_guarantor','employee','admin','blogs','add_blog','edit_blog','staff-new','staff','add_official','edit_official'))) {?>
    
    $(document).on('change','#country, #state', function(){
        //console.log("hellllo");
        let csd={ data: $(this).val(), type: $(this).attr('id') };      
        //console.log(csd);
        globalParam={sp: `<?php echo $_smarty_tpl->tpl_vars['sitePage']->value;?>
`, csd: csd};
        $.post("<?php echo $_smarty_tpl->tpl_vars['Site']->value['siteProtocol'];
echo $_smarty_tpl->tpl_vars['Site']->value['domainName'];?>
/site/inc/root/general.php", globalParam, (res)=> {
          //console.log(res);
          let ret=JSON.parse(res);
          let target=( $(this).attr('id')=="country"? "#state": "#city");
          //console.log(target);
          if(ret!=null){
            if(ret.length>0 && ret!=null){
            $(target).empty();
            for (let re of ret) {
              $(target).append(`<option value="${re.name}">${re.name}</option>`);
            }
            }else{
              $(target).html(`<option value="">No Record Found</option>`);
            }
          }else{
            $(target).html(`<option value="">No Record Found</option>`);
          }
          
        });
      });
  <?php }?>
    
    $(document).on('change keyup', "#password", function() {
		  $('.genedPass').text(`${$("#password").val()}`);
		});

		$(document).on('click','.genPassword', function(){
			let genPass=getToken(8);
			$('#password').attr('value',`${genPass}`).val(genPass);
			$('#cpassword').attr('value',`${genPass}`).val(genPass);
			$('.genedPass').text(genPass);
	  });
    
  <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('blog-new','blog','project-new','project','news-new','news-info','newsletters','event-new','event','docupload-new','docupload','companydocs-new','companydoc','management_report','add_management_report','investment_webpage'))) {?>
    // Initialize Quill editor
    let toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
      ['blockquote', 'code-block', 'code'],

      //[{ 'header': 1 }, { 'header': 2 }],               // custom button values
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
      [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
      [{ 'direction': 'rtl' }],                         // text direction

       //[{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
      // [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      [{ 'header': [4, 5, 6, false] }],

      [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
      [{ 'font': [] }],
      [{ 'align': [] }],
      ['image', 'formula', 'link', 'video'], 
      ['clean']                                         // remove formatting button
    ]
    let quillOptions = {
      modules: {
        
        imageResize: {
          displaySize: true
        },
        
        toolbar: toolbarOptions
      },
      placeholder: 'Type your content here...',
      theme: 'snow'
    };
    var quill = new Quill('#editcontent', quillOptions);

    /*Event Triggerers*/
    quill.on('text-change', function(delta, oldDelta, source) {
      // if (source == 'api') {
      //   console.log("An API call triggered this change.");
      // } else if (source == 'user') {
      //   console.log("A user action triggered this change.");
      // }
      $('#content').attr('value',htmlEntities($('#editcontent .ql-editor').html()));
      //console.log($('#content').attr('value'));
      $('.addContent').attr('value', $('#content').attr('value'));
    });
  <?php }?>
  <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('blog','event','companydoc','project','news-info','event','management_report','investment_webpage'))) {?>
  $('#editcontent .ql-editor').html(`<?php echo $_smarty_tpl->tpl_vars['content_stripe']->value;?>
`);
  <?php }?>
  <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('certupload'))) {?>
    $(document).on('change', "#student", function() {
      let crsDetail=JSON.parse($("#student option:selected").attr('i').trim());
      $(".stdToken").text(`${crsDetail.token}`);
      $(".stdName").text(`Student Name:  ${crsDetail.names}`);
      $(".stdPhone").text(`Contact number: ${crsDetail.phone}`);
      var d = $("#reftoken").text();
      $("#token").val(d);
    });
  <?php }?>
  
  <?php if (!empty($_smarty_tpl->tpl_vars['sitePage']->value) && in_array($_smarty_tpl->tpl_vars['sitePage']->value,array('add_payment'))) {?>
    $(document).on('change keyup', "#project1", function() {
      let subDetail=JSON.parse($("#project1 option:selected").attr('i').trim());
      $("#serial").val(`${subDetail.email}`);
    });
  <?php }
echo '</script'; ?>
>

<?php }
}
