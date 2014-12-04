<?php

	function loadDashboardLeftMenu( $tpl, $params )
	{
		global $C;
		$page 		= & $GLOBALS['page'];
		$network 	= & $GLOBALS['network'];
		$user 		= & $GLOBALS['user'];
		$pm 		= & $GLOBALS['plugins_manager'];
	
		$tab	= 'everybody';
		if( $page->param('tab') ){
			$tab = htmlspecialchars( $page->param('tab') );
		}
		
		$my_groups	= $user->get_top_groups(5);
		//tab_state
		
		
		$new_activities = $network->get_dashboard_tabstate($user->id, array('all', 'commented', '@me'));
		
		$menu = array( 	array('url' => 'dashboard/tab:all', 		'css_class' => 'my-activities'.(($tab === 'all')? ' selected' : ''), 	'title' => $page->lang('dbrd_leftmenu_all'), 												'tab_state' => $new_activities['all']),
						array('url' => 'dashboard/tab:@me', 		'css_class' => 'at'.(($tab === '@me')? ' selected' : ''), 				'title' => $page->lang('dbrd_leftmenu_@me', array('#USERNAME#'=>$user->info->username) ), 	'tab_state' => $new_activities['@me'] ),
						array('url' => 'dashboard/tab:commented', 	'css_class' => 'comments'.(($tab === 'commented')? ' selected' : ''), 	'title' => $page->lang('dbrd_leftmenu_commented'), 											'tab_state' => $new_activities['commented'] ),
						array('url' => 'dashboard/tab:bookmarks', 	'css_class' => 'favourites'.(($tab === 'bookmarks')? ' selected' : ''), 'title' => $page->lang('dbrd_leftmenu_bookmarks') ),
						array('url' => 'dashboard/tab:everybody', 	'css_class' => 'filter-all'.(($tab === 'everybody')? ' selected' : ''), 'title' => $page->lang('dbrd_leftmenu_everybody', array('#COMPANY#'=>$C->COMPANY)) )
		);
		
		
		
		$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock($page->lang('dbrd_poststitle_everybody'), $tpl->designer->createMenu('feed-navigation', $menu, 'dashboard_main_left_menu')) );
		
$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock( 'گروه‌های پیشنهادی', '<div align="center"><a href="http://ume.ir/playlist"><img src="http://ume.ir/banner/playlist_gp.gif" /></a></div>' ));	
		
		$menu = array();
		foreach( $my_groups as $group ){
			$menu[] = array('url' => 'dashboard/tab:group/g:'.$group->groupname, 	'css_class' => (($group->is_public)? 'public' : 'private') . (($page->param('g') === $group->groupname)? ' selected' : ''),	'title' => ucfirst( $group->title ) );
		}
		if( count($menu) > 0 ){
			$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock($page->lang('dbrd_leftmenu_groups_my'), $tpl->designer->createMenu('feed-navigation', $menu)) );
		}unset($menu, $my_groups);
		
		$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_lastonline'), $tpl->designer->createUserLinks( $network->get_online_users(), 'thumbs3' ) ) );
		
			
		
		$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock( 'آخرین کاربران', $tpl->designer->createUserLinks( $network->get_new_users(), 'thumbs3' ) ) );
		$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock( 'لیدرهای شبکه', $tpl->designer->createUserLinks( $network->get_leader_users(), 'thumbs3' ) ) );
		$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_posttags'), $tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) );
		$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->whatToDoBlock() ); 
		$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock( 'تبلیغات', '<div align="center"><a href="http://www.myqq.ir" target="_blank"><img src="'.$C->STATIC_URL.'images/myqq-120x120.gif" width="120" height="120" alt="MyQQ" /></a></div>' ));
		//$tpl->layout->saveVars(); //there is a saveVar to set the placeholders
	
	}