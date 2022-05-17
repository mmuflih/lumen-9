@extends('mail.layout')
@section('content')
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="576" bgcolor="#ffffff" style="border-radius:4px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding:10px 32px; text-align:left;">
                        <div style="line-height:24px"><span style="color: #333333;line-height:24px;font-family:Inter, Helvetica, Arial, sans-serif; font-size:16px;text-align:left;">
                                Hi {{$name}},<br>
                                Terima kasih telah mendaftar di friendchised.id.
                            </span></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection()