<?php
  /* This file is automatically generated by {@link BuildMetadataPHPFromXml}.
   * Please don't modify it directly.
   */


return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '
          [1-8]\\d{7}|
          9(?:
            [1-69]\\d{6}|
            7[2-6]\\d{5,7}|
            8\\d{8}
          )
        ',
    'PossibleNumberPattern' => '\\d{6,10}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          (?:
            1[0124-6]|
            2[13-79]|
            3[135-8]|
            4[146-9]|
            5[135-7]|
            6[13-9]|
            7[15-9]|
            8[1-46-9]|
            9[1-79]
          )\\d{6}
        ',
    'PossibleNumberPattern' => '\\d{6,8}',
    'ExampleNumber' => '14567890',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          9(?:
            7[45]|
            8[01456]
          )\\d{7}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '9841234567',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'sharedCost' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'personalNumber' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'voip' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'pager' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'uan' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'emergency' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'voicemail' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'shortCode' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'standardRate' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'carrierSpecific' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'noInternationalDialling' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'id' => 'NP',
  'countryCode' => 977,
  'internationalPrefix' => '00',
  'nationalPrefix' => '0',
  'nationalPrefixForParsing' => '0',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(1)(\\d{7})',
      'format' => '$1-$2',
      'leadingDigitsPatterns' => 
      array (
        0 => '1[2-6]',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(\\d{2})(\\d{6})',
      'format' => '$1-$2',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            1[01]|
            [2-8]|
            9(?:
              [1-69]|
              7[15-9]
            )
          ',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    2 => 
    array (
      'pattern' => '(9\\d{2})(\\d{7})',
      'format' => '$1-$2',
      'leadingDigitsPatterns' => 
      array (
        0 => '
            9(?:
              7[45]|
              8
            )
            ',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
  ),
  'intlNumberFormat' => 
  array (
  ),
  'mainCountryForCode' => false,
  'leadingZeroPossible' => false,
  'mobileNumberPortableRegion' => false,
);
/* EOF */