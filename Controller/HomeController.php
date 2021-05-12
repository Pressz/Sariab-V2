<?php

class HomeController extends Controller {

    function IndexGET($q = '', $page = 1) {

        if ($q == '*')
            $q = '';

        if (!is_numeric($page) || $page < 1)
            $this->RedirectResponse(_Root . 'Home');

        $PostModel = $this->CallModel("Post");
        $Posts = $PostModel->GetHome(
            ['q'=> $q],
            $page
        );
        
        $PodcastModel = $this->CallModel("Podcast");
        $Podcasts = $PodcastModel->GetHome();

        $KeywordModel= $this->CallModel("Keyword");
        $Keywords= $KeywordModel->GetHome();

        $RoadModel= $this->CallModel("Road");
        $Roads= $RoadModel->GetHome();

        $Data = [
            "Title" => _AppName,
            "Models" => [
                'Posts'=> $Posts,
                'Podcasts'=> $Podcasts,
                'Keywords' => $Keywords,
                'Roads'=> $Roads,
            ],
            'PostsPage' => $page
        ];
        
        $this->Render('Index', $Data);
    }

    function RssGET($search_query = '') {

        $Model = $this->CallModel("Post");
        // TODO: From, to, limitation
        $Rows = $Model->GetHome([
            'q'=> $search_query
        ], 1);

        $Title = _AppName;
        $Link = _Root;
        $Description = 'Sariab RSS Feed.'; // TODO: Description

        header("Content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rss version=\"2.0\">

<channel>
<title>$Title</title>
<link>$Link</link>
<description>$Description</description>";

        for ($i = 0 ; $i < count($Rows) ; $i++)
        {
            $item_title = htmlspecialchars( $Rows[$i]['Title']);
            $item_link = _Root . 'Home/Redirect/' . $Rows[$i]['Id'];
            $item_abstract = htmlspecialchars($Rows[$i]['Abstract']);
            $pub_date = $pubDate= date("D, d M Y H:i:s T", strtotime($Rows[$i]['Submit']));;

            echo "<item>
    <title>$item_title</title>
    <link>$item_link</link>
    <description>$item_abstract</description>
    <pubDate>$pub_date</pubDate>
</item>
";
        }
        
echo '
</channel>

</rss>';
    }

    function ThankYouGET($search_query = '') {

        $Model = $this->CallModel("Support");
        // TODO: From, to, limitation
        $Supports = $Model->GetAll();

        $Data = [
            "Title" => _AppName . ' قدر دانی',
            "Model" => $Supports
        ];
        
        $this->Render('thankyou', $Data);
    }

    function PositionsGET() {

        $Model = $this->CallModel("Position");
        $Supports = $Model->GetActive();

        $Data = [
            "Title" => _AppName . ' موقعیت‌های همکاری',
            "Model" => $Supports
        ];
        
        $this->Render('positions', $Data);
    }

    function RedirectGET($Id)
    {
        setcookie('CLIENT_VIEW',
        (isset($_COOKIE['CLIENT_VIEW']) ? $_COOKIE['CLIENT_VIEW'] : '') .
        '<' . $Id . '>'
        , time() + (86400 * 30), "/");

        $Model = $this->CallModel("Post");
        $Rows = $Model->GetItemByIdentifier([
            'Id'=> $Id
        ]);
        $this->RedirectResponse($Rows[0]['Canonical']);
    }

    function ViewGET($Id, $RoadId = null) {

        $Model = $this->CallModel("Post");
        $Rows = $Model->GetVerifiedItemByIdentifier([
            'Id'=> $Id
        ]);

        $Data = [
            "Title" => $Rows[0]['Title'],
            "RoadId" => $RoadId,
            "Model" => $Rows[0]
        ];

        if ($RoadId != null)
        {
            $RoadMapModel = $this->CallModel('Roadmap');
            $LeadLagItems = $RoadMapModel->GetLeadLagItemIds([
                'RoadId' => $RoadId,
                'PostId' => $Id
            ]);
            
            foreach ($LeadLagItems as $LeadOrLag)
            {
                if ($LeadOrLag['Location'] == 'Next')
                    $Data['Navigation']['Next'] = $LeadOrLag['PostId'];
                else  if ($LeadOrLag['Location'] == 'Previous')
                    $Data['Navigation']['Previous'] = $LeadOrLag['PostId'];
            }
        }
        
        $this->Render('view', $Data, true);
    }

    function RoadmapGET($Id) {

        $Model = $this->CallModel("Road");
        $Rows = $Model->GetItemByIdentifier([
            'Id'=> $Id
        ]);

        $RoadmapModel = $this->CallModel("Roadmap");
        $Roadmaps = $RoadmapModel->GetPostsByRoadId([
            'Id'=> $Id
        ]);

        $Data = [
            "Title" => $Rows[0]['Title'],
            "Road" => $Rows[0],
            "Posts" => $Roadmaps
        ];
        
        $this->Render('Roadmap', $Data);
    }

    function RulesGET()
    {
        // Read the file
        $filename = "docs/rules/public.txt";
        $publicrules = fopen($filename, "r") or die("فایل قوانین پیدا نشد!");
        $rules = str_repeat("       ",5) . "[ " . _Root . $filename . " ]" . str_repeat("\n",5);
        $rules .= fread($publicrules,filesize($filename));
        fclose($publicrules);

        // Render the view
        $Data = [
            'Title' => 'قوانین ساریاب',
            'Rules' => $rules
        ];

        $this->Render('Rules', $Data);
    }

    function SubmitGET($Auth = false)
    {
        $ExternalWriter = null;
        $CheckAuth = $this->CheckAuth($Auth);
        if ($CheckAuth
            and isset($CheckAuth['submit_post'])
            and $CheckAuth['submit_post']
            ) // Check permission to send post as verified author
            $ExternalWriter = $CheckAuth['human'];

        $Data = [
            'Title' => 'ارسال محتوا برای ساریاب',
            'ExternalWriter' => $ExternalWriter
        ];

        $this->Render('Submit', $Data);
    }


    function SubmitPOST()
    {
        $Model = $this->CallModel('Post');

        $IsExternalWriter = true;
        $CheckAuth = $this->CheckAuth(false);
        if (
            $CheckAuth
            and isset($CheckAuth['submit_post'])
            and $CheckAuth['submit_post']
        ) // Check permission to send post as verified author
        {
            $Publisher = '@' . $CheckAuth['human'];
            $IsExternalWriter = false;
        }
        else
        {
            $_POST['Publisher'] = StringFunctions::remove_all_non_alpha_numeric($_POST['Publisher']);
        }

        $Model->SubmitPost([
            'Title' => $_POST['Title'],
            'Language' => $_POST['Language'],
            'Publisher' => $IsExternalWriter ? $_POST['Publisher'] : $Publisher,
            'Abstract' => $_POST['Abstract'],
            'Canonical' => $_POST['Canonical'],
            'IsExternalWriter' => $IsExternalWriter
        ]);

        $Data = [
            'Title' => 'ارسال محتوا برای ساریاب',
            'Message' => 'محتوا با موفقیت ثبت شد و در انتظار تایید تحریریه است! از شما ممنونیم.'
        ];
        $this->Render('Submit', $Data);
    }

    function BlogGET()
    {
        $Model = $this->CallModel("Proclamation");
        $Proclamations = $Model->GetBlog();

        $Data = [
            "Title" => _AppName . ' بلاگ',
            "Model" => $Proclamations
        ];
        
        $this->Render('Blog', $Data);

    }

    function FeedbackGET($Type = '', $Details = '') {

        if ($Type == 'Post' && $Details != '')
        {
            $PostModel = $this->CallModel("Post");

            $Rows = $PostModel->GetVerifiedItemByIdentifier([
                'Id' => $Details
            ]);

            if (count($Rows) > 0)
            {
                $Row = $Rows[0];

                $PostTitle = $Row['Title'];

                $Data = [
                    'Title' => 'ثبت گزارش برای «' . $PostTitle . '»',
                    'FeedbackTitle' => 'ثبت گزارش برای «' . $PostTitle . '»',
                    'FeedbackMeta' => $Type.'#'.$Details,
                    'FeedbackUrl' => _Root
                ];
            }

            // If post was removed or was private
            else
            {
                $Data = [
                    'Title' => 'ثبت بازخورد در مورد پست حذف شده',
                    'FeedbackTitle' => 'ثبت گزارش برای پست ناشناس',
                    'FeedbackMeta' => $Type.'#'.$Details,
                    'FeedbackUrl' => _Root
                ];
            }
        }
        else
        {
            $Data = [
                'Title' => 'ثبت بازخورد جدید',
                'FeedbackUrl' => _Root
            ];
        }

        $this->Render('Feedback', $Data);
    }

    function FeedbackPOST() {

        $Model = $this->CallModel("Feedback");

        $Model->FeedbackInsert([
            'Status' => $_POST['Status'],
            'Contact' => $_POST['Contact'],
            'Message' => $_POST['Message'],
            'Url' => $_POST['Url'],
            'Meta' => $_POST['Meta']
        ]);

        $Data = [
            'Title' => 'بازخورد ثبت شد',
            'Message' => 'ممنونیم!',
            'FeedbackUrl' => $_POST['Url']
        ];

        $this->Render('Feedback', $Data);
    }

}

