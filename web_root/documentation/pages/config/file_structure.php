<h2>File Structure</h2>

<p class="panel">Nothing about the Codeigniter directory structure is altered in CoreModule.  There are obviously some additional directories and files.  I will point them out according to the libraries and modules that have been added.</p>

<h4>HMVC</h4>
<ul>
  <li>application/third_party/MX</li>
  <li>application/core/MY_Loader.php</li>
  <li>application/third_party/MY_Router.php</li>
  <li>aplication/modules</li>
</ul>
<p>So obviously there is a modules folder now because HMVC allows us to organize our models, views, and controllers into modules.</p>

<h4>Grocery CRUD</h4>
<ul>
  <li>application/config/grocery_crud.php</li>
  <li>application/libraries/grocery_crud.php</li>
  <li>application/libraries/image_moo.php</li>
  <li>application/models/grocery_crud_model.php</li>
  <li>assets/grocery_crud</li>
  <li>assets/uploads</li>
</ul>
<p>The installation of the Grocery Crud library introduced the assets folder and it is now used as the system wide assets folder.</p>

<h4>Doctrine 2</h4>
<ul>
  <li>application/libraries/Doctrine.php</li>
  <li>application/libraries/Doctrine</li>
  <li>application/models/Entities</li>
  <li>application/models/Mappings</li>
  <li>application/models/Proxies</li>
</ul>
<p>This particular directory structure is not required by Doctrine but if you know Doctrine you probably already know that.  I decided to leave it exactly the way the online instructions have indicated so one can easily refer to it in case there are any issues.</p>

<h4>Modules</h4>
<ul>
  <li>application/modules/core_asset_loader</li>
  <li>application/modules/core_messages</li>
  <li>application/modules/core_model</li>
  <li>application/modules/core_template</li>
  <li>application/modules/demo_default_controller</li>
  <li>application/modules/demo_doctrine2</li>
  <li>application/modules/demo_grocery_crud</li>
  <li>application/modules/user</li>
</ul>
<p>So you may have noticed that I have written seven modules with this project.  The core modules are prefixed with "core_" with the exception of the User module.  This was necessary to avoid having users visit a url that reads like <em>http://example.com/core_user/profile/</em>.  The demo modules are fully working examples and can be edited for use with your project.</p>

<h4>Templates</h4>
<ul>
  <li>assets/templates/default_template</li>
  <li>assets/templates/default_template/images</li>
  <li>assets/templates/default_template/javascripts</li>
  <li>assets/templates/default_template/stylesheets</li>
</ul>
<p>Once again we are reusing the assets folder installed by Grocery Crud as our templates folder.</p>

<h4>Schema</h4>
<ul>
  <li>assets/schema</li>
</ul>
<p>This folder contains the database archive that ships with CoreModule.  Once you have it installed or if you are not going to use it this directory should be deleted.</p>

<h4>Documentation</h4>
<ul>
  <li>documentation/</li>
</ul>
<p>This is probably what you are reading right now.  But you can put it anywhere as it is a stand alone php application.  Jut a simple view loader.</p>

<h4>Gitignore</h4>
<ul>
  <li>.gitignore</li>
</ul>
<p>The default Codeigniter .gitignore file with the addition of both Netbeans and Eclipse project files.</p>

<h4>README.md</h4>
<ul>
  <li>README.md</li>
</ul>
<p>This file came from Github and is not part of the Codeigniter core.  This can be deleted or edited at will.  CoreModule will continue using this file because there is no reason not to.  It is not a source of documentation but a brief description with links.  It includes html which Github will render and output as the repository front page.</p>
