<?php
/*
 * admin.php - admin plugin controller
 * Copyright (c) 2010  André Klaßen
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

require_once 'app/controllers/authenticated_controller.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 
	'classes/OCRestClient/SearchClient.php';


class ProfileController extends AuthenticatedController
{
	
	/**
	 * Common code for all actions: set default layout and page title.
	 */
	function before_filter(&$action, &$args)
	{
#		$this->flash = Trails_Flash::instance();
		
		// set default layout
		$layout = $GLOBALS['template_factory']->open('layouts/base');
		$this->set_layout($layout);
		
		// notify on trails action
#		$klass = substr(get_called_class(), 0, -10);
#		$name = sprintf('oc_admin.performed.%s_%s', $klass, $action);
#		NotificationCenter::postNotification($name, $this);

		PageLayout::setTitle(_(get_config("OPENCAST_GUI_NAME")));
	}
	
	
	/**
	 * This is the default action of this controller.
	 */
	function index_action()
	{
#		$this->redirect(PluginEngine::getLink('opencast/admin/config'));
	}
	
	
	/**
	 * This is the default action of this controller.
	 */
	function list_action()
	{
		$conf = OCRestClient::getConfig('apisecurity');
		$sc = new OCRestClient ( $conf['service_host'], 
			$conf['service_user'], $conf['service_password']);
			
		# Alle Videos der eingeloggten Person per JSON abfragen:
		$jsonArr = $sc->getJSON(
			'/api/events/?sign=false&withacl=false&withmetadata=true'.
				'&withpublications=true'.
				'&filter=presenters:'.$_SESSION['auth']->auth['uname'].
				'&sort=date:DESC&limit=0&offset=0');
		
		# JSON-Array durchgehen und Video-Daten in Extra-Array speichern:
		$this->videos = array();
		for($i = 0; $i < count($jsonArr); $i++)
		{
			$this->videos[$i] = json_decode(json_encode($jsonArr[$i]), true);
			$this->videos[$i]['series_id'] = 
				$this->videos[$i]['metadata'][0]['fields'][6]['value'];
			$this->videos[$i]['series_data'] = array();
			
			# Daten einer Serie (des Videos) per JSON abfragen, sofern zugeordnet:
			if ($this->videos[$i]['series_id'] != '')
			{
				$jsonArr_seriesData = 
					$sc->getJSON('/api/series/'.$this->videos[$i]['series_id']);
				$this->videos[$i]['series_data'] = 
					json_decode(json_encode($jsonArr_seriesData), true);
				
				# Zugeordnete Veranstaltung abfragen, sofern vorhanden:
				$qrySeminarSeries = 
					'SELECT sem.Name, sem.Seminar_id FROM oc_seminar_series ocss '.
					'JOIN seminare sem ON ocss.seminar_id = sem.Seminar_id '.
					'WHERE ocss.series_id LIKE ?';
				$stmt = DBManager::get()->prepare($qrySeminarSeries);
				$stmt->execute( array($this->videos[$i]['series_id']) );
				$semArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if ( count($semArr) > 0 )
					$this->videos[$i]['series_data']['seminars'] = $semArr;
			}
			
			# Mitwirkende-Daten genauer bestimmen, sofern möglich:
			for ($contr = 0; 
					$contr < count($this->videos[$i]['contributor']); $contr++)
			{
				$qryContributerData = 
					'SELECT user_id, username, perms, Vorname, Nachname '.
					'FROM auth_user_md5 '.
					'WHERE username '.
						'LIKE "'.$this->videos[$i]['contributor'][$contr].'"';
				$stmt = DBManager::get()->prepare($qryContributerData);
				$stmt->execute( array($this->videos[$i]['contributor'][$contr]) );
				$resContributerData = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if ( count($resContributerData) > 0 ) {
					$this->videos[$i]['contributor'][$contr] = 
						$resContributerData[0];
				}
			}
		}
		
		# OpenCast-Base-URL beziehen:
		$search_client = SearchClient::getInstance();
		$this->baseURL = $search_client->getBaseURL();
		
		# OpenCast-Sprachkürzel + Sprachen auf deutsch:
		$this->lang = array(
			'ara' => 'Arabisch',
			'zho' => 'Chinesisch',
			'dan' => 'D&auml;nisch',
			'nld' => 'Holl&auml;ndisch',
			'eng' => 'Englisch',
			'fin' => 'Finnisch',
			'fra' => 'Franz&ouml;sisch',
			'deu' => 'Deutsch',
			'gsw' => 'Schweizerdeutsch',
			'hin' => 'Hindi',
			'ita' => 'Italienisch',
			'jpx' => 'Japanisch',
			'nor' => 'Norwegisch',
			'pol' => 'Polnisch',
			'por' => 'Portugiesisch',
			'roh' => 'R&auml;toromanisch',
			'rus' => 'Russisch',
			'slv' => 'Slowenisch',
			'spa' => 'Spanisch',
			'swe' => 'Schwedisch',
			'tur' => 'T&uuml;rkisch',
			'ukr' => 'Ukrainisch'
		);
	}
}


?>
