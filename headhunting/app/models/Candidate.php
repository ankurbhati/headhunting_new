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
class Candidate extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'candidates';

    /**
     *
     * candidate : Relation between candidate & visa.
     *
     * @return Object belongs to Relation candidate & visa.
     */
    public function visa() {
    
    	return $this->belongsTo('Visa','visa_id','id');
    }

    /**
     *
     * countries : Relation between candidate & City.
     *
     * @return Object belongs to Relation candidate & City..
     */
    public function city() {
    
    	return $this->belongsTo('City','city_id','id');
    }

    /**
     *
     * countries : Relation between candidate & State.
     *
     * @return Object belongs to Relation candidate & State..
     */
    public function state() {
    
    	return $this->belongsTo('State','state_id','id');
    }

    /**
     *
     * countries : Relation between candidate & Country.
     *
     * @return Object belongs to Relation candidate & Country..
     */
    public function country() {
    
    	return $this->belongsTo('Country','country_id','id');
    }

    /**
     *
     * countries : Relation between candidate & Vendor.
     *
     * @return Object belongs to Relation candidate & Vendor..
     */
    public function workstate() {
    
    	return $this->belongsTo('WorkStates','work_state_id','id');
    }

    /**
     *
     * countries : Relation between candidate & User.
     *
     * @return Object belongs to Relation candidate & User..
     */
    public function createdby() {
    
    	return $this->belongsTo('User','added_by','id');
    }

    /**
     *
     * countries : Relation between candidate & its resume.
     *
     * @return Object belongs to Relation candidate & its resume..
     */
    public function candidateresume() {
    
        return $this->hasOne('CandidateResume','candidate_id','id');
    }


    /**
     *
     * candidateRecentRate : Relation between candidate & its rate.
     *
     * @return Object belongs to Relation candidate & its resume..
     */
    public function candidateRecentRate() {
    
        return $this->hasOne('CandidateRate','candidate_id','id')->orderBy('id', 'desc')->first();
    }

    /**
     *
     * countries : Relation between candidate & its resume.
     *
     * @return Object belongs to Relation candidate & its resume..
     */
    public function candidaterate() {
    
        return $this->hasOne('CandidateRate','candidate_id','id');
    }

    /**
     *
     * countries : Relation between candidate & User.
     *
     * @return Object belongs to Relation candidate & User..
     */
    public function thirdparty() {
    
        return $this->belongsTo('Thirdparty','source_id','id');
    }
}