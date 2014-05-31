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
     * Return a success message from the controller.
     *
     * @param array $return
     * @return array
     */
    public function success($return = array())
    {
        return array_merge(array('success' => true), $return);
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
