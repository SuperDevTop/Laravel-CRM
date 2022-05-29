<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head> 
    <body>
        <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" style= 'margin-top:0px;'>
            <tr style="background-color:#CCC;">
                <td colspan="2" height="25" align="center">
                    <b><font color="314053"><br>{{ Settings::setting('emailTitle') }}<br><br></font></b>
                </td>
            </tr>
            <tr>
                <td style="border-left:#CCC 1px solid;"></td>
                <td style="border-right:1px solid #CCC; padding-top:5px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" style="font-size:12px;">{{ date('d-m-Y') }}<br/><br/></font>    
                </td>
            </tr>
          <tr>
              <td valign="top" colspan="2" bgcolor="#ffffff" style="padding:20px;  border-left:#CCC 1px solid; border-right:1px solid #CCC;">
                <font face="Arial, Helvetica, sans-serif" color="#333333" style="font-size:12px;"><br/><br /><br/>{{ $content }}</font><br />
              </td>
          </tr>
          <tr>
            <td colspan="2" bgcolor="#ff6600" style="padding:5px 10px 5px 10px;">
            	{{ Settings::setting('emailFooterBar') }}
            </td>
          </tr>
          <tr>
            <td height="60" valign="middle" style="border-left:1px solid #CCC; border-bottom:1px solid #CCC; padding-left:10px;">
            	<font face="Arial, Helvetica, sans-serif" color="#999999" style="font-size:9px">
                	{{ Settings::setting('emailFooterCopyright') }}
                </font>
          	</td>
            <td height="40" width="150" valign="middle" align="right" style="width:100px; border-right:1px solid #CCC; padding-right:10px; border-bottom:1px solid #CCC;">
            	<span style = "font-weight:bold; font-style:italic; font-size:11pt; color:gray;">
                	Powered by 
                    <a href="http://www.urbytus.es/" target="_blank" style="text-decoration:none;">
                        <font color="#314053">Urby</font><font color="#ff6600">tus</font>
                     </a>
               	</span>
          	  </td>
          </tr>
        </table>
    </body>
</html>