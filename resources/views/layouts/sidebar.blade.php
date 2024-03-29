  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">COFAT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          {{-- <a href="#" class="d-block">Alexander Pierce</a> --}}
          <a href="#" class="d-block">{{Auth::user()->nom}} {{Auth::user()->prenom}}</a>
          <a class="d-block" href="{{ route('logout') }}"
           onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                         <i class="nav-icon fas fa-sign-out-alt text-danger"> Se déconnecter</i>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        </div>
      </div>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        
    </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @if (Auth::user()->role == "ADMIN")
            <li class="nav-item menu">
            <li class="nav-item">
              <router-link to='/users' class="nav-link">
                <i class="nav-icon fas fa-user-tie"></i>
                <p>
                  Gérer les personnels
                </p>
              </router-link>
            </li>  
            <li class="nav-item">
              <router-link to='/equipements' class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                  Equipements
                </p>
              </router-link>
            </li>       
            <li class="nav-item">
              <router-link to='/code_pannes' class="nav-link">
                <i class="nav-icon fas fa-file-code"></i>
                <p>
                  Code pannes
                </p>
              </router-link>
            </li>
            
            <li class="nav-item">
              <router-link to="/hist-admin" class="nav-link">
                <i class="nav-icon fas fa-history"></i>
                <p>
                  Interventions
                </p>
              </router-link>
            </li>
          @endif
          @if (Auth::user()->role == "HOTLINE")
          <li class="nav-item">
            <router-link to="/hist-hotline" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>
                Interventions
              </p>
            </router-link>
          </li>
          @endif
          @if (Auth::user()->role == "TECHNICIEN")
          <li class="nav-item">
            <router-link to="/hist-technicien" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>
                Interventions
              </p>
            </router-link>
          </li>
          @endif
          @if (Auth::user()->role == "PRODUCTION" || Auth::user()->role == "ADMIN")
          <li class="nav-item">
            <router-link to='/feedbacks' class="nav-link">
            <i class="nav-icon far fa-comment-alt"></i>
              <p>
                Feedback
              </p>
            </a>
          </li> 
          @endif
        </ul>
        
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>