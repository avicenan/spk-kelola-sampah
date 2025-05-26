<li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#" onclick="toggleUserCard()"
        @if (config('adminlte.sidebar_collapse_remember')) data-enable-remember="true" @endif
        @if (!config('adminlte.sidebar_collapse_remember_no_transition')) data-no-transition-after-reload="false" @endif
        @if (config('adminlte.sidebar_collapse_auto_size')) data-auto-collapse-size="{{ config('adminlte.sidebar_collapse_auto_size') }}" @endif>
        <i class="fas fa-bars"></i>
        <span class="sr-only">{{ __('adminlte::adminlte.toggle_navigation') }}</span>
    </a>

    {{-- <div id="user-card" class="p-2 mt-6 user-panel"
        style="position: fixed; bottom: 0; right: 0; width: 250px; z-index: 9999;">
        <div class="bg-navy p-2 rounded-lg d-flex justify-content-between items-center shadow-lg">
            <p class="text-center p-0 m-0 my-auto"><u>Logged as {{ Auth::user()->name }}</u></p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger rounded-pill">Logout</button>
            </form>
        </div>
    </div> --}}
    <script>
        function toggleUserCard() {
            const userCard = document.querySelector('#user-card');
            const userCardIcon = document.querySelector('#user-card-icon');

            // Add transition styles
            userCard.style.transition = 'transform 0.3s ease-in-out';
            userCardIcon.style.transition = 'transform 0.3s ease-in-out';

            // Instead of d-none, use transform to slide
            if (userCard.style.transform !== 'translateX(-100%)') {
                userCard.style.transform = 'translateX(-100%)';
                userCardIcon.style.transform = 'translateX(0)';
                // Hide userCard after slide out
                setTimeout(() => {
                    userCard.classList.add('d-none');
                    userCardIcon.classList.remove('d-none');
                }, 300);
            } else {
                userCard.classList.remove('d-none');
                userCardIcon.classList.add('d-none');
                // Small delay to ensure d-none is removed before slide in
                setTimeout(() => {
                    userCard.style.transform = 'translateX(0)';
                    userCardIcon.style.transform = 'translateX(-100%)';
                }, 10);
            }
        }
    </script>
</li>
