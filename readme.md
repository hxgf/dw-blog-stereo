## Darkwave Blog Pattern
### Server Controllers (Stereo) - v0.0.1 (MIT)

Darkwave documentation: [http://darkwave.ltd/](http://darkwave.ltd/)

-------

### Installation

1\. Copy the files from this repository the corresponding directories in your project.

2\. Create a new table in your database
```
CREATE TABLE `articles` ( `id` INT(255) NOT NULL AUTO_INCREMENT , `_id` VARCHAR(255) NULL DEFAULT NULL , `user_id` VARCHAR(255) NULL DEFAULT NULL , `title` TEXT NULL DEFAULT NULL , `url_slug` VARCHAR(255) NULL DEFAULT NULL , `tags` TEXT NULL DEFAULT NULL , `date_published` INT(255) NULL DEFAULT NULL , `summary` MEDIUMTEXT NULL DEFAULT NULL , `body` LONGTEXT NULL DEFAULT NULL , `photo_url_small` TEXT NULL DEFAULT NULL , `photo_url_medium` TEXT NULL DEFAULT NULL , `photo_url_original` TEXT NULL DEFAULT NULL , `photo_caption` TEXT NULL DEFAULT NULL , `published` VARCHAR(1) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
```

3\. Make a new folder for the images
```
mkdir images/blog
chmod 755 images/blog
```

4\. Include the controllers in your project: */controllers/_routes.php*
```
include('admin/blog.php');
include('blog.php');
```

5\. Add links to the admin screens: */pages/_layouts/admin.hbs*
```
<a class="link {{#if current_blog}}white{{else}}white-70{{/if}} hover-white dib mr4" href="/admin/blog" title="Blog">Blog</a>
```
