
 <tr bgcolor="#09170B">
		<td align="center" style="padding: 15px 20px;">
			<p style="font-family: 'Open Sans', sans-serif; font-size:12px; color: #cccccc; margin: 0 0 5px 0; line-height:16px;">Copyright  &copy;  &nbsp; {{date('Y')}} {{ Common::getWebsiteSettings()->site_title }}. All rights reserved. </p>			
		</td>
	</tr>
	<tr bgcolor="#09170B">
		<td style="border-top: 1px solid #444444; text-align:center;padding:20px 0 15px 0;" style="">
			<table width="200" border="0" cellspacing="0" cellpadding="0" align="center" >
				<tr>
                    <td style="text-align: center;">
                        <a href="{{Common::getWebsiteSettings()->facebook_link ?? '#' }}" style="display: inline-block; text-align: center;" target="_blank">
                        	<img src="{{ URL::asset('admin/files/images/facebook-icon.png') }}" width="32" height="32" style="display: block;">
                        </a>                        
                    </td>
                 
                    <td style="text-align: center;">                        
                        <a href="{{Common::getWebsiteSettings()->linkedin_link ?? '#' }}" style="display: inline-block; text-align: center;" target="_blank">
                            <img src="{{ URL::asset('admin/files/images/linkedin-icon.png') }}" width="32" height="32" style="display: block;">
                        </a>                        
                    </td>
                 
                </tr>
            </table>
		</td>
	</tr>