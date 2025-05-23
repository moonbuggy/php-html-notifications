<?php
  $notifierPath = './';
  $notifierScriptPath = $notifierPath . 'notify.php';

  $notifierStylePath = $notifierPath . 'notify.min.css';
  // $notifierStylePath = $notifierPath . 'notify.inline.min.css';

  require_once $notifierScriptPath;
  use \moonbuggy\Notify as Notify;
  $notifier = new Notify;
  $notifier->enable();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="preload" href="<?php echo $notifierStylePath; ?>" as="style">
  <title>PHP HTML Notifier Test</title>
  <link rel="stylesheet" href="<?php echo $notifierStylePath; ?>" type="text/css" media="screen">
</head>

<body>
  <h1>PHP HTML Notifier Test</h1>

  <h2>Notify->now()</h2>
<?php
  foreach(['error', 'warning', 'success', 'info', 'notice', 'timer', 'mark', 'debug'] as $notifyType) {
    $aString = in_array($notifyType, ['error', 'info']) ? 'an ' : 'a ';

    // display immediately
    $notifier->now($notifyType, 'this is ' . $aString . $notifyType);

    // buffer and display later
    $notifier->buffer($notifyType, 'this is a buffered ' . $notifyType);
  }
?>

  <h2>Notify->buffer()</h2>
<?php
  // now it's later, print the buffer
  $notifier->printBufferHTML();
?>

  <h2>Notify->disable()</h2>
<?php
  // nothing will be displayed if the notifier is disabled
  $notifier->disable();
  foreach(['error', 'warning', 'success', 'info', 'notice', 'timer', 'mark', 'debug'] as $notifyType) {
    $aString = in_array($notifyType, ['error', 'info']) ? 'an ' : 'a ';
    $notifier->now($notifyType, 'this is a disabled ' . $aString . $notifyType);
    $notifier->buffer($notifyType, 'this is a disabled buffered ' . $notifyType);
  }
  $notifier->printBufferHTML();
?>
  <p>Nothing to see here..</p>
</body>
</html>
