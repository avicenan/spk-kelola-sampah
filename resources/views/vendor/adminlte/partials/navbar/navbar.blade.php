@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav
    class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')

    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        {{-- @if (Auth::user())
            @if (config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif --}}

        <li class="user-footer">
            <a class="btn btn-default btn-flat float-right btn-block" href="#" id="userInfoBtn"
                style="background-color: #007bff; color: white;">
                <i class="fa fa-fw fa-user text-white"></i>
                {{ Auth::user()->name }}
            </a>
        </li>

        {{-- User Info Modal --}}
        <div class="modal" id="userInfoModal" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userInfoModalLabel">Informasi Pengguna</h5>
                        <button type="button" class="close" id="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="user-info">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold mb-1">Name</label>
                                <div class="form-control bg-light">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="info-item mb-3">
                                <label class="font-weight-bold mb-1">Email</label>
                                <div class="form-control bg-light">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="info-item">
                                <label class="font-weight-bold mb-1">Role</label>
                                <div class="form-control bg-light">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                        <button type="button" class="btn btn-secondary" id="closeModalBtn">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('userInfoModal');
                const btn = document.getElementById('userInfoBtn');
                const closeBtn = document.getElementById('closeModal');
                const closeModalBtn = document.getElementById('closeModalBtn');

                btn.onclick = function() {
                    modal.style.display = "block";
                    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
                    modal.style.backgroundColor = "rgba(0,0,0,0.4)";
                }

                function closeModal() {
                    modal.style.display = "none";
                    document.body.style.backgroundColor = "";
                }

                closeBtn.onclick = closeModal;
                closeModalBtn.onclick = closeModal;

                window.onclick = function(event) {
                    if (event.target == modal) {
                        closeModal();
                    }
                }
            });
        </script>

        {{-- Right sidebar toggler link --}}
        @if ($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
