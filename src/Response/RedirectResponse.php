<?php
namespace Baguette\Response;
use Baguette;

/**
 * Redirect Response class
 *
 * @package   Baguette\Response
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2015 USAMI Kenta
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
final class RedirectResponse implements ResponseInterface
{
    private static $REDIRECT_STATUS_CODES = [
        301, // Moved Permanently
        302, // Found
        303, // See Other
        307, // Temporary Redirect
        308, // Permanent Redirect
    ];

    /** @var string */
    private $location;
    /** @var array  */
    private $params;
    /** @var int */
    private $status_code;

    /**
     * @param string $location
     * @param array  $params
     * @param int    $status_code
     */
    public function __construct($location, array $params = [], $status_code = null)
    {
        $this->location    = $location;
        $this->params      = $params;
        $this->status_code = ($status_code === null) ? 302 : $status_code;

        if (in_array($status_code, self::$REDIRECT_STATUS_CODES, true)) {
            $this->status_code = $status_code;
        } else {
            new \DomainException("$status_code is unexpected HTTP status code for redirect.");
        }
    }

    /**
     * @param  \Baguette\Application $_ is not used.
     * @return array[]
     */
    public function getResponseHeaders(Baguette\Application $_)
    {
        $location = $this->location;
        if ($this->params) {
            $location .= '?' . http_build_query($this->params);
        }

        return [
            ['Location: ' . $location, true, $this->status_code],
        ];
    }

    /**
     * @param  \Baguette\Application $_ is not used.
     * @return null
     */
    public function render(Baguette\Application $_)
    {
        return null;
    }
}