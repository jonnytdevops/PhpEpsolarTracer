<html>
<head>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta http-equiv="refresh" content="5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EpSolar Dashboard</title>
</head>
<body>

<?php
  require_once 'PhpEpsolarTracer.php';
  $tracer = new PhpEpsolarTracer('/dev/ttyEpSolar1');

  if ($tracer->getInfoData()) {
    $tracer->getRealTimeData();
    $tracer->getStatData();
    $equipStatus = $tracer->realtimeData[16];
    $chargStatus = 0b11 & ($equipStatus >> 2);
    switch ($chargStatus) {
      case 0: $eStatus = "Not charging"; break;
      case 1: $eStatus = "Float (13.8V)"; break;
      case 2: $eStatus = "Boost (14.9V)"; break;
      case 3: $eStatus = "Equalization (14.6V)"; break;
    };
    if ($equipStatus >> 4) {
      $eStatus = "<font color=\"red\">FAULT</font>";
    }
  } else {
    print "<h1>Connection to RS485 interface unavailable</h1>";
    print "</body></html>";
    die();
  }

?>

<script src="canvas-gauges/gauge.min.js"></script>
<div style="text-align: center">
<canvas data-type="radial-gauge"
        data-width="252"
        data-height="252"
        data-units="Battery Volts"
        data-title="false"
        data-value="<?php echo $tracer->realtimeData[3]; ?>"
        data-animate-on-init="false"
        data-animated-value="true"
        data-min-value="10"
        data-max-value="15"
        data-major-ticks="10,11,12,13,14,15"
        data-minor-ticks="0.5"
        data-stroke-ticks="false"
        data-highlights='[
            { "from": 10, "to": 12, "color": "rgba(255,0,0)" },
            { "from": 12, "to": 12.5, "color": "rgba(255,165,0)" },
            { "from": 12.5, "to": 15, "color": "rgba(50,205,50)" }
        ]'
        data-color-plate="#222"
        data-color-major-ticks="#f5f5f5"
        data-color-minor-ticks="#ddd"
        data-color-title="#fff"
        data-color-units="#ccc"
        data-color-numbers="#eee"
        data-color-needle-start="rgba(0,0,255)"
        data-color-needle-end="rgba(106,90,205)"
        data-value-box="true"
        data-animation-rule="bounce"
        data-animation-duration="500"
        data-font-value="Led"
        data-font-numbers="Led"
></canvas>

<canvas data-type="radial-gauge"
        data-width="252"
        data-height="252"
        data-units="PV Watts"
        data-title="false"
        data-value="<?php echo $tracer->realtimeData[2]; ?>"
        data-animate-on-init="false"
        data-animated-value="true"
        data-min-value="0"
        data-max-value="700"
        data-major-ticks="0,100,200,300,400,500,600,700"
        data-minor-ticks="5"
        data-stroke-ticks="false"
        data-highlights='[
            { "from": 0, "to": 90, "color": "rgba(255,0,0)" },
            { "from": 90, "to": 150, "color": "rgba(255,165,0)" },
            { "from": 150, "to": 210, "color": "rgba(173,255,47)" },
            { "from": 210, "to": 700, "color": "rgba(50,205,50)" }
        ]'
        data-color-plate="#222"
        data-color-major-ticks="#f5f5f5"
        data-color-minor-ticks="#ddd"
        data-color-title="#fff"
        data-color-units="#ccc"
        data-color-numbers="#eee"
        data-color-needle-start="rgba(0,0,255)"
        data-color-needle-end="rgba(106,90,205)"
        data-value-box="true"
        data-animation-rule="bounce"
        data-animation-duration="500"
        data-font-value="Led"
        data-font-numbers="Led"
></canvas>

<canvas data-type="radial-gauge"
        data-width="252"
        data-height="252"
        data-units="Battery Amps"
        data-title="false"
        data-value="<?php echo $tracer->realtimeData[4]; ?>"
        data-animate-on-init="false"
        data-animated-value="true"
        data-min-value="0"
        data-max-value="50"
        data-major-ticks="0,5,10,15,20,25,30,35,40,45,50"
        data-minor-ticks="5"
        data-stroke-ticks="false"
        data-highlights='[
            { "from": 0, "to": 8, "color": "rgba(255,0,0)" },
            { "from": 8, "to": 12.5, "color": "rgba(255,165,0)" },
            { "from": 12.5, "to": 17, "color": "rgba(173,255,47)" },
            { "from": 17, "to": 50, "color": "rgba(50,205,50)" }
        ]'
        data-color-plate="#222"
        data-color-major-ticks="#f5f5f5"
        data-color-minor-ticks="#ddd"
        data-color-title="#fff"
        data-color-units="#ccc"
        data-color-numbers="#eee"
        data-color-needle-start="rgba(0,0,255)"
        data-color-needle-end="rgba(106,90,205)"
        data-value-box="true"
        data-animation-rule="bounce"
        data-animation-duration="500"
        data-font-value="Led"
        data-font-numbers="Led"
></canvas>
<div style="font-family: Helvetica;">
<p>System Status: <?php print $eStatus; ?><br />
Units Generated Today: <?php echo $tracer->statData[8]; ?>kWh<br />
Units Generated This Month: <?php echo $tracer->statData[9]; ?>kWh<br />
Units Generated This Year: <?php echo $tracer->statData[10]; ?>kWh<br />
Min Battery Voltage Today: <?php echo $tracer->statData[3]; ?>V<br />
DC Load Output Power: <?php echo $tracer->realtimeData[8]; ?>W<br />
</p>
</div>
</div>
</body>
</html>
