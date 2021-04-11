<?php
use CRM_Reports_ExtensionUtil as E;

class CRM_Reports_Page_ListeAnniversaireJeunesse extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Jeunesse - liste des personnes âgés de moins de 18 ans'));

	// Assign variables for use in a template
		// Liste des Personnes âgés de plus de 75 ans
		$this->assign('AnniversaireJeunesse', $this->getAnniversaireJeunesseTable());

    parent::run();
  }

// Création de la table des personnes de plus de 75 ans
	private function getAnniversaireJeunesseTable() {
		$t = [];
		
		$sql = "
		SELECT
			c.id,
			c.display_name,
			c.last_name,
			c.first_name,
			a.street_address,
			a.postal_code,
			a.city,
			c.birth_date,
			e.email,
			p.phone
			
		FROM civicrm_contact AS c
		
		LEFT JOIN civicrm_membership AS m ON c.id = m.contact_id
		LEFT JOIN civicrm_address AS a ON c.id = a.contact_id AND a.is_primary = 1
		LEFT JOIN civicrm_email AS e ON c.id = e.contact_id AND e.is_primary = 1
		LEFT JOIN civicrm_phone AS p ON c.id = p.contact_id AND p.is_primary = 1
		
		WHERE (
			(c.contact_type IN ('Individual'))
			AND ((YEAR(c.birth_date)) >= (YEAR(NOW()) - 18))
			
			AND (c.is_deceased = '0')
			AND (c.is_deleted = '0')
			AND (m.membership_type_id NOT IN ('7', '4', '5', '6'))
			)
		ORDER BY c.birth_date ASC
		";

/*
AND (c.birth_date <= (CURRENT_DATE - 365*75))

CODE pour RELATION
r.phone
LEFT JOIN civicrm_relationship AS r ON c.id = r.contact_id_a AND (r.relationship_type_id = '8' AND r.is_active = '1')
		LEFT JOIN {civicrm_relationship} civicrm_relationship ON c.id = civicrm_relationship.contact_id_a
		LEFT JOIN {c} civicrm_contact_civicrm_relationship ON civicrm_relationship.contact_id_b = civicrm_contact_civicrm_relationship.id

LEFT JOIN {civicrm_phone} civicrm_contact_civicrm_relationship__civicrm_phone ON civicrm_contact_civicrm_relationship.id = civicrm_contact_civicrm_relationship__civicrm_phone.contact_id


(c.birth_date <= '1952-09-23 09:35:35')

			AND (DATEDIFF(day,c.birth_date,getdate())/365 >= 75)


/*
+-----+--------------+
| id  | display_name |
+-----+--------------+
*/
	
		$dao = CRM_Core_DAO::executeQuery($sql);
		
		while ($dao->fetch()) {
			$t[] = [
				$dao->id,
				$dao->last_name,
				$dao->first_name,
				$dao->birth_date,
				$dao->email,
				$dao->phone,
				$dao->street_address,
				$dao->postal_code,
				$dao->city,
				];
		}
		
		return $t;
	}








}
