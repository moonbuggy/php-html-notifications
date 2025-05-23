# PHP HTML Notifications
A simple PHP class to display notifications easily on a web page.

<div align="center">
    <img src="notify-demo.svg" width="100%" alt="html-notifications">
</div>

## Usage
See [index.php](index.php) for a basic example.

### now() and buffer()
There's two methods to log messages, _now()_ and _buffer()_, with the difference
being whether or not the message is displayed immediately. Messages logged via
_buffer()_ can be displayed at an appropriate time with _printBufferHTML()_.

_buffer()_ is useful for messages that are generated before any HTML has been
sent, allowing such messages to held until we're in the HTML ```<body>```.

Both _now()_ and _buffer()_ take the same arguments.

The first argument is a string matching one of the message types: ```error```,
```warning```, ```success```, ```info```, ```notice```, ```timer```, ```mark```,
```debug```

The next argument(s) should consist of the message to display/buffer. The
argument list is variable-length, it doesn't matter how many arguments are
provided, all arguments beyond the first will be combined into a single message.

This is useful because _Notify_ will process arrays and iterable objects into
pretty JSON encoded strings, so there's no need to worry about casting to
strings at the caller.

For example:
```php
<?php
  $notifier = new \moonbuggy\Notify();
  $nauticalArray = [ 'type' => 'boat',
                     'name' => 'Boaty McBoatface',
                     'isWhale' => False ];
  $notifier->now('notice', 'Whale status: ', $nauticalArray);
?>
```

<div align="center">
    <img src="notify-demo-nautical.svg" width="100%" alt="Is it a whale?">
</div><br>

The message can contain HTML tags, and there's some code in the CSS that should
scale any ```<img>``` elements to fit in the box.

### printBufferHTML()
This will echo any buffered messages. It takes no arguments.


### enable() and disable()
Notifications can be switched on or off globally by these methods. The default
is set by ```$this->enabled = False;``` in _Notify\\\_\_construct_ but is trivial
to change.

This is an easy way to have messages only appear for a certain class of client
(e.g. logged in admin), which is useful in situations where the notifications
are primarily being used for debugging code or some backend/management purpose
and are not intended for general consumption.

For example:
```php
<?php
  $myApp = new \my\Application();
  $notifier = new \moonbuggy\Notify();

  // explicitly setting both isn't really necessary,
  // one of these will be the default setting
  if($myApp->isLoggedIn())
    $notifier->enable();
  else
    $notifier->disable();
?>
```

## ```debug``` messages
When processing messages of the ```debug``` type, _Notify_ will attempt to
determine the calling function with a backtrace and add it to the message as a
prefix.

The boxes rendered for ```debug``` messages are wider than the others by
default, under the assumption that they'll be used for larger blocks of text
than simple status messages.

## Styling
The CSS is fairly straight forward. Colours, icons and sizes can be modified as
ncessary in the CSS files and it should hopefully be fairly obvious.

The _notify.css_ script is simple and uses the _icon.*.svg_ files, while
_notify.inline.css_ has the SVG icons inline and doesn't require the icon files.
Only one of these needs to be loaded, and in either case there's minified
versions provided.

The inline SVGs don't always work. Loading inline images from the CSS file will
need to be allowed in the HTTP Content Security Policy headers, and there may be
other issues with browser compatibility. The stylesheet using the external
_*.svg_ is more reliable and should work out of the box.
