<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2'))  && !in_array($userinfo->department, array('Corporate Office'))):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_newsletter_subscriber")) && ($requestMethod == 'POST') && isset($Site["post"]['add_mailing_list']) ) {
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->lists)) or !in_array($posts->lists, array("single","bulk"))):
		$err++;
		$fail.='<p class="border badge-pill border-danger">Choose a format</p>';
	elseif( $posts->lists=='single'):
		if(empty($posts->email)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly enter email!</p>';
		else:
			$email = $posts->email;
			if (!empty($ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$posts->email'"))) {
				if($ezDb->query("UPDATE `newsletter` SET `status`='1', `dateupdated`='$dateNow' WHERE `email`='$posts->email'")){
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New Newsletter Subscriber was successfully added.</p></div>';
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
				}
			}else{
				if($ezDb->query("INSERT INTO `newsletter` (`status`, `dateupdated`, `email`) VALUES ('1', '$dateNow', '$posts->email');")){
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New Newsletter Subscriber was successfully added.</p></div>';
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
				}
			}
			require_once "mail_new_subscriber.php";
		endif;
	elseif( $posts->lists=='bulk'):
		if(empty($posts->email2)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly enter emails!</p>';
		else:
			$myArray = explode(',', $posts->email2);
			$count = 0; 
			if(!empty($myArray)){
				foreach ($myArray as $key => $email) {
					if (!empty($ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$email'"))) {
						if($ezDb->query("UPDATE `newsletter` SET `status`='1', `dateupdated`='$dateNow' WHERE `email`='$email'")){
							$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New Newsletter Subscriber was successfully added.</p></div>';
						}else{
							$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
						}
					}else{
						if($ezDb->query("INSERT INTO `newsletter` (`status`, `dateupdated`, `email`) VALUES ('1', '$dateNow', '$email');")){
							$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New Newsletter Subscriber was successfully added.</p></div>';
						}else{
							$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
						}
					}
					require_once "mail_new_subscriber.php";
					$count++;
				}
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$count.' New Newsletter Subscribers were successfully added.</p></div>';
				
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Please Enter emails and separate with comma</p></div>';
				
			}
			//require_once "mail_newsletter.php";
		endif;
	endif;
}
