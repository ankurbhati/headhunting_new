<?php
/**
 * CandidateApplication.php
 *
 * This file contatins Model class to provide Data Logic for all CandidateApplication Table
 *
 * @category   Models
 * @package    CandidateApplication
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
class CandidateApplication extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'candidate_applications';

    /**
     *
     * candidate : Relation between Candidate and Submittel.
     *
     * @return Object belongs to Relation Candidate.
     */
      public function candidate() {

      	return $this->belongsTo('Candidate','candidate_id','id');
      }

      /**
       *
       * jobPost : Relation between jobPost and Submittel.
       *
       * @return Object belongs to Relation jobPost.
       */
        public function requirement() {

        	return $this->belongsTo('JobPost','job_post_id','id');
        }

    /**
     *
     * submittedBy : Relation between submittel and User.
     *
     * @return Object belongs to Relation.
     */
      public function submittedBy() {

        return $this->belongsTo('User','submitted_by','id');
      }

      public function applicationStatus() {
        return $this->hasMany('JobPostSubmittleStatus','job_post_submittle_id','id')
          ->orderBy('created_at', 'desc');
      }
}
