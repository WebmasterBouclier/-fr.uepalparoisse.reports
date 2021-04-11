<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Reports_Form_Report_ListeParAges',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'ListeParAges',
      'description' => 'ListeParAges (fr.uepalparoisse.reports)',
      'class_name' => 'CRM_Reports_Form_Report_ListeParAges',
      'report_url' => 'fr.uepalparoisse.reports/listeparages',
      'component' => '',
    ],
  ],
];
