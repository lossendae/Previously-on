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

trait ValidationResponseFormatter
{
    /**
     * Add validation error
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
}
