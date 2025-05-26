<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}"
    style="background: linear-gradient(to bottom, #3F72AF,#112D4E, #000000);">

    {{-- Sidebar brand logo --}}
    @if (config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar ">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if (config('adminlte.sidebar_nav_animation_speed') != 300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if (!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

    <div id="user-card" class="p-2 mt-6 user-panel"
        style="position: fixed; bottom: 0; left: 0; width: 250px; z-index: 9999;">
        <div class="bg-navy p-2 rounded-lg d-flex justify-content-between items-center shadow-lg">
            <p class="text-center p-0 m-0 my-auto"><u>Logged as {{ Auth::user()->name }}</u></p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger rounded-pill">Logout</button>
            </form>
        </div>
    </div>

    <div id="user-card-icon" class="p-2 mt-6 user-panel d-none"
        style="position: fixed; bottom: 0; left: 0; width: 60px; z-index: 9999;">
        <div class=" p-2 rounded-lg d-flex justify-content-center shadow-lg">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger rounded-circle ml-2">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>



</aside>
