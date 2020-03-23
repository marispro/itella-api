<?php
// TODO: write docs
namespace Mijora\Itella;

class Helper
{
  public static function keyExists($key, &$arr)
  {
    if (!$arr || !is_array($arr)) {
      return false;
    }
    return (isset($arr[$key]) || array_key_exists($key, $arr));
  }

  /**
   * Generates reference code for COD using supplied ID (usualy order iD). ID must be min. 3 characters long for correct calculation
   * @param int|string $id
   */
  public static function generateCODReference($id)
  {
    // TODO: make sure $id is at least 2 symbols
    $weights = array(7, 3, 1);
    $sum = 0;
    $base = str_split(strval(($id)));
    $reversed_base = array_reverse($base);
    $reversed_base_length = count($reversed_base);
    for ($i = 0; $i < $reversed_base_length; $i++) {
      $sum += $reversed_base[$i] * $weights[$i % 3];
    }
    $checksum = (10 - $sum % 10) % 10;
    return implode('', $base) . $checksum;
  }

  public static function fixPhoneNumber($phone, $country_code)
  {
    // prep number
    $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    $phone = str_replace(['-', '+'], '', $phone);

    switch (strtoupper($country_code)) {
      case 'LT':
        if ($phone[0] == 6 && strlen($phone) == 8) {
          $phone = '370' . $phone;
        }

        $pos = strpos($phone, '86');
        if ($pos === 0) {
          $phone = substr_replace($phone, '3706', $pos, strlen('86'));
        }
        break;
      case 'LV':
        if ($phone[0] == 2 && strlen($phone) == 8) {
          $phone = '371' . $phone;
        }
        break;
      case 'EE':
        $length = strlen($phone);
        if ($phone[0] == 5 && ($length == 7 || $length == 8)) {
          $phone = '372' . $phone;
        }
        if ($phone[0] == 8 && $length == 8) {
          $phone = '372' . $phone;
        }
        break;

      default:
        // do nothing
        break;
    }

    return '+' . $phone;
  }

  public static function get_class_name($obj)
  {
    if ($obj == null) {
      return null;
    }
    $classname = get_class($obj);
    if ($pos = strrpos($classname, '\\')) {
      return substr($classname, $pos + 1);
    }
    return $classname; // no namespace
  }
}
