<?php
use CRM_Reports_ExtensionUtil as E;

class CRM_Reports_Page_ListeElectorale extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Liste électorale de la paroisse'));

    
	// Assign variables for use in a template
		// Liste électorale
		$this->assign('ListeElectorale', $this->getListeElectoraleTable());

    parent::run();
  }

// Création de la liste électorale
	private function getListeElectoraleTable() {
		$t = [];
		
		$sql = "
		SELECT
			c.id,
			c.display_name,
			c.last_name,
			c.first_name,
			c.birth_date,
			ms.name,
			a.street_address,
			a.postal_code,
			a.city,
			a.country_id,
			e.email,
			p.phone  
		FROM civicrm_contact AS c
        INNER JOIN civicrm_membership AS m
			ON c.id = m.contact_id AND m.is_test = 0
		LEFT JOIN civicrm_membership_status AS ms
			ON ms.id = m.status_id 
		LEFT JOIN civicrm_address AS a
			ON (c.id = a.contact_id) AND a.is_primary = 1
		LEFT JOIN civicrm_phone AS p
            ON c.id = p.contact_id AND p.is_primary = 1
		LEFT JOIN  civicrm_email AS e
			ON c.id = e.contact_id AND e.is_primary = 1 

		WHERE  
			m.membership_type_id IN (1)
			AND ms.id IN (2)
			AND c.is_deleted = 0
			AND c.is_deceased = 0
			AND c.contact_type IN  ('Individual')
		ORDER BY c.last_name ASC, c.first_name ASC
		";

/*
+-----+--------------+
| id  | display_name |
+-----+--------------+
*/
	
		$dao = CRM_Core_DAO::executeQuery($sql);
		
		while ($dao->fetch()) {
			$t[] = [
				$dao->id,
				$dao->display_name,
				$dao->last_name,
				$dao->first_name,
				$dao->birth_date,
				$dao->email,
				$dao->phone,
				$dao->name,
				$dao->street_address,
				$dao->postal_code,
				$dao->city,
				$dao->country_id
				];
		}
		
		return $t;
	}





}
