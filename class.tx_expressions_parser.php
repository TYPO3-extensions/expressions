<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Francois Suter (Cobweb) <typo3@cobweb.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
*
* $Id: class.tx_expressions_parser.php 241 2009-09-29 15:38:23Z fsuter $
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


/**
 * Utility class to parse strings and evaluate them as expressions or for any subexpressions they may contain
 *
 * @author		Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package		TYPO3
 * @subpackage	tx_expressions
 *
 * $Id: class.tx_expressions_parser.php 241 2009-09-29 15:38:23Z fsuter $
 */
class tx_expressions_parser {
	public static $extKey = 'expressions';
	public static $vars = array(); // Local variables stored in the parser
	public static $extraData = array(); // Additional values stored in the parser

	/**
	 * This method takes a string and checks for substrings inside curly braces {}
	 * Each such substring will be evaluated and replaced
	 * The resulting string is then evaluated or not, depending on the second argument
	 *
	 * @param	string		$string: the string to parse
	 * @param	boolean		$doEvaluation: true if string should be evaluated before being returned (default)
	 * @return	string		The parsed string
	 */
	public static function evaluateString($string, $doEvaluation = true) {
		$matches = array();
		$numReplacements = preg_match_all('/(\{.*?\})/', $string, $matches, PREG_SET_ORDER);
			// If there was nothing to match or the matching failed, return the string as is
		if (empty($numReplacements)) {
			$result = $string;
		}
		else {
			$searches = array();
			$replacements = array();
			foreach ($matches as $aMatch) {
				$searches[] = $aMatch[1];
				$expression = substr($aMatch[1], 1, strlen($aMatch[1]) - 2);
				try {
					$evaluatedExpression = self::evaluateExpression($expression);
				}
				catch (Exception $e) {
					$evaluatedExpression = $expression;
					if (TYPO3_DLOG) {
						t3lib_div::devLog('Bad subexpression: ' . $expression . ' (' . $e->getMessage() . ')', 'expressions', 2);
					}
				}
				$replacements[] = $evaluatedExpression;
			}
			$result = str_replace($searches, $replacements, $string);
		}
			// Evaluate the string, if necessary
		if ($doEvaluation) {
			try {
				$finalString = self::evaluateExpression($result);
			}
				// If the evaluation fails, return the original string
			catch (Exception $e) {
				$finalString = $result;
				if (TYPO3_DLOG) {
					t3lib_div::devLog('Could not evaluate string: ' . $result . ' (' . $e->getMessage() . ')', 'expressions', 2);
				}
			}
		}
		else {
			$finalString = $result;
		}
		return $finalString;
	}

	/**
	 * This method evaluates the value of a given expression for a filter
	 * The expected syntax of a filter value is key:index1|index2|...
	 * Simple values will be used as is
	 *
	 * @param	string	$expression: the expression to evaluate
	 * @return	string	The value for the filter
	 */
	public static function evaluateExpression($expression) {
		$returnValue = '';
		$hasValue = false;
		if (empty($expression)) {
			throw new Exception('Empty expression received');
		}
		else {
				// An expression may contain several expressions as alternate values, separated by a double slash (//)
			$allExpressions = t3lib_div::trimExplode('//', $expression);
			foreach ($allExpressions as $anExpression) {
					// If there's no colon (:) in the expression, take it to be a litteral value and return it as is
				if (strpos($anExpression, ':') === false) {
					$returnValue = $anExpression;
					$hasValue = true;
				}
				else {
					$indices = '';
					list($key, $indices) = t3lib_div::trimExplode(':', $anExpression);
					if (empty($indices)) {
						throw new Exception('No indices in expression: ' . $expression);
					}
					$key = strtolower($key);
					switch ($key) {
							// Search for a value in the TSFE
						case 'tsfe':
							if (TYPO3_MODE == 'FE') {
								try {
									$returnValue = self::getValue($GLOBALS['TSFE'], $indices);
									$hasValue = true;
								}
								catch (Exception $e) {
									continue;
								}
							}
							else {
								throw new Exception('TSFE not available in this mode (' . TYPO3_MODE . ')');
							}
							break;
							// Search for a value in the page record
							// This is a convenience shortcut, as the page record is in the TSFE
						case 'page':
							if (TYPO3_MODE == 'FE') {
								try {
									$returnValue = self::getValue($GLOBALS['TSFE']->page, $indices);
									$hasValue = true;
								}
								catch (Exception $e) {
									continue;
								}
							}
							else {
								throw new Exception('TSFE->page not available in this mode (' . TYPO3_MODE . ')');
							}
							break;
							// Search for a value in the template configuration
							// This is a convenience shortcut, as the template configuration is in the TSFE
						case 'config':
							if (TYPO3_MODE == 'FE') {
								try {
									$returnValue = self::getValue($GLOBALS['TSFE']->config['config'], $indices);
									$hasValue = true;
								}
								catch (Exception $e) {
									continue;
								}
							}
							else {
								throw new Exception('TSFE->config not available in this mode (' . TYPO3_MODE . ')');
							}
							break;
							// Search for a value in the merged GET and POST arrays
						case 'gp':
							try {
								$returnValue = self::getValue(array_merge(t3lib_div::_GET(), t3lib_div::_POST()), $indices);
								$hasValue = true;
							}
							catch (Exception $e) {
								continue;
							}
							break;
							// Search for a value in the local variables
						case 'vars':
							try {
								$returnValue = self::getValue(self::$vars, $indices);
								$hasValue = true;
							}
							catch (Exception $e) {
								continue;
							}
							break;
							// Search for a value in the extra data
						case 'extra':
							try {
								$returnValue = self::getValue(self::$extraData, $indices);
								$hasValue = true;
							}
							catch (Exception $e) {
								continue;
							}
							break;
							// Calculate a value using the PHP date() function
						case 'date':
							$returnValue = date($indices);
							$hasValue = true;
							break;
						case 'strtotime':
							$value = strtotime($indices);
							if ($value === false || $value === -1) {
								throw new Exception('Date string could not be parsed: ' . $indices);
							} else {
								$returnValue = $value;
								$hasValue = true;
							}
							break;
							// Get data from the session
							// The session key is the first segment after the "session" keyword
						case 'session':
							$segments = t3lib_div::trimExplode('|', $indices);
							$cacheKey = array_shift($segments);
							$indices = implode('|', $segments);
							$cache = $GLOBALS['TSFE']->fe_user->getKey('ses', $cacheKey);
							if (empty($cache)) {
								throw new Exception('No session data found for expression: ' . $expression);
							}
							try {
								$returnValue = self::getValue($cache, $indices);
								$hasValue = true;
							}
							catch (Exception $e) {
								continue;
							}
							break;
							// If none of the standard keys matched, try looking for a hook for that given key
						default:
							if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][self::$extKey]['keyProcessor'][$key])) {
								$keyProcessor = &t3lib_div::getUserObj($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][self::$extKey]['keyProcessor'][$key]);
								if ($keyProcessor instanceof tx_expressions_keyProcessor) {
									$returnValue = $keyProcessor->getValue($indices);
									$hasValue = true;
								}
							}
							break;
					}
				}
			}
		}
			// If a value was found, call a post-processing hook and return the value
		if ($hasValue) {
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][self::$extKey]['postprocessReturnValue'])) {
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][self::$extKey]['postprocessReturnValue'] as $className) {
					$postProcessor = &t3lib_div::getUserObj($className);
					if ($postProcessor instanceof tx_expressions_valuePostProcessor) {
						$returnValue = $postProcessor->postprocessReturnValue($returnValue);
					}
				}
			}
			return $returnValue;

			// No value was found, throw an exception
		} else {
			throw new Exception('No value found for expression: ' . $expression);
		}
	}

	/**
	 * This method is used to get a value from inside a multi-dimensional array or object
	 * NOTE: this code is largely inspired by tslib_content::getGlobal()
	 *
	 * @param	mixed	$source: array or object to look into
	 * @param	string	$indices: "path" of indinces inside the multi-dimensional array, of the form index1|index2|...
	 * @return	mixed	Whatever value was found in the array, but it should be a simple type
	 */
	protected static function getValue($source, $indices) {
		if (empty($indices)) {
			throw new Exception('No key given for source');
		}
		else {
			$indexList = t3lib_div::trimExplode('|', $indices);
			$value = $source;
			foreach ($indexList as $key) {
				if (is_object($value) && isset($value->$key)) {
					$value = $value->$key;
				}
				elseif (is_array($value) && isset($value[$key])) {
					$value = $value[$key];
				}
				else {
					throw new Exception('Key ' . $indices . ' not found in source');
				}
			}
		}
		return $value;
	}

	/**
	 * This method can be used to store whatever local variables make sense into the parser, for later retrieval
	 * In the case of a FE plugin, it would be the piVars
	 *
	 * @param	array	$vars: array of values
	 * @param	boolean	$reset: true if self::$vars must be reset
	 * @return	void
	 */
	public static function setVars($vars, $reset = false) {
		if (is_array($vars)) {
			if ($reset) {
				self::$vars = $vars;
			} else {
				self::$vars = t3lib_div::array_merge_recursive_overrule(self::$vars, $vars);
			}
		}
	}

	/**
	 * This method can be used to store additional variables into the parser,
	 * that should not be mixed up with the local variables stored in self::$vars
	 *
	 * @param	array	$vars: array of values
	 * @param	boolean	$reset: true if self::$extraData must be reset
	 * @return	void
	 * @see		tx_expressions_parser::setVars()
	 */
	public static function setExtraData($data, $reset = false) {
		if (is_array($data)) {
			if ($reset) {
				self::$extraData = $data;
			} else {
				self::$extraData = t3lib_div::array_merge_recursive_overrule(self::$extraData, $data);
			}
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/expressions/class.tx_expressions_parser.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/expressions/class.tx_expressions_parser.php']);
}

?>