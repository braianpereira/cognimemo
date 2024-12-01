<?php
namespace App\Helpers;
function periodToBr($period) {
    switch ($period) {
        case 'daily': return "Diariamente";
        case 'weekly': return "Semanalmente";
        case 'monthly': return "Mensalmente";
        case 'yearly': return "Anualmente";
        default: return "-";
    }
}
