# Simple Timer

This is a ridiculously simple microsecond timer for you to use within your PHP applications.

It starts the timer when you instantiate the class and from there you can `mark` points of delta along the way and `stop` the timer.

Really; could it get simpler?

# Usage examples

```php
<?php

use Amnuts\Datetime\Timer;

$timer = new Timer();
sleep(5); // do something here
echo $timer->stop();
```

and that'll give us the result of:

```
Started 2020-09-19 16:06:52
Ended 2020-09-19 16:06:57, total time 0d 0h 0m 5.000200s
```

Simple, but kinda boring.  However, if you have a function that you're wanting to time which has lots of parts, then it's useful to place markers throughout and see the difference between each part.

For example:

```php
<?php

use Amnuts\Datetime\Timer;

$timer = new Timer();
sleep(1);
$timer->mark();
sleep(2);
$timer->mark();
sleep(3);
$timer->stop();

echo $timer;
```

will give us the result of:

```
Started 2020-09-19 16:11:59
	Δ 0d 0h 0m 1.000200s
	Δ 0d 0h 0m 2.000700s
Ended 2020-09-19 16:12:05, total time 0d 0h 0m 6.001800s
```

The `mark` method allows us to see the delta between each time it's marked.  But it can be even more helpful with supplying a message for the mark:

```php
<?php

use Amnuts\Datetime\Timer;

$timer = new Timer();
sleep(1);
$timer->mark('Starting something slow');
sleep(2);
$timer->mark('Ended that and going onto something even slower!');
sleep(3);
$timer->stop();

echo $timer;
```

and that gives us:

```
Started 2020-09-19 16:14:51
	Δ 0d 0h 0m 1.000700s (Starting something slow)
	Δ 0d 0h 0m 2.000700s (Ended that and going onto something even slower!)
Ended 2020-09-19 16:14:57, total time 0d 0h 0m 6.001900s
```

And, to be honest, you don't even need to `stop` the timer to output the current delta.  You could just do this:

```
<?php

use Amnuts\Datetime\Timer;

$timer = new Timer();
sleep(1);
echo $timer;
sleep(2);
echo $timer;
```

which produces output such as:

```
Started 2020-09-19 16:40:47, current delta 0d 0h 0m 1.000100s
Started 2020-09-19 16:40:47, current delta 0d 0h 0m 3.000700s
```

# License

MIT: http://acollington.mit-license.org/
