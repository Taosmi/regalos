<?php

namespace apis\v1;
use runPHP\ApiController, runPHP\Response, runPHP\RunException, runPHP\Logger;
use domain\Gift as oGift;

/**
 * Gift REST API.
 */
class gift extends ApiController {

    const IMG_DOMAIN = 'https://i.ibb.co';

    /**
     * Delete a gift.
     *
     * @param  array  $params  The request parameters.
     * @return Response        An Ok response.
     * @throws RunException    If the gift could not be deleted.
     */
    public function delete ($session, $params) {
        // Get a gift ID from the URL.
        $giftId = current($params);
        if (!$giftId) {
            throw new RunException(400, __('The gift ID is mandatory'));
        }
        // Check the gift exists.
        $giftRepo = $this->repository('domain\Gift');
        $gift = $giftRepo->findOne(array(
            'giftId' => eq($giftId)
        ));
        if (!$gift) {
            throw new RunException(400, __('The gift does not exist'));
        }
        // Check the current user is the gift owner.
        if ($gift->userId != $session->get('id')) {
            throw new RunException(400, __('The gift does not exist'));
        }
        // Delete the gift.
        $gift->status = 'delete';
        $giftRepo->select('status')->modify($gift);
        // Return Ok response.
        return new Response(array(
            'result' => 'ok'
        ));
    }

    /**
     * If a gift ID is provided as part of the URL, get this gift. If the 'owner'
     * parameter is provided, get all the gifts owned by the current user.
     * If no parameters are provided, get all the gifts that the current user
     * should see.
     *
     * @param  array  $params  The request parameters.
     * @return Response        A list of gifts.
     * @throws RunException    If the gift could not be retrieved.
     */
    public function get ($session, $params) {
        // Get input data.
        $id = current($params);
        $username = $this->inputGet('username');
        $owner = $this->inputGet('owner');
        // Search values.
        $offset = $this->inputGet('offset');

        // Get a gift repository.
        $giftRepo = $this->repository('domain\Gift');

        if ($id) {
            // Get a gift by ID.
            $gift = $giftRepo->findOne(array(
                'giftId' => eq($id)
            ));
            if (!$gift) {
                throw new RunException(400, __('The gift does not exist'));
            }
            // Check the privacy.
            if (($gift->privacy === 'private') and ($session->get('id') != $gift->userId)) {
                throw new RunException(400, __('The gift does not exist'));
            }
            // Return the gift.
            return new Response(array(
                'gift' => $gift
            ));
        } else if ($owner) {
            // Get the gifts owned by the current user.
            $list = $giftRepo->find(array(
                'status' => eq('active'),
                'userId' => eq($session->get('id'))
            ));
        } else {
            // Get other users gifts that current user may see.
            $list = $giftRepo->find(array(
                'status' => eq('active'),
                'userId' => ne($session->get('id')),
                'or' => array(
                    'privacy' => eq('public')//,  'privacy' => like('%'.Session::get('id').'%')
                )
            ), '"createdOn" desc');
            //'condition' => 'status = "active" and userId != "'.$userId.'" and (privacy = "public" or privacy like "%,'.$userId.',%")'.$userFilter,
            //$userFilter = $username ? ' and username like "'.$username.'%"' : '';
        }

        // Return the gifts and number of gifts.
        return new Response(array(
            'num' =>  count($list),
            'gifts' => $list ? $list : _('No gifts found')
        ), 200, array(
            'x-total-count: '.count($list)
        ));
    }

    /**
     * Add a new gift with the current user as owner and return the gift ID.
     *
     * @param  array  $params  The request parameters.
     * @return Response        The gift ID.
     * @throws RunException    If the gift could not be created.
     */
    public function post ($session, $params) {
        // Input data.
        $title = $this->inputGet('title');
        $description = $this->inputGet('description');
        $link = $this->inputGet('link');
        $privacy = $this->inputGet('privacy');
        $image = $this->inputGet('image');
        // Check the input data.
        if (!$title) {
            throw new RunException(400, __('The gift title is mandatory'));
        }
        // Store the image.
        if ($image) {
            // Get a name for the gift image and process the image raw data to a file on the server.
            $imgName = $session->get('name') . '_' . mt_rand() . '.' . $this->getImageFormat($image);
            $data = $this->getImage($image);
            file_put_contents(STATICS . '/imgs/gifts/' . $imgName, $data);
        }
        // Create a gift.
        $gift = new oGift();
        $gift->title = $title;
        $gift->description = $description;
        $gift->image = $image ? '/'.STATICS.'/imgs/gifts/'.$imgName : null;
        $gift->link = $link;
        $gift->privacy = $privacy;
        $gift->userId = $session->get('id');
        $gift->username = $session->get('name');
        $gift->status = 'active';
        // Store the gift.
        $giftRepo = $this->repository('domain\Gift');
        $gift = $giftRepo->add($gift);
        // Return the gift ID.
        return new Response(array(
            'id' => $gift->giftId
        ));
    }

    /**
     * Modify a gift.
     *
     * @param  array  $params  The request parameters.
     * @return Response        An Ok response.
     * @throws RunException    If something goes wrong.
     */
    public function put ($session, $params) {
        // Input data.
        $title = $this->inputGet('title');
        $description = $this->inputGet('description');
        $link = $this->inputGet('link');
        $privacy = $this->inputGet('privacy');
        $image = $this->inputGet('image');
        $giftId = current($params);
        // Check the gift ID.
        if (!$giftId) {
            throw new RunException(400, __('The gift ID is mandatory'));
        }
        // Check the gift already exists.
        $giftRepo = $this->repository('domain\Gift');
        $gift = $giftRepo->findOne(array(
            'giftId' => eq($giftId)
        ));
        if (!$gift) {
            return new Response(400, __('The gift is not available'));
        }
        // Check the gift owner is the current user.
        if ($gift->userId != $session->get('id')) {
            return new Response(401, __('The gift is not available'));
        }
print_r($image);
print_r($description);
        // Store the image.
        if ($image) {
            // Get the image name.
            if (strpos($image, self::IMG_DOMAIN) === 0) {
                // The image is already in the server.
                $imgName = substr($image, strrpos($image, '/') + 1);
            } else {
                // Get a new image name.
                $imgName = $session->get('name') . '-' . mt_rand() . '.' . $this->getImageFormat($image);
            }
            // Get the image raw data and save it on the server.
            $data = $this->getImage($image);
            // file_put_contents(STATICS . '/imgs/gifts/' . $imgName, $data);
            $this->storeImage($imgName, $data);
        }
        // Modify a gift.
        $gift->title = $title;
        $gift->description = $description;
        $gift->link = $link;
        $gift->privacy = $privacy;
        $gift->image = $image ? self::IMG_DOMAIN.$imgName : null;
        $gift->createdOn = date('Y-m-d H:i:s');
        // Store the gift.
        $giftRepo->select('title,description,link,privacy,image,createdOn')->modify($gift);
        // Return an Ok response.
        return new Response(array(
            'result' => 'ok'
        ));
    }


    /**
     * Get the image data from an uploaded image or from a URL.
     *
     * @param $image   Image uploaded or URL.
     * @return string  Image raw data.
     */
    private function getImage($image) {
        if (strpos($image, self::IMG_DOMAIN) === 0) {
            // Get the image data from URL.
            $data = file_get_contents($image);
        } else {
            // Get the image data uploaded from browser.
            //$image = urldecode($image);
            list($mime, $data) = explode(';', $image);
            list($type, $format) = explode('/', $mime);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
        }
        return $data;
    }

    private function getImageFormat($image) {
        if (strrpos($image, self::IMG_DOMAIN) === 0) {
            // Get the image format from a URL.
            return substr($image, -(strlen($image) - strrpos($image, '.')));
        } else {
            // Get the image format from data uploaded from browser.
            $image = urldecode($image);
            list($mime, $data) = explode(';', $image);
            list($type, $format) = explode('/', $mime);
            return $format;
        }
    }

    private function storeImage ($name, $data) {
        $url = "https://api.imgbb.com/1/upload";
        $fields = [
            'name' => $name,
            'key' => '0ac831068603484e426fda013b57e80e',
            'image' => base64_encode($data)
        ];
        // url-ify the data for the POST.
        $fields_string = http_build_query($fields);
        // Open connection.
        $ch = curl_init();
        // Set the url, number of POST vars, POST data.
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        // So that curl_exec returns the contents of the cURL; rather than echoing it.
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        // Execute post.
        $result = curl_exec($ch);
        echo $result;
    }

}