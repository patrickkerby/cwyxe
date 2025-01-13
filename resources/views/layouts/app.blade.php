<script>
  document.body.classList.add = 'hidden';
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">



<a class="sr-only focus:not-sr-only" href="#main">
  {{ __('Skip to content') }}
</a>

  @include('sections.header')

  <main id="main" class="main">
    @yield('content')
  </main>

  {{-- @hasSection('sidebar')
    <aside class="sidebar">
      @yield('sidebar')
    </aside>
  @endif --}}

@include('sections.footer')
