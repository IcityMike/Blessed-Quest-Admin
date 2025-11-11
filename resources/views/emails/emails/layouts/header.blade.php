
<tr bgcolor="#000000">
	<td style="padding: 20px 20px 20px 20px;">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr valign="bottom">
				<td><img width="150" src="{{url(config('settings.site_logo_folder'))."/".$siteLogo}}"" alt="{{$siteTitle}}" style="display:block;">
				</td>

        <td style="text-align:right; vertical-align:middle;">
        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding-bottom:10px;text-align: right;"><a href="mailto:{{$siteEmail}}" style=" font-family: 'Open Sans', sans-serif; font-size:13px; font-weight: bold; color: #ffffff; text-decoration: none; outline: 0;">{{$siteEmail}}</a></td>
              </tr>
              <tr>
                <td style="text-align: right;"><a href="{{env('SITE_URL')}}" target="_blank" style=" font-family: 'Open Sans', sans-serif; font-size:13px; font-weight: bold; color: #ffffff; text-decoration: none; outline: 0;">{{$siteEmail}}</a></td>
              </tr>
           </table>
        </td>
			</tr>				
		</table>
	</td>
</tr>
<tr>
	<td style="border-top: 5px solid #FFB708;height:0"></td>
</tr>