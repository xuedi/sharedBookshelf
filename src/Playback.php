<?php declare(strict_types=1);

namespace SharedBookshelf;

use SharedBookshelf\Playback\LoginPlayback;

/**
 * TODO: maybe use symfony's cli and then integrate doctrine and doctrine fixtures into it as well
 */
class Playback
{
    private LoginPlayback $loginPlayback;

    public function __construct(LoginPlayback $loginPlayback)
    {
        $this->loginPlayback = $loginPlayback;
    }

    public function excecute(): void
    {

        //TODO: $this->eventStore->process()

        echo '### Playback ###' . PHP_EOL;

        echo '--> Login';
        $this->loginPlayback->execute();
        echo PHP_EOL;
    }
}
