<nav class="navbar navbar-expand-md navbar-dark bg-custom-gray shadow-sm">


<div class="header-box px-3 fs-4 my-1 me-5" id="profile">
            <a href="{{ route('dashboard', 'default') }}" class="text-decoration-none">
                <span><img src="/images/logoo.png" alt="" width="40" height="40" class="me-3"></span>
                <span><img src="/images/logooo.png" id="app-name" alt="" width="180" height="38" class=" pb-1"></span>
            </a>
        </div>

            <button class="sidebar-toggler" onclick="toggle()" type="button">
                    <span class="sidebar-toggler-icon"></span>
                </button>



                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <a class="text-decoration-none text-gray fs-6 mx-3" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Se déconnecter') }}&nbsp;&nbsp;<i class="fa-solid fa-right-from-bracket"></i>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>

                                    <!-- @if (auth()->user()->roles()->where('role','admin')->exists())
                                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                            {{ __('List des utilisateurs') }}
                                        </a>
                                    @endif -->

                        @endguest
                    </ul>
                </div>
        </nav>
