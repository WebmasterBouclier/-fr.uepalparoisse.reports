<?php
use CRM_Reports_ExtensionUtil as E;
/*TO DO
- Filtrer sur Individual
- Mettre le numéro de téléphone Fixe (par Relation)
- Rajouter le mois et le jour de naissance
- Vérifier Exclusion décédés et deleted
*/

class CRM_Reports_Form_Report_ListeParAges extends CRM_Report_Form {

	protected $_addressField = FALSE;

	protected $_emailField = FALSE;

	protected $_summary = NULL;

	protected $_customGroupExtends = NULL;

	protected $_customGroupGroupBy = FALSE;


	function __construct() {
		$this->_columns = array(
			'civicrm_contact' => array(
				'dao' => 'CRM_Contact_DAO_Contact',
				'fields' => array(
					'id' => array(
						'no_display' => TRUE,
						'required' => TRUE,
						),
					'sort_name' => array(
						'title' => E::ts('Contact Name'),
						'required' => FALSE,
						'default' => TRUE,
						'no_repeat' => TRUE,
					),
					'last_name' => array(
						'title' => E::ts('Last Name'),
						'default' => TRUE,
						'required' => FALSE,
					),
					'first_name' => array(
						'title' => E::ts('First Name'),
						'default' => TRUE,
						'required' => FALSE,
					),
					'birth_date' => array(
						'title' => E::ts('Birth Date'),
						'type' => CRM_Utils_Type::T_DATE,
						'default' => TRUE,
						'required' => FALSE,
					),
					'birthday' => array(
						'title' => E::ts('Birthday'),
						'type' => CRM_Utils_Type::T_DATE,
						'no_repeat' => TRUE,
						'required' => FALSE,
						'default' => FALSE,
						'no_display' => TRUE,
					),
					'age' => array(
						'title' => E::ts('Age'),
						'default' => TRUE,
						'required' => FALSE,
					),
				),
				'filters' => array(
					'sort_name' => array(
						'title' => E::ts('Contact Name'),
						'operator' => 'like',
					),
					'id' => array(
						'no_display' => TRUE,
					),
					'birth_date' => array(
						'title' => E::ts('Birth Date'),
						'operatorType' => CRM_Report_Form::OP_DATE,
						'type' => CRM_Utils_Type::T_DATE,
					),
					// A GARDER ?
/*NE MARCHE PAS
					'birthday' => array(
						'title' => E::ts('Anniversaire'),
						'operatorType' => CRM_Report_Form::OP_DATE,
						'type' => CRM_Utils_Type::T_DATE,
						'no_display' => TRUE,
					),
*/					
					'age' => array(
						'title' => E::ts('Age'),
						'type' => CRM_Utils_Type::T_INT,
					),
					'is_deleted' => [
						'title' => ts('Is Deleted'),
						'default' => 0,
						'type' => CRM_Utils_Type::T_BOOLEAN,
					],
					'is_deceased' => array(
						'title' => E::ts('Is deceased'),
						'type' => CRM_Utils_Type::T_BOOLEAN,
						'default' => 0,
					),
				),
				'grouping' => 'contact-fields',

				'group_bys' => [
					'sort_name' => [
						'title' => E::ts('Contact Name')
					],
				],

		
				'order_bys' => array(
					'sort_name' => array(
						'title' => E::ts('Last Name, First Name'),
						'default' => '1',
						'default_weight' => '0',
						'default_order' => 'ASC',
					),
					'birth_date' => array(
						'title' => E::ts('Birth Date'),
						'default_order' => 'ASC',
					),
					
				),  
			),
			
	        'civicrm_address' => array(
				'dao' => 'CRM_Core_DAO_Address',
				'fields' => array(
					'street_address' => array(
						'required' => FALSE,
						'default' => TRUE,
					),
					'postal_code' => array(
						'required' => FALSE,
						'default' => TRUE,
					),
					'city' => array(
						'required' => FALSE,
						'default' => TRUE,
					),
					'country_id' => array(
						'title' => E::ts('Country'),
						'required' => FALSE	,
						'default' => TRUE,
					),
				),
				'grouping' => 'contact-fields',
			),

			'civicrm_email' => array(
				'dao' => 'CRM_Core_DAO_Email',
				'fields' => array(
					'email' => array(
						'title' => ts('Email'),
						'no_repeat' => TRUE,
						'required' => FALSE,
						'default' => TRUE,
					),
				),
				'grouping' => 'contact-fields',
			),

			'civicrm_phone' => array(
				'dao' => 'CRM_Core_DAO_Phone',
				'fields' => array(
					'phone' => array(
						'required' => FALSE,
						'default' => TRUE,
					),
				),
				'grouping' => 'contact-fields',
			),

			'civicrm_membership' => array(
				'dao' => 'CRM_Member_DAO_Membership',
				'fields' => array(
					'membership_type_id' => array(
						'title' => E::ts('Membership Type'),
						'required' => FALSE,
						'no_repeat' => TRUE,
						'default' => TRUE,
					),
					'join_date' => array(
						'title' => E::ts('Join Date'),
						'default' => FALSE,
						'required' => FALSE,
					),
				),
				'filters' => array(
					'join_date' => array(
						'title' => E::ts('Join Date'),
						'operatorType' => CRM_Report_Form::OP_DATE,
					),
					'tid' => array(
						'name' => 'membership_type_id',
						'title' => E::ts('Membership Types'),
						'type' => CRM_Utils_Type::T_INT,
						'operatorType' => CRM_Report_Form::OP_MULTISELECT,
						'options' => CRM_Member_PseudoConstant::membershipType(),
					//'default' => 1, // 1 = Electeur
					),
				),
				'grouping' => 'member-fields',
			),

			'civicrm_membership_status' => array(
				'dao' => 'CRM_Member_DAO_MembershipStatus',
				'alias' => 'mem_status',
				'fields' => array(
					'name' => array(
						'title' => E::ts('Status'),
						'default' => FALSE,
						'required' => FALSE,
					),
				),
				'filters' => array(
					'sid' => array(
						'name' => 'id',
						'title' => E::ts('Status'),
						'type' => CRM_Utils_Type::T_INT,
						'operatorType' => CRM_Report_Form::OP_MULTISELECT,
						'options' => CRM_Member_PseudoConstant::membershipStatus(NULL, NULL, 'label'),
						//'default' => 2, // 2 = Courant
					),
				),
				'grouping' => 'member-fields',
			),
		);
    
		$this->_groupFilter = TRUE;
		$this->_tagFilter = TRUE;
		parent::__construct();
	}

	function preProcess() {
		$this->assign('reportTitle', E::ts('Birthday Report'));
		parent::preProcess();
	}

	function select() {
		$select = $this->_columnHeaders = array();

		foreach ($this->_columns as $tableName => $table) {
			if (array_key_exists('fields', $table)) {
				foreach ($table['fields'] as $fieldName => $field) {
					if (CRM_Utils_Array::value('required', $field) || CRM_Utils_Array::value($fieldName, $this->_params['fields'])) {

						if ($fieldName == 'birthday') {
							$select[] = "DATE_ADD({$this->_aliases['civicrm_contact']}.birth_date, INTERVAL YEAR(CURDATE() - INTERVAL 2 DAY) - YEAR({$this->_aliases['civicrm_contact']}.birth_date) + IF(DAYOFYEAR(CURDATE() - INTERVAL 2 DAY) >= DAYOFYEAR({$this->_aliases['civicrm_contact']}.birth_date),1,0) YEAR) AS birthday";
							$this->_columnHeaders["birthday"]['title'] = $field['title'];
							$this->_columnHeaders["birthday"]['type'] = CRM_Utils_Array::value('type', $field);
						}
						elseif ($fieldName == 'age') {
							$select[] = "(YEAR(DATE_ADD({$this->_aliases['civicrm_contact']}.birth_date, INTERVAL YEAR(CURDATE() - INTERVAL 2 DAY) - YEAR({$this->_aliases['civicrm_contact']}.birth_date) + IF(DAYOFYEAR(CURDATE() - INTERVAL 2 DAY) >= DAYOFYEAR({$this->_aliases['civicrm_contact']}.birth_date),1,0) YEAR)) - YEAR({$this->_aliases['civicrm_contact']}.birth_date)) AS age";
							$this->_columnHeaders["age"]['title'] = $field['title'];
							$this->_columnHeaders["age"]['type'] = CRM_Utils_Array::value('type', $field);
						}
						else {
							if ($tableName == 'civicrm_email') {
								$this->_emailField = TRUE;
							}

							$select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
							$this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
							$this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
						}
					}
				}
			}
		}

		$this->_select = "SELECT " . implode(', ', $select) . " ";
	}

	function from() {
		$this->_from = NULL;

		$this->_from = "
			FROM  civicrm_contact {$this->_aliases['civicrm_contact']} {$this->_aclFrom}
			INNER JOIN civicrm_membership {$this->_aliases['civicrm_membership']}
				ON {$this->_aliases['civicrm_contact']}.id =
                {$this->_aliases['civicrm_membership']}.contact_id AND {$this->_aliases['civicrm_membership']}.is_test = 0
			LEFT  JOIN civicrm_membership_status {$this->_aliases['civicrm_membership_status']}
				ON {$this->_aliases['civicrm_membership_status']}.id =
                {$this->_aliases['civicrm_membership']}.status_id ";

    //used when email field is selected
/*    if ($this->_emailField) {
      $this->_from .= "
              LEFT JOIN civicrm_email {$this->_aliases['civicrm_email']}
                        ON {$this->_aliases['civicrm_contact']}.id =
                           {$this->_aliases['civicrm_email']}.contact_id AND
                           {$this->_aliases['civicrm_email']}.is_primary = 1\n";
    }
*/	
	$this->joinAddressFromContact();
	$this->joinEmailFromContact();
	$this->joinPhoneFromContact();
	$this->joinCountryFromAddress();
	
  }

  function where() {
    $clauses = array();
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('filters', $table)) {
        foreach ($table['filters'] as $fieldName => $field) {
          $clause = NULL;
          if ($fieldName == 'birthday') {
            $field['name'] = "DATE_ADD({$this->_aliases['civicrm_contact']}.birth_date, INTERVAL YEAR(CURDATE() - INTERVAL 2 DAY) - YEAR({$this->_aliases['civicrm_contact']}.birth_date) + IF(DAYOFYEAR(CURDATE() - INTERVAL 2 DAY) >= DAYOFYEAR({$this->_aliases['civicrm_contact']}.birth_date),1,0) YEAR) ";
          }
          elseif ($fieldName == 'age') {
            $field['dbAlias'] = "(YEAR(DATE_ADD({$this->_aliases['civicrm_contact']}.birth_date, INTERVAL YEAR(CURDATE() - INTERVAL 2 DAY) - YEAR({$this->_aliases['civicrm_contact']}.birth_date) + IF(DAYOFYEAR(CURDATE() - INTERVAL 2 DAY) >= DAYOFYEAR({$this->_aliases['civicrm_contact']}.birth_date),1,0) YEAR)) - YEAR({$this->_aliases['civicrm_contact']}.birth_date)) ";
          }

          if (CRM_Utils_Array::value('operatorType', $field) & CRM_Utils_Type::T_DATE) {
            $relative = CRM_Utils_Array::value("{$fieldName}_relative", $this->_params);
            $from     = CRM_Utils_Array::value("{$fieldName}_from", $this->_params);
            $to       = CRM_Utils_Array::value("{$fieldName}_to", $this->_params);

            $clause = $this->dateClause($field['name'], $relative, $from, $to, $field['type']);
          }
          else {
            $op = CRM_Utils_Array::value("{$fieldName}_op", $this->_params);
            if ($op) {
              $clause = $this->whereClause($field,
                $op,
                CRM_Utils_Array::value("{$fieldName}_value", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_min", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_max", $this->_params)
              );
            }
          }

          if (!empty($clause)) {
            $clauses[] = $clause;
          }
        }
      }
    }

    // only contacts with birthdays
    $clauses[] = "({$this->_aliases['civicrm_contact']}.birth_date IS NOT NULL)";

    // no deleted contacts (see https://github.com/systopia/de.systopia.birthdays/issues/10)
    $clauses[] = "(({$this->_aliases['civicrm_contact']}.is_deleted IS NULL) OR ({$this->_aliases['civicrm_contact']}.is_deleted = 0))";
	$clauses[] = "({$this->_aliases['civicrm_contact']}.is_deceased = 0)"; //RAJOUT du 21.10.2018 pour enlever personnes décédées
	
    $this->_where = "WHERE " . implode(' AND ', $clauses);

    if ($this->_aclWhere) {
      $this->_where .= " AND {$this->_aclWhere} ";
    }
  }

/* A REMETTRE ?
  function orderBy() {
    $this->_orderBy = " ORDER BY birthday ASC ";
  }
*/
  function postProcess() {

    $this->beginPostProcess();

    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    $sql = $this->buildQuery(TRUE);

    $rows = array();
    $this->buildRows($sql, $rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }

  function alterDisplay(&$rows) {
    // custom code to alter rows
    $entryFound = FALSE;
    $checkList = array();
    foreach ($rows as $rowNum => $row) {

      if (!empty($this->_noRepeats) && $this->_outputMode != 'csv') {
        // not repeat contact display names if it matches with the one in previous row
        $repeatFound = FALSE;
        foreach ($row as $colName => $colVal) {
          if (CRM_Utils_Array::value($colName, $checkList) &&
            is_array($checkList[$colName]) &&
            in_array($colVal, $checkList[$colName])
          ) {
            $rows[$rowNum][$colName] = "";
            $repeatFound = TRUE;
          }
          if (in_array($colName, $this->_noRepeats)) {
            $checkList[$colName][] = $colVal;
          }
        }
      }
// Changement de la colonne pour mettre le lien CiviCRM
      if (array_key_exists('civicrm_contact_sort_name', $row) &&
        $rows[$rowNum]['civicrm_contact_sort_name'] &&
        array_key_exists('civicrm_contact_id', $row)
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view",
          'reset=1&cid=' . $row['civicrm_contact_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
        $rows[$rowNum]['civicrm_contact_sort_name_hover'] = E::ts("View Contact Summary for this Contact.");
        $entryFound = TRUE;
      }

// Changement de la colonne pour mettre le nom du pays
      if (array_key_exists('civicrm_address_country_id', $row)) {
        if ($value = $row['civicrm_address_country_id']) {
          $rows[$rowNum]['civicrm_address_country_id'] = CRM_Core_PseudoConstant::country($value, FALSE);
        }
        $entryFound = TRUE;
      }

// Changement de la colonne pour mettre le statut Membre
      if (array_key_exists('civicrm_membership_membership_type_id', $row)) {
        if ($value = $row['civicrm_membership_membership_type_id']) {
          $rows[$rowNum]['civicrm_membership_membership_type_id'] = CRM_Member_PseudoConstant::membershipType($value, FALSE);
        }
        $entryFound = TRUE;
      }


      if (!$entryFound) {
        break;
      }
    }
  }

  /**
   * Override getOperationPair to add 'in', 'not in' for 'age' field
   */
  public function getOperationPair($type = 'string', $fieldName = NULL) {
    $operations = parent::getOperationPair($type, $fieldName);
    if ($fieldName == 'age') {
      $operations += parent::getOperationPair(CRM_Report_Form::OP_MULTISELECT, $fieldName);
    }
    return $operations;
  }

	
		
// Rajout d'une limite d'âge - NE SERT SANS DOUTE A RIEN
/*	function where() {
		parent::where();
		$this->_where .= "AND (YEAR(NOW()) - YEAR({$this->_aliases['civicrm_contact']}.birth_date)) >= 75";
	}
*/	

/*	public function __construct() {
		parent::__construct();
		
		$this->_columns = array(
			'civicrm_contact' => array(
				'dao' => 'CRM_Contact_DAO_Contact',
				'fields' => array(
					'sort_name' => array(
						'title' => E::ts('Contact Name'),
						'required' => TRUE,
						'default' => TRUE,
						'no_repeat' => TRUE,
					),
					'id' => array(
						'no_display' => TRUE,
						'required' => TRUE,
					),
					'last_name' => array(
						'title' => E::ts('Last Name'),
						'no_repeat' => TRUE,
						'default' => TRUE,
						'required' => TRUE,
					),
					'first_name' => array(
						'title' => E::ts('First Name'),
						'no_repeat' => TRUE,
						'default' => TRUE,
						'required' => TRUE,
					),
					'birth_date' => array(
						'title' => E::ts('Birth date'),
						'required' => TRUE,
						'default' => TRUE,
						'no_repeat' => TRUE,
					),
					'age' => array (
						'title' => ts('Age'),
						'dbAlias' => 'TIMESTAMPDIFF(YEAR, contact_civireport.birth_date, CURDATE())',
					),

/*					'age' => array(
						'title' => ts('Age', array('domain'	 => 'de.systopia.birthdays')),
						'type' => CRM_Utils_Type::T_INT,
					),
*//*				),
				'filters' => array(
					'sort_name' => array(
						'title' => E::ts('Contact Name'),
						'operator' => 'like',
					),
					'id' => array(
						'no_display' => TRUE,
					),
					'age' => array(
						'title' => ts('Age', array('domain' => 'de.systopia.birthdays')),
						'type' => CRM_Utils_Type::T_INT,
					),
				),
			),
		);
		
/*		$this->_columns .= array(
			'civicrm_address' => array(
				'dao' => 'CRM_Core_DAO_Address',
				'fields' => array(
					'street_address' => array(
						'required' => TRUE,
						'default' => TRUE,
					),
					'postal_code' => array(
						'required' => TRUE,
						'default' => TRUE,
					),
					'city' => array(
						'required' => TRUE,
						'default' => TRUE,
					),
          /*'state_province_id' => array('title' => E::ts('State/Province')),*/
/*					'country_id' => array(
						'title' => E::ts('Country'),
						'required' => TRUE,
						'default' => TRUE,
					),
				),
				'grouping' => 'contact-fields',
			),
		);


	
	}
*/	
	
	
	

  /**
   * Add field specific select alterations.
   *
   * @param string $tableName
   * @param string $tableKey
   * @param string $fieldName
   * @param array $field
   *
   * @return string
   */
  
/*  
  function selectClause(&$tableName, $tableKey, &$fieldName, &$field) {
    return parent::selectClause($tableName, $tableKey, $fieldName, $field);
  }
*/



  /**
   * Add field specific where alterations.
   *
   * This can be overridden in reports for special treatment of a field
   *
   * @param array $field Field specifications
   * @param string $op Query operator (not an exact match to sql)
   * @param mixed $value
   * @param float $min
   * @param float $max
   *
   * @return null|string
   */
/*
  public function whereClause(&$field, $op, $value, $min, $max) {
    return parent::whereClause($field, $op, $value, $min, $max);
  }
*/


}
