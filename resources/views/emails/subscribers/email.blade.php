@php
    $username = $subscriber->name;
    $email = $subscriber->email;
    $body = str_replace('[username]', $username, $body);
    $body = str_replace('[userEmail]', $email, $body);
@endphp
<x-mail::message :subscriber="$subscriber">
    {!! $body !!}
</x-mail::message>
