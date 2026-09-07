<x-mail::message>
# Reply from {{ config('app.name') }}

Hello {{ $websiteMessage->name }},

{{ $reply }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
