<?php
include_once('Libs/imgGenerator.php');
class MediaController extends Controller {
    function PostCardGET($id) {
        if (!headers_sent())
            foreach (headers_list() as $header)
                header_remove($header);
        $Model = $this->CallModel("Post");
        $Rows = $Model->GetVerifiedItemByIdentifier([
            'Id'=> $id
        ]);
        if (sizeof($Rows) == 0)
            throw new NotFoundException();
        header("Content-type: image/png");
        imagepng((new ImageGenerator())->PostCard($Rows[0]['Title'], $Rows[0]['Publisher']));
    }
}