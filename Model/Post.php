<?php

class Post extends Model{
    
    public $Title;
    public $Language;
    protected $Meta;
    protected $Publisher;
    protected $IsVerified;
    protected $IsExternalWriter;
    public $Canonical;
    public $Abstract;


    function GetHome($Values, $page) {
        $MaxItems = 30;
        $Offset = ($page - 1) * $MaxItems;

        $Query = "SELECT *
        FROM `Posts`
        WHERE
        (`Title` LIKE CONCAT('%', :q, '%')
        OR `Meta` LIKE CONCAT('%', :q, '%')
        OR :q LIKE '')
        AND `IsVerified` = 1
        ORDER BY `Submit` DESC
        LIMIT $Offset,$MaxItems";

        return $this->DoSelect($Query, $Values);
    }
    
    function GetVerifiedItemByIdentifier($Values) {
        $Query = "SELECT *
        FROM `Posts`
        WHERE `Id` = :Id
        AND `IsVerified` = 1
        LIMIT 1";

        return $this->DoSelect($Query, $Values);
    }

    function SubmitPost($Values) {
        $Query = "INSERT INTO `Posts`
        (`Publisher`, `Title`, `Canonical`, `Abstract`, `IsExternalWriter`, `IsVerified`, `Submit`, `Meta`, `Language`)
        VALUES (:Publisher, :Title, :Canonical, :Abstract, :IsExternalWriter, b'0', NOW(), '', :Language);
        ";
        $this->DoQuery($Query, $Values);
    }

    // === Based on a pattern ===
    function DescribeTable() {
        return $this->DoSelect("DESCRIBE `Posts`");
    }
    function GetAdminPanelItems($Values = null) {

        $Query = 'SELECT
        CONCAT(\'<a class="btn btn-sm btn-default" href="' . _Root . 'Admin/Items/Post/\', id , \'">\', \'Edit\', \'</a>\') as Edit,
        CONCAT(\'<a class="btn btn-sm btn-default" href="' . _Root . 'Home/View/\', id , \'">\', \'View\', \'</a>\') as View,
        Id
        , Submit
        ,`Title`
        ,`Publisher`
        FROM `Posts`
        WHERE `IsExternalWriter` = 0
        AND `IsVerified` = 1
        ORDER BY `Id` DESC';

        return $this->DoSelect($Query);
    }
    function GetItemByIdentifier($Values) {
        $Query = "SELECT *
        FROM `Posts`
        WHERE `Id` = :Id
        LIMIT 1";

        return $this->DoSelect($Query, $Values);
    }
}