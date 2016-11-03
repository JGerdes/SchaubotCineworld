<?php

namespace JGerdes\SchauBot\Dispatcher;


use JGerdes\SchauBot\Database\DbController;

abstract class InputDispatcher {

    /**
     * @var DbController
     */
    protected $db;

    /**
     * @param DbController $dbController
     */
    public function __construct($dbController) {
        $this->db = $dbController;
    }


    /**
     * @param string $input
     * @return string result/answer to given input
     */
    public abstract function handle($input);
}