<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Exceptions\Displayers;

use Throwable;
use GrahamCampbell\Exceptions\Displayer\DisplayerInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpFoundation\Response;

class ThrottleDisplayer implements DisplayerInterface
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new redirect displayer instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the error response associated with the given exception.
     *
     * @param \Throwable $exception
     * @param string     $id
     * @param int        $code
     * @param string[]   $headers
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function display(Throwable $exception, string $id, int $code, array $headers) : Response
    {
        return cachet_redirect('auth.login')->withError(trans('forms.login.rate-limit'));
    }

    /**
     * Get the supported content type.
     *
     * @return string
     */
    public function contentType() : string
    {
        return 'text/html';
    }

    /**
     * Can we display the exception?
     *
     * @param \Throwable $original
     * @param \Throwable $transformed
     * @param int        $code
     *
     * @return bool
     */
    public function canDisplay(Throwable $original, Throwable $transformed, int $code) : bool
    {
        return $transformed instanceof TooManyRequestsHttpException && $this->request->is('auth*');
    }

    /**
     * Do we provide verbose information about the exception?
     *
     * @return bool
     */
    public function isVerbose() : bool
    {
        return false;
    }
}
