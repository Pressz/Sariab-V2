<?php
class ApiController extends Controller {

    private function ReturnJson($Data) {
        header('Content-Type: application/json');
        $this->Render("Blank", json_encode($Data), true);
    }
    private function GetJsonInput() {
        $inputJSON = file_get_contents('php://input');
        $client_input = json_decode($inputJSON, TRUE);
        return $client_input;
    }

    // ==================================================

    function PostsGET($search_query = '') {
        $Model = $this->CallModel("Post");
        // TODO: From, to, limitation, and pagination
        $Rows = $Model->GetHome([
            'q'=> $search_query
        ], 1);
        $this->ReturnJson($Rows);
    }

    function NewPostGET() {
        $Model = $this->CallModel("Post");
        $class_vars = get_class_vars(get_class($Model));
        foreach ($class_vars as $name => $value)
            $output[$name] = gettype($name);
        $this->ReturnJson($output);
    }

    function NewPostPOST() {
        // TODO: Authorization
        // TODO: Authentication

        $post = $this->GetJsonInput();
        if ($post == null)
        {
            throw new BadRequestException();
            $this->ReturnJson("Bad input.");
            return;
        }

        // Check permission to send post as verified author
        $post['IsExternalWriter'] = true;
        $post['Publisher'] = '';
        $CheckAuth = $this->CheckAuth(false);
        if (
            $CheckAuth
            and isset($CheckAuth['submit_post_with_bot'])
            and $CheckAuth['submit_post_with_bot']
        )
        {
            $post['Publisher'] = '@' . $CheckAuth['human'];
            $post['IsExternalWriter'] = false;
        }

        $Model = $this->CallModel("Post");
        try
        {
            $Model->SubmitPost($post);
        }
        catch (Exception $ex)
        {
            throw new BadRequestException();
            $this->ReturnJson("Submit failed.");
            return;
        }
        $this->ReturnJson($this->GetJsonInput());
    }
}