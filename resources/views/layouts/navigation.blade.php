<nav x-data="{ open: false }" class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">

      <!-- Logo + Name -->
      <div class="flex items-center space-x-2">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-9 rounded-full">
          <span class="font-semibold text-gray-800 text-lg tracking-wide">Yayasan<span class="text-blue-600">Kita</span></span>
        </a>
      </div>

      <!-- Center Menu (Desktop Only) -->
      <div class="hidden md:flex items-center space-x-8 mx-auto">
        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('donation') }}" class="nav-link {{ request()->routeIs('donation') ? 'active' : '' }}">Donation</a>
        <a href="{{ url('/about') }}" class="nav-link {{ request()->is('about') ? 'active' : '' }}">About</a>
        <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>
      </div>

      <!-- Auth Section -->
      <div class="hidden md:flex items-center space-x-4">
        @auth
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:text-blue-700 transition">
                <img src="{{ asset('images/avatar-default.png') }}" class="h-6 w-6 rounded-full" alt="avatar">
                <span>{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </x-slot>

            <x-slot name="content">
              <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                                 onclick="event.preventDefault(); this.closest('form').submit();">
                  Log Out
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        @else
          <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-700 font-medium text-sm">Log in</a>
          <a href="{{ route('register') }}"
             class="px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700 shadow transition">
            Register
          </a>
        @endauth
      </div>

      <!-- Hamburger Button (Mobile) -->
      <div class="flex md:hidden items-center">
        <button @click="open = !open"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none transition">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>


  <!-- Mobile Menu -->
  <div 
    :class="{'flex': open, 'hidden': !open}" 
    class="hidden md:hidden flex-col items-center bg-white/95 backdrop-blur-md border-t border-gray-200 shadow-lg animate-fadeIn"  >
    <div class="flex flex-col items-center justify-center w-full px-6 py-5 space-y-3 text-center">

      <a href="{{ route('home') }}" class="mobile-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
      <a href="{{ route('donation') }}" class="mobile-link {{ request()->routeIs('donation') ? 'active' : '' }}">Donation</a>
      <a href="{{ url('/about') }}" class="mobile-link {{ request()->is('about') ? 'active' : '' }}">About</a>
      <a href="{{ url('/contact') }}" class="mobile-link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>

      <div class="border-t border-gray-200 my-3"></div>

      @auth
        <p class="text-sm text-gray-700 font-medium mb-1">{{ auth()->user()->name }}</p>
        <a href="{{ route('profile.edit') }}" class="mobile-link">Profile</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="mobile-link text-red-600 font-semibold w-full text-center">
            Log Out
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="mobile-link">Log in</a>
        <a href="{{ route('register') }}" class="mobile-link text-blue-600 font-semibold">Register</a>
      @endauth
    </div>
  </div>
</nav>

{{-- === Custom Navbar Styles === --}}
<style>
  .nav-link {
    @apply text-gray-700 font-medium text-sm relative transition-all duration-300;
  }
  .nav-link::after {
    content: '';
    @apply absolute left-0 -bottom-1 w-0 h-[2px] bg-blue-600 transition-all duration-300;
  }
  .nav-link:hover::after,
  .nav-link.active::after {
    @apply w-full;
  }
  .nav-link.active {
    @apply text-blue-600 font-semibold;
  }

  .mobile-link {
    @apply block text-gray-700 hover:text-blue-700 font-medium text-base py-2 transition;
  }
  .mobile-link.active {
    @apply text-blue-600 font-semibold;
  }
</style>
