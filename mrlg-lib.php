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

function printError ($message)
{
	echo "<font color=red><code><strong>" . $message . "</strong></code></font><br>\n";
}

function safeOutput ($string)
{
	return htmlentities (substr ($string, 0, 50));
}

function safeInputArg ($argname)
{
	if (! array_key_exists ($argname, $_REQUEST))
		return NULL;
	$value = trim ($_REQUEST[$argname]);
	return preg_match ('/^[A-Za-z0-9_:\.\/\-]*$/', $value) ? $value : NULL;
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
		$safe = safeInputArg ('argument');
		if ($safe == '')
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
		else
			$argument = $safe;
	}
	// All Ok, prepare to connect.
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
	global $socket_timeout;
	$link = fsockopen ($address, $port, $errno, $errstr, $socket_timeout);
	if (!$link)
	{
		printError ("Error connecting to router");
		return;
	}
	socket_set_timeout ($link, $socket_timeout);
	$username = $router[$routerid]["username"];
	if (!empty ($username)) fputs ($link, "{$username}\n");
	fputs ($link, "{$password}\nterminal length 0\n{$command}\n");
	// let daemon print bulk of records uninterrupted
	if (empty ($argument) && $request[$requestid]["argc"] > 0) sleep (2);
	fputs ($link, "quit\n");
	echo "<pre>\n";
	// Skip text up to the line following out command.
	while (!feof ($link)
		&& (strpos (fgets ($link, 1024), $command) === FALSE));
	// Skip everything up to the 'quit' command.
	while (!feof ($link)
		&& (strpos (($buf = fgets ($link, 1024)), "quit") === FALSE))
	{
		echo $buf;
	}
	echo "</pre>\n";
	fclose ($link);
}
?>
