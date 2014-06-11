<?php
 /*  Version 1.0.7
  *  MRLG for PHP --- multi router looking glass for GNU Zebra and
  *  Cisco IOS routers, written for PHP-enabled web-server.
  *  Copyright (C) 2002-2007 Denis Ovsienko
  *
  *  This program is free software; you can redistribute it and/or modify
  *  it under the terms of the GNU General Public License as published by
  *  the Free Software Foundation; either version 2 of the License, or
  *  (at your option) any later version.
  *
  *  This program is distributed in the hope that it will be useful,
  *  but WITHOUT ANY WARRANTY; without even the implied warranty of
  *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  *  GNU General Public License for more details.
  *
  *  You should have received a copy of the GNU General Public License
  *  along with this program; if not, write to the Free Software
  *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  *
  */

?>

<html>
	<head>
		<title>Multi-router looking glass</title>
	</head>
	<body>
		<center>
		<h1>Multi-router looking glass</h1>
<?php
// MRLG4PHP is shipped with mrlg-config-sample.php, copy it into
// mrlg-config.php and adjust to your needs. Until this is done,
// the script will not work.
require 'mrlg-lib.php';
include 'mrlg-config.php';
if (!isset ($router) || !isset ($request))
{
	printError ('Oops. This installation misses a configuration file (mrlg-config.php)');
}
else
{
?>
		<table border=0>
		<form method=post>
			<tr>
				<td valign=top><b>router:</b><br><?php printRouterList ($router, $router_list_style); ?></td>
				<td valign=top><b>request:</b><br><?php printRequestList ($request, $request_list_style) ?></td>
			</tr>
			<tr>
				<td><b>argument:</b> <input type=text name=argument length=20 maxlength=50 value='<?php echo safeOutput (safeInputArg ('argument')); ?>'></td>
				<td><input type=submit value="Execute"></td>
			</tr>
		</form>
			<tr><td colspan=2><hr><b>result:</b><br><?php execPreviousRequest ($router, $request); ?></td></tr>
		</table>
<?php
}
?>
		</center>
		<hr>
		<small>
		<a href='https://github.com/infrastation/mrlg4php'>MRLG for PHP</a> version 1.0.7, Copyright &copy; 2002-2007
		Denis Ovsienko, comes with ABSOLUTELY NO WARRANTY; for
		details see source code. This is free software, and you are welcome to redistribute it
		under certain conditions; see source code for details.
		</small>
	</body>
</html>
