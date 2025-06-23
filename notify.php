<?php
namespace moonbuggy;

/**
 * Notify
 *
 * A simple notification class.
 *
 *  now($type, $message)    - immediatly echo notification HTML
 *  buffer($type, $message) - store notification of type $type
 *  printBufferHTML()       - display all stored notifications and clear buffer
 *
 *  $message can be a string, array or iterable object. In the case of an
 *  iterable, they'll be json_encoded and printed in a <code> block to preserve
 *  whitespace.
 */
class Notify {
  private bool $enabled;
  private string $notifyBuffer;

  function __construct() {
    $this->enabled = False;
    $this->notifyBuffer = '';
  }

  public function enable(): void {
    $this->enabled = True;
  }

  public function disable(): void {
    $this->enabled = False;
  }

  public function isEnabled(): bool {
    return $this->enabled;
  }

  public function now(string $notifyType='info', mixed ...$messages): void {
    if($this->enabled)
      echo $this->getMessageHTML($notifyType, $this->stringifyMessages($messages));
  }

  public function buffer(string $notifyType='info', mixed ...$messages): void {
    if($this->enabled)
      $this->notifyBuffer .=
        $this->getMessageHTML($notifyType, $this->stringifyMessages($messages));
  }

  private function stringifyMessages(mixed $messages): string {
    $messageString = '';
    foreach($messages as $message) {
      if(is_object($message) || is_array($message))
        $message = '<code>' . json_encode($message,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            . '</code>';

      $messageString .= $message;
    }
    return $messageString;
  }

  private function getMessageHTML(string $notifyType, string $message): string {
    if($notifyType == 'debug') {
      $backtrace = array_slice(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 0), 2);
      foreach(array_values($backtrace) as $data) {
        if($data['function'] == 'include')
          continue;

        $dataClass = isset($data['class']) ? $data['class'] : '';
        $message = $dataClass . '\\' . $data['function'] . ':<br>' . PHP_EOL . $message;
        break;
      }
    }
    return (empty($message)) ? ''
      : "<p class=\"notification $notifyType\"><strong>$notifyType:</strong> $message</p>" . PHP_EOL;
  }

  public function printBufferHTML(): void {
    if($this->enabled && !empty($this->notifyBuffer)) {
      echo PHP_EOL, '<!-- start notification buffer -->', PHP_EOL,
           trim($this->notifyBuffer), PHP_EOL,
           '<!-- end notification buffer -->', PHP_EOL;
      $this->notifyBuffer = '';
    }
  }
}
?>
