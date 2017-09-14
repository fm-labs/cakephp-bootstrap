<?php

namespace Bootstrap\View\Helper;

use Cake\View\Helper;

abstract class BaseHelper extends Helper
{
    public static $defaultItemSize = "default";

    protected function _mapSizeClass($size, $prefix = "")
    {
        switch ($size) {
            case "xs":
            case "sm":
            case "md":
            case "lg":
                break;
            default:
                break;
        }

        return ($prefix) ? $prefix . "-" . $size : $size;
    }

    protected function _mapTypeClass($type, $prefix = "")
    {
        switch ($type) {
            case "success":
                $type = "success"; break;
            case "error":
            case "danger":
                $type = "danger"; break;
            case "warn":
            case "warning":
                $type = "warning"; break;
            case "info":
                $type = "info"; break;
            case "primary":
                $type = "primary"; break;
            default:
                $type = ($type === null) ? "default" : $type; break;
                break;
        }

        return ($prefix) ? $prefix . "-" . $type : $type;
    }
}
