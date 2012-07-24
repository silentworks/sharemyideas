<?php
/**
 * User: Andrew Smith
 * Date: 25/03/2011
 * Time: 08:17
 */
class Ideas extends Model {
    public function comments() {
        return $this->has_many('Comments');
    }
}