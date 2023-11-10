<aside class="main-sidebar sidebar-dark-primary elevation-3">
    <!-- Sidebar -->
    @php $route = Route::current()->getName(); @endphp
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        @if(auth()->user()->photo)
            <img src="/storage/{{ auth()->user()->photo }}" style="width: 2.5rem !important;height:2.5rem" class="img-circle elevation-2" alt="User Photo">
          @else
            <img src="{{asset('backend/dist/img/user-placeholder.jpg')}}" class="img-circle elevation-2" alt="User Photo">
          @endif
        </div>
        <div class="info">
          <a href="{{ route('backend.profile') }}" class="d-block">{{ auth()->user()->name }}</a>
          @if(auth()->user()->type == 1)
          <span class="badge badge-info">Super Admin</span>
          @elseif(auth()->user()->type == 2)
          <span class="badge badge-warning">Institute Admin</span>
          @elseif (auth()->user()->type == 3)
          <span class="badge badge-warning">Teacher</span>
          @elseif (auth()->user()->type == 4)
          <span class="badge badge-warning">Parent</span>
          @else
          <span class="badge badge-warning">Student</span>
          @endif
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('backend.dashboard') }}" class="nav-link {{ $route == 'backend.dashboard' ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          @can('isSuperAdmin')
          <li class="nav-item {{ ($route == 'backend.institute_admin' || $route == 'backend.other_users') ? 'menu-is-openning menu-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link {{ ($route == 'backend.institute_admin' || $route == 'backend.other_users') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('backend.institute_admin') }}" class="nav-link {{ $route == 'backend.institute_admin' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-angle-right"></i>
                  <p>Institute Admin</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('backend.other_users') }}" class="nav-link {{ $route == 'backend.other_users' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-angle-right"></i>
                  <p>Others</p>
                </a>
              </li>
            </ul>
          </li>
          @endcan
          @can('isAdmin')
          <li class="nav-item">
            <a href="{{ route('backend.institute_info') }}" class="nav-link {{ $route == 'backend.institute_info' ? 'active' : '' }}">
              <i class="nav-icon fas fa-school"></i>
              <p>
                Institute Info
              </p>
            </a>
          </li>
          @endcan
          @can('isTeacher')
          <li class="nav-item">
            <a href="{{ route('backend.teacher') }}" class="nav-link {{ $route == 'backend.teacher' ? 'active' : '' }}">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>
                Teachers
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ route('backend.class_teacher') }}" class="nav-link {{ $route == 'backend.class_teacher' ? 'active' : '' }}">
               <i class="nav-icon fas fa-tasks"></i>
              <p>
                Class Wise Teacher
              </p>
            </a>
          </li>
          @endcan


          @can('isParent')
          <li class="nav-item">
            <a href="{{ route('backend.parents') }}" class="nav-link {{ $route == 'backend.parents' ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Parents
              </p>
            </a>
          </li>
          @endcan

          <li class="nav-item">
            <a href="{{ route('backend.student') }}" class="nav-link {{ $route == 'backend.student' ? 'active' : '' }}">
              <i class='nav-icon fas fa-user-graduate'></i>
              <p>
                Students
              </p>
            </a>
          </li>

          @can('isAdmin')
          <li class="nav-item">
            <a href="{{ route('backend.attribute') }}" class="nav-link {{ $route == 'backend.attribute' ? 'active' : '' }}">
              <i class="nav-icon fas fa-sitemap fa-fw"></i>
              <p>
                Performance Attributes
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('backend.indicator') }}" class="nav-link {{ $route == 'backend.indicator' ? 'active' : '' }}">
              <i class="nav-icon fas fa-link"></i>
              <p>
                Evaluating Indicators
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('backend.marks-settings') }}" class="nav-link {{ $route == 'backend.marks-settings' ? 'active' : '' }}">
            <i class="nav-icon fas fa-tasks"></i>
              <p>
                Marks Settings
              </p>
            </a>
          </li>
          @endcan

          @can('isTeacher')
          <li class="nav-item">
            <a href="{{ route('backend.marks-entry') }}" class="nav-link {{ $route == 'backend.marks-entry' ? 'active' : '' }}">
            <i class="nav-icon fas fa-tasks"></i>
              <p>
                Marks Entry
              </p>
            </a>
          </li>
          @endcan

          <li class="nav-item">
            <a href="{{ route('backend.result-sheet') }}" class="nav-link {{ $route == 'backend.result-sheet' ? 'active' : '' }}">
            <i class="nav-icon fas fa-newspaper"></i>
              <p>
                Result Sheet
              </p>
            </a>
          </li>

        @can('isAdmin')
          <li class="nav-item {{ ($route == 'backend.class' || $route == 'backend.group' || $route == 'backend.section' || $route == 'backend.subject' || $route == 'backend.shift') ? 'menu-is-openning menu-open' : '' }}">
            <a href="javascript:void(0)" class="nav-link {{ ($route == 'backend.class' || $route == 'backend.group' || $route == 'backend.section' || $route == 'backend.subject' || $route == 'backend.shift') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Configuration
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('backend.class') }}" class="nav-link {{ $route == 'backend.class' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-angle-right"></i>
                  <p>Class</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('backend.group') }}" class="nav-link {{ $route == 'backend.group' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-angle-right"></i>
                  <p>Group</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('backend.section') }}" class="nav-link {{ $route == 'backend.section' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-angle-right"></i>
                  <p>Section</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('backend.subject') }}" class="nav-link {{ $route == 'backend.subject' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-angle-right"></i>
                  <p>Subject</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('backend.shift') }}" class="nav-link {{ $route == 'backend.shift' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-angle-right"></i>
                  <p>Shift</p>
                </a>
              </li>
            </ul>
          </li>
          @endcan

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>