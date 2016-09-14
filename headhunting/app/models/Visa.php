<?php
/**
 * Visa.php
 *
 * This file contatins Model class to provide Data Logic for all User Visas Table
 *
 * @category   Models
 * @package    User
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    User
 *
 */
class Visa extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'visas';

    /**
     *
     * @param $timestamp : Disabling Time Stamp to False for stoping Auto Updating time.
     */
    public $timestamps = false;
}