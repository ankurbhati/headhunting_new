<?php
/**
 * State.php
 *
 * This file contatins Model class to provide Data Logic for all State Table
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
class State extends Eloquent {

    /**
     * The database table state by the model.
     *
     * @var string
     */
    protected $table = 'states';

    /**
     * Hidden Variables
     * 
     */
    protected $hidden = array('country_id');

    // Relation Start

    /**
     *
     * @param $timestamp : Disabling Time Stamp to False for stoping Auto Updating time.
     */
    public $timestamps = false;
}