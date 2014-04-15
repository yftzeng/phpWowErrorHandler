<?php
/**
 * Wow! Error Handler
 *
 * PHP version 5
 *
 * @category Wow
 * @package  WowErrorHandler
 * @author   Tzeng, Yi-Feng <yftzeng@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/yftzeng/phpWowErrorHandler
 */

namespace Wow\Exception;

/**
 * Wow! Error Handler
 *
 * @category Wow
 * @package  WowErrorHandler
 * @author   Tzeng, Yi-Feng <yftzeng@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/yftzeng/phpWowErrorHandler
 */
class WowErrorHandler
{
    private $_logger;
    private $_handlerType;

    /**
     * @param int    $_handlerType handler type
     *                             {
     *                               0: log to file
     *                               1: error_log
     *                               2: throw exception
     *                               3: log to file & custom operation
     *                             }
     * @param string $logDir       handler type
     *
     * @comment construct
     *
     * @return void
     */
    public function __construct($_handlerType=0, $logDir=__DIR__)
    {
        $this->_handlerType = (int) $_handlerType;
        if ($this->_handlerType === 0 || $this->_handlerType === 3) {
            if ($logDir === __DIR__) {
                $logDir = __DIR__ . '/log';
            }
            $this->_logger = new \Wow\Log\WowLog($logDir, 0, 'WowErrorHandler');
        }

        set_exception_handler(array($this, 'exceptionHandler'));
        set_error_handler(array($this, 'errorHandler'));
        register_shutdown_function(array($this, 'shutdownHandler'));
    }

    /**
     * @comment destruct
     *
     * @return void
     */
    public function __destruct()
    {
        restore_exception_handler();
        restore_error_handler();
    }

    /**
     * @param string $errtype error type
     * @param int    $errno   error number
     * @param string $errstr  error string
     * @param string $errfile error filename
     * @param int    $errline line of error filename
     * @param mixed  $error   error object
     *
     * @comment handler class
     *
     * @return void
     */
    private function _handler(
        $errtype,
        $errno,
        $errstr,
        $errfile,
        $errline,
        $error = false
    ) {
        if ($this->_handlerType === 0 || $this->_handlerType === 3) {
            if ($errtype === 'EXCEPTION') {
                $this->_logger->warn(
                    $errtype .
                    ', FILE:' . $errfile . ':' . $errline .
                    ', ERRNO:' . $errno . ', ' . $errstr
                );
            } else if ($errtype === 'ERROR') {
                $this->_logger->error(
                    $errtype .
                    ', FILE:' . $errfile . ':' . $errline .
                    ', ERRNO:' . $errno . ', ' . $errstr
                );
            } else {
                $this->_logger->emer(
                    $errtype .
                    ', FILE:' . $errfile . ':' . $errline .
                    ', ERRNO:' . $errno . ', ' . $errstr
                );
            }

            if ($this->_handlerType === 3) {
                $this->customHandler(
                    $errtype,
                    $errno,
                    $errstr,
                    $errfile,
                    $errline,
                    $error
                );
            }
        } else if ($this->_handlerType === 1) {
            if ($error !== false) {
                error_log($errtype . ': '.print_r($error, true));
            } else {
                error_log(
                    $errtype .
                    ', FILE:' . $errfile . ':' . $errline .
                    ', ERRNO:' . $errno . ', ' . $errstr
                );
            }
        } else {
            throw New ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }

    /**
     * @param Exception $e exception object
     *
     * @comment exception handler
     *
     * @return void
     */
    public function exceptionHandler($e)
    {
        $errtype = 'EXCEPTION';
        $this->_handler(
            $errtype,
            $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e
        );
    }

    /**
     * @param int    $errno   error number
     * @param string $errstr  error string
     * @param string $errfile error filename
     * @param int    $errline line of error filename
     *
     * @comment error handler
     *
     * @return void
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $errtype = 'ERROR';
        $this->_handler($errtype, $errno, $errstr, $errfile, $errline);
    }

    /**
     * @comment shutdown handler
     *
     * @return void
     */
    public function shutdownHandler()
    {
        if (($e = error_get_last()) !== null) {
            $errtype = 'SHUTDOWN';
            $this->_handler($errtype, 0, $e['message'], $e['file'], $e['line'], $e);
        }
    }

    /**
     * @param string $errtype error type
     * @param int    $errno   error number
     * @param string $errstr  error string
     * @param string $errfile error filename
     * @param int    $errline line of error filename
     * @param mixed  $error   error object
     *
     * @comment custom handler
     *
     * @return void
     */
    public function customHandler(
        $errtype,
        $errno,
        $errstr,
        $errfile,
        $errline,
        $error = false
    ) {
        // TODO: complete your own handler
    }
}
