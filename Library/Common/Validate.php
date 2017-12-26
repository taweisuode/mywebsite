<?php
class Validate {
	//用于计算多字节字符串长度
	private $encoding	= 'UTF-8';

	private $limitDescs	= array (
		'minvalue'	=> '值不能小于',
		'maxvalue'	=> '值不能大于',
		'min'		=> '值不能小于',
		'max'		=> '值不能大于',
		'count'		=> '个数必须等于',
		'maxcount'	=> '个数不能大于',
		'len'		=> '长度必须等于',
		'mb_len'	=> '长度必须等于',
		'minlen'	=> '长度不能小于',
		'mb_minlen'	=> '长度不能小于',
		'maxlen'	=> '长度不能大于',
		'mb_maxlen'	=> '长度不能大于',
		'width'		=> '占用字节数必须等于',
		'minwidth'	=> '占用字节数不能小于',
		'maxwidth'	=> '占用字节数不能大于',
		'enum'		=> '必须是所列值中的一个: ',
	);

	const RETURN_NULL	= true;

	public function __construct() {
        include LIBRARY_PATH."/Exception/ValidateException.php";

	}

	public function check($rules, $data, $isReturnNull = false, $suffix = '') {
		if (!is_array($rules)) {
			throw new ValidateException('param $rules', ErrorCode::PARAM_NOT_ARRAY);
		}

		if (!empty($rules['any']) && is_array($rules['any'])) {
			$any	= false;
			$anyarr	= $rules['any'];
			foreach ($anyarr as $col) {
				if (isset($data[$col]) && ($data[$col] !== '')) {
					$any	= true;
					break;
				}
			}

			if (!$any) {
				throw new ValidateException(implode(', ', $rules['any']), ErrorCode::PARAM_MUST_APPLY_ANY);
			}
			unset($rules['any']);
		}

		$refl	= new ReflectionClass(__CLASS__);
		$retarr	= array ();
		foreach ($rules as $rule => $default) {
			//对嵌套数组的参数验证
			if (is_array($default) && is_string($rule) && (strpos($rule, '#') === false)) {
				//嵌套参数验证, 如果键不包含 ".$", 则认为嵌套的是联合数组的验证, 直接用嵌套数组递归调用即可
				if (strpos($rule, '.$') === false) {
					if (isset($retarr[$rule])) {
						$retarr[$rule]	= $this->check($default, $retarr[$rule], $isReturnNull, $suffix . $rule . '.');
					}
				} else {
				//嵌套索引数组验证, 需要循环待验证数组, 对其中每一项使用配置的规则进行验证
					$rule	= rtrim($rule, '.$');
					$subdata= isset($retarr[$rule]) && is_array($retarr[$rule]) ? $retarr[$rule] : array ();
					$suffix	= $suffix . $rule . '.';
					foreach ($subdata as $key => $item) {
						$retarr[$rule][$key]	= $this->check($default, $subdata[$key], $isReturnNull, $suffix . $key . '.');
					}
				}

				continue;
			}

			//支持不设置默认值的情况, 此时单元值为规则
			$notSettedDefault	= is_int($rule);
			if ($notSettedDefault) {
				$rule	= $default;
			}

			$parts	= explode('#', $rule, 2);
			if (empty($parts[0]) || empty($parts[1])) {
				continue;
			}
			$col	= $parts[0];	//字段名
			if ($col === '$') {
				$value	= $data;
			} else {
				if ($notSettedDefault) {
					$value	= isset($data[$col]) ? $data[$col] : null;
				} else {
					$value	= isset($data[$col]) ? $data[$col] : $default;
				}
				if (is_string($value)) {
					$value	= trim($value);
				}
				$value	= array ($value);
			}

			$rarr	= explode('|', $parts[1]);
			foreach ($value as $itemkey => $itemval) {
				$descArr	= array();
				foreach ($rarr as $rk=>$rval) {
					if (strpos($rval, 'desc') !== false) {
						$tmpArr	= explode(':', $rval, 2);
						if (!empty($tmpArr[1])) {
							$descArr[$itemkey] = $tmpArr[1];
						}
					}
				}
				foreach ($rarr as $rk => $rval) {
					$kv		= explode(':', $rval, 2);
					if (empty($kv[0]) || $kv[0] == 'desc') {
						continue;
					}

					$ruleName	= $kv[0];
					$method		= 'check_' . $ruleName;
					if (!$refl->hasMethod($method)) {
						throw new ValidateException('Validate Rule: ' . $ruleName, ErrorCode::PARAM_NOT_SUPPORT);
					}

					$limit	= isset($kv[1]) ? $kv[1] : null;
					$ret	= $this->$method($itemval, $limit);

					if ($ret !== true) {
						if (($col === '$')) {
							$col	= is_int($itemkey) && empty($suffix) ? ($col . $itemkey) : $itemkey;
						}
						$desc		= !empty($descArr[$itemkey]) ? $descArr[$itemkey] : $suffix . $col;
						$limitDesc	= '';
						if (isset($limit)) {
							$limitDesc	= !empty($this->limitDescs[$ruleName]) ? ($this->limitDescs[$ruleName] . $limit) : ($ruleName . ':' . $limit);
						}
						throw new ValidateException($desc, $ret);
					}
				}

				//$isReturnNull为true, 或者$itemval不为空时, 都需要把数据加入返回字段数组里
				if ($isReturnNull || !is_null($itemval)) {
					$retarr[($col === '$') ? $itemkey : $col]	= $itemval;
				}
			}
		}

		return $retarr;
	}

	private function check_required($value) {
		return isset($value) && ($value !== '') ? true : ErrorCode::PARAM_REQUIRED;
	}

	private function check_notempty($value) {
		return !empty($value) ? true : ErrorCode::PARAM_EMPTY;
	}

	private function check_int(&$value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		if (is_int($value)) {
			return true;
		}

		if (is_numeric($value) && (strpos($value, '.') === false)) {
			$value	= (int)$value;
			return true;
		}

		return ErrorCode::PARAM_NOT_INT;
	}

	private function check_multi_int(&$value, $isSplit) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		//兼容数组中的多个ID
		if (is_array($value)) {
			foreach ($value as $v) {
				if (!preg_match('/^[+-]?\d+$/', $v)) {
					return ErrorCode::PARAM_NOT_MULTI_INT;
				}
			}
			$value	= array_map('intval', $value);

			return true;
		}

		//逗号隔开的多个ID
		if (!preg_match('/^[+-]?\d+(,[+-]?\d+)*$/', $value)) {
			return ErrorCode::PARAM_NOT_MULTI_INT;
		}

		if (!empty($isSplit)) {
			$isBool	= $this->check_bool($isSplit);
			if (($isBool === true) && $isSplit) {
				$value	= array_map('intval', explode(',', $value));
			}
		}

		return true;
	}

	private function check_seq_int(&$value, $isSplit) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		if (!preg_match('/^\d+(\.\d+)*$/', $value)) {
			return ErrorCode::PARAM_NOT_MULTI_INT;
		}

		if (!empty($isSplit)) {
			$isBool	= $this->check_bool($isSplit);
			if (($isBool === true) && $isSplit) {
				$value	= array_map('intval', explode('.', $value));
			}
		}

		return true;
	}

	private function check_float(&$value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		if (is_float($value)) {
			return true;
		}

		if (is_numeric($value)) {
			$value	= (float)$value;
			return true;
		}

		return ErrorCode::PARAM_NOT_FLOAT;
	}

	private function check_number(&$value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		if (!is_numeric($value)) {
			return ErrorCode::PARAM_NOT_NUMBER;
		}

// 		if (strpos($value, '.') === false) {
// 			$value	= (int)$value;
// 		} else {
			$value	= (float)$value;
// 		}

		return true;
	}

	private function check_bool(&$value) {
		//check_bool 不判断 $value === '', 因为调用端传过来false之后会被PHP解析成空字符串""
		if (!isset($value)) {
			return true;
		}

		$type	= gettype($value);
		if ($type === 'boolean') {
			return true;
		} else if ($type === 'string') {
			if ($value === '0' || $value === '1') {
				$value	= (bool)$value;
				return true;
			}
			if ($value === 'true') {
				$value	= true;
				return true;
			}
			if (($value === 'false') || ($value === '')) {
				$value	= false;
				return true;
			}

			return ErrorCode::PARAM_NOT_BOOL;
		} else if ($type === 'integer') {
			if ($value === 0 || $value === 1) {
				$value	= (bool)$value;
				return true;
			}

			return ErrorCode::PARAM_NOT_BOOL;
		}

		return ErrorCode::PARAM_NOT_BOOL;
	}

	private function check_string($value) {
		if (!isset($value)) {
			return true;
		}

		return is_string($value) ? true : ErrorCode::PARAM_NOT_STRING;
	}

	private function check_multi_str(&$value, $isSplit) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		//兼容数组中的多个字符串
		if (is_array($value)) {
			foreach ($value as $v) {
				if (!is_string($v)) {
					return ErrorCode::PARAM_NOT_MULTI_STR;
				}
			}

			return true;
		}

		if (!is_string($value) || !preg_match('/^.+(,.+)*$/', $value)) {
			return ErrorCode::PARAM_NOT_MULTI_STR;
		}

		if (!empty($isSplit)) {
			$isBool	= $this->check_bool($isSplit);
			if (($isBool === true) && $isSplit) {
				$value	= array_map('trim', explode(',', $value));
			}
		}

		return true;
	}

	private function check_no_chinese($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$preg = preg_match('/^[a-zA-Z 0-9\.\?\!\~\`\(\)\@\#\$\%\^\&\*\……\+\=\、\:\：\"\”\“\’\'\'\,\，\-]+$/', $value);
		return $preg ? true : ErrorCode::PARAM_NOT_NO_CHINESE;
	}

	private function check_word($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		return preg_match('/^\w+$/', $value) ? true : ErrorCode::PARAM_NOT_WORD;
	}

	private function check_multi_word($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		return preg_match('/^\w+(,\w+)*$/', $value) ? true : ErrorCode::PARAM_NOT_MULTI_WORD;
	}

	private function check_print_chars($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		return preg_match('/^[\x21-\x7e]+$/', $value) ? true : ErrorCode::PARAM_NOT_PRINT_CHARS;
	}

	private function check_alphanum($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		return preg_match('/^[a-zA-Z0-9]+$/', $value) ? true : ErrorCode::PARAM_NOT_ALPHANUMBER;
	}

	private function check_alpha($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		return preg_match('/^[a-zA-Z]+$/', $value) ? true : ErrorCode::PARAM_NOT_ALPHA;
	}
	private function check_upper_case($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		return preg_match('/^[A-Z]+$/', $value) ? true : ErrorCode::PARAM_NOT_UPPER_CASE;
	}
	private function check_case_space($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}
		return preg_match('/^[A-Z ]+$/', $value) ? true : ErrorCode::PARAM_NOT_UPPER_CASE_SPACE;
	}

	private function check_array(&$value) {
		if (!isset($value)) {
			return true;
		}

		if (is_array($value)) {
			return true;
		}

		//兼容json格式
		$ret	= @json_decode($value, true);
		if (is_array($ret)) {
			$value	= $ret;
			return true;
		}

		return ErrorCode::PARAM_NOT_ARRAY;
	}

	private function check_count($value, $count) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($count);
		if ($isInt !== true) {
			return $isInt;
		}

		return (count($value) === $count) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_maxcount($value, $max) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isNumber	= $this->check_int($max);
		if ($isNumber !== true) {
			return $isNumber;
		}

		return (count($value) <= $max) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_object($value) {
		if (!isset($value)) {
			return true;
		}

		return is_object($value) ? true : ErrorCode::PARAM_NOT_OBJECT;
	}

	private function check_min($value, $min) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isNumber	= $this->check_number($min);
		if ($isNumber !== true) {
			return $isNumber;
		}

		return ($value >= $min) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_max($value, $max) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isNumber	= $this->check_number($max);
		if ($isNumber !== true) {
			return $isNumber;
		}

		return ($value <= $max) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_len($value, $len) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($len);
		if ($isInt !== true) {
			return $isInt;
		}

		return (strlen($value) === $len) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_mb_len($value, $len) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($len);
		if ($isInt !== true) {
			return $isInt;
		}

		return (mb_strlen($value, $this->encoding) === $len) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_minlen($value, $len) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($len);
		if ($isInt !== true) {
			return $isInt;
		}

		return (strlen($value) >= $len) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_mb_minlen($value, $len) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($len);
		if ($isInt !== true) {
			return $isInt;
		}

		return (mb_strlen($value, $this->encoding) >= $len) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_maxlen($value, $len) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($len);
		if ($isInt !== true) {
			return $isInt;
		}

		return (strlen($value) <= $len) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_mb_maxlen($value, $len) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($len);
		if ($isInt !== true) {
			return $isInt;
		}

		return (mb_strlen($value, $this->encoding) <= $len) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_width($value, $width) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($width);
		if ($isInt !== true) {
			return $isInt;
		}

		return ($this->getwidth($value) === $width) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_minwidth($value, $minwidth) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($minwidth);
		if ($isInt !== true) {
			return $isInt;
		}

		$width	= $this->getwidth($value);

		return ($width >= $minwidth) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_maxwidth($value, $maxwidth) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isInt	= $this->check_int($maxwidth);
		if ($isInt !== true) {
			return $isInt;
		}

		$width	= $this->getwidth($value);

		return ($width <= $maxwidth) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_minvalue($value, $min) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isNumber	= $this->check_number($min);
		if ($isNumber !== true) {
			return $isNumber;
		}

		return ($value >= $min) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_maxvalue($value, $max) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isNumber	= $this->check_number($max);
		if ($isNumber !== true) {
			return $isNumber;
		}

		return ($value <= $max) ? true : ErrorCode::PARAM_OUT_OF_RANGE;
	}

	private function check_enum($value, $enum) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$isString	= $this->check_string($enum);
		if ($isString !== true) {
			return $isString;
		}

		if (is_string($value)) {
			$vals	= explode(',', $value);
		} else if (!is_array($value)) {
			$vals	= array ($value);
		}
		$isCorrect	= true;
		$enums		= array_map('trim', explode(',', $enum));
		foreach ($vals as $v) {
			$v	= trim($v);
			if (!in_array($v, $enums)) {
				$isCorrect	= false;
				break;
			}
		}

		return $isCorrect ? true : ErrorCode::PARAM_INVALID_ENUM;
	}

	private function check_datetime(&$value, $isToTimestamp) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$datetime	= explode(' ', $value);
		if (count($datetime) !== 2) {
			return ErrorCode::PARAM_INVALID_DATETIME;
		}

		$isDate	= $this->check_date($datetime[0], false);
		if ($isDate !== true) {
			return $isDate;
		}

		$isTime	= $this->check_time($datetime[1], '');
		if ($isTime !== true) {
			return $isTime;
		}

		if (!empty($isToTimestamp)) {
			$isBool	= $this->check_bool($isToTimestamp);
			if (($isBool === true) && $isToTimestamp) {
				$value	= strtotime($value);
			}
		}

		return true;
	}

	private function check_multi_date(&$value, $isToTimestamp) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$dates	= explode(',', $value);
		foreach ($dates as $key => $d) {
			$ret	= $this->check_date($d, $isToTimestamp);
			if ($ret !== true) {
				return ErrorCode::PARAM_INVALID_MULTI_DATE;
			}

			$dates[$key]	= $d;
		}
		$value	= $dates;

		return true;
	}

	private function check_date(&$value, $isToTimestamp) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$match	= array ();
		if (!preg_match('/^([12]\d{3})-(\d{2})-(\d{2})$/', $value, $match)) {
			return ErrorCode::PARAM_INVALID_DATE;
		}

		$year	= $match[1];
		$month	= (int)$match[2];
		if (!($month >= 1 && $month <= 12)) {
			return ErrorCode::PARAM_INVALID_DATE;
		}

		//1,3,5,7,8,10,12月最大日期为31号; 4,6,9,11月日期最大为30号; 2月最大日期为28或29号, 和是否闰年有关
		$maxDay		= ($this->check_enum($month, '1,3,5,7,8,10,12') === true) ? 31 : 30;
		if ($month === 2) {
			$maxDay	= ((($year % 4 === 0) && ($year % 100 !== 0)) || ($year % 400 === 0)) ? 29 : 28;
		}
		$day		= (int)$match[3];
		if ($day > $maxDay) {
			return ErrorCode::PARAM_INVALID_DATE;
		}

		if (!empty($isToTimestamp)) {
			$isBool	= $this->check_bool($isToTimestamp);
			if (($isBool === true) && $isToTimestamp) {
				$value	= strtotime($value);
			}
		}

		return true;
	}

	private function check_time($value, $part) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$match		= array ();
		$pattern	= '';
		if (isset($part{0}) && ($part{0} === 'H')) {
			$pattern	.= '(\d{2})';	//时;
		}
		if (isset($part{1}) && ($part{1} === 'i')) {
			$pattern	.= ':(\d{2})';	//分;
		}
		if (isset($part{2}) && ($part{2} === 's')) {
			$pattern	.= ':(\d{2})';	//秒;
		}
		if (empty($pattern)) {
			$pattern	 = '(\d{2}):(\d{2}):(\d{2})';
		}

		if (!preg_match('/^' . $pattern . '$/', $value, $match)) {
			return ErrorCode::PARAM_INVALID_TIME;
		}

		$hour	= isset($match[1]) ? (int)$match[1] : 0;
		$minute	= isset($match[2]) ? (int)$match[2] : 0;
		$second	= isset($match[3]) ? (int)$match[3] : 0;
		if (!($hour >= 0 && $hour <= 23)) {
			return ErrorCode::PARAM_INVALID_TIME;
		}
		if (!($minute >= 0 && $minute <= 59)) {
			return ErrorCode::PARAM_INVALID_TIME;
		}
		if (!($second >= 0 && $second <= 59)) {
			return ErrorCode::PARAM_INVALID_TIME;
		}

		return true;
	}

	private function check_ipv4($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		if (!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $value)) {
			return ErrorCode::PARAM_INVALID_IPV4;
		}

		$dots	= explode('.', $value);
		foreach ($dots as $num) {
			$num	= (int)$num;
			if (!($num >= 0 && $num <= 255)) {
				return ErrorCode::PARAM_INVALID_IPV4;
			}
		}

		return true;
	}

	private function check_json(&$value, $decode) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		$ret	= @json_decode($value, true);
		if (is_null($value)) {
			return ErrorCode::PARAM_INVALID_JSON;
		}

		if (!empty($decode)) {
			$isBool	= $this->check_bool($decode);
			if (($isBool === true) && $decode) {
				$value	= $ret;
			}
		}

		return true;
	}

	private function check_email($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		if (!preg_match('/^([\w\.\-])+@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)+$/', $value)) {
			return ErrorCode::PARAM_INVALID_EMAIL;
		}

		return true;
	}

	private function check_multi_email(&$value, $isSplit) {
	    if (!isset($value) || $value === '') {
	        return true;
        }

        if (is_string($value)) {
            $value  = str_replace(array(',', ';'), ';', $value);
            $emails = explode(';', $value);
        } else if (is_array($value)) {
            $emails = $value;
        } else {
            return ErrorCode::PARAM_INVALID_MULTI_EMAIL;
        }

        $emails = array_map('trim', $emails);
        foreach ($emails as $e) {
            $ret = $this->check_email($e);
            if ($ret !== true) {
                return ErrorCode::PARAM_INVALID_MULTI_EMAIL;
            }
        }

        if (!empty($isSplit)) {
            $isBool	= $this->check_bool($isSplit);
            if (($isBool === true) && $isSplit) {
                $value = $emails;
            } else {
                $value = implode(',', $emails);
            }
        }

        return true;
    }

	private function check_mobile($value) {
		if (!isset($value) || ($value === '')) {
			return true;
		}

		if (!preg_match('/^1[3-9][0-9]{9}$/', $value)) {
			return ErrorCode::PARAM_INVALID_MOBILE;
		}

		return true;
	}

	private function getwidth($value) {
		$width	= 0;
		$len	= mb_strlen($value, $this->encoding);
		for ($i = 0; $i < $len; ++$i) {
			$char	 = mb_substr($value, $i, 1, $this->encoding);
			$width	+= (mb_strlen($char, $this->encoding) === strlen($char)) ? 1 : 2;
		}

		return $width;
	}
}
