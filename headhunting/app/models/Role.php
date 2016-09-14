<?php
/**
 * Roles.php
 *
 * This file contatins Model class to provide Data Logic for all User Roles Table
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
class Role extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     *
     * @param $timestamp : Disabling Time Stamp to False for stoping Auto Updating time.
     */
    public $timestamps = false;
}