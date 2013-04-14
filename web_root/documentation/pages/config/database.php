<h2>Database</h2>
<p class="panel">When you run the User, Grocery Crud Demo, or Doctrine 2 Demo modules CoreModule will automatically start trying to connect to the database.  So one of the first steps to take is to setup the database and configure the database config file.</p>

<h4>Database config file</h4>
<code class="prettyprint">
  $db['default']['hostname'] = 'localhost';<br/>
  $db['default']['username'] = 'CoreModule';<br/>
  $db['default']['password'] = 'CoreModule';<br/>
  $db['default']['database'] = 'CoreModule';<br/>
  $db['default']['dbdriver'] = 'mysql';<br/>
</code>
<p class="code-after">These are the out of the box database settings in CoreModule.  They have to match your database settings.</p>

<h4>Schema</h4>
<p>Here is the sql file that ships with with CoreModule.  Import into your database then delete the directory.</p>
<ul>
  <li>assets/schema/CoreModule.sql</li>
</ul>

<h4>Autoloading</h4>
<code class="prettyprint">$autoload['libraries'] = array('session');</code>
<p class="code-after">As you can see CoreModule does not autoload the database library by default.  If you are going to use Doctrine 2 then add 'doctrine' to the array although you can always do it manually in the controller.  Add 'database' to autoload the ultra fast Codeigniter query builder.  Grocery Crud does not require you to load the database library at all, it does it all on its own.</p>