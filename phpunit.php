<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cmevans@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard;

use PHP_CodeSniffer\Tests\TestSuite as CodeSnifferTestSuite;
use PHP_CodeSniffer\Util\Standards;
use PHP_CodeSniffer\Autoload;
use PHPUnit\Framework\TestSuite;

/**
 * Test suite for the West coding standard.
 */
class AllTests
{
    /**
     * Add all PHP_CodeSniffer test suites into a single test suite.
     *
     * @return \PHPUnit\Framework\TestSuite
     */
    public static function suite()
    {
        $GLOBALS['PHP_CODESNIFFER_STANDARD_DIRS'] = [];
        $GLOBALS['PHP_CODESNIFFER_TEST_DIRS'] = [];

        // Use a special PHP_CodeSniffer test suite so that we can
        // unset our autoload function after the run.
        $suite = new CodeSnifferTestSuite('West');

        $suite->addTest(self::sniff_suite());

        return $suite;
    }

    /**
     * Add all sniff unit tests into a test suite.
     *
     * Sniff unit tests are found by recursing through the 'Tests' directory
     * of each installed coding standard.
     *
     * @return \PHPUnit\Framework\TestSuite
     */
    public static function sniff_suite()
    {
        $GLOBALS['PHP_CODESNIFFER_SNIFF_CODES']   = [];
        $GLOBALS['PHP_CODESNIFFER_FIXABLE_CODES'] = [];

        $suite = new TestSuite('West PHP CodeSniffer Standards');

        $installedStandards = self::getInstalledStandardDetails();


        foreach ($installedStandards as $standard => $details) {
            //Autoload::addSearchPath($details['path'], $details['namespace']);

            $testPath = $details['path'];
            $testsDir = $testPath . DIRECTORY_SEPARATOR . 'Tests' . DIRECTORY_SEPARATOR;
            if (is_dir($testsDir) === false) {
                // No tests for this standard.
                continue;
            }

            $di = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($testsDir));
            foreach ($di as $file) {
                // Skip hidden files.
                if (substr($file->getFilename(), 0, 1) === '.') {
                    continue;
                }

                // Tests must have the extension 'php'.
                $parts = explode('.', $file);
                $ext   = array_pop($parts);
                if ($ext !== 'php') {
                    continue;
                }

                $className = Autoload::loadFile($file->getPathname());
                $GLOBALS['PHP_CODESNIFFER_STANDARD_DIRS'][$className] = $details['path'];
                $GLOBALS['PHP_CODESNIFFER_TEST_DIRS'][$className]     = $testsDir;
                $suite->addTestSuite($className);
            }
        }

        return $suite;
    }

    /**
     * Get the details of all coding standards installed.
     *
     * @return array
     * @see    Standards::getInstalledStandardDetails()
     */
    protected static function getInstalledStandardDetails()
    {
        return Standards::getInstalledStandardDetails(true, realpath(__DIR__));
    }
}
