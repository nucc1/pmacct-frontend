pmacct frontend
===============

Quick and ugly statistics frontend for [pmacct](http://www.pmacct.net/) for my personal use. Needs 
some code cleanup and documentation. See [a screenshot](http://stuff.dan.cx/images/projects/pmacct/month.png)

If using Apache, ensure you have mod_rewrite installed, and AllowOverride set to "All" (or just copy the contents of .htaccess into your Apache configuration). For other web servers, you will have to convert the rewrite rule in .htaccess to your server's equivalent.

Requires php-gmp, php-mysql.

Please pay no heed to the number of commits. I'm using a git commit to push to test workflow, 
so i have to commit everything I need to test, resulting in inflated numbers of commits.  