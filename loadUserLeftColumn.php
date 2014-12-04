<?php
	function loadUserLeftColumn( $tpl, $params )
	{
		global $C;
		$page 	= & $GLOBALS['page'];
		$user 	= & $GLOBALS['user'];
		$network = & $GLOBALS['network'];
		
		if( !isset($params[0]) || !isset($params[1]) ){
			return;
		}
		
		$u = $params[0];
		$he_follows = $params[1]; 
		$if_he_follows_me 	= isset( $he_follows[$user->id] );
		
		$is_admin_or_follows_me = ( $user->is_logged && $user->info->is_network_admin || $if_he_follows_me );
		$is_dm_protected = ( $u->is_dm_protected && !$is_admin_or_follows_me );
		
		$tmp_he_follows = array();
		if( count($he_follows) > 0 ) {
			$he_follows = array_slice(array_keys($he_follows), 0, 10);
			foreach( $he_follows as $uid ){
				$tmp = $network->get_user_by_id($uid);
				if( $tmp ){
					$tmp_he_follows[] = array('username'=>$tmp->username, 'avatar'=>$tmp->avatar);
				}
			}
		}
		$space1=array('m','f');
		$space2=array('مرد','زن');
		$text = $u->signature;
		$signature = preg_replace("#[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]#","<a href=\"\\0\">\\0</a>", $text);
        $bg='';
		if($u->bg > '1'){
			$bg='<style>html,body{background-image:url('.$u->bg.'); background-position:top center; background-repeat:repeat; background-attachment:fixed; background-size:100% 100%;}</style>';
		}
        $cover='';
		if($u->mp3_usr > '1'){
			$cover='<style>.user-subheader-container{background-image:url('.$u->mp3_usr.'); background-position:top center; background-repeat:repeat;}</style>';
		}
		$tpl->layout->setVar( 'left_content', '<div class="profile-avatar '.(($u->active)? '' : 'suspended') .'"><img src="'.$C->STORAGE_URL.'avatars/'.$u->avatar.'" alt="'.$u->fullname.'" /><span class="avatar-overlay"><span></span></span>{%state%}</div>');
		$tpl->layout->setVar( 'left_content', '<div class="section-container">
		<h3 class="section-title">درباره من</h3>
		<div>'.$u->about_me.'</div>
		<h3 class="section-title">تاریخ تولد</h3>
		<div>'.$u->birthdate.'</div>
		<h3 class="section-title">محل زندگی</h3>
		<div>'.$u->location.'</div>
		<h3 class="section-title">تعداد ارسال ها</h3>
		<div>'.$u->num_posts.' پست</div>
		<h3 class="section-title">تعداد دنبال کنندگان</h3>
		<div>'.$u->num_followers.' نفر</div>
		<h3 class="section-title">امضاء</h3>
		<div>'.$signature.'</div>
		<h3 class="section-title">حالت امروز</h3>
		<div>'.$u->sense.'</div>
		<h3 class="section-title">جنسیت - وضعیت تاهل</h3>
		<div>'.str_replace($space1,$space2,$u->gender).' و '.$u->marital.'</div>
		<h3 class="section-title">دین</h3>
		<div>'.$u->religion.'</div>
		<h3 class="section-title">سطح تحصیلات</h3>
		<div>'.$u->edu.'</div>
		<h3 class="section-title">شغل</h3>
		<div>'.$u->job.'</div>
		<h3 class="section-title">مشخصات ظاهری</h3>
		<div>وزن :'.$u->weight.'کیلوگرم</div>
		<div>قد :'.$u->length.'سانتی متر</div>
		<h3 class="section-title">خدمت وظیفه</h3>
		<div>'.$u->sold.'</div>
		<h3 class="section-title">سیگار</h3>
		<div>'.$u->smoke.'</div>
		<h3 class="section-title">مدل گوشی</h3>
		<div>'.$u->phone.'</div>
		<h3 class="section-title">مدل اتومبیل</h3>
		<div>'.$u->car.'</div>
		<h3 class="section-title">علاقه ها</h3>
		<div>'.$u->favs.'</div>
		</div>'.$bg.''.$cover);
		
		$user_message_data = '{"users_id": '.$u->id.', "users_name":"'.$u->fullname.'"}';
		$user_info = '<ul class="personal-information">'
						.(( $user->is_logged && $user->info->is_network_admin || $if_he_follows_me ) ? '
						<li class="personal-information-email"><a href="mailto:'.$u->email.'">'.$u->email.'</a></li> '. 
						(!empty($u->pphone)? '<li>' . $page->lang('usr_left_cnt_pphone').' '.htmlspecialchars($u->pphone) .'</li>' : '') : '').
						
						( ( $user->is_logged && !$is_dm_protected && $user->id != $u->id)? '<li><a class="plain-btn send-message" data-role="services" data-namespace="users" data-value="'.htmlentities($user_message_data).'" data-action="sendMessagePopup">Send a message</a></li>' : '' ). 
					'</ul>';
		$tpl->layout->setVar( 'left_content', $tpl->designer->createInfoBlock( '', $user_info));

		
		$tpl->layout->setVar( 'left_content', 
			$tpl->designer->createInfoBlock( $page->lang('usr_left_following'), $tpl->designer->createUserLinks( $tmp_he_follows , 'thumbs3') ) .
			$tpl->designer->createInfoBlock( $page->lang('usr_left_tgsubx_ttl'), $network->get_recent_posttags(10, $u->id, 'user') )
		);
		
		if( count($u->tags) > 0 ){
			$tpl->layout->setVar( 'left_content', $tpl->designer->createInfoBlock( $page->lang('usr_left_tgsubx_ttl'), $tpl->designer->createTagLinks( $u->tags ) ) );
		$tpl->layout->setVar( 'left_content', $tpl->designer->createInfoBlock( 'تبلیغات', '<div align="center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="120" height="240" title="Flash">  <param name="movie" value="'.$C->STATIC_URL.'flash/120-240.swf" />  <param name="quality" value="high" />  <embed src="'.$C->STATIC_URL.'flash/120-240.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="120" height="240"></embed></object></div>' ));
		}
		
		unset($tmp_he_follows, $tmp );
	}