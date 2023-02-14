CREATE TABLE `countries` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(128) NOT NULL,
	`countrycode` varchar(3) NOT NULL,
	`code` varchar(2) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 ROW_FORMAT = DYNAMIC;
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Afghanistan', 'AFG', 'AF'),
	('Albania', 'ALB', 'AL'),
	('Algeria', 'DZA', 'DZ'),
	('American Samoa', 'ASM', 'AS'),
	('Andorra', 'AND', 'AD'),
	('Angola', 'AGO', 'AO'),
	('Anguilla', 'AIA', 'AI'),
	('Antarctica', 'ATA', 'AQ'),
	('Antigua and Barbuda', 'ATG', 'AG'),
	('Argentina', 'ARG', 'AR');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Armenia', 'ARM', 'AM'),
	('Aruba', 'ABW', 'AW'),
	('Australia', 'AUS', 'AU'),
	('Austria', 'AUT', 'AT'),
	('Azerbaijan', 'AZE', 'AZ'),
	('Bahamas', 'BHS', 'BS'),
	('Bahrain', 'BHR', 'BH'),
	('Bangladesh', 'BGD', 'BD'),
	('Barbados', 'BRB', 'BB'),
	('Belarus', 'BLR', 'BY');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Belgium', 'BEL', 'BE'),
	('Belize', 'BLZ', 'BZ'),
	('Benin', 'BEN', 'BJ'),
	('Bermuda', 'BMU', 'BM'),
	('Bhutan', 'BTN', 'BT'),
	('Bolivia', 'BOL', 'BO'),
	('Bosnia and Herzegovina', 'BIH', 'BA'),
	('Botswana', 'BWA', 'BW'),
	('Bouvet Island', 'BVT', 'BV'),
	('Brazil', 'BRA', 'BR');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('British Indian Ocean Territory', 'IOT', 'IO'),
	('Brunei Darussalam', 'BRN', 'BN'),
	('Bulgaria', 'BGR', 'BG'),
	('Burkina Faso', 'BFA', 'BF'),
	('Burundi', 'BDI', 'BI'),
	('Cambodia', 'KHM', 'KH'),
	('Cameroon', 'CMR', 'CM'),
	('Canada', 'CAN', 'CA'),
	('Cape Verde', 'CPV', 'CV'),
	('Cayman Islands', 'CYM', 'KY');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Central African Republic', 'CAF', 'CF'),
	('Chad', 'TCD', 'TD'),
	('Chile', 'CHL', 'CL'),
	('China', 'CHN', 'CN'),
	('Christmas Island', 'CXR', 'CX'),
	('Cocos (Keeling) Islands', 'CCK', 'CC'),
	('Colombia', 'COL', 'CO'),
	('Comoros', 'COM', 'KM'),
	('Congo', 'COG', 'CG'),
	('Cook Islands', 'COK', 'CK');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Costa Rica', 'CRI', 'CR'),
	('Cote D''Ivoire', 'CIV', 'CI'),
	('Croatia', 'HRV', 'HR'),
	('Cuba', 'CUB', 'CU'),
	('Cyprus', 'CYP', 'CY'),
	('Czech Republic', 'CZE', 'CZ'),
	('Denmark', 'DNK', 'DK'),
	('Djibouti', 'DJI', 'DJ'),
	('Dominica', 'DMA', 'DM'),
	('Dominican Republic', 'DOM', 'DO');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('East Timor', 'TLS', 'TL'),
	('Ecuador', 'ECU', 'EC'),
	('Egypt', 'EGY', 'EG'),
	('El Salvador', 'SLV', 'SV'),
	('Equatorial Guinea', 'GNQ', 'GQ'),
	('Eritrea', 'ERI', 'ER'),
	('Estonia', 'EST', 'EE'),
	('Ethiopia', 'ETH', 'ET'),
	('Falkland Islands (Malvinas)', 'FLK', 'FK'),
	('Faroe Islands', 'FRO', 'FO');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Fiji', 'FJI', 'FJ'),
	('Finland', 'FIN', 'FI'),
	('France, Metropolitan', 'FRA', 'FR'),
	('French Guiana', 'GUF', 'GF'),
	('French Polynesia', 'PYF', 'PF'),
	('French Southern Territories', 'ATF', 'TF'),
	('Gabon', 'GAB', 'GA'),
	('Gambia', 'GMB', 'GM'),
	('Georgia', 'GEO', 'GE'),
	('Germany', 'DEU', 'DE');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Ghana', 'GHA', 'GH'),
	('Gibraltar', 'GIB', 'GI'),
	('Greece', 'GRC', 'GR'),
	('Greenland', 'GRL', 'GL'),
	('Grenada', 'GRD', 'GD'),
	('Guadeloupe', 'GLP', 'GP'),
	('Guam', 'GUM', 'GU'),
	('Guatemala', 'GTM', 'GT'),
	('Guinea', 'GIN', 'GN'),
	('Guinea-Bissau', 'GNB', 'GW');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Guyana', 'GUY', 'GY'),
	('Haiti', 'HTI', 'HT'),
	('Heard and Mc Donald Islands', 'HMD', 'HM'),
	('Honduras', 'HND', 'HN'),
	('Hong Kong', 'HKG', 'HK'),
	('Hungary', 'HUN', 'HU'),
	('Iceland', 'ISL', 'IS'),
	('India', 'IND', 'IN'),
	('Indonesia', 'IDN', 'ID'),
	('Iran (Islamic Republic of)', 'IRN', 'IR');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Iraq', 'IRQ', 'IQ'),
	('Ireland', 'IRL', 'IE'),
	('Israel', 'ISR', 'IL'),
	('Italy', 'ITA', 'IT'),
	('Jamaica', 'JAM', 'JM'),
	('Japan', 'JPN', 'JP'),
	('Jordan', 'JOR', 'JO'),
	('Kazakhstan', 'KAZ', 'KZ'),
	('Kenya', 'KEN', 'KE'),
	('Kiribati', 'KIR', 'KI');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('North Korea', 'PRK', 'KP'),
	('South Korea', 'KOR', 'KR'),
	('Kuwait', 'KWT', 'KW'),
	('Kyrgyzstan', 'KGZ', 'KG'),
	('Lao People''s Democratic Republic', 'LAO', 'LA'),
	('Latvia', 'LVA', 'LV'),
	('Lebanon', 'LBN', 'LB'),
	('Lesotho', 'LSO', 'LS'),
	('Liberia', 'LBR', 'LR'),
	('Libyan Arab Jamahiriya', 'LBY', 'LY');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Liechtenstein', 'LIE', 'LI'),
	('Lithuania', 'LTU', 'LT'),
	('Luxembourg', 'LUX', 'LU'),
	('Macau', 'MAC', 'MO'),
	('FYROM', 'MKD', 'MK'),
	('Madagascar', 'MDG', 'MG'),
	('Malawi', 'MWI', 'MW'),
	('Malaysia', 'MYS', 'MY'),
	('Maldives', 'MDV', 'MV'),
	('Mali', 'MLI', 'ML');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Malta', 'MLT', 'MT'),
	('Marshall Islands', 'MHL', 'MH'),
	('Martinique', 'MTQ', 'MQ'),
	('Mauritania', 'MRT', 'MR'),
	('Mauritius', 'MUS', 'MU'),
	('Mayotte', 'MYT', 'YT'),
	('Mexico', 'MEX', 'MX'),
	('Micronesia, Federated States of', 'FSM', 'FM'),
	('Moldova, Republic of', 'MDA', 'MD'),
	('Monaco', 'MCO', 'MC');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Mongolia', 'MNG', 'MN'),
	('Montserrat', 'MSR', 'MS'),
	('Morocco', 'MAR', 'MA'),
	('Mozambique', 'MOZ', 'MZ'),
	('Myanmar', 'MMR', 'MM'),
	('Namibia', 'NAM', 'NA'),
	('Nauru', 'NRU', 'NR'),
	('Nepal', 'NPL', 'NP'),
	('Netherlands', 'NLD', 'NL'),
	('Netherlands Antilles', 'ANT', 'AN');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('New Caledonia', 'NCL', 'NC'),
	('New Zealand', 'NZL', 'NZ'),
	('Nicaragua', 'NIC', 'NI'),
	('Niger', 'NER', 'NE'),
	('Nigeria', 'NGA', 'NG'),
	('Niue', 'NIU', 'NU'),
	('Norfolk Island', 'NFK', 'NF'),
	('Northern Mariana Islands', 'MNP', 'MP'),
	('Norway', 'NOR', 'NO'),
	('Oman', 'OMN', 'OM');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Pakistan', 'PAK', 'PK'),
	('Palau', 'PLW', 'PW'),
	('Panama', 'PAN', 'PA'),
	('Papua New Guinea', 'PNG', 'PG'),
	('Paraguay', 'PRY', 'PY'),
	('Peru', 'PER', 'PE'),
	('Philippines', 'PHL', 'PH'),
	('Pitcairn', 'PCN', 'PN'),
	('Poland', 'POL', 'PL'),
	('Portugal', 'PRT', 'PT');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Puerto Rico', 'PRI', 'PR'),
	('Qatar', 'QAT', 'QA'),
	('Reunion', 'REU', 'RE'),
	('Romania', 'ROM', 'RO'),
	('Russian Federation', 'RUS', 'RU'),
	('Rwanda', 'RWA', 'RW'),
	('Saint Kitts and Nevis', 'KNA', 'KN'),
	('Saint Lucia', 'LCA', 'LC'),
	('Saint Vincent and the Grenadines', 'VCT', 'VC'),
	('Samoa', 'WSM', 'WS');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('San Marino', 'SMR', 'SM'),
	('Sao Tome and Principe', 'STP', 'ST'),
	('Saudi Arabia', 'SAU', 'SA'),
	('Senegal', 'SEN', 'SN'),
	('Seychelles', 'SYC', 'SC'),
	('Sierra Leone', 'SLE', 'SL'),
	('Singapore', 'SGP', 'SG'),
	('Slovak Republic', 'SVK', 'SK'),
	('Slovenia', 'SVN', 'SI'),
	('Solomon Islands', 'SLB', 'SB');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Somalia', 'SOM', 'SO'),
	('South Africa', 'ZAF', 'ZA'),
	(
		'South Georgia &amp; South Sandwich Islands',
		'SGS',
		'GS'
	),
	('Spain', 'ESP', 'ES'),
	('Sri Lanka', 'LKA', 'LK'),
	('St. Helena', 'SHN', 'SH'),
	('St. Pierre and Miquelon', 'SPM', 'PM'),
	('Sudan', 'SDN', 'SD'),
	('Suriname', 'SUR', 'SR'),
	('Svalbard and Jan Mayen Islands', 'SJM', 'SJ');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Swaziland', 'SWZ', 'SZ'),
	('Sweden', 'SWE', 'SE'),
	('Switzerland', 'CHE', 'CH'),
	('Syrian Arab Republic', 'SYR', 'SY'),
	('Taiwan', 'TWN', 'TW'),
	('Tajikistan', 'TJK', 'TJ'),
	('Tanzania, United Republic of', 'TZA', 'TZ'),
	('Thailand', 'THA', 'TH'),
	('Togo', 'TGO', 'TG'),
	('Tokelau', 'TKL', 'TK');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Tonga', 'TON', 'TO'),
	('Trinidad and Tobago', 'TTO', 'TT'),
	('Tunisia', 'TUN', 'TN'),
	('Turkey', 'TUR', 'TR'),
	('Turkmenistan', 'TKM', 'TM'),
	('Turks and Caicos Islands', 'TCA', 'TC'),
	('Tuvalu', 'TUV', 'TV'),
	('Uganda', 'UGA', 'UG'),
	('Ukraine', 'UKR', 'UA'),
	('United Arab Emirates', 'ARE', 'AE');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('United Kingdom', 'GBR', 'GB'),
	('United States of America', 'USA', 'US'),
	(
		'United States Minor Outlying Islands',
		'UMI',
		'UM'
	),
	('Uruguay', 'URY', 'UY'),
	('Uzbekistan', 'UZB', 'UZ'),
	('Vanuatu', 'VUT', 'VU'),
	('Vatican City State (Holy See)', 'VAT', 'VA'),
	('Venezuela', 'VEN', 'VE'),
	('Viet Nam', 'VNM', 'VN'),
	('Virgin Islands (British)', 'VGB', 'VG');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Virgin Islands (U.S.)', 'VIR', 'VI'),
	('Wallis and Futuna Islands', 'WLF', 'WF'),
	('Western Sahara', 'ESH', 'EH'),
	('Yemen', 'YEM', 'YE'),
	('Democratic Republic of Congo', 'COD', 'CD'),
	('Zambia', 'ZMB', 'ZM'),
	('Zimbabwe', 'ZWE', 'ZW'),
	('Montenegro', 'MNE', 'ME'),
	('Serbia', 'SRB', 'RS'),
	('Aaland Islands', 'ALA', 'AX');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Bonaire, Sint Eustatius and Saba', 'BES', 'BQ'),
	('Curacao', 'CUW', 'CW'),
	('Palestinian Territory, Occupied', 'PSE', 'PS'),
	('South Sudan', 'SSD', 'SS'),
	('St. Barthelemy', 'BLM', 'BL'),
	('St. Martin (French part)', 'MAF', 'MF'),
	('Canary Islands', 'ICA', 'IC'),
	('Ascension Island (British)', 'ASC', 'AC'),
	('Kosovo, Republic of', 'UNK', 'XK'),
	('Isle of Man', 'IMN', 'IM');
INSERT INTO `countries` (`name`, `countrycode`, `code`)
VALUES ('Tristan da Cunha', 'SHN', 'TA'),
	('Guernsey', 'GGY', 'GG'),
	('Jersey', 'JEY', 'JE');