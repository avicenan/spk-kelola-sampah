<style>
    .sidebar-search {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 10px;
    }

    .sidebar-search .form-control-sidebar {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        border-radius: 20px 0 0 20px;
    }

    .sidebar-search .form-control-sidebar::placeholder {
        color: rgba(255, 255, 255, 0.9);
    }

    .sidebar-search .form-control-sidebar:focus {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
        color: #fff;
        box-shadow: none;
    }

    .sidebar-search .btn-sidebar {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-left: none;
        color: #fff;
        border-radius: 0 20px 20px 0;
    }

    .sidebar-search .btn-sidebar:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .sidebar-search .input-group-append {
        margin-left: -1px;
    }
</style>

<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}"
    style="background: linear-gradient(to bottom, #3F72AF,#112D4E, #000000);">

    {{-- Sidebar brand logo --}}
    @if (config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar search bar --}}
    <div class="sidebar-search p-3">
        <form action="#" method="get" class="form-inline">
            <div class="input-group w-100">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search..." aria-label="Search"
                    id="sidebar-search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar" type="submit">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('sidebar-search');
        const menuItems = document.querySelectorAll('.nav-sidebar .nav-item');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();

            menuItems.forEach(function(item) {
                const text = item.textContent.toLowerCase();
                const isHeader = item.querySelector('.nav-header');

                if (isHeader) {
                    // Always show headers
                    item.style.display = '';
                    return;
                }

                if (searchTerm === '') {
                    // Show all items when search is empty
                    item.style.display = '';
                } else if (text.includes(searchTerm)) {
                    // Show items that match the search term
                    item.style.display = '';
                    item.style.backgroundColor = 'rgba(255,255,255,0.1)';
                } else {
                    // Hide items that don't match
                    item.style.display = 'none';
                }
            });
        });

        // Clear highlighting when search is cleared
        searchInput.addEventListener('blur', function() {
            if (this.value === '') {
                menuItems.forEach(function(item) {
                    item.style.backgroundColor = '';
                });
            }
        });
    });
</script>
