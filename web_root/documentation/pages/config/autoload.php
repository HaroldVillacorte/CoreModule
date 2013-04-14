<h2>Autoload</h2>
<p class="panel">Only three things are autoloaded by default in CoreModule.</p>

<h4>Session</h4>
<code class="prettyprint">$autoload['libraries'] = array('session');</code>
<p class="code-after">
The Messages, Dotrine 2 Demo, and User modules all make use of the session library.
</p>

<h4>Url</h4>
 <code class="prettyprint">$autoload['helper'] = array('url');</code>
<p class="code-after">
The Url helper is used throughout most of the application.
</p>

<h4>Core Model</h4>
<code class="prettyprint">$autoload['model'] = array('core_model/core_model');</code>
<p class="code-after">
The core_model module consists of just one model which is needed just about everywhere.  Notice that when you autoload a modular model you have to specify the module directory as well.
</p>