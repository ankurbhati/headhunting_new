<?php
/**
 * CandidateResume.php
 *
 * This file contatins Model class to provide Data Logic for all CandidateResume Table
 *
 * @category   Models
 * @package    CandidateResume
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    CandidateResume
 *
 */
use Elasticquent\ElasticquentTrait;


class CandidateResume extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */

    use ElasticquentTrait;

    protected $table = 'candidate_resumes';

    //public $fillable = ['id', 'candidate_id', 'resume', "resume_path", "key_skills", "designation", "visa", "region"];
    public $fillable = ['id', 'candidate_id', 'resume', "resume_path", "designation", "visa", "region"];

	protected $mappingProperties = array(
	'id' => [
	  'type' => 'integer',
	  #"analyzer" => "standard",
	],
	'candidate_id' => [
	  'type' => 'integer',
	  #"analyzer" => "standard",
	],
	'resume' => [
	  'type' => 'string',
	  "analyzer" => "standard",
	],
	'resume_path' => [
	  'type' => 'string',
	  "analyzer" => "standard",
	],
	/*'key_skills' => [
	  'type' => 'string',
	  "analyzer" => "standard",
	],*/
	'designation' => [
	  'type' => 'string',
	  "analyzer" => "standard",
	  //"index"=> "not_analyzed"
	],
	'visa' => [
	  'type' => 'string',
	  "analyzer" => "standard",
	],
	'region' => [
	  'type' => 'string',
	  "analyzer" => "standard",
	]
	);

	/*
	php artisan tinker
	CandidateResume::createIndex($shards = null, $replicas = null);
	CandidateResume::putMapping($ignoreConflicts = true);
	CandidateResume::addAllToIndex();
	*/

	/**
     *
     * countries : Relation between User & country.
     *
     * @return Object belongs to Relation User Country.
     */
    public function candidate() {

    	return $this->belongsTo('Candidate','candidate_id','id');
    }

}
