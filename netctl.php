<?php
/**
 * Automatic script to start netctl with specified SSID on first available Wi-Fi interface, USB preferred.
 Copyright © 2024 Alexander Yakovlev

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 
 **/
$ssid = $argv[1] ?? '';
if (empty($ssid)) {
  echo 'Usage: netctl.php [SSID]';
  return;
}
$list = shell_exec('ip -brief address');
$list = explode("\n", $list);
$interfaces = [];
foreach ($list as $line) {
  $l = explode(" ", $line);
  $interface = $l[0];
  if (substr($interface, 0, 3) === 'wlp') {
    $interfaces[] = $interface;
  }
}
// Makes wlp5s0 lower than whatever is USB
$interfaces = array_reverse($interfaces);
foreach($interfaces as $interface) {
  if (file_exists('/etc/netctl/'.$interface.'-'.$ssid)) {
    shell_exec('netctl start '.$interface.'-'.$ssid);
    return;
  }
}
echo 'No preconfigured network found.';
