<?
 /*  Version 1.04
  *  MRLG for PHP --- multi router looking glass for GNU Zebra and
  *  Cisco IOS routers, written for PHP-enabled web-server.
  *  Copyright (C) 2002 Denis Ovsienko
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
  *  Author can be reached at his homepage: http://pilot.org.ua
  */
	
	// Specify the look you wish lists to have here: ("radio"/"select")
	$router_list_style = "radio";
	$request_list_style = "radio";

/*
	Describe your routers here.
	* [title] is what you will see at the web-page, e.g. "border router"
	or "core-1-2-net15-gw". If omitted, <address> will be used.
	* <address> is IP dotted quad or DNS name, to which this script will telnet.
	If omitted, corresonding router will be removed from the page automatically.
	Pay attention to be able to resolve DNS name from the web server's side.
	* [services] is a list of following words: zebra, ripd,	ripngd, ospfd, bgpd,
	ospf6d. It defines availability to execute certain command on the router.
	If omitted, no commands will be allowed on the router, although it
	will remain on the list.
	* [ignore_argc] lets get full routing table, when set to true,
	therefore disabled by default
	* <username> sets optional username to send before the password for the
	router. If not set, username is not sent.
	* [password] sets default password for all daemons on the router
	* [DAEMON_password] redefines password for DAEMON on the router
	* [DAEMON_port] redefines TCP port for the DAEMON. See examples below.
*/	
	// default values for all routers, used if there is no more specific setting
	$router["default"]["zebra_port"] = "2601";
	$router["default"]["ripd_port"] = "2602";
	$router["default"]["ripngd_port"] = "2603";
	$router["default"]["ospfd_port"] = "2604";
	$router["default"]["bgpd_port"] = "2605";
	$router["default"]["ospf6d_port"] = "2606";
	$router["default"]["password"] = "zebra";
	$router["default"]["ignore_argc"] = false;
	// your routers
	// I recommend using of key numbers with step of 10, it allows to insert new
	// declarations without reordering the rest of list. As in BASIC or DNS MX.

	$router[10]["title"] = "core router 1";
	$router[10]["address"] = "router1.some.net";
	$router[10]["services"] = "zebra ospfd bgpd";

	$router[20]["address"] = "router2.some.net";
	$router[20]["services"] = "zebra ospfd";

	$router[30]["address"] = "border router";
	$router[30]["address"] = "192.168.0.1";
	$router[30]["services"] = "bgpd";

	// an example how to define a CISCO router
	$router[40]["title"] = "cisco";
	$router[40]["address"] = "cisco.some.net";
	$router[40]["services"] = "zebra ospfd bgpd";
	$router[40]["username"] = "username";
	$router[40]["password"] = "password";
	$router[40]["zebra_port"] = "23";
	$router[40]["ospfd_port"] = "23";
	$router[40]["bgpd_port"] = "23";

/*
	// You can force use of non-standard port numbers to access Zebra daemons.
	$router[0]["zebra_port"] = "5601";
	$router[0]["ripd_port"] = "5602";
	$router[0]["ripngd_port"] = "5603";
	$router[0]["ospfd_port"] = "5604";
	$router[0]["bgpd_port"] = "5605";
	$router[0]["ospf6d_port"] = "5606";
	// Define passwords for your daemons this way, if they are not default
	$router[0]["password"] = "onelongpassword";
	$router[0]["zebra_password"] = "zebrapassword";
	$router[0]["ripd_password"] = "ripdpassword";
	$router[0]["ripngd_password"] = "ripngdpassword";
	$router[0]["ospfd_password"] = "ospfdpassword";
	$router[0]["bgpd_password"] = "bgpdpassword";
	$router[0]["ospf6d_password"] = "ospf6dpassword";
*/

/*
	Requests definitions.
	[title] is what you see on the web-page. If omitted, <command> is used instead.
	<command> is what is sent to the CLI.
	<handler> is processing daemon's name
	<argc> is minimal argument count
*/
	$request[10]["title"] = "IPv4 OSPF neighborship";
	$request[10]["command"] = "show ip ospf neighbor";
	$request[10]["handler"] = "ospfd";
	$request[10]["argc"] = 0;

	$request[20]["title"] = "IPv4 BGP neighborship";
	$request[20]["command"] = "show ip bgp summary";
	$request[20]["handler"] = "bgpd";
	$request[20]["argc"] = 0;

	$request[30]["title"] = "IPv4 OSPF RT";
	$request[30]["command"] = "show ip ospf route";
	$request[30]["handler"] = "ospfd";
	$request[30]["argc"] = 0;

	$request[40]["title"] = "IPv4 BGP RR to...";
	$request[40]["command"] = "show ip bgp";
	$request[40]["handler"] = "bgpd";
	$request[40]["argc"] = 1;

	$request[50]["title"] = "IPv4 any RR to...";
	$request[50]["command"] = "show ip route";
	$request[50]["handler"] = "zebra";
	$request[50]["argc"] = 1;

	$request[60]["title"] = "interface info on...";
	$request[60]["command"] = "show interface";
	$request[60]["handler"] = "zebra";
	$request[60]["argc"] = 1;

	$request[70]["title"] = "IPv6 OSPF neighborship";
	$request[70]["command"] = "show ipv6 ospf neighbor";
	$request[70]["handler"] = "ospf6d";
	$request[70]["argc"] = 0;

	$request[80]["title"] = "IPv6 BGP neighborship";
	$request[80]["command"] = "show ipv6 bgp summary";
	$request[80]["handler"] = "ripngd";
	$request[80]["argc"] = 0;

	$request[90]["title"] = "IPv6 OSPF RT";
	$request[90]["command"] = "show ipv6 ospf route";
	$request[90]["handler"] = "ospf6d";
	$request[90]["argc"] = 0;

	$request[100]["title"] = "IPv6 BGP route to...";
	$request[100]["command"] = "show ipv6 bgp";
	$request[100]["handler"] = "ripngd";
	$request[100]["argc"] = 1;

	$request[110]["title"] = "IPv6 any route to...";
	$request[110]["command"] = "show ipv6 route";
	$request[110]["handler"] = "zebra";
	$request[110]["argc"] = 1;

	function printError ($message)
	{
		echo "<font color=red><code><strong>" . $message . "</strong></code></font><br>\n";
	}
	
	function safeOutput ($string)
	{
		return htmlentities (substr ($string, 0, 50));
	}
	
	function printRouterList ($router, $type)
	{
		if ($type == "select") echo "<select name=routerid>\n";
		while (list ($id, $attribute) = each ($router))
			if (strcmp ($id, "default") && !empty($attribute["address"]))
			{
				if ($type == "select") echo "<option value={$id}";
				if ($type == "radio") echo "<input type=radio name=routerid value={$id}";
				if ($_REQUEST["routerid"] == $id)
				{
					if ($type == "select") echo " selected=on";
					if ($type == "radio") echo " checked=on";
				}
				echo ">";
				echo $attribute["title"] ? $attribute["title"] : $attribute["address"];
				if ($type == "select") echo "</option>\n";
				if ($type == "radio") echo "</input><br>\n";
			}
		if ($type == "select") echo "</{$type}>\n";
	}

	function printRequestList ($request, $type)
	{
		if ($type == "select") echo "<select name=requestid>";
		while (list($id, $attribute) = each ($request))
			if (!empty ($attribute["command"]) && !empty ($attribute["handler"]) && isset ($attribute["argc"]))
			{
				if ($type == "select") echo "<option value={$id}";
				if ($type == "radio") echo "<input type=radio name=requestid value={$id}";
				if ($_REQUEST["requestid"] == $id)
				{
					if ($type == "select") echo " selected=on";
					if ($type == "radio") echo " checked=on";
				}
				echo ">";
				echo $attribute["title"] ? $attribute["title"] : $attribute["command"];
				if ($type == "select") echo "</option>\n";
				if ($type == "radio") echo "</input><br>\n";
			}
		echo "</{$type}>\n";
	}

	function execPreviousRequest ($router, $request)
	{
		if (!isset($_REQUEST["routerid"])) return;
		$routerid = $_REQUEST["routerid"];
		if (!isset ($router[$routerid]["address"])) return;
		if (!isset($_REQUEST["requestid"])) return;
		$requestid = $_REQUEST["requestid"];
		if (!isset ($request[$requestid]["argc"])) return;
		$handler = $request[$requestid]["handler"];
		if (empty ($handler) || strpos ($router[$routerid]["services"], $handler) === false)
		{
			printError ("This request is not permitted for this router by administrator.");
			return;
		}
		if ($request[$requestid]["argc"] > 0)
		{
			if (empty($_REQUEST["argument"]))
			{
				$router_defined = isset ($router[$routerid]["ignore_argc"]);
				$router_permits = $router[$routerid]["ignore_argc"] == 1;
				$default_defined = isset ($router["default"]["ignore_argc"]);
				$default_permits = $router["default"]["ignore_argc"] == 1;
				$final_permits =
					(!$router_defined && $default_defined && $default_permits) ||
					($router_defined && $router_permits);
				if (!$final_permits)
				{
					printError ("Full table view is denied on this router");
					return;
				}
			}
			else $argument = $_REQUEST["argument"];
		}
		// All Ok, do telnet
		$address = $router[$routerid]["address"];
		if (!empty ($router[$routerid][$handler . "_port"]))
			$port = $router[$routerid][$handler . "_port"];
		else
			$port = $router["default"][$handler . "_port"];
		if (!empty ($router[$routerid][$handler . "_password"]))
			$password = $router[$routerid][$handler . "_password"];
		elseif (!empty ($router[$routerid]["password"]))
			$password = $router[$routerid]["password"];
		else
			$password = $router["default"]["password"];
		$command = $request[$requestid]["command"] . (!empty ($argument) ? (" " . safeOutput ($argument)) : "");
		$link = fsockopen ($address, $port, $errno, $errstr, 5);
		if (!$link)
		{
			printError ("Error connecting to router");
			return;
		}
		socket_set_timeout ($link, 5);
		$username = $router[$routerid]["username"];
		if (!empty ($username)) fputs ($link, "{$username}\n");
		fputs ($link, "{$password}\nterminal length 0\n{$command}\n");
		// let daemon print bulk of records uninterrupted
		if (empty ($argument) && $request[$requestid]["argc"] > 0) sleep (2);
		fputs ($link, "quit\n");
		echo "<pre>\n";
		while (!feof ($link)) $readbuf = $readbuf . fgets ($link, 256);
		$start = strpos ($readbuf, $command);
		$len = strpos ($readbuf, "quit") - $start;
		while ($readbuf[$start + $len] != "\n") $len--;
		echo substr($readbuf, $start, $len);
		echo "</pre>\n";
		fclose ($link);
	}
?>

<html>
	<head>
		<title>Multi-router looking glass</title>
	</head>
	<body>
		<center>
		<h1>Multi-router looking glass</h1>
		<table border=0>
		<form method=post action=<? echo $_SERVER[PHP_SELF]; ?>>
			<tr>
				<td valign=top><b>router:</b><br><? printRouterList ($router, $router_list_style); ?></td>
				<td valign=top><b>request:</b><br><? printRequestList ($request, $request_list_style) ?></td>
			</tr>
			<tr>
				<td><b>argument:</b> <input type=text name=argument length=20 maxlength=50<? echo " value='" . safeOutput ($_REQUEST["argument"] . "'"); ?>></td>
				<td><input type=submit value="Execute"></td>
			</tr>
		</form>
			<tr><td colspan=2><hr><b>result:</b><br><? execPreviousRequest ($router, $request); ?></td></tr>
		</table>
		</center>
		<hr>
		<small>
		MRLG for PHP version 1.04, Copyright &copy; 2002 Denis Ovsienko<br>
		MRLG for PHP comes with ABSOLUTELY NO WARRANTY; for details see source code.
		This is free software, and you are welcome to redistribute it
		under certain conditions; see source code for details.
		</small>
	</body>
</html>
