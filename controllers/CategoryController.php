<?php
require_once dirname(__DIR__) . '/models/Category.php';

public function __construct(Type $var = null) {
    $this->var = $var;
}
?>