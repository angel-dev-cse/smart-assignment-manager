<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="/dashboard">
            <img src="{{ asset('startheme/images/logo.png') }}" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="/dashboard">
            <img src="{{ asset('startheme/images/logo.png') }}" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top"> 
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Good {{ $greeting }} , <span class="text-black fw-bold">{{ $user->name }}</span></h1>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown"> 
            <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="icon-bubble"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="countDropdown" style="max-height: 15rem; width: 20rem; overflow-y: auto;">
              <a class="dropdown-item py-3">
                <span class="mdi mdi-24px mdi-wechat mr-3"></span>
                <p class="mb-0 font-weight-medium float-left">You have {{ $chats->count() }} active chats</p>
              </a>
              <div class="dropdown-divider"></div>
              @foreach($chats as $chat)
                <a href="#" class="open-chat-popup dropdown-item preview-item" data-recipient="{{$chat->recipient()->id}}">
                  <div class="preview-thumbnail">
                    <img src="{{ asset('startheme/images/faces/face10.jpg') }}" alt="image" class="img-sm profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow py-2">
                    <p class="preview-subject ellipsis font-weight-medium text-dark" style="overflow:visible">{{$chat->recipient()->name}}</p>
                    <p class="small-text text-dark m-0">{{$chat->messages->last()->content ?? "No messages yet!"}}</p>
                  </div>
                </a>
              @endforeach
            </div>
          </li>

          <li class="nav-item dropdown"> 
            <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="icon-bell"></i>
              @if ($notificationCount>0)
                <span class="count">{{ $notificationCount }}</span>
              @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="countDropdown" style="max-height: 15rem; overflow-y: auto;">
              <a class="dropdown-item py-3">
                <span class="mdi mdi-24px mdi-bell-outline  mr-3"></span>
                <p class="mb-0 font-weight-medium float-left">You have {{ $notificationCount }} unread notifications</p>
              </a>
              <div class="dropdown-divider"></div>
              @foreach($notifications as $notification)
              <a href="{{ route('notification.show', ['notification' => $notification]) }}" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <img src="{{ asset('startheme/images/faces/face10.jpg') }}" alt="image" class="img-sm profile-pic">
                </div>
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis font-weight-medium text-dark" style="overflow:visible">{{ $notification->title }}</p>
                  <p class="small-text text-dark m-0">{{ $notification->description }}</p>
                  <p class="fw-light small-text mb-0">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
              </a>
              @endforeach
            </div>
          </li>

          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
            @if ($user->role === 'student')
              <img class="img-xs rounded-circle" src="{{ asset('startheme/images/student.png') }}" alt="Profile Image">
            @elseif ($user->role === 'teacher')
              <img class="img-xs rounded-circle" src="{{ asset('startheme/images/teacher.png') }}" alt="Profile Image">
            @else
              <img class="img-xs rounded-circle" src="{{ asset('startheme/images/admin.png') }}" alt="Profile Image">
            @endif
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle inline-block" src="{{ asset('startheme/images/faces/face8.jpg') }}" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold">{{ $user->name }}</p>
                <p class="fw-light text-muted mb-0">{{ $user->email }}</p>
              </div>

              <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('profileForm').submit();">
                <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i>
                My Profile
              </a>

              <form id="profileForm" class="form" method="POST" action="{{ route('profile.edit')}}">
                  @csrf
                  <input type="hidden" name="id" value="{{$user->id}}" />
              </form>

              <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('profileForm_admin').submit();">
                <i class="dropdown-item-icon mdi mdi-seat-recline-extra text-primary me-2"></i>
                Contact Admin
              </a>
              <form id="profileForm_admin" class="form" method="POST" action="{{ route('profile.show') }}">
                  @csrf
                  <input type="hidden" name="id" value="151" />
              </form>
              
              <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')" class="dropdown-item" 
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i> Sign Out
                </x-dropdown-link>
              </form>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>