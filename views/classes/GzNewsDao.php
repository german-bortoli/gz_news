<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GzNewsDao
 *
 * @author german
 */
class GzNewsDao extends DAO {

	/**
	 * It references to self object: City.
	 * It is used as a singleton
	 * 
	 * @access private
	 * @since unknown
	 * @var City 
	 */
	private static $instance;

	/**
	 * It creates a new City object class ir if it has been created
	 * before, it return the previous object
	 * 
	 * @access public
	 * @since unknown
	 * @return City 
	 */
	public static function newInstance() {
		if (!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	function __construct() {

		parent::__construct();

		$this->setTableName('t_news');
		$this->setPrimaryKey('gn_id');

		$array_fields = array(
			'gn_id',
			'gn_lang',
			'gn_title',
			'gn_description',
			'gn_tags',
			'gn_time_created',
			'gn_enabled',
			'gn_visits_counter'
		);
		$this->setFields($array_fields);
	}

	public function save(array $fields = array()) {

		$gn_id = 0;
		if (isset($fields['gn_id'])) {
			$gn_id = (int) $fields['gn_id'];
		}

		if ($gn_id) {
			$success = $this->dao->update($this->getTableName(), $fields, array('gn_id' => $gn_id));
			$news_id = $gn_id;
		} else {
			$this->dao->insert($this->getTableName(), $fields);
			$news_id = $this->dao->insertedId();
		}

		return $news_id;
	}

	public function listItems(array $options = array()) {

		$defaults = array(
			'page' => NULL,
			'total_per_page' => 10,
			'language' => NULL,
			'limit' => NULL,
			'offset' => 0,
		);
		
		$options = array_merge($defaults, $options);

		$this->dao->select($this->getFields());
		$this->dao->from($this->getTableName());
		
		
		if ($options['page'] !== NULL) {
			$current_page = (int) $options['page'];
			if($current_page > 0) {
                $current_page = $current_page-1;
            }
			
			$offset = (int) $options['total_per_page'];
			$limit = $current_page * $offset;
			
			$this->dao->limit($limit, $offset);
		}
		
		if ($options['limit'] != NULL && $options['page'] == NULL) {
			$this->dao->limit($options['limit'], $options['offset']);
			
		}
		
		if ($options['language']) {
			$this->dao->where('gn_lang', $options['language']);
		}
		
		$result = $this->dao->get();
		
		if ($result == false) {
			return array();
		}
		
		return $result->result();
	}

}
