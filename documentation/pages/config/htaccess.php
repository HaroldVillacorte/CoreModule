<h2>The .htaccess file</h2>
<p class="panel">The only gripe I have with open source Codeigniter projects and Codeigniter in general is that the .htaccess file is not pre-configured and the documentation gives code that does not necessarily work.  I am assuming that every single developer reading this does not want that pesky index.php thing in the uri so CI Starter already comes with an .htaccess file.</p>

<code class="prettyprint">
DirectoryIndex index.php<br/>
RewriteEngine on<br/>
RewriteCond $1 !^(index\.php|images|css|js|robots\.txt|favicon\.ico)<br/>
RewriteCond %{REQUEST_FILENAME} !-f<br/>
RewriteCond %{REQUEST_FILENAME} !-d<br/>
RewriteRule ^(.*)$ ./index.php/$1 [QSA,L]<br/>
</code>
<p class="code-after">This works for me.  No need to copy this the file is already installed.  Good luck with it.</code>
