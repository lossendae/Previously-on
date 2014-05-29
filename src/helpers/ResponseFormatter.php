<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Lossendae\PreviouslyOn\Helpers;

trait ResponseFormatter
{
    /**
     * Add validation error
     * @todo Should be in a trait
     *
     * @param  string
     * @return array
     */
    protected function formatErrors($errors)
    {
        $result = array();

        foreach($errors as $key => $messages)
        {
            $result[] = array(
                'title' => $key, 'messages' => $messages,
            );
        }

        return $result;
    }

    /**
     * Return a success message from the controller.
     *
     * @param string $msg
     * @param array  $data
     * @return array
     */
    public function success($msg, $data = array())
    {
        $response = array(
            'success' => true, 'message' => $msg
        );
        if(!empty($data))
        {
            $response['data'] = $data;
        }

        return $response;
    }

    /**
     * Return a success message from the controller.
     *
     * @param string|array $return
     * @return array
     */
    public function failure($return)
    {
        $response = array('success' => false);
        if(is_array($return))
        {
            $response = array_merge($return, $response);
        }
        else
        {
            $response['message'] = $return;
        }

        return $response;
    }
}
