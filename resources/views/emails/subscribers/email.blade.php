@php
    $username = $subscriber->name;
    $email = $subscriber->email;
    $body = str_replace('[username]', $username, $body);
    $body = str_replace('[userEmail]', $email, $body);
    $body = preg_replace_callback(
        '/\[button(?:\s+color="([^"]+)")?(?:\s+url="([^"]+)")?(?:\s+text="([^"]+)")?\]/',
        function ($matches) {
            $buttonColor = empty($matches[1]) ? 'success': $matches[1];
            $buttonUrl = empty($matches[2]) ? env('APP_URL'): $matches[2];
            $buttonText = empty($matches[3]) ? 'Click here': $matches[3];

            $buttonCode =
                '<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
            <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
            <td align="center">
            <table border="0" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
            <td>
            <a href="' .
                $buttonUrl .
                '" class="button button-' .
                $buttonColor .
                '" target="_blank" rel="noopener">' .
                $buttonText .
                '</a>
            </td>
            </tr>
            </table>
            </td>
            </tr>
            </table>
            </td>
            </tr>
            </table>';
            return $buttonCode;
        },
        $body,
    );
@endphp
<x-mail::message :subscriber="$subscriber">
    {!! $body !!}
</x-mail::message>
