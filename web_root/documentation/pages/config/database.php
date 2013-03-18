<h2>Database</h2>
<p class="panel">When you run the User, Grocery Crud Demo, or Doctrine 2 Demo modules CI Starter will automatically start trying to connect to the database.  So one of the first steps to take is to setup the database and configure the database config file.</p>

<h4>Database config file</h4>
<code class="prettyprint">
  $db['default']['hostname'] = 'localhost';<br/>
  $db['default']['username'] = 'ci_starter';<br/>
  $db['default']['password'] = 'ci_starter';<br/>
  $db['default']['database'] = 'ci_starter';<br/>
  $db['default']['dbdriver'] = 'mysql';<br/>
</code>
<p class="code-after">These are the out of the box database settings in CI Starter.  They have to match your database settings.</p>

<h4>Schema</h4>
<p>Here is the sql file that ships with with CI Starter.  Import into your database then delete the directory.</p>
<ul>
  <li>assets/schema/ci_starter.sql</li>
</ul>

<h4>Autoloading</h4>
<code class="prettyprint">$autoload['libraries'] = array('session');</code>
<p class="code-after">As you can see CI Starter does not autoload the database library by default.  If you are going to use Doctrine 2 then add 'doctrine' to the array although you can always do it manually in the controller.  Add 'database' to autoload the ultra fast Codeigniter query builder.  Grocery Crud does not require you to load the database library at all, it does it all on its own.</p>