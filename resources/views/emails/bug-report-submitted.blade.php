@component('mail::message')
# New bug report

**Reported by:** {{ $bugReport->reporter_email }}  
**Station:** {{ strtoupper((string) $bugReport->location) }}  
**Title:** {{ $bugReport->title }}

{{ \Illuminate\Support\Str::limit(trim(strip_tags($bugReport->description)), 500) }}

@component('mail::button', ['url' => $reviewUrl])
Review bug report
@endcomponent

Reported from: {{ $bugReport->page_url ?: 'Page not supplied' }}
@endcomponent
