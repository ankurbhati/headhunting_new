<?php
/**
 * City.php
 *
 * This file contatins Model class to provide Data Logic for all City Table
 *
 * @category   Models
 * @package    City
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    City
 *
 */
class City extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     *
     * @param $timestamp : Disabling Time Stamp to False for stoping Auto Updating time.
     */
    public $timestamps = false;
}