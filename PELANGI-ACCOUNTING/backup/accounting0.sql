-- Adminer 5.4.1 PostgreSQL 16.10 dump

DROP TABLE IF EXISTS "account_journal_entry";
CREATE TABLE "public"."account_journal_entry" (
    "account_id" bigint NOT NULL,
    "journal_entry_id" bigint NOT NULL
)
WITH (oids = false);


DROP TABLE IF EXISTS "account_templates";
DROP SEQUENCE IF EXISTS account_templates_id_seq;
CREATE SEQUENCE account_templates_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."account_templates" (
    "id" bigint DEFAULT nextval('account_templates_id_seq') NOT NULL,
    "code" character varying(255) NOT NULL,
    "name" character varying(255) NOT NULL,
    "description" text,
    "account_type" character varying(255) NOT NULL,
    "classification_type" character varying(255) NOT NULL,
    "is_header" boolean DEFAULT false NOT NULL,
    "is_cash_bank" boolean DEFAULT false NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "cash_flow" character varying(255) DEFAULT 'undefined' NOT NULL,
    "parent_code" character varying(255),
    "level" integer DEFAULT '1' NOT NULL,
    "template_name" character varying(255) NOT NULL,
    "notes" text,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "account_templates_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE INDEX account_templates_template_name_code_index ON public.account_templates USING btree (template_name, code);

CREATE INDEX account_templates_template_name_parent_code_index ON public.account_templates USING btree (template_name, parent_code);

INSERT INTO "account_templates" ("id", "code", "name", "description", "account_type", "classification_type", "is_header", "is_cash_bank", "is_active", "cash_flow", "parent_code", "level", "template_name", "notes", "created_at", "updated_at") VALUES
(542,	'1',	'Harta',	'Asset',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(543,	'100000000',	'Harta',	'Asset',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'1',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(544,	'110000000',	'Harta Lancar',	'Current Asset',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'1',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(545,	'111000000',	'Kas Dan Setara Kas',	'Cash & Equivalent',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'110000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(546,	'111100000',	'Kas',	'Cash',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'111000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(547,	'111100001',	'Kas Kecil',	'Petty Cash',	'current_asset',	'asset',	'0',	'1',	'1',	'operating',	'111100000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(548,	'111100002',	'Kas',	'Kas',	'current_asset',	'asset',	'0',	'1',	'1',	'operating',	'111100000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(549,	'111200000',	'Dompet Digital',	'E-Wallet',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'111000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(550,	'111200001',	'Gopay',	'Gopay',	'current_asset',	'asset',	'0',	'1',	'1',	'operating',	'111200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(551,	'111200002',	'Ovo',	'Ovo',	'current_asset',	'asset',	'0',	'1',	'1',	'operating',	'111200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(552,	'111200003',	'ShopeePay',	'ShopeePay',	'current_asset',	'asset',	'0',	'1',	'1',	'operating',	'111200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(553,	'111200099',	'Dompet Digital Lain',	'Other E-Wallet',	'current_asset',	'asset',	'0',	'1',	'1',	'operating',	'111200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(554,	'111300000',	'Bank',	'Bank',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'1',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(555,	'111300001',	'Bank',	'Bank',	'current_asset',	'asset',	'0',	'1',	'1',	'operating',	'111300000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(556,	'112000000',	'Surat Berharga',	'Securities',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'110000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(557,	'112000001',	'Deposito',	'Deposito',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'112000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(558,	'113000000',	'Piutang',	'Account Receivable',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'110000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(559,	'113100000',	'Piutang Usaha',	'Piutang Usaha',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'1',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(560,	'113100001',	'Piutang Usaha',	'Piutang Usaha',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'113100000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(561,	'113200000',	'Piutang Lain',	'Other Receivable',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'113000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(562,	'113200001',	'Piutang Belum Terbit Faktur',	'Account Receivable Have Not Been Invoiced',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'113200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(563,	'113200002',	'Piutang Giro',	'Post-dated Receivable',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'113200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(564,	'113200003',	'Piutang Karyawan',	'Employee Receivable',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'113200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(565,	'113200004',	'Piutang Pihak Ketiga',	'Third Party Receivable',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'113200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(566,	'113200099',	'Piutang Lain',	'Piutang Lain',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'113200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(567,	'113300000',	'Cadangan Kerugian Piutang',	'Allowance For Doubtful Receivable',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'113000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(568,	'113300001',	'Cadangan Kerugian Piutang Usaha',	'Cadangan Kerugian Piutang Usaha',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'113300000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(569,	'113300099',	'Cadangan Kerugian Piutang Lainnya',	'Allowance For Doubtful Other Receivable',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'113300000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(570,	'114000000',	'Persediaan',	'Persediaan',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'1',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(571,	'114000001',	'Persediaan',	'Persediaan',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'114000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(572,	'115000000',	'Uang Muka Pengeluaran',	'Advance Expenditure',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'110000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(573,	'115000001',	'Uang Muka Pengeluaran Umum',	'General Advance Expenditure',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'115000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(574,	'116000000',	'Biaya Dibayar Dimuka',	'Prepaid Expenses',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'110000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(575,	'116100000',	'Biaya Dibayar Dimuka Umum',	'General Prepaid Expenses',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'116000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(576,	'116100001',	'Sewa Dibayar Dimuka',	'Prepaid Rent',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116100000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(577,	'116100002',	'Asuransi Dibayar Dimuka',	'Prepaid Insurance',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116100000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(578,	'116100099',	'Biaya Dibayar Dimuka Lain',	'Other Prepaid Expense',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116100000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(651,	'310000000',	'Modal',	'Equity',	'equity',	'equity',	'1',	'0',	'1',	'undefined',	'300000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(579,	'116200000',	'Pajak Dibayar Dimuka',	'Prepaid Taxes',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'116000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(580,	'116200001',	'PPN Masukan',	'VAT In',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(581,	'116200002',	'PPh Pasal 23-2 Dibayar Dimuka',	'Prepaid Income Tax Article 23-2',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(582,	'116200003',	'PPh Pasal 23-4 Dibayar Dimuka',	'Prepaid Income Tax Article 23-4',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(583,	'116200004',	'PPh Pasal 4 ayat 2 Dibayar Dimuka',	'Prepaid Income Tax Article 4 paragraph 2',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(584,	'116200005',	'PPh Pasal 25 Dibayar Dimuka',	'Prepaid Income Tax Article 25',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(585,	'116200006',	'Pajak Dibayar Dimuka',	'Pajak Dibayar Dimuka',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(586,	'116200007',	'PPh 23-2 Dibayar Dimuka',	'PPh 23-2 Dibayar Dimuka',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(587,	'116200008',	'PPh 4.2 Dibayar Dimuka',	'PPh 4.2 Dibayar Dimuka',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(588,	'116200009',	'PPh 23-4 Dibayar Dimuka',	'PPh 23-4 Dibayar Dimuka',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(589,	'116200010',	'PPN Masukan',	'PPN Masukan',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(590,	'116200011',	'ppnbm20 Dibayar Dimuka',	'ppnbm20 Dibayar Dimuka',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'116200000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(591,	'117000000',	'Pendapatan Yang Akan Diterima',	'Accrued Income',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'110000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(592,	'117000001',	'Pendapatan Yang Akan Diterima',	'Accrued Income',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'117000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(593,	'118000000',	'Investasi',	'Investments',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'110000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(594,	'118000001',	'Investasi Saham',	'Stock Investment',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'118000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(595,	'119000000',	'Harta Lancar',	'Other Current Assets',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'110000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(596,	'119000001',	'Harta Lancar',	'Other Current Assets',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'119000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(597,	'120000000',	'Harta Tetap',	'Fixed Assets',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'100000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(598,	'121000000',	'Harta Tetap',	'Fixed Assets',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'120000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(599,	'121000001',	'Tanah',	'Land',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(600,	'121000002',	'Bangunan',	'Building',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(601,	'121000003',	'Mesin & Peralatan',	'Machinery & Equipment',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(602,	'121000004',	'Kendaraan',	'Vehicle',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(603,	'121000099',	'Harta Tetap Lainnya',	'Other Fixed Assets',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(604,	'121000100',	'Tanah',	'Tanah',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(605,	'121000101',	'Gedung',	'Gedung',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(606,	'121000102',	'Mesin & Peralatan',	'Mesin & Peralatan',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(607,	'121000103',	'Kendaraan',	'Kendaraan',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(608,	'121000104',	'Harta Lainnya',	'Harta Lainnya',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'121000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(609,	'122000000',	'Akumulasi Penyusutan Harta Tetap',	'Accumulated Depreciation Of Fixed Assets',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	'120000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(610,	'122000002',	'Akumulasi Penyusutan Bangunan',	'Accumulated Depreciation Of Building',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'122000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(611,	'122000003',	'Akumulasi Penyusutan Mesin & Peralatan',	'Accumulated Depreciation Of Machinery & Equipment',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'122000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(612,	'122000004',	'Akumulasi Penyusutan Kendaraan',	'Accumulated Depreciation Of Vehicle',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'122000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(613,	'122000099',	'Akumulasi Penyusutan Harta Tetap Lainnya',	'Accumulated Depreciation Of Other Fixed Assets',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'122000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(614,	'122000100',	'Akumulasi Penyusutan Gedung',	'Akumulasi Penyusutan Gedung',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'122000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(615,	'122000101',	'Akumulasi Penyusutan Mesin & Peralatan',	'Akumulasi Penyusutan Mesin & Peralatan',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'122000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(616,	'122000102',	'Akumulasi Penyusutan Kendaraan',	'Akumulasi Penyusutan Kendaraan',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'122000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(617,	'122000103',	'Akumulasi Penyusutan Harta Lainnya',	'Akumulasi Penyusutan Harta Lainnya',	'current_asset',	'asset',	'0',	'0',	'1',	'operating',	'122000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(618,	'2',	'Kewajiban',	'Liabilities',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(619,	'200000000',	'Kewajiban',	'Liabilities',	'current_liability',	'liability',	'1',	'0',	'1',	'operating',	'2',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(620,	'210000000',	'Kewajiban Lancar',	'Current Liabilities',	'current_liability',	'liability',	'1',	'0',	'1',	'operating',	'200000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(621,	'211000000',	'Utang Usaha',	'Utang Usaha',	'current_liability',	'liability',	'1',	'0',	'1',	'operating',	'2',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(622,	'211000001',	'Utang Usaha',	'Utang Usaha',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'211000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(623,	'212000000',	'Utang Lain',	'Utang Lain',	'current_liability',	'liability',	'1',	'0',	'1',	'operating',	'2',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(624,	'212000001',	'Utang Giro',	'Utang Giro',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'212000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(625,	'213000000',	'Pajak Terutang',	'Taxes Payable',	'current_liability',	'liability',	'1',	'0',	'1',	'operating',	'210000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(626,	'213000001',	'PPN Keluaran',	'VAT Out',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(627,	'213000002',	'PPh Pasal 23-2 Terutang',	'Income Tax Article 23-2 Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(628,	'213000003',	'PPh Pasal 23-4 Terutang',	'Income Tax Article 23-4 Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(629,	'213000004',	'PPh Pasal 4 ayat 2 Terutang',	'Income Tax Article 4 paragraph 2 Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(630,	'213000005',	'PPh Pasal 25 Terutang',	'Income Tax Article 25 Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(631,	'213000006',	'Pajak Terutang',	'Pajak Terutang',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(632,	'213000007',	'Utang PPh 23-2',	'Utang PPh 23-2',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(633,	'213000008',	'Utang PPh 4.2',	'Utang PPh 4.2',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(634,	'213000009',	'Utang PPh 23-4',	'Utang PPh 23-4',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(635,	'213000010',	'PPN Keluaran',	'PPN Keluaran',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(636,	'213000011',	'Utang ppnbm20',	'Utang ppnbm20',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'213000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(637,	'219000000',	'Kewajiban Lancar lainnya',	'Other Current Liabilities',	'current_liability',	'liability',	'1',	'0',	'1',	'operating',	'210000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(638,	'219000010',	'Utang Belum Terbit Faktur',	'Account Payable Have Not Been Invoiced',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'219000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(639,	'219000020',	'Utang Konsinyasi',	'Consignment Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'219000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(640,	'219000030',	'Utang Gaji & Upah',	'Salary Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'219000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(641,	'219000040',	'Utang Komisi Penjualan',	'Sales Commission Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'219000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(642,	'219000050',	'Biaya Yang Akan Dibayar',	'Accrued Expense',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'219000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(643,	'219000060',	'Utang Pajak',	'Tax Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'219000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(644,	'219000070',	'Pendapatan Diterima Dimuka',	'Unearned Revenues',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'219000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(645,	'219000080',	'Utang Pihak Ketiga',	'Third Party Payable',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'219000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(646,	'220000000',	'Kewajiban Jangka Panjang',	'Long-term Liabilities',	'current_liability',	'liability',	'1',	'0',	'1',	'operating',	'200000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(647,	'220000001',	'Utang Bank',	'Bank Loans',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'220000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(648,	'220000002',	'Utang Pembiayaan',	'Finance Lease Liabilities',	'current_liability',	'liability',	'0',	'0',	'1',	'operating',	'220000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(649,	'3',	'Modal',	'Equity',	'current_asset',	'asset',	'1',	'0',	'1',	'undefined',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(650,	'300000000',	'Modal',	'Equity',	'equity',	'equity',	'1',	'0',	'1',	'undefined',	'3',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(652,	'310000010',	'Modal Disetor',	'Paid-In Capital',	'equity',	'equity',	'0',	'0',	'1',	'undefined',	'310000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(653,	'310000020',	'Saham Biasa',	'Common Stock',	'equity',	'equity',	'0',	'0',	'1',	'undefined',	'310000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(654,	'310000030',	'Prive',	'Prive',	'equity',	'equity',	'0',	'0',	'1',	'undefined',	'310000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(655,	'320000000',	'Laba',	'Earning',	'equity',	'equity',	'1',	'0',	'1',	'undefined',	'300000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(656,	'320000010',	'Laba Ditahan',	'Laba Ditahan',	'equity',	'equity',	'0',	'0',	'1',	'undefined',	'320000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(657,	'320000020',	'Laba Berjalan',	'Laba Berjalan',	'equity',	'equity',	'0',	'0',	'1',	'undefined',	'320000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(658,	'320000030',	'Ekuitas Saldo Awal',	'Historical Balancing',	'equity',	'equity',	'0',	'0',	'1',	'undefined',	'320000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(659,	'4',	'Pendapatan Usaha',	'Revenues',	'current_asset',	'asset',	'1',	'0',	'1',	'undefined',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(660,	'400000000',	'Pendapatan Usaha',	'Revenues',	'revenue',	'revenue',	'1',	'0',	'1',	'undefined',	'4',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(661,	'410000000',	'Penjualan',	'Sales',	'revenue',	'revenue',	'1',	'0',	'1',	'undefined',	'400000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(662,	'410000001',	'Penjualan',	'Penjualan',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'410000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(663,	'410000099',	'Penjualan Jasa',	'Service Sales',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'410000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(664,	'420000000',	'Diskon Penjualan',	'Diskon Penjualan',	'revenue',	'revenue',	'1',	'0',	'1',	'undefined',	'4',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(665,	'420000001',	'Diskon Penjualan',	'Diskon Penjualan',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'420000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(666,	'430000000',	'Retur Penjualan',	'Retur Penjualan',	'revenue',	'revenue',	'1',	'0',	'1',	'undefined',	'4',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(667,	'430000001',	'Retur Penjualan',	'Retur Penjualan',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'430000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(668,	'490000000',	'Pendapatan Usaha Lain',	'Pendapatan Usaha Lain',	'revenue',	'revenue',	'1',	'0',	'1',	'undefined',	'4',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(669,	'490000099',	'Pendapatan Usaha Lain',	'Pendapatan Usaha Lain',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'490000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(670,	'5',	'Beban Atas Pendapatan',	'Cost of Revenues',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(671,	'500000000',	'Beban Atas Pendapatan',	'Cost of Revenues',	'expense',	'expense',	'1',	'0',	'1',	'operating',	'5',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(672,	'520000000',	'Beban Pembelian',	'Purchase Expenses',	'expense',	'expense',	'1',	'0',	'1',	'operating',	'500000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(673,	'520000001',	'Beban Pengiriman',	'Freight Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'520000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(674,	'530000000',	'Diskon Pembelian',	'Diskon Pembelian',	'expense',	'expense',	'1',	'0',	'1',	'operating',	'5',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(675,	'530000001',	'Diskon Pembelian',	'Diskon Pembelian',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'530000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(676,	'540000000',	'Retur Pembelian',	'Retur Pembelian',	'expense',	'expense',	'1',	'0',	'1',	'operating',	'5',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(677,	'540000001',	'Retur Pembelian',	'Retur Pembelian',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'540000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(678,	'590000000',	'Beban Atas Pendapatan Lain',	'Other Cost Of Revenues',	'expense',	'expense',	'1',	'0',	'1',	'operating',	'500000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(679,	'590000099',	'Beban Atas Pendapatan Lain',	'Other Cost Of Revenue',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'590000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(680,	'6',	'Beban Operasional',	'Operating Expenses',	'current_asset',	'asset',	'1',	'0',	'1',	'operating',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(681,	'600000000',	'Beban Operasional',	'Operating Expenses',	'expense',	'expense',	'1',	'0',	'1',	'operating',	'6',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(682,	'610000000',	'Beban Pemasaran',	'Marketing Expenses',	'expense',	'expense',	'1',	'0',	'1',	'operating',	'600000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(683,	'610000001',	'Beban Komisi Penjualan',	'Sales Commission Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'610000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(684,	'610000002',	'Beban Piutang Tak Tertagih',	'Bad Debts Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'610000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(685,	'620000000',	'Beban Administrasi Dan Umum',	'Administration & General Expenses',	'expense',	'expense',	'1',	'0',	'1',	'operating',	'600000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(686,	'620000001',	'Beban Gaji & Upah',	'Wages and Salaries Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(687,	'620000002',	'Beban Staff Ahli & Perizinan',	'Professional and Legal Fees',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(688,	'620000003',	'Beban Sistem & Teknologi',	'System & Technology Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(689,	'620000004',	'Beban Sewa',	'Rent Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(690,	'620000005',	'Beban Listrik',	'Electricity Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(691,	'620000006',	'Beban Air',	'Water Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(692,	'620000007',	'Beban Telepon',	'Communication Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(693,	'620000008',	'Beban Internet',	'Internet Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(694,	'620000009',	'Beban Perlengkapan',	'Supplies Expenses',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(695,	'620000099',	'Beban Operasional Lainnya',	'Beban Operasional Lainnya',	'expense',	'expense',	'0',	'0',	'1',	'operating',	'620000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(696,	'7',	'Beban Non Operasional',	'Non Operating Expenses',	'current_asset',	'asset',	'1',	'0',	'1',	'undefined',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(697,	'700000000',	'Beban Non Operasional',	'Non Operating Expenses',	'expense',	'expense',	'1',	'0',	'1',	'undefined',	'7',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(698,	'710000000',	'Beban Non Operasional',	'Non Operating Expense',	'expense',	'expense',	'1',	'0',	'1',	'undefined',	'700000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(699,	'710000001',	'Beban Non Operasional',	'Non Operating Expense',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'710000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(700,	'710000002',	'Beban Penyusutan Gedung',	'Beban Penyusutan Gedung',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'710000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(701,	'710000003',	'Beban Penyusutan Mesin & Peralatan',	'Beban Penyusutan Mesin & Peralatan',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'710000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(702,	'710000004',	'Beban Penyusutan Kendaraan',	'Beban Penyusutan Kendaraan',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'710000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(703,	'710000005',	'Beban Penyusutan Harta Lainnya',	'Beban Penyusutan Harta Lainnya',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'710000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(704,	'8',	'Pendapatan Lain',	'Other Revenues',	'current_asset',	'asset',	'1',	'0',	'1',	'undefined',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(705,	'800000000',	'Pendapatan Lain',	'Other Revenues',	'revenue',	'revenue',	'1',	'0',	'1',	'undefined',	'8',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(706,	'810000000',	'Pendapatan Lain',	'Other Revenues',	'revenue',	'revenue',	'1',	'0',	'1',	'undefined',	'800000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(707,	'810000001',	'Pendapatan Lain',	'Other Revenues',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'810000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(708,	'810000002',	'Pendapatan Bunga / Bagi Hasil',	'Interest Income',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'810000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(709,	'820000000',	'Laba Selisih Kurs',	'Laba Selisih Kurs',	'revenue',	'revenue',	'1',	'0',	'1',	'undefined',	'8',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(710,	'820000001',	'Laba Selisih Kurs - Unrealize',	'Laba Selisih Kurs - Unrealize',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'820000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(711,	'820000002',	'Laba Selisih Kurs - Realize',	'Laba Selisih Kurs - Realize',	'revenue',	'revenue',	'0',	'0',	'1',	'undefined',	'820000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(712,	'9',	'Beban Lain',	'Other Expenses',	'current_asset',	'asset',	'1',	'0',	'1',	'undefined',	NULL,	1,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(713,	'900000000',	'Beban Lain',	'Other Expenses',	'expense',	'expense',	'1',	'0',	'1',	'undefined',	'9',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(714,	'910000000',	'Beban Luar Usaha',	'Other Expenses',	'expense',	'expense',	'1',	'0',	'1',	'undefined',	'900000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(715,	'910000001',	'Beban Administrasi Bank',	'Bank Administration Expense',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'910000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(716,	'910000002',	'Beban Bunga / Bagi Hasil',	'Interest Expense',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'910000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(717,	'910000099',	'Beban Lain',	'Other Expenses',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'910000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(718,	'920000000',	'Rugi Selisih Kurs',	'Rugi Selisih Kurs',	'expense',	'expense',	'1',	'0',	'1',	'undefined',	'9',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(719,	'920000001',	'Rugi Selisih Kurs - Unrealize',	'Rugi Selisih Kurs - Unrealize',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'920000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(720,	'920000002',	'Rugi Selisih Kurs - Realize',	'Rugi Selisih Kurs - Realize',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'920000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(721,	'990000000',	'Beban Pajak',	'Tax Expenses',	'expense',	'expense',	'1',	'0',	'1',	'undefined',	'900000000',	3,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(722,	'990000001',	'Beban Pajak - Kini',	'Tax Expense - Current',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'990000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43'),
(723,	'990000002',	'Beban Pajak - Tangguhan',	'Tax Expense - Deferred',	'expense',	'expense',	'0',	'0',	'1',	'undefined',	'990000000',	5,	'Standard Indonesian COA',	'Standard Indonesian Chart of Accounts template',	'2025-11-18 09:47:43',	'2025-11-18 09:47:43');

DROP TABLE IF EXISTS "accounts";
DROP SEQUENCE IF EXISTS accounts_id_seq;
CREATE SEQUENCE accounts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."accounts" (
    "id" bigint DEFAULT nextval('accounts_id_seq') NOT NULL,
    "code" character varying(50) NOT NULL,
    "name" character varying(200) NOT NULL,
    "account_type" character varying(255) NOT NULL,
    "is_cash_bank" boolean DEFAULT false NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "level" integer DEFAULT '1' NOT NULL,
    "opening_balance" numeric(15,2) DEFAULT '0' NOT NULL,
    "current_balance" numeric(15,2) DEFAULT '0' NOT NULL,
    "parent_id" bigint,
    "company_id" bigint,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    "classification_type" character varying(255),
    "description" text,
    "is_header" boolean DEFAULT false NOT NULL,
    "created_by_user_id" bigint,
    "cash_flow" character varying(50),
    "classification_id" bigint,
    CONSTRAINT "accounts_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "accounts_account_type_check" CHECK (((account_type)::text = ANY (ARRAY[('current_asset'::character varying)::text, ('fixed_asset'::character varying)::text, ('current_liability'::character varying)::text, ('long_term_liability'::character varying)::text, ('equity'::character varying)::text, ('revenue'::character varying)::text, ('expense'::character varying)::text, ('cost_of_goods_sold'::character varying)::text]))),
    CONSTRAINT "accounts_classification_type_check" CHECK (((classification_type)::text = ANY (ARRAY[('asset'::character varying)::text, ('liability'::character varying)::text, ('equity'::character varying)::text, ('revenue'::character varying)::text, ('expense'::character varying)::text])))
)
WITH (oids = false);

INSERT INTO "accounts" ("id", "code", "name", "account_type", "is_cash_bank", "is_active", "level", "opening_balance", "current_balance", "parent_id", "company_id", "created_at", "updated_at", "deleted_at", "classification_type", "description", "is_header", "created_by_user_id", "cash_flow", "classification_id") VALUES
(1073,	'1',	'Harta',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:25',	NULL,	'asset',	'Asset',	'1',	1,	'operating',	NULL),
(1149,	'2',	'Kewajiban',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:25',	NULL,	'asset',	'Liabilities',	'1',	1,	'operating',	NULL),
(1117,	'116200007',	'PPh 23-2 Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'PPh 23-2 Dibayar Dimuka',	'0',	1,	'operating',	NULL),
(1180,	'3',	'Modal',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'asset',	'Equity',	'1',	1,	'undefined',	NULL),
(1190,	'4',	'Pendapatan Usaha',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'asset',	'Revenues',	'1',	1,	'undefined',	NULL),
(1201,	'5',	'Beban Atas Pendapatan',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'asset',	'Cost of Revenues',	'1',	1,	'operating',	NULL),
(1160,	'213000004',	'PPh Pasal 4 ayat 2 Terutang',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Income Tax Article 4 paragraph 2 Payable',	'0',	1,	'operating',	NULL),
(1161,	'213000005',	'PPh Pasal 25 Terutang',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Income Tax Article 25 Payable',	'0',	1,	'operating',	NULL),
(1211,	'6',	'Beban Operasional',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'asset',	'Operating Expenses',	'1',	1,	'operating',	NULL),
(1227,	'7',	'Beban Non Operasional',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'asset',	'Non Operating Expenses',	'1',	1,	'undefined',	NULL),
(1235,	'8',	'Pendapatan Lain',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'asset',	'Other Revenues',	'1',	1,	'undefined',	NULL),
(1243,	'9',	'Beban Lain',	'current_asset',	'0',	'1',	1,	0.00,	0.00,	NULL,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'asset',	'Other Expenses',	'1',	1,	'undefined',	NULL),
(1203,	'520000000',	'Beban Pembelian',	'expense',	'0',	'1',	3,	0.00,	0.00,	1202,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Purchase Expenses',	'1',	1,	'operating',	NULL),
(1204,	'520000001',	'Beban Pengiriman',	'expense',	'0',	'1',	5,	0.00,	0.00,	1203,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Freight Expenses',	'0',	1,	'operating',	NULL),
(1205,	'530000000',	'Diskon Pembelian',	'expense',	'0',	'1',	3,	0.00,	0.00,	1201,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Diskon Pembelian',	'1',	1,	'operating',	NULL),
(1076,	'111000000',	'Kas Dan Setara Kas',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1075,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Cash & Equivalent',	'1',	1,	'operating',	NULL),
(1077,	'111100000',	'Kas',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1076,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Cash',	'1',	1,	'operating',	NULL),
(1078,	'111100001',	'Kas Kecil',	'current_asset',	'1',	'1',	5,	0.00,	0.00,	1077,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Petty Cash',	'0',	1,	'operating',	NULL),
(1079,	'111100002',	'Kas',	'current_asset',	'1',	'1',	5,	0.00,	0.00,	1077,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Kas',	'0',	1,	'operating',	NULL),
(1080,	'111200000',	'Dompet Digital',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1076,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'E-Wallet',	'1',	1,	'operating',	NULL),
(1081,	'111200001',	'Gopay',	'current_asset',	'1',	'1',	5,	0.00,	0.00,	1080,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Gopay',	'0',	1,	'operating',	NULL),
(1082,	'111200002',	'Ovo',	'current_asset',	'1',	'1',	5,	0.00,	0.00,	1080,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Ovo',	'0',	1,	'operating',	NULL),
(1083,	'111200003',	'ShopeePay',	'current_asset',	'1',	'1',	5,	0.00,	0.00,	1080,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'ShopeePay',	'0',	1,	'operating',	NULL),
(1084,	'111200099',	'Dompet Digital Lain',	'current_asset',	'1',	'1',	5,	0.00,	0.00,	1080,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Other E-Wallet',	'0',	1,	'operating',	NULL),
(1087,	'112000000',	'Surat Berharga',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1075,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Securities',	'1',	1,	'operating',	NULL),
(1088,	'112000001',	'Deposito',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1087,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Deposito',	'0',	1,	'operating',	NULL),
(1089,	'113000000',	'Piutang',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1075,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Account Receivable',	'1',	1,	'operating',	NULL),
(1091,	'113100001',	'Piutang Usaha',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1090,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Piutang Usaha',	'0',	1,	'operating',	NULL),
(1092,	'113200000',	'Piutang Lain',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1089,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Other Receivable',	'1',	1,	'operating',	NULL),
(1093,	'113200001',	'Piutang Belum Terbit Faktur',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1092,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Account Receivable Have Not Been Invoiced',	'0',	1,	'operating',	NULL),
(1094,	'113200002',	'Piutang Giro',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1092,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Post-dated Receivable',	'0',	1,	'operating',	NULL),
(1095,	'113200003',	'Piutang Karyawan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1092,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Employee Receivable',	'0',	1,	'operating',	NULL),
(1096,	'113200004',	'Piutang Pihak Ketiga',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1092,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Third Party Receivable',	'0',	1,	'operating',	NULL),
(1097,	'113200099',	'Piutang Lain',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1092,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Piutang Lain',	'0',	1,	'operating',	NULL),
(1098,	'113300000',	'Cadangan Kerugian Piutang',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1089,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Allowance For Doubtful Receivable',	'1',	1,	'operating',	NULL),
(1099,	'113300001',	'Cadangan Kerugian Piutang Usaha',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1098,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Cadangan Kerugian Piutang Usaha',	'0',	1,	'operating',	NULL),
(1100,	'113300099',	'Cadangan Kerugian Piutang Lainnya',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1098,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Allowance For Doubtful Other Receivable',	'0',	1,	'operating',	NULL),
(1101,	'114000000',	'Persediaan',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1073,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Persediaan',	'1',	1,	'operating',	NULL),
(1103,	'115000000',	'Uang Muka Pengeluaran',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1075,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Advance Expenditure',	'1',	1,	'operating',	NULL),
(1075,	'110000000',	'Harta Lancar',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1085,	1,	'2025-11-18 09:48:25',	'2025-12-12 04:57:16',	NULL,	'asset',	'Current Asset',	'1',	1,	'operating',	1073),
(1086,	'111300001',	'Bank',	'current_asset',	'1',	'1',	5,	0.00,	0.00,	1085,	1,	'2025-11-18 09:48:25',	'2025-12-12 05:11:59',	'2025-12-12 05:11:59',	'asset',	'Bank',	'0',	1,	'operating',	NULL),
(1085,	'11100000',	'Bank',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1085,	1,	'2025-11-18 09:48:25',	'2025-12-12 05:15:57',	NULL,	'asset',	'Bank',	'1',	1,	'operating',	1073),
(1102,	'114000001',	'Persediaan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1101,	1,	'2025-11-18 09:48:25',	'2025-12-12 05:39:34',	'2025-12-12 05:39:34',	'asset',	'Persediaan',	'0',	1,	'operating',	NULL),
(1104,	'115000001',	'Uang Muka Pengeluaran Umum',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1103,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'General Advance Expenditure',	'0',	1,	'operating',	NULL),
(1105,	'116000000',	'Biaya Dibayar Dimuka',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1075,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Prepaid Expenses',	'1',	1,	'operating',	NULL),
(1106,	'116100000',	'Biaya Dibayar Dimuka Umum',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1105,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'General Prepaid Expenses',	'1',	1,	'operating',	NULL),
(1107,	'116100001',	'Sewa Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1106,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Prepaid Rent',	'0',	1,	'operating',	NULL),
(1108,	'116100002',	'Asuransi Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1106,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Prepaid Insurance',	'0',	1,	'operating',	NULL),
(1109,	'116100099',	'Biaya Dibayar Dimuka Lain',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1106,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Other Prepaid Expense',	'0',	1,	'operating',	NULL),
(1110,	'116200000',	'Pajak Dibayar Dimuka',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1105,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Prepaid Taxes',	'1',	1,	'operating',	NULL),
(1111,	'116200001',	'PPN Masukan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'VAT In',	'0',	1,	'operating',	NULL),
(1112,	'116200002',	'PPh Pasal 23-2 Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Prepaid Income Tax Article 23-2',	'0',	1,	'operating',	NULL),
(1113,	'116200003',	'PPh Pasal 23-4 Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Prepaid Income Tax Article 23-4',	'0',	1,	'operating',	NULL),
(1114,	'116200004',	'PPh Pasal 4 ayat 2 Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Prepaid Income Tax Article 4 paragraph 2',	'0',	1,	'operating',	NULL),
(1115,	'116200005',	'PPh Pasal 25 Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Prepaid Income Tax Article 25',	'0',	1,	'operating',	NULL),
(1116,	'116200006',	'Pajak Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Pajak Dibayar Dimuka',	'0',	1,	'operating',	NULL),
(1118,	'116200008',	'PPh 4.2 Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'PPh 4.2 Dibayar Dimuka',	'0',	1,	'operating',	NULL),
(1119,	'116200009',	'PPh 23-4 Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'PPh 23-4 Dibayar Dimuka',	'0',	1,	'operating',	NULL),
(1120,	'116200010',	'PPN Masukan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'PPN Masukan',	'0',	1,	'operating',	NULL),
(1121,	'116200011',	'ppnbm20 Dibayar Dimuka',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1110,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'ppnbm20 Dibayar Dimuka',	'0',	1,	'operating',	NULL),
(1122,	'117000000',	'Pendapatan Yang Akan Diterima',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1075,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Accrued Income',	'1',	1,	'operating',	NULL),
(1123,	'117000001',	'Pendapatan Yang Akan Diterima',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1122,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Accrued Income',	'0',	1,	'operating',	NULL),
(1124,	'118000000',	'Investasi',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1075,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Investments',	'1',	1,	'operating',	NULL),
(1125,	'118000001',	'Investasi Saham',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1124,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Stock Investment',	'0',	1,	'operating',	NULL),
(1126,	'119000000',	'Harta Lancar',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1075,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Other Current Assets',	'1',	1,	'operating',	NULL),
(1127,	'119000001',	'Harta Lancar',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1126,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Other Current Assets',	'0',	1,	'operating',	NULL),
(1128,	'120000000',	'Harta Tetap',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1074,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Fixed Assets',	'1',	1,	'operating',	NULL),
(1129,	'121000000',	'Harta Tetap',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1128,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Fixed Assets',	'1',	1,	'operating',	NULL),
(1130,	'121000001',	'Tanah',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Land',	'0',	1,	'operating',	NULL),
(1131,	'121000002',	'Bangunan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Building',	'0',	1,	'operating',	NULL),
(1132,	'121000003',	'Mesin & Peralatan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Machinery & Equipment',	'0',	1,	'operating',	NULL),
(1133,	'121000004',	'Kendaraan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Vehicle',	'0',	1,	'operating',	NULL),
(1134,	'121000099',	'Harta Tetap Lainnya',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Other Fixed Assets',	'0',	1,	'operating',	NULL),
(1135,	'121000100',	'Tanah',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Tanah',	'0',	1,	'operating',	NULL),
(1136,	'121000101',	'Gedung',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Gedung',	'0',	1,	'operating',	NULL),
(1137,	'121000102',	'Mesin & Peralatan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Mesin & Peralatan',	'0',	1,	'operating',	NULL),
(1138,	'121000103',	'Kendaraan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Kendaraan',	'0',	1,	'operating',	NULL),
(1139,	'121000104',	'Harta Lainnya',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1129,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Harta Lainnya',	'0',	1,	'operating',	NULL),
(1140,	'122000000',	'Akumulasi Penyusutan Harta Tetap',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1128,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Accumulated Depreciation Of Fixed Assets',	'1',	1,	'operating',	NULL),
(1141,	'122000002',	'Akumulasi Penyusutan Bangunan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1140,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Accumulated Depreciation Of Building',	'0',	1,	'operating',	NULL),
(1142,	'122000003',	'Akumulasi Penyusutan Mesin & Peralatan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1140,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Accumulated Depreciation Of Machinery & Equipment',	'0',	1,	'operating',	NULL),
(1143,	'122000004',	'Akumulasi Penyusutan Kendaraan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1140,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Accumulated Depreciation Of Vehicle',	'0',	1,	'operating',	NULL),
(1144,	'122000099',	'Akumulasi Penyusutan Harta Tetap Lainnya',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1140,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Accumulated Depreciation Of Other Fixed Assets',	'0',	1,	'operating',	NULL),
(1145,	'122000100',	'Akumulasi Penyusutan Gedung',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1140,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Akumulasi Penyusutan Gedung',	'0',	1,	'operating',	NULL),
(1146,	'122000101',	'Akumulasi Penyusutan Mesin & Peralatan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1140,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Akumulasi Penyusutan Mesin & Peralatan',	'0',	1,	'operating',	NULL),
(1147,	'122000102',	'Akumulasi Penyusutan Kendaraan',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1140,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Akumulasi Penyusutan Kendaraan',	'0',	1,	'operating',	NULL),
(1196,	'420000001',	'Diskon Penjualan',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1195,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Diskon Penjualan',	'0',	1,	'undefined',	NULL),
(1148,	'122000103',	'Akumulasi Penyusutan Harta Lainnya',	'current_asset',	'0',	'1',	5,	0.00,	0.00,	1140,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'asset',	'Akumulasi Penyusutan Harta Lainnya',	'0',	1,	'operating',	NULL),
(1150,	'200000000',	'Kewajiban',	'current_liability',	'0',	'1',	3,	0.00,	0.00,	1149,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Liabilities',	'1',	1,	'operating',	NULL),
(1151,	'210000000',	'Kewajiban Lancar',	'current_liability',	'0',	'1',	3,	0.00,	0.00,	1150,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Current Liabilities',	'1',	1,	'operating',	NULL),
(1152,	'211000000',	'Utang Usaha',	'current_liability',	'0',	'1',	3,	0.00,	0.00,	1149,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Utang Usaha',	'1',	1,	'operating',	NULL),
(1153,	'211000001',	'Utang Usaha',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1152,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Utang Usaha',	'0',	1,	'operating',	NULL),
(1154,	'212000000',	'Utang Lain',	'current_liability',	'0',	'1',	3,	0.00,	0.00,	1149,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Utang Lain',	'1',	1,	'operating',	NULL),
(1155,	'212000001',	'Utang Giro',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1154,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Utang Giro',	'0',	1,	'operating',	NULL),
(1156,	'213000000',	'Pajak Terutang',	'current_liability',	'0',	'1',	3,	0.00,	0.00,	1151,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Taxes Payable',	'1',	1,	'operating',	NULL),
(1157,	'213000001',	'PPN Keluaran',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'VAT Out',	'0',	1,	'operating',	NULL),
(1158,	'213000002',	'PPh Pasal 23-2 Terutang',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Income Tax Article 23-2 Payable',	'0',	1,	'operating',	NULL),
(1159,	'213000003',	'PPh Pasal 23-4 Terutang',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Income Tax Article 23-4 Payable',	'0',	1,	'operating',	NULL),
(1162,	'213000006',	'Pajak Terutang',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Pajak Terutang',	'0',	1,	'operating',	NULL),
(1163,	'213000007',	'Utang PPh 23-2',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Utang PPh 23-2',	'0',	1,	'operating',	NULL),
(1164,	'213000008',	'Utang PPh 4.2',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Utang PPh 4.2',	'0',	1,	'operating',	NULL),
(1165,	'213000009',	'Utang PPh 23-4',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Utang PPh 23-4',	'0',	1,	'operating',	NULL),
(1166,	'213000010',	'PPN Keluaran',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'PPN Keluaran',	'0',	1,	'operating',	NULL),
(1167,	'213000011',	'Utang ppnbm20',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1156,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Utang ppnbm20',	'0',	1,	'operating',	NULL),
(1168,	'219000000',	'Kewajiban Lancar lainnya',	'current_liability',	'0',	'1',	3,	0.00,	0.00,	1151,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Other Current Liabilities',	'1',	1,	'operating',	NULL),
(1169,	'219000010',	'Utang Belum Terbit Faktur',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1168,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Account Payable Have Not Been Invoiced',	'0',	1,	'operating',	NULL),
(1170,	'219000020',	'Utang Konsinyasi',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1168,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Consignment Payable',	'0',	1,	'operating',	NULL),
(1171,	'219000030',	'Utang Gaji & Upah',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1168,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Salary Payable',	'0',	1,	'operating',	NULL),
(1172,	'219000040',	'Utang Komisi Penjualan',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1168,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Sales Commission Payable',	'0',	1,	'operating',	NULL),
(1173,	'219000050',	'Biaya Yang Akan Dibayar',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1168,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Accrued Expense',	'0',	1,	'operating',	NULL),
(1174,	'219000060',	'Utang Pajak',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1168,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Tax Payable',	'0',	1,	'operating',	NULL),
(1175,	'219000070',	'Pendapatan Diterima Dimuka',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1168,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Unearned Revenues',	'0',	1,	'operating',	NULL),
(1176,	'219000080',	'Utang Pihak Ketiga',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1168,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Third Party Payable',	'0',	1,	'operating',	NULL),
(1177,	'220000000',	'Kewajiban Jangka Panjang',	'current_liability',	'0',	'1',	3,	0.00,	0.00,	1150,	1,	'2025-11-18 09:48:25',	'2025-11-18 09:48:26',	NULL,	'liability',	'Long-term Liabilities',	'1',	1,	'operating',	NULL),
(1178,	'220000001',	'Utang Bank',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1177,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'liability',	'Bank Loans',	'0',	1,	'operating',	NULL),
(1179,	'220000002',	'Utang Pembiayaan',	'current_liability',	'0',	'1',	5,	0.00,	0.00,	1177,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'liability',	'Finance Lease Liabilities',	'0',	1,	'operating',	NULL),
(1181,	'300000000',	'Modal',	'equity',	'0',	'1',	3,	0.00,	0.00,	1180,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Equity',	'1',	1,	'undefined',	NULL),
(1182,	'310000000',	'Modal',	'equity',	'0',	'1',	3,	0.00,	0.00,	1181,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Equity',	'1',	1,	'undefined',	NULL),
(1183,	'310000010',	'Modal Disetor',	'equity',	'0',	'1',	5,	0.00,	0.00,	1182,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Paid-In Capital',	'0',	1,	'undefined',	NULL),
(1184,	'310000020',	'Saham Biasa',	'equity',	'0',	'1',	5,	0.00,	0.00,	1182,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Common Stock',	'0',	1,	'undefined',	NULL),
(1185,	'310000030',	'Prive',	'equity',	'0',	'1',	5,	0.00,	0.00,	1182,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Prive',	'0',	1,	'undefined',	NULL),
(1186,	'320000000',	'Laba',	'equity',	'0',	'1',	3,	0.00,	0.00,	1181,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Earning',	'1',	1,	'undefined',	NULL),
(1187,	'320000010',	'Laba Ditahan',	'equity',	'0',	'1',	5,	0.00,	0.00,	1186,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Laba Ditahan',	'0',	1,	'undefined',	NULL),
(1188,	'320000020',	'Laba Berjalan',	'equity',	'0',	'1',	5,	0.00,	0.00,	1186,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Laba Berjalan',	'0',	1,	'undefined',	NULL),
(1189,	'320000030',	'Ekuitas Saldo Awal',	'equity',	'0',	'1',	5,	0.00,	0.00,	1186,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'equity',	'Historical Balancing',	'0',	1,	'undefined',	NULL),
(1191,	'400000000',	'Pendapatan Usaha',	'revenue',	'0',	'1',	3,	0.00,	0.00,	1190,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Revenues',	'1',	1,	'undefined',	NULL),
(1192,	'410000000',	'Penjualan',	'revenue',	'0',	'1',	3,	0.00,	0.00,	1191,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Sales',	'1',	1,	'undefined',	NULL),
(1193,	'410000001',	'Penjualan',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1192,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Penjualan',	'0',	1,	'undefined',	NULL),
(1194,	'410000099',	'Penjualan Jasa',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1192,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Service Sales',	'0',	1,	'undefined',	NULL),
(1195,	'420000000',	'Diskon Penjualan',	'revenue',	'0',	'1',	3,	0.00,	0.00,	1190,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Diskon Penjualan',	'1',	1,	'undefined',	NULL),
(1197,	'430000000',	'Retur Penjualan',	'revenue',	'0',	'1',	3,	0.00,	0.00,	1190,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Retur Penjualan',	'1',	1,	'undefined',	NULL),
(1198,	'430000001',	'Retur Penjualan',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1197,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Retur Penjualan',	'0',	1,	'undefined',	NULL),
(1199,	'490000000',	'Pendapatan Usaha Lain',	'revenue',	'0',	'1',	3,	0.00,	0.00,	1190,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Pendapatan Usaha Lain',	'1',	1,	'undefined',	NULL),
(1200,	'490000099',	'Pendapatan Usaha Lain',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1199,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Pendapatan Usaha Lain',	'0',	1,	'undefined',	NULL),
(1202,	'500000000',	'Beban Atas Pendapatan',	'expense',	'0',	'1',	3,	0.00,	0.00,	1201,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Cost of Revenues',	'1',	1,	'operating',	NULL),
(1206,	'530000001',	'Diskon Pembelian',	'expense',	'0',	'1',	5,	0.00,	0.00,	1205,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Diskon Pembelian',	'0',	1,	'operating',	NULL),
(1207,	'540000000',	'Retur Pembelian',	'expense',	'0',	'1',	3,	0.00,	0.00,	1201,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Retur Pembelian',	'1',	1,	'operating',	NULL),
(1208,	'540000001',	'Retur Pembelian',	'expense',	'0',	'1',	5,	0.00,	0.00,	1207,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Retur Pembelian',	'0',	1,	'operating',	NULL),
(1209,	'590000000',	'Beban Atas Pendapatan Lain',	'expense',	'0',	'1',	3,	0.00,	0.00,	1202,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Other Cost Of Revenues',	'1',	1,	'operating',	NULL),
(1210,	'590000099',	'Beban Atas Pendapatan Lain',	'expense',	'0',	'1',	5,	0.00,	0.00,	1209,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Other Cost Of Revenue',	'0',	1,	'operating',	NULL),
(1212,	'600000000',	'Beban Operasional',	'expense',	'0',	'1',	3,	0.00,	0.00,	1211,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Operating Expenses',	'1',	1,	'operating',	NULL),
(1213,	'610000000',	'Beban Pemasaran',	'expense',	'0',	'1',	3,	0.00,	0.00,	1212,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Marketing Expenses',	'1',	1,	'operating',	NULL),
(1214,	'610000001',	'Beban Komisi Penjualan',	'expense',	'0',	'1',	5,	0.00,	0.00,	1213,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Sales Commission Expenses',	'0',	1,	'operating',	NULL),
(1215,	'610000002',	'Beban Piutang Tak Tertagih',	'expense',	'0',	'1',	5,	0.00,	0.00,	1213,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Bad Debts Expenses',	'0',	1,	'operating',	NULL),
(1216,	'620000000',	'Beban Administrasi Dan Umum',	'expense',	'0',	'1',	3,	0.00,	0.00,	1212,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Administration & General Expenses',	'1',	1,	'operating',	NULL),
(1217,	'620000001',	'Beban Gaji & Upah',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Wages and Salaries Expenses',	'0',	1,	'operating',	NULL),
(1218,	'620000002',	'Beban Staff Ahli & Perizinan',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Professional and Legal Fees',	'0',	1,	'operating',	NULL),
(1219,	'620000003',	'Beban Sistem & Teknologi',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'System & Technology Expenses',	'0',	1,	'operating',	NULL),
(1220,	'620000004',	'Beban Sewa',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Rent Expenses',	'0',	1,	'operating',	NULL),
(1221,	'620000005',	'Beban Listrik',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Electricity Expenses',	'0',	1,	'operating',	NULL),
(1222,	'620000006',	'Beban Air',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Water Expenses',	'0',	1,	'operating',	NULL),
(1223,	'620000007',	'Beban Telepon',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Communication Expenses',	'0',	1,	'operating',	NULL),
(1224,	'620000008',	'Beban Internet',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Internet Expenses',	'0',	1,	'operating',	NULL),
(1225,	'620000009',	'Beban Perlengkapan',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Supplies Expenses',	'0',	1,	'operating',	NULL),
(1226,	'620000099',	'Beban Operasional Lainnya',	'expense',	'0',	'1',	5,	0.00,	0.00,	1216,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Beban Operasional Lainnya',	'0',	1,	'operating',	NULL),
(1228,	'700000000',	'Beban Non Operasional',	'expense',	'0',	'1',	3,	0.00,	0.00,	1227,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Non Operating Expenses',	'1',	1,	'undefined',	NULL),
(1229,	'710000000',	'Beban Non Operasional',	'expense',	'0',	'1',	3,	0.00,	0.00,	1228,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Non Operating Expense',	'1',	1,	'undefined',	NULL),
(1230,	'710000001',	'Beban Non Operasional',	'expense',	'0',	'1',	5,	0.00,	0.00,	1229,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Non Operating Expense',	'0',	1,	'undefined',	NULL),
(1231,	'710000002',	'Beban Penyusutan Gedung',	'expense',	'0',	'1',	5,	0.00,	0.00,	1229,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Beban Penyusutan Gedung',	'0',	1,	'undefined',	NULL),
(1232,	'710000003',	'Beban Penyusutan Mesin & Peralatan',	'expense',	'0',	'1',	5,	0.00,	0.00,	1229,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Beban Penyusutan Mesin & Peralatan',	'0',	1,	'undefined',	NULL),
(1233,	'710000004',	'Beban Penyusutan Kendaraan',	'expense',	'0',	'1',	5,	0.00,	0.00,	1229,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Beban Penyusutan Kendaraan',	'0',	1,	'undefined',	NULL),
(1234,	'710000005',	'Beban Penyusutan Harta Lainnya',	'expense',	'0',	'1',	5,	0.00,	0.00,	1229,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Beban Penyusutan Harta Lainnya',	'0',	1,	'undefined',	NULL),
(1236,	'800000000',	'Pendapatan Lain',	'revenue',	'0',	'1',	3,	0.00,	0.00,	1235,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Other Revenues',	'1',	1,	'undefined',	NULL),
(1237,	'810000000',	'Pendapatan Lain',	'revenue',	'0',	'1',	3,	0.00,	0.00,	1236,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Other Revenues',	'1',	1,	'undefined',	NULL),
(1238,	'810000001',	'Pendapatan Lain',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1237,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Other Revenues',	'0',	1,	'undefined',	NULL),
(1239,	'810000002',	'Pendapatan Bunga / Bagi Hasil',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1237,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Interest Income',	'0',	1,	'undefined',	NULL),
(1240,	'820000000',	'Laba Selisih Kurs',	'revenue',	'0',	'1',	3,	0.00,	0.00,	1235,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Laba Selisih Kurs',	'1',	1,	'undefined',	NULL),
(1241,	'820000001',	'Laba Selisih Kurs - Unrealize',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1240,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Laba Selisih Kurs - Unrealize',	'0',	1,	'undefined',	NULL),
(1242,	'820000002',	'Laba Selisih Kurs - Realize',	'revenue',	'0',	'1',	5,	0.00,	0.00,	1240,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'revenue',	'Laba Selisih Kurs - Realize',	'0',	1,	'undefined',	NULL),
(1244,	'900000000',	'Beban Lain',	'expense',	'0',	'1',	3,	0.00,	0.00,	1243,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Other Expenses',	'1',	1,	'undefined',	NULL),
(1245,	'910000000',	'Beban Luar Usaha',	'expense',	'0',	'1',	3,	0.00,	0.00,	1244,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Other Expenses',	'1',	1,	'undefined',	NULL),
(1246,	'910000001',	'Beban Administrasi Bank',	'expense',	'0',	'1',	5,	0.00,	0.00,	1245,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Bank Administration Expense',	'0',	1,	'undefined',	NULL),
(1247,	'910000002',	'Beban Bunga / Bagi Hasil',	'expense',	'0',	'1',	5,	0.00,	0.00,	1245,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Interest Expense',	'0',	1,	'undefined',	NULL),
(1248,	'910000099',	'Beban Lain',	'expense',	'0',	'1',	5,	0.00,	0.00,	1245,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Other Expenses',	'0',	1,	'undefined',	NULL),
(1249,	'920000000',	'Rugi Selisih Kurs',	'expense',	'0',	'1',	3,	0.00,	0.00,	1243,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Rugi Selisih Kurs',	'1',	1,	'undefined',	NULL),
(1250,	'920000001',	'Rugi Selisih Kurs - Unrealize',	'expense',	'0',	'1',	5,	0.00,	0.00,	1249,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Rugi Selisih Kurs - Unrealize',	'0',	1,	'undefined',	NULL),
(1251,	'920000002',	'Rugi Selisih Kurs - Realize',	'expense',	'0',	'1',	5,	0.00,	0.00,	1249,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Rugi Selisih Kurs - Realize',	'0',	1,	'undefined',	NULL),
(1252,	'990000000',	'Beban Pajak',	'expense',	'0',	'1',	3,	0.00,	0.00,	1244,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Tax Expenses',	'1',	1,	'undefined',	NULL),
(1253,	'990000001',	'Beban Pajak - Kini',	'expense',	'0',	'1',	5,	0.00,	0.00,	1252,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Tax Expense - Current',	'0',	1,	'undefined',	NULL),
(1254,	'990000002',	'Beban Pajak - Tangguhan',	'expense',	'0',	'1',	5,	0.00,	0.00,	1252,	1,	'2025-11-18 09:48:26',	'2025-11-18 09:48:26',	NULL,	'expense',	'Tax Expense - Deferred',	'0',	1,	'undefined',	NULL),
(1074,	'100000000',	'KAS',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	1085,	1,	'2025-11-18 09:48:25',	'2025-12-12 04:56:42',	NULL,	'asset',	'Asset',	'1',	1,	'operating',	1073),
(1,	'110000000',	'KAS',	'current_asset',	'0',	'0',	4,	0.00,	0.00,	1085,	NULL,	'2025-12-12 04:59:36',	'2025-12-12 05:04:39',	'2025-12-12 05:04:39',	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(2,	'110000000',	'KAS',	'current_asset',	'0',	'0',	4,	0.00,	0.00,	1085,	NULL,	'2025-12-12 05:04:12',	'2025-12-12 05:04:43',	'2025-12-12 05:04:43',	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(3,	'110000000',	'ASSET LANCAR',	'current_asset',	'0',	'0',	4,	0.00,	0.00,	1085,	NULL,	'2025-12-12 05:06:41',	'2025-12-12 05:12:17',	'2025-12-12 05:12:17',	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(4,	'11100000',	'ASSET LANCAR',	'current_asset',	'1',	'1',	2,	0.00,	0.00,	1073,	NULL,	'2025-12-12 05:29:33',	'2025-12-12 05:29:33',	NULL,	NULL,	NULL,	'0',	NULL,	'operating',	1073),
(5,	'11100001',	'KAS',	'current_asset',	'1',	'1',	3,	0.00,	0.00,	4,	NULL,	'2025-12-12 05:30:43',	'2025-12-12 05:30:43',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(6,	'11100002',	'BANK BCA',	'current_asset',	'1',	'1',	4,	0.00,	0.00,	5,	NULL,	'2025-12-12 05:31:11',	'2025-12-12 05:31:32',	'2025-12-12 05:31:32',	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(7,	'11100002',	'BANK BCA',	'current_asset',	'1',	'1',	3,	0.00,	0.00,	4,	NULL,	'2025-12-12 05:31:45',	'2025-12-12 05:31:45',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(1090,	'11200000',	'Piutang Usaha',	'current_asset',	'1',	'1',	3,	0.00,	0.00,	1090,	1,	'2025-11-18 09:48:25',	'2025-12-12 05:34:33',	NULL,	'asset',	'Piutang Usaha',	'1',	1,	'operating',	1073),
(8,	'11200000',	'Piutang Usaha',	'current_asset',	'1',	'1',	2,	0.00,	0.00,	1073,	NULL,	'2025-12-12 05:35:54',	'2025-12-12 05:35:54',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(9,	'11200001',	'Piutang Usaha',	'current_asset',	'0',	'1',	3,	0.00,	0.00,	8,	NULL,	'2025-12-12 05:36:57',	'2025-12-12 05:36:57',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(10,	'11300000',	'PPH PS 21 DIBAYAR DIMUKA',	'current_asset',	'1',	'1',	3,	0.00,	0.00,	4,	NULL,	'2025-12-12 05:48:11',	'2025-12-12 05:48:37',	'2025-12-12 05:48:37',	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(11,	'11300000',	'UANG MUKA ',	'current_asset',	'1',	'1',	2,	0.00,	0.00,	1073,	NULL,	'2025-12-12 05:50:05',	'2025-12-12 05:50:05',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(12,	'11300001',	'PPH PS 21 DIBAYAR DIMUKA',	'current_asset',	'1',	'1',	3,	0.00,	0.00,	11,	NULL,	'2025-12-12 05:50:37',	'2025-12-12 05:50:37',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(13,	'11300002',	'PPH PS 25 DIBAYAR DIMUKA',	'current_asset',	'1',	'1',	3,	0.00,	0.00,	11,	NULL,	'2025-12-12 05:51:08',	'2025-12-12 05:51:08',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(14,	'11300003',	'PPH PS 23 DIBAYAR DIMUKA',	'current_asset',	'1',	'1',	3,	0.00,	0.00,	11,	NULL,	'2025-12-12 05:51:34',	'2025-12-12 05:51:34',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(16,	'12200001',	'TANAH',	'fixed_asset',	'0',	'1',	3,	0.00,	0.00,	15,	NULL,	'2025-12-12 06:19:39',	'2025-12-12 06:19:39',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(15,	'12200000',	'AKTIVA TETAP',	'fixed_asset',	'0',	'1',	2,	0.00,	0.00,	1073,	NULL,	'2025-12-12 06:18:06',	'2025-12-12 06:20:35',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(17,	'12200002',	'GEDUNG',	'fixed_asset',	'0',	'1',	3,	0.00,	0.00,	15,	NULL,	'2025-12-12 06:21:13',	'2025-12-12 06:21:13',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(18,	'12200003',	'KENDARAAN',	'fixed_asset',	'0',	'1',	3,	0.00,	0.00,	15,	NULL,	'2025-12-12 06:21:43',	'2025-12-12 06:21:43',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(19,	'12200004',	'PERALATAN KANTOR GOL I',	'fixed_asset',	'0',	'0',	3,	0.00,	0.00,	15,	NULL,	'2025-12-12 06:22:14',	'2025-12-12 06:22:14',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(20,	'12200005',	'PERALATAN KANTOR GOL II',	'fixed_asset',	'0',	'0',	3,	0.00,	0.00,	15,	NULL,	'2025-12-12 06:22:40',	'2025-12-12 06:22:40',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(21,	'12200006',	'AKUMULASI PENYUSUTAN TANAH',	'fixed_asset',	'0',	'1',	3,	0.00,	0.00,	15,	NULL,	'2025-12-12 06:25:32',	'2025-12-12 06:25:57',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(22,	'12200007',	'AKUMULASI PENYUSUTAN GEDUNG',	'fixed_asset',	'0',	'1',	3,	0.00,	0.00,	15,	NULL,	'2025-12-12 06:26:32',	'2025-12-12 06:26:32',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073),
(23,	'12200008',	'AKUMULASI PENYUSUTAN KENDARAAN',	'fixed_asset',	'0',	'1',	3,	0.00,	0.00,	15,	NULL,	'2025-12-12 06:28:29',	'2025-12-12 06:28:29',	NULL,	NULL,	NULL,	'0',	NULL,	NULL,	1073);

DROP TABLE IF EXISTS "advance_disbursement_items";
DROP SEQUENCE IF EXISTS advance_disbursement_items_id_seq;
CREATE SEQUENCE advance_disbursement_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."advance_disbursement_items" (
    "id" bigint DEFAULT nextval('advance_disbursement_items_id_seq') NOT NULL,
    "amount" character varying(255) NOT NULL,
    "description" text,
    "advance_disbursement_id" bigint NOT NULL,
    "transaction_classification_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "advance_disbursement_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "advance_disbursements";
DROP SEQUENCE IF EXISTS advance_disbursements_id_seq;
CREATE SEQUENCE advance_disbursements_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."advance_disbursements" (
    "id" bigint DEFAULT nextval('advance_disbursements_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "total" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "recipient_id" bigint NOT NULL,
    "from_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "advance_disbursements_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "advance_disbursements_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "advance_payment_allocations";
DROP SEQUENCE IF EXISTS advance_payment_allocations_id_seq;
CREATE SEQUENCE advance_payment_allocations_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."advance_payment_allocations" (
    "id" bigint DEFAULT nextval('advance_payment_allocations_id_seq') NOT NULL,
    "allocated_amount" character varying(255) NOT NULL,
    "allocated_date" date NOT NULL,
    "notes" text NOT NULL,
    "advance_payment_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "advance_payment_allocations_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "advance_payments";
DROP SEQUENCE IF EXISTS advance_payments_id_seq;
CREATE SEQUENCE advance_payments_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."advance_payments" (
    "id" bigint DEFAULT nextval('advance_payments_id_seq') NOT NULL,
    "advance_number" character varying(50) NOT NULL,
    "date" date NOT NULL,
    "amount" character varying(255) NOT NULL,
    "description" text,
    "status" character varying(255) NOT NULL,
    "used_amount" character varying(255) DEFAULT '0' NOT NULL,
    "remaining_amount" character varying(255) NOT NULL,
    "expiry_date" date,
    "customer_id" bigint NOT NULL,
    "company_id" bigint NOT NULL,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "advance_payments_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "advance_payments_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('active'::character varying)::text, ('partially_used'::character varying)::text, ('fully_used'::character varying)::text, ('expired'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);

CREATE UNIQUE INDEX advance_payments_advance_number_unique ON public.advance_payments USING btree (advance_number);


DROP TABLE IF EXISTS "advance_receipt_items";
DROP SEQUENCE IF EXISTS advance_receipt_items_id_seq;
CREATE SEQUENCE advance_receipt_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."advance_receipt_items" (
    "id" bigint DEFAULT nextval('advance_receipt_items_id_seq') NOT NULL,
    "amount" character varying(255) NOT NULL,
    "description" text,
    "advance_receipt_id" bigint NOT NULL,
    "transaction_classification_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "advance_receipt_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "advance_receipts";
DROP SEQUENCE IF EXISTS advance_receipts_id_seq;
CREATE SEQUENCE advance_receipts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."advance_receipts" (
    "id" bigint DEFAULT nextval('advance_receipts_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "total" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "recipient_id" bigint NOT NULL,
    "to_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "advance_receipts_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "advance_receipts_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "bank_accounts";
DROP SEQUENCE IF EXISTS bank_accounts_id_seq;
CREATE SEQUENCE bank_accounts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."bank_accounts" (
    "id" bigint DEFAULT nextval('bank_accounts_id_seq') NOT NULL,
    "account_number" character varying(50) NOT NULL,
    "account_name" character varying(200) NOT NULL,
    "account_type" character varying(255) DEFAULT 'checking' NOT NULL,
    "balance" numeric(15,2) DEFAULT '0' NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "bank_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "bank_accounts_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "bank_accounts_account_type_check" CHECK (((account_type)::text = ANY (ARRAY[('checking'::character varying)::text, ('savings'::character varying)::text, ('credit_card'::character varying)::text, ('investment'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "bank_reconciliations";
DROP SEQUENCE IF EXISTS bank_reconciliations_id_seq;
CREATE SEQUENCE bank_reconciliations_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."bank_reconciliations" (
    "id" bigint DEFAULT nextval('bank_reconciliations_id_seq') NOT NULL,
    "statement_date" date NOT NULL,
    "statement_balance" character varying(255) NOT NULL,
    "book_balance" character varying(255) NOT NULL,
    "reconciliation_date" date NOT NULL,
    "status" character varying(255) NOT NULL,
    "reconciled_at" timestamp(0),
    "difference" character varying(255) DEFAULT '0' NOT NULL,
    "bank_account_id" bigint NOT NULL,
    "reconciled_by_user_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "bank_reconciliations_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "bank_reconciliations_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('in_progress'::character varying)::text, ('completed'::character varying)::text, ('failed'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "banks";
DROP SEQUENCE IF EXISTS banks_id_seq;
CREATE SEQUENCE banks_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."banks" (
    "id" bigint DEFAULT nextval('banks_id_seq') NOT NULL,
    "code" character varying(20) NOT NULL,
    "name" character varying(200) NOT NULL,
    "logo" character varying(255),
    "country" character varying(100) NOT NULL,
    "clearing_code" character varying(20),
    "skn_code" character varying(20),
    "is_active" boolean DEFAULT true NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "banks_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "banks" ("id", "code", "name", "logo", "country", "clearing_code", "skn_code", "is_active", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(8,	'',	'',	NULL,	'',	'',	'',	'1',	NULL,	1,	'2025-12-02 09:35:14',	'2025-12-02 09:37:40',	'2025-12-02 09:37:40'),
(9,	'',	'',	NULL,	'',	'',	'',	'1',	NULL,	1,	'2025-12-02 09:38:00',	'2025-12-02 09:41:48',	'2025-12-02 09:41:48'),
(10,	'',	'',	NULL,	'',	'',	'',	'1',	NULL,	1,	'2025-12-02 09:41:59',	'2025-12-02 09:46:02',	'2025-12-02 09:46:02'),
(11,	'BCA',	'Bank Central Asia',	NULL,	'Indonesia',	'014',	'101',	'1',	NULL,	1,	'2025-12-02 09:56:03',	'2025-12-02 09:56:23',	'2025-12-02 09:56:23'),
(12,	'BNI',	'Bank Negara Indonesia',	NULL,	'Indonesia',	'009',	'102',	'1',	NULL,	1,	'2025-12-02 09:56:03',	'2025-12-02 09:56:23',	'2025-12-02 09:56:23'),
(13,	'BRI',	'Bank Rakyat Indonesia',	NULL,	'Indonesia',	'002',	'103',	'1',	NULL,	1,	'2025-12-02 09:56:03',	'2025-12-02 09:56:23',	'2025-12-02 09:56:23'),
(14,	'MANDIRI',	'Bank Mandiri',	NULL,	'Indonesia',	'008',	'104',	'1',	NULL,	1,	'2025-12-02 09:56:03',	'2025-12-02 09:56:23',	'2025-12-02 09:56:23'),
(15,	'BCA',	'Bank Central Asia',	NULL,	'Indonesia',	'014',	'101',	'1',	NULL,	1,	'2025-12-02 10:13:14',	'2025-12-02 10:13:22',	'2025-12-02 10:13:22'),
(16,	'BNI',	'Bank Negara Indonesia',	NULL,	'Indonesia',	'009',	'102',	'1',	NULL,	1,	'2025-12-02 10:13:14',	'2025-12-02 10:13:22',	'2025-12-02 10:13:22'),
(17,	'BRI',	'Bank Rakyat Indonesia',	NULL,	'Indonesia',	'002',	'103',	'1',	NULL,	1,	'2025-12-02 10:13:14',	'2025-12-02 10:13:22',	'2025-12-02 10:13:22'),
(18,	'MANDIRI',	'Bank Mandiri',	NULL,	'Indonesia',	'008',	'104',	'1',	NULL,	1,	'2025-12-02 10:13:14',	'2025-12-02 10:13:22',	'2025-12-02 10:13:22'),
(4,	'AGI BANK',	'PT. BANK ARTHA GRAHA INTERNASIONAL TBK',	'banks/01KC3XVWZSHEX1AT91PH9PY45P.png',	'Indonesia',	'12123123',	'123123',	'1',	1,	1,	'2025-11-06 09:16:08',	'2025-12-10 10:45:45',	NULL),
(20,	'BCA',	'Bank Central Asia',	NULL,	'Indonesia',	'014',	'101',	'1',	NULL,	1,	'2025-12-17 08:24:51',	'2025-12-17 08:25:10',	'2025-12-17 08:25:10'),
(21,	'BNI',	'Bank Negara Indonesia',	NULL,	'Indonesia',	'009',	'102',	'1',	NULL,	1,	'2025-12-17 08:24:51',	'2025-12-17 08:25:10',	'2025-12-17 08:25:10'),
(22,	'BRI',	'Bank Rakyat Indonesia',	NULL,	'Indonesia',	'002',	'103',	'1',	NULL,	1,	'2025-12-17 08:24:51',	'2025-12-17 08:25:10',	'2025-12-17 08:25:10'),
(23,	'MANDIRI',	'Bank Mandiri',	NULL,	'Indonesia',	'008',	'104',	'1',	NULL,	1,	'2025-12-17 08:24:51',	'2025-12-17 08:25:10',	'2025-12-17 08:25:10'),
(24,	'BCA',	'Bank Central Asia',	NULL,	'Indonesia',	'014',	'101',	'1',	NULL,	1,	'2025-12-17 08:28:23',	'2025-12-17 08:28:23',	NULL),
(25,	'BNI',	'Bank Negara Indonesia',	NULL,	'Indonesia',	'009',	'102',	'1',	NULL,	1,	'2025-12-17 08:28:23',	'2025-12-17 08:28:23',	NULL),
(26,	'BRI',	'Bank Rakyat Indonesia',	NULL,	'Indonesia',	'002',	'103',	'1',	NULL,	1,	'2025-12-17 08:28:23',	'2025-12-17 08:28:23',	NULL),
(27,	'MANDIRI',	'Bank Mandiri',	NULL,	'Indonesia',	'008',	'104',	'1',	NULL,	1,	'2025-12-17 08:28:23',	'2025-12-17 08:28:23',	NULL);

DROP TABLE IF EXISTS "business_types";
DROP SEQUENCE IF EXISTS business_types_id_seq;
CREATE SEQUENCE business_types_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."business_types" (
    "id" bigint DEFAULT nextval('business_types_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "description" text,
    "is_active" boolean DEFAULT true NOT NULL,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "business_types_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "business_types" ("id", "name", "code", "description", "is_active", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(1,	'Perdagangan Umum',	'general-trading',	NULL,	'1',	1,	'2025-11-06 03:54:32',	'2025-11-12 08:24:10',	NULL);

DROP TABLE IF EXISTS "cache";
CREATE TABLE "public"."cache" (
    "key" character varying(255) NOT NULL,
    "value" text NOT NULL,
    "expiration" integer NOT NULL,
    CONSTRAINT "cache_pkey" PRIMARY KEY ("key")
)
WITH (oids = false);

INSERT INTO "cache" ("key", "value", "expiration") VALUES
('laravel-cache-livewire-rate-limiter:2fff5ba6f901fa052a1116376aaaa33993413919:timer',	'i:1765363752;',	1765363752),
('laravel-cache-livewire-rate-limiter:2fff5ba6f901fa052a1116376aaaa33993413919',	'i:1;',	1765363752),
('laravel-cache-livewire-rate-limiter:5d12d115d28785ea01810653b40c820415736dd7:timer',	'i:1765434814;',	1765434814),
('laravel-cache-livewire-rate-limiter:5d12d115d28785ea01810653b40c820415736dd7',	'i:2;',	1765434814),
('laravel-cache-livewire-rate-limiter:52abcdebc1b65ebb6c0a8c3d8aee6fccc3ea958b:timer',	'i:1765435087;',	1765435087),
('laravel-cache-livewire-rate-limiter:52abcdebc1b65ebb6c0a8c3d8aee6fccc3ea958b',	'i:1;',	1765435087),
('laravel-cache-livewire-rate-limiter:2e06c75f277601817ae8d9e43327882f777382bf:timer',	'i:1765445537;',	1765445537),
('laravel-cache-livewire-rate-limiter:2e06c75f277601817ae8d9e43327882f777382bf',	'i:1;',	1765445537),
('laravel-cache-livewire-rate-limiter:6d663e64d8081aa5683daf86f9ee78dc28093090:timer',	'i:1765510869;',	1765510869),
('laravel-cache-livewire-rate-limiter:6d663e64d8081aa5683daf86f9ee78dc28093090',	'i:1;',	1765510869),
('laravel-cache-livewire-rate-limiter:d09ceb38b3bdf89cd3063a561c37bf95d7547e32:timer',	'i:1765514881;',	1765514881),
('laravel-cache-livewire-rate-limiter:d09ceb38b3bdf89cd3063a561c37bf95d7547e32',	'i:1;',	1765514881),
('laravel-cache-spatie.permission.cache',	'a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:346:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:27:"ViewAny:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:24:"View:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:26:"Create:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:26:"Update:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:26:"Delete:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:5;a:4:{s:1:"a";i:6;s:1:"b";s:27:"Restore:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:6;a:4:{s:1:"a";i:7;s:1:"b";s:31:"ForceDelete:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:7;a:4:{s:1:"a";i:8;s:1:"b";s:34:"ForceDeleteAny:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:8;a:4:{s:1:"a";i:9;s:1:"b";s:30:"RestoreAny:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:9;a:4:{s:1:"a";i:10;s:1:"b";s:29:"Replicate:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:10;a:4:{s:1:"a";i:11;s:1:"b";s:27:"Reorder:AdvanceDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:11;a:4:{s:1:"a";i:12;s:1:"b";s:22:"ViewAny:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:12;a:4:{s:1:"a";i:13;s:1:"b";s:19:"View:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:13;a:4:{s:1:"a";i:14;s:1:"b";s:21:"Create:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:14;a:4:{s:1:"a";i:15;s:1:"b";s:21:"Update:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:15;a:4:{s:1:"a";i:16;s:1:"b";s:21:"Delete:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:16;a:4:{s:1:"a";i:17;s:1:"b";s:22:"Restore:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:17;a:4:{s:1:"a";i:18;s:1:"b";s:26:"ForceDelete:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:18;a:4:{s:1:"a";i:19;s:1:"b";s:29:"ForceDeleteAny:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:19;a:4:{s:1:"a";i:20;s:1:"b";s:25:"RestoreAny:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:20;a:4:{s:1:"a";i:21;s:1:"b";s:24:"Replicate:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:21;a:4:{s:1:"a";i:22;s:1:"b";s:22:"Reorder:AdvanceReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:22;a:4:{s:1:"a";i:23;s:1:"b";s:19:"ViewAny:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:23;a:4:{s:1:"a";i:24;s:1:"b";s:16:"View:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:24;a:4:{s:1:"a";i:25;s:1:"b";s:18:"Create:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:25;a:4:{s:1:"a";i:26;s:1:"b";s:18:"Update:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:26;a:4:{s:1:"a";i:27;s:1:"b";s:18:"Delete:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:27;a:4:{s:1:"a";i:28;s:1:"b";s:19:"Restore:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:28;a:4:{s:1:"a";i:29;s:1:"b";s:23:"ForceDelete:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:29;a:4:{s:1:"a";i:30;s:1:"b";s:26:"ForceDeleteAny:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:30;a:4:{s:1:"a";i:31;s:1:"b";s:22:"RestoreAny:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:31;a:4:{s:1:"a";i:32;s:1:"b";s:21:"Replicate:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:32;a:4:{s:1:"a";i:33;s:1:"b";s:19:"Reorder:BankAccount";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:33;a:4:{s:1:"a";i:34;s:1:"b";s:12:"ViewAny:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:34;a:4:{s:1:"a";i:35;s:1:"b";s:9:"View:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:35;a:4:{s:1:"a";i:36;s:1:"b";s:11:"Create:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:36;a:4:{s:1:"a";i:37;s:1:"b";s:11:"Update:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:37;a:4:{s:1:"a";i:38;s:1:"b";s:11:"Delete:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:38;a:4:{s:1:"a";i:39;s:1:"b";s:12:"Restore:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:39;a:4:{s:1:"a";i:40;s:1:"b";s:16:"ForceDelete:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:40;a:4:{s:1:"a";i:41;s:1:"b";s:19:"ForceDeleteAny:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:41;a:4:{s:1:"a";i:42;s:1:"b";s:15:"RestoreAny:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:42;a:4:{s:1:"a";i:43;s:1:"b";s:14:"Replicate:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:43;a:4:{s:1:"a";i:44;s:1:"b";s:12:"Reorder:Bank";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:44;a:4:{s:1:"a";i:45;s:1:"b";s:20:"ViewAny:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:45;a:4:{s:1:"a";i:46;s:1:"b";s:17:"View:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:46;a:4:{s:1:"a";i:47;s:1:"b";s:19:"Create:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:47;a:4:{s:1:"a";i:48;s:1:"b";s:19:"Update:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:48;a:4:{s:1:"a";i:49;s:1:"b";s:19:"Delete:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:49;a:4:{s:1:"a";i:50;s:1:"b";s:20:"Restore:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:50;a:4:{s:1:"a";i:51;s:1:"b";s:24:"ForceDelete:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:51;a:4:{s:1:"a";i:52;s:1:"b";s:27:"ForceDeleteAny:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:52;a:4:{s:1:"a";i:53;s:1:"b";s:23:"RestoreAny:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:53;a:4:{s:1:"a";i:54;s:1:"b";s:22:"Replicate:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:54;a:4:{s:1:"a";i:55;s:1:"b";s:20:"Reorder:BusinessType";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:55;a:4:{s:1:"a";i:56;s:1:"b";s:24:"ViewAny:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:56;a:4:{s:1:"a";i:57;s:1:"b";s:21:"View:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:57;a:4:{s:1:"a";i:58;s:1:"b";s:23:"Create:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:58;a:4:{s:1:"a";i:59;s:1:"b";s:23:"Update:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:59;a:4:{s:1:"a";i:60;s:1:"b";s:23:"Delete:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:60;a:4:{s:1:"a";i:61;s:1:"b";s:24:"Restore:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:61;a:4:{s:1:"a";i:62;s:1:"b";s:28:"ForceDelete:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:62;a:4:{s:1:"a";i:63;s:1:"b";s:31:"ForceDeleteAny:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:63;a:4:{s:1:"a";i:64;s:1:"b";s:27:"RestoreAny:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:64;a:4:{s:1:"a";i:65;s:1:"b";s:26:"Replicate:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:65;a:4:{s:1:"a";i:66;s:1:"b";s:24:"Reorder:CashDisbursement";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:66;a:4:{s:1:"a";i:67;s:1:"b";s:19:"ViewAny:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:67;a:4:{s:1:"a";i:68;s:1:"b";s:16:"View:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:68;a:4:{s:1:"a";i:69;s:1:"b";s:18:"Create:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:69;a:4:{s:1:"a";i:70;s:1:"b";s:18:"Update:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:70;a:4:{s:1:"a";i:71;s:1:"b";s:18:"Delete:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:71;a:4:{s:1:"a";i:72;s:1:"b";s:19:"Restore:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:72;a:4:{s:1:"a";i:73;s:1:"b";s:23:"ForceDelete:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:73;a:4:{s:1:"a";i:74;s:1:"b";s:26:"ForceDeleteAny:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:74;a:4:{s:1:"a";i:75;s:1:"b";s:22:"RestoreAny:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:75;a:4:{s:1:"a";i:76;s:1:"b";s:21:"Replicate:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:76;a:4:{s:1:"a";i:77;s:1:"b";s:19:"Reorder:CashReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:77;a:4:{s:1:"a";i:78;s:1:"b";s:20:"ViewAny:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:78;a:4:{s:1:"a";i:79;s:1:"b";s:17:"View:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:79;a:4:{s:1:"a";i:80;s:1:"b";s:19:"Create:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:80;a:4:{s:1:"a";i:81;s:1:"b";s:19:"Update:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:81;a:4:{s:1:"a";i:82;s:1:"b";s:19:"Delete:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:82;a:4:{s:1:"a";i:83;s:1:"b";s:20:"Restore:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:83;a:4:{s:1:"a";i:84;s:1:"b";s:24:"ForceDelete:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:84;a:4:{s:1:"a";i:85;s:1:"b";s:27:"ForceDeleteAny:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:85;a:4:{s:1:"a";i:86;s:1:"b";s:23:"RestoreAny:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:86;a:4:{s:1:"a";i:87;s:1:"b";s:22:"Replicate:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:87;a:4:{s:1:"a";i:88;s:1:"b";s:20:"Reorder:CashTransfer";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:88;a:4:{s:1:"a";i:89;s:1:"b";s:15:"ViewAny:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:89;a:4:{s:1:"a";i:90;s:1:"b";s:12:"View:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:90;a:4:{s:1:"a";i:91;s:1:"b";s:14:"Create:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:91;a:4:{s:1:"a";i:92;s:1:"b";s:14:"Update:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:92;a:4:{s:1:"a";i:93;s:1:"b";s:14:"Delete:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:93;a:4:{s:1:"a";i:94;s:1:"b";s:15:"Restore:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:94;a:4:{s:1:"a";i:95;s:1:"b";s:19:"ForceDelete:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:95;a:4:{s:1:"a";i:96;s:1:"b";s:22:"ForceDeleteAny:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:96;a:4:{s:1:"a";i:97;s:1:"b";s:18:"RestoreAny:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:97;a:4:{s:1:"a";i:98;s:1:"b";s:17:"Replicate:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:98;a:4:{s:1:"a";i:99;s:1:"b";s:15:"Reorder:Company";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:99;a:4:{s:1:"a";i:100;s:1:"b";s:15:"ViewAny:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:100;a:4:{s:1:"a";i:101;s:1:"b";s:12:"View:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:101;a:4:{s:1:"a";i:102;s:1:"b";s:14:"Create:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:102;a:4:{s:1:"a";i:103;s:1:"b";s:14:"Update:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:103;a:4:{s:1:"a";i:104;s:1:"b";s:14:"Delete:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:104;a:4:{s:1:"a";i:105;s:1:"b";s:15:"Restore:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:105;a:4:{s:1:"a";i:106;s:1:"b";s:19:"ForceDelete:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:106;a:4:{s:1:"a";i:107;s:1:"b";s:22:"ForceDeleteAny:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:107;a:4:{s:1:"a";i:108;s:1:"b";s:18:"RestoreAny:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:108;a:4:{s:1:"a";i:109;s:1:"b";s:17:"Replicate:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:109;a:4:{s:1:"a";i:110;s:1:"b";s:15:"Reorder:Contact";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:110;a:4:{s:1:"a";i:111;s:1:"b";s:18:"ViewAny:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:111;a:4:{s:1:"a";i:112;s:1:"b";s:15:"View:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:112;a:4:{s:1:"a";i:113;s:1:"b";s:17:"Create:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:113;a:4:{s:1:"a";i:114;s:1:"b";s:17:"Update:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:114;a:4:{s:1:"a";i:115;s:1:"b";s:17:"Delete:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:115;a:4:{s:1:"a";i:116;s:1:"b";s:18:"Restore:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:116;a:4:{s:1:"a";i:117;s:1:"b";s:22:"ForceDelete:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:117;a:4:{s:1:"a";i:118;s:1:"b";s:25:"ForceDeleteAny:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:118;a:4:{s:1:"a";i:119;s:1:"b";s:21:"RestoreAny:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:119;a:4:{s:1:"a";i:120;s:1:"b";s:20:"Replicate:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:120;a:4:{s:1:"a";i:121;s:1:"b";s:18:"Reorder:Department";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:121;a:4:{s:1:"a";i:122;s:1:"b";s:18:"ViewAny:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:122;a:4:{s:1:"a";i:123;s:1:"b";s:15:"View:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:123;a:4:{s:1:"a";i:124;s:1:"b";s:17:"Create:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:124;a:4:{s:1:"a";i:125;s:1:"b";s:17:"Update:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:125;a:4:{s:1:"a";i:126;s:1:"b";s:17:"Delete:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:126;a:4:{s:1:"a";i:127;s:1:"b";s:18:"Restore:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:127;a:4:{s:1:"a";i:128;s:1:"b";s:22:"ForceDelete:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:128;a:4:{s:1:"a";i:129;s:1:"b";s:25:"ForceDeleteAny:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:129;a:4:{s:1:"a";i:130;s:1:"b";s:21:"RestoreAny:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:130;a:4:{s:1:"a";i:131;s:1:"b";s:20:"Replicate:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:131;a:4:{s:1:"a";i:132;s:1:"b";s:18:"Reorder:Expedition";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:132;a:4:{s:1:"a";i:133;s:1:"b";s:26:"ViewAny:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:133;a:4:{s:1:"a";i:134;s:1:"b";s:23:"View:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:134;a:4:{s:1:"a";i:135;s:1:"b";s:25:"Create:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:135;a:4:{s:1:"a";i:136;s:1:"b";s:25:"Update:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:136;a:4:{s:1:"a";i:137;s:1:"b";s:25:"Delete:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:137;a:4:{s:1:"a";i:138;s:1:"b";s:26:"Restore:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:138;a:4:{s:1:"a";i:139;s:1:"b";s:30:"ForceDelete:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:139;a:4:{s:1:"a";i:140;s:1:"b";s:33:"ForceDeleteAny:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:140;a:4:{s:1:"a";i:141;s:1:"b";s:29:"RestoreAny:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:141;a:4:{s:1:"a";i:142;s:1:"b";s:28:"Replicate:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:142;a:4:{s:1:"a";i:143;s:1:"b";s:26:"Reorder:FixedAssetCategory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:143;a:4:{s:1:"a";i:144;s:1:"b";s:18:"ViewAny:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:144;a:4:{s:1:"a";i:145;s:1:"b";s:15:"View:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:145;a:4:{s:1:"a";i:146;s:1:"b";s:17:"Create:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:146;a:4:{s:1:"a";i:147;s:1:"b";s:17:"Update:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:147;a:4:{s:1:"a";i:148;s:1:"b";s:17:"Delete:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:148;a:4:{s:1:"a";i:149;s:1:"b";s:18:"Restore:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:149;a:4:{s:1:"a";i:150;s:1:"b";s:22:"ForceDelete:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:150;a:4:{s:1:"a";i:151;s:1:"b";s:25:"ForceDeleteAny:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:151;a:4:{s:1:"a";i:152;s:1:"b";s:21:"RestoreAny:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:152;a:4:{s:1:"a";i:153;s:1:"b";s:20:"Replicate:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:153;a:4:{s:1:"a";i:154;s:1:"b";s:18:"Reorder:FixedAsset";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:154;a:4:{s:1:"a";i:155;s:1:"b";s:20:"ViewAny:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:155;a:4:{s:1:"a";i:156;s:1:"b";s:17:"View:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:156;a:4:{s:1:"a";i:157;s:1:"b";s:19:"Create:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:157;a:4:{s:1:"a";i:158;s:1:"b";s:19:"Update:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:158;a:4:{s:1:"a";i:159;s:1:"b";s:19:"Delete:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:159;a:4:{s:1:"a";i:160;s:1:"b";s:20:"Restore:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:160;a:4:{s:1:"a";i:161;s:1:"b";s:24:"ForceDelete:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:161;a:4:{s:1:"a";i:162;s:1:"b";s:27:"ForceDeleteAny:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:162;a:4:{s:1:"a";i:163;s:1:"b";s:23:"RestoreAny:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:163;a:4:{s:1:"a";i:164;s:1:"b";s:22:"Replicate:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:164;a:4:{s:1:"a";i:165;s:1:"b";s:20:"Reorder:GoodsReceipt";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:165;a:4:{s:1:"a";i:166;s:1:"b";s:20:"ViewAny:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:166;a:4:{s:1:"a";i:167;s:1:"b";s:17:"View:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:167;a:4:{s:1:"a";i:168;s:1:"b";s:19:"Create:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:168;a:4:{s:1:"a";i:169;s:1:"b";s:19:"Update:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:169;a:4:{s:1:"a";i:170;s:1:"b";s:19:"Delete:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:170;a:4:{s:1:"a";i:171;s:1:"b";s:20:"Restore:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:171;a:4:{s:1:"a";i:172;s:1:"b";s:24:"ForceDelete:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:172;a:4:{s:1:"a";i:173;s:1:"b";s:27:"ForceDeleteAny:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:173;a:4:{s:1:"a";i:174;s:1:"b";s:23:"RestoreAny:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:174;a:4:{s:1:"a";i:175;s:1:"b";s:22:"Replicate:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:175;a:4:{s:1:"a";i:176;s:1:"b";s:20:"Reorder:JournalEntry";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:176;a:4:{s:1:"a";i:177;s:1:"b";s:19:"ViewAny:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:177;a:4:{s:1:"a";i:178;s:1:"b";s:16:"View:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:178;a:4:{s:1:"a";i:179;s:1:"b";s:18:"Create:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:179;a:4:{s:1:"a";i:180;s:1:"b";s:18:"Update:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:180;a:4:{s:1:"a";i:181;s:1:"b";s:18:"Delete:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:181;a:4:{s:1:"a";i:182;s:1:"b";s:19:"Restore:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:182;a:4:{s:1:"a";i:183;s:1:"b";s:23:"ForceDelete:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:183;a:4:{s:1:"a";i:184;s:1:"b";s:26:"ForceDeleteAny:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:184;a:4:{s:1:"a";i:185;s:1:"b";s:22:"RestoreAny:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:185;a:4:{s:1:"a";i:186;s:1:"b";s:21:"Replicate:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:186;a:4:{s:1:"a";i:187;s:1:"b";s:19:"Reorder:PaymentTerm";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:187;a:4:{s:1:"a";i:188;s:1:"b";s:20:"ViewAny:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:188;a:4:{s:1:"a";i:189;s:1:"b";s:17:"View:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:189;a:4:{s:1:"a";i:190;s:1:"b";s:19:"Create:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:190;a:4:{s:1:"a";i:191;s:1:"b";s:19:"Update:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:191;a:4:{s:1:"a";i:192;s:1:"b";s:19:"Delete:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:192;a:4:{s:1:"a";i:193;s:1:"b";s:20:"Restore:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:193;a:4:{s:1:"a";i:194;s:1:"b";s:24:"ForceDelete:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:194;a:4:{s:1:"a";i:195;s:1:"b";s:27:"ForceDeleteAny:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:195;a:4:{s:1:"a";i:196;s:1:"b";s:23:"RestoreAny:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:196;a:4:{s:1:"a";i:197;s:1:"b";s:22:"Replicate:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:197;a:4:{s:1:"a";i:198;s:1:"b";s:20:"Reorder:ProductGroup";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:198;a:4:{s:1:"a";i:199;s:1:"b";s:15:"ViewAny:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:199;a:4:{s:1:"a";i:200;s:1:"b";s:12:"View:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:200;a:4:{s:1:"a";i:201;s:1:"b";s:14:"Create:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:201;a:4:{s:1:"a";i:202;s:1:"b";s:14:"Update:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:202;a:4:{s:1:"a";i:203;s:1:"b";s:14:"Delete:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:203;a:4:{s:1:"a";i:204;s:1:"b";s:15:"Restore:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:204;a:4:{s:1:"a";i:205;s:1:"b";s:19:"ForceDelete:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:205;a:4:{s:1:"a";i:206;s:1:"b";s:22:"ForceDeleteAny:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:206;a:4:{s:1:"a";i:207;s:1:"b";s:18:"RestoreAny:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:207;a:4:{s:1:"a";i:208;s:1:"b";s:17:"Replicate:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:208;a:4:{s:1:"a";i:209;s:1:"b";s:15:"Reorder:Product";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:209;a:4:{s:1:"a";i:210;s:1:"b";s:23:"ViewAny:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:210;a:4:{s:1:"a";i:211;s:1:"b";s:20:"View:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:211;a:4:{s:1:"a";i:212;s:1:"b";s:22:"Create:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:212;a:4:{s:1:"a";i:213;s:1:"b";s:22:"Update:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:213;a:4:{s:1:"a";i:214;s:1:"b";s:22:"Delete:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:214;a:4:{s:1:"a";i:215;s:1:"b";s:23:"Restore:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:215;a:4:{s:1:"a";i:216;s:1:"b";s:27:"ForceDelete:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:216;a:4:{s:1:"a";i:217;s:1:"b";s:30:"ForceDeleteAny:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:217;a:4:{s:1:"a";i:218;s:1:"b";s:26:"RestoreAny:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:218;a:4:{s:1:"a";i:219;s:1:"b";s:25:"Replicate:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:219;a:4:{s:1:"a";i:220;s:1:"b";s:23:"Reorder:PurchaseInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:220;a:4:{s:1:"a";i:221;s:1:"b";s:21:"ViewAny:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:221;a:4:{s:1:"a";i:222;s:1:"b";s:18:"View:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:222;a:4:{s:1:"a";i:223;s:1:"b";s:20:"Create:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:223;a:4:{s:1:"a";i:224;s:1:"b";s:20:"Update:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:224;a:4:{s:1:"a";i:225;s:1:"b";s:20:"Delete:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:225;a:4:{s:1:"a";i:226;s:1:"b";s:21:"Restore:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:226;a:4:{s:1:"a";i:227;s:1:"b";s:25:"ForceDelete:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:227;a:4:{s:1:"a";i:228;s:1:"b";s:28:"ForceDeleteAny:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:228;a:4:{s:1:"a";i:229;s:1:"b";s:24:"RestoreAny:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:229;a:4:{s:1:"a";i:230;s:1:"b";s:23:"Replicate:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:230;a:4:{s:1:"a";i:231;s:1:"b";s:21:"Reorder:PurchaseOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:231;a:4:{s:1:"a";i:232;s:1:"b";s:22:"ViewAny:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:232;a:4:{s:1:"a";i:233;s:1:"b";s:19:"View:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:233;a:4:{s:1:"a";i:234;s:1:"b";s:21:"Create:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:234;a:4:{s:1:"a";i:235;s:1:"b";s:21:"Update:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:235;a:4:{s:1:"a";i:236;s:1:"b";s:21:"Delete:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:236;a:4:{s:1:"a";i:237;s:1:"b";s:22:"Restore:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:237;a:4:{s:1:"a";i:238;s:1:"b";s:26:"ForceDelete:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:238;a:4:{s:1:"a";i:239;s:1:"b";s:29:"ForceDeleteAny:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:239;a:4:{s:1:"a";i:240;s:1:"b";s:25:"RestoreAny:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:240;a:4:{s:1:"a";i:241;s:1:"b";s:24:"Replicate:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:241;a:4:{s:1:"a";i:242;s:1:"b";s:22:"Reorder:PurchaseReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:242;a:4:{s:1:"a";i:243;s:1:"b";s:24:"ViewAny:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:243;a:4:{s:1:"a";i:244;s:1:"b";s:21:"View:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:244;a:4:{s:1:"a";i:245;s:1:"b";s:23:"Create:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:245;a:4:{s:1:"a";i:246;s:1:"b";s:23:"Update:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:246;a:4:{s:1:"a";i:247;s:1:"b";s:23:"Delete:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:247;a:4:{s:1:"a";i:248;s:1:"b";s:24:"Restore:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:248;a:4:{s:1:"a";i:249;s:1:"b";s:28:"ForceDelete:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:249;a:4:{s:1:"a";i:250;s:1:"b";s:31:"ForceDeleteAny:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:250;a:4:{s:1:"a";i:251;s:1:"b";s:27:"RestoreAny:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:251;a:4:{s:1:"a";i:252;s:1:"b";s:26:"Replicate:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:252;a:4:{s:1:"a";i:253;s:1:"b";s:24:"Reorder:DeliveryDocument";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:253;a:4:{s:1:"a";i:254;s:1:"b";s:20:"ViewAny:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:254;a:4:{s:1:"a";i:255;s:1:"b";s:17:"View:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:255;a:4:{s:1:"a";i:256;s:1:"b";s:19:"Create:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:256;a:4:{s:1:"a";i:257;s:1:"b";s:19:"Update:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:257;a:4:{s:1:"a";i:258;s:1:"b";s:19:"Delete:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:258;a:4:{s:1:"a";i:259;s:1:"b";s:20:"Restore:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:259;a:4:{s:1:"a";i:260;s:1:"b";s:24:"ForceDelete:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:260;a:4:{s:1:"a";i:261;s:1:"b";s:27:"ForceDeleteAny:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:261;a:4:{s:1:"a";i:262;s:1:"b";s:23:"RestoreAny:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:262;a:4:{s:1:"a";i:263;s:1:"b";s:22:"Replicate:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:263;a:4:{s:1:"a";i:264;s:1:"b";s:20:"Reorder:SalesInvoice";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:264;a:4:{s:1:"a";i:265;s:1:"b";s:18:"ViewAny:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:265;a:4:{s:1:"a";i:266;s:1:"b";s:15:"View:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:266;a:4:{s:1:"a";i:267;s:1:"b";s:17:"Create:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:267;a:4:{s:1:"a";i:268;s:1:"b";s:17:"Update:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:268;a:4:{s:1:"a";i:269;s:1:"b";s:17:"Delete:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:269;a:4:{s:1:"a";i:270;s:1:"b";s:18:"Restore:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:270;a:4:{s:1:"a";i:271;s:1:"b";s:22:"ForceDelete:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:271;a:4:{s:1:"a";i:272;s:1:"b";s:25:"ForceDeleteAny:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:272;a:4:{s:1:"a";i:273;s:1:"b";s:21:"RestoreAny:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:273;a:4:{s:1:"a";i:274;s:1:"b";s:20:"Replicate:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:274;a:4:{s:1:"a";i:275;s:1:"b";s:18:"Reorder:SalesOrder";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:275;a:4:{s:1:"a";i:276;s:1:"b";s:19:"ViewAny:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:276;a:4:{s:1:"a";i:277;s:1:"b";s:16:"View:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:277;a:4:{s:1:"a";i:278;s:1:"b";s:18:"Create:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:278;a:4:{s:1:"a";i:279;s:1:"b";s:18:"Update:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:279;a:4:{s:1:"a";i:280;s:1:"b";s:18:"Delete:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:280;a:4:{s:1:"a";i:281;s:1:"b";s:19:"Restore:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:281;a:4:{s:1:"a";i:282;s:1:"b";s:23:"ForceDelete:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:282;a:4:{s:1:"a";i:283;s:1:"b";s:26:"ForceDeleteAny:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:283;a:4:{s:1:"a";i:284;s:1:"b";s:22:"RestoreAny:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:284;a:4:{s:1:"a";i:285;s:1:"b";s:21:"Replicate:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:285;a:4:{s:1:"a";i:286;s:1:"b";s:19:"Reorder:SalesReturn";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:286;a:4:{s:1:"a";i:287;s:1:"b";s:11:"ViewAny:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:287;a:4:{s:1:"a";i:288;s:1:"b";s:8:"View:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:288;a:4:{s:1:"a";i:289;s:1:"b";s:10:"Create:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:289;a:4:{s:1:"a";i:290;s:1:"b";s:10:"Update:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:290;a:4:{s:1:"a";i:291;s:1:"b";s:10:"Delete:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:291;a:4:{s:1:"a";i:292;s:1:"b";s:11:"Restore:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:292;a:4:{s:1:"a";i:293;s:1:"b";s:15:"ForceDelete:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:293;a:4:{s:1:"a";i:294;s:1:"b";s:18:"ForceDeleteAny:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:294;a:4:{s:1:"a";i:295;s:1:"b";s:14:"RestoreAny:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:295;a:4:{s:1:"a";i:296;s:1:"b";s:13:"Replicate:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:296;a:4:{s:1:"a";i:297;s:1:"b";s:11:"Reorder:Tax";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:297;a:4:{s:1:"a";i:298;s:1:"b";s:12:"ViewAny:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:298;a:4:{s:1:"a";i:299;s:1:"b";s:9:"View:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:299;a:4:{s:1:"a";i:300;s:1:"b";s:11:"Create:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:300;a:4:{s:1:"a";i:301;s:1:"b";s:11:"Update:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:301;a:4:{s:1:"a";i:302;s:1:"b";s:11:"Delete:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:302;a:4:{s:1:"a";i:303;s:1:"b";s:12:"Restore:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:303;a:4:{s:1:"a";i:304;s:1:"b";s:16:"ForceDelete:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:304;a:4:{s:1:"a";i:305;s:1:"b";s:19:"ForceDeleteAny:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:305;a:4:{s:1:"a";i:306;s:1:"b";s:15:"RestoreAny:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:306;a:4:{s:1:"a";i:307;s:1:"b";s:14:"Replicate:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:307;a:4:{s:1:"a";i:308;s:1:"b";s:12:"Reorder:Unit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:308;a:4:{s:1:"a";i:309;s:1:"b";s:12:"ViewAny:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:309;a:4:{s:1:"a";i:310;s:1:"b";s:9:"View:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:310;a:4:{s:1:"a";i:311;s:1:"b";s:11:"Create:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:311;a:4:{s:1:"a";i:312;s:1:"b";s:11:"Update:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:312;a:4:{s:1:"a";i:313;s:1:"b";s:11:"Delete:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:313;a:4:{s:1:"a";i:314;s:1:"b";s:12:"Restore:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:314;a:4:{s:1:"a";i:315;s:1:"b";s:16:"ForceDelete:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:315;a:4:{s:1:"a";i:316;s:1:"b";s:19:"ForceDeleteAny:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:316;a:4:{s:1:"a";i:317;s:1:"b";s:15:"RestoreAny:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:317;a:4:{s:1:"a";i:318;s:1:"b";s:14:"Replicate:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:318;a:4:{s:1:"a";i:319;s:1:"b";s:12:"Reorder:User";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:319;a:4:{s:1:"a";i:320;s:1:"b";s:17:"ViewAny:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:320;a:4:{s:1:"a";i:321;s:1:"b";s:14:"View:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:321;a:4:{s:1:"a";i:322;s:1:"b";s:16:"Create:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:322;a:4:{s:1:"a";i:323;s:1:"b";s:16:"Update:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:323;a:4:{s:1:"a";i:324;s:1:"b";s:16:"Delete:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:324;a:4:{s:1:"a";i:325;s:1:"b";s:17:"Restore:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:325;a:4:{s:1:"a";i:326;s:1:"b";s:21:"ForceDelete:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:326;a:4:{s:1:"a";i:327;s:1:"b";s:24:"ForceDeleteAny:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:327;a:4:{s:1:"a";i:328;s:1:"b";s:20:"RestoreAny:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:328;a:4:{s:1:"a";i:329;s:1:"b";s:19:"Replicate:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:329;a:4:{s:1:"a";i:330;s:1:"b";s:17:"Reorder:Warehouse";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:330;a:4:{s:1:"a";i:331;s:1:"b";s:12:"ViewAny:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:331;a:4:{s:1:"a";i:332;s:1:"b";s:9:"View:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:332;a:4:{s:1:"a";i:333;s:1:"b";s:11:"Create:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:333;a:4:{s:1:"a";i:334;s:1:"b";s:11:"Update:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:334;a:4:{s:1:"a";i:335;s:1:"b";s:11:"Delete:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:335;a:4:{s:1:"a";i:336;s:1:"b";s:12:"Restore:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:336;a:4:{s:1:"a";i:337;s:1:"b";s:16:"ForceDelete:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:337;a:4:{s:1:"a";i:338;s:1:"b";s:19:"ForceDeleteAny:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:338;a:4:{s:1:"a";i:339;s:1:"b";s:15:"RestoreAny:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:339;a:4:{s:1:"a";i:340;s:1:"b";s:14:"Replicate:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:340;a:4:{s:1:"a";i:341;s:1:"b";s:12:"Reorder:Role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:341;a:3:{s:1:"a";i:342;s:1:"b";s:28:"View:ManageDocumentNumbering";s:1:"c";s:3:"web";}i:342;a:4:{s:1:"a";i:343;s:1:"b";s:19:"View:ManageAccounts";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:343;a:4:{s:1:"a";i:344;s:1:"b";s:14:"View:Dashboard";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:344;a:4:{s:1:"a";i:345;s:1:"b";s:26:"View:ManageOpeningBalances";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:345;a:4:{s:1:"a";i:346;s:1:"b";s:27:"View:ManageReferenceNumbers";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}}s:5:"roles";a:1:{i:0;a:3:{s:1:"a";i:1;s:1:"b";s:11:"super_admin";s:1:"c";s:3:"web";}}}',	1766046098),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer',	'i:1765960740;',	1765960740),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab',	'i:1;',	1765960740),
('laravel-cache-livewire-rate-limiter:056fc329aaaa757d31db450f525da23fde4d1b36:timer',	'i:1765966852;',	1765966852),
('laravel-cache-livewire-rate-limiter:056fc329aaaa757d31db450f525da23fde4d1b36',	'i:1;',	1765966852);

DROP TABLE IF EXISTS "cache_locks";
CREATE TABLE "public"."cache_locks" (
    "key" character varying(255) NOT NULL,
    "owner" character varying(255) NOT NULL,
    "expiration" integer NOT NULL,
    CONSTRAINT "cache_locks_pkey" PRIMARY KEY ("key")
)
WITH (oids = false);


DROP TABLE IF EXISTS "cash_disbursement_items";
DROP SEQUENCE IF EXISTS cash_disbursement_items_id_seq;
CREATE SEQUENCE cash_disbursement_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."cash_disbursement_items" (
    "id" bigint DEFAULT nextval('cash_disbursement_items_id_seq') NOT NULL,
    "amount" character varying(255) NOT NULL,
    "description" text,
    "cash_disbursement_id" bigint NOT NULL,
    "account_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "cash_disbursement_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "cash_disbursements";
DROP SEQUENCE IF EXISTS cash_disbursements_id_seq;
CREATE SEQUENCE cash_disbursements_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."cash_disbursements" (
    "id" bigint DEFAULT nextval('cash_disbursements_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "total" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "recipient_id" bigint NOT NULL,
    "from_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "cash_disbursements_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "cash_disbursements_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "cash_receipt_items";
DROP SEQUENCE IF EXISTS cash_receipt_items_id_seq;
CREATE SEQUENCE cash_receipt_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."cash_receipt_items" (
    "id" bigint DEFAULT nextval('cash_receipt_items_id_seq') NOT NULL,
    "amount" character varying(255) NOT NULL,
    "description" text,
    "cash_receipt_id" bigint NOT NULL,
    "account_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "cash_receipt_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "cash_receipts";
DROP SEQUENCE IF EXISTS cash_receipts_id_seq;
CREATE SEQUENCE cash_receipts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."cash_receipts" (
    "id" bigint DEFAULT nextval('cash_receipts_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "total" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "recipient_id" bigint NOT NULL,
    "to_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "cash_receipts_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "cash_receipts_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "cash_transfers";
DROP SEQUENCE IF EXISTS cash_transfers_id_seq;
CREATE SEQUENCE cash_transfers_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."cash_transfers" (
    "id" bigint DEFAULT nextval('cash_transfers_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "amount" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "from_account_id" bigint NOT NULL,
    "to_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "cash_transfers_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "cash_transfers_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "check_disbursements";
DROP SEQUENCE IF EXISTS check_disbursements_id_seq;
CREATE SEQUENCE check_disbursements_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."check_disbursements" (
    "id" bigint DEFAULT nextval('check_disbursements_id_seq') NOT NULL,
    "date" date NOT NULL,
    "check_number" character varying(255) NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "due_date" date NOT NULL,
    "description" text,
    "amount" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "bank_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "check_disbursements_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "check_disbursements_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('issued'::character varying)::text, ('cleared'::character varying)::text, ('bounced'::character varying)::text, ('cancelled'::character varying)::text, ('void'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "companies";
DROP SEQUENCE IF EXISTS companies_id_seq;
CREATE SEQUENCE companies_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."companies" (
    "id" bigint DEFAULT nextval('companies_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "description" text,
    "tax_id" character varying(50),
    "fiscal_year_start" date,
    "fiscal_year_end" date,
    "tax_period" character varying(255) DEFAULT 'monthly' NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "settings" json,
    "billing_address_line_1" character varying(255),
    "billing_address_line_2" character varying(255),
    "billing_city" character varying(100),
    "billing_state" character varying(100),
    "billing_postal_code" character varying(20),
    "billing_country" character varying(100),
    "delivery_address_line_1" character varying(255),
    "delivery_address_line_2" character varying(255),
    "delivery_city" character varying(100),
    "delivery_state" character varying(100),
    "delivery_postal_code" character varying(20),
    "delivery_country" character varying(100),
    "photo" character varying(255),
    "business_type_id" bigint NOT NULL,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    "tax_document" character varying(255),
    CONSTRAINT "companies_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "companies_tax_period_check" CHECK (((tax_period)::text = ANY (ARRAY[('monthly'::character varying)::text, ('quarterly'::character varying)::text, ('semi_annual'::character varying)::text, ('annual'::character varying)::text])))
)
WITH (oids = false);

INSERT INTO "companies" ("id", "name", "description", "tax_id", "fiscal_year_start", "fiscal_year_end", "tax_period", "is_active", "settings", "billing_address_line_1", "billing_address_line_2", "billing_city", "billing_state", "billing_postal_code", "billing_country", "delivery_address_line_1", "delivery_address_line_2", "delivery_city", "delivery_state", "delivery_postal_code", "delivery_country", "photo", "business_type_id", "created_by_user_id", "created_at", "updated_at", "deleted_at", "tax_document") VALUES
(3,	'Test Company Ltd',	'A test company for demonstration',	NULL,	'2025-01-01',	'2025-12-31',	'monthly',	'1',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	1,	'2025-11-06 04:40:31',	'2025-11-06 04:41:23',	'2025-11-06 04:41:23',	NULL),
(4,	'PT OTHER',	NULL,	'123123',	'2025-11-06',	'2025-11-06',	'monthly',	'1',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	1,	'2025-11-06 09:16:42',	'2025-11-06 09:16:42',	NULL,	NULL),
(1,	'PT Pelangi Sentral Kreasi',	NULL,	'123123123123',	'2025-11-06',	'2025-11-06',	'monthly',	'1',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'companies/01KC3XAEBMSARET2QG404N1FDJ.png',	1,	1,	'2025-11-06 04:04:00',	'2025-12-10 10:37:46',	NULL,	'companies/tax-documents/01KC3XD9S8B7JA48N2TK3MXQD8.png'),
(9,	'Test Company New',	NULL,	'123456789012345678',	'2024-01-01',	'2024-12-31',	'monthly',	'1',	NULL,	'Test Address',	NULL,	'Test City',	NULL,	'12345',	'Indonesia',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	3,	'2025-12-17 10:04:59',	'2025-12-17 10:05:22',	'2025-12-17 10:05:22',	NULL);

DROP TABLE IF EXISTS "contacts";
DROP SEQUENCE IF EXISTS contacts_id_seq;
CREATE SEQUENCE contacts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."contacts" (
    "id" bigint DEFAULT nextval('contacts_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "email" character varying(150),
    "phone" character varying(50),
    "tax" character varying(50),
    "contact_person" character varying(200),
    "is_customer" boolean DEFAULT false NOT NULL,
    "is_supplier" boolean DEFAULT false NOT NULL,
    "is_employee" boolean DEFAULT false NOT NULL,
    "is_sales" boolean DEFAULT false NOT NULL,
    "credit_limit" numeric(15,2) DEFAULT '0' NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "billing_address_line_1" character varying(255),
    "billing_address_line_2" character varying(255),
    "billing_city" character varying(100),
    "billing_state" character varying(100),
    "billing_postal_code" character varying(20),
    "billing_country" character varying(100),
    "delivery_address_line_1" character varying(255),
    "delivery_address_line_2" character varying(255),
    "delivery_city" character varying(100),
    "delivery_state" character varying(100),
    "delivery_postal_code" character varying(20),
    "delivery_country" character varying(100),
    "supporting_document" character varying(255),
    "payment_term_id" bigint,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    "contact_code" character varying(50),
    CONSTRAINT "contacts_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "contacts" ("id", "name", "email", "phone", "tax", "contact_person", "is_customer", "is_supplier", "is_employee", "is_sales", "credit_limit", "is_active", "billing_address_line_1", "billing_address_line_2", "billing_city", "billing_state", "billing_postal_code", "billing_country", "delivery_address_line_1", "delivery_address_line_2", "delivery_city", "delivery_state", "delivery_postal_code", "delivery_country", "supporting_document", "payment_term_id", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at", "contact_code") VALUES
(8,	'Dotcom',	NULL,	NULL,	NULL,	NULL,	'1',	'1',	'0',	'0',	0.00,	'1',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	1,	'2025-11-18 10:56:45',	'2025-11-18 10:56:45',	NULL,	NULL),
(9,	'Admin',	NULL,	NULL,	NULL,	NULL,	'1',	'1',	'0',	'0',	0.00,	'1',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	'2025-11-18 10:56:55',	'2025-11-18 10:56:55',	NULL,	NULL);

DROP TABLE IF EXISTS "cost_centers";
DROP SEQUENCE IF EXISTS cost_centers_id_seq;
CREATE SEQUENCE cost_centers_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."cost_centers" (
    "id" bigint DEFAULT nextval('cost_centers_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "department_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "cost_centers_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "delivery_document_items";
DROP SEQUENCE IF EXISTS delivery_document_items_id_seq;
CREATE SEQUENCE delivery_document_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."delivery_document_items" (
    "id" bigint DEFAULT nextval('delivery_document_items_id_seq') NOT NULL,
    "total_quantity" character varying(255) NOT NULL,
    "description" text,
    "delivery_allocation" json NOT NULL,
    "delivery_document_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "warehouse_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "delivery_document_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "delivery_documents";
DROP SEQUENCE IF EXISTS delivery_documents_id_seq;
CREATE SEQUENCE delivery_documents_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."delivery_documents" (
    "id" bigint DEFAULT nextval('delivery_documents_id_seq') NOT NULL,
    "date" date NOT NULL,
    "is_closed" boolean DEFAULT false NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "delivery_status" character varying(255) NOT NULL,
    "tracking_number" character varying(100),
    "bast_document" character varying(255),
    "tpb_document" character varying(255),
    "dispatch_time" timestamp(0),
    "completion_time" timestamp(0),
    "customer_id" bigint NOT NULL,
    "sales_order_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "expedition_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    "delivery_type" character varying(255) DEFAULT 'goods' NOT NULL,
    CONSTRAINT "delivery_documents_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "delivery_documents_delivery_status_check" CHECK (((delivery_status)::text = ANY (ARRAY[('pending'::character varying)::text, ('picked'::character varying)::text, ('in_transit'::character varying)::text, ('delivered'::character varying)::text, ('failed'::character varying)::text, ('cancelled'::character varying)::text]))),
    CONSTRAINT "delivery_documents_delivery_type_check" CHECK (((delivery_type)::text = ANY (ARRAY[('document'::character varying)::text, ('goods'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "delivery_updates";
DROP SEQUENCE IF EXISTS delivery_updates_id_seq;
CREATE SEQUENCE delivery_updates_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."delivery_updates" (
    "id" bigint DEFAULT nextval('delivery_updates_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "date" date NOT NULL,
    "task_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "delivery_updates_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "delivery_updates_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('in_progress'::character varying)::text, ('completed'::character varying)::text, ('delayed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "departments";
DROP SEQUENCE IF EXISTS departments_id_seq;
CREATE SEQUENCE departments_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."departments" (
    "id" bigint DEFAULT nextval('departments_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "departments_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "departments" ("id", "name", "code", "is_active", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(1,	'Dotcom',	'dotcom',	'1',	1,	1,	'2025-11-06 09:38:08',	'2025-11-06 09:38:08',	NULL),
(26,	'Sumber Daya Manusia',	'HR',	'1',	NULL,	1,	'2025-12-17 08:38:02',	'2025-12-17 08:38:02',	NULL),
(27,	'Teknologi Informasi',	'IT',	'1',	NULL,	1,	'2025-12-17 08:38:02',	'2025-12-17 08:38:02',	NULL),
(28,	'Keuangan',	'FIN',	'1',	NULL,	1,	'2025-12-17 08:38:02',	'2025-12-17 08:38:02',	NULL),
(29,	'Operasional',	'OPS',	'1',	NULL,	1,	'2025-12-17 08:38:02',	'2025-12-17 08:38:02',	NULL);

DROP TABLE IF EXISTS "document_numberings";
DROP SEQUENCE IF EXISTS document_numberings_id_seq;
CREATE SEQUENCE document_numberings_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."document_numberings" (
    "id" bigint DEFAULT nextval('document_numberings_id_seq') NOT NULL,
    "document_type" character varying(50) NOT NULL,
    "prefix" character varying(20) NOT NULL,
    "format" character varying(50) NOT NULL,
    "next_number" integer DEFAULT '1' NOT NULL,
    "reset_period" character varying(255) NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    "format_components" json,
    CONSTRAINT "document_numberings_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "document_numberings_reset_period_check" CHECK (((reset_period)::text = ANY (ARRAY[('never'::character varying)::text, ('daily'::character varying)::text, ('weekly'::character varying)::text, ('monthly'::character varying)::text, ('quarterly'::character varying)::text, ('yearly'::character varying)::text])))
)
WITH (oids = false);

INSERT INTO "document_numberings" ("id", "document_type", "prefix", "format", "next_number", "reset_period", "is_active", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at", "format_components") VALUES
(256,	'product_group',	'PRG',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:12:46',	'2025-12-17 10:12:46',	NULL,	'["prefix","number"]'),
(257,	'product_group',	'PRG',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:12:46',	'2025-12-17 10:12:46',	NULL,	'["prefix","number"]'),
(188,	'sales_invoice',	'INV',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(189,	'purchase_invoice',	'SUP',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(190,	'cash_receipt',	'CR',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(191,	'cash_disbursement',	'CD',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(192,	'journal_entry',	'JE',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(193,	'sales_order',	'SO',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(194,	'purchase_order',	'PO',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(195,	'product',	'PRD',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(196,	'fixed_asset',	'FA',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(197,	'unit_measurement',	'UM',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(198,	'bank_account',	'BA',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(199,	'warehouse',	'WH',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(200,	'department',	'DPT',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(201,	'tax',	'TAX',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(202,	'expedition',	'EXP',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(203,	'contact',	'CT',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(204,	'bank',	'BK',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(205,	'business_type',	'BT',	'{CODE}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(206,	'advance_disbursement',	'ADV',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(207,	'advance_receipt',	'AR',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(208,	'cash_transfer',	'TRF',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(209,	'check_disbursement',	'CHK',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(210,	'delivery_document',	'DO',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(211,	'fixed_asset_transaction',	'FAT',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(212,	'goods_receipt',	'GR',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(213,	'inventory_adjustment',	'IA',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(214,	'overpayment_receipt',	'OR',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(215,	'overpayment_refund',	'RF',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(216,	'payable_payment',	'PP',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(217,	'purchase_return',	'PRN',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(218,	'receivable_payment',	'RP',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(219,	'sales_return',	'SRN',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(220,	'stock_opname',	'SO',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(221,	'warehouse_transfer',	'WT',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	4,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(222,	'sales_invoice',	'INV',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(223,	'purchase_invoice',	'SUP',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(224,	'cash_receipt',	'CR',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(225,	'cash_disbursement',	'CD',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(226,	'journal_entry',	'JE',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(227,	'sales_order',	'SO',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(228,	'purchase_order',	'PO',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(229,	'product',	'PRD',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(230,	'fixed_asset',	'FA',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(231,	'unit_measurement',	'UM',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(232,	'bank_account',	'BA',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(233,	'warehouse',	'WH',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(234,	'department',	'DPT',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(235,	'tax',	'TAX',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(236,	'expedition',	'EXP',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(237,	'contact',	'CT',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(238,	'bank',	'BK',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(239,	'business_type',	'BT',	'{CODE}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","number"]'),
(240,	'advance_disbursement',	'ADV',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(241,	'advance_receipt',	'AR',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(242,	'cash_transfer',	'TRF',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(243,	'check_disbursement',	'CHK',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(244,	'delivery_document',	'DO',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(245,	'fixed_asset_transaction',	'FAT',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(246,	'goods_receipt',	'GR',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(247,	'inventory_adjustment',	'IA',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(248,	'overpayment_receipt',	'OR',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(249,	'overpayment_refund',	'RF',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(250,	'payable_payment',	'PP',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(251,	'purchase_return',	'PRN',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(252,	'receivable_payment',	'RP',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(253,	'sales_return',	'SRN',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(254,	'stock_opname',	'SO',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]'),
(255,	'warehouse_transfer',	'WT',	'{CODE}{YYYY}{NUMBER}',	0,	'never',	'1',	1,	1,	'2025-12-17 10:07:51',	'2025-12-17 10:07:51',	NULL,	'["prefix","year_full","number"]');

DROP TABLE IF EXISTS "expeditions";
DROP SEQUENCE IF EXISTS expeditions_id_seq;
CREATE SEQUENCE expeditions_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."expeditions" (
    "id" bigint DEFAULT nextval('expeditions_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "expeditions_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "expeditions" ("id", "name", "code", "is_active", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(2,	'test',	'test',	'0',	NULL,	1,	'2025-11-18 11:07:29',	'2025-11-18 11:07:29',	NULL),
(3,	'asd',	'asd',	'0',	1,	1,	'2025-11-18 11:07:39',	'2025-11-18 11:07:39',	NULL);

DROP TABLE IF EXISTS "failed_jobs";
DROP SEQUENCE IF EXISTS failed_jobs_id_seq;
CREATE SEQUENCE failed_jobs_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."failed_jobs" (
    "id" bigint DEFAULT nextval('failed_jobs_id_seq') NOT NULL,
    "uuid" character varying(255) NOT NULL,
    "connection" text NOT NULL,
    "queue" text NOT NULL,
    "payload" text NOT NULL,
    "exception" text NOT NULL,
    "failed_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT "failed_jobs_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX failed_jobs_uuid_unique ON public.failed_jobs USING btree (uuid);


DROP TABLE IF EXISTS "financial_years";
DROP SEQUENCE IF EXISTS financial_years_id_seq;
CREATE SEQUENCE financial_years_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."financial_years" (
    "id" bigint DEFAULT nextval('financial_years_id_seq') NOT NULL,
    "name" character varying(100) NOT NULL,
    "start_date" date NOT NULL,
    "end_date" date NOT NULL,
    "is_current" boolean DEFAULT false NOT NULL,
    "is_locked" boolean DEFAULT false NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "financial_years_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "fixed_asset_categories";
DROP SEQUENCE IF EXISTS fixed_asset_categories_id_seq;
CREATE SEQUENCE fixed_asset_categories_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."fixed_asset_categories" (
    "id" bigint DEFAULT nextval('fixed_asset_categories_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "depreciation_method" character varying(255) NOT NULL,
    "useful_life" integer NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "sales_account_id" bigint NOT NULL,
    "asset_account_id" bigint NOT NULL,
    "accumulated_depreciation_account_id" bigint NOT NULL,
    "depreciation_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "fixed_asset_categories_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "fixed_asset_categories_depreciation_method_check" CHECK (((depreciation_method)::text = ANY (ARRAY[('straight_line'::character varying)::text, ('declining_balance'::character varying)::text, ('double_declining'::character varying)::text, ('sum_of_years'::character varying)::text, ('units_of_production'::character varying)::text])))
)
WITH (oids = false);

INSERT INTO "fixed_asset_categories" ("id", "name", "depreciation_method", "useful_life", "is_active", "sales_account_id", "asset_account_id", "accumulated_depreciation_account_id", "depreciation_account_id", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(2,	'Gedung',	'straight_line',	20,	'1',	1,	238,	247,	333,	1,	1,	'2025-11-17 08:58:01',	'2025-11-17 08:58:01',	NULL),
(3,	'Harta Lainnya',	'straight_line',	4,	'1',	1,	241,	250,	336,	1,	1,	'2025-11-17 08:58:01',	'2025-11-17 08:58:01',	NULL),
(4,	'Kendaraan',	'straight_line',	8,	'1',	1,	240,	249,	335,	1,	1,	'2025-11-17 08:58:01',	'2025-11-17 08:58:01',	NULL),
(5,	'Mesin & Peralatan',	'straight_line',	4,	'1',	1,	239,	248,	334,	1,	1,	'2025-11-17 08:58:01',	'2025-11-17 08:58:01',	NULL),
(6,	'Tanah',	'straight_line',	0,	'1',	1,	237,	1,	1,	1,	1,	'2025-11-17 08:58:01',	'2025-11-17 08:58:01',	NULL);

DROP TABLE IF EXISTS "fixed_asset_category_templates";
DROP SEQUENCE IF EXISTS fixed_asset_category_templates_id_seq;
CREATE SEQUENCE fixed_asset_category_templates_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."fixed_asset_category_templates" (
    "id" bigint DEFAULT nextval('fixed_asset_category_templates_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "depreciation_method" character varying(255) NOT NULL,
    "useful_life" integer NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "asset_account_code" character varying(255),
    "accumulated_depreciation_account_code" character varying(255),
    "depreciation_account_code" character varying(255),
    "sales_account_code" character varying(255),
    "template_name" character varying(255) NOT NULL,
    "notes" text,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "fixed_asset_category_templates_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE INDEX fixed_asset_category_templates_template_name_name_index ON public.fixed_asset_category_templates USING btree (template_name, name);

INSERT INTO "fixed_asset_category_templates" ("id", "name", "depreciation_method", "useful_life", "is_active", "asset_account_code", "accumulated_depreciation_account_code", "depreciation_account_code", "sales_account_code", "template_name", "notes", "created_at", "updated_at") VALUES
(1,	'Gedung',	'straight_line',	20,	'1',	'121000101',	'122000100',	'710000002',	'400000001',	'Standard Indonesian Fixed Asset Categories',	'Standard Indonesian Fixed Asset Category template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(2,	'Harta Lainnya',	'straight_line',	4,	'1',	'121000104',	'122000103',	'710000005',	'400000001',	'Standard Indonesian Fixed Asset Categories',	'Standard Indonesian Fixed Asset Category template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(3,	'Kendaraan',	'straight_line',	8,	'1',	'121000103',	'122000102',	'710000004',	'400000001',	'Standard Indonesian Fixed Asset Categories',	'Standard Indonesian Fixed Asset Category template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(4,	'Mesin & Peralatan',	'straight_line',	4,	'1',	'121000102',	'122000101',	'710000003',	'400000001',	'Standard Indonesian Fixed Asset Categories',	'Standard Indonesian Fixed Asset Category template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(5,	'Tanah',	'straight_line',	0,	'1',	'121000100',	NULL,	NULL,	'400000001',	'Standard Indonesian Fixed Asset Categories',	'Standard Indonesian Fixed Asset Category template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23');

DROP TABLE IF EXISTS "fixed_asset_depreciations";
DROP SEQUENCE IF EXISTS fixed_asset_depreciations_id_seq;
CREATE SEQUENCE fixed_asset_depreciations_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."fixed_asset_depreciations" (
    "id" bigint DEFAULT nextval('fixed_asset_depreciations_id_seq') NOT NULL,
    "year_number" integer NOT NULL,
    "period_start" date NOT NULL,
    "period_end" date NOT NULL,
    "months_count" integer NOT NULL,
    "beginning_book_value" character varying(255) NOT NULL,
    "percentage" numeric(5,2) NOT NULL,
    "yearly_depreciation" character varying(255) NOT NULL,
    "monthly_depreciation" character varying(255) NOT NULL,
    "ending_book_value" character varying(255) NOT NULL,
    "fixed_asset_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "fixed_asset_depreciations_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "fixed_asset_disposals";
DROP SEQUENCE IF EXISTS fixed_asset_disposals_id_seq;
CREATE SEQUENCE fixed_asset_disposals_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."fixed_asset_disposals" (
    "id" bigint DEFAULT nextval('fixed_asset_disposals_id_seq') NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "date" date NOT NULL,
    "description" text,
    "disposal_value" character varying(255) NOT NULL,
    "fixed_asset_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "fixed_asset_disposals_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "fixed_asset_transactions";
DROP SEQUENCE IF EXISTS fixed_asset_transactions_id_seq;
CREATE SEQUENCE fixed_asset_transactions_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."fixed_asset_transactions" (
    "id" bigint DEFAULT nextval('fixed_asset_transactions_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "journal_value" character varying(255) NOT NULL,
    "asset_value" character varying(255) NOT NULL,
    "difference" character varying(255) NOT NULL,
    "transaction_type" character varying(255) NOT NULL,
    "fixed_asset_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "fixed_asset_transactions_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "fixed_asset_transactions_transaction_type_check" CHECK (((transaction_type)::text = ANY (ARRAY[('acquisition'::character varying)::text, ('depreciation'::character varying)::text, ('revaluation'::character varying)::text, ('disposal'::character varying)::text, ('impairment'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "fixed_assets";
DROP SEQUENCE IF EXISTS fixed_assets_id_seq;
CREATE SEQUENCE fixed_assets_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."fixed_assets" (
    "id" bigint DEFAULT nextval('fixed_assets_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "location" character varying(200),
    "acquisition_date" date NOT NULL,
    "description" text,
    "acquisition_value" numeric(15,2) NOT NULL,
    "monthly_depreciation" numeric(15,2) DEFAULT '0' NOT NULL,
    "depreciation_method" character varying(255) DEFAULT 'straight_line' NOT NULL,
    "accumulated_depreciation" numeric(15,2) DEFAULT '0' NOT NULL,
    "useful_life" integer NOT NULL,
    "book_value" numeric(15,2) NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "category_id" bigint NOT NULL,
    "department_id" bigint NOT NULL,
    "transaction_in_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "fixed_assets_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "fixed_assets_depreciation_method_check" CHECK (((depreciation_method)::text = ANY (ARRAY[('straight_line'::character varying)::text, ('declining_balance'::character varying)::text, ('double_declining'::character varying)::text, ('sum_of_years'::character varying)::text, ('units_of_production'::character varying)::text])))
)
WITH (oids = false);

INSERT INTO "fixed_assets" ("id", "name", "code", "location", "acquisition_date", "description", "acquisition_value", "monthly_depreciation", "depreciation_method", "accumulated_depreciation", "useful_life", "book_value", "is_active", "category_id", "department_id", "transaction_in_id", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(6,	'Mobil Toyota Avanza',	'AST-001',	'Gudang Utama',	'2023-01-15',	'Mobil operasional untuk delivery barang',	250000000.00,	4166667.00,	'straight_line',	25000000.00,	5,	225000000.00,	'1',	1,	1,	1,	NULL,	1,	'2025-12-03 04:32:26',	'2025-12-03 04:33:31',	'2025-12-03 04:33:31'),
(7,	'Laptop Dell Inspiron',	'AST-002',	'Ruang Kantor IT',	'2023-03-20',	'Laptop untuk staff IT',	15000000.00,	625000.00,	'straight_line',	750000.00,	2,	14250000.00,	'1',	5,	2,	2,	NULL,	1,	'2025-12-03 04:32:26',	'2025-12-03 04:33:31',	'2025-12-03 04:33:31'),
(8,	'Mesin Produksi Type A',	'AST-003',	'Pabrik Produksi',	'2022-06-10',	'Mesin untuk produksi makanan',	500000000.00,	4166667.00,	'straight_line',	125000000.00,	10,	375000000.00,	'1',	4,	3,	3,	NULL,	1,	'2025-12-03 04:32:26',	'2025-12-03 04:33:31',	'2025-12-03 04:33:31'),
(9,	'Gedung Kantor Cabang',	'AST-004',	'Jl. Sudirman No. 123',	'2021-12-01',	'Gedung kantor cabang operasional',	2000000000.00,	8333333.00,	'straight_line',	150000000.00,	20,	1850000000.00,	'1',	3,	4,	4,	NULL,	1,	'2025-12-03 04:32:26',	'2025-12-03 04:33:31',	'2025-12-03 04:33:31'),
(10,	'Kursi Kantor Ergonomis',	'AST-005',	'Ruang Kantor Umum',	'2023-07-05',	'Kursi kerja untuk staff administrasi',	2500000.00,	104167.00,	'double_declining',	208333.00,	2,	2291667.00,	'0',	2,	4,	5,	NULL,	1,	'2025-12-03 04:32:26',	'2025-12-03 04:33:31',	'2025-12-03 04:33:31'),
(11,	'Mobil Toyota Avanza',	'AST-001',	'Gudang Utama',	'2023-01-15',	'Mobil operasional untuk delivery barang',	250000000.00,	4166667.00,	'straight_line',	25000000.00,	5,	225000000.00,	'1',	1,	1,	1,	NULL,	1,	'2025-12-03 04:33:39',	'2025-12-03 04:33:39',	NULL),
(12,	'Laptop Dell Inspiron',	'AST-002',	'Ruang Kantor IT',	'2023-03-20',	'Laptop untuk staff IT',	15000000.00,	625000.00,	'straight_line',	750000.00,	2,	14250000.00,	'1',	5,	2,	2,	NULL,	1,	'2025-12-03 04:33:39',	'2025-12-03 04:33:39',	NULL),
(13,	'Mesin Produksi Type A',	'AST-003',	'Pabrik Produksi',	'2022-06-10',	'Mesin untuk produksi makanan',	500000000.00,	4166667.00,	'straight_line',	125000000.00,	10,	375000000.00,	'1',	4,	3,	3,	NULL,	1,	'2025-12-03 04:33:39',	'2025-12-03 04:33:39',	NULL),
(14,	'Gedung Kantor Cabang',	'AST-004',	'Jl. Sudirman No. 123',	'2021-12-01',	'Gedung kantor cabang operasional',	2000000000.00,	8333333.00,	'straight_line',	150000000.00,	20,	1850000000.00,	'1',	3,	4,	4,	NULL,	1,	'2025-12-03 04:33:39',	'2025-12-03 04:33:39',	NULL),
(15,	'Kursi Kantor Ergonomis',	'AST-005',	'Ruang Kantor Umum',	'2023-07-05',	'Kursi kerja untuk staff administrasi',	2500000.00,	104167.00,	'double_declining',	208333.00,	2,	2291667.00,	'0',	2,	4,	5,	NULL,	1,	'2025-12-03 04:33:39',	'2025-12-03 04:33:39',	NULL);

DROP TABLE IF EXISTS "goods_receipt_items";
DROP SEQUENCE IF EXISTS goods_receipt_items_id_seq;
CREATE SEQUENCE goods_receipt_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."goods_receipt_items" (
    "id" bigint DEFAULT nextval('goods_receipt_items_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "description" text,
    "batch_number" character varying(50),
    "expiry_date" date,
    "unit_cost" character varying(255) DEFAULT '0' NOT NULL,
    "goods_receipt_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "warehouse_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "goods_receipt_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "goods_receipts";
DROP SEQUENCE IF EXISTS goods_receipts_id_seq;
CREATE SEQUENCE goods_receipts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."goods_receipts" (
    "id" bigint DEFAULT nextval('goods_receipts_id_seq') NOT NULL,
    "date" date NOT NULL,
    "is_closed" boolean DEFAULT false NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "status" character varying(255) NOT NULL,
    "supplier_id" bigint NOT NULL,
    "purchase_order_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "goods_receipts_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "goods_receipts_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('received'::character varying)::text, ('inspected'::character varying)::text, ('approved'::character varying)::text, ('rejected'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "inventory_adjustment_items";
DROP SEQUENCE IF EXISTS inventory_adjustment_items_id_seq;
CREATE SEQUENCE inventory_adjustment_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."inventory_adjustment_items" (
    "id" bigint DEFAULT nextval('inventory_adjustment_items_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "cost_of_goods_sold" character varying(255) DEFAULT '0' NOT NULL,
    "description" text,
    "inventory_adjustment_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "account_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "inventory_adjustment_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "inventory_adjustments";
DROP SEQUENCE IF EXISTS inventory_adjustments_id_seq;
CREATE SEQUENCE inventory_adjustments_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."inventory_adjustments" (
    "id" bigint DEFAULT nextval('inventory_adjustments_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "status" character varying(255) NOT NULL,
    "warehouse_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "inventory_adjustments_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "inventory_adjustments_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "inventory_transactions";
DROP SEQUENCE IF EXISTS inventory_transactions_id_seq;
CREATE SEQUENCE inventory_transactions_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."inventory_transactions" (
    "id" bigint DEFAULT nextval('inventory_transactions_id_seq') NOT NULL,
    "transaction_type" character varying(255) NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "unit_cost" character varying(255) DEFAULT '0' NOT NULL,
    "total_cost" character varying(255) DEFAULT '0' NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "date" date NOT NULL,
    "description" text,
    "batch_number" character varying(50),
    "expiry_date" date,
    "product_id" bigint NOT NULL,
    "warehouse_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "inventory_transactions_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "inventory_transactions_transaction_type_check" CHECK (((transaction_type)::text = ANY (ARRAY[('purchase'::character varying)::text, ('sale'::character varying)::text, ('return_in'::character varying)::text, ('return_out'::character varying)::text, ('adjustment'::character varying)::text, ('transfer_in'::character varying)::text, ('transfer_out'::character varying)::text, ('opname'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "job_batches";
CREATE TABLE "public"."job_batches" (
    "id" character varying(255) NOT NULL,
    "name" character varying(255) NOT NULL,
    "total_jobs" integer NOT NULL,
    "pending_jobs" integer NOT NULL,
    "failed_jobs" integer NOT NULL,
    "failed_job_ids" text NOT NULL,
    "options" text,
    "cancelled_at" integer,
    "created_at" integer NOT NULL,
    "finished_at" integer,
    CONSTRAINT "job_batches_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "jobs";
DROP SEQUENCE IF EXISTS jobs_id_seq;
CREATE SEQUENCE jobs_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."jobs" (
    "id" bigint DEFAULT nextval('jobs_id_seq') NOT NULL,
    "queue" character varying(255) NOT NULL,
    "payload" text NOT NULL,
    "attempts" smallint NOT NULL,
    "reserved_at" integer,
    "available_at" integer NOT NULL,
    "created_at" integer NOT NULL,
    CONSTRAINT "jobs_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);

INSERT INTO "jobs" ("id", "queue", "payload", "attempts", "reserved_at", "available_at", "created_at") VALUES
(1,	'default',	'{"uuid":"2143a0e6-b4e3-4936-83d5-21f311c6bd26","displayName":"Filament\\Auth\\Notifications\\ResetPassword","job":"Illuminate\\Queue\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\Notifications\\SendQueuedNotifications","command":"O:48:\"Illuminate\\Notifications\\SendQueuedNotifications\":3:{s:11:\"notifiables\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:15:\"App\\Models\\User\";s:2:\"id\";a:1:{i:0;i:1;}s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"pgsql\";s:15:\"collectionClass\";N;}s:12:\"notification\";O:41:\"Filament\\Auth\\Notifications\\ResetPassword\":3:{s:3:\"url\";s:228:\"http:\/\/127.0.0.1:8000\/main\/password-reset\/reset?email=mohamad.basori12%40gmail.com&token=63116c546fb8ce6bacb463dc92af5137171c4df8c29ed596e934e03bd727a675&signature=039ee6add9e58af5a63ca8be54883f7b4400305d06f68f7bf3183886aaae1c64\";s:5:\"token\";s:64:\"63116c546fb8ce6bacb463dc92af5137171c4df8c29ed596e934e03bd727a675\";s:2:\"id\";s:36:\"514f47aa-0c49-4bf1-8142-59e3e65f5e2e\";}s:8:\"channels\";a:1:{i:0;s:4:\"mail\";}}"},"createdAt":1762502361,"delay":null}',	0,	NULL,	1762502361,	1762502361);

DROP TABLE IF EXISTS "journal_entries";
DROP SEQUENCE IF EXISTS journal_entries_id_seq;
CREATE SEQUENCE journal_entries_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."journal_entries" (
    "id" bigint DEFAULT nextval('journal_entries_id_seq') NOT NULL,
    "entry_number" character varying(50) NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255),
    "description" text,
    "amount" numeric(15,2) NOT NULL,
    "status" character varying(255) DEFAULT 'draft' NOT NULL,
    "document_no" character varying(255),
    "document_date" date,
    "posted_at" timestamp(0),
    "department_id" bigint NOT NULL,
    "posted_by_user_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "journal_entries_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "journal_entries_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('posted'::character varying)::text, ('reversed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "journal_entry_items";
DROP SEQUENCE IF EXISTS journal_entry_items_id_seq;
CREATE SEQUENCE journal_entry_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."journal_entry_items" (
    "id" bigint DEFAULT nextval('journal_entry_items_id_seq') NOT NULL,
    "debit" numeric(15,2) NOT NULL,
    "credit" numeric(15,2) NOT NULL,
    "notes" text,
    "journal_entry_id" bigint NOT NULL,
    "account_id" bigint NOT NULL,
    "cost_center_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "journal_entry_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "migrations";
DROP SEQUENCE IF EXISTS migrations_id_seq;
CREATE SEQUENCE migrations_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."migrations" (
    "id" integer DEFAULT nextval('migrations_id_seq') NOT NULL,
    "migration" character varying(255) NOT NULL,
    "batch" integer NOT NULL,
    CONSTRAINT "migrations_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "migrations" ("id", "migration", "batch") VALUES
(1,	'0001_01_01_000000_create_users_table',	1),
(2,	'0001_01_01_000001_create_cache_table',	1),
(3,	'0001_01_01_000002_create_jobs_table',	1),
(4,	'2025_10_20_082515_create_business_types_table',	1),
(5,	'2025_10_20_082516_create_companies_table',	1),
(6,	'2025_10_20_082517_create_user_companies_table',	1),
(7,	'2025_10_20_082518_create_payment_terms_table',	1),
(8,	'2025_10_20_082519_create_banks_table',	1),
(9,	'2025_10_20_082520_create_expeditions_table',	1),
(10,	'2025_10_20_082521_create_account_classifications_table',	1),
(11,	'2025_10_20_082522_create_departments_table',	1),
(12,	'2025_10_20_082523_create_accounts_table',	1),
(13,	'2025_10_20_082524_create_cost_centers_table',	1),
(14,	'2025_10_20_082525_create_taxes_table',	1),
(15,	'2025_10_20_082526_create_units_table',	1),
(16,	'2025_10_20_082527_create_product_groups_table',	1),
(17,	'2025_10_20_082528_create_warehouses_table',	1),
(18,	'2025_10_20_082529_create_transaction_classifications_table',	1),
(19,	'2025_10_20_082530_create_document_numberings_table',	1),
(20,	'2025_10_20_082531_create_fixed_asset_categories_table',	1),
(21,	'2025_10_20_082532_create_financial_years_table',	1),
(22,	'2025_10_20_082533_create_contacts_table',	1),
(23,	'2025_10_20_082534_create_products_table',	1),
(24,	'2025_10_20_082535_create_bank_accounts_table',	1),
(25,	'2025_10_20_082536_create_fixed_assets_table',	1),
(26,	'2025_10_20_082537_create_projects_table',	1),
(27,	'2025_10_20_082538_create_tasks_table',	1),
(28,	'2025_10_20_082539_create_task_updates_table',	1),
(29,	'2025_10_20_082540_create_milestones_table',	1),
(30,	'2025_10_20_082541_create_advance_payments_table',	1),
(31,	'2025_10_20_082542_create_sales_orders_table',	1),
(32,	'2025_10_20_082543_create_sales_order_items_table',	1),
(33,	'2025_10_20_082544_create_delivery_documents_table',	1),
(34,	'2025_10_20_082545_create_delivery_document_items_table',	1),
(35,	'2025_10_20_082546_create_sales_invoices_table',	1),
(36,	'2025_10_20_082547_create_sales_invoice_items_table',	1),
(37,	'2025_10_20_082548_create_sales_returns_table',	1),
(38,	'2025_10_20_082549_create_sales_return_items_table',	1),
(39,	'2025_10_20_082550_create_receivable_payments_table',	1),
(40,	'2025_10_20_082551_create_receivable_payment_items_table',	1),
(41,	'2025_10_20_082552_create_overpayment_refunds_table',	1),
(42,	'2025_10_20_082553_create_purchase_orders_table',	1),
(43,	'2025_10_20_082554_create_purchase_order_items_table',	1),
(44,	'2025_10_20_082555_create_goods_receipts_table',	1),
(45,	'2025_10_20_082556_create_goods_receipt_items_table',	1),
(46,	'2025_10_20_082557_create_purchase_invoices_table',	1),
(47,	'2025_10_20_082558_create_purchase_invoice_items_table',	1),
(48,	'2025_10_20_082559_create_purchase_returns_table',	1),
(49,	'2025_10_20_082600_create_purchase_return_items_table',	1),
(50,	'2025_10_20_082601_create_payable_payments_table',	1),
(51,	'2025_10_20_082602_create_payable_payment_items_table',	1),
(52,	'2025_10_20_082603_create_overpayment_receipts_table',	1),
(53,	'2025_10_20_082604_create_bank_reconciliations_table',	1),
(54,	'2025_10_20_082605_create_check_disbursements_table',	1),
(55,	'2025_10_20_082606_create_cash_disbursements_table',	1),
(56,	'2025_10_20_082607_create_cash_disbursement_items_table',	1),
(57,	'2025_10_20_082608_create_advance_disbursements_table',	1),
(58,	'2025_10_20_082609_create_advance_disbursement_items_table',	1),
(59,	'2025_10_20_082610_create_cash_receipts_table',	1),
(60,	'2025_10_20_082611_create_cash_receipt_items_table',	1),
(61,	'2025_10_20_082612_create_advance_receipts_table',	1),
(62,	'2025_10_20_082613_create_advance_receipt_items_table',	1),
(63,	'2025_10_20_082614_create_cash_transfers_table',	1),
(64,	'2025_10_20_082615_create_inventory_transactions_table',	1),
(65,	'2025_10_20_082616_create_inventory_adjustments_table',	1),
(66,	'2025_10_20_082617_create_inventory_adjustment_items_table',	1),
(67,	'2025_10_20_082618_create_stock_opnames_table',	1),
(68,	'2025_10_20_082619_create_stock_opname_items_table',	1),
(69,	'2025_10_20_082620_create_warehouse_transfers_table',	1),
(70,	'2025_10_20_082621_create_warehouse_transfer_items_table',	1),
(71,	'2025_10_20_082622_create_fixed_asset_transactions_table',	1),
(72,	'2025_10_20_082623_create_fixed_asset_depreciations_table',	1),
(73,	'2025_10_20_082624_create_fixed_asset_disposals_table',	1),
(74,	'2025_10_20_082625_create_journal_entries_table',	1),
(75,	'2025_10_20_082626_create_journal_entry_items_table',	1),
(76,	'2025_10_20_082627_create_opening_balances_table',	1),
(77,	'2025_10_20_082628_create_period_closings_table',	1),
(78,	'2025_10_20_082629_create_advance_payment_allocations_table',	1),
(79,	'2025_10_20_082630_create_delivery_updates_table',	1),
(80,	'2025_10_20_082631_create_task_user_table',	1),
(81,	'2025_10_20_082632_create_account_journal_entry_table',	1),
(82,	'2025_10_20_084204_add_tax_document_to_companies_table',	1),
(83,	'2025_10_23_095829_update_delivery_document_enum_fields',	1),
(84,	'2025_10_27_081104_create_notifications_table',	2),
(85,	'2025_10_28_083807_add_classification_fields_to_accounts_table',	3),
(86,	'2025_10_28_083835_drop_account_classifications_table',	3),
(87,	'2025_10_28_084647_make_company_and_user_nullable_in_accounts_table',	4),
(88,	'2025_10_28_110000_add_cash_flow_to_accounts_table',	5),
(89,	'2025_10_28_110100_add_classification_id_to_accounts_table',	6),
(90,	'2025_11_05_102113_add_format_components_to_document_numberings_table',	7),
(91,	'2025_11_06_040058_make_all_description_columns_nullable',	8),
(93,	'2025_11_06_041127_create_permission_tables',	9),
(94,	'2025_11_06_092000_make_logo_nullable_in_banks_table',	10),
(95,	'2025_11_06_092810_modify_assigned_by_user_id_nullable_in_user_companies',	10),
(96,	'2025_11_07_120000_make_company_id_columns_nullable',	11),
(97,	'2025_11_07_104950_make_payment_term_id_nullable_in_contacts_table',	12),
(98,	'2025_11_12_095856_make_company_id_nullable_in_document_numberings_table',	13),
(99,	'2025_11_17_083056_make_product_groups_company_id_nullable',	14),
(100,	'2025_11_18_044759_create_account_templates_table',	15),
(103,	'2025_11_18_102511_make_effective_date_nullable_in_taxes_table',	16),
(104,	'2025_11_18_104316_create_fixed_asset_category_templates_table',	17),
(105,	'2025_11_18_104352_create_tax_templates_table',	17),
(106,	'2025_12_01_062031_make_company_id_nullable_in_remaining_tables',	18),
(108,	'2025_12_16_072800_add_code_to_product_groups_table',	19),
(109,	'2025_12_16_065000_make_fiscal_year_nullable_in_companies_table',	20),
(110,	'2025_12_16_071420_make_clearing_and_skn_nullable_in_banks_table',	20),
(111,	'2025_12_17_043049_make_purchase_and_sales_account_nullable_in_taxes_table',	20),
(112,	'2025_12_17_043413_make_tax_type_nullable_in_taxes_table',	20),
(113,	'2025_12_17_050513_make_sales_orders_foreign_keys_nullable',	20),
(115,	'2025_12_17_095739_migrate_existing_reference_numbers_to_company_specific',	21);

DROP TABLE IF EXISTS "milestones";
DROP SEQUENCE IF EXISTS milestones_id_seq;
CREATE SEQUENCE milestones_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."milestones" (
    "id" bigint DEFAULT nextval('milestones_id_seq') NOT NULL,
    "milestone_number" character varying(50) NOT NULL,
    "milestone_type" character varying(255) NOT NULL,
    "title" character varying(200) NOT NULL,
    "description" text,
    "target_date" date NOT NULL,
    "actual_date" date,
    "pending_history" json,
    "job_id" bigint NOT NULL,
    "company_id" bigint,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "milestones_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "milestones_milestone_type_check" CHECK (((milestone_type)::text = ANY (ARRAY[('phase'::character varying)::text, ('deliverable'::character varying)::text, ('payment'::character varying)::text, ('review'::character varying)::text, ('acceptance'::character varying)::text])))
)
WITH (oids = false);

CREATE UNIQUE INDEX milestones_milestone_number_unique ON public.milestones USING btree (milestone_number);


DROP TABLE IF EXISTS "model_has_permissions";
CREATE TABLE "public"."model_has_permissions" (
    "permission_id" bigint NOT NULL,
    "model_type" character varying(255) NOT NULL,
    "model_id" bigint NOT NULL,
    CONSTRAINT "model_has_permissions_pkey" PRIMARY KEY ("permission_id", "model_id", "model_type")
)
WITH (oids = false);

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


DROP TABLE IF EXISTS "model_has_roles";
CREATE TABLE "public"."model_has_roles" (
    "role_id" bigint NOT NULL,
    "model_type" character varying(255) NOT NULL,
    "model_id" bigint NOT NULL,
    CONSTRAINT "model_has_roles_pkey" PRIMARY KEY ("role_id", "model_id", "model_type")
)
WITH (oids = false);

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);

INSERT INTO "model_has_roles" ("role_id", "model_type", "model_id") VALUES
(1,	'App\Models\User',	1),
(2,	'App\Models\User',	2),
(4,	'App\Models\User',	2);

DROP TABLE IF EXISTS "notifications";
CREATE TABLE "public"."notifications" (
    "id" uuid NOT NULL,
    "type" character varying(255) NOT NULL,
    "notifiable_type" character varying(255) NOT NULL,
    "notifiable_id" bigint NOT NULL,
    "data" text NOT NULL,
    "read_at" timestamp(0),
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "notifications_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


DROP TABLE IF EXISTS "opening_balances";
DROP SEQUENCE IF EXISTS opening_balances_id_seq;
CREATE SEQUENCE opening_balances_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."opening_balances" (
    "id" bigint DEFAULT nextval('opening_balances_id_seq') NOT NULL,
    "balance_type" character varying(255) NOT NULL,
    "amount" character varying(255) NOT NULL,
    "date" date NOT NULL,
    "description" text,
    "account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "opening_balances_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "opening_balances_balance_type_check" CHECK (((balance_type)::text = ANY (ARRAY[('debit'::character varying)::text, ('credit'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "overpayment_receipts";
DROP SEQUENCE IF EXISTS overpayment_receipts_id_seq;
CREATE SEQUENCE overpayment_receipts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."overpayment_receipts" (
    "id" bigint DEFAULT nextval('overpayment_receipts_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "amount" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "supplier_id" bigint NOT NULL,
    "payable_payment_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "overpayment_receipts_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "overpayment_receipts_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('processed'::character varying)::text, ('failed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "overpayment_refunds";
DROP SEQUENCE IF EXISTS overpayment_refunds_id_seq;
CREATE SEQUENCE overpayment_refunds_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."overpayment_refunds" (
    "id" bigint DEFAULT nextval('overpayment_refunds_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "amount" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "customer_id" bigint NOT NULL,
    "receivable_payment_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "overpayment_refunds_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "overpayment_refunds_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('processed'::character varying)::text, ('failed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "password_reset_tokens";
CREATE TABLE "public"."password_reset_tokens" (
    "email" character varying(255) NOT NULL,
    "token" character varying(255) NOT NULL,
    "created_at" timestamp(0),
    CONSTRAINT "password_reset_tokens_pkey" PRIMARY KEY ("email")
)
WITH (oids = false);

INSERT INTO "password_reset_tokens" ("email", "token", "created_at") VALUES
('mohamad.basori12@gmail.com',	'$2y$12$OuIrMcVwmkrO9ftPQHZbCONOXI4GrH3YcI1rRvuEDj9j8Zxrz6Tdq',	'2025-11-07 07:59:21');

DROP TABLE IF EXISTS "payable_payment_items";
DROP SEQUENCE IF EXISTS payable_payment_items_id_seq;
CREATE SEQUENCE payable_payment_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."payable_payment_items" (
    "id" bigint DEFAULT nextval('payable_payment_items_id_seq') NOT NULL,
    "date" date NOT NULL,
    "amount" character varying(255) NOT NULL,
    "paid_amount" character varying(255) NOT NULL,
    "discount_amount" character varying(255) DEFAULT '0' NOT NULL,
    "write_off_amount" character varying(255) DEFAULT '0' NOT NULL,
    "set_payment" character varying(255) NOT NULL,
    "payable_payment_id" bigint NOT NULL,
    "purchase_invoice_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "payable_payment_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "payable_payments";
DROP SEQUENCE IF EXISTS payable_payments_id_seq;
CREATE SEQUENCE payable_payments_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."payable_payments" (
    "id" bigint DEFAULT nextval('payable_payments_id_seq') NOT NULL,
    "payment_date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "total_payment" character varying(255) NOT NULL,
    "payment_method" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "supplier_id" bigint NOT NULL,
    "bank_account_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "payable_payments_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "payable_payments_payment_method_check" CHECK (((payment_method)::text = ANY (ARRAY[('cash'::character varying)::text, ('bank_transfer'::character varying)::text, ('check'::character varying)::text, ('credit_card'::character varying)::text, ('debit_card'::character varying)::text, ('online_payment'::character varying)::text, ('other'::character varying)::text]))),
    CONSTRAINT "payable_payments_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('completed'::character varying)::text, ('failed'::character varying)::text, ('cancelled'::character varying)::text, ('refunded'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "payment_terms";
DROP SEQUENCE IF EXISTS payment_terms_id_seq;
CREATE SEQUENCE payment_terms_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."payment_terms" (
    "id" bigint DEFAULT nextval('payment_terms_id_seq') NOT NULL,
    "name" character varying(100) NOT NULL,
    "description" text,
    "due_days" integer NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "payment_terms_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "period_closings";
DROP SEQUENCE IF EXISTS period_closings_id_seq;
CREATE SEQUENCE period_closings_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."period_closings" (
    "id" bigint DEFAULT nextval('period_closings_id_seq') NOT NULL,
    "period_type" character varying(255) NOT NULL,
    "start_date" date NOT NULL,
    "end_date" date NOT NULL,
    "status" character varying(255) NOT NULL,
    "closed_at" timestamp(0) NOT NULL,
    "description" text,
    "closed_by_user_id" bigint NOT NULL,
    "company_id" bigint,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "period_closings_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "period_closings_period_type_check" CHECK (((period_type)::text = ANY (ARRAY[('daily'::character varying)::text, ('weekly'::character varying)::text, ('monthly'::character varying)::text, ('quarterly'::character varying)::text, ('yearly'::character varying)::text]))),
    CONSTRAINT "period_closings_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('in_progress'::character varying)::text, ('completed'::character varying)::text, ('failed'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "permissions";
DROP SEQUENCE IF EXISTS permissions_id_seq;
CREATE SEQUENCE permissions_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."permissions" (
    "id" bigint DEFAULT nextval('permissions_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "guard_name" character varying(255) NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "permissions_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX permissions_name_guard_name_unique ON public.permissions USING btree (name, guard_name);

INSERT INTO "permissions" ("id", "name", "guard_name", "created_at", "updated_at") VALUES
(1,	'ViewAny:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(2,	'View:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(3,	'Create:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(4,	'Update:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(5,	'Delete:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(6,	'Restore:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(7,	'ForceDelete:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(8,	'ForceDeleteAny:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(9,	'RestoreAny:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(10,	'Replicate:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(11,	'Reorder:AdvanceDisbursement',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(12,	'ViewAny:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(13,	'View:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(14,	'Create:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(15,	'Update:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(16,	'Delete:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(17,	'Restore:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(18,	'ForceDelete:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(19,	'ForceDeleteAny:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(20,	'RestoreAny:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(21,	'Replicate:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(22,	'Reorder:AdvanceReceipt',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(23,	'ViewAny:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(24,	'View:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(25,	'Create:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(26,	'Update:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(27,	'Delete:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(28,	'Restore:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(29,	'ForceDelete:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(30,	'ForceDeleteAny:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(31,	'RestoreAny:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(32,	'Replicate:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(33,	'Reorder:BankAccount',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(34,	'ViewAny:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(35,	'View:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(36,	'Create:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(37,	'Update:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(38,	'Delete:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(39,	'Restore:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(40,	'ForceDelete:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(41,	'ForceDeleteAny:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(42,	'RestoreAny:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(43,	'Replicate:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(44,	'Reorder:Bank',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23'),
(45,	'ViewAny:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(46,	'View:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(47,	'Create:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(48,	'Update:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(49,	'Delete:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(50,	'Restore:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(51,	'ForceDelete:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(52,	'ForceDeleteAny:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(53,	'RestoreAny:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(54,	'Replicate:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(55,	'Reorder:BusinessType',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(56,	'ViewAny:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(57,	'View:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(58,	'Create:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(59,	'Update:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(60,	'Delete:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(61,	'Restore:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(62,	'ForceDelete:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(63,	'ForceDeleteAny:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(64,	'RestoreAny:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(65,	'Replicate:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(66,	'Reorder:CashDisbursement',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(67,	'ViewAny:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(68,	'View:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(69,	'Create:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(70,	'Update:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(71,	'Delete:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(72,	'Restore:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(73,	'ForceDelete:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(74,	'ForceDeleteAny:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(75,	'RestoreAny:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(76,	'Replicate:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(77,	'Reorder:CashReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(78,	'ViewAny:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(79,	'View:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(80,	'Create:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(81,	'Update:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(82,	'Delete:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(83,	'Restore:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(84,	'ForceDelete:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(85,	'ForceDeleteAny:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(86,	'RestoreAny:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(87,	'Replicate:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(88,	'Reorder:CashTransfer',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(89,	'ViewAny:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(90,	'View:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(91,	'Create:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(92,	'Update:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(93,	'Delete:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(94,	'Restore:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(95,	'ForceDelete:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(96,	'ForceDeleteAny:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(97,	'RestoreAny:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(98,	'Replicate:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(99,	'Reorder:Company',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(100,	'ViewAny:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(101,	'View:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(102,	'Create:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(103,	'Update:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(104,	'Delete:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(105,	'Restore:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(106,	'ForceDelete:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(107,	'ForceDeleteAny:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(108,	'RestoreAny:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(109,	'Replicate:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(110,	'Reorder:Contact',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(111,	'ViewAny:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(112,	'View:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(113,	'Create:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(114,	'Update:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(115,	'Delete:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(116,	'Restore:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(117,	'ForceDelete:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(118,	'ForceDeleteAny:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(119,	'RestoreAny:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(120,	'Replicate:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(121,	'Reorder:Department',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(122,	'ViewAny:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(123,	'View:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(124,	'Create:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(125,	'Update:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(126,	'Delete:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(127,	'Restore:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(128,	'ForceDelete:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(129,	'ForceDeleteAny:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(130,	'RestoreAny:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(131,	'Replicate:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(132,	'Reorder:Expedition',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(133,	'ViewAny:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(134,	'View:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(135,	'Create:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(136,	'Update:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(137,	'Delete:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(138,	'Restore:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(139,	'ForceDelete:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(140,	'ForceDeleteAny:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(141,	'RestoreAny:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(142,	'Replicate:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(143,	'Reorder:FixedAssetCategory',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(144,	'ViewAny:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(145,	'View:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(146,	'Create:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(147,	'Update:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(148,	'Delete:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(149,	'Restore:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(150,	'ForceDelete:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(151,	'ForceDeleteAny:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(152,	'RestoreAny:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(153,	'Replicate:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(154,	'Reorder:FixedAsset',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(155,	'ViewAny:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(156,	'View:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(157,	'Create:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(158,	'Update:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(159,	'Delete:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(160,	'Restore:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(161,	'ForceDelete:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(162,	'ForceDeleteAny:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(163,	'RestoreAny:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(164,	'Replicate:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(165,	'Reorder:GoodsReceipt',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(166,	'ViewAny:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(167,	'View:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(168,	'Create:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(169,	'Update:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(170,	'Delete:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(171,	'Restore:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(172,	'ForceDelete:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(173,	'ForceDeleteAny:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(174,	'RestoreAny:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(175,	'Replicate:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(176,	'Reorder:JournalEntry',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(177,	'ViewAny:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(178,	'View:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(179,	'Create:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(180,	'Update:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(181,	'Delete:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(182,	'Restore:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(183,	'ForceDelete:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(184,	'ForceDeleteAny:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(185,	'RestoreAny:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(186,	'Replicate:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(187,	'Reorder:PaymentTerm',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(188,	'ViewAny:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(189,	'View:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(190,	'Create:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(191,	'Update:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(192,	'Delete:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(193,	'Restore:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(194,	'ForceDelete:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(195,	'ForceDeleteAny:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(196,	'RestoreAny:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(197,	'Replicate:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(198,	'Reorder:ProductGroup',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(199,	'ViewAny:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(200,	'View:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(201,	'Create:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(202,	'Update:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(203,	'Delete:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(204,	'Restore:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(205,	'ForceDelete:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(206,	'ForceDeleteAny:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(207,	'RestoreAny:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(208,	'Replicate:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(209,	'Reorder:Product',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(210,	'ViewAny:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(211,	'View:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(212,	'Create:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(213,	'Update:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(214,	'Delete:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(215,	'Restore:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(216,	'ForceDelete:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(217,	'ForceDeleteAny:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(218,	'RestoreAny:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(219,	'Replicate:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(220,	'Reorder:PurchaseInvoice',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(221,	'ViewAny:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(222,	'View:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(223,	'Create:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(224,	'Update:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(225,	'Delete:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(226,	'Restore:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(227,	'ForceDelete:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(228,	'ForceDeleteAny:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(229,	'RestoreAny:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(230,	'Replicate:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(231,	'Reorder:PurchaseOrder',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(232,	'ViewAny:PurchaseReturn',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(233,	'View:PurchaseReturn',	'web',	'2025-11-06 04:12:24',	'2025-11-06 04:12:24'),
(234,	'Create:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(235,	'Update:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(236,	'Delete:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(237,	'Restore:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(238,	'ForceDelete:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(239,	'ForceDeleteAny:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(240,	'RestoreAny:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(241,	'Replicate:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(242,	'Reorder:PurchaseReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(243,	'ViewAny:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(244,	'View:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(245,	'Create:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(246,	'Update:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(247,	'Delete:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(248,	'Restore:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(249,	'ForceDelete:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(250,	'ForceDeleteAny:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(251,	'RestoreAny:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(252,	'Replicate:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(253,	'Reorder:DeliveryDocument',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(254,	'ViewAny:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(255,	'View:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(256,	'Create:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(257,	'Update:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(258,	'Delete:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(259,	'Restore:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(260,	'ForceDelete:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(261,	'ForceDeleteAny:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(262,	'RestoreAny:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(263,	'Replicate:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(264,	'Reorder:SalesInvoice',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(265,	'ViewAny:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(266,	'View:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(267,	'Create:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(268,	'Update:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(269,	'Delete:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(270,	'Restore:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(271,	'ForceDelete:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(272,	'ForceDeleteAny:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(273,	'RestoreAny:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(274,	'Replicate:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(275,	'Reorder:SalesOrder',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(276,	'ViewAny:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(277,	'View:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(278,	'Create:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(279,	'Update:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(280,	'Delete:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(281,	'Restore:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(282,	'ForceDelete:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(283,	'ForceDeleteAny:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(284,	'RestoreAny:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(285,	'Replicate:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(286,	'Reorder:SalesReturn',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(287,	'ViewAny:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(288,	'View:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(289,	'Create:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(290,	'Update:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(291,	'Delete:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(292,	'Restore:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(293,	'ForceDelete:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(294,	'ForceDeleteAny:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(295,	'RestoreAny:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(296,	'Replicate:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(297,	'Reorder:Tax',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(298,	'ViewAny:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(299,	'View:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(300,	'Create:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(301,	'Update:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(302,	'Delete:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(303,	'Restore:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(304,	'ForceDelete:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(305,	'ForceDeleteAny:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(306,	'RestoreAny:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(307,	'Replicate:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(308,	'Reorder:Unit',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(309,	'ViewAny:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(310,	'View:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(311,	'Create:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(312,	'Update:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(313,	'Delete:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(314,	'Restore:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(315,	'ForceDelete:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(316,	'ForceDeleteAny:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(317,	'RestoreAny:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(318,	'Replicate:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(319,	'Reorder:User',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(320,	'ViewAny:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(321,	'View:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(322,	'Create:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(323,	'Update:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(324,	'Delete:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(325,	'Restore:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(326,	'ForceDelete:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(327,	'ForceDeleteAny:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(328,	'RestoreAny:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(329,	'Replicate:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(330,	'Reorder:Warehouse',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(331,	'ViewAny:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(332,	'View:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(333,	'Create:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(334,	'Update:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(335,	'Delete:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(336,	'Restore:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(337,	'ForceDelete:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(338,	'ForceDeleteAny:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(339,	'RestoreAny:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(340,	'Replicate:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(341,	'Reorder:Role',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(342,	'View:ManageDocumentNumbering',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(343,	'View:ManageAccounts',	'web',	'2025-11-06 04:12:25',	'2025-11-06 04:12:25'),
(344,	'View:Dashboard',	'web',	'2025-11-12 07:54:55',	'2025-11-12 07:54:55'),
(345,	'View:ManageOpeningBalances',	'web',	'2025-12-04 06:03:17',	'2025-12-04 06:03:17'),
(346,	'View:ManageReferenceNumbers',	'web',	'2025-12-04 06:03:17',	'2025-12-04 06:03:17');

DROP TABLE IF EXISTS "product_groups";
DROP SEQUENCE IF EXISTS product_groups_id_seq;
CREATE SEQUENCE product_groups_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."product_groups" (
    "id" bigint DEFAULT nextval('product_groups_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "shipping_type" character varying(255) NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    "code" character varying(50),
    CONSTRAINT "product_groups_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "product_groups_shipping_type_check" CHECK (((shipping_type)::text = ANY (ARRAY[('physical'::character varying)::text, ('digital'::character varying)::text])))
)
WITH (oids = false);

INSERT INTO "product_groups" ("id", "name", "is_active", "shipping_type", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at", "code") VALUES
(1,	'Electronics',	'1',	'physical',	NULL,	1,	'2025-12-02 10:04:16',	'2025-12-02 10:10:13',	'2025-12-02 10:10:13',	NULL),
(2,	'Software',	'1',	'digital',	NULL,	1,	'2025-12-02 10:04:16',	'2025-12-02 10:10:13',	'2025-12-02 10:10:13',	NULL),
(3,	'Books',	'1',	'physical',	NULL,	1,	'2025-12-02 10:04:16',	'2025-12-02 10:10:13',	'2025-12-02 10:10:13',	NULL),
(4,	'Online Courses',	'1',	'digital',	NULL,	1,	'2025-12-02 10:04:16',	'2025-12-02 10:10:13',	'2025-12-02 10:10:13',	NULL),
(5,	'Elektronik',	'1',	'physical',	NULL,	1,	'2025-12-02 10:12:09',	'2025-12-02 10:12:09',	NULL,	NULL),
(6,	'Perangkat Lunak',	'1',	'digital',	NULL,	1,	'2025-12-02 10:12:09',	'2025-12-17 08:26:28',	'2025-12-17 08:26:28',	NULL),
(7,	'Buku',	'1',	'physical',	NULL,	1,	'2025-12-02 10:12:09',	'2025-12-17 08:26:28',	'2025-12-17 08:26:28',	NULL),
(8,	'Kursus Daring',	'1',	'digital',	NULL,	1,	'2025-12-02 10:12:09',	'2025-12-17 08:26:28',	'2025-12-17 08:26:28',	NULL),
(10,	'Perangkat Lunak',	'1',	'digital',	1,	1,	'2025-12-17 08:29:44',	'2025-12-17 08:29:52',	'2025-12-17 08:29:52',	'PR2025000001'),
(11,	'Buku',	'1',	'physical',	1,	1,	'2025-12-17 08:29:44',	'2025-12-17 08:29:52',	'2025-12-17 08:29:52',	'PR2025000002'),
(12,	'Kursus Daring',	'1',	'digital',	1,	1,	'2025-12-17 08:29:44',	'2025-12-17 08:29:52',	'2025-12-17 08:29:52',	'PR2025000003'),
(13,	'Perangkat Lunak',	'1',	'digital',	1,	1,	'2025-12-17 08:30:03',	'2025-12-17 08:30:03',	NULL,	'PR2025000004'),
(14,	'Buku',	'1',	'physical',	1,	1,	'2025-12-17 08:30:03',	'2025-12-17 08:30:03',	NULL,	'PR2025000005'),
(15,	'Kursus Daring',	'1',	'digital',	1,	1,	'2025-12-17 08:30:03',	'2025-12-17 08:30:03',	NULL,	'PR2025000006');

DROP TABLE IF EXISTS "products";
DROP SEQUENCE IF EXISTS products_id_seq;
CREATE SEQUENCE products_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."products" (
    "id" bigint DEFAULT nextval('products_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "description" text,
    "cost_price" numeric(15,2) DEFAULT '0' NOT NULL,
    "selling_price" numeric(15,2) DEFAULT '0' NOT NULL,
    "reorder_level" numeric(15,2) DEFAULT '0' NOT NULL,
    "max_stock" numeric(15,2) DEFAULT '0' NOT NULL,
    "weight" numeric(10,3) DEFAULT '0' NOT NULL,
    "product_type" character varying(50) DEFAULT 'simple' NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "image" character varying(255),
    "unit_id" bigint NOT NULL,
    "product_group_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "products_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "products" ("id", "name", "code", "description", "cost_price", "selling_price", "reorder_level", "max_stock", "weight", "product_type", "is_active", "image", "unit_id", "product_group_id", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(8,	'Laptop Dell Inspiron',	'LP-001',	'Laptop Dell Inspiron 15 inch dengan prosesor Intel Core i5, RAM 8GB, SSD 256GB',	12000000.00,	15000000.00,	5.00,	50.00,	1.500,	'physical',	'1',	NULL,	9,	5,	NULL,	1,	'2025-12-03 04:48:57',	'2025-12-03 04:48:57',	NULL),
(9,	'Mouse Wireless Logitech',	'MO-001',	'Mouse wireless Logitech dengan sensor optik presisi tinggi',	150000.00,	200000.00,	20.00,	100.00,	0.100,	'physical',	'1',	NULL,	9,	5,	NULL,	1,	'2025-12-03 04:48:57',	'2025-12-03 04:48:57',	NULL),
(10,	'Software Akuntansi Premium',	'SA-001',	'Software akuntansi untuk usaha kecil dan menengah',	500000.00,	750000.00,	1.00,	10.00,	0.000,	'digital',	'1',	NULL,	1,	6,	NULL,	1,	'2025-12-03 04:48:57',	'2025-12-03 04:48:57',	NULL),
(12,	'Lorem',	'PROD000001',	NULL,	0.00,	0.00,	0.00,	0.00,	0.000,	'simple',	'1',	NULL,	1,	5,	NULL,	1,	'2025-12-03 08:36:46',	'2025-12-03 08:36:46',	NULL),
(14,	'test',	'PRD2025000001',	NULL,	0.00,	0.00,	0.00,	0.00,	0.000,	'simple',	'1',	NULL,	9,	13,	1,	1,	'2025-12-17 08:45:52',	'2025-12-17 08:45:52',	NULL),
(15,	'test',	'PRD2025000002',	NULL,	0.00,	0.00,	0.00,	0.00,	0.000,	'simple',	'1',	NULL,	9,	14,	4,	1,	'2025-12-17 09:03:44',	'2025-12-17 09:03:44',	NULL);

DROP TABLE IF EXISTS "projects";
DROP SEQUENCE IF EXISTS projects_id_seq;
CREATE SEQUENCE projects_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."projects" (
    "id" bigint DEFAULT nextval('projects_id_seq') NOT NULL,
    "job_number" character varying(50) NOT NULL,
    "status" character varying(255) DEFAULT 'planning' NOT NULL,
    "customer_po_number" character varying(100),
    "title" character varying(200) NOT NULL,
    "description" text,
    "total_value" numeric(15,2) DEFAULT '0' NOT NULL,
    "total_invoiced" numeric(15,2) DEFAULT '0' NOT NULL,
    "total_paid" numeric(15,2) DEFAULT '0' NOT NULL,
    "total_delivered" numeric(15,2) DEFAULT '0' NOT NULL,
    "customer_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "projects_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "projects_status_check" CHECK (((status)::text = ANY (ARRAY[('planning'::character varying)::text, ('in_progress'::character varying)::text, ('on_hold'::character varying)::text, ('completed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "purchase_invoice_items";
DROP SEQUENCE IF EXISTS purchase_invoice_items_id_seq;
CREATE SEQUENCE purchase_invoice_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."purchase_invoice_items" (
    "id" bigint DEFAULT nextval('purchase_invoice_items_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "unit_price" character varying(255) NOT NULL,
    "total" character varying(255) NOT NULL,
    "description" text,
    "purchase_invoice_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "tax_id" bigint NOT NULL,
    "cost_center_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "purchase_invoice_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "purchase_invoices";
DROP SEQUENCE IF EXISTS purchase_invoices_id_seq;
CREATE SEQUENCE purchase_invoices_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."purchase_invoices" (
    "id" bigint DEFAULT nextval('purchase_invoices_id_seq') NOT NULL,
    "date" date NOT NULL,
    "due_date" date NOT NULL,
    "is_paid" boolean DEFAULT false NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "other_charges" character varying(255) DEFAULT '0' NOT NULL,
    "discount" character varying(255) DEFAULT '0' NOT NULL,
    "advance_payment" character varying(255) DEFAULT '0' NOT NULL,
    "subtotal" character varying(255) DEFAULT '0' NOT NULL,
    "tax_amount" character varying(255) DEFAULT '0' NOT NULL,
    "total" character varying(255) DEFAULT '0' NOT NULL,
    "paid_amount" character varying(255) DEFAULT '0' NOT NULL,
    "outstanding_amount" character varying(255) DEFAULT '0' NOT NULL,
    "status" character varying(255) NOT NULL,
    "supplier_id" bigint NOT NULL,
    "purchase_order_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "other_charges_account_id" bigint NOT NULL,
    "discount_account_id" bigint NOT NULL,
    "advance_payment_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "purchase_invoices_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "purchase_invoices_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('received'::character varying)::text, ('approved'::character varying)::text, ('paid'::character varying)::text, ('partially_paid'::character varying)::text, ('disputed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "purchase_order_items";
DROP SEQUENCE IF EXISTS purchase_order_items_id_seq;
CREATE SEQUENCE purchase_order_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."purchase_order_items" (
    "id" bigint DEFAULT nextval('purchase_order_items_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "unit_price" character varying(255) NOT NULL,
    "total" character varying(255) NOT NULL,
    "description" text,
    "received_quantity" character varying(255) DEFAULT '0' NOT NULL,
    "purchase_order_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "tax_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "purchase_order_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "purchase_orders";
DROP SEQUENCE IF EXISTS purchase_orders_id_seq;
CREATE SEQUENCE purchase_orders_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."purchase_orders" (
    "id" bigint DEFAULT nextval('purchase_orders_id_seq') NOT NULL,
    "purchase_order_no" character varying(255) NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "other_charges" character varying(255) DEFAULT '0' NOT NULL,
    "discount" character varying(255) DEFAULT '0' NOT NULL,
    "subtotal" character varying(255) DEFAULT '0' NOT NULL,
    "tax_amount" character varying(255) DEFAULT '0' NOT NULL,
    "total" character varying(255) DEFAULT '0' NOT NULL,
    "status" character varying(255) NOT NULL,
    "supplier_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "department_id" bigint NOT NULL,
    "other_charges_account_id" bigint NOT NULL,
    "discount_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "purchase_orders_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "purchase_orders_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('sent'::character varying)::text, ('confirmed'::character varying)::text, ('partially_received'::character varying)::text, ('completed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "purchase_return_items";
DROP SEQUENCE IF EXISTS purchase_return_items_id_seq;
CREATE SEQUENCE purchase_return_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."purchase_return_items" (
    "id" bigint DEFAULT nextval('purchase_return_items_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "description" text,
    "return_reason" text NOT NULL,
    "purchase_return_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "purchase_return_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "purchase_returns";
DROP SEQUENCE IF EXISTS purchase_returns_id_seq;
CREATE SEQUENCE purchase_returns_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."purchase_returns" (
    "id" bigint DEFAULT nextval('purchase_returns_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "status" character varying(255) NOT NULL,
    "supplier_id" bigint NOT NULL,
    "purchase_invoice_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "purchase_returns_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "purchase_returns_status_check" CHECK (((status)::text = ANY (ARRAY[('requested'::character varying)::text, ('approved'::character varying)::text, ('shipped'::character varying)::text, ('received'::character varying)::text, ('processed'::character varying)::text, ('rejected'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "receivable_payment_items";
DROP SEQUENCE IF EXISTS receivable_payment_items_id_seq;
CREATE SEQUENCE receivable_payment_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."receivable_payment_items" (
    "id" bigint DEFAULT nextval('receivable_payment_items_id_seq') NOT NULL,
    "date" date NOT NULL,
    "amount" numeric(15,2) NOT NULL,
    "paid_amount" numeric(15,2) NOT NULL,
    "discount_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "write_off_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "set_payment" numeric(15,2) NOT NULL,
    "receivable_payment_id" bigint NOT NULL,
    "sales_invoice_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "receivable_payment_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "receivable_payments";
DROP SEQUENCE IF EXISTS receivable_payments_id_seq;
CREATE SEQUENCE receivable_payments_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."receivable_payments" (
    "id" bigint DEFAULT nextval('receivable_payments_id_seq') NOT NULL,
    "payment_number" character varying(50) NOT NULL,
    "payment_date" date NOT NULL,
    "reference_no" character varying(255),
    "description" text,
    "total_payment" numeric(15,2) NOT NULL,
    "payment_method" character varying(255) DEFAULT 'bank_transfer' NOT NULL,
    "status" character varying(255) DEFAULT 'pending' NOT NULL,
    "customer_id" bigint NOT NULL,
    "bank_account_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "receivable_payments_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "receivable_payments_payment_method_check" CHECK (((payment_method)::text = ANY (ARRAY[('cash'::character varying)::text, ('bank_transfer'::character varying)::text, ('check'::character varying)::text, ('credit_card'::character varying)::text, ('debit_card'::character varying)::text, ('online_payment'::character varying)::text, ('other'::character varying)::text]))),
    CONSTRAINT "receivable_payments_status_check" CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('completed'::character varying)::text, ('failed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "role_has_permissions";
CREATE TABLE "public"."role_has_permissions" (
    "permission_id" bigint NOT NULL,
    "role_id" bigint NOT NULL,
    CONSTRAINT "role_has_permissions_pkey" PRIMARY KEY ("permission_id", "role_id")
)
WITH (oids = false);

INSERT INTO "role_has_permissions" ("permission_id", "role_id") VALUES
(1,	1),
(2,	1),
(3,	1),
(4,	1),
(5,	1),
(6,	1),
(7,	1),
(8,	1),
(9,	1),
(10,	1),
(11,	1),
(12,	1),
(13,	1),
(14,	1),
(15,	1),
(16,	1),
(17,	1),
(18,	1),
(19,	1),
(20,	1),
(21,	1),
(22,	1),
(23,	1),
(24,	1),
(25,	1),
(26,	1),
(27,	1),
(28,	1),
(29,	1),
(30,	1),
(31,	1),
(32,	1),
(33,	1),
(34,	1),
(35,	1),
(36,	1),
(37,	1),
(38,	1),
(39,	1),
(40,	1),
(41,	1),
(42,	1),
(43,	1),
(44,	1),
(45,	1),
(46,	1),
(47,	1),
(48,	1),
(49,	1),
(50,	1),
(51,	1),
(52,	1),
(53,	1),
(54,	1),
(55,	1),
(56,	1),
(57,	1),
(58,	1),
(59,	1),
(60,	1),
(61,	1),
(62,	1),
(63,	1),
(64,	1),
(65,	1),
(66,	1),
(67,	1),
(68,	1),
(69,	1),
(70,	1),
(71,	1),
(72,	1),
(73,	1),
(74,	1),
(75,	1),
(76,	1),
(77,	1),
(78,	1),
(79,	1),
(80,	1),
(81,	1),
(82,	1),
(83,	1),
(84,	1),
(85,	1),
(86,	1),
(87,	1),
(88,	1),
(89,	1),
(90,	1),
(91,	1),
(92,	1),
(93,	1),
(94,	1),
(95,	1),
(96,	1),
(97,	1),
(98,	1),
(99,	1),
(100,	1),
(101,	1),
(102,	1),
(103,	1),
(104,	1),
(105,	1),
(106,	1),
(107,	1),
(108,	1),
(109,	1),
(110,	1),
(111,	1),
(112,	1),
(113,	1),
(114,	1),
(115,	1),
(116,	1),
(117,	1),
(118,	1),
(119,	1),
(120,	1),
(121,	1),
(122,	1),
(123,	1),
(124,	1),
(125,	1),
(126,	1),
(127,	1),
(128,	1),
(129,	1),
(130,	1),
(131,	1),
(132,	1),
(133,	1),
(134,	1),
(135,	1),
(136,	1),
(137,	1),
(138,	1),
(139,	1),
(140,	1),
(141,	1),
(142,	1),
(143,	1),
(144,	1),
(145,	1),
(146,	1),
(147,	1),
(148,	1),
(149,	1),
(150,	1),
(151,	1),
(152,	1),
(153,	1),
(154,	1),
(155,	1),
(156,	1),
(157,	1),
(158,	1),
(159,	1),
(160,	1),
(161,	1),
(162,	1),
(163,	1),
(164,	1),
(165,	1),
(166,	1),
(167,	1),
(168,	1),
(169,	1),
(170,	1),
(171,	1),
(172,	1),
(173,	1),
(174,	1),
(175,	1),
(176,	1),
(177,	1),
(178,	1),
(179,	1),
(180,	1),
(181,	1),
(182,	1),
(183,	1),
(184,	1),
(185,	1),
(186,	1),
(187,	1),
(188,	1),
(189,	1),
(190,	1),
(191,	1),
(192,	1),
(193,	1),
(194,	1),
(195,	1),
(196,	1),
(197,	1),
(198,	1),
(199,	1),
(200,	1),
(201,	1),
(202,	1),
(203,	1),
(204,	1),
(205,	1),
(206,	1),
(207,	1),
(208,	1),
(209,	1),
(210,	1),
(211,	1),
(212,	1),
(213,	1),
(214,	1),
(215,	1),
(216,	1),
(217,	1),
(218,	1),
(219,	1),
(220,	1),
(221,	1),
(222,	1),
(223,	1),
(224,	1),
(225,	1),
(226,	1),
(227,	1),
(228,	1),
(229,	1),
(230,	1),
(231,	1),
(232,	1),
(233,	1),
(234,	1),
(235,	1),
(236,	1),
(237,	1),
(238,	1),
(239,	1),
(240,	1),
(241,	1),
(242,	1),
(331,	1),
(332,	1),
(333,	1),
(334,	1),
(335,	1),
(336,	1),
(337,	1),
(338,	1),
(339,	1),
(340,	1),
(341,	1),
(243,	1),
(244,	1),
(245,	1),
(246,	1),
(247,	1),
(248,	1),
(249,	1),
(250,	1),
(251,	1),
(252,	1),
(253,	1),
(254,	1),
(255,	1),
(256,	1),
(257,	1),
(258,	1),
(259,	1),
(260,	1),
(261,	1),
(262,	1),
(263,	1),
(264,	1),
(265,	1),
(266,	1),
(267,	1),
(268,	1),
(269,	1),
(270,	1),
(271,	1),
(272,	1),
(273,	1),
(274,	1),
(275,	1),
(276,	1),
(277,	1),
(278,	1),
(279,	1),
(280,	1),
(281,	1),
(282,	1),
(283,	1),
(284,	1),
(285,	1),
(286,	1),
(287,	1),
(288,	1),
(289,	1),
(290,	1),
(291,	1),
(292,	1),
(293,	1),
(294,	1),
(295,	1),
(296,	1),
(297,	1),
(298,	1),
(299,	1),
(300,	1),
(301,	1),
(302,	1),
(303,	1),
(304,	1),
(305,	1),
(306,	1),
(307,	1),
(308,	1),
(309,	1),
(310,	1),
(311,	1),
(312,	1),
(313,	1),
(314,	1),
(315,	1),
(316,	1),
(317,	1),
(318,	1),
(319,	1),
(320,	1),
(321,	1),
(322,	1),
(323,	1),
(324,	1),
(325,	1),
(326,	1),
(327,	1),
(328,	1),
(329,	1),
(330,	1),
(344,	1),
(343,	1),
(345,	1),
(346,	1);

DROP TABLE IF EXISTS "roles";
DROP SEQUENCE IF EXISTS roles_id_seq;
CREATE SEQUENCE roles_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."roles" (
    "id" bigint DEFAULT nextval('roles_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "guard_name" character varying(255) NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "roles_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX roles_name_guard_name_unique ON public.roles USING btree (name, guard_name);

INSERT INTO "roles" ("id", "name", "guard_name", "created_at", "updated_at") VALUES
(1,	'super_admin',	'web',	'2025-11-06 04:12:23',	'2025-11-06 04:12:23');

DROP TABLE IF EXISTS "sales_invoice_items";
DROP SEQUENCE IF EXISTS sales_invoice_items_id_seq;
CREATE SEQUENCE sales_invoice_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."sales_invoice_items" (
    "id" bigint DEFAULT nextval('sales_invoice_items_id_seq') NOT NULL,
    "quantity" numeric(15,2) NOT NULL,
    "unit_price" numeric(15,2) NOT NULL,
    "total" numeric(15,2) NOT NULL,
    "description" text,
    "discount" numeric(15,2) DEFAULT '0' NOT NULL,
    "discount_percentage" numeric(5,2) DEFAULT '0' NOT NULL,
    "tax_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "sales_invoice_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "tax_id" bigint NOT NULL,
    "cost_center_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "sales_invoice_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "sales_invoices";
DROP SEQUENCE IF EXISTS sales_invoices_id_seq;
CREATE SEQUENCE sales_invoices_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."sales_invoices" (
    "id" bigint DEFAULT nextval('sales_invoices_id_seq') NOT NULL,
    "invoice_number" character varying(50) NOT NULL,
    "date" date NOT NULL,
    "due_date" date NOT NULL,
    "is_paid" boolean DEFAULT false NOT NULL,
    "reference_no" character varying(255),
    "description" text,
    "other_charges" numeric(15,2) DEFAULT '0' NOT NULL,
    "discount" numeric(15,2) DEFAULT '0' NOT NULL,
    "subtotal" numeric(15,2) DEFAULT '0' NOT NULL,
    "tax_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "total_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "paid_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "outstanding_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "is_advance_payment_invoice" boolean DEFAULT false NOT NULL,
    "is_settlement_invoice" boolean DEFAULT false NOT NULL,
    "status" character varying(255) DEFAULT 'draft' NOT NULL,
    "customer_id" bigint NOT NULL,
    "sales_order_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "other_charges_account_id" bigint NOT NULL,
    "discount_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "sales_invoices_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "sales_invoices_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('sent'::character varying)::text, ('overdue'::character varying)::text, ('paid'::character varying)::text, ('partially_paid'::character varying)::text, ('written_off'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "sales_order_items";
DROP SEQUENCE IF EXISTS sales_order_items_id_seq;
CREATE SEQUENCE sales_order_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."sales_order_items" (
    "id" bigint DEFAULT nextval('sales_order_items_id_seq') NOT NULL,
    "quantity" numeric(15,2) NOT NULL,
    "unit_price" numeric(15,2) NOT NULL,
    "total" numeric(15,2) NOT NULL,
    "description" text,
    "discount" numeric(15,2) DEFAULT '0' NOT NULL,
    "discount_percentage" numeric(5,2) DEFAULT '0' NOT NULL,
    "tax_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "delivered_quantity" numeric(15,2) DEFAULT '0' NOT NULL,
    "sales_order_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "tax_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "sales_order_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "sales_orders";
DROP SEQUENCE IF EXISTS sales_orders_id_seq;
CREATE SEQUENCE sales_orders_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."sales_orders" (
    "id" bigint DEFAULT nextval('sales_orders_id_seq') NOT NULL,
    "order_number" character varying(50) NOT NULL,
    "order_type" character varying(255) DEFAULT 'standard' NOT NULL,
    "date" date NOT NULL,
    "is_closed" boolean DEFAULT false NOT NULL,
    "reference_no" character varying(255),
    "description" text,
    "other_charges" numeric(15,2) DEFAULT '0' NOT NULL,
    "discount" numeric(15,2) DEFAULT '0' NOT NULL,
    "subtotal" numeric(15,2) DEFAULT '0' NOT NULL,
    "tax_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "total_amount" numeric(15,2) DEFAULT '0' NOT NULL,
    "status" character varying(255) DEFAULT 'draft' NOT NULL,
    "job_id" bigint NOT NULL,
    "customer_id" bigint NOT NULL,
    "advance_payment_id" bigint,
    "other_charges_account_id" bigint,
    "discount_account_id" bigint,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "sales_orders_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "sales_orders_order_type_check" CHECK (((order_type)::text = ANY (ARRAY[('standard'::character varying)::text, ('cash'::character varying)::text, ('credit'::character varying)::text, ('consignment'::character varying)::text, ('service'::character varying)::text]))),
    CONSTRAINT "sales_orders_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('confirmed'::character varying)::text, ('partially_delivered'::character varying)::text, ('completed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "sales_return_items";
DROP SEQUENCE IF EXISTS sales_return_items_id_seq;
CREATE SEQUENCE sales_return_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."sales_return_items" (
    "id" bigint DEFAULT nextval('sales_return_items_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "description" text,
    "return_reason" text NOT NULL,
    "sales_return_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "sales_return_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "sales_returns";
DROP SEQUENCE IF EXISTS sales_returns_id_seq;
CREATE SEQUENCE sales_returns_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."sales_returns" (
    "id" bigint DEFAULT nextval('sales_returns_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "status" character varying(255) NOT NULL,
    "customer_id" bigint NOT NULL,
    "sales_invoice_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "sales_returns_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "sales_returns_status_check" CHECK (((status)::text = ANY (ARRAY[('requested'::character varying)::text, ('approved'::character varying)::text, ('received'::character varying)::text, ('processed'::character varying)::text, ('rejected'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "sessions";
CREATE TABLE "public"."sessions" (
    "id" character varying(255) NOT NULL,
    "user_id" bigint,
    "ip_address" character varying(45),
    "user_agent" text,
    "payload" text NOT NULL,
    "last_activity" integer NOT NULL,
    CONSTRAINT "sessions_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);

INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES
('qS2wqkyu7aUKkAxPSXTcNyZZvNw0cTxw6FZsP02u',	1,	'127.0.0.1',	'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',	'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiNjFZcUpuSGxRZFNMVVdqU1BnaUhuQURGMzI5c21reDZOZWpVNENWSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYWluL2J1c2luZXNzLXR5cGVzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJHJXSzYuQjAzSW55b0VTZ3F1STUwVi43Y05KNVl0OWg4dThDUmJtZkFtWlNSMzkxT2VoYkNTIjtzOjE5OiJzZWxlY3RlZF9jb21wYW55X2lkIjtzOjE6IjEiO3M6NjoidGFibGVzIjthOjg6e3M6NDA6IjczMzUwOWU4MGY1ZGQyMTM1NTdjODViMTk2ZDBiODY4X2NvbHVtbnMiO2E6Njp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoiY29kZSI7czo1OiJsYWJlbCI7czo0OiJLb2RlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6NToiQWt0aWYiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE4OiJjcmVhdGVkQnlVc2VyLm5hbWUiO3M6NToibGFiZWwiO3M6MTE6IkRpYnVhdCBPbGVoIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjExOiJEaWJ1YXQgUGFkYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxNToiRGlwZXJiYXJ1aSBQYWRhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6ImE1MDU1OGFkMzZlNmE3NzkxMjRjNTc2MDQ3OWY5MzdmX2NvbHVtbnMiO2E6Nzp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoiY29kZSI7czo1OiJsYWJlbCI7czo0OiJLb2RlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoiY29tcGFueS5uYW1lIjtzOjU6ImxhYmVsIjtzOjEwOiJQZXJ1c2FoYWFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6NToiQWt0aWYiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE4OiJjcmVhdGVkQnlVc2VyLm5hbWUiO3M6NToibGFiZWwiO3M6MTE6IkRpYnVhdCBPbGVoIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjExOiJEaWJ1YXQgUGFkYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxNToiRGlwZXJiYXJ1aSBQYWRhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6IjdjMzkzZGExODA0YThjOGRiNzE0MmRkN2MwNTIyODE0X2NvbHVtbnMiO2E6MTA6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjEzOiJOYW1hIEthdGVnb3JpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxOToiZGVwcmVjaWF0aW9uX21ldGhvZCI7czo1OiJsYWJlbCI7czoxNzoiTWV0b2RlIFBlbnl1c3V0YW4iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJ1c2VmdWxfbGlmZSI7czo1OiJsYWJlbCI7czoxMjoiTWFzYSBNYW5mYWF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoiY29tcGFueS5uYW1lIjtzOjU6ImxhYmVsIjtzOjEwOiJQZXJ1c2FoYWFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6NToiQWt0aWYiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE3OiJhc3NldEFjY291bnQubmFtZSI7czo1OiJsYWJlbCI7czo5OiJBa3VuIEFzZXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MjQ6ImRlcHJlY2lhdGlvbkFjY291bnQubmFtZSI7czo1OiJsYWJlbCI7czoxNToiQWt1biBQZW55dXN1dGFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE4OiJjcmVhdGVkQnlVc2VyLm5hbWUiO3M6NToibGFiZWwiO3M6MTE6IkRpYnVhdCBPbGVoIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo4O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjExOiJEaWJ1YXQgUGFkYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6OTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxNToiRGlwZXJiYXJ1aSBQYWRhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6ImFiYzBlZTJlMzc2MjA2ZTZlMWNlZmU5NmZkMGQxMGQ5X2NvbHVtbnMiO2E6ODp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6MjA6Ik5hbWEgS2Vsb21wb2sgUHJvZHVrIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJjb2RlIjtzOjU6ImxhYmVsIjtzOjQ6IktvZGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJjb21wYW55Lm5hbWUiO3M6NToibGFiZWwiO3M6MTA6IlBlcnVzYWhhYW4iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6ImlzX2FjdGl2ZSI7czo1OiJsYWJlbCI7czo1OiJBa3RpZiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6InNoaXBwaW5nX3R5cGUiO3M6NToibGFiZWwiO3M6MTI6IkplbmlzIFByb2R1ayI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxODoiY3JlYXRlZEJ5VXNlci5uYW1lIjtzOjU6ImxhYmVsIjtzOjExOiJEaWJ1YXQgT2xlaCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMToiRGlidWF0IFBhZGEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTU6IkRpcGVyYmFydWkgUGFkYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiI4YTUyYTAxMjNkZDQwYjc5MGJiNTQzMmUwNjRhMTI3Nl9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6ImNvZGUiO3M6NToibGFiZWwiO3M6NDoiS29kZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTI6ImNvbXBhbnkubmFtZSI7czo1OiJsYWJlbCI7czoxMDoiUGVydXNhaGFhbiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjU6IkFrdGlmIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxODoiY3JlYXRlZEJ5VXNlci5uYW1lIjtzOjU6ImxhYmVsIjtzOjExOiJEaWJ1YXQgT2xlaCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMToiRGlidWF0IFBhZGEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTU6IkRpcGVyYmFydWkgUGFkYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiI4ZmFjNmViMWNlYzI2ODAzYjNmN2ZiNDQwYTI3MTExYl9jb2x1bW5zIjthOjE0OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiaW1hZ2UiO3M6NToibGFiZWwiO3M6NToiSW1hZ2UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoiY29kZSI7czo1OiJsYWJlbCI7czoxMToiS29kZSBQcm9kdWsiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE3OiJwcm9kdWN0R3JvdXAubmFtZSI7czo1OiJsYWJlbCI7czo4OiJLZWxvbXBvayI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToidW5pdC5uYW1lIjtzOjU6ImxhYmVsIjtzOjQ6IlVuaXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJzZWxsaW5nX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjU6IkhhcmdhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoiY29tcGFueS5uYW1lIjtzOjU6ImxhYmVsIjtzOjEwOiJQZXJ1c2FoYWFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6NToiQWt0aWYiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo4O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjb3N0X3ByaWNlIjtzOjU6ImxhYmVsIjtzOjU6IkJpYXlhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo5O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJyZW9yZGVyX2xldmVsIjtzOjU6ImxhYmVsIjtzOjExOiJQZXNhbiBVbGFuZyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6MTA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToibWF4X3N0b2NrIjtzOjU6ImxhYmVsIjtzOjEzOiJTdG9rIE1ha3NpbWFsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aToxMTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxODoiY3JlYXRlZEJ5VXNlci5uYW1lIjtzOjU6ImxhYmVsIjtzOjExOiJEaWJ1YXQgT2xlaCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6MTI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTE6IkRpYnVhdCBQYWRhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aToxMzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxNToiRGlwZXJiYXJ1aSBQYWRhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6ImMxZTY5NGEyNTJiZDFiODBlYTU5OTU1YzEzZTZmZjVjX2NvbHVtbnMiO2E6MTI6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoib3JkZXJfbnVtYmVyIjtzOjU6ImxhYmVsIjtzOjk6IlBlc2FuYW4gIyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoiZGF0ZSI7czo1OiJsYWJlbCI7czoxNToiVGFuZ2dhbCBQZXNhbmFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoiY3VzdG9tZXIubmFtZSI7czo1OiJsYWJlbCI7czo5OiJQZWxhbmdnYW4iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJvcmRlcl90eXBlIjtzOjU6ImxhYmVsIjtzOjEzOiJKZW5pcyBQZXNhbmFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoidG90YWxfYW1vdW50IjtzOjU6ImxhYmVsIjtzOjU6IlRvdGFsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19jbG9zZWQiO3M6NToibGFiZWwiO3M6NzoiRGl0dXR1cCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTI6ImNvbXBhbnkubmFtZSI7czo1OiJsYWJlbCI7czoxMDoiUGVydXNhaGFhbiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6ODthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxODoiY3JlYXRlZEJ5VXNlci5uYW1lIjtzOjU6ImxhYmVsIjtzOjExOiJEaWJ1YXQgT2xlaCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6OTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMToiRGlidWF0IFBhZGEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjEwO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjE1OiJEaXBlcmJhcnVpIFBhZGEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjExO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJkZWxldGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEyOiJEaWhhcHVzIFBhZGEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiNGM1YjVjZDQwZDg1MmJhZWI3YTZjN2M4ZWYwYjU4YWRfY29sdW1ucyI7YToxNTp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6MTA6IkFzc2V0IE5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6ImNvZGUiO3M6NToibGFiZWwiO3M6OToiS29kZSBBc2V0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoiY2F0ZWdvcnkubmFtZSI7czo1OiJsYWJlbCI7czo4OiJLZWxvbXBvayI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImJvb2tfdmFsdWUiO3M6NToibGFiZWwiO3M6MTA6Ik5pbGFpIEJ1a3UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJjb21wYW55Lm5hbWUiO3M6NToibGFiZWwiO3M6MTA6IlBlcnVzYWhhYW4iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6ImlzX2FjdGl2ZSI7czo1OiJsYWJlbCI7czo1OiJBa3RpZiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6ODoibG9jYXRpb24iO3M6NToibGFiZWwiO3M6NjoiTG9rYXNpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE2OiJhY3F1aXNpdGlvbl9kYXRlIjtzOjU6ImxhYmVsIjtzOjk6IkRpcGVyb2xlaCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6ODthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNzoiYWNxdWlzaXRpb25fdmFsdWUiO3M6NToibGFiZWwiO3M6MTU6Ik5pbGFpIFBlcm9sZWhhbiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6OTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNToiZGVwYXJ0bWVudC5uYW1lIjtzOjU6ImxhYmVsIjtzOjEwOiJEZXBhcnRlbWVuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aToxMDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxOToiZGVwcmVjaWF0aW9uX21ldGhvZCI7czo1OiJsYWJlbCI7czoxOToiRGVwcmVjaWF0aW9uIE1ldGhvZCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6MTE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6InVzZWZ1bF9saWZlIjtzOjU6ImxhYmVsIjtzOjEyOiJNYXNhIE1hbmZhYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjEyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE4OiJjcmVhdGVkQnlVc2VyLm5hbWUiO3M6NToibGFiZWwiO3M6MTE6IkRpYnVhdCBPbGVoIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aToxMzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMToiRGlidWF0IFBhZGEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjE0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjE1OiJEaXBlcmJhcnVpIFBhZGEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319fX0=',	1765967199);

DROP TABLE IF EXISTS "stock_opname_items";
DROP SEQUENCE IF EXISTS stock_opname_items_id_seq;
CREATE SEQUENCE stock_opname_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."stock_opname_items" (
    "id" bigint DEFAULT nextval('stock_opname_items_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "book_stock" character varying(255) NOT NULL,
    "physical_stock" character varying(255) NOT NULL,
    "difference" character varying(255) NOT NULL,
    "description" text,
    "stock_opname_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "stock_opname_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "stock_opnames";
DROP SEQUENCE IF EXISTS stock_opnames_id_seq;
CREATE SEQUENCE stock_opnames_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."stock_opnames" (
    "id" bigint DEFAULT nextval('stock_opnames_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "status" character varying(255) NOT NULL,
    "warehouse_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "stock_opnames_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "stock_opnames_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('in_progress'::character varying)::text, ('completed'::character varying)::text, ('approved'::character varying)::text, ('rejected'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "task_updates";
DROP SEQUENCE IF EXISTS task_updates_id_seq;
CREATE SEQUENCE task_updates_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."task_updates" (
    "id" bigint DEFAULT nextval('task_updates_id_seq') NOT NULL,
    "update_text" text NOT NULL,
    "task_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "task_updates_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "task_user";
CREATE TABLE "public"."task_user" (
    "task_id" bigint NOT NULL,
    "user_id" bigint NOT NULL
)
WITH (oids = false);


DROP TABLE IF EXISTS "tasks";
DROP SEQUENCE IF EXISTS tasks_id_seq;
CREATE SEQUENCE tasks_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."tasks" (
    "id" bigint DEFAULT nextval('tasks_id_seq') NOT NULL,
    "task_number" character varying(50) NOT NULL,
    "task_type" character varying(255) NOT NULL,
    "title" character varying(200) NOT NULL,
    "description" text,
    "due_date" date,
    "status" character varying(255) NOT NULL,
    "completed_at" timestamp(0),
    "assigned_to_id" bigint NOT NULL,
    "job_id" bigint NOT NULL,
    "company_id" bigint,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "tasks_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "tasks_status_check" CHECK (((status)::text = ANY (ARRAY[('todo'::character varying)::text, ('in_progress'::character varying)::text, ('review'::character varying)::text, ('completed'::character varying)::text, ('cancelled'::character varying)::text]))),
    CONSTRAINT "tasks_task_type_check" CHECK (((task_type)::text = ANY (ARRAY[('milestone'::character varying)::text, ('deliverable'::character varying)::text, ('issue'::character varying)::text, ('bug_fix'::character varying)::text, ('feature'::character varying)::text, ('review'::character varying)::text])))
)
WITH (oids = false);

CREATE UNIQUE INDEX tasks_task_number_unique ON public.tasks USING btree (task_number);


DROP TABLE IF EXISTS "tax_templates";
DROP SEQUENCE IF EXISTS tax_templates_id_seq;
CREATE SEQUENCE tax_templates_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."tax_templates" (
    "id" bigint DEFAULT nextval('tax_templates_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "code" character varying(255) NOT NULL,
    "tax_percentage" numeric(8,2) NOT NULL,
    "tax_type" character varying(255) NOT NULL,
    "is_purchase_tax" boolean DEFAULT true NOT NULL,
    "is_sales_tax" boolean DEFAULT true NOT NULL,
    "effective_date" date NOT NULL,
    "expiry_date" date,
    "compound_tax" boolean DEFAULT false NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "purchase_account_code" character varying(255),
    "sales_account_code" character varying(255),
    "template_name" character varying(255) NOT NULL,
    "notes" text,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "tax_templates_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE INDEX tax_templates_template_name_code_index ON public.tax_templates USING btree (template_name, code);

CREATE INDEX tax_templates_template_name_name_index ON public.tax_templates USING btree (template_name, name);

INSERT INTO "tax_templates" ("id", "name", "code", "tax_percentage", "tax_type", "is_purchase_tax", "is_sales_tax", "effective_date", "expiry_date", "compound_tax", "is_active", "purchase_account_code", "sales_account_code", "template_name", "notes", "created_at", "updated_at") VALUES
(1,	'No Tax',	'.',	0.00,	'vat',	'1',	'1',	'2025-11-18',	NULL,	'0',	'1',	'116200006',	'213000006',	'Standard Indonesian Taxes',	'Standard Indonesian Tax template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(2,	'Pajak Pertambahan Nilai',	'PPN',	11.00,	'vat',	'1',	'1',	'2025-11-18',	NULL,	'0',	'1',	'116200010',	'213000010',	'Standard Indonesian Taxes',	'Standard Indonesian Tax template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(3,	'PPh Pasal 23 Non NPWP',	'PPh 23-4',	4.00,	'withholding_tax',	'1',	'1',	'2025-11-18',	NULL,	'0',	'1',	'213000009',	'116200009',	'Standard Indonesian Taxes',	'Standard Indonesian Tax template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(4,	'PPh Pasal 23 NPWP',	'PPh 23-2',	2.00,	'withholding_tax',	'1',	'1',	'2025-11-18',	NULL,	'0',	'1',	'213000007',	'116200007',	'Standard Indonesian Taxes',	'Standard Indonesian Tax template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(5,	'PPh Pasal 4 Ayat 2',	'PPh 4.2',	1.00,	'withholding_tax',	'1',	'1',	'2025-11-18',	NULL,	'0',	'1',	'213000008',	'116200008',	'Standard Indonesian Taxes',	'Standard Indonesian Tax template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23'),
(6,	'PPnBM 20%',	'ppnbm20',	20.00,	'excise_tax',	'1',	'1',	'2025-11-18',	NULL,	'0',	'1',	'116200011',	'213000011',	'Standard Indonesian Taxes',	'Standard Indonesian Tax template',	'2025-11-18 10:47:23',	'2025-11-18 10:47:23');

DROP TABLE IF EXISTS "taxes";
DROP SEQUENCE IF EXISTS taxes_id_seq;
CREATE SEQUENCE taxes_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."taxes" (
    "id" bigint DEFAULT nextval('taxes_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "tax_percentage" numeric(10,2) NOT NULL,
    "tax_type" character varying(255),
    "is_purchase_tax" boolean DEFAULT false NOT NULL,
    "is_sales_tax" boolean DEFAULT false NOT NULL,
    "effective_date" date,
    "expiry_date" date,
    "compound_tax" boolean DEFAULT false NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "purchase_account_id" bigint,
    "sales_account_id" bigint,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "taxes_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "taxes_tax_type_check" CHECK (((tax_type)::text = ANY (ARRAY[('vat'::character varying)::text, ('sales_tax'::character varying)::text, ('service_tax'::character varying)::text, ('withholding_tax'::character varying)::text, ('excise_tax'::character varying)::text])))
)
WITH (oids = false);

INSERT INTO "taxes" ("id", "name", "code", "tax_percentage", "tax_type", "is_purchase_tax", "is_sales_tax", "effective_date", "expiry_date", "compound_tax", "is_active", "purchase_account_id", "sales_account_id", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(1,	'No Tax',	'.',	0.00,	'vat',	'1',	'1',	'2025-11-17',	NULL,	'0',	'1',	219,	264,	1,	1,	'2025-11-17 08:58:12',	'2025-11-17 08:58:12',	NULL),
(2,	'Pajak Pertambahan Nilai',	'PPN',	11.00,	'vat',	'1',	'1',	'2025-11-17',	NULL,	'0',	'1',	223,	268,	1,	1,	'2025-11-17 08:58:12',	'2025-11-17 08:58:12',	NULL),
(3,	'PPh Pasal 23 Non NPWP',	'PPh 23-4',	4.00,	'withholding_tax',	'1',	'1',	'2025-11-17',	NULL,	'0',	'1',	267,	222,	1,	1,	'2025-11-17 08:58:12',	'2025-11-17 08:58:12',	NULL),
(4,	'PPh Pasal 23 NPWP',	'PPh 23-2',	2.00,	'withholding_tax',	'1',	'1',	'2025-11-17',	NULL,	'0',	'1',	265,	220,	1,	1,	'2025-11-17 08:58:12',	'2025-11-17 08:58:12',	NULL),
(5,	'PPh Pasal 4 Ayat 2',	'PPh 4.2',	1.00,	'withholding_tax',	'1',	'1',	'2025-11-17',	NULL,	'0',	'1',	266,	221,	1,	1,	'2025-11-17 08:58:12',	'2025-11-17 08:58:12',	NULL),
(6,	'PPnBM 20%',	'ppnbm20',	20.00,	'excise_tax',	'1',	'1',	'2025-11-17',	NULL,	'0',	'1',	224,	269,	1,	1,	'2025-11-17 08:58:12',	'2025-11-17 08:58:12',	NULL);

DROP TABLE IF EXISTS "transaction_classifications";
DROP SEQUENCE IF EXISTS transaction_classifications_id_seq;
CREATE SEQUENCE transaction_classifications_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."transaction_classifications" (
    "id" bigint DEFAULT nextval('transaction_classifications_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "description" text,
    "classification_type" character varying(255) NOT NULL,
    "tax_impact" character varying(255),
    "reporting_category" character varying(100),
    "is_active" boolean DEFAULT true NOT NULL,
    "default_account_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "transaction_classifications_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "transaction_classifications_classification_type_check" CHECK (((classification_type)::text = ANY (ARRAY[('operating'::character varying)::text, ('investing'::character varying)::text, ('financing'::character varying)::text, ('non_operating'::character varying)::text]))),
    CONSTRAINT "transaction_classifications_tax_impact_check" CHECK (((tax_impact)::text = ANY (ARRAY[('taxable'::character varying)::text, ('exempt'::character varying)::text, ('zero_rated'::character varying)::text, ('out_of_scope'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "units";
DROP SEQUENCE IF EXISTS units_id_seq;
CREATE SEQUENCE units_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."units" (
    "id" bigint DEFAULT nextval('units_id_seq') NOT NULL,
    "code" character varying(20) NOT NULL,
    "name" character varying(100) NOT NULL,
    "description" text,
    "is_active" boolean DEFAULT true NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "units_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "units" ("id", "code", "name", "description", "is_active", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(1,	'Box',	'Box',	'Box',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(2,	'Cup',	'Cup',	'Cup',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(3,	'Dzn',	'Dozen',	'Dozen',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(4,	'Gr',	'Gram',	'Gram',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(5,	'Gross',	'Gross',	'Gross',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(6,	'Hour',	'Hour',	'Hour',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(7,	'Kg',	'Kg',	'Kilogram',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(8,	'Pack',	'Pack',	'Pack',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(9,	'Pcs',	'Pcs',	'Pieces',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(10,	'Score',	'Score',	'Score',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL),
(11,	'Ton',	'Ton',	'Ton',	'1',	NULL,	1,	'2025-11-17 08:58:09',	'2025-11-17 08:58:09',	NULL);

DROP TABLE IF EXISTS "user_companies";
DROP SEQUENCE IF EXISTS user_companies_id_seq;
CREATE SEQUENCE user_companies_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."user_companies" (
    "id" bigint DEFAULT nextval('user_companies_id_seq') NOT NULL,
    "user_id" bigint NOT NULL,
    "company_id" bigint NOT NULL,
    "assigned_by_user_id" bigint,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "user_companies_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "user_companies" ("id", "user_id", "company_id", "assigned_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(1,	1,	1,	1,	'2025-11-06 04:40:04',	'2025-11-06 04:44:51',	NULL),
(5,	1,	4,	NULL,	'2025-11-06 09:30:47',	'2025-11-06 09:30:47',	NULL);

DROP TABLE IF EXISTS "users";
DROP SEQUENCE IF EXISTS users_id_seq;
CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."users" (
    "id" bigint DEFAULT nextval('users_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "email" character varying(255) NOT NULL,
    "email_verified_at" timestamp(0),
    "password" character varying(255) NOT NULL,
    "remember_token" character varying(100),
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "users_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX users_email_unique ON public.users USING btree (email);

INSERT INTO "users" ("id", "name", "email", "email_verified_at", "password", "remember_token", "created_at", "updated_at") VALUES
(3,	'Test User',	'test@example.com',	'2025-11-17 08:55:39',	'$2y$12$N5Em4tQYzuXY9vdmBeUE.OPbcpYmNVA9FBfyzRHU1ZPn51.HJm0Ui',	'hAFyLd25PV',	'2025-11-17 08:55:40',	'2025-11-17 08:55:40'),
(1,	'Admin',	'dcsadmin@yopmail.com',	'2025-10-27 08:04:23',	'$2y$12$rWK6.B03InyoESgquI50V.7cNJ5Yt9h8u8CRbmfAmZSR391OehbCS',	'pkaZgMdqFEwTFMsoJYGyYzbWxXQNAHSQqibTs0nKWLk6beTzi5Y1y54lxWcT',	'2025-10-27 08:04:23',	'2025-12-10 10:46:25');

DROP TABLE IF EXISTS "warehouse_transfer_items";
DROP SEQUENCE IF EXISTS warehouse_transfer_items_id_seq;
CREATE SEQUENCE warehouse_transfer_items_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."warehouse_transfer_items" (
    "id" bigint DEFAULT nextval('warehouse_transfer_items_id_seq') NOT NULL,
    "quantity" character varying(255) NOT NULL,
    "description" text,
    "warehouse_transfer_id" bigint NOT NULL,
    "product_id" bigint NOT NULL,
    "unit_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "warehouse_transfer_items_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);


DROP TABLE IF EXISTS "warehouse_transfers";
DROP SEQUENCE IF EXISTS warehouse_transfers_id_seq;
CREATE SEQUENCE warehouse_transfers_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."warehouse_transfers" (
    "id" bigint DEFAULT nextval('warehouse_transfers_id_seq') NOT NULL,
    "date" date NOT NULL,
    "reference_no" character varying(255) NOT NULL,
    "description" text,
    "status" character varying(255) NOT NULL,
    "from_warehouse_id" bigint NOT NULL,
    "to_warehouse_id" bigint NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "updated_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "warehouse_transfers_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "warehouse_transfers_status_check" CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('processed'::character varying)::text, ('cancelled'::character varying)::text])))
)
WITH (oids = false);


DROP TABLE IF EXISTS "warehouses";
DROP SEQUENCE IF EXISTS warehouses_id_seq;
CREATE SEQUENCE warehouses_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."warehouses" (
    "id" bigint DEFAULT nextval('warehouses_id_seq') NOT NULL,
    "name" character varying(200) NOT NULL,
    "code" character varying(50) NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "company_id" bigint,
    "created_by_user_id" bigint NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "deleted_at" timestamp(0),
    CONSTRAINT "warehouses_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "warehouses" ("id", "name", "code", "is_active", "company_id", "created_by_user_id", "created_at", "updated_at", "deleted_at") VALUES
(2,	'test',	'WH2025000001',	'1',	NULL,	1,	'2025-12-17 09:48:25',	'2025-12-17 09:48:25',	NULL);

ALTER TABLE ONLY "public"."accounts" ADD CONSTRAINT "accounts_classification_id_foreign" FOREIGN KEY (classification_id) REFERENCES accounts(id) ON DELETE SET NULL NOT DEFERRABLE;

ALTER TABLE ONLY "public"."model_has_permissions" ADD CONSTRAINT "model_has_permissions_permission_id_foreign" FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE NOT DEFERRABLE;

-- 2025-12-17 10:26:58 UTC
