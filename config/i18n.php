<?php

/**
 * Translate configuration
 * 
 * Usage: 
 * ```bash
 * yii message/extract @vendor/vommuan/yii2-filemanager/config/i18n.php
 * ```
 */
return [
	/**
	 * string, required, root directory of all source files
	 */
	'sourcePath' => __DIR__ . DIRECTORY_SEPARATOR . '..',
	
	/**
	 * array, required, list of language codes that the extracted messages 
	 * should be translated to. For example, ['zh-CN', 'de'].
	 */
	'languages' => [
		'af-ZA',
		'am-ET',
		'ar-AE',
		'ar-BH',
		'ar-DZ',
		'ar-EG',
		'ar-IQ',
		'ar-JO',
		'ar-KW',
		'ar-LB',
		'ar-LY',
		'ar-MA',
		'ar-OM',
		'ar-QA',
		'ar-SA',
		'ar-SY',
		'ar-TN',
		'ar-YE',
		'arn-CL',
		'as-IN',
		'az-Cyrl-AZ',
		'az-Latn-AZ',
		'ba-RU',
		'be-BY',
		'bg-BG',
		'bn-BD',
		'bn-IN',
		'bo-CN',
		'br-FR',
		'bs-Cyrl-BA',
		'bs-Latn-BA',
		'ca-ES',
		'co-FR',
		'cs-CZ',
		'cy-GB',
		'da-DK',
		'de-AT',
		'de-CH',
		'de-DE',
		'de-LI',
		'de-LU',
		'dsb-DE',
		'dv-MV',
		'el-GR',
		'en-029',
		'en-AU',
		'en-BZ',
		'en-CA',
		'en-GB',
		'en-IE',
		'en-IN',
		'en-JM',
		'en-MY',
		'en-NZ',
		'en-PH',
		'en-SG',
		'en-TT',
		'en-US',
		'en-ZA',
		'en-ZW',
		'es-AR',
		'es-BO',
		'es-CL',
		'es-CO',
		'es-CR',
		'es-DO',
		'es-EC',
		'es-ES',
		'es-GT',
		'es-HN',
		'es-MX',
		'es-NI',
		'es-PA',
		'es-PE',
		'es-PR',
		'es-PY',
		'es-SV',
		'es-US',
		'es-UY',
		'es-VE',
		'et-EE',
		'eu-ES',
		'fa-IR',
		'fi-FI',
		'fil-PH',
		'fo-FO',
		'fr-BE',
		'fr-CA',
		'fr-CH',
		'fr-FR',
		'fr-LU',
		'fr-MC',
		'fy-NL',
		'ga-IE',
		'gd-GB',
		'gl-ES',
		'gsw-FR',
		'gu-IN',
		'ha-Latn-NG',
		'he-IL',
		'hi-IN',
		'hr-BA',
		'hr-HR',
		'hsb-DE',
		'hu-HU',
		'hy-AM',
		'id-ID',
		'ig-NG',
		'ii-CN',
		'is-IS',
		'it-CH',
		'it-IT',
		'iu-Cans-CA',
		'iu-Latn-CA',
		'ja-JP',
		'ka-GE',
		'kk-KZ',
		'kl-GL',
		'km-KH',
		'kn-IN',
		'kok-IN',
		'ko-KR',
		'ky-KG',
		'lb-LU',
		'lo-LA',
		'lt-LT',
		'lv-LV',
		'mi-NZ',
		'mk-MK',
		'ml-IN',
		'mn-MN',
		'mn-Mong-CN',
		'moh-CA',
		'mr-IN',
		'ms-BN',
		'ms-MY',
		'mt-MT',
		'nb-NO',
		'ne-NP',
		'nl-BE',
		'nl-NL',
		'nn-NO',
		'nso-ZA',
		'oc-FR',
		'or-IN',
		'pa-IN',
		'pl-PL',
		'prs-AF',
		'ps-AF',
		'pt-BR',
		'pt-PT',
		'qut-GT',
		'quz-BO',
		'quz-EC',
		'quz-PE',
		'rm-CH',
		'ro-RO',
		'ru-RU',
		'rw-RW',
		'sah-RU',
		'sa-IN',
		'se-FI',
		'se-NO',
		'se-SE',
		'si-LK',
		'sk-SK',
		'sl-SI',
		'sma-NO',
		'sma-SE',
		'smj-NO',
		'smj-SE',
		'smn-FI',
		'sms-FI',
		'sq-AL',
		'sr-Cyrl-BA',
		'sr-Cyrl-CS',
		'sr-Cyrl-ME',
		'sr-Cyrl-RS',
		'sr-Latn-BA',
		'sr-Latn-CS',
		'sr-Latn-ME',
		'sr-Latn-RS',
		'sv-FI',
		'sv-SE',
		'sw-KE',
		'syr-SY',
		'ta-IN',
		'te-IN',
		'tg-Cyrl-TJ',
		'th-TH',
		'tk-TM',
		'tn-ZA',
		'tr-TR',
		'tt-RU',
		'tzm-Latn-DZ',
		'ug-CN',
		'uk-UA',
		'ur-PK',
		'uz-Cyrl-UZ',
		'uz-Latn-UZ',
		'vi-VN',
		'wo-SN',
		'xh-ZA',
		'yo-NG',
		'zh-CN',
		'zh-HK',
		'zh-MO',
		'zh-SG',
		'zh-TW',
		'zu-ZA',
	],
	
	/**
	 * string, the name of the function for translating messages.
	 * Defaults to 'Yii::t'. This is used as a mark to find the messages to be
	 * translated. You may use a string for single function name or an array for
	 * multiple function names.
	 */
	'translator' => 'Module::t',
	
	/**
	 * boolean, whether to sort messages by keys when merging new messages
	 * with the existing ones. Defaults to false, which means the new (untranslated)
	 * messages will be separated from the old (translated) ones.
	 */
	'sort' => false,
	
	/**
	 * boolean, whether to remove messages that no longer appear in the source code.
	 * Defaults to false, which means these messages will NOT be removed.
	 */
	'removeUnused' => true,
	
	/**
	 * boolean, whether to mark messages that no longer appear in the source code.
	 * Defaults to true, which means each of these messages will be enclosed with a pair of '@@' marks.
	 */
	'markUnused' => true,
	
	/**
	 * array, list of patterns that specify which files (not directories) should be processed.
	 * If empty or not set, all files will be processed.
	 * Please refer to "except" for details about the patterns.
	 */
	'only' => ['*.php'],
	
	/**
	 * array, list of patterns that specify which files/directories should NOT be processed.
	 * If empty or not set, all files/directories will be processed.
	 * A path matches a pattern if it contains the pattern string at its end. For example,
	 * '/a/b' will match all files and directories ending with '/a/b';
	 * the '*.svn' will match all files and directories whose name ends with '.svn'.
	 * and the '.svn' will match all files and directories named exactly '.svn'.
	 * Note, the '/' characters in a pattern matches both '/' and '\'.
	 * See helpers/FileHelper::findFiles() description for more details on pattern matching rules.
	 * If a file/directory matches both a pattern in "only" and "except", it will NOT be processed.
	 */
	'except' => [
		'.svn',
		'.git',
		'.gitignore',
		'.gitkeep',
		'.hgignore',
		'.hgkeep',
		'/messages',
	],

	/**
	 * 'php' output format is for saving messages to php files.
	 */
	'format' => 'php',
	
	/**
	 * Root directory containing message translations.
	 */
	'messagePath' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'messages',
	
	/**
	 * boolean, whether the message file should be overwritten with the merged messages
	 */
	'overwrite' => true,

	/**
	 * Message categories to ignore
	 */
	'ignoreCategories' => [
		'yii',
	],
];