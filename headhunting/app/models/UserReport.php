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
class UserReport extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_reports';
    

    public function user() {
    
    	return $this->belongsTo('User','user_id','id');
    }
}