<?php

/**
 * Country.php
 *
 * This file contatins Model class to provide Data Logic for all Region Table
 *
 * @category   Models
 * @package    User
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @package    User
 * @category   Models
 *
 */
class Country extends Eloquent {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     *
     * @param $timestamp : Disabling Time Stamp to False for stoping Auto Updating time.
     */
    public $timestamps = false;
}