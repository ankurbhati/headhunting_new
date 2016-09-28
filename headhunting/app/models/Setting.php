<?php
/**
 * Candidate.php
 *
 * This file contatins Model class to provide Data Logic for all Candidate Table
 *
 * @category   Models
 * @package    Candidate
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    Candidate
 *
 */
class Setting extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';
    
    public $timestamps = false;

}