<h2>Core Template</h2>
<p class="panel">The Core Template module uses the technique given on YouTube by David Connely.  First of all, it does not use a parser, just straight php.  It makes use of HMVC in a brilliantly simple way which simply just works and I have yet to find a better solution nor do I want one, this method is satisfactory.  I added to the Connely technique by breaking up templates into partials the having the template files pass the entire data array to the partials so variables work in the partials in the normal Codeigniter way.</p>

<p>Here is the video:</p>
<p><iframe width="420" height="315" src="http://www.youtube.com/embed/7F4PiyfwOtI" frameborder="0" allowfullscreen></iframe></p>
<p>If you are not seeing the video then you are probably not connected to the internet.</p>

<h4>The Core Template controller</h4>
<p>Here is the condensed code:</p>
<pre class="prettyprint">
class Core_Template extends MX_Controller {<br/>
&nbsp;&nbsp;public function slider_template($data) {<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$data['data_two'] = $data;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$this->load->view ('slider_template', $data);<br/>
&nbsp;&nbsp;}<br/>
&nbsp;&nbsp;public function default_template($data) {<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$data['data_two'] = $data;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;$this->load->view ('default_template', $data);<br/>
&nbsp;&nbsp;}<br/>
}
</pre>

<p class="code-after">So to call the template from a controller we do something like this:</p>
<code class="prettyprint">
  self::$data['module'] = 'name_of_the_controller';<br/>
  self::$data['view_file'] = 'name_of_the_view';<br/>
  echo Modules::run('core_template/default_template', self::$data);
</code>

<p class="code-after">In the template file we load the view that is passed from the controller that called the Core Template module:</p>
<code class="prettyprint">
  $this->load->view($module . '/' . $view_file);
</code>

<h4 class="code-after">Templates</h4>
<p>CI Starter ships with a template called default_template:</p>
<code class="prettyprint">
  $this->load->view($module . '/' . $view_file);
</code>


