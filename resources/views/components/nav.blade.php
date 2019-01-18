<nav class="navbar navbar-expand-md navbar-dark bg-primary navbar-cje">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            Asamblea CJE
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                @if(Auth::user())
                    @if(Auth::user()->hasRole('credential_manager'))
                        <li>
                            <a class="nav-link {{ Route::currentRouteName() === 'credentials' ? 'active' : '' }}" href="{{ route('credentials') }}">
                                <i class="far fa-id-card-alt"></i> Acreditaciones
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasRole('vote_manager'))
                        <li>
                            <a class="nav-link {{ Route::currentRouteName() === 'amendments' ? 'active' : '' }}" href="{{ route('amendments') }}">
                                <i class="far fa-archive"></i> Votaciones
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="nav-link {{ Route::currentRouteName() === 'vote' ? 'active' : '' }}" href="{{ route('vote') }}">
                            <i class="far fa-hand-paper"></i> Votar
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ Route::currentRouteName() === 'my-votes' ? 'active' : '' }}" href="{{ route('my-votes') }}">
                            <i class="far fa-box-ballot"></i> Mis votos
                        </a>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Entrar') }}</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="far fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('my-votes') }}">
                                <i class="far fa-box-ballot"></i> {{ __('Mis votos') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="far fa-sign-out-alt"></i> {{ __('Cerrar sesión') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
  </nav>