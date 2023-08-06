<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="/dashboard">
              <i class="mdi menu-icon mdi-grid-large"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>

          @if(Auth::user()->hasRole('admin'))
            <li class="nav-item nav-category">Requests & Applications</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-request" aria-expanded="false" aria-controls="ui-request">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Approve</span>
                <i class="menu-arrow"></i> 
              </a>
              <div class="collapse" id="ui-request">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{ route('registration.index')}}">Registrations</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{ route('application.index')}}">Applications</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item nav-category">Course and Department</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-course" aria-expanded="false" aria-controls="ui-course">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Manage</span>
                <i class="menu-arrow"></i> 
              </a>
              <div class="collapse" id="ui-course">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{ route('course.index')}}">Courses</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{ route('department.index')}}">Departments</a></li>
                </ul>
              </div>
            </li>
          @endif
          
          @if(Auth::user()->hasRole('teacher'))
            <li class="nav-item">
              <a class="nav-link" href="/apply/teacher">
                <i class="mdi menu-icon mdi-file"></i>
                <span class="menu-title">Course Application</span>
              </a>
            </li>
          @endif

          @if(Auth::user()->hasRole('student'))
            <li class="nav-item">
              <a class="nav-link" href="/apply/student">
                <i class="mdi menu-icon mdi-file"></i>
                <span class="menu-title">Join Course</span>
              </a>
            </li>
          @endif
      </nav>