<?php


$app->get('/admin/blog/?', function(){

	$_articles = db_find("articles", "id IS NOT NULL ORDER BY date_published DESC");
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
			$articles[$a['_id']] = $a;
		}
	}

	$title = 'Blog Articles - ' . $GLOBALS['site_title'] .' Admin';

	$GLOBALS['app']->render_template(array(
		'layout' => 'admin',
		'template' => 'admin/blog-list',
    'title' => $title,
		'data' => array(
	    'current_blog' => true,
	    'articles' => $articles
		)
	));

});





$app->get('/admin/blog/edit/[*:article_id]', function($article_id){

	$_data = db_find("articles", "_id='".$article_id."'");

	$data = $_data['data'][0];

	if ($_data['data']){
		$title = 'Edit Article - '.$data['title'].' - '.$GLOBALS['site_title'].' Admin';
	}else{
		$title = 'New Article - '.$GLOBALS['site_title'].' Admin';
		$data['date_published'] = time();
	}

	$GLOBALS['app']->render_template(array(
		'layout' => 'admin',
		'template' => 'admin/blog-edit',
    'title' => $title,
		'data' => array(
	    'current_blog' => true,
	    'data' => $data
		)
	));

});





$app->post('/admin/blog/save', function(){

	$form = array();
	parse_str($_POST['form'],$form);

	$input = array(
		'title' => $form['title'],
		'url_slug' => $GLOBALS['app']->url_slug($form['title']),
		'body' => $form['body'],
		'summary' => $form['summary'],
		'tags' => $form['tags'],
		'photo_caption' => $form['photo_caption'],
		'user_id' => $_POST['user_id'],
		'date_published' => $form['date_published'] ? strtotime($form['date_published']) : time(),
		'published' => $form['published'] ? '1' : NULL,
	);

  if ($form['_id'] == ''){
	  $input['_id'] = uniqid(uniqid());
		db_insert("articles", $input);
		$article_id = $input['_id'];
  }else{
		db_update("articles", $input, "_id='".$form['_id']."'");
		$article_id = $form['_id'];
  }

  if ($form['file_1']){
		if ($form['file_1'] == 'DELETE'){
			$article = db_find("articles", "_id='".$article_id."'");
			unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_small']);
			unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_medium']);
			unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_original']);
			$photo_input = array(
				'photo_url_small' => null,
				'photo_url_medium' => null,
				'photo_url_original' => null,
			);
		}else{
			$article = db_find("articles", "_id='".$article_id."'");
			if ($article['data'][0]['photo_url_small']){
				unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_small']);
				unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_medium']);
				unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_original']);
			}
			$filename = $form['file_1'];
			$ext = strtolower(pathinfo($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $filename, PATHINFO_EXTENSION));
			$filename_clean = explode('||-||', str_replace('.'.$ext, '', $filename));
	    $source = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $filename;
			$filename_small = $article_id . '-' . $filename_clean[1] . '-s.' . $ext;
			$filename_medium = $article_id . '-' . $filename_clean[1] . '-m.' . $ext;
			$filename_original = $article_id . '-' . $filename_clean[1] . '-o.' . $ext;
			list($photo_width, $photo_height) = getimagesize($source);
			$sq = new phMagick($source, $_SERVER['DOCUMENT_ROOT'].'/images/blog/'.$filename_small);
			$sq->resizeExactly(150,150);
			if ($photo_width > 800){
				$md = new phMagick($source, $_SERVER['DOCUMENT_ROOT'].'/images/blog/'.$filename_medium);
				$md->resize(800, 0);
			}else{
				copy($source, $_SERVER['DOCUMENT_ROOT'].'/images/blog/'.$filename_medium);
			}
			copy($source, $_SERVER['DOCUMENT_ROOT'].'/images/blog/'.$filename_original);
			unlink($source);
			$photo_input = array(
				'photo_url_small' => '/images/blog/' . $filename_small,
				'photo_url_medium' => '/images/blog/' . $filename_medium,
				'photo_url_original' => '/images/blog/' . $filename_original,
			);
		}
		db_update("articles", $photo_input, "_id='".$article_id."'");
  }

	$GLOBALS['app']->render_json(array(
		'success' => true,
		'article_id' => $article_id,
		'photo_input' => $photo_input
	));

});





$app->post('/admin/blog/delete', function(){

	$article = db_find("articles", "_id='".$_POST['_id']."'");

	if ($article['data'][0]['photo_url_small']){
		unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_small']);
		unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_medium']);
		unlink($_SERVER['DOCUMENT_ROOT'] . $article['data'][0]['photo_url_original']);
	}

	db_delete("articles", "_id='".$_POST['_id']."'");

	$GLOBALS['app']->render_json(array(
		'success' => true
	));

});





 ?>
