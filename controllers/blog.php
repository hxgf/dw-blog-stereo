<?php


$app->get('/blog', function(){

	$_articles = db_find("articles", "published IS NOT NULL ORDER BY date_published DESC");
	$_users = db_find("users", "id IS NOT NULL");

	$users = array();
	if ($_users['data']){
		foreach($_users['data'] as $u){
			$users[$u['_id']] = $u;
		}
	}
	
	$articles = array();
	if ($_articles['data']){
		foreach($_articles['data'] as $a){
			$a['screenname'] = $users[$a['user_id']]['screenname'];
			$a['body'] = html_entity_decode($a['body']);
			$articles[$a['_id']] = $a;
		}		
	}

	$pagination = false;
/*
	$pagination = array(
		'href_next' => '/link/to/next',
		'href_previous' => '/link/to/previous'
	);
*/

	$GLOBALS['app']->render_template(array(
		'template' => 'blog-list',
    'title' => 'Blog - ' . $GLOBALS['site_title'],
		'data' => array(
			'articles' => $articles,
			'pagination' => $pagination
		)
	));

});






$app->get('/blog/[*:url_slug]', function($url_slug){

	$_data = db_find("articles", "url_slug='".$url_slug."'");
	$_users = db_find("users", "_id='".$_data['data'][0]['user_id']."'");

	$data = $_data['data'][0];
	$data['screenname'] = $_users['data'][0]['screenname'];
	$data['body'] = html_entity_decode($data['body']);

	$GLOBALS['app']->render_template(array(
		'template' => 'blog-detail',
    'title' => $data['title'] . ' - Blog - ' . $GLOBALS['site_title'],
		'data' => array(
			'data' => $data
			)
	));

});






 ?>
