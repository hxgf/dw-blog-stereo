<?php

// ----------- install process -----------

// 0. install the files

// 1. create new table in mysql
  // CREATE TABLE `articles` ( `id` INT(255) NOT NULL AUTO_INCREMENT , `_id` VARCHAR(255) NULL DEFAULT NULL , `user_id` VARCHAR(255) NULL DEFAULT NULL , `title` TEXT NULL DEFAULT NULL , `url_slug` VARCHAR(255) NULL DEFAULT NULL , `tags` TEXT NULL DEFAULT NULL , `date_published` INT(255) NULL DEFAULT NULL , `summary` MEDIUMTEXT NULL DEFAULT NULL , `body` LONGTEXT NULL DEFAULT NULL , `photo_url_small` TEXT NULL DEFAULT NULL , `photo_url_medium` TEXT NULL DEFAULT NULL , `photo_url_original` TEXT NULL DEFAULT NULL , `photo_caption` TEXT NULL DEFAULT NULL , `published` VARCHAR(1) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

// 2. make a new folder
  // mkdir images/blog
  // chmod 755 images/blog

// 3. add links to controllers in /controllers/_routes.php
	// include('admin/blog.php');
	// include('blog.php');

// 4. add links to the admin screens in the admin header (/pages/_layouts/admin.hbs)
	// <a class="link {{#if current_blog}}white{{else}}white-70{{/if}} hover-white dib mr4" href="/admin/blog" title="Blog">Blog</a>

?>
