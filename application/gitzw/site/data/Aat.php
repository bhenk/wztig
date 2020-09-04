<?php

namespace gitzw\site\data;

use EasyRdf\Graph;
use EasyRdf\RdfNamespace;
use gitzw\GZ;
use Exception;

class Aat extends JsonData {
	
	const AAT_URL = 'http://vocab.getty.edu/aat/';
	
	private static $instance;
	
	public static function get() : Aat {
		if (is_null(self::$instance)) {
			self::$instance = new Aat();
		}
		return self::$instance;
	}
	
	private $data;
	
	private function __construct() {
		$this->data = $this->load();
	}
	
	public function getHtmlLabels($term) : string {
		$str = '';
		foreach ($this->getLables($term) as $lan=>$val) {
			$str .= '<span lang="'.$lan.'">'.$val.'</span>&nbsp;&nbsp;&nbsp; ';
		}
		return $str;
	}
	
	public function getLables(string $term) : array {
		$labels = $this->data[$term];
		if (is_null($labels)) {
			$labels = $this->getLablesFromGraph($term);
			$this->data[$term] = $labels;
			$this->persist();
		}
		return $labels;
	}
	
	public function getLablesFromGraph(string $term) : array {
		RdfNamespace::set('aat', self::AAT_URL);
		RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
		RdfNamespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
		
		$g = $this->getGraph($term);
		
		$literals = [];
		$this->getShortest($g->allLiterals($term, 'rdfs:label'), $literals);
		$this->getShortest($g->allLiterals($term, 'skos:altLabel'), $literals);
		$this->getShortest($g->allLiterals($term, 'skos:prefLabel'), $literals);
		return $literals;
	}
	
	private function getShortest(array $ins, array &$literals) {
		foreach ($ins as $literal) {
			$lang = $literal->getLang();
			$value = $literal->getValue();
			if ($literals[$lang]) {
				if (strlen($value) < strlen($literals[$lang])) {
					$literals[$lang] = $value;
				}
			} else {
				$literals[$lang] = $value;
			}
		}
	}
	
	public function getGraph(string $term) : Graph {
		$name = str_replace(':', '_', $term).'.rdf';
		$filename = GZ::DATA.'/aat/'.$name;
		if (!file_exists($filename)) {
			$url = self::AAT_URL.explode(':', $term)[1].'.rdf';
			$ch = curl_init($url); 
			$fp = fopen($filename, 'w+');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			if(curl_errno($ch)){
				throw new Exception(curl_error($ch));
			}
			$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			fclose($fp);
			if($statusCode != 200){
				throw new Exception('statusCode: '.$statusCode);
			}
		}
		$g = new Graph();
		$g->parseFile($filename);
		return $g;
	}
	
	public function jsonSerialize() {
		return $this->data;
	}

	public function getFile(): string {
		return GZ::DATA.'/aat/aat.json';
	}

	

}

