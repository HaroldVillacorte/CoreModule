<h2>The .gitignore file</h2>
<p class="panel">The CI Starter application starter will also have a .gitignore file in there.  It is the default file that Github puts in there when you open a repository and asks if you want to initialize with a Codeigniter .gitignore file.  I chose yes and added both Netbeans and Eclipse project files to it.</p>

<code class="prettyprint">
*/config/development<br/>
*/logs/log-*.php<br/>
*/logs/!index.html<br/>
*/cache/*<br/>
*/cache/!index.html<br/>
nbproject<br/>
.settings<br/>
.buildpath<br/>
.project<br/>
</code>
