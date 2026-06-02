@props(['url'])
<tr>
    <td class="header" style="text-align: center; padding: 25px 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                {{-- Logo --}}
                <td style="vertical-align: middle; padding-right: 10px;">
                    <a href="{{ $url }}" style="text-decoration: none; display: inline-block;">
                        <img src="{{ config('app.logo', 'https://asset.loker.id/img/2017/08/7-PT-Liku-Telaga-150x150.png') }}"
                             alt="{{ config('app.name') }} Logo"
                             style="height: 50px; width: auto; display: block;">
                    </a>
                </td>

                {{-- App Name --}}
                <td style="vertical-align: middle;">
                    <a href="{{ $url }}" style="text-decoration: none; color: #333; font-size: 22px; font-weight: bold; line-height: 1; display: inline-block;">
                        {{ config('app.name') }}
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
