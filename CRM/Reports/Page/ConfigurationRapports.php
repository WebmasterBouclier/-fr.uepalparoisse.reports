<?php
use CRM_Reports_ExtensionUtil as E;

class CRM_Reports_Page_ConfigurationRapports extends CRM_Core_Page {

	public function run() {
	// Flag pour les premières paroisses. Permet de changer les Option Listes.
		//  Bouclier = $flagBouclier


		// Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
		CRM_Utils_System::setTitle(E::ts('ConfigurationRapports'));

		
		
		// Création des modèles de rapports
		$this->assign('TemplateListeElectorale', $this->getListeElectoraleTemplate()); // Template de la liste électorale
		$this->assign('TemplateListeGroupes', $this->getListeGroupesTemplate()); // Template des listes par groupes
		$this->assign('TemplateListeMembres', $this->getListeMembresTemplate()); // Template des listes de membres
		$this->assign('TemplateListeAge', $this->getListeParAgeTemplate()); // Template des listes par âge
		$this->assign('TemplateListeParticipants', $this->getListeParParticipantsTemplate()); // Template des listes par participants


		// Creation des instances de rapports
		$this->assign('RapportListeElectorale', $this->getListeElectoraleRapport()); // Rapport de la liste électorale
		$this->assign('RapportNouveauxArrivants', $this->getNouveauxArrivantsRapport()); // Rapport des nouveaux arrivants
		$this->assign('RapportAnniversairesJeunes', $this->getAnniversairesJeunesRapport()); // Rapport des anniversaires des jeunes de moins de 18 ans
		$this->assign('RapportAnniversairesAgees', $this->getAnniversairesAgeesRapport()); // Rapport des personnes de plus de 75 ans


		parent::run();
	}


/***************************************/
/* DEFINITION DE LA PAROISSE CONCERNEE */
/***************************************/
// Flag pour les premières paroisses. Permet de changer les Option Listes.
	//  Bouclier = $flagBouclier

	public function getQuelleParoisse() {
//		$flagBouclier = FALSE; // réinitialisation de la variable
		
		$getFlagChurch = civicrm_api3('Domain', 'get', [
			'sequential' => 1,
			'return' => ["name"],
			]);// recherche du nom de la paroisse
		
		$result = $getFlagChurch['values'][0]['name'];
		
/*		switch ($flagChurch) {
			case "Eglise réformée du Bouclier":
				$flagBouclier = TRUE;
				break;
			default:
				break;
		}
*/
		return $result;
	}





/****************************************/
/* DEFINITION DES TEMPLATES DE RAPPORTS */
/****************************************/
/* Recherche de l'ID de Groupe pour les modèles de rapport */
	public function getIDGroupTemplate() {
		$result = civicrm_api3('OptionGroup', 'get', [
				'sequential' => 1,
				'name' => "report_template",
			]);
			
		return $result['id'];
	}

/* Template Liste Electorale */
	public function getListeElectoraleTemplate() {
		$params = [
			'option_group_id' => $this->getIDGroupTemplate(),
			'label' => "Liste Electorale",
			'value' => "fr.uepalparoisse.reports/listeelectorale",
			'name' => "CRM_Reports_Form_Report_ListeElectorale",
			'description' => "Modèle de rapport permettant de créer la liste électorale",
			'component_id' => "3",
			'is_active' => "1",
		];
		
		return $this->createOrGetTemplate($params);
	}

/* Template Liste des Groupes */	
	public function getListeGroupesTemplate() {
		$params = [
			'option_group_id' => $this->getIDGroupTemplate(),
			'label' => "Liste des Groupes",
			'value' => "fr.uepalparoisse.reports/listedesgroupes",
			'name' => "CRM_Reports_Form_Report_ListeDesGroupes",
			'description' => "Modèle de rapport permettant de créer des listes par groupe",
			'is_active' => "1",
		];
		
		return $this->createOrGetTemplate($params);
	}
	
/* Template Liste de Membres */	
	public function getListeMembresTemplate() {
		$params = [
			'option_group_id' => $this->getIDGroupTemplate(),
			'label' => "Liste des Membres",
			'value' => "fr.uepalparoisse.reports/listedesmembres",
			'name' => "CRM_Reports_Form_Report_ListeDesMembres",
			'description' => "Modèle de rapport permettant de créer des listes de membres",
			'component_id' => "3",
			'is_active' => "1",
		];
		
		return $this->createOrGetTemplate($params);
	}
	
/* Template Liste par Age */	
	public function getListeParAgeTemplate() {
		$params = [
			'option_group_id' => $this->getIDGroupTemplate(),
			'label' => "Liste Par Ages",
			'value' => "fr.uepalparoisse.reports/listeparages",
			'name' => "CRM_Reports_Form_Report_ListeParAges",
			'description' => "Modèle de rapport permettant de créer des listes par âge",
			'is_active' => "1",
		];
		
		return $this->createOrGetTemplate($params);
	}

/* Template Liste Groupes par Participants */	
	public function getListeParParticipantsTemplate() {
		$params = [
			'option_group_id' => $this->getIDGroupTemplate(),
			'label' => "Liste par Participants",
			'value' => "fr.uepalparoisse.reports/groupesparticipants",
			'name' => "CRM_Reports_Form_Report_GroupesParticipants",
			'description' => "Modèle de rapport permettant de créer des listes par participants",
			'is_active' => "1",
		];
		
		return $this->createOrGetTemplate($params);
	}





/****************************************/
/* DEFINITION DES INSTANCES DE RAPPORTS */
/****************************************/

/* Rapport Liste Electorale */
	public function getListeElectoraleRapport() {
		// besoin de séparer la création du rapport en fonction de la paroisse
		$flagChurch = $this->getQuelleParoisse();

		if ($flagChurch == "Eglise réformée du Bouclier") {
			$params = [
				'title' => "Liste électorale",
				'report_id' => "fr.uepalparoisse.reports/listedesmembres",
				'domain_id' => "1",
				'description' => "Rapport permettant d'afficher la liste électorale",
	            'permission' => "access CiviReport",
	            'grouprole' => array(
	                "utilisateur authentifié",
	                "administrator"
				),
				'form_values' => "a:40:{s:6:\"fields\";a:10:{s:9:\"sort_name\";s:1:\"1\";s:9:\"last_name\";s:1:\"1\";s:10:\"first_name\";s:1:\"1\";s:10:\"birth_date\";s:1:\"1\";s:14:\"street_address\";s:1:\"1\";s:11:\"postal_code\";s:1:\"1\";s:4:\"city\";s:1:\"1\";s:10:\"country_id\";s:1:\"1\";s:5:\"email\";s:1:\"1\";s:5:\"phone\";s:1:\"1\";}s:12:\"sort_name_op\";s:3:\"has\";s:15:\"sort_name_value\";s:0:\"\";s:15:\"contact_type_op\";s:2:\"in\";s:18:\"contact_type_value\";a:1:{i:0;s:10:\"Individual\";}s:13:\"is_deleted_op\";s:2:\"eq\";s:16:\"is_deleted_value\";s:1:\"0\";s:21:\"created_date_relative\";s:0:\"\";s:17:\"created_date_from\";s:0:\"\";s:15:\"created_date_to\";s:0:\"\";s:14:\"is_deceased_op\";s:2:\"eq\";s:17:\"is_deceased_value\";s:1:\"0\";s:6:\"id_min\";s:0:\"\";s:6:\"id_max\";s:0:\"\";s:5:\"id_op\";s:3:\"lte\";s:8:\"id_value\";s:0:\"\";s:29:\"membership_join_date_relative\";s:0:\"\";s:25:\"membership_join_date_from\";s:0:\"\";s:23:\"membership_join_date_to\";s:0:\"\";s:6:\"tid_op\";s:2:\"in\";s:9:\"tid_value\";a:1:{i:0;s:1:\"1\";}s:6:\"sid_op\";s:2:\"in\";s:9:\"sid_value\";a:3:{i:0;s:1:\"1\";i:1;s:1:\"8\";i:2;s:1:\"2\";}s:8:\"tagid_op\";s:2:\"in\";s:11:\"tagid_value\";a:0:{}s:6:\"gid_op\";s:2:\"in\";s:9:\"gid_value\";a:0:{}s:9:\"order_bys\";a:1:{i:1;a:2:{s:6:\"column\";s:9:\"sort_name\";s:5:\"order\";s:3:\"ASC\";}}s:11:\"description\";s:50:\"Rapport permettant d'afficher la liste électorale\";s:13:\"email_subject\";s:0:\"\";s:8:\"email_to\";s:0:\"\";s:8:\"email_cc\";s:0:\"\";s:9:\"row_count\";s:0:\"\";s:9:\"view_mode\";s:4:\"view\";s:13:\"cache_minutes\";s:2:\"60\";s:10:\"permission\";s:17:\"access CiviReport\";s:9:\"parent_id\";s:0:\"\";s:8:\"radio_ts\";s:0:\"\";s:6:\"groups\";s:0:\"\";s:11:\"instance_id\";N;}",
				'header' => "\r\n  \r\n    \r\n    \r\n    \r\n    \r\n  \r\n",
				'footer' => "\r\n\r\n",
			];
		}
		
		else {
			$params = [
				'title' => "Liste electorale",
				'report_id' => "fr.uepalparoisse.reports/listedesmembres",
				'domain_id' => "1",
				'description' => "Rapport permettant d'afficher la liste électorale",
	            'permission' => "access CiviReport",
	            'grouprole' => array(
	                "utilisateur authentifié",
	                "administrator"
				),
				'form_values' => "a:40:{s:6:\"fields\";a:10:{s:9:\"sort_name\";s:1:\"1\";s:9:\"last_name\";s:1:\"1\";s:10:\"first_name\";s:1:\"1\";s:10:\"birth_date\";s:1:\"1\";s:14:\"street_address\";s:1:\"1\";s:11:\"postal_code\";s:1:\"1\";s:4:\"city\";s:1:\"1\";s:10:\"country_id\";s:1:\"1\";s:5:\"email\";s:1:\"1\";s:5:\"phone\";s:1:\"1\";}s:12:\"sort_name_op\";s:3:\"has\";s:15:\"sort_name_value\";s:0:\"\";s:15:\"contact_type_op\";s:2:\"in\";s:18:\"contact_type_value\";a:1:{i:0;s:10:\"Individual\";}s:13:\"is_deleted_op\";s:2:\"eq\";s:16:\"is_deleted_value\";s:1:\"0\";s:21:\"created_date_relative\";s:0:\"\";s:17:\"created_date_from\";s:0:\"\";s:15:\"created_date_to\";s:0:\"\";s:14:\"is_deceased_op\";s:2:\"eq\";s:17:\"is_deceased_value\";s:1:\"0\";s:6:\"id_min\";s:0:\"\";s:6:\"id_max\";s:0:\"\";s:5:\"id_op\";s:3:\"lte\";s:8:\"id_value\";s:0:\"\";s:29:\"membership_join_date_relative\";s:0:\"\";s:25:\"membership_join_date_from\";s:0:\"\";s:23:\"membership_join_date_to\";s:0:\"\";s:6:\"tid_op\";s:2:\"in\";s:9:\"tid_value\";a:1:{i:0;s:1:\"1\";}s:6:\"sid_op\";s:2:\"in\";s:9:\"sid_value\";a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}s:8:\"tagid_op\";s:2:\"in\";s:11:\"tagid_value\";a:0:{}s:6:\"gid_op\";s:2:\"in\";s:9:\"gid_value\";a:0:{}s:9:\"order_bys\";a:1:{i:1;a:2:{s:6:\"column\";s:9:\"sort_name\";s:5:\"order\";s:3:\"ASC\";}}s:11:\"description\";s:50:\"Rapport permettant d'afficher la liste électorale\";s:13:\"email_subject\";s:0:\"\";s:8:\"email_to\";s:0:\"\";s:8:\"email_cc\";s:0:\"\";s:9:\"row_count\";s:0:\"\";s:9:\"view_mode\";s:4:\"view\";s:13:\"cache_minutes\";s:2:\"60\";s:10:\"permission\";s:17:\"access CiviReport\";s:9:\"parent_id\";s:0:\"\";s:8:\"radio_ts\";s:0:\"\";s:6:\"groups\";s:0:\"\";s:11:\"instance_id\";N;}",
				'header' => "\r\n  \r\n    \r\n    \r\n    \r\n    \r\n  \r\n",
				'footer' => "\r\n\r\n",
			];			
		}


		return $this->createOrGetRapport($params);
	}

/* Rapport des nouveaux arrivants */
	public function getNouveauxArrivantsRapport() {
	// pas besoin de séparer la création du rapport en fonction de la paroisse	

		$params = [
            'title' => "Liste des nouveaux arrivants (12 derniers mois)",
            'report_id' => "fr.uepalparoisse.reports/listedesmembres",
			'domain_id' => "1",
            'description' => "Liste des personnes inscrites dans la base durant les 12 derniers mois",
            'permission' => "access CiviReport",
            'grouprole' => array(
                "utilisateur authentifié",
                "administrator"
			),
            'form_values' => "a:41:{s:6:\"fields\";a:12:{s:9:\"sort_name\";s:1:\"1\";s:9:\"last_name\";s:1:\"1\";s:10:\"first_name\";s:1:\"1\";s:10:\"birth_date\";s:1:\"1\";s:12:\"contact_type\";s:1:\"1\";s:12:\"created_date\";s:1:\"1\";s:14:\"street_address\";s:1:\"1\";s:11:\"postal_code\";s:1:\"1\";s:4:\"city\";s:1:\"1\";s:10:\"country_id\";s:1:\"1\";s:5:\"email\";s:1:\"1\";s:5:\"phone\";s:1:\"1\";}s:12:\"sort_name_op\";s:3:\"has\";s:15:\"sort_name_value\";s:0:\"\";s:15:\"contact_type_op\";s:2:\"in\";s:18:\"contact_type_value\";a:1:{i:0;s:10:\"Individual\";}s:13:\"is_deleted_op\";s:2:\"eq\";s:16:\"is_deleted_value\";s:1:\"0\";s:21:\"created_date_relative\";s:11:\"ending.year\";s:17:\"created_date_from\";s:0:\"\";s:15:\"created_date_to\";s:0:\"\";s:14:\"is_deceased_op\";s:2:\"eq\";s:17:\"is_deceased_value\";s:1:\"0\";s:6:\"id_min\";s:0:\"\";s:6:\"id_max\";s:0:\"\";s:5:\"id_op\";s:3:\"lte\";s:8:\"id_value\";s:0:\"\";s:29:\"membership_join_date_relative\";s:0:\"\";s:25:\"membership_join_date_from\";s:0:\"\";s:23:\"membership_join_date_to\";s:0:\"\";s:6:\"tid_op\";s:2:\"in\";s:9:\"tid_value\";a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}s:6:\"sid_op\";s:5:\"notin\";s:9:\"sid_value\";a:2:{i:0;s:1:\"6\";i:1;s:1:\"7\";}s:8:\"tagid_op\";s:2:\"in\";s:11:\"tagid_value\";a:0:{}s:6:\"gid_op\";s:2:\"in\";s:9:\"gid_value\";a:0:{}s:9:\"order_bys\";a:1:{i:1;a:2:{s:6:\"column\";s:12:\"created_date\";s:5:\"order\";s:4:\"DESC\";}}s:11:\"description\";s:70:\"Liste des personnes inscrites dans la base durant les 12 derniers mois\";s:13:\"email_subject\";s:0:\"\";s:8:\"email_to\";s:0:\"\";s:8:\"email_cc\";s:0:\"\";s:9:\"row_count\";s:2:\"15\";s:9:\"view_mode\";s:4:\"view\";s:14:\"addToDashboard\";s:1:\"1\";s:13:\"cache_minutes\";s:4:\"1000\";s:10:\"permission\";s:17:\"access CiviReport\";s:9:\"parent_id\";s:0:\"\";s:8:\"radio_ts\";s:0:\"\";s:6:\"groups\";s:0:\"\";s:11:\"instance_id\";s:2:\"51\";}",
            'header' => "\r\n  \r\n    \r\n    \r\n    \r\n    \r\n  \r\n  ",
            'footer' => " \r\n\r\n",
            'is_reserved' => "0"
		];

		return $this->createOrGetRapport($params);
	}

/* Rapport des jeunes de moins de 18 ans */
	public function getAnniversairesJeunesRapport() {
		$params = [
            'title' => "Anniversaires des jeunes de moins de 18 ans",
            'report_id' => "fr.uepalparoisse.reports/listeparages",
			'domain_id' => "1",
            'description' => "Liste des jeunes de moins de 18 ans",
            'permission' => "access CiviReport",
            'grouprole' => array(
                "utilisateur authentifié",
                "administrator"
			),
            'form_values' => "a:42:{s:6:\"fields\";a:12:{s:9:\"sort_name\";s:1:\"1\";s:9:\"last_name\";s:1:\"1\";s:10:\"first_name\";s:1:\"1\";s:10:\"birth_date\";s:1:\"1\";s:3:\"age\";s:1:\"1\";s:14:\"street_address\";s:1:\"1\";s:11:\"postal_code\";s:1:\"1\";s:4:\"city\";s:1:\"1\";s:10:\"country_id\";s:1:\"1\";s:5:\"email\";s:1:\"1\";s:5:\"phone\";s:1:\"1\";s:18:\"membership_type_id\";s:1:\"1\";}s:12:\"sort_name_op\";s:3:\"has\";s:15:\"sort_name_value\";s:0:\"\";s:6:\"id_min\";s:0:\"\";s:6:\"id_max\";s:0:\"\";s:5:\"id_op\";s:3:\"lte\";s:8:\"id_value\";s:0:\"\";s:19:\"birth_date_relative\";s:0:\"\";s:15:\"birth_date_from\";s:0:\"\";s:13:\"birth_date_to\";s:0:\"\";s:7:\"age_min\";s:0:\"\";s:7:\"age_max\";s:0:\"\";s:6:\"age_op\";s:3:\"lte\";s:9:\"age_value\";s:2:\"18\";s:13:\"is_deleted_op\";s:2:\"eq\";s:16:\"is_deleted_value\";s:1:\"0\";s:14:\"is_deceased_op\";s:2:\"eq\";s:17:\"is_deceased_value\";s:1:\"0\";s:18:\"join_date_relative\";s:0:\"\";s:14:\"join_date_from\";s:0:\"\";s:12:\"join_date_to\";s:0:\"\";s:6:\"tid_op\";s:5:\"notin\";s:9:\"tid_value\";a:3:{i:0;s:1:\"4\";i:1;s:1:\"5\";i:2;s:1:\"6\";}s:6:\"sid_op\";s:2:\"in\";s:9:\"sid_value\";a:0:{}s:8:\"tagid_op\";s:2:\"in\";s:11:\"tagid_value\";a:0:{}s:6:\"gid_op\";s:2:\"in\";s:9:\"gid_value\";a:0:{}s:9:\"order_bys\";a:2:{i:1;a:2:{s:6:\"column\";s:10:\"birth_date\";s:5:\"order\";s:3:\"ASC\";}i:2;a:2:{s:6:\"column\";s:9:\"sort_name\";s:5:\"order\";s:3:\"ASC\";}}s:11:\"description\";s:35:\"Liste des jeunes de moins de 18 ans\";s:13:\"email_subject\";s:0:\"\";s:8:\"email_to\";s:0:\"\";s:8:\"email_cc\";s:0:\"\";s:9:\"row_count\";s:0:\"\";s:9:\"view_mode\";s:8:\"criteria\";s:13:\"cache_minutes\";s:2:\"60\";s:10:\"permission\";s:17:\"access CiviReport\";s:9:\"parent_id\";s:0:\"\";s:8:\"radio_ts\";s:0:\"\";s:6:\"groups\";s:0:\"\";s:11:\"instance_id\";s:2:\"60\";}",
            'header' => "\r\n  \r\n    \r\n    \r\n    \r\n    \r\n  \r\n  ",
            'footer' => " \r\n\r\n",
            'is_reserved' => "0"
		];

		return $this->createOrGetRapport($params);
	}


/* Rapport des personnes de plus de 75 ans */
	public function getAnniversairesAgeesRapport() {
		$params = [
            'title' => "Anniversaires des personnes de plus de 75 ans",
            'report_id' => "fr.uepalparoisse.reports/listeparages",
			'domain_id' => "1",
            'description' => "Liste des personnes de plus de 75 ans",
            'permission' => "access CiviReport",
            'grouprole' => array(
                "utilisateur authentifié",
                "administrator"
			),
            'form_values' => "a:42:{s:6:\"fields\";a:12:{s:9:\"sort_name\";s:1:\"1\";s:9:\"last_name\";s:1:\"1\";s:10:\"first_name\";s:1:\"1\";s:10:\"birth_date\";s:1:\"1\";s:3:\"age\";s:1:\"1\";s:14:\"street_address\";s:1:\"1\";s:11:\"postal_code\";s:1:\"1\";s:4:\"city\";s:1:\"1\";s:10:\"country_id\";s:1:\"1\";s:5:\"email\";s:1:\"1\";s:5:\"phone\";s:1:\"1\";s:18:\"membership_type_id\";s:1:\"1\";}s:12:\"sort_name_op\";s:3:\"has\";s:15:\"sort_name_value\";s:0:\"\";s:6:\"id_min\";s:0:\"\";s:6:\"id_max\";s:0:\"\";s:5:\"id_op\";s:3:\"lte\";s:8:\"id_value\";s:0:\"\";s:19:\"birth_date_relative\";s:0:\"\";s:15:\"birth_date_from\";s:0:\"\";s:13:\"birth_date_to\";s:0:\"\";s:7:\"age_min\";s:0:\"\";s:7:\"age_max\";s:0:\"\";s:6:\"age_op\";s:3:\"gte\";s:9:\"age_value\";s:2:\"75\";s:13:\"is_deleted_op\";s:2:\"eq\";s:16:\"is_deleted_value\";s:1:\"0\";s:14:\"is_deceased_op\";s:2:\"eq\";s:17:\"is_deceased_value\";s:1:\"0\";s:18:\"join_date_relative\";s:0:\"\";s:14:\"join_date_from\";s:0:\"\";s:12:\"join_date_to\";s:0:\"\";s:6:\"tid_op\";s:5:\"notin\";s:9:\"tid_value\";a:1:{i:0;s:1:\"5\";}s:6:\"sid_op\";s:2:\"in\";s:9:\"sid_value\";a:0:{}s:8:\"tagid_op\";s:2:\"in\";s:11:\"tagid_value\";a:0:{}s:6:\"gid_op\";s:2:\"in\";s:9:\"gid_value\";a:0:{}s:9:\"order_bys\";a:2:{i:1;a:2:{s:6:\"column\";s:10:\"birth_date\";s:5:\"order\";s:3:\"ASC\";}i:2;a:2:{s:6:\"column\";s:9:\"sort_name\";s:5:\"order\";s:3:\"ASC\";}}s:11:\"description\";s:37:\"Liste des personnes de plus de 75 ans\";s:13:\"email_subject\";s:0:\"\";s:8:\"email_to\";s:0:\"\";s:8:\"email_cc\";s:0:\"\";s:9:\"row_count\";s:0:\"\";s:9:\"view_mode\";s:8:\"criteria\";s:13:\"cache_minutes\";s:2:\"60\";s:10:\"permission\";s:17:\"access CiviReport\";s:9:\"parent_id\";s:0:\"\";s:8:\"radio_ts\";s:0:\"\";s:6:\"groups\";s:0:\"\";s:11:\"instance_id\";s:2:\"42\";}",
            'header' => "\r\n  \r\n    \r\n    \r\n    \r\n    \r\n  \r\n  ",
            'footer' => " \r\n\r\n",
            'is_reserved' => "0"
		];

		return $this->createOrGetRapport($params);
	}










/***********************************/
/* CREATION DES MODELES DE RAPPORT */
/***********************************/
	private function createOrGetTemplate($params) {
		try {
			$rapportType = civicrm_api3('ReportTemplate', 'getsingle', [
				'label' => $params['label'],
				'value' => $params['value'],
				]);
		}
		catch (Exception $e) {
			$rapportType = civicrm_api3('ReportTemplate', 'create', $params);
			CRM_Core_Session::setStatus('Modèle de rapport crée : '.$params['label'], 'Modèle de rapport crée', 'success');
		}
		
		return $rapportType;
	}



/**************************************/
/* CREATION DES INSTANCES DE RAPPORTS */
/**************************************/

	private function createOrGetRapport($params) {
		try {
			$rapportType = civicrm_api3('ReportInstance', 'getsingle', [
				'title' => $params['title'],
				]);
		}
		catch (Exception $e) {
			$rapportType = civicrm_api3('ReportInstance', 'create', $params);
			CRM_Core_Session::setStatus('Rapport crée : '.$params['title'], 'Rapport crée', 'success');
		}
		
		return $rapportType;
	}
		

/*
private function getListeElectoraleRapport() {
	try {
		$t = civicrm_api3('ReportInstance', 'create', [
			'title' => "Test-2-Liste Electorale",
			'report_id' => "fr.uepalparoisse.reports/listeelectorale",
			'domain_id' => "1",
			'description' => "Liste Electorale Test 2",
			'form_values' => "a:36:{s:6:\"fields\";a:11:{s:9:\"sort_name\";s:1:\"1\";s:9:\"last_name\";s:1:\"1\";s:10:\"first_name\";s:1:\"1\";s:18:\"membership_type_id\";s:1:\"1\";s:9:\"join_date\";s:1:\"1\";s:4:\"name\";s:1:\"1\";s:14:\"street_address\";s:1:\"1\";s:11:\"postal_code\";s:1:\"1\";s:4:\"city\";s:1:\"1\";s:10:\"country_id\";s:1:\"1\";s:5:\"phone\";s:1:\"1\";}s:12:\"sort_name_op\";s:3:\"has\";s:15:\"sort_name_value\";s:0:\"\";s:6:\"id_min\";s:0:\"\";s:6:\"id_max\";s:0:\"\";s:5:\"id_op\";s:3:\"lte\";s:8:\"id_value\";s:0:\"\";s:18:\"join_date_relative\";s:0:\"\";s:14:\"join_date_from\";s:0:\"\";s:12:\"join_date_to\";s:0:\"\";s:23:\"owner_membership_id_min\";s:0:\"\";s:23:\"owner_membership_id_max\";s:0:\"\";s:22:\"owner_membership_id_op\";s:3:\"lte\";s:25:\"owner_membership_id_value\";s:0:\"\";s:6:\"tid_op\";s:2:\"in\";s:9:\"tid_value\";a:1:{i:0;s:1:\"1\";}s:6:\"sid_op\";s:2:\"in\";s:9:\"sid_value\";a:1:{i:0;s:1:\"2\";}s:8:\"tagid_op\";s:2:\"in\";s:11:\"tagid_value\";a:0:{}s:6:\"gid_op\";s:2:\"in\";s:9:\"gid_value\";a:0:{}s:9:\"order_bys\";a:1:{i:1;a:2:{s:6:\"column\";s:9:\"sort_name\";s:5:\"order\";s:3:\"ASC\";}}s:11:\"description\";s:42:\"ListeElectorale (fr.uepalparoisse.reports)\";s:13:\"email_subject\";s:0:\"\";s:8:\"email_to\";s:0:\"\";s:8:\"email_cc\";s:0:\"\";s:9:\"row_count\";s:0:\"\";s:9:\"view_mode\";s:4:\"view\";s:13:\"cache_minutes\";s:2:\"60\";s:10:\"permission\";s:17:\"access CiviReport\";s:9:\"parent_id\";s:0:\"\";s:8:\"radio_ts\";s:0:\"\";s:6:\"groups\";s:0:\"\";}",
			'header' => "\r\n  \r\n    \r\n    \r\n    \r\n    \r\n  \r\n  
",
			'footer' => "


\r\n\r\n",
			

		]);

	  CRM_Core_Session::setStatus('Rapport crée', 'Rapport OK', 'success');
    }
    catch (CiviCRM_API3_Exception $ex) {
      CRM_Core_Session::setStatus('Rapport non crée', 'Rapport KO', 'error');
    }		
		

	
		return $t;
	}
*/















}
