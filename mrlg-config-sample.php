<?
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
  *  Author can be reached at his homepage: http://pilot.org.ua/
  */


	// Specify the look you wish lists to have here: ('radio'/'select')
	$router_list_style = 'radio';
	$request_list_style = 'radio';
	$socket_timeout = 5;

/*
	Describe your routers here.
	* [title] is what you will see at the web-page, e.g. 'border router'
	or 'core-1-2-net15-gw'. If omitted, <address> will be used.
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
	$router['default']['zebra_port'] = 2601;
	$router['default']['ripd_port'] = 2602;
	$router['default']['ripngd_port'] = 2603;
	$router['default']['ospfd_port'] = 2604;
	$router['default']['bgpd_port'] = 2605;
	$router['default']['ospf6d_port'] = 2606;
	$router['default']['password'] = 'zebra';
	$router['default']['ignore_argc'] = FALSE;
	// your routers
	// I recommend using of key numbers with step of 10, it allows to insert new
	// declarations without reordering the rest of list. As in BASIC or DNS MX.

	$router[10]['title'] = 'core router 1';
	$router[10]['address'] = 'router1.some.net';
	$router[10]['services'] = 'zebra ospfd bgpd';

	$router[20]['address'] = 'router2.some.net';
	$router[20]['services'] = 'zebra ospfd';

	$router[30]['title'] = 'border router';
	$router[30]['address'] = '192.168.0.1';
	$router[30]['services'] = 'bgpd';

	// an example how to define a CISCO router
	$router[40]['title'] = 'cisco';
	$router[40]['address'] = 'cisco.some.net';
	$router[40]['services'] = 'zebra ospfd bgpd';
	$router[40]['username'] = 'username';
	$router[40]['password'] = 'password';
	$router[40]['zebra_port'] = '23';
	$router[40]['ospfd_port'] = '23';
	$router[40]['bgpd_port'] = '23';

/*
	// You can force use of non-standard port numbers to access Zebra daemons.
	$router[0]['zebra_port'] = 5601;
	$router[0]['ripd_port'] = 5602;
	$router[0]['ripngd_port'] = 5603;
	$router[0]['ospfd_port'] = 5604;
	$router[0]['bgpd_port'] = 5605;
	$router[0]['ospf6d_port'] = 5606;
	// Define passwords for your daemons this way, if they are not default
	$router[0]['password'] = 'onelongpassword';
	$router[0]['zebra_password'] = 'zebrapassword';
	$router[0]['ripd_password'] = 'ripdpassword';
	$router[0]['ripngd_password'] = 'ripngdpassword';
	$router[0]['ospfd_password'] = 'ospfdpassword';
	$router[0]['bgpd_password'] = 'bgpdpassword';
	$router[0]['ospf6d_password'] = 'ospf6dpassword';
*/

/*
	Requests definitions.
	[title] is what you see on the web-page. If omitted, <command> is used instead.
	<command> is what is sent to the CLI.
	<handler> is processing daemon's name
	<argc> is minimal argument count
*/
	$request[10]['title'] = 'IPv4 OSPF neighborship';
	$request[10]['command'] = 'show ip ospf neighbor';
	$request[10]['handler'] = 'ospfd';
	$request[10]['argc'] = 0;

	$request[20]['title'] = 'IPv4 BGP neighborship';
	$request[20]['command'] = 'show ip bgp summary';
	$request[20]['handler'] = 'bgpd';
	$request[20]['argc'] = 0;

	$request[30]['title'] = 'IPv4 OSPF RT';
	$request[30]['command'] = 'show ip ospf route';
	$request[30]['handler'] = 'ospfd';
	$request[30]['argc'] = 0;

	$request[40]['title'] = 'IPv4 BGP RR to...';
	$request[40]['command'] = 'show ip bgp';
	$request[40]['handler'] = 'bgpd';
	$request[40]['argc'] = 1;

	$request[50]['title'] = 'IPv4 any RR to...';
	$request[50]['command'] = 'show ip route';
	$request[50]['handler'] = 'zebra';
	$request[50]['argc'] = 1;

	$request[60]['title'] = 'interface info on...';
	$request[60]['command'] = 'show interface';
	$request[60]['handler'] = 'zebra';
	$request[60]['argc'] = 1;

	$request[70]['title'] = 'IPv6 OSPF neighborship';
	$request[70]['command'] = 'show ipv6 ospf neighbor';
	$request[70]['handler'] = 'ospf6d';
	$request[70]['argc'] = 0;

	$request[80]['title'] = 'IPv6 BGP neighborship';
	$request[80]['command'] = 'show ipv6 bgp summary';
	$request[80]['handler'] = 'ripngd';
	$request[80]['argc'] = 0;

	$request[90]['title'] = 'IPv6 OSPF RT';
	$request[90]['command'] = 'show ipv6 ospf route';
	$request[90]['handler'] = 'ospf6d';
	$request[90]['argc'] = 0;

	$request[100]['title'] = 'IPv6 BGP route to...';
	$request[100]['command'] = 'show ipv6 bgp';
	$request[100]['handler'] = 'ripngd';
	$request[100]['argc'] = 1;

	$request[110]['title'] = 'IPv6 any route to...';
	$request[110]['command'] = 'show ipv6 route';
	$request[110]['handler'] = 'zebra';
	$request[110]['argc'] = 1;

?>
