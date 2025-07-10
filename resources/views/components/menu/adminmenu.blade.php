 <x-navlink href="{{ route('admin.rooms.index') }}" :active="request()->routeIs('admin.rooms.*')">Rooms</x-navlink>
 <x-navlink href="{{ route('admin.reservations.index') }}" :active="request()->routeIs('admin.reservations.*')">Reservations</x-navlink>
 <div class="border-t-2 lg:border-t-0 lg:border-l-2 border-white lg:pl-4 lg:pt-0 pt-4">
     <form method="POST" action="{{ route('logout') }}">
         @csrf
         @method('DELETE')
         <button type="submit"
             class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-white  hover:bg-gray-400/50  bg-none cursor-pointer">
             Logout
         </button>
     </form>
 </div>
