# GZ NEWS

> Allow osclass admins, to publish news in them site.

## How to install

+ Enable module.
+ Add a link to your theme, that point to http://yoursite.com/news
+ Modify your .htaccess to enable mod rewrites (friendly url)
<pre>
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
	
	#Modified file for News
	RewriteRule ^news/read/([^/]*)/([^/]*)\.html$ /oc-content/plugins/gz_news/pages/item.php?i=$1&title=$2 [L]
	RewriteRule ^news/$ /oc-content/plugins/gz_news/pages/index.php [L]
	RewriteRule ^news$ /oc-content/plugins/gz_news/pages/index.php [L]

	#Osclass
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
	
</IfModule>

</pre>
