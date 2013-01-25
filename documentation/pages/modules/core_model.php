<h2>Core Model Module</h2>
<p class="panel">This module is simply a model that normally would get autoloaded.  It contains the site configuration array.  This is different from the way a typical CMS stores site information.  They normally store it in the database and give the admin users a graphical interface to set site information such as the site name, slogan, description, etc.  Since this is not a CMS and is not intended for use by non Codeigniter developers CI Starter simply stores the information in a model.  If you have ever played around with Yii you may have noticed they do the same thing.  All of the modules that ship with CI Starter use this module to get site information.</p>

<p>Here is a condensed version of the code without all the comments:</p>

<pre class="prettyprint">
class Core_Model extends CI_Model {<br/>
&nbsp;&nbsp;public function site_info() {<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$meta_description = 'A Codeigniter boilerplate.';<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$data = array(<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'site_name' => 'CI Starter',<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'site_description' => $meta_description,<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'template_url' => base_url () . 'assets/templates/default_template/',<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'css_url' => base_url () . 'assets/templates/default_template/stylesheets/',<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'js_url' => base_url () . 'assets/templates/default_template/javascripts/',<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'img_url' => base_url () . 'assets/templates/default_template/images/',<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'asset_path' => FCPATH . 'assets/templates/default_template',<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'scripts' => array('custom.js'),<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'stylesheets' => array('custom.css'),<br/>
&nbsp;&nbsp;&nbsp;&nbsp;);<br/>
&nbsp;&nbsp;&nbsp;&nbsp;return $data;<br/>
&nbsp;&nbsp;}<br/>
}
</pre>

<p class="code-after">The way I use this module is to first set a protected static property in the controller:</p>

<code class="prettyprint">protected static $data;</code>

<p class="code-after">Then in the class constructor set the property as the site_info() $data array:</p>

<code class="prettyprint">
  self::$data = $this->core_model->site_info();
</code>

<p class="code-after">Remember we are autoloading this module so there was no need to load it.  Now to access any of the data within the controller just do this.  We will use the site_name as an example:</p>

<code class="prettyprint">
  $site_name = self::$data['site_name'];
</code>

<p class="code-after">Now if you want to pass the data array to a view:</p>

<code class="prettyprint">
  $this->load->view('view_name', self::$data);
</code>

<p class="code-after">Or to the Template module:</p>

<code class="prettyprint">
  echo Modules::run('template/name_of_the_template', self::$data);
</code>

<p class="code-after">The Core Model module passes some url and path values:</p>

<code class="prettyprint">
  'template_url' => base_url () . 'assets/templates/default_template/',<br/>
  'css_url' => base_url () . 'assets/templates/default_template/stylesheets/',<br/>
  'js_url' => base_url () . 'assets/templates/default_template/javascripts/',<br/>
  'img_url' => base_url () . 'assets/templates/default_template/images/',<br/>
  'asset_path' => FCPATH . 'assets/templates/default_template',
</code>

<p class="code-after">So to echo something out in a view you can just do something like this:</p>

<code class="prettyprint">
  echo $js_url
</code>

<p class="code-after">The following two values are used a little differently:</p>

<code class="prettyprint">
  'scripts' => array('custom.js'),<br/>
  'stylesheets' => array('custom.css'),
</code>

<p class="code-after">The Asset Loader module, when run (normally in view or template), automatically looks for the <code class="prettyprint">$scripts</code> and <code class="prettyprint">$stylesheets</code> variables so the Core Model module passes those along here and by default "custom.css" and "custom.js" are set.  You can add any files to the arrays and they will be loaded anywhere that the Asset Loader module is being run.  Additionally you can add to the array from a controller which is the primary use of the Asset Loader module.  More on that in the Asset Loader module section.</p>