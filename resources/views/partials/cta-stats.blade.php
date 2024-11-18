<h4>@field('title')</h4>
@hasfields('statistics')
@fields('statistics')
    <div>
        @hassub('statistic_data')
            <span class="stat">@sub('statistic_data')</span>
        @endsub
        @hassub('title')
            <span class="title">@sub('title')</span>
        @endsub
    </div>
@endfields
    </ul>
@endhasfields
<img src="@field('image', 'url')" alt="@field('image', 'alt')">