<!-- Header and Nav -->
<div>
  <nav class="top-bar">
    <ul>
      <li class="name"><h1><a href="{function="base_url()"}admin/">{$site_name} Admin</a></h1></li>
      <li class="divider hide-for-small"></li>
      <li class="toggle-topbar"><a href="#"></a></li>
    </ul>
    <section>
      <ul class="left">
          {if="$CI->uri->segment(1) == 'admin'"}
            <ul>
            {function="Modules::run('_core_menu', 1, 'admin_menu')"}
            </ul>
          {/if}
      </ul>
    </section>
  </nav>
</div>
<!-- End Header and Nav -->
