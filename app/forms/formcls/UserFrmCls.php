<?php



class UserFrmCls extends MuxForm{
				public static $countryArr = array(
					'AL'=>'Albania',
					'DZ'=>'Algeria',
					'AS'=>'American Samoa',
					'AD'=>'Andorra',
					'AO'=>'Angola',
					'AI'=>'Anguilla',
					'AG'=>'Antigua and Barbadua',
					'AR'=>'Argentina',
					'AM'=>'Armenia',
					'AW'=>'Aruba',
					'AU'=>'Australia',
					'AT'=>'Austria',
					'AZ'=>'Azerbaijan',
					'BS'=>'Bahamas',
					'BH'=>'Bahrain',
					'BD'=>'Bangladesh',
					'BB'=>'Barbados',
					'BY'=>'Belarus',
					'BE'=>'Belgium',
					'BZ'=>'Belize',
					'BJ'=>'Benin',
					'BM'=>'Bermuda',
					'BT'=>'Bhutan',
					'BO'=>'Bolivia',
					'BA'=>'Bosnia',
					'BW'=>'Botswana',
					'BR'=>'Brazil',
					'VG'=>'British Virgin Isles',
					'BN'=>'Brunei',
					'BG'=>'Bulgaria',
					'BF'=>'Burkina Faso',
					'BI'=>'Burundi',
					'KH'=>'Cambodia',
					'CM'=>'Cameroon',
					'CA'=>'Canada',
					'CV'=>'Cape Verde',
					'KY'=>'Cayman Islands',
					'CF'=>'Central African Republic',
					'TD'=>'Chad',
					'CL'=>'Chile',
					'CN'=>'China',
					'CO'=>'Colombia',
					'CK'=>'Cook Islands',
					'CR'=>'Costa Rica',
					'HR'=>'Croatia',
					'CY'=>'Cyprus',
					'CZ'=>'Czech Republic',
					'DK'=>'Denmark',
					'DJ'=>'Djibouti',
					'DM'=>'Dominica',
					'DO'=>'Dominican Republic',
					'EC'=>'Ecuador',
					'EG'=>'Egypt',
					'SV'=>'El Salvador',
					'GQ'=>'Equatorial Guinea',
					'ER'=>'Eritrea',
					'EE'=>'Estonia',
					'ET'=>'Ethiopia',
					'FO'=>'Faroe Islands',
					'FJ'=>'Fiji',
					'FI'=>'Finland',
					'FR'=>'France',
					'GF'=>'French Guiana',
					'PF'=>'French Polynesia',
					'GA'=>'Gabon',
					'GM'=>'Gambia',
					'DE'=>'Germany',
					'GH'=>'Ghana',
					'GI'=>'Gibraltar',
					'GB'=>'Great Britain and Northern Ireland',
					'GR'=>'Greece',
					'GL'=>'Greenland',
					'GD'=>'Grenada',
					'GP'=>'Guadeloupe',
					'GU'=>'Guam',
					'GT'=>'Guatemala',
					'GN'=>'Guinea',
					'GW'=>'Guinea-bissau',
					'GY'=>'Guyana',
					'HT'=>'Haiti',
					'HN'=>'Honduras',
					'HK'=>'Hong Kong',
					'HU'=>'Hungary',
					'IS'=>'Iceland',
					'IN'=>'India',
					'ID'=>'Indonesia',
					'IQ'=>'Iraq',
					'IE'=>'Ireland',
					'IL'=>'Israel',
					'IT'=>'Italy',
					'CI'=>'Ivory Coast',
					'JM'=>'Jamaica',
					'JP'=>'Japan',
					'JO'=>'Jordan',
					'KZ'=>'Kazakhstan',
					'KE'=>'Kenya',
					'KI'=>'Kiribati',
					'KW'=>'Kuwait',
					'KG'=>'Kyrgyzstan',
					'LA'=>'Laos',
					'LV'=>'Latvia',
					'LB'=>'Lebanon',
					'LS'=>'Lesotho',
					'LR'=>'Liberia',
					'LI'=>'Liechtenstein',
					'LT'=>'Lithuania',
					'LU'=>'Luxembourg',
					'MO'=>'Macao',
					'MK'=>'Macedonia',
					'MG'=>'Madagascar',
					'MW'=>'Malawi',
					'MY'=>'Malaysia',
					'MV'=>'Maldives',
					'ML'=>'Mali',
					'MT'=>'Malta',
					'MH'=>'Marshall Islands',
					'MQ'=>'Martinique',
					'MR'=>'Mauritania',
					'MU'=>'Mauritius',
					'MX'=>'Mexico',
					'FM'=>'Micronesia',
					'MD'=>'Moldova',
					'MC'=>'Monaco',
					'MN'=>'Mongolia',
					'MS'=>'Montserrat',
					'MA'=>'Morocco',
					'MZ'=>'Mozambique',
					'MP'=>'N. Mariana Islands',
					'NA'=>'Namibia',
					'NP'=>'Nepal',
					'NL'=>'Netherlands',
					'AN'=>'Netherlands Antilles',
					'NC'=>'New Caledonia',
					'NZ'=>'New Zealand',
					'NI'=>'Nicaragua',
					'NE'=>'Niger',
					'NG'=>'Nigeria',
					'NF'=>'Norfolk Island',
					'NO'=>'Norway',
					'OM'=>'Oman',
					'PK'=>'Pakistan',
					'PW'=>'Palau',
					'PA'=>'Panama',
					'PG'=>'Papua New Guinea',
					'PY'=>'Paraguay',
					'PE'=>'Peru',
					'PH'=>'Philippines',
					'PL'=>'Poland',
					'PT'=>'Portugal',
					'PR'=>'Puerto Rico',
					'QA'=>'Qatar',
					'GE'=>'Republic of Georgia',
					'KR'=>'Republic of Korea',
					'CG'=>'Republic of the Congo',
					'RE'=>'Reunion',
					'RO'=>'Romania',
					'RU'=>'Russia',
					'RW'=>'Rwanda',
					'SM'=>'San Marino',
					'SA'=>'Saudi Arabia',
					'SN'=>'Senegal',
					'SC'=>'Seychelles',
					'SL'=>'Sierra Leone',
					'SG'=>'Singapore',
					'SK'=>'Slovakia',
					'SI'=>'Slovenia',
					'SB'=>'Solomon Islands',
					'ZA'=>'South Africa',
					'ES'=>'Spain',
					'LK'=>'Sri Lanka',
					'KN'=>'St. Christopher and Nevis',
					'LC'=>'St. Lucia',
					'VC'=>'St. Vincent and the Grenadines',
					'SR'=>'Suriname',
					'SZ'=>'Swaziland',
					'SE'=>'Sweden',
					'CH'=>'Switzerland',
					'SY'=>'Syria',
					'TW'=>'Taiwan',
					'TJ'=>'Tajikistan',
					'TZ'=>'Tanzania',
					'TH'=>'Thailand',
					'TG'=>'Togo',
					'TO'=>'Tonga',
					'TT'=>'Trinidad and Tobago',
					'TN'=>'Tunisia',
					'TR'=>'Turkey',
					'TM'=>'Turkmenistan',
					'TC'=>'Turks and Caicos Islands',
					'TV'=>'Tuvalu',
					'UG'=>'Uganda',
					'UA'=>'Ukraine',
					'AE'=>'United Arab Emirates',
					'US'=>'United States',
					'UY'=>'Uruguay',
					'UZ'=>'Uzbekistan',
					'VU'=>'Vanuatu',
					'VE'=>'Venezuela',
					'VN'=>'Vietnam',
					'VI'=>'Virgin Islands',
					'WF'=>'Wallis and Futuna Islands',
					'WS'=>'Western Samoa',
					'YE'=>'Yemen',
					'CS'=>'Yugoslavia',
					'ZM'=>'Zambia',
					'ZW'=>'Zimbabwe'
				);
			public static $Motnhs = array(
			'1'=>'January',
			'2'=>'February',
			'3'=>'March',
			'4'=>'April',
			'5'=>'May',
			'6'=>'June',
			'7'=>'July',
			'8'=>'August',
			'9'=>'September',
			'10'=>'October',
			'11'=>'November',
			'12'=>'December'
		);
		
		public static $Days = array(
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'9',
			'10',
			'11',
			'12',
			'13',
			'14',
			'15',
			'16',
			'17',
			'18',
			'19',
			'20',
			'21',
			'22',
			'23',
			'24',
			'25',
			'26',
			'27',
			'28',
			'29',
			'30',
			'31'
		);
		
		public static $Years = array(
			'1933',
			'1934',
			'1935',
			'1936',
			'1937',
			'1938',
			'1939',
			'1940',
			'1941',
			'1942',
			'1943',
			'1944',
			'1945',
			'1946',
			'1947',
			'1948',
			'1949',
			'1950',
			'1951',
			'1952',
			'1953',
			'1954',
			'1955',
			'1956',
			'1957',
			'1958',
			'1959',
			'1960',
			'1961',
			'1962',
			'1963',
			'1964',
			'1965',
			'1966',
			'1967',
			'1968',
			'1969',
			'1970',
			'1971',
			'1972',
			'1973',
			'1974',
			'1975',
			'1976',
			'1977',
			'1978',
			'1979',
			'1980',
			'1981',
			'1982',
			'1983',
			'1984',
			'1985',
			'1986',
			'1987',
			'1988',
			'1989',
			'1990',
			'1991',
			'1992',
			'1993',
			'1994',
			'1995',
			'1996',
			'1997',
			'1998',
			'1999',
			'2000',
			'2001',
			'2002',
			'2003',
			'2004',
			'2005',
			'2006',
			'2007',
			'2008',
			'2009',
			'2010',
			'2011',
		);
		
	public function countryDdl($keyVal='' , $cssClass=''){
		$html = '';
		$html .= '<select name="country" class='.$cssClass.'><option value="" selected="selected">Select Country</option>';
		foreach(self::$countryArr as $code=>$names){
			if($code == $keyVal){
				$html .= "<option value='$code' selected='selected'>$names</option>";
			}else{
				$html .= "<option value='$code'>$names</option>";
			}
		}
		$html .= '</select>';
		return $html; 			
	}
	
	public function profileGenderRdo($selected=''){
			
			$html = '';
			switch($selected){
				case 'm':
					$html = '<input type="radio" name="gender" value="m" checked="checked" /> Male &nbsp;&nbsp;&nbsp;
                        	 <input type="radio" name="gender" value="f" /> Female &nbsp;&nbsp;&nbsp;
                             <input type="radio" name="gender" value="n" /> Rather not say';
					break;
				case 'f':
					$html = '<input type="radio" name="gender" value="m" /> Male &nbsp;&nbsp;&nbsp;
                        	 <input type="radio" name="gender" value="f" checked="checked"/> Female &nbsp;&nbsp;&nbsp;
                             <input type="radio" name="gender" value="n" /> Rather not say';
					break;
				case 'n':
					$html = '<input type="radio" name="gender" value="m"/> Male &nbsp;&nbsp;&nbsp;
                        	 <input type="radio" name="gender" value="f" /> Female &nbsp;&nbsp;&nbsp;
                             <input type="radio" name="gender" value="n" checked="checked"/> Rather not say';
					break;
				default:
					$html = '<input type="radio" name="gender" value="m" checked="checked" /> Male &nbsp;&nbsp;&nbsp;
                        	 <input type="radio" name="gender" value="f" /> Female &nbsp;&nbsp;&nbsp;
                             <input type="radio" name="gender" value="n" checked="checked"/> Rather not say';
			}
			return $html;
	}
	
	public function profileMounthDdl($selected='',$cssClass='dropdown'){
		$html = '';
		$html .= '<select name="birth_month" class="'.$cssClass.'"><option></option>';
		foreach(self::$Motnhs as $key=>$val){
			if($selected==$key){
				$html .= '<option value="'.$key.'" selected="selected">'.$val.'</option>';
			}else{
				$html .= '<option value="'.$key.'">'.$val.'</option>';		
			}
		}
		$html .= '</select>';
		return $html;
	}
	
	
	public function profileDayDdl($selected='',$cssClass='dropdown'){
		$html = '';
		$html .= '<select name="birth_day" class="'.$cssClass.'"><option></option>';
		foreach(self::$Days as $val){
			if($selected==$val){
				$html .= '<option value="'.$val.'" selected="selected">'.$val.'</option>';
			}else{
				$html .= '<option value="'.$val.'">'.$val.'</option>';		
			}
		}
		$html .= '</select>';
		return $html;
	}
	
	public function profileYearDdl($selected='',$cssClass='dropdown'){
		$html = '';
		$html .= '<select name="birth_year" class="'.$cssClass.'"><option></option>';
		foreach(self::$Years as $val){
			if($selected==$val){
				$html .= '<option value="'.$val.'" selected="selected">'.$val.'</option>';
			}else{
				$html .= '<option value="'.$val.'">'.$val.'</option>';		
			}
		}
		$html .= '</select>';
		return $html;
	}
	
	
}//$

?>