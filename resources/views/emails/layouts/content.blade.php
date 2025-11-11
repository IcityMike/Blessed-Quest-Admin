<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>blessed-quest</title>
</head>
<body style="margin:0px; padding:0px; font-family: 'Open Sans', sans-serif; font-size:14px; color:#333333; line-height:24px;">
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
	 @include('emails.layouts.header')
	<tr bgcolor="#f9f9f9">
		<td style="padding: 20px;">
			<div style="font-family: 'Open Sans', sans-serif; font-size: 14px; color: #000000; margin: 0px 0 20px 0;">
            	 @yield('emailContent')
            </div>
            
           
		</td>
	</tr>
	@include('emails.layouts.footer')
</table>
</body>
</html>