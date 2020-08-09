<?php

class Visit extends Model {

    function TopUsers() {
        $Query = 'SELECT
        COUNT(*) as TotalRequests, `CLIENT_TRACK`, `HTTP_USER_AGENT`
        FROM `Visits`
        WHERE `Submit` > DATE_ADD(NOW(), INTERVAL -7 DAY) -- LIMIT FOR 7 DAYS
        GROUP BY `CLIENT_TRACK`, `HTTP_USER_AGENT`
        ORDER BY TotalRequests DESC
        LIMIT 10';
        $Result = $this->DoSelect($Query);
        return $Result;
    }

    function UserStory($Values) {
        $Query = 'SELECT `Submit`, REQUEST_URI as Uri,
        `PHP_AUTH_USER`, `PHP_AUTH_USER`,
        `HTTP_REFERER`
        FROM `Visits`
        -- TODO: Difference with prev step (To determine waiting time)
        WHERE CLIENT_TRACK=:CLIENT_TRACK
        AND `Submit` > DATE_ADD(NOW(), INTERVAL -7 DAY) -- LIMIT FOR 7 DAYS
        ORDER BY `Id` DESC';
        $Result = $this->DoSelect($Query, $Values);
        return $Result;
    }

    function DailyGroupedVisitCount() {

        $temp = 'create temporary table dailyhours (
            DayNumber smallint,
            HourNumber tinyint
        );
        insert into dailyhours values (0,24), (0,1),
        (0,2), (0,3), (0,4), (0,5), (0,6), (0,7), (0,8), (0,9),
        (0,10), (0,11), (0,12), (0,13), (0,14), (0,15), (0,16),
        (0,17), (0,18), (0,19), (0,20), (0,21), (0,22), (0,23);
        update dailyhours set DayNumber = 1 where HourNumber <= HOUR(NOW());
        ';
        $this->DoQuery($temp);

        $Query = 'SELECT
        CONCAT(\'ساعت \', A.HourNumber % 24) as HourNumber,
        IFNULL(TotalVisits,0) as TotalVisits FROM
        dailyhours A left outer join (
            SELECT
            HourNumber,
            COUNT(*) as TotalVisits
            FROM
            (
                SELECT
                distinct CLIENT_TRACK,
                HOUR(`Submit`) AS HourNumber
                FROM `Visits`
                WHERE `Submit` > DATE_ADD(NOW(), INTERVAL -23 HOUR) -- Limit for 1 days
            ) as AliasOfFirstSelect
            GROUP BY
            HourNumber
        ) B on B.HourNumber = A.HourNumber
        order by A.DayNumber ASC, A.HourNumber ASC
        ';
        $Result = $this->DoSelect($Query);
        return $Result;

    }

    function GroupedVisitCount() {
        $Query = 'SELECT
        CONCAT(\'هفته \', WeekNumber) as WeekNumber,
        COUNT(*) as TotalVisits
        FROM
        (
            SELECT
            distinct CLIENT_TRACK,
            YEARWEEK( `Submit`) AS WeekNumber
            FROM `Visits`
            WHERE `Submit` > DATE_ADD(NOW(), INTERVAL -90 DAY) -- Limit for three monthes
        ) as AliasOfFirstSelect
        GROUP BY
        WeekNumber';
        $Result = $this->DoSelect($Query);
        return $Result;
    }

    function GroupedVisitCountByAgent() {
        $Query = 'SELECT
        COUNT(*) as TotalVisits,
        HTTP_USER_AGENT as Agent
        FROM `Visits`
        WHERE `Submit` > DATE_ADD(NOW(), INTERVAL -90 DAY) -- Limit for three monthes
        GROUP BY `HTTP_USER_AGENT`
        ORDER BY TotalVisits DESC
        LIMIT 5
        ';
        $Result = $this->DoSelect($Query);
        return $Result;
    }
    
    function PostsVisitCountByAddress() {
        $Query = 'SELECT
        COUNT(*) as TotalVisits,
        Posts.Id,
        Posts.Title
        FROM `Visits`
        LEFT OUTER JOIN `Posts`
        ON Posts.Id = SUBSTRING(REQUEST_URI FROM POSITION(\'Redirect\' in REQUEST_URI) + 9 /* FOR 0 */)
        WHERE Visits.`Submit` > DATE_ADD(NOW(), INTERVAL -90 DAY) -- Limit for three monthes
        AND `REQUEST_URI` LIKE \'%HOME/REDIRECT%\'
        GROUP BY Posts.Id
        ORDER BY TotalVisits DESC, Posts.Id DESC
        LIMIT 50
        ';
        $Result = $this->DoSelect($Query);
        return $Result;
    }
}