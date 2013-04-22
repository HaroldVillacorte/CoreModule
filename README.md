CoreModule
==========

<h4>Overview</h4>
<p class="panel">CoreModule is an application container used for rapid and secure PHP development built on top of the Codeigniter framework.</p>

<h4>What does CoreModule offer?</h4>
<ul>
  <li>Modularity.  CoreModule uses HMVC and offers a module management system that incorporates the templating and user systems.</a></li>
  <li>Templating.  A simple and fast templating system that extends the Codeigniter parsing engine.</li>
  <li>Menu navigation system.</li>
  <li>User system.  A user authentication and management system.</li>
  <li>Email system that integrates PHPMailer with Codeigniter.</li>
</ul>

<h2>File Structure</h2>

<p class="panel">CoreModule separates the the web accessible web root folder from the system and application directories for improved security.</p>

<h4>Modules</h4>

<p>These are the eight modules that ship with CoreModule.</p>

<ul>
  <li>application/modules/core_admin_front</li>
  <li>application/modules/core_email</li>
  <li>application/modules/core_install</li>
  <li>application/modules/core_menu</li>
  <li>application/modules/demo_messages</li>
  <li>application/modules/demo_module</li>
  <li>application/modules/demo_template</li>
  <li>application/modules/core_user</li>
</ul>

<h4>APPCACHE</h4>

<p>CoreModule utilizes database caching.</p>

<ul>
  <li>application/APPCACHE/DB</li>
</ul>

<h4>Templates</h4>

<p>Templates are kept in the views folder.</p>

<ul>
  <li>application/views/admin_template</li>
  <li>application/views/demo_template</li>
</ul>


<h4>Web root</h4>

<p>The web root folder contains the index.php file, the asset cache, and the CoreModule and Codeigniter documentation files.</p>

<ul>
  <li>web_root/asset_cache</li>
  <li>web_root/documentation</li>
  <li>web_root/user_guide</li>
</ul>

<h2>Autoload</h2>
<p class="panel">Just a few files are autoloaded by default by CoreModule.</p>

<h4>Libraries</h4>
<code class="prettyprint">$autoload['libraries'] = array('session', 'database');</code>

<h4>Helpers</h4>
 <code class="prettyprint">$autoload['helper'] = array('url', 'core_module_helper');</code>

<h4>Models</h4>
<code class="prettyprint">$autoload['model'] = array('core_module/core_module_model');</code>

<h2>Config File</h2>
<p class="panel">Make sure to set the config file to match your environment.</p>

<h4>Base url</h4>
<code class="prettyprint">$config['base_url']  = 'http://CoreModule/';</code>
<p class="code-after">You need to set this to the base url of your application.</p>

<h4>Index file</h4>
<code class="prettyprint">$config['index_page'] = '';</code>
<p class="code-after">This comes preset as empty since there is an .htaccess file to eliminate the index.php from the url.</p>

<h4>Encryption key</h4>
<code class="prettyprint">$config['encryption_key'] = 'superSecretEncrytionKey';</code>
<p class="code-after">This needs to be set in order to use the session class and to encrypt and decrypt data.</p>

<h4>Session variables</h4>
<code class="prettyprint">$config['sess_use_database']	= TRUE;</code>
<p class="code-after">This is will be required to be set to TRUE during installation.</p>

<h4>Global XSS filtering</h4>
<code class="prettyprint">$config['global_xss_filtering'] = TRUE;</code>
<p class="code-after">This has been set to true to help prevent cross site scripting attacks.</p>

<h4>Cross Site Request Forgery</h4>
<code class="prettyprint">$config['csrf_protection'] = TRUE;</code>
<p class="code-after">It is strongly recommended to enable CSRF protection if you are accepting user data.</p>

<h4>HMVC</h4>
<code class="prettyprint">
/*<br/>
|--------------------------------------------------------------------------<br/>
| HMVC<br/>
|--------------------------------------------------------------------------<br/>
|<br/>
| Set the path to the modules directory<br/>
|<br/>
*/<br/>
$config['modules_locations'] = array(<br/>
  &nbsp;&nbsp; APPPATH.'modules/' => '../modules/',<br/>
);
</code>
<p class="code-after">The config variable required to tell Codeigniter the location of HMVC modules.</p>
