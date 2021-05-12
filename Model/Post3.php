<?php

include_once 'Post.php';
class Post3 extends Post {

    function GetAdminPanelItems($Values = null) {

        $Query = 'SELECT
        CONCAT(\'<a class="btn btn-sm btn-default" href="' . _Root . 'Admin/Items/Post3/\', id , \'">\', \'Edit\', \'</a>\') as Edit,
        CONCAT(\'<a target="_blank" class="btn btn-sm btn-default" href="\', `Canonical` , \'">\', \'View\', \'</a>\') as View,
        Id
        , Submit
        ,`Title`
        ,`Publisher`
        FROM `Posts`
        WHERE `IsExternalWriter` = 0
        AND `IsVerified` = 0
        ORDER BY `Id` DESC';

        return $this->DoSelect($Query);
    }
}