<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\Util;

class EasterEggDispatcher extends InputDispatcher {

    private $GIFS_POPCORN = [
        'http://i.giphy.com/3oGRFGmtIJe1AZFDtm.gif', //Boehmermann
        'http://i.giphy.com/zA8YZufPBK46Y.gif', // IT-Crowd
        'http://i.giphy.com/tFK8urY6XHj2w.gif', // 3D-Glas-Guy
        'http://i.giphy.com/d31wXL2CoYtWoIJG.gif', //giphy original
        'http://i.giphy.com/cHKnErUX39Xxe.gif' //Panda
    ];

    private $SOUND_INCEPTION = 'AwADBAADHAADxeSFETPT0mBvuhERAg'; //file_id on telegram server
    private $SOUND_INCEPTION_DURATION = 3;
    private $GIF_INCEPTION = 'http://i.giphy.com/7GnpBauVVOsBW.gif';

    private $GIF_FARGO_OKAY = 'http://i.giphy.com/Yt4O1DVRpyxR6.gif';


    public function handle($message) {
        $input = strtolower($message->getText());
        if (Util::contains($input, 'inception')) {
            $this->startRecord($message);
            $this->sendDocument($message, $this->GIF_INCEPTION);
            $this->sendVoice($message, $this->SOUND_INCEPTION, $this->SOUND_INCEPTION_DURATION);
            return true;
        }

        if ($message->getVoice() != null) {
            $this->startRecord($message);
            $this->sendVoice($message, $this->SOUND_INCEPTION, $this->SOUND_INCEPTION_DURATION);
            return true;
        }

        if (Util::contains($input, 'popkorn')
            || Util::contains($input, 'popcorn')
            || $message->getText() == null
        ) {
            $this->startTyping($message);
            $this->sendDocument($message, $this->getRandomPopcornGif());
            return true;
        }

        if (trim($input) == 'okay') {
            $this->startTyping($message);
            $this->sendDocument($message, $this->GIF_FARGO_OKAY);
            return true;
        }


        return false;
    }

    private function getRandomPopcornGif() {
        $random = rand(0, count($this->GIFS_POPCORN) - 1);
        return $this->GIFS_POPCORN[$random];
    }

}