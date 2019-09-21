INSERT INTO `journal` (`id`, `code`, `label`, `discr`) VALUES
(NULL, 'BQ', 'Journal de banque', ''),,
(NULL, 'CA', 'Journal de caisse', '');

INSERT INTO `tiers` (`id`, `code`, `label`, `discr`) VALUES
(NULL, 'FOU01', 'Fournisseur 01', ''),
(NULL, 'FOU02', 'Fournisseur 02', ''),
(NULL, 'FOU03', 'Fournisseur 03', ''),
(NULL, 'CLI01', 'Client 01', ''),
(NULL, 'CLI02', 'Client 02', '');

INSERT INTO `accountable_account` (`id`, `journal_id`, `code`, `label`, `discr`) VALUES
(NULL, '1', 'BQ1', 'Compte courant', ''),
(NULL, '1', 'BQ2', 'Livret A', ''), 
(NULL, '2', 'CROUGE', 'Caisse rouge principale', '');

INSERT INTO `entry` (`id`, `project_id`, `entry_parent_id`, `reference`, `accounting_date`, `value_date`, `amount`, `active`, `updated_at`, `accountable_account_id`) VALUES
(NULL, NULL, NULL, 'facture fournisseur 01', '2019-08-19', '2019-08-19', '-7000', '1', '2019-08-20', '1'),
(NULL, NULL, NULL, 'facture Fournisseur 01', '2019-08-18', '2019-08-19', '-152', '1', '2019-08-15', '1'),
(NULL, NULL, NULL, 'facture fournisseur 02', '2019-08-05', '2019-08-02', '-4560', '1', '2019-08-15', '1'),
(NULL, NULL, NULL, 'Avoir fournisseur 02', '2019-07-31', '2019-08-01', '4100', '1', '2019-08-15', '1'),
(NULL, NULL, NULL, 'versement interne', '2019-07-01', '2019-07-01', '150000', '1', '2019-08-15', '2'),
(NULL, NULL, NULL, 'Interêt 2019', '2019-01-01', '2019-01-01', '2800', '1', '2019-08-15', '2'),
(NULL, NULL, NULL, 'versement initial', '2019-09-01', '2019-09-01', '85165', '1', '2019-08-15', '3'),
(NULL, NULL, NULL, 'Facture client 01', '2019-07-29', '2019-07-28', '1200', '1', '2019-08-15', '1'),
(NULL, NULL, NULL, 'Facture client 02', '2019-07-25', '2019-07-20', '202000', '1', '2019-07-03', '1');


INSERT INTO `solde` (`id`, `accountable_account_id`, `date`, `amount`, `is_prev`, `updated_at`) VALUES
(NULL, '1', '2019-08-31 00:00:00', '195588', '0', '2019-08-31 15:00:00'),
(NULL, '2', '2019-08-31', '152800', '0', '2019-08-31 15:30:00'),
(NULL, '3', '2019-08-31', '85165', '0', '2019-08-31 12:00:00');

