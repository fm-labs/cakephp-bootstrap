<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

use Cake\View\Helper;

abstract class BaseHelper extends Helper
{
    public static $defaultItemSize = "default"; // @TODO Use Helper config to assign default size

    protected function _mapSizeClass($size, $prefix = "")
    {
        switch ($size) {
            case "xs":
            case "sm":
            case "md":
            case "lg":
                break;
            default:
                return "";
        }

        return $prefix && $size ? $prefix . "-" . $size : $size;
    }

    protected function _mapTypeClass($type, $prefix = "")
    {
        switch ($type) {
            case "success":
                $type = "success";
                break;
            case "error":
            case "danger":
                $type = "danger";
                break;
            case "warn":
            case "warning":
                $type = "warning";
                break;
            case "info":
                $type = "info";
                break;
            case "primary":
                $type = "primary";
                break;
            default:
                $type = $type ?? "outline-secondary";
                break;
        }

        return $prefix ? $prefix . "-" . $type : $type;
    }
}
