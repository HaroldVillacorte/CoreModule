<h2>Config File</h2>
<p class="panel">CI Starter comes optimally pre-configured but a few things you have to set up for your environment.  Here is a list of the changes CI Starter has made to the config file.</p>

<h4>Base url</h4>
<code class="prettyprint">$config['base_url']	= 'http://localhost/CI_Starter/';</code>
<p class="code-after">You need to set this to the base url of your application.  From my experience if this is left blank it can lead to buggy behavior.</p>

<h4>Index file</h4>
<code class="prettyprint">$config['index_page'] = 'index.php';</code>
<p class="code-after">This comes preset as index.php.  Just like the base url setting this should not be left blank.</p>

<h4>Encryption key</h4>
<code class="prettyprint">$config['encryption_key'] = 'superSecretEncrytionKey';</code>
<p class="code-after">This needs to be set in order to use the session class and to encrypt and decrypt data.</p>

<h4>Session variables</h4>
<code class="prettyprint">$config['sess_use_database']	= TRUE;</code>
<p class="code-after">This has been set to true in order to store sessions in the database.</p>

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
