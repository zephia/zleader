<!-- Sidebar Menu -->
<ul class="sidebar-menu">
  <li class="header">SISTEMA</li>
  <!-- Optionally, you can add icons to the links -->
  <li class="{{ Route::getCurrentRoute()->getActionName() == 'Zephia\\ZLeader\\Http\\Controllers\\DashboardController@index' ? 'active' : '' }}"><a href="{{ action('\Zephia\ZLeader\Http\Controllers\DashboardController@index') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
  <li class="{{ Route::getCurrentRoute()->getActionName() == 'Zephia\\ZLeader\\Http\\Controllers\\LeadController@index' ? 'active' : '' }}"><a href="{{ action('\Zephia\ZLeader\Http\Controllers\LeadController@index') }}"><i class="fa fa-crosshairs"></i> <span>Leads</span></a></li>
  @if(app('user') !== false)
    @if(app('user')->inRole(app('admins_role')))
      <li class="{{ Route::getCurrentRoute()->getActionName() == 'Zephia\\ZLeader\\Http\\Controllers\\FormController@index' ? 'active' : '' }}"><a href="{{ action('\Zephia\ZLeader\Http\Controllers\FormController@index') }}"><i class="fa fa-list-alt"></i> <span>Formularios</span></a></li>
      <li class="{{ Route::getCurrentRoute()->getActionName() == 'Zephia\\ZLeader\\Http\\Controllers\\CompanyController@index' ? 'active' : '' }}"><a href="{{ action('\Zephia\ZLeader\Http\Controllers\CompanyController@index') }}"><i class="fa fa-building"></i> <span>Empresas</span></a></li>
      <li class="{{ Route::getCurrentRoute()->getActionName() == 'Zephia\\ZLeader\\Http\\Controllers\\AreaController@index' ? 'active' : '' }}"><a href="{{ action('\Zephia\ZLeader\Http\Controllers\AreaController@index') }}"><i class="fa fa-sitemap"></i> <span>Areas</span></a></li>
      <li class="{{ Route::getCurrentRoute()->getActionName() == 'Zephia\\ZLeader\\Http\\Controllers\\FieldController@index' ? 'active' : '' }}"><a href="{{ action('\Zephia\ZLeader\Http\Controllers\FieldController@index') }}"><i class="fa fa-list-alt"></i> <span>Campos</span></a></li>
  <!--<li class="treeview">
    <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li><a href="#">Link in level 2</a></li>
      <li><a href="#">Link in level 2</a></li>
    </ul>
  </li> -->
      <li class="header">OPCIONES</li>
      <li><a href="#"><i class="fa fa-gears"></i> <span>Configuración</span></a></li>
    @endif
  @endif
</ul>
<!-- /.sidebar-menu -->