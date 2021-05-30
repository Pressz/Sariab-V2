<?php

class Maintenance extends Model {
    function BatchMoveBotsVisits() {

        $Clause = '
        HTTP_USER_AGENT LIKE \'%Google-Apps-Script%\'
        OR HTTP_USER_AGENT LIKE \'%Googlebot%\'
        OR HTTP_USER_AGENT LIKE \'%bingbot%\'
        OR HTTP_USER_AGENT LIKE \'%Shields.io%\'
        OR HTTP_USER_AGENT LIKE \'%facebookexternalhit%\'
        OR HTTP_USER_AGENT LIKE \'%HubSpot%\'
        OR HTTP_USER_AGENT LIKE \'%Linguee%\'
        ';

        $Query = '
        START TRANSACTION;
        INSERT INTO BotsVisits SELECT * FROM Visits WHERE ' . $Clause . ';
        DELETE FROM Visits WHERE ' . $Clause . ';
        COMMIT;
        ';

        $Result = $this->DoQuery($Query);
        return $Result;
    }
}