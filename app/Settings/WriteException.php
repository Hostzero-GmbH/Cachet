<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Settings;

use Throwable;

/**
 * This is the write exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class WriteException extends SettingsException
{
    /**
     * Create a new write exception instance.
     *
     * @param \Exception $e
     *
     * @return void
     */
    public function __construct(Throwable $e)
    {
        parent::__construct('Unable to write Cachet settings', $e);
    }
}
