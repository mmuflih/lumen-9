@extends('mail.layout')
@section('content')
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td width="576" bgcolor="#ffffff" style="border-radius:4px;">
      <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
          <td style="padding:10px 32px; text-align:left;">
            <div style="line-height:24px"><span style="color: #333333;line-height:24px;font-family:Inter, Helvetica, Arial, sans-serif; font-size:16px;text-align:left;">Hi {{$name}},<br>Untuk Melakukan Veifikasi Email. Silahkan masukkan kode OTP di bawah (hanya berlaku 5 menit).</span></div>
          </td>
        </tr>
        <tr>
          <td style="padding:10px 32px;">
            <div style="line-height:24px; text-align: center;"><span style="color: #333333;line-height:24px;font-family:Inter, Helvetica, Arial, sans-serif; font-size:24px;font-weight: 600; letter-spacing: 10px;">{{$code}}</span></div>
          </td>
        </tr>
        <tr>
          <td style="padding:10px 32px; text-align:left;">
            <div style="line-height:24px"><span style="color: #333333;line-height:24px;font-family:Inter, Helvetica, Arial, sans-serif; font-size:16px;text-align:left;">Harap jangan pernah membagikan kode ini kepada siapa pun. Terima kasih telah menggunakan layanan kami. </span></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
@endsection()