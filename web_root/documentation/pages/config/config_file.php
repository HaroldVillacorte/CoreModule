<h2>Config File</h2>
<p class="panel">Make sure to set the config file to match your environment.</p>

<h4>Base url</h4>
<code class="prettyprint">$config['base_url']	= 'http://CoreModule/';</code>
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
