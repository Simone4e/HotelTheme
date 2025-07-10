 @props(['direction' => 'left', 'label' => ''])

 @php
     $sp = $direction === 'left' ? '<span class="sr-only">Prev</span>' : '<span class="sr-only">Next</span>';
     $class =
         $direction === 'left'
             ? 'absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none'
             : 'absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none';
 @endphp

 <button {{ $attributes->merge([
     'class' => $class,
 ]) }}>
     <span
         class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary/30 group-hover:bg-primary/50  group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="{{ $direction === 'left' ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7' }}" />
         </svg>
         {!! $sp !!}
     </span>
 </button>
