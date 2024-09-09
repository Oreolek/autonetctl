<?php
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
