<h4>Overview</h4>
<p class="panel">
  First of all, CoreModule is not a content management system.  If a CMS is what is needed for the job then a CMS should be used.  CoreModule is a Codeigniter boilerplate intended to help kickstart application development.  If you are looking for a CMS I suggest <a href="https://www.pyrocms.com/" target="_blank">PyroCMS</a> or <a href="http://drupal.org" target="_blank">Drupal</a>.  Pyro is probably my favorite CMS.  If you have not tried it, please do.  You will not be disappointed.  And Drupal, well, is Drupal.  Powerful and flexible but not necessarily the easiest CMS to develop for.  And the technology it uses is getting dated.  Nonetheless it is a stable platform and has plenty of out of the box functionality and a thriving community.</p>

<h4>Why did I build CoreModule?</h4>
<p>There are other open source projects out there to do a similar job, most notably Bonfire.  Please take a look at <a href="http://cibonfire.com/" target="_blank">Bonfire</a> to evaluate if it is right for you before you proceed to use CoreModule.  I built CoreModule to have a boilerplate that does not alter the Codeigniter core in any way.  The directory structure and directory names have been left completely intact and all CoreModule components are completely modular.  This makes it easy to upgrade a project or any of it's components to the latest version.</p>

<p>Additionally I wanted both Grocery CRUD and Doctrine 2 to be readily available and pre-integrated with Codeigniter as options for any project.  These both are the only components that are not modules.  They have both been installed according to their own documented techniques.</p>

<h4>What does CoreModule offer?</h4>
<ul>
  <li>Modularity.  CoreModule uses HMVC in accordance with their documentation <a href="https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc" target="_blank">here.</a></li>
  <li>Templating.  A simple and fast templating system that does not use a parser.  It utilizes HMVC according to this brilliant YouTube video by David Connely   with just a    few simple enhancements:  <a href="http://www.youtube.com/watch?v=7F4PiyfwOtI" target="_blank">Video</a></li>
  <li>Foundation 3.  The templating system does not care what templates you use but the preinstalled one is pure Foundation 3.</li>
  <li>Asset Loader Module.  I wrote a very simple system to load javascript and css files through the controller so that each page only loads what is required    to render that page and nothing more.</li>
  <li>User system.  A pre-written user system that offers user CRUD, login and logout, and permissions.  Use of this system is completely optional.</li>
  <li>Grocery CRUD.  The Grocery CRUD library is installed and ready to use.  <a href="http://www.grocerycrud.com/" target="_blank">Grocery CRUD</a></li>
  <li>Doctrine 2.  Installed and ready using Joel Verhagen's technique <a href="http://www.joelverhagen.com/blog/2011/05/setting-up-codeigniter-2-with-doctrine-2-the-right-way/" target="_blank">here.</a></li>
<ul>