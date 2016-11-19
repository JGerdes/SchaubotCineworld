<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\Util;

class EasterEggDispatcher extends InputDispatcher {

    private $POPCORN_GIFS = [
        'http://i.giphy.com/3oGRFGmtIJe1AZFDtm.gif', //Boehmermann
        'http://i.giphy.com/zA8YZufPBK46Y.gif', // IT-Crowd
        'http://i.giphy.com/tFK8urY6XHj2w.gif', // 3D-Glas-Guy
        'http://i.giphy.com/d31wXL2CoYtWoIJG.gif', //giphy original
        'http://i.giphy.com/cHKnErUX39Xxe.gif' //Panda
    ];

    public function handle($message) {
        $input = strtolower($message->getText());
        if (Util::contains($input, 'popkorn')
            || Util::contains($input, 'popcorn')
            || $message->getText() == null
        ) {
            $this->startTyping($message);
            $this->sendDocument($message, $this->getRandomPopcornGif());
            return true;
        }


        return false;
    }

    private function getRandomPopcornGif() {
        $random = rand(0, count($this->POPCORN_GIFS) - 1);
        return $this->POPCORN_GIFS[$random];
    }

}