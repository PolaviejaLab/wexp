<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ExperimentRepository extends EntityRepository
{
	/**
	 * Returns the names of all the fields for a given experiment.
	 */
	public function getFieldNames($experiment)
	{
		// Convert object to Id
		if(is_object($experiment))
			$experiment = $experiment->getId();

		// Query database for 
		$queryStr = "SELECT DISTINCT r.field " .
					"FROM AppBundle:Response r, AppBundle:Player p " .
					"WHERE p.experiment = :expId AND r.player = p";
		
		$query = $this->getEntityManager()->createQuery($queryStr);
		$query->setParameter('expId', $experiment);
		$result = $query->getResult();

		$fields = array();
		
		array_walk_recursive($result, 
			function($field) use (&$fields) { 
				$fields[] = $field; 
			});
		
		return $fields;
	}
	
	
	/**
	 * Returns the complete dataset for a single experiment.
	 */
	public function getDataset($experiment)
	{
		// Convert object to Id
		if(is_object($experiment))
			$experiment = $experiment->getId();
		
		$queryStr = "SELECT p.id, r.field, r.value " .
					"FROM AppBundle:Response r, AppBundle:Player p " .
					"WHERE p.experiment = :expId AND r.player = p " .
					"ORDER BY p.id";

		$query = $this->getEntityManager()->createQuery($queryStr);
		$query->setParameter('expId', $experiment);
		$result = $query->getResult();
		
		$fields = $this->getFieldNames($experiment);
		$dataset = array();

		foreach($result as $item) {
			$field_index = array_keys($fields, $item['field'])[0];
			$participant_index = $item['id'];
			
			// If this is a new participant, create empty fields
			if(!array_key_exists($participant_index, $dataset)) {
				$entry = array();
				foreach($fields as $key => $field)
					$entry[$key] = null;
				$dataset[$participant_index] = $entry;
			}
			
			// Fill field
			$dataset[$participant_index][$field_index] = $item['value'];
		}

		// Make sure all results are sorted
		foreach($dataset as $participant)
			ksort($participant);
		
		return array("fields" => $fields, "data" => $dataset);
	}
}
