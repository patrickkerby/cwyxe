@if (! empty($items))

  <nav
    aria-label="Breadcrumb"
    class="flex items-center py-2 -mx-2 leading-none"
    vocab="https://schema.org/"
    typeof="BreadcrumbList"
  >
    @foreach ($items as $item)
    
      @if (empty($item['url']) && $item['label'] == 'Our People' && is_singular('agent')) 
        <span class="current cursor-default">
          <a href="/our-people">
            {{ $item['label'] }}
          </a>
        </span> |

      @elseif (is_singular('post') && $loop->first)
        <span property="name">
            <a href="/">Home <span class="sr-only">{!! $item['label'] !!}</span></a>
        </span> 
        |
        <span class="current cursor-default">
          <a href="/insights">
            Insights
          </a>
        </span> |
      
      @elseif (empty($item['url']))
        <span class="current cursor-default">
          {{ $item['label'] }}
        </span>
      
      {{-- For Insights Articles, replace archive links with links to facet-filtered insights landing page --}}
      @elseif(is_singular('post'))

        @php
          $parts = explode('/', $item['url']);
          $url = '/insights/?_insights_topics=' . $parts[4];
        @endphp
      
        <span property="itemListElement" typeof="ListItem">
          <a
            property="item"
            typeof="WebPage"
            title="Go to {!! $item['label'] !!}."
            href="{{ $url }}"
          >
            <span property="name">
              @if ($loop->first)
                Home <span class="sr-only">{!! $item['label'] !!}</span>              
              @else
                {!! $item['label'] !!}
              @endif
            </span>
          </a>
          <meta property="position" content="{{ $loop->iteration }}">
        </span>
        @if (!$loop->last)|@endif
      @else
        <span property="itemListElement" typeof="ListItem">
          <a
            property="item"
            typeof="WebPage"
            title="Go to {!! $item['label'] !!}."
            href="{{ $item['url'] }}"
          >
            <span property="name">
              @if ($loop->first)
                Home <span class="sr-only">{!! $item['label'] !!}</span>              
              @else
                {!! $item['label'] !!}
              @endif
            </span>
          </a>
          <meta property="position" content="{{ $loop->iteration }}">
        </span>
        @if (!$loop->last)|@endif
      @endif
    @endforeach
  </nav>
@endif