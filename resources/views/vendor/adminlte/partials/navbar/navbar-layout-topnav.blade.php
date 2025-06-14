@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav
    class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand-md') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    <div class="{{ config('adminlte.classes_topnav_container', 'container') }}">

        {{-- Navbar brand logo --}}
        @if (config('adminlte.logo_img_xl'))
            @include('adminlte::partials.common.brand-logo-xl')
        @else
            @include('adminlte::partials.common.brand-logo-xs')
        @endif

        {{-- Navbar toggler button --}}
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar collapsible menu --}}
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            {{-- Navbar left links --}}
            <ul class="nav navbar-nav">
                {{-- Configured left links --}}
                @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

                {{-- Custom left links --}}
                @yield('content_top_nav_left')
            </ul>
        </div>

        {{-- Navbar right links --}}
        <ul class="navbar-nav ml-auto order-1 order-md-3 navbar-no-expand">
            {{-- Custom right links --}}
            @yield('content_top_nav_right')

            {{-- Configured right links --}}
            @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

            {{-- User menu link --}}
            @if (Auth::user())
                @if (config('adminlte.usermenu_enabled'))
                    @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
                @else
                    @include('adminlte::partials.navbar.menu-item-logout-link')
                @endif
            @endif
            {{-- User Info Modal --}}
            <div class="modal fade" id="userInfoModal" tabindex="-1" role="dialog"
                aria-labelledby="userInfoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="z-index: 1050;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right sidebar toggler link --}}
            @if ($layoutHelper->isRightSidebarEnabled())
                @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
            @endif
        </ul>

    </div>

</nav>
